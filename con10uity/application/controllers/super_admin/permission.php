<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permission extends CI_Controller
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
		$this->load->database();
		$this->load->view("super_admin/add/add_permission");	
	}
	
	function edit($item_id = 0)
	{
		$this->load->database();
		$this->load->library('permission_lib');
		$data['item'] = $this->permission_lib->detail($item_id);
		
		$this->load->view("super_admin/edit/edit_permission",$data);	
	}
	
	function main($page = 0)
	{
		$this->load->library('permission_lib');

		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		//$page=$this->input->get('page');
		
		$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
		$href1=$_SERVER['PHP_SELF']."?";

		$list = $this->permission_lib->get_list($page,'',$sort,$ord);
	
		$data['pagination'] = $this->permission_lib->display_pagination('/super_admin/permission/main/',$page); 
	
		$data['list'] = $list;
	
		$this->load->view("super_admin/main/main_permission",$data);
	}
	
	function view()
	{
		$this->load->view("super_admin/view/view_permission");	
	}
	
	function do_add()
	{
		$this->load->library('permission_lib');
	
		$name = $this->input->post('name',TRUE);
		$group = $this->input->post('group',TRUE);
		
		
		$uploaddir = '';
		
		
		$this->permission_lib->add($name,$group);
		
		//$this->db->query("INSERT INTO `permission` (`name`,`group`) VALUES (".$this->escape($name).",".$this->escape($group).")");
		//echo "Added";
		redirect("/super_admin/permission/main/");
	}
	
	function do_update($permission_id)
	{
		$this->load->library('permission_lib');
		
		$name = $this->input->post('name',TRUE);
		$group = $this->input->post('group',TRUE);
		
		
		$uploaddir = '';
		
		
		$this->permission_lib->update($permission_id,$name,$group);
		//$this->db->query("UPDATE `permission` set `name`=".$this->escape($name).",`group`=".$this->escape($group)." WHERE `permission_id`='$permission_id'");
		//echo "Updated"; 
		redirect("/super_admin/permission/main/");
	}
	
	function do_delete($permission_id)
	{
		$this->load->library('permission_lib');
		$this->permission_lib->delete($permission_id);
		//$this->db->query("DELETE FROM `permission` WHERE `permission_id`='$permission_id'");
		//echo "Deleted"; 
		redirect("/super_admin/permission/main/");
	}
	
}

?>