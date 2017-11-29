<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help extends CI_Controller
{
	function __construct() {
       parent::__construct();
       $this->protect();
   }
   
	public function protect()
	{
		$is_admin = $this->session->userdata("superadmin"); 
		if(!$is_admin)
		{
			redirect("/super_admin/login/");
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
		$this->load->library('help_lib');
		$this->load->library('help_category_lib');
		
		$data['item'] = $this->help_lib->empty_row();
		
		$categories = $this->help_category_lib->get_list();
		$data['categories'] = itemsToTree($categories);
		
		$this->load->view("super_admin/add/add_help",$data);	
	}
	
	function edit($item_id = 0)
	{
		//$this->load->database();
		$this->load->library('help_lib');
		$this->load->library('help_category_lib');

		$data['item'] = $this->help_lib->detail($item_id);

		$categories = $this->help_category_lib->get_list();
		$data['categories'] = itemsToTree($categories);

		$this->load->view("super_admin/add/add_help",$data);	
	}
	
	function main($page = 0)
	{
		$this->load->library('help_lib');

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
			$filter = "`help`.`title` like '%".$search."%' or `help`.`description` like '%".$search."%'";
		
		$this->help_lib->items_per_page=$items_per_page;
		$list = $this->help_lib->get_list($page,$filter,$sort,$ord);
	
		$data['pagination'] = $this->help_lib->display_pagination('/super_admin/help/main/',$page,$filter); 
	
		$data['list'] = $list;
	
		
		if($output=='ajax'){
			$this->load->view("super_admin/main/main_help_ajax",$data);		
		}
		else {
			$this->load->view("super_admin/main/main_help",$data);
		}
	}
	
	function view()
	{
		$this->load->view("super_admin/view/view_help");	
	}
	
	function do_add()
	{
		$this->load->library('help_lib');
	
		$help_id = $this->input->post('help_id',TRUE);
			if($help_id!=''){
				$this->do_update($help_id);
				return;
			}		
		
	
		$help_category_id = $this->input->post('help_category_id',TRUE);
		$title = $this->input->post('title',TRUE);
		$tags = $this->input->post('tags',TRUE);
		$description = $this->input->post('description');
	
		$this->help_lib->add($help_category_id,$title,$tags,$description);
		redirect("/super_admin/help/main/");
	}
	
	function do_copy()
	{
		$id = $this->input->post("id",TRUE);
		$this->load->library('help_lib');
		$new_help_id = $this->help_lib->copy($id);
		echo $new_help_id;
		//echo $new_library_id;
	}	
	
	function do_update($help_id)
	{
		$this->load->library('help_lib');
		
		$help_category_id = $this->input->post('help_category_id',TRUE);
		$title = $this->input->post('title',TRUE);
		$tags = $this->input->post('tags',TRUE);
		$description = $this->input->post('description');
		
		$this->help_lib->update($help_id,$help_category_id,$title,$tags,$description);
		redirect("/super_admin/help/main/");
	}
	
	function do_delete($help_id)
	{
		$this->load->library('help_lib');
		$this->help_lib->delete($help_id);
		//$this->db->query("DELETE FROM `help` WHERE `help_id`='$help_id'");
		//echo "Deleted"; 
		redirect("/super_admin/help/main/");
	}
	
	function do_delete_multi($help_id=0)
	{
		$this->load->library('help_lib');
		
		$ids = $this->input->post("ids",TRUE);
		
		if($help_id==0)
		{
			$array = explode(',', $ids);
			if(is_array($array) and count($array)>0)
			foreach ($array as $value) {
				$this->help_lib->delete($value);
			}
		
		}else {
			$this->help_lib->delete($help_id);
			
		}
		
		$this->help_lib->delete($help_id);
		//$this->db->query("DELETE FROM `help` WHERE `help_id`='$help_id'");
		//echo "Deleted"; 
		redirect("/super_admin/help/main/");
	}
	
}

?>