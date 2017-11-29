<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role_permission extends CI_Controller
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
	
	function add($role_id = 0)
	{
		$this->load->database();
		$this->load->library("permission_lib");
		$this->load->library("role_permission_lib");
		$this->load->library("role_lib");
		
		/*$data['role_id'] = $role_id;
		$data['permissions'] = $this->permission_lib->get_permissions_by_group();
		$data['current_permissions'] = $this->role_permission_lib->get_permissions($role_id);
		$data['role_details'] = $this->role_lib->detail($role_id);
		*/
		$data['role_id'] = $role_id;
		$data['permissions'] = $this->permission_lib->get_permissions_by_group();
		$data['role_permissions'] = $this->permission_lib->get_role_permissions($role_id);
		$data['modules_included'] = array_keys($data['role_permissions']);
		$data['role_details'] = $this->role_lib->detail($role_id);
		
		$this->load->view("super_admin/add/add_role_permission",$data);	
	}
	
	function edit($item_id = 0)
	{
		$this->load->database();
		$this->load->library('role_permission_lib');
		$data['item'] = $this->role_permission_lib->detail($item_id);
		
		$this->load->view("super_admin/edit/edit_role_permission",$data);	
	}
	
	function main($role_id,$page = 0)
	{
		$this->add($role_id);
	}
	
	function do_add()
	{
		$this->load->library('role_permission_lib');
		$permissions = $this->input->post('permissions',TRUE);
		$role_id = $this->input->post('role_id',TRUE);
		
		$uploaddir = '';
		if(is_array($permissions) and count($permissions)>0)
		{
			$this->role_permission_lib->add_multi($role_id,$permissions);
		}
		//$this->db->query("INSERT INTO `role_permission` (`permission_id`) VALUES (".$this->escape($permission_id).")");
		//echo "Added";
		redirect("/super_admin/role_permission/main/".$role_id."/?updated=1");
	}
	

}

?>