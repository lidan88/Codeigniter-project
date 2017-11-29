<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help_category extends CI_Controller
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
		$this->load->library('help_category_lib');
		$data['item'] = $this->help_category_lib->empty_row();
		$items = $this->help_category_lib->get_list();
		
		$data['categories'] = $items;
		$tree = itemsToTree($data['categories']);
		
		$data['tree'] = $tree;
		
		$this->load->view("super_admin/add/add_help_category",$data);	
	}
	
	function edit($item_id = 0)
	{
		//$this->load->database();
		$this->load->library('help_category_lib');
		$data['item'] = $this->help_category_lib->detail($item_id);
		
		$this->load->view("super_admin/add/add_help_category",$data);	
	}
	
	function main($page = 0)
	{
		$this->load->library('help_category_lib');

		$items = $this->help_category_lib->get_list();
		$data['tree'] = itemsToTree($items);
		
		$this->load->view("super_admin/main/main_help_category",$data);
	}
	
	function view()
	{
		$this->load->view("super_admin/view/view_help_category");	
	}
	
	function do_add()
	{
		$this->load->library('help_category_lib');
	
		$id = $this->input->post('id',TRUE);
			if($id!=''){
				$this->do_update($id);
				return;
			}		
		
	
		$parent_id = $this->input->post('parent_id',TRUE);
		$title = $this->input->post('title',TRUE);
		
		
		$uploaddir = '';
		
		
		$this->help_category_lib->add($parent_id,$title);
		
		//$this->db->query("INSERT INTO `help_category` (`parent_id`,`title`) VALUES (".$this->escape($parent_id).",".$this->escape($title).")");
		//echo "Added";
		redirect("/super_admin/help_category/main/");
	}
	
	function do_update($id)
	{
		$this->load->library('help_category_lib');
		
		$parent_id = $this->input->post('parent_id',TRUE);
		$title = $this->input->post('title',TRUE);
		
		
		$uploaddir = '';
		
		
		$this->help_category_lib->update($id,$parent_id,$title);
		//$this->db->query("UPDATE `help_category` set `parent_id`=".$this->escape($parent_id).",`title`=".$this->escape($title)." WHERE `id`='$id'");
		//echo "Updated"; 
		redirect("/super_admin/help_category/main/");
	}
	
	function do_delete($id)
	{
		$this->load->library('help_category_lib');
		$this->help_category_lib->delete($id);
		//$this->db->query("DELETE FROM `help_category` WHERE `id`='$id'");
		//echo "Deleted"; 
		redirect("/super_admin/help_category/main/");
	}
	
	function do_delete_multi($id=0)
	{
		$this->load->library('help_category_lib');
		
		$ids = $this->input->post("ids",TRUE);
		
		if($id==0)
		{
			$array = explode(',', $ids);
			if(is_array($array) and count($array)>0)
			foreach ($array as $value) {
				$this->help_category_lib->delete($value);
			}
		
		}else {
			$this->help_category_lib->delete($id);
			
		}
		
		$this->help_category_lib->delete($id);
		//$this->db->query("DELETE FROM `help_category` WHERE `id`='$id'");
		//echo "Deleted"; 
		redirect("/super_admin/help_category/main/");
	}
	
	function set_sort_order()
	{
		$this->load->library('api_lib');
		
		$sort_order = preg_replace('/[^0-9,:#\[\]]/', '',$this->input->post('sort_order',TRUE));
		
		$sort_array = explode(',', $sort_order);
		foreach ($sort_array as $key => $value) {
			$temp = explode(OPT_SEPERATOR,$value);
			//$sql_query[] = "update library_items set item_order=".$temp[1]." where id=".$temp[0];
			//echo "update library_items set item_order=".$temp[1]." where id=".$temp[0];
			$this->api_lib->query("update help_category set `priority`=".$temp[1]." where id=".$temp[0]);
		}
		//print_r($this->input->post());
	}
	
}

?>