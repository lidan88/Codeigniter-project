<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Library_template extends CI_Controller
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
		$this->load->library('library_template_lib');
		$this->load->library("library_lib");
		$data['item'] = $this->library_template_lib->empty_row();
		
		$this->library_lib->items_per_page=100;
		$data['library_list'] = $this->library_lib->get_list(0,'company_id='.$this->company_id);
		
		$this->load->view("admin/add/add_library_template",$data);	
	}
	
	function edit($item_id = 0)
	{
		//$this->load->database();
		$this->load->library('library_template_lib');
		$this->load->library("library_lib");
		$data['item'] = $this->library_template_lib->detail($item_id);
		
		$this->library_lib->items_per_page=100;
		$data['library_list'] = $this->library_lib->get_list(0,'company_id='.$this->company_id);
		
		$this->load->view("admin/add/add_library_template",$data);	
	}
	
	function main($page = 0)
	{
		$this->load->library('library_template_lib');

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
		
		$this->library_template_lib->items_per_page=$items_per_page;
		$list = $this->library_template_lib->get_list($page,$filter,$sort,$ord);
	
		$data['pagination'] = $this->library_template_lib->display_pagination('/admin/library_template/main/',$page,$filter); 
	
		$data['list'] = $list;
			
		if($output=='ajax'){
			$this->load->view("admin/main/main_library_template_ajax",$data);		
		}
		else {
			$this->load->view("admin/main/main_library_template",$data);
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
	
	function view()
	{
		$this->load->view("admin/view/view_library_template");	
	}
	
	function do_add()
	{
		$this->load->library('library_template_lib');
	
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
		
		$this->library_template_lib->add($library_id,$name,$template,$sort_by,$order_by);
		//redirect("/admin/library_template/main/");
	}
	
	function do_update($id)
	{
		$this->load->library('library_template_lib');
		
		$library_id = $this->input->post('library_id',TRUE);
		$name = $this->input->post('name',TRUE);
		$template = $this->input->post('template',TRUE);
		$sort_by = $this->input->post('sort_by',TRUE);
		$order_by = $this->input->post('order_by',TRUE);	
		
		$this->library_template_lib->update($id,$library_id,$name,$template,$sort_by,$order_by);
		redirect("/admin/library_template/main/");
	}
	
	function do_delete($id)
	{
		$this->load->library('library_template_lib');
		$this->library_template_lib->delete($id);
		//$this->db->query("DELETE FROM `library_template` WHERE `id`='$id'");
		//echo "Deleted"; 
		redirect("/admin/library_template/main/");
	}
	
	function do_delete_multi($id=0)
	{
		$this->load->library('library_template_lib');
		
		$ids = $this->input->post("ids",TRUE);
		
		if($id==0)
		{
			$array = explode(',', $ids);
			if(is_array($array) and count($array)>0)
			foreach ($array as $value) {
				$this->library_template_lib->delete($value);
			}
		
		}else {
			$this->library_template_lib->delete($id);
			
		}
		
		$this->library_template_lib->delete($id);
		//$this->db->query("DELETE FROM `library_template` WHERE `id`='$id'");
		//echo "Deleted"; 
		redirect("/admin/library_template/main/");
	}
	
}

?>