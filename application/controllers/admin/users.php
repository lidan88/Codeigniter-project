<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller
{
	function __construct() {
       parent::__construct();
       $this->protect();
       
       $user_info = $this->session->userdata("user_info");
       $this->company_id = $user_info['company_id'];
       $this->company_id = preg_replace('/[^0-9]/', '', $this->company_id);
       
       if($this->company_id=='' or $this->company_id==0)
       	redirect("/admin/login/");
       
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
		$this->load->library("company_lib");
		$data['company_id'] = $this->company_id;
		$data['company_details'] = $this->company_lib->detail($this->company_id);
		
		$this->load->view("admin/add/add_users",$data);	
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
		
		$this->load->view("admin/edit/edit_users",$data);	
	}
	
	function main($page = 0)
	{
		$this->load->library('users_lib');
		$this->load->library('company_lib');
		
		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		$search=$this->input->get('search');
		$output=$this->input->get('output');
		
		//$page=$this->input->get('page');
		
		$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
		$href1=$_SERVER['PHP_SELF']."?";

		$filter = '`users`.company_id='.$this->company_id;
		if($search!='')
			$filter .= " and (`first_name` like '%".$search."%'  or `last_name` like '%".$search."%'  or `username` like '%".$search."%'  or `email` like '%".$search."%')"; 

		$list = $this->users_lib->get_list($page,$filter,$sort,$ord);
	
		$data['pagination'] = $this->users_lib->display_pagination('/admin/users/main/',$page,$filter); 
	
		$data['list'] = $list;
		$data['company_id'] = $this->company_id;
		$data['company_details'] = $this->company_lib->detail($this->company_id);
		
		if($output=='')
			$this->load->view("admin/main/main_users",$data);
		else
			$this->load->view("admin/main/main_users_ajax",$data);
			
	}
	
	function view()
	{
		$this->load->view("admin/view/view_users");	
	}
	
	function do_add()
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
		
		$this->users_lib->add($company_id,$role_id,$first_name,$last_name,$username,$password,$email,$aemail,$phone,$status,$timezone);
		
		//$this->db->query("INSERT INTO `users` (`company_id`,`role_id`,`first_name`,`last_name`,`username`,`password`,`email`,`aemail`,`phone`,`status`,`added`) VALUES (".$this->escape($company_id).",".$this->escape($role_id).",".$this->escape($first_name).",".$this->escape($last_name).",".$this->escape($username).",".$this->escape($password).",".$this->escape($email).",".$this->escape($aemail).",".$this->escape($phone).",".$this->escape($status).",".$this->escape($added).")");
		//echo "Added";
		redirect("/admin/users/main/");
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
		redirect("/admin/users/main/");
	}
	
	function do_delete($user_id)
	{
		$this->load->library('users_lib');
		$this->users_lib->delete($user_id);
		//$this->db->query("DELETE FROM `users` WHERE `user_id`='$user_id'");
		//echo "Deleted"; 
		redirect("/admin/users/main/");
	}
	
}

?>