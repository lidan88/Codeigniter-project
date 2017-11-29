<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plans extends CI_Controller
{
	function __construct() {
       parent::__construct();
       $this->protect();
       
       $this->load->database();
       
       $user_info = $this->session->userdata("user_info");
       $this->company_id = isset($user_info['company_id'])?$user_info['company_id']:0;
       $this->user_id = isset($user_info['user_id'])?$user_info['user_id']:0;
       $this->role_id = isset($user_info['role_id'])?$user_info['role_id']:0;
       
       setDBByCompany($this->db,$this->company_id);
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
		$this->load->library('plans_lib');
		$this->load->library('library_lib');
		
		$data['plan_id']='';
		$data['item'] = $this->plans_lib->empty_row();
		$data['list'] = $this->library_lib->return_rendered_items("PLANS");
		
		$this->load->view("admin/add/add_plans",$data);	
	}
	
	function edit($plan_id = 0)
	{
		//$this->load->database();
		$this->load->library('plans_lib');
		$this->load->library('library_lib');
		$data['plan_id']=$plan_id;
		$data['item'] = $this->plans_lib->detail($plan_id);
		$data['plan_items'] = $this->plans_lib->get_plan_items($plan_id);
		
		$data['list'] = $this->library_lib->return_rendered_items("PLANS",'',$data['item']['info']);
		
		$this->load->view("admin/add/add_plans",$data);	
	}
	
	function export($plan_id)
	{
		//ini_set('memory_limit','32M'); // boost the memory limit if it's low <img src="http://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
		
		$output = $this->input->get("output",TRUE);
		$output = $output==""?"html":$output;
		
		$this->load->library('plans_lib');
		$this->load->library('library_lib');
		$this->load->library('company_lib');
		$data['company']=$this->company_lib->detail($this->company_id);
		$data['plan_id']=$plan_id;
		$data['item'] = $this->plans_lib->detail($plan_id);
		$data['plan_items'] = $this->plans_lib->get_plan_items($plan_id);
		$data['list'] = $this->library_lib->return_rendered_items("PLANS",'',$data['item']['info']);
		/*echo '<pre>';
		print_r($data);
		echo '</pre>';
		die;*/
		
		
		if($output=="html")
		{
			//$this->load->view("admin/view/plan_export_title_page",$data);
			$this->load->view("admin/view/plan_export_library_items",$data);
		}
		else {
		    $this->load->library('mpdf_lib');
		    $pdf = $this->mpdf_lib->load('','A3',0,'',5,5,5,10,4,4,'P');
		    
		    $pdf->h2toc = array('H1'=>0, 'H2'=>1, 'H3'=>2);
		    $pdf->h2toc = array('H3'=>0);
		    
		    $html = $this->load->view("admin/view/plan_export_title_page",$data,true);
			$pdf->WriteHTML($html);
		    
		    $pdf->SetHTMLHeader("<table style='border-bottom:solid 1px #ccc'><tr><td style='color:#5b82b3;'>".$data['company']['name']."</td><td style='color:#5b82b3;text-align:right'>Continuity Pro</td></tr></table>");
		    
		    $pdf->TOCpagebreakByArray(array("links"=>1,
		    								"margin-top"=>"15px",
		    								"toc-orientation"=>"P",
		    								"toc-preHTML"=>"<h1>Table Of Contents</h1>"));
		    
		    //$pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822)); // Add a footer for good measure <img src="http://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
		    
		  //  $pdf->AddPageByArray(array('margin-top'=>'15px'));
			$html = $this->load->view("admin/view/plan_export_library_items",$data,true);
		    $pdf->SetHTMLFooter("<table style='border-top:solid 1px #ccc;'><tr><td width='33%' style='color:#5b82b3;padding-top:5px'>ContinuityPRO®</td><td width='33%' style='text-align:center;padding-top:5px'>{PAGENO}</td><td  width='33%' style='color:#5b82b3;text-align:right;padding-top:5px'>".date("M d,Y")."</td></tr></table>"); //DATE_RFC822
		    //$pdf->SetFooter('ContinuityPRO®|{PAGENO}|'.date(DATE_RFC822)); // Add a footer for good measure <img src="http://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
		    
		    $pdf->WriteHTML($html); // write the HTML into the PDF
		    
			//echo $html;
		    $pdf->Output(); // save to file because we can
		    //$pdf->Output($pdfFilePath, 'F'); // save to file because we can
	    }
	    
	}
	
	function edit_plan_item($plan_id,$plan_item_id)
	{
		$this->load->library("plans_lib");
		$this->load->library("modules_lib");
		$this->load->library("library_lib");
		
		$data['plan_id']=$plan_id;
		$data['plan_item_id']=$plan_item_id;
		$data['plan_item_details'] = $this->plans_lib->get_plan_item_details($plan_item_id);
		$data['modules_list'] = $this->modules_lib->get();
		
		if(($data['plan_item_details']['plan_type']=='lib' || $data['plan_item_details']['plan_type']=='gallery') and is_array($data['plan_item_details']['library_items']) and count($data['plan_item_details']['library_items'])>0)
		{
			$data['plan_selected_items_temp'] = $this->library_lib->get_user_data_by_ids(implode(',', $data['plan_item_details']['library_items']));
			foreach ($data['plan_selected_items_temp'] as $key => $psi) {
				$data['plan_selected_items_temp'][$key]['value'] = json_decode($data['plan_selected_items_temp'][$key]['value'],true);
			}
			
			foreach ($data['plan_selected_items_temp'] as $key => $psi) {
				$data['plan_selected_items'][] = $psi;
			}
		}else if($data['plan_item_details']['plan_type']=='cc' and is_array($data['plan_item_details']['library_items']) and count($data['plan_item_details']['library_items'])>0)
		{
			//die("Asd");
			$this->load->library("call_chain_lib");
			$temp = $this->call_chain_lib->get_user_data_by_ids(implode(',', $data['plan_item_details']['library_items']));
			$data['plan_selected_items'] = $temp;
		}
		else if($data['plan_item_details']['plan_type']=='ra' and is_array($data['plan_item_details']['library_items']) and count($data['plan_item_details']['library_items'])>0)
		{
			$this->load->library("risk_assessment_lib");
			//die("Asd");
			$temp = $this->risk_assessment_lib->get_list_by_ids(implode(',', $data['plan_item_details']['library_items']));
			$data['plan_selected_items'] = $temp;
		}		
		
		$this->load->view("admin/add/add_plan_item",$data);
	}
	
	
	function add_plan_item($plan_id)
	{
		$this->load->library("plans_lib");
		$this->load->library("modules_lib");
		$this->load->library("library_lib");
		$data['plan_id']=$plan_id;
		$data['plan_item_id']='';
		$data['plan_item_details'] = array("id"=>"",
											"plan_type"=>"",
											"title"=>"",
											"description"=>"",
											"footer"=>"",
											"library_id"=>"0",
											"library_items"=>"");
		
		$data['modules_list'] = $this->modules_lib->get();
		
		$data['plan_selected_items'] = array();
		
		$this->load->view("admin/add/add_plan_item",$data);
	}
	
	function main($page = 0)
	{
		$this->load->library('plans_lib');
		$this->load->library('library_lib');

		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		$search=$this->input->get('search');
		$items_per_page = $this->input->get('items_per_page');
		$items_per_page = $items_per_page==''?10:$items_per_page;
		$output=$this->input->get('output');
		$status=$this->input->get('status');
		
		//$page=$this->input->get('page');
		//$page = $page==''?0:$page;
		//$reset = $this->input->get('reset');
		
		$href=$_SERVER['PHP_SELF']."?items_per_page=$items_per_page&page=$page&sort=$sort&ord=$ord&search=".$search;
		$href1=$_SERVER['PHP_SELF']."?";
		
		$filter = '1=1';
		if($search!='')
			$filter = "`name` like '%".$search."%'";
		
		if($status!='')
			$filter .= " and p.`status`='".$status."'";
		
		
		$this->plans_lib->items_per_page=$items_per_page;
		$list = $this->plans_lib->get_list($page,$filter,$sort,$ord);
	
		$data['pagination'] = $this->plans_lib->display_pagination('/admin/plans/main/',$page,$filter); 
	
		$data['list'] = $list;

		$data['library_fields'] = $this->library_lib->explain("PLANS");
		
		if($output=='ajax'){
			$this->load->view("admin/main/main_plans_ajax",$data);		
		}
		else {
			$this->load->view("admin/main/main_plans",$data);
		}
	}
	
	function submit_for_approval($plan_id)
	{
		$this->load->library('plans_lib');
		$this->plans_lib->submit_for_approval($plan_id);
		echo "1";
	}
	
	function approve($plan_id)
	{
		$this->load->library('plans_lib');
		$this->plans_lib->approve($plan_id);
		echo "1";
	}

	function reject($plan_id)
	{
		$this->load->library("plans_lib");
		$notes = $this->input->post("notes",TRUE);
		$this->plans_lib->reject($plan_id,$notes);
		echo "1";
	}
	
	function manage_items($plan_id)
	{
		$this->load->library("plans_lib");
		$this->load->library("library_lib");
		$data['plan_id']=$plan_id;
		$this->load->view("admin/main/main_plan_items",$data);
	}
		
	function view()
	{
		$this->load->view("admin/view/view_plans");	
	}
	
	function do_add()
	{
		$this->load->library('plans_lib');
		$this->load->library('library_lib');
	
		$action = $this->input->post('action',TRUE);
		$plan_id = $this->input->post('plan_id',TRUE);
		$unique_id = $this->input->post('unique_id',TRUE);
		
		$submit = $this->input->post('Submit',TRUE);
		$library_value_id = $this->input->post('library_value_id',TRUE);
		$status = $this->input->post('status',TRUE);
		$itemsEncoded = $this->library_lib->encode_user_post_data();
		
		if($plan_id==''){
			$library_value_id = $this->library_lib->save_user_data($this->user_id,"PLANS",$this->company_id,$itemsEncoded,$unique_id);
			$plan_id = $this->plans_lib->add($library_value_id,$this->user_id);
			
			if($submit=='Save')
			{
				redirect("/admin/plans/edit/".$plan_id);
				return;
			}
			
			if($submit=='Save and New')
			{
				redirect("/admin/plans/add/");
				return;
			}
		}	
		else {
		
			if($submit=='Save' or $submit=='Save and New')	
			{
				$this->library_lib->update_user_data($library_value_id,$this->user_id,$itemsEncoded,$unique_id);
				$this->plans_lib->update($plan_id,$this->user_id);
			
				if($submit=='Save and New')
				{
					redirect("admin/plans/add/");
					return;
				}
			}
			else if($action=='Delete' or $submit=='Delete')
			{
				$this->plans_lib->delete($plan_id);
				redirect("/admin/plans/main/");
				return;
			}else if ($submit=='Copy') {
				$new_plan_id = $this->plans_lib->copy($plan_id);
				redirect("/admin/plans/edit/".$new_plan_id."/");
				return;
			}
			
		}
		
		redirect("/admin/plans/main/");
	}
	
	function do_copy()
	{
		$this->load->library('plans_lib');
		$plan_id = $this->input->post("id",TRUE);
		$this->plans_lib->copy($plan_id);
		echo "1";
	}
	
	function do_update($plan_id)
	{
		$this->load->library('plans_lib');
		$this->load->library('library_lib');
		
		$library_value_id = $this->input->post('library_value_id',TRUE);
		$status = $this->input->post('status',TRUE);
		
		$itemsEncoded = $this->library_lib->encode_user_post_data();
		$this->library_lib->update_user_data($library_value_id,$this->user_id,$itemsEncoded);
		$this->plans_lib->update($plan_id,$status,$this->user_id);
		redirect("/admin/plans/main/");
	}
	
	function save_plan_items()
	{
		$this->load->library('plans_lib');
		$plan_item_id = $this->input->post("plan_item_id",TRUE);
		$plan_id=$this->input->post("plan_id",TRUE);
		$plan_type=$this->input->post("plan_type",TRUE);
		$library_id=$this->input->post("library_id",TRUE);
		$template_id=$this->input->post("template_id",TRUE);
		$title=$this->input->post("title",TRUE);
		$description=$this->input->post("description",TRUE);
		$footer=$this->input->post("footer",TRUE);
		$library_items=$this->input->post("library_items",TRUE);
		
		//print_r($this->input->post());
		if($plan_item_id=="")
		{
			$this->plans_lib->save_plan_item($plan_id,$library_id,$plan_type,$template_id,$title,$description,$footer,$library_items);
		}else {
			$this->plans_lib->update_plan_item($plan_item_id,$plan_id,$library_id,$plan_type,$template_id,$title,$description,$footer,$library_items);
		}
	}
	
	function set_items_sort_order()
	{
		$this->load->library('api_lib');
		
		$sort_order = preg_replace('/[^0-9,:]/', '',$this->input->post('sort_order',TRUE));
		
		$sort_array = explode(',', $sort_order);
		foreach ($sort_array as $key => $value) {
			$temp = explode(":",$value);
			$this->api_lib->query("update plan_items set order_by=".$temp[1]." where id=".$temp[0]);
		}
	}
	
	function do_delete($plan_id)
	{
		$this->load->library('plans_lib');
		$this->plans_lib->delete($plan_id);
		redirect("/admin/plans/main/");
	}
	
	function do_delete_plan_item($plan_id,$plan_item_id)
	{
		$this->load->library('plans_lib');
		$this->plans_lib->delete_plan_item($plan_item_id);
		redirect("/admin/plans/edit/".$plan_id);
	}
	
	function do_delete_multi($plan_id=0)
	{
		$this->load->library('plans_lib');
		
		$ids = $this->input->post("ids",TRUE);
		
		if($plan_id==0)
		{
			$array = explode(',', $ids);
			if(is_array($array) and count($array)>0)
			foreach ($array as $value) {
				$this->plans_lib->delete($value);
			}
		
		}else {
			$this->plans_lib->delete($plan_id);
			
		}
		
		$this->plans_lib->delete($plan_id);
		redirect("/admin/plans/main/");
	}
	
}

?>