<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Risk_assessment extends CI_Controller
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
		$this->load->database();
		$this->load->view("admin/add/add_risk_assessment");	
	}
	
	function edit($item_id = 0)
	{
		$this->load->database();
		$this->load->library('risk_assessment_lib');
		$data['item'] = $this->risk_assessment_lib->detail($item_id);
		
		$this->load->view("admin/edit/edit_risk_assessment",$data);	
	}
	
	function main($page = 0)
	{
		$this->load->library('risk_assessment_lib');

		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		$search=$this->input->get('search');
		$status=$this->input->get('status');
		$output=$this->input->get('output');
		$type = $this->input->get('type')==''?'':$this->input->get('type');
		$data['page_type'] = $this->input->get('page_type')==''?'':$this->input->get('page_type');
		
		//$page=$this->input->get('page');
		$filter = 'ra.company_id='.$this->company_id;
		if($search!='')
			$filter .= " and `name` like '%".$search."%'"; 
		
		if($status!='')
			$filter .= " and ra.`status`='".$status."'";
		
		
		// plan item id - reset ay other search and only retrieve the ids selected.
		if($type=='pi')
		{
			$search = preg_replace('/[^0-9,]/', '', $search);
			$filter="`ra`.risk_assessment_id in (".$search.")";
		}
		
		$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
		$href1=$_SERVER['PHP_SELF']."?";

		$list = $this->risk_assessment_lib->get_list($page,$filter,$sort,$ord);
	
		$data['pagination'] = $this->risk_assessment_lib->display_pagination('/admin/risk_assessment/main/',$page); 
	
		$data['list'] = $list;
	
		if($output=='')
			$this->load->view("admin/main/main_risk_assessment",$data);	
		else if ($output=='pi')
			$this->load->view("admin/main/main_risk_assessment_pi",$data);
		else {
			$this->load->view("admin/main/main_risk_assessment_ajax",$data);
		}
		
		
	}
	
	function export_items()
	{
		$this->load->database();
		$this->load->dbutil();
		$this->load->library('library_lib');
		$this->load->helper('download');
		$this->load->helper('csv');
		$this->load->library('risk_assessment_lib');
		
		$ids = $this->input->post('ids',TRUE);
		$ids = ltrim ($ids,',');
		
		$items = $this->risk_assessment_lib->get_list_by_ids($ids);
		
		$header_array[]="Name";
		$header_array[]="Description";
		$header_array[]="Modified On";
		$header_array[]="Modified By";
		$header_array[]="Status";
		
		$temp_array = array($header_array);
		
		$add_to_csv = array_to_csv($temp_array);
		$add_to_csv .= array_to_csv($items);
		
		//$data = file_get_contents("/path/to/photo.jpg"); // Read the file's contents
		$name = 'download_'.rand(1000,9999).'.csv';
		
		force_download($name, $add_to_csv);
	}
	
	function copy($risk_assessment_id)
	{
		$this->load->library('risk_assessment_lib');
	
		$this->risk_assessment_lib->copy($risk_assessment_id);
		redirect("/admin/risk_assessment/main/");
	}

	function delete_user_values()
	{
		$ids = $this->input->post("ids",TRUE);
		$this->load->library("risk_assessment_lib");
		
		$this->risk_assessment_lib->delete_user_values($ids);
		echo json_encode(array("success"=>"1"));	
	}
	
	function do_add()
	{
		$this->load->library('risk_assessment_lib');
		$this->load->library('library_lib');
	
		$name = $this->input->post('name',TRUE);
		$description = $this->input->post('description',TRUE);
		$modified_by = $this->input->post('modified_by',TRUE);
		$status = $this->input->post('status',TRUE);
		
		$library_id = $this->library_lib->get_library_id_by_name($this->company_id,"Threat Assessment");
		$library_value_id = $this->library_lib->save_user_data($this->user_id,$library_id,$this->company_id,'');
		$risk_assessment_id = $this->risk_assessment_lib->add($name,$description,$this->user_id,$this->company_id,$library_value_id,$status);
		
		redirect("/admin/threat_analysis/main/".$risk_assessment_id."/");
	}
	
	function do_update($risk_assessment_id)
	{
		$this->load->library('risk_assessment_lib');
		
		$name = $this->input->post('name',TRUE);
		$description = $this->input->post('description',TRUE);
		$status = $this->input->post('status',TRUE);
		
		$this->risk_assessment_lib->update($risk_assessment_id,$name,$description,$this->user_id,$status);
		redirect("/admin/risk_assessment/main/");
	}
	
	function submit_for_approval($risk_assessment_id)
	{
		$this->load->library('risk_assessment_lib');
		$this->risk_assessment_lib->submit_for_approval($risk_assessment_id);
		echo "1";
	}
	
	function approve($risk_assessment_id)
	{
		$this->load->library('risk_assessment_lib');
		$this->risk_assessment_lib->approve($risk_assessment_id);
		echo "1";
	}
	
	function do_delete($risk_assessment_id)
	{
		$this->load->library('risk_assessment_lib');
		$this->risk_assessment_lib->delete($risk_assessment_id);
		//$this->db->query("DELETE FROM `risk_assessment` WHERE `risk_assessment_id`='$risk_assessment_id'");
		//echo "Deleted"; 
		redirect("/admin/risk_assessment/main/");
	}
	
}

?>