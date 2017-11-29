<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company extends CI_Controller
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
	
	function login_to($company_id)
	{
		$settings["company_id"]=$company_id;
		$settings["user_id"]=1;
		$settings["role_id"]=1;
		$this->session->set_userdata("user_info",$settings);
		redirect("/super_admin/users/main/".$company_id);
	}
	
	function index()
	{
		$this->main();	
	}
	
	function add()
	{
		$this->load->database();
		$this->load->view("super_admin/add/add_company");	
	}
	
	function edit($item_id = 0)
	{
		$this->load->database();
		$this->load->library('company_lib');
		$data['item'] = $this->company_lib->detail($item_id);
		
		$this->load->view("super_admin/edit/edit_company",$data);	
	}
	
	function main($page = 0)
	{
		$this->load->library('company_lib');

		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		//$page=$this->input->get('page');
		
		$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
		$href1=$_SERVER['PHP_SELF']."?";

		$list = $this->company_lib->get_list($page,'',$sort,$ord);
	
		$data['pagination'] = $this->company_lib->display_pagination('/super_admin/company/main/',$page); 
	
		$data['list'] = $list;
	
		$this->load->view("super_admin/main/main_company",$data);
	}
	
	function view()
	{
		$this->load->view("super_admin/view/view_company");	
	}
	
	function do_add()
	{
		$this->load->library('company_lib');
	
		$name = '';//$this->input->post('name',TRUE);
		$username = $this->input->post('username',TRUE);
		$notes = $this->input->post('notes',TRUE);
		$phone = $this->input->post('phone',TRUE);
		$address = $this->input->post('address',TRUE);
		$web = $this->input->post('web',TRUE);
		$main_contact = $this->input->post('main_contact',TRUE);
		
		if(!$this->company_lib->exists($username))
		{
			$companny_id = $this->company_lib->add($username,$notes,$phone,$address,$web,$main_contact);
			redirect("/super_admin/company/main/");
		}
		else {
			redirect("/super_admin/company/add/?error=1");
		}
	}
	
	function do_update($company_id)
	{
		$this->load->library('company_lib');
		
		$name = '';//$this->input->post('name',TRUE);
		$username = $this->input->post('username',TRUE);
		$notes = $this->input->post('notes',TRUE);
		$phone = $this->input->post('phone',TRUE);
		$address = $this->input->post('address',TRUE);
		$web = $this->input->post('web',TRUE);
		$main_contact = $this->input->post('main_contact',TRUE);
		
		$this->company_lib->update($company_id,$username,$notes,$phone,$address,$web,$main_contact);
		//$this->db->query("UPDATE `company` set `name`=".$this->escape($name).",`notes`=".$this->escape($notes).",`phone`=".$this->escape($phone).",`address`=".$this->escape($address).",`web`=".$this->escape($web).",`main_contact`=".$this->escape($main_contact).",`added`=".$this->escape($added)." WHERE `company_id`='$company_id'");
		//echo "Updated"; 
		redirect("/super_admin/company/main/");
	}
	
	function set_enable_status()
	{
		$company_id = $this->input->post("company_id",TRUE);
		$enabled = $this->input->post("enabled",TRUE);
		
		$this->load->library('company_lib');
		$this->company_lib->set_enable_status($company_id,$enabled);
	}
	
	function do_delete($company_id)
	{
		$this->load->library('company_lib');
		$this->company_lib->delete($company_id);
		//$this->db->query("DELETE FROM `company` WHERE `company_id`='$company_id'");
		//echo "Deleted"; 
		redirect("/super_admin/company/main/");
	}
	
}

?>