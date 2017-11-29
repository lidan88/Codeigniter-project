<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role_permission extends CI_Controller
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
	
	function add($role_id = 0)
	{
		$this->load->database();
		$this->load->library("permission_lib");
		$this->load->library("role_lib");
		
		$data['role_id'] = $role_id;
		$data['permissions'] = $this->permission_lib->get_permissions_by_group();
		$data['role_permissions'] = $this->permission_lib->get_role_permissions($role_id);
				
		$data['modules_included'] = array_keys($data['role_permissions']);
		
		$data['role_details'] = $this->role_lib->detail($role_id);
		
		//die;
		
		$this->load->view("admin/add/add_role_permission",$data);	
	}

	function main($role_id,$page = 0)
	{
		$this->add($role_id);
	}
	
	function do_add()
	{
		$this->load->library('permission_lib');
		$permissions = $this->input->post('permissions',TRUE);
		$role_id = $this->input->post('role_id',TRUE);
		
		if(is_array($permissions) and count($permissions)>0)
		{
			$this->permission_lib->adjust_role_permissions($role_id,$permissions);
			$this->permission_lib->get_user_permission_list(true);
		}

		redirect("/admin/role_permission/main/".$role_id."/?updated=1");
	}
	

}

?>