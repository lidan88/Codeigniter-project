<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sys_reports extends CI_Controller
{
	function __construct() {
       parent::__construct();
       $this->protect();
       
       $user_info = $this->session->userdata("user_info");
       $this->company_id = $user_info['company_id'];
       $this->user_id = $user_info['user_id'];
   }
   
	public function protect()
	{
		$is_admin = $this->session->userdata("is_admin"); 
		if(!$is_admin)
		{
			redirect("/admin/login/");
			die;
		}
		else
			return true;  
	}
	
	function index()
	{
		$this->main();	
	}
	
	function main($page = 0)
	{
		$this->load->view("admin/main/main_system_reports");
	}
	
	// Qualitative and Quantitative Impacts Over Time
	function qqiot()
	{
		$this->load->library("library_values");
		
		$items = $this->library_values->get_select_box($this->company_id,'BIA','Process Name');
		
		$newItems = array();
		
		if(is_array($items))
		{
			foreach ($items as $key => $value) {
				$newItems[opt2valueid($key)] = $value;
			}
		}else {
			die("no records available");
		}
		
		$data['items'] = $newItems;
		$this->load->view("admin/reports/_qqiot",$data);
	}
		
	function getImpactColumnDetails($impact = '',$show=true)
	{
		if($impact=='')
			$impact = $this->input->post('impact');
		
		switch ($impact) {
			case "financial":
				$impact = "Financial Impact";
				break;
			case "market_share":
				$impact = "Market Share Impact";
				break;
			case "reputation":
				$impact = "Reputation Impact";
				break;
			case "operational":
				$impact = "Operational Impact";
				break;
			case "legal":
				$impact = "Legal Impact";
				break;
		}
		
		$this->load->library("library_lib");
		$fields = $this->library_lib->rexplain("BIA",'var_value');
		
		$ret = '||';
		foreach ($fields as $impactType => $value) {
			//echo $impactType.' - '.$impact.'\n\r';
			if($impact==$impactType)
			{
				$ret = $value;
				break;
			}
		}
		//print_r($fields);		
		if($show)
			echo $ret;
		else {
			return $ret;
		}
	}
	
	function select($type)
	{
		switch ($type) {
			case "iot":
				// Impact over time select page.
				$this->load->view("admin/reports/_select-iot");
			break;
			case "rrot":
				// Required Resources Over Time
				$this->load->library("library_values");
				$this->load->library("library_lib");
				
				$data['fields'] = $this->library_lib->rexplain('BIA','var_value');
				
				$items = $this->library_values->get_select_box($this->company_id,'BIA','Process Name');
				
				$newItems = array();
				
				if(is_array($items))
				{
					foreach ($items as $key => $value) {
						$newItems[opt2valueid($key)] = $value;
					}
				}else {
					die("no records available");
				}
				
				$data['items'] = $newItems;
				$this->load->view("admin/reports/_select-rrot",$data);
			break;
			case "ra":
				// Risk Assessment
				$this->load->library("risk_assessment_lib");
				$this->load->library("threats_lib");
				
				$this->risk_assessment_lib->items_per_page = 100;
				$data['list'] = $this->risk_assessment_lib->get_list(0);
				$data['groups'] = $this->threats_lib->getGroups($this->company_id);
				
				$this->load->view("admin/reports/_select-ra",$data);
			break;
		}
	}
	
	function show($report_id = '',$a = '')
	{
		$this->load->library("library_lib");
		$this->load->library("library_values");

		$set = $this->input->post("set",TRUE);
		
		if($set=="1")
		{
			$this->session->set_userdata("print-header",$this->input->post("header"));
			$this->session->set_userdata("print-footer",$this->input->post("footer"));
			$this->session->set_userdata("print-title",$this->input->post("title"));
		
			$data['title'] = $this->input->post("title");
			$data['header'] = $this->input->post("header");
			$data['footer'] = $this->input->post("footer");
		}else {
			$data['title'] = $this->session->userdata("print-title");
			$data['header'] = $this->session->userdata("print-header");
			$data['footer'] = $this->session->userdata("print-footer");
		}
		
		if($report_id=="")
			$report_id=$this->input->post("report_id");
			
		$output = $this->input->get("output",TRUE);
		if($output=="")
			$output = $this->input->post("output",TRUE);
		$output = $output==""?"pdf":$output;
		
		switch($report_id){
			case 1:
				// RTO Gap Analysis
				$data['type'] = 'RTO';
				$data['items'] = $this->library_values->select_library_columns_with_data($this->company_id,'BIA');
			break;
			case 2:
				//RPO Gap Analysis
				$data['type'] = 'RPO';
				$data['items'] = $this->library_values->select_library_columns_with_data($this->company_id,'BIA');
			break;
			case 3:
				//Qualitative and Quantitative Impacts Over Time
				if($a=="")
					redirect("/admin/sys_reports/qqiot/");
			
				$valueId = $a;
				
				$details = $this->library_values->detail($valueId);
				
				$data['fields'] = $this->library_lib->rexplain($details['library_id'],'var_value');
				//$details['value'] = '{"104":"Sale","123":["5[#]12[#]3[#]Nabeel Khan"],"146":[["<$100"," $101-$1000"," $1","001-$5000","000"," >$10","000"]],"147":"","148":"","149":"","151":"Yes","152":"","153":"Yes","154":["5[#]12[#]3[#]Nabeel Khan"],"221":["27[#]104[#]5[#]Sale"],"222":["27[#]104[#]5[#]Sale"],"224":["31[#]215[#]1[#]Design Design"],"225":["31[#]215[#]2[#]Development Development"],"227":[[" 1","2","3","3","4","5","10"],[" 1","2","2","4","2","5","5"],["3"," 1"," 1","2","2","2","3"],[" 1"," 1","2","2","3","4","5"],[" 1"," 1"," 1","2","3","3","4"],[" 1","2","3","3","4","4","5"]],"228":[["Low"," Medium"," High"," Medium"," Medium"," High"," Severe"]],"229":"","230":[["Low"," Medium"," High"," High"," Severe"," Severe"," Severe"]],"231":"","232":[["Low","Low"," Medium"," High"," High"," Severe"," High"]],"233":"","234":[["Low"," Medium"," High"," High"," Severe","Low"," Severe"]],"235":"","236":"4 Hours","237":"24 Hours","240":["31[#]215[#]1[#]Design Design"]}';
				
				$details = $this->library_values->format_by_name_data($this->company_id,'BIA',$details['value']);
				
				$data['type'] = 'QQIOT';
				$data['items'] = $details;				
			break;
			case 4:
				// Financial Impact over time
				if($this->input->post("type")=="")
					redirect("/admin/sys_reports/select/iot");
				
				$data['type'] = 'FIOT';
				$data['impactDetails'] = $this->getImpactColumnDetails($this->input->post("type",TRUE),false);
				$data['type_key'] = ucwords(str_replace('_', ' ',$this->input->post("type",TRUE)));
				$data['timeframe'] = $this->input->post("timeframe",TRUE);
				$data['impact'] = strtoupper($this->input->post("impact",TRUE));
				
				$data['items'] = $this->library_values->select_library_columns_with_data($this->company_id,'BIA',$columns = false);
				
			break;
			case 5:
				//Required Resources Over Time
				if($this->input->post("process_id")=="")
					redirect("/admin/sys_reports/select/rrot/");
			
				$valueId = $this->input->post("process_id"); //$a;
				
				$details = $this->library_values->detail($valueId);
				
				$data['fields'] = $this->library_lib->rexplain($details['library_id'],'var_value');
				
				$details = $this->library_values->format_by_name_data($this->company_id,'BIA',$details['value'],true);
				
				$data['resourceFilter'] = $this->input->post("resource");
				$data['type'] = 'RROT';
				$data['items'] = $details;				
			break;	
			case 6:
				//Risk Assessment
				$this->load->library("threats_lib");
				$this->load->library("threat_analysis_lib");
				$this->load->library("risk_assessment_lib");
				
				if($this->input->post("risk_assessment_id")=="")
					redirect("/admin/sys_reports/select/ra/");
				
				$risk_assessment_id=$this->input->post('risk_assessment_id');
				$data['groupFilter'] = $this->input->post("group");
				$data['weightFilter'] = $this->input->post("weight");
				
				//TBD
				$items['threats_by_group'] = $this->threats_lib->get_list_by_group($this->company_id); //'company_id='.$this->company_id);


				$data['risk_assessment_detail'] = $this->risk_assessment_lib->detail($risk_assessment_id);

				$items['ra'] = $this->threat_analysis_lib->get_list($risk_assessment_id);

				$data['items']=$items;
				$data['type'] = 'RISKASSESSMENT';
			break;
			

		}
		
		
		if($output=="html")
		{
			$this->load->view("admin/reports/template",$data);
		}
		else {
		    $this->load->library('mpdf_lib');
		    $pdf = $this->mpdf_lib->load('','A3',0,'',5,5,15,10,4,4,'P');
		    
		    //$pdf->h2toc = array('H1'=>0, 'H2'=>1, 'H3'=>2);
		    $pdf->h2toc = array('H3'=>0);
		    
		    $pdf->SetHTMLHeader("<table style='border-bottom:solid 1px #ccc'><tr><td style='color:#5b82b3;'>".$data['company']['name']."</td><td style='color:#5b82b3;text-align:right'>Continuity Pro</td></tr></table>");
		    
			$html = $this->load->view("admin/reports/template",$data,true);
		    $pdf->SetHTMLFooter("<table style='border-top:solid 1px #ccc;'><tr><td width='33%' style='color:#5b82b3;padding-top:5px'>ContinuityPRO®</td><td width='33%' style='text-align:center;padding-top:5px'>{PAGENO}</td><td  width='33%' style='color:#5b82b3;text-align:right;padding-top:5px'>".date("M d,Y")."</td></tr></table>"); //DATE_RFC822
		    
		    $pdf->WriteHTML($html); // write the HTML into the PDF		    
		    $pdf->Output(); // save to file because we can
		    //$pdf->Output($pdfFilePath, 'F'); // save to file because we can
		}
		
		
	}
		
}

?>