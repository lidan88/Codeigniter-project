<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller
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
	
	function add()
	{
		//$this->load->database();
		$this->load->library('reports_lib');
		$this->load->library("library_lib");
		$data['item'] = $this->reports_lib->empty_row();
		
		$this->library_lib->items_per_page=100;
		$data['library_list'] = $this->library_lib->get_list(0,'company_id='.$this->company_id);
		
		$this->load->view("admin/add/add_report",$data);	
	}
	
	function edit($item_id = 0)
	{
		//$this->load->database();
		$this->load->library('reports_lib');
		$this->load->library("library_lib");
		$data['item'] = $this->reports_lib->detail($item_id);
		
		$this->library_lib->items_per_page=100;
		$data['library_list'] = $this->library_lib->get_list(0,'company_id='.$this->company_id);
		
		$this->load->view("admin/add/add_report",$data);	
	}
	
	function main($page = 0)
	{
		$this->load->library('reports_lib');

		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		$search=$this->input->get('search');
		$items_per_page = $this->input->get('items_per_page');
		$items_per_page = $items_per_page==''?10:$items_per_page;
		$output=$this->input->get('output');
		
		//$page=$this->input->get('page');
		//$page = $page==''?0:$page;
		//$reset = $this->input->get('reset');
		$href=$_SERVER['PHP_SELF']."?items_per_page=$items_per_page&page=$page&sort=$sort&ord=$ord&search=".$search;
		$href1=$_SERVER['PHP_SELF']."?";
		
		$filter = '1=1';
		if($search!='')
			$filter = "`name` like '%".$search."%'";
		
		$this->reports_lib->items_per_page=$items_per_page;
		$list = $this->reports_lib->get_list($page,$filter,$sort,$ord);
	
		$data['pagination'] = $this->reports_lib->display_pagination('/admin/reports/main/',$page,$filter); 
	
		$data['list'] = $list;
			
		if($output=='ajax'){
			$this->load->view("admin/main/main_library_template_ajax",$data);		
		}
		else {
			$this->load->view("admin/main/main_reports",$data);
		}
	}
	
	function get_templates_by_library_id()
	{
		$this->load->library("library_template_lib");
		$library_id = $this->input->post("library_id",TRUE);
		$list = $this->library_template_lib->get_templates_by_library_id($library_id);
		echo json_encode($list);
	}
	
	function get_columns() {
		$this->load->library("library_lib");
		$library_id = $this->input->post('library_id',TRUE);
		
		$items = $this->library_lib->explain($library_id);
		echo json_encode($items);
	}
	
	function do_add()
	{
		$this->load->library('reports_lib');
	
		/*echo '<pre>';
		print_r($this->input->post());
		echo '</pre>';
		die;*/
	
		$id = $this->input->post('id',TRUE);
		if($id!=''){
			$this->do_update($id);
			return;
		}		
		
		$library_id = $this->input->post('library_id',TRUE);
		$name = $this->input->post('name',TRUE);
		$template = $this->input->post('template',TRUE);
		$sort_by = $this->input->post('sort_by',TRUE);
		$order_by = $this->input->post('order_by',TRUE);	
		$filter_by = $this->input->post('filter_by',TRUE);	

		$report_id = $this->reports_lib->add($library_id,$name,$template,$sort_by,$order_by,$filter_by);
		echo $report_id;
		//redirect("/admin/library_template/main/");
	}
	
	function do_update($id)
	{
		$this->load->library('reports_lib');
		
		$library_id = $this->input->post('library_id',TRUE);
		$name = $this->input->post('name',TRUE);
		$template = $this->input->post('template',TRUE);
		$sort_by = $this->input->post('sort_by',TRUE);
		$order_by = $this->input->post('order_by',TRUE);
		$filter_by = $this->input->post('filter_by',TRUE);	
		
		$this->reports_lib->update($id,$library_id,$name,$template,$sort_by,$order_by,$filter_by);
		echo $id;
		//redirect("/admin/reports/main/");
	}
	
	function do_delete($id)
	{
		$this->load->library('reports_lib');
		$this->reports_lib->delete($id);
		//$this->db->query("DELETE FROM `library_template` WHERE `id`='$id'");
		//echo "Deleted"; 
		//redirect("/admin/reports/main/");
	}
	
	function do_delete_multi($id=0)
	{
		$this->load->library('reports_lib');
		
		$ids = $this->input->post("ids",TRUE);
		
		if($id==0)
		{
			$array = explode(',', $ids);
			if(is_array($array) and count($array)>0)
			foreach ($array as $value) {
				$this->reports_lib->delete($value);
			}
		
		}else {
			$this->reports_lib->delete($id);
			
		}
		
		$this->reports_lib->delete($id);
		//$this->db->query("DELETE FROM `library_template` WHERE `id`='$id'");
		//echo "Deleted"; 
		redirect("/admin/reports/main/");
	}
	
	function export($report_id)
	{
		//ini_set('memory_limit','32M'); // boost the memory limit if it's low <img src="http://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
		
		$output = $this->input->get("output",TRUE);
		$output = $output==""?"html":$output;
		
		$this->load->library('reports_lib');
		$this->load->library('library_lib');
		$this->load->library('company_lib');
		$this->load->library('library_values');
		$data['company']=$this->company_lib->detail($this->company_id);
		
		$data['report_id'] = $report_id;
		$data['item'] = $this->reports_lib->detail($report_id);
		
		$search = '';
		if(is_array($data['item']['filter_by']))
		{
			foreach ($data['item']['filter_by'] as $key => $value) {
					//$v = json_decode($value,true);
					$search[] = $value['c'].OPT_SEPERATOR.$value['f'];
					//print_r($value).'<br />';
			}
		}
		//die;
		
		$sort_by = explode(':',$data['item']['sort_by']);
		if(isset($sort_by[1]))
		{
			$sort_by = $sort_by[1];
		}
		else if(count($sort_by)>0)
		{
			$sort_by = '';
		}
		
		$order_by = $data['item']['order_by']==''?'ASC':$data['item']['order_by'];
		
		//$columns = false,$search = '',$sort = '',$ord = 'ASC'
		$data['data'] = $this->library_values->select_library_columns_with_data($data['item']['company_id'],
																$data['item']['library_id'],
																false,
																$search, //"20":"Nabeel"
																$sort_by,
																$order_by);
		
		//print_r($data['data'] );
		//die;
		if($output=="html")
		{
			$this->load->view("admin/view/export_report",$data);
		}
		else {
		    $this->load->library('mpdf_lib');
		    $pdf = $this->mpdf_lib->load('','A3',0,'',5,5,5,10,4,4,'P');
		    
		    //$pdf->h2toc = array('H1'=>0, 'H2'=>1, 'H3'=>2);
		    $pdf->h2toc = array('H3'=>0);
		    
		    $html = $this->load->view("admin/view/export_report_title_page",$data,true);
		    $pdf->WriteHTML($html);
		    
		    $pdf->SetHTMLHeader("<table style='border-bottom:solid 1px #ccc'><tr><td style='color:#5b82b3;'>".$data['company']['name']."</td><td style='color:#5b82b3;text-align:right'>Continuity Pro</td></tr></table>");
		    
		    /*$pdf->TOCpagebreakByArray(array("links"=>1,
		    								"margin-top"=>"15px",
		    								"toc-orientation"=>"P",
		    								"toc-preHTML"=>"<h1>Table Of Contents</h1>"));
		    */
		    //$pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822)); // Add a footer for good measure <img src="http://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
		    
		  //  $pdf->AddPageByArray(array('margin-top'=>'15px'));
			$html = $this->load->view("admin/view/export_report",$data,true);
		    $pdf->SetHTMLFooter("<table style='border-top:solid 1px #ccc;'><tr><td width='33%' style='color:#5b82b3;padding-top:5px'>ContinuityPRO®</td><td width='33%' style='text-align:center;padding-top:5px'>{PAGENO}</td><td  width='33%' style='color:#5b82b3;text-align:right;padding-top:5px'>".date("M d,Y")."</td></tr></table>"); //DATE_RFC822
		    //$pdf->SetFooter('ContinuityPRO®|{PAGENO}|'.date(DATE_RFC822)); // Add a footer for good measure <img src="http://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
		    
		    $pdf->WriteHTML($html); // write the HTML into the PDF
		    
		    $pdf->Output(); // save to file because we can
		    //$pdf->Output($pdfFilePath, 'F'); // save to file because we can
	    }
	    
	}
	
}

?>