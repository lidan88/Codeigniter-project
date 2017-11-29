<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller
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
	
	function add($company_id)
	{
		$this->load->database();
		$this->load->library("company_lib");
		$data['company_id'] = $company_id;
		$data['company_details'] = $this->company_lib->detail($company_id);
		
		$this->load->view("super_admin/add/add_users",$data);	
	}
	
	function edit($item_id = 0)
	{
		$this->load->database();
		$this->load->library('users_lib');
		$this->load->library('company_lib');
		$data['item'] = $this->users_lib->detail($item_id);
		
		$company_id = $data['item']['company_id'];
		$data['company_id'] = $company_id;
		$data['company_details'] = $this->company_lib->detail($company_id);
		
		$this->load->view("super_admin/edit/edit_users",$data);	
	}
	
	function main($company_id,$page = 0)
	{
		$this->load->library('users_lib');
		$this->load->library('company_lib');
		//$this->users_lib->change_db($company_id);

		$company_id = preg_replace('/[^0-9]/', '', $company_id);
		
		if($company_id=='' or $company_id==0)
			redirect("/super_admin/company/main/");

		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		$search=$this->input->get('search');
		$output=$this->input->get('output');
		
		$filter = '`users`.company_id='.$company_id;
		if($search!='')
			$filter .= " and (`first_name` like '%".$search."%'  or `last_name` like '%".$search."%'  or `username` like '%".$search."%'  or `email` like '%".$search."%')"; 
		
		//$page=$this->input->get('page');
		
		$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
		$href1=$_SERVER['PHP_SELF']."?";

		$list = $this->users_lib->get_list($page,$filter,$sort,$ord);
	
		$data['pagination'] = $this->users_lib->display_pagination('/super_admin/users/main/',$page,$filter); 
	
		$data['list'] = $list;
		$data['company_id'] = $company_id;
		$data['company_details'] = $this->company_lib->detail($company_id);
		
		if($output=='')
			$this->load->view("super_admin/main/main_users",$data);
		else
			$this->load->view("super_admin/main/main_users_ajax",$data);
			
	}
	
	function view()
	{
		$this->load->view("super_admin/view/view_users");	
	}
	
	function do_add()
	{
		$this->load->library('users_lib');
	
		$company_id = $this->input->post('company_id',TRUE);
		$this->users_lib->change_db($company_id);

		$role_id = $this->input->post('role_id',TRUE);
		$first_name = $this->input->post('first_name',TRUE);
		$last_name = $this->input->post('last_name',TRUE);
		$username = $this->input->post('username',TRUE);
		$password = $this->input->post('password',TRUE);
		$email = $this->input->post('email',TRUE);
		$aemail = $this->input->post('aemail',TRUE);
		$phone = $this->input->post('phone',TRUE);
		$timezone = $this->input->post('timezone',TRUE);
		
		$status = $this->input->post('status',TRUE);
		
		$uploaddir = '';
		
		
		$this->users_lib->add($company_id,$role_id,$first_name,$last_name,$username,$password,$email,$aemail,$phone,$status,$timezone);
		
		//$this->db->query("INSERT INTO `users` (`company_id`,`role_id`,`first_name`,`last_name`,`username`,`password`,`email`,`aemail`,`phone`,`status`,`added`) VALUES (".$this->escape($company_id).",".$this->escape($role_id).",".$this->escape($first_name).",".$this->escape($last_name).",".$this->escape($username).",".$this->escape($password).",".$this->escape($email).",".$this->escape($aemail).",".$this->escape($phone).",".$this->escape($status).",".$this->escape($added).")");
		//echo "Added";
		redirect("/super_admin/users/main/".$company_id.'/');
	}
	
	function do_update($user_id)
	{
		$this->load->library('users_lib');
		
		$company_id = $this->input->post('company_id',TRUE);
		$role_id = $this->input->post('role_id',TRUE);
		$first_name = $this->input->post('first_name',TRUE);
		$last_name = $this->input->post('last_name',TRUE);
		$username = $this->input->post('username',TRUE);
		$password = $this->input->post('password',TRUE);
		$email = $this->input->post('email',TRUE);
		$aemail = $this->input->post('aemail',TRUE);
		$phone = $this->input->post('phone',TRUE);
		$timezone = $this->input->post('timezone',TRUE);
		
		$status = $this->input->post('status',TRUE);
				
		$this->users_lib->update($user_id,$company_id,$role_id,$first_name,$last_name,$username,$password,$email,$aemail,$phone,$status,$timezone);
		//$this->db->query("UPDATE `users` set `company_id`=".$this->escape($company_id).",`role_id`=".$this->escape($role_id).",`first_name`=".$this->escape($first_name).",`last_name`=".$this->escape($last_name).",`username`=".$this->escape($username).",`password`=".$this->escape($password).",`email`=".$this->escape($email).",`aemail`=".$this->escape($aemail).",`phone`=".$this->escape($phone).",`status`=".$this->escape($status).",`added`=".$this->escape($added)." WHERE `user_id`='$user_id'");
		//echo "Updated"; 
		redirect("/super_admin/users/main/".$company_id.'/');
	}
	
	function do_delete($user_id)
	{
		$this->load->library('users_lib');
		$this->users_lib->delete($user_id);
		//$this->db->query("DELETE FROM `users` WHERE `user_id`='$user_id'");
		//echo "Deleted"; 
		redirect("/super_admin/users/main/");
	}
	
}

?>