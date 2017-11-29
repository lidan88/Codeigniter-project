<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function index()
	{
		
	}
	
	public function al($company_id = 0)
	{
		if(is_numeric($company_id) and $company_id>0)
		{
			$this->load->library("api_lib");
			// change db to that of companies
			$this->api_lib->change_db($company_id);
			$response = $this->api_lib->row("select count(*) as cnt from users where company_id=".$company_id." and `username`='admin'");
			if($response['cnt']>0)
			{
				$user_info = $this->api_lib->row("select u.*,c.logo,c.enabled from users u,company c where u.company_id=c.company_id and u.company_id=".$company_id." and u.`username`='admin'");
				
				if(!$user_info)
				{
					redirect("/home/login/?c=".$company."&error=2");
					die;
				}
				
				if($user_info['enabled']==0)
				{
					redirect("/home/login/?c=".$company."&error=4");
					die;
				}
				
				$this->session->set_userdata("user_info",$user_info);
				$this->session->set_userdata("is_admin",1);
				$this->session->unset_userdata("superadmin");
				redirect("/home/");
			}
		}
	}
	
	public function login()
	{
		//$host = getHost();
		$session_all_data = $this->session->all_userdata();
		if(isset($session_all_data['remember_me']) && $session_all_data['remember_me'] =='1')
		{
			$this->session->set_userdata("is_admin",1);
			redirect("/home/");
		}
		else
		{
		$company = $this->input->post('company',TRUE);
		$username = $this->input->post('username',TRUE);
		$password = $this->input->post('password',TRUE);
		
		if($username=='')
		{
			$username = urldecode($this->input->get('username',TRUE));
			$password = urldecode($this->input->get('password',TRUE));
			$company = urldecode($this->input->get('company',TRUE));
		}
		$company = preg_replace('/[^0-9a-zA-Z\.\-\_]/', '', $company);
		$username = preg_replace('/[^0-9a-zA-Z]/','',$username);
		$password = preg_replace('/[^0-9a-zA-Z\@\#\$\%\^\&\*\(\)\~\=]/','',$password);
		
		$this->load->library("api_lib");

		//$this->api_lib->debug_db=true;
		$company_info = $this->api_lib->row("select `company_id` from `company` where `username`=".$this->api_lib->escape($company));
		
		if(!$company_info)
		{
			redirect("/home/login/?c=".$company."&error=1");
		}
		
		$company_id = $company_info['company_id'];
		
		// change db to that of companies
		$this->api_lib->change_db($company_id);
		
		$response = $this->api_lib->row("select count(*) as cnt from users where company_id=".$company_id." and `username`=".$this->api_lib->escape($username)." and `password`=".$this->api_lib->escape($password));

		if($response['cnt']>0)
		{
			$user_info = $this->api_lib->row("select u.*,c.logo,c.enabled from users u,company c where u.company_id=c.company_id and u.company_id=".$company_id." and u.`username`=".$this->api_lib->escape($username)." and u.`password`=".$this->api_lib->escape($password));
			
			if(!$user_info)
			{
				redirect("/home/login/?c=".$company."&error=2");
				die;
			}
			
			if($user_info['enabled']==0)
			{
				redirect("/home/login/?c=".$company."&error=4");
				die;
			}
			
			$remember = $this->input->post('remember_me',TRUE);
			if($remember)
			{
				$this->session->set_userdata('remember_me',1);
			}
			$this->session->set_userdata("user_info",$user_info);
			$this->session->set_userdata("is_admin",1);
			$this->session->unset_userdata("superadmin");
			redirect("/home/");
		}
		else {
			redirect("/home/login/?c=".$company."&error=3");
		}
		}
	}
	
	public function logout() {
		$this->session->unset_userdata("admin");
		//$this->session->unset_userdata("user_info");
		redirect("/home/login/");
	}
	
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */