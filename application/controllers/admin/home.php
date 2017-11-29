<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

   function __construct() {
       parent::__construct();
       $this->protect();
   }
   
	public function protect()
	{
		$is_admin = $this->session->userdata("is_admin"); 
		if(!$is_admin)
		{
			redirect("/admin/login/");
			die;
			//return false;
		}
		else
			return true;  
	}
	
	public function logout()
	{
		$this->session->unset_userdata("is_admin"); 
		redirect("/admin/login/");
	}

	public function index()
	{
		redirect("/admin/users/");
		//$this->load->view('admin/dashboard');
	}
	
	public function test()
	{
		$this->load->view("admin/main_locations");	
	}
	
	public function maps()
	{
		$this->load->view("admin/main/maps");
	}
	
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */