<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Call_chain extends CI_Controller
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
		$this->load->library("library_values");
		
		$search = $this->input->get("search");
		$sort = $this->input->get("sort");
		$ord = $this->input->get("ord");
		$output = $this->input->get("output");
		//$data['employees'] = $this->library_values->get_select_box($this->company_id,'Employees','First Name');
		$data['employees'] = $this->library_values->select_library_columns_with_data($this->company_id,'Employees',array('First Name',
																												'Last Name',
																												'Department',
																												'Location'),$search,$sort,$ord);
		
		$data['call_chain'] = array("call_chain_id"=>0,"call_chain"=>"[]","name"=>"","description"=>"");
		if($output=='ajax')
			$this->load->view("admin/add/add_call_chain_ajax",$data);	
		else
			$this->load->view("admin/add/add_call_chain",$data);	
	}
	
	function edit($item_id = 0)
	{
		$this->load->database();
		$this->load->library('call_chain_lib');
		$this->load->library("library_values");
		
		$search = $this->input->get("search");
		$output = $this->input->get("output");
		$sort = $this->input->get("sort");
		$ord = $this->input->get("ord");
		
		//$data['employees'] = $this->library_values->get_select_box($this->company_id,'Employees','First Name');
		$data['employees'] = $this->library_values->select_library_columns_with_data($this->company_id,'Employees',array('First Name',
																												'Last Name',
																												'Department',
																												'Location'),$search,$sort,$ord);
		$data['call_chain'] = $this->call_chain_lib->detail($item_id);
		
		$this->load->view("admin/add/add_call_chain",$data);	
	}
	
	function main($page = 0)
	{
		$this->load->library('call_chain_lib');

		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		//$page=$this->input->get('page');
		$items_per_page = $this->input->get('items_per_page');
		$items_per_page = $items_per_page==''?10:$items_per_page;
		$search=$this->input->get('search');
		$output=$this->input->get('output');
		$type = $this->input->get('type')==''?'':$this->input->get('type');
		$data['page_type'] = $this->input->get('page_type')==''?'':$this->input->get('page_type');
		
		$this->call_chain_lib->items_per_page=$items_per_page;
		
		$href=$_SERVER['PHP_SELF']."?items_per_page=$items_per_page&page=$page&sort=$sort&ord=$ord&search=".$search;
		$href1=$_SERVER['PHP_SELF']."?";
	
		$filter = '1=1';
		if($search!='')
			$filter="`name` like '%".$search."%' or description like '%".$search."%'";
		
		// plan item id
		if($type=='pi')
		{
			$search = preg_replace('/[^0-9,]/', '', $search);
			$filter="`call_chain_id` in (".$search.")";
		}
		
		$list = $this->call_chain_lib->get_list($page,$filter,$sort,$ord);
	
		$data['pagination'] = $this->call_chain_lib->display_pagination('/admin/call_chain/main/',$page,$filter); 
	
		$data['list'] = $list;
	
		if($output=='')
			$this->load->view("admin/main/main_call_chain",$data);
		else if ($output=='pi')
			$this->load->view("admin/main/main_call_chain_pi",$data);
		else
			$this->load->view("admin/main/main_call_chain_ajax",$data);
			
	}
	
	function view()
	{
		$this->load->view("admin/view/view_call_chain");	
	}
	
	function do_add()
	{
		$this->load->library('call_chain_lib');
	
		$name = $this->input->post('name',TRUE);
		$save_type = $this->input->post('save_type',TRUE);
		
		$description = $this->input->post('description',TRUE);
		$call_chain = $this->input->post('call_chain',TRUE);
		$call_chain_id = $this->input->post('call_chain_id',TRUE);
		
		if($call_chain_id==0)
			$this->call_chain_lib->add($name,$description,$call_chain,$this->user_id);
		else {
		
			if($save_type=='copy')
			{
				$this->call_chain_lib->add($name.' Copy',$description,$call_chain,$this->user_id);
			}
			else {
				$this->call_chain_lib->update($call_chain_id,$name,$description,$call_chain,$this->user_id);
			}
			
		
		}
		
		echo "1";
		//redirect("/admin/call_chain/main/");
	}

	function export_items()
	{
		$this->load->database();
		$this->load->dbutil();
		$this->load->library('library_lib');
		$this->load->helper('download');
		$this->load->helper('csv');
		$this->load->library('call_chain_lib');
		
		$ids = $this->input->post('ids',TRUE);
		$ids = ltrim ($ids,',');
		
		$items = $this->call_chain_lib->get_user_data_by_ids($ids);
		
		$header_array[]="Name";
		$header_array[]="Description";
		$header_array[]="Modified On";
		$header_array[]="Modified By";
		
		$temp_array = array($header_array);
		
		$add_to_csv = array_to_csv($temp_array);
		$add_to_csv .= array_to_csv($items);
		
		//$data = file_get_contents("/path/to/photo.jpg"); // Read the file's contents
		$name = 'download_'.rand(1000,9999).'.csv';
		
		force_download($name, $add_to_csv);
	}
	
	function do_copy()
	{
		$this->load->library('call_chain_lib');
		
		$id = $this->input->post("id",TRUE);
		
		$this->call_chain_lib->copy($id);
		echo "1";
	}
	
	function do_delete($call_chain_id=0)
	{
		$this->load->library('call_chain_lib');
		
		$ids = $this->input->post("ids",TRUE);
		
		if($call_chain_id==0)
		{
			$array = explode(',', $ids);
			if(is_array($array) and count($array)>0)
			foreach ($array as $value) {
				$this->call_chain_lib->delete($value);
			}
		
		}else {
			$this->call_chain_lib->delete($call_chain_id);
			
		}
		//$this->db->query("DELETE FROM `call_chain` WHERE `call_chain_id`='$call_chain_id'");
		//echo "Deleted"; 
		redirect("/admin/call_chain/main/");
	}
	
}

?>