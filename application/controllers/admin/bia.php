<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bia extends CI_Controller
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
		$this->load->library("library_lib");
		$this->load->library('library_items_lib');
		$this->load->library("library_values");
		$this->load->library("dropdown_lib");
		$this->load->library('bia_lib');
		
		$data['item'] = $this->bia_lib->empty_row();
		$data['list'] = $this->library_items_lib->return_rendered_items($this->library_lib->get_library_id_by_name($this->company_id,'BIA'));
		 
		$this->load->view("admin/add/add_bia",$data);	
	}
	
	function edit($item_id = 0)
	{
		$this->load->library("library_lib");
		$this->load->library('library_items_lib');
		$this->load->library("library_values");
		$this->load->library("dropdown_lib");
		$this->load->library('bia_lib');
		
		$data['item'] = $this->bia_lib->detail($item_id);
		$survey = json_decode($data['item']['survey'],TRUE);
		$data['list'] = $this->library_items_lib->return_rendered_items($this->library_lib->get_library_id_by_name($this->company_id,'BIA'),'',$survey);
		
		$this->load->view("admin/add/add_bia",$data);	
	}
	
	function main($page = 0)
	{
		$this->load->library('bia_lib');

		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		$search=$this->input->get('search');
		$status=$this->input->get('status');
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
		
		if($status!='')
			$filter .= " and b.`status`='".$status."'";
		
		$this->bia_lib->items_per_page=$items_per_page;
		$list = $this->bia_lib->get_list($page,$this->company_id,$filter,$sort,$ord);
		
		$data['pagination'] = $this->bia_lib->display_pagination('/admin/bia/main/',$page,$filter); 
	
		$data['list'] = $list;
	
		
		if($output=='ajax'){
			$this->load->view("admin/main/main_bia_ajax",$data);		
		}
		else {
			$this->load->view("admin/main/main_bia",$data);
		}
	}
	
	function view()
	{
		$this->load->view("admin/view/view_bia");	
	}
	
	function do_add()
	{
		$this->load->library('bia_lib');
		$this->load->library('library_lib');
		$this->load->library('library_items_lib');
		
		$bia_id = $this->input->post('bia_id',TRUE);
		if($bia_id!=''){
			$this->do_update($bia_id);
			return;
		}		
	
		$status = 'In Progress';//$this->input->post('status',TRUE);
		$itemsEncoded = $this->library_items_lib->encode_user_post_data();
		
		$library_id = $this->library_lib->get_library_id_by_name($this->company_id,"BIA");
		$library_value_id = $this->library_lib->save_user_data($this->user_id,$library_id,$this->company_id,$itemsEncoded);
		$this->bia_lib->add($library_value_id,$status,$this->user_id);
		
		//$this->db->query("INSERT INTO `bia` (`name`,`description`,`conducted_by`,`category`,`status`,`modified_on`,`modified_by`) VALUES (".$this->escape($name).",".$this->escape($description).",".$this->escape($conducted_by).",".$this->escape($category).",".$this->escape($status).",".$this->escape($modified_on).",".$this->escape($modified_by).")");
		//echo "Added";
		redirect("/admin/bia/main/");
	}
	
	function do_copy()
	{
		$this->load->library('bia_lib');
		$id = $this->input->post("id",TRUE);
		$this->bia_lib->copy($id);
		echo "1";
	}
	
	function do_update($bia_id)
	{
		$this->load->library('bia_lib');
		$this->load->library('library_items_lib');
		
		$library_value_id = $this->input->post('library_value_id',TRUE);
		$status = $this->input->post('status',TRUE);
		$itemsEncoded = $this->library_items_lib->encode_user_post_data();
		
		$this->library_lib->update_user_data($library_value_id,$this->user_id,$itemsEncoded);
		$this->bia_lib->update($bia_id,$status,$this->user_id);
		
		redirect("/admin/bia/main/");
	}
	
	function do_delete($bia_id)
	{
		$this->load->library('bia_lib');
		$this->bia_lib->delete($bia_id);
		//$this->db->query("DELETE FROM `bia` WHERE `bia_id`='$bia_id'");
		//echo "Deleted"; 
		redirect("/admin/bia/main/");
	}
	
	function do_delete_multi($bia_id=0)
	{
		$this->load->library('bia_lib');
		
		$ids = $this->input->post("ids",TRUE);
		
		if($bia_id==0)
		{
			$array = explode(',', $ids);
			if(is_array($array) and count($array)>0)
			foreach ($array as $value) {
				$this->bia_lib->delete($value);
			}
		
		}else {
			$this->bia_lib->delete($bia_id);
			
		}
		
		$this->bia_lib->delete($bia_id);
		//$this->db->query("DELETE FROM `bia` WHERE `bia_id`='$bia_id'");
		//echo "Deleted"; 
		redirect("/admin/bia/main/");
	}
	
	function submit_for_approval($bia_id)
	{
		$this->load->library('bia_lib');
		$this->bia_lib->submit_for_approval($bia_id);
		echo "1";
	}
	
	function approve($bia_id)
	{
		$this->load->library('bia_lib');
		$this->bia_lib->approve($bia_id);
		echo "1";
	}
	
	function reject($bia_id)
	{
		$this->load->library('bia_lib');
		$this->bia_lib->reject($bia_id);
		echo "1";
	}
}

?>