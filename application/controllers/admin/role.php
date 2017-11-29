<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role extends CI_Controller
{
	function __construct() {
       parent::__construct();
       $this->protect();
       
       $user_info = $this->session->userdata("user_info");
       $this->company_id = $user_info['company_id'];
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
		$this->load->view("admin/add/add_role");	
	}
	
	function edit($item_id = 0)
	{
		$this->load->database();
		$this->load->library('role_lib');
		$data['item'] = $this->role_lib->detail($item_id);
		
		$this->load->view("admin/edit/edit_role",$data);	
	}
	
	function main($page = 0)
	{
		$this->load->library('role_lib');

		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		//$page=$this->input->get('page');
		
		$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
		$href1=$_SERVER['PHP_SELF']."?";

		$list = $this->role_lib->get_list($page,'company_id='.$this->company_id,$sort,$ord);
	
		$data['pagination'] = $this->role_lib->display_pagination('/admin/role/main/',$page); 
	
		$data['list'] = $list;
	
		$this->load->view("admin/main/main_role",$data);
	}
	
	function view()
	{
		$this->load->view("admin/view/view_role");	
	}
	
	function do_add()
	{
		$this->load->library('role_lib');
	
		$name = $this->input->post('name',TRUE);
		$uploaddir = '';
		$this->role_lib->add($name,$this->company_id);
		
		//$this->db->query("INSERT INTO `role` (`name`) VALUES (".$this->escape($name).")");
		//echo "Added";
		redirect("/admin/role/main/");
	}
	
	function do_update($role_id)
	{
		$this->load->library('role_lib');
		
		$name = $this->input->post('name',TRUE);
		
		
		$uploaddir = '';
		
		
		$this->role_lib->update($role_id,$name);
		//$this->db->query("UPDATE `role` set `name`=".$this->escape($name)." WHERE `role_id`='$role_id'");
		//echo "Updated"; 
		redirect("/admin/role/main/");
	}
	
	function do_delete($role_id)
	{
		$this->load->library('role_lib');
		$this->role_lib->delete($role_id);
		//$this->db->query("DELETE FROM `role` WHERE `role_id`='$role_id'");
		//echo "Deleted"; 
		redirect("/admin/role/main/");
	}
	
}

?>