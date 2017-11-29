<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {

   function __construct() {
       parent::__construct();
       $this->protect();
       
       $this->load->database();
       
       $user_info = $this->session->userdata("user_info");
       $this->company_id = isset($user_info['company_id'])?$user_info['company_id']:0;
       $this->user_id = isset($user_info['user_id'])?$user_info['user_id']:0;
       $this->role_id = isset($user_info['role_id'])?$user_info['role_id']:0;
       
       setDBByCompany($this->db,$this->company_id);
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
	
	public function index()
	{
		$this->load->library("users_lib");
		$data['item'] = $this->users_lib->detail($this->user_id);
		$this->load->view('admin/add/settings',$data);
	}
	
	function do_update()
	{
		$this->load->library('users_lib');
		
		$first_name = $this->input->post('first_name',TRUE);
		$last_name = $this->input->post('last_name',TRUE);
		$password = $this->input->post('password',TRUE);
		$email = $this->input->post('email',TRUE);
		$aemail = $this->input->post('aemail',TRUE);
		$phone = $this->input->post('phone',TRUE);
		$timezone = $this->input->post('timezone',TRUE);
		$default_view = $this->input->post('default_view',TRUE);
		
		$this->users_lib->update_settings($this->user_id,$first_name,$last_name,$password,$email,$aemail,$phone,$timezone,$default_view);
		redirect("/admin/settings/?updated=1");
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */