<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help extends CI_Controller {

	public function index($help_id = 0)
	{
		$search = $this->input->get("search",TRUE);
	
		if($help_id==0)
		{
			$this->list_all($search);
		}
		else {
			$this->help_details($help_id);
		}
	}
	
	
	private function help_details($help_id)
	{
		$this->load->library('help_lib');
		//$this->load->library('help_category_lib');
	
		$data['item'] = $this->help_lib->detail($help_id);
		
		$this->load->view("help_details",$data);
	}
	
	private function list_all($search = '')
	{
		$this->load->library('help_lib');
		$this->load->library('help_category_lib');
		
		$parent_id = $this->input->post("p",TRUE);
		
		$user_info = $this->session->userdata("user_info");
		$this->company_id = isset($user_info['company_id'])?$user_info['company_id']:0;
		$this->user_id = isset($user_info['user_id'])?$user_info['user_id']:0;
		$this->role_id = isset($user_info['role_id'])?$user_info['role_id']:0;
		
		$helpList = $this->help_lib->all($search);
		
		$helpByCategory = array();
		
		if(is_array($helpList))		
		foreach($helpList as $h)
		{
			$helpByCategory[$h['help_category_id']][] = $h;
		}
		
		$data['helpByCategory'] = $helpByCategory;
		$data['search'] = $search;
		$data['categories'] = $this->help_category_lib->get_list();
		
		setDBByCompany($this->db,$this->company_id);
		
		if(!$this->session->userdata("is_admin"))
		{
			$this->load->view('login');
		}
		else
			$this->load->view('help',$data);
			//redirect("/admin/home/");
	}
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/landing.php */