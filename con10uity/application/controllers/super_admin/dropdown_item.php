<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dropdown_item extends CI_Controller
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
	
	function add($dropdown_id = 0)
	{
		$this->load->database();
		$this->load->library("dropdown_lib");
		$data['dropdown_details'] = $this->dropdown_lib->detail($dropdown_id);
		
		$this->load->view("super_admin/add/add_dropdown_item",$data);	
	}
	
	function edit($item_id = 0)
	{
		$this->load->database();
		$this->load->library('dropdown_item_lib');
		$data['item'] = $this->dropdown_item_lib->detail($item_id);
		
		$this->load->library("dropdown_lib");
		$data['dropdown_details'] = $this->dropdown_lib->detail($data['item']['dropdown_id']);
		
		$this->load->view("super_admin/edit/edit_dropdown_item",$data);	
	}
	
	function main($dropdown_id = 0)
	{
		$page=0;
		$this->load->library('dropdown_lib');
		$this->load->library('dropdown_item_lib');

		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		//$page=$this->input->get('page');
		
		$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
		$href1=$_SERVER['PHP_SELF']."?";

		$list = $this->dropdown_item_lib->get_list($page,'`dropdown_item`.dropdown_id='.$dropdown_id,$sort,$ord);
	
		$data['dropdown_details'] = $this->dropdown_lib->detail($dropdown_id);
		
		$data['pagination'] = $this->dropdown_item_lib->display_pagination('/super_admin/dropdown_item/main/',$page,'dropdown_id='.$dropdown_id); 
	
		$data['list'] = $list;
	
		$this->load->view("super_admin/main/main_dropdown_item",$data);
	}
	
	function view()
	{
		$this->load->view("super_admin/view/view_dropdown_item");	
	}
	
	function do_add()
	{
		$this->load->library('dropdown_item_lib');
	
		$dropdown_id = $this->input->post('dropdown_id',TRUE);
		$name = $this->input->post('name',TRUE);
		$is_active = $this->input->post('is_active',TRUE);
		
		
		$uploaddir = '';
		
		
		$this->dropdown_item_lib->add($dropdown_id,$name,$is_active);
		
		//$this->db->query("INSERT INTO `dropdown_item` (`dropdown_id`,`name`,`is_active`) VALUES (".$this->escape($dropdown_id).",".$this->escape($name).",".$this->escape($is_active).")");
		//echo "Added";
		redirect("/super_admin/dropdown_item/main/".$dropdown_id."/");
	}
	
	function do_update($dropdown_item_id)
	{
		$this->load->library('dropdown_item_lib');
		
		$dropdown_id = $this->input->post('dropdown_id',TRUE);
		$name = $this->input->post('name',TRUE);
		$is_active = $this->input->post('is_active',TRUE);
		
		
		$uploaddir = '';
		
		
		$this->dropdown_item_lib->update($dropdown_item_id,$dropdown_id,$name,$is_active);
		//$this->db->query("UPDATE `dropdown_item` set `dropdown_id`=".$this->escape($dropdown_id).",`name`=".$this->escape($name).",`is_active`=".$this->escape($is_active)." WHERE `dropdown_item_id`='$dropdown_item_id'");
		//echo "Updated"; 
		redirect("/super_admin/dropdown_item/main/".$dropdown_id."/");
	}
	
	function do_delete($dropdown_item_id)
	{
		$this->load->library('dropdown_item_lib');
		$this->dropdown_item_lib->delete($dropdown_item_id);
		//$this->db->query("DELETE FROM `dropdown_item` WHERE `dropdown_item_id`='$dropdown_item_id'");
		//echo "Deleted"; 
		redirect("/super_admin/dropdown_item/main/");
	}
	
}

?>