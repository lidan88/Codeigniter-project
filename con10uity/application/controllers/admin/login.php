<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index()
	{
		if(!$this->session->userdata("is_admin"))
		{
			$this->load->view('login');
		}
		else
			$this->load->view('home');
			//redirect("/admin/home/");
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/landing.php */