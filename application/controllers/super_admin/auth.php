<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct() {
	   parent::__construct();
	}
	
	public function is_user_authorized()
	{
		$is_admin = $this->session->userdata("superadmin");
		if(! $is_admin)
		{
			return false;
		}
		else
			return true;  
	}
	
	
	public function index()
	{
		$this->login();
	}
	
	public function login()
	{
		if($this->is_user_authorized())
			redirect("/super_admin/company/");
		else
			$this->load->view('super_admin/login');
	}
	
	public function do_login()
	{

		$session_all_data = $this->session->all_userdata();
		if(isset($session_all_data['remember_me']) && $session_all_data['remember_me'] =='1')
		{
			$this->session->set_userdata("superadmin",1);
			redirect("/super_admin/company/");
		}
		else
		{
		
			$username = $this->input->post('username',TRUE);
			$password = $this->input->post('password',TRUE);
			
			
			$username = preg_replace('/[^0-9a-zA-Z]/','',$username);
			$password = preg_replace('/[^0-9a-zA-Z\@\#\$\%\^\&\*\(\)\~\=]/','',$password);
			
			$this->load->library("api_lib");

			//$this->api_lib->debug_db=true;		
			$response = $this->api_lib->row("select count(*) as cnt from `continuitypro`.super_admin where `username`=".$this->api_lib->escape($username)." and `password`=".$this->api_lib->escape($password));
			if($response['cnt']>0)
			{
				$remember = $this->input->post('remember_me',TRUE);
				if($remember)
				{
					$this->session->set_userdata('remember_me',1);
				}

				$this->session->set_userdata("superadmin",1);
				$this->session->unset_userdata("user_info");
				$this->session->unset_userdata("is_admin");
				redirect("/super_admin/auth/");
			}
			else
			{
				redirect("/super_admin/auth/login/?error=1");
			}
		}
	}
	
	public function logout()
	{
		$this->session->unset_userdata("superadmin");
		$this->session->unset_userdata("user_info");
		redirect("/super_admin/auth/login/");
	}
	public function forgetpassword()
	{
		$this->load->view('super_admin/forgetpassword');
	}	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */