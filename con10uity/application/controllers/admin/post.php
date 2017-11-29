<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends CI_Controller {

	public function login()
	{
		$this->load->library("api_lib");
		
		$user = preg_replace('/[^a-zA-Z]/i','',$this->input->post("user",TRUE));
		$password = preg_replace('/[^a-zA-Z0-9#\@!\$%\^\&\*\(\)]/i','',$this->input->post("password",TRUE));
		
		if($user!="" and $password!="")
		{
			$row = $this->api_lib->row("select * from `admin` WHERE user=".$this->api_lib->escape($user)." and pass=".$this->api_lib->escape($password));
	
			if(is_array($row) and count($row)>0)
			{
				$this->session->set_userdata("is_admin",1);
				$this->api_lib->query("update `admin` set last_login=now() WHERE user=".$this->api_lib->escape($user));
				redirect("/admin/home/");
			}
			else
				redirect("/admin/login/");
		}
	}

}

/* End of file Post.php */
/* Location: ./application/controllers/admin/post.php */