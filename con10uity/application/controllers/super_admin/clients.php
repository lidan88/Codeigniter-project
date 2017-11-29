<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clients extends CI_Controller
{
	function __construct() {
       parent::__construct();
       $this->is_user_authorized();
   }
   
	public function is_user_authorized()
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
		$data['company_id'] = $company_id;
		$this->load->view("super_admin/add/add_clients",$data);	
	}
	
	function edit($item_id = 0)
	{
		$this->load->library('clients_lib');
		$this->load->library('company_lib');
		$data['item'] = $this->clients_lib->detail($item_id);
		
		$company_id = $data['item']['company_id'];
		$data['company_id'] = $company_id;
		$data['company_details'] = $this->company_lib->detail($company_id);
		
		$this->load->view("super_admin/edit/edit_clients",$data);		
	}
	
	function main($company_id = 0,$page = 0)
	{
		
		$company_id = preg_replace('/[^0-9]/', '', $company_id);
		
		if($company_id=='' or $company_id==0)
			redirect("/super_admin/company/main/");
		
		$this->load->library('clients_lib');
		$this->load->library('company_lib');
		
		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		//$page=$this->input->get('page');
		
		$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
		$href1=$_SERVER['PHP_SELF']."?";

		$list = $this->clients_lib->get_list($page,'company_id='.$company_id,$sort,$ord);
	
		$data['pagination'] = $this->clients_lib->display_pagination('/super_admin/clients/main/'.$company_id.'/',$page); 
	
		$data['list'] = $list;
		$data['company_id'] = $company_id;
		$data['company_details'] = $this->company_lib->detail($company_id);
	
		$this->load->view("super_admin/main/main_clients",$data);
		
				
/*		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		$search=$this->input->get('search');
		$type=$this->input->get('type');
		$full_search=$this->input->get('full_search');
*/
	}
	
	function view()
	{
		$this->load->view("super_admin/view/view_user");	
	}
	
	function do_add()
	{
		$this->load->library('clients_lib');
		
		$first_name = $this->input->post('first_name',TRUE);
		$last_name = $this->input->post('last_name',TRUE);
		$username = $this->input->post('username',TRUE);
		$password = $this->input->post('password',TRUE);
		$email = $this->input->post('email',TRUE);
		$aemail = $this->input->post('aemail',TRUE);
		$company = $this->input->post('company',TRUE);
		$phone = $this->input->post('phone',TRUE);
		$address = $this->input->post('address',TRUE);
		$notes = $this->input->post('notes',TRUE);
		$status = $this->input->post('status',TRUE);
		$role_id = $this->input->post('role_id',TRUE);
		$company_id = $this->input->post('company_id',TRUE);
		
		$uploaddir = '';
		
		$this->clients_lib->add($company_id,$role_id,$first_name,$last_name,$username,$password,$email,$aemail,$phone,$address,$notes,$status);
		
		//$this->db->query("INSERT INTO `clients` (`role_id`,`first_name`,`middle_name`,`last_name`,`username`,`password`,`email`,`aemail`,`company`,`phone`,`address`,`notes`,`status`,`added`) VALUES (".$this->escape($role_id).",".$this->escape($first_name).",".$this->escape($middle_name).",".$this->escape($last_name).",".$this->escape($username).",".$this->escape($password).",".$this->escape($email).",".$this->escape($aemail).",".$this->escape($company).",".$this->escape($phone).",".$this->escape($address).",".$this->escape($notes).",".$this->escape($status).",".$this->escape($added).")");
		//echo "Added";
		redirect("/super_admin/clients/main/");
		
	}
	
	function do_update($id)
	{
		$this->load->library('clients_lib');
		
		$role_id = $this->input->post('role_id',TRUE);
		$first_name = $this->input->post('first_name',TRUE);
		$last_name = $this->input->post('last_name',TRUE);
		$username = $this->input->post('username',TRUE);
		$password = $this->input->post('password',TRUE);
		$email = $this->input->post('email',TRUE);
		$aemail = $this->input->post('aemail',TRUE);
		$phone = $this->input->post('phone',TRUE);
		$address = $this->input->post('address',TRUE);
		$notes = $this->input->post('notes',TRUE);
		$status = $this->input->post('status',TRUE);
		
		$uploaddir = '';
		
		$this->clients_lib->update($id,$role_id,$first_name,$last_name,$username,$password,$email,$aemail,$phone,$address,$notes,$status);
		//$this->db->query("UPDATE `clients` set `role_id`=".$this->escape($role_id).",`first_name`=".$this->escape($first_name).",`middle_name`=".$this->escape($middle_name).",`last_name`=".$this->escape($last_name).",`username`=".$this->escape($username).",`password`=".$this->escape($password).",`email`=".$this->escape($email).",`aemail`=".$this->escape($aemail).",`company`=".$this->escape($company).",`phone`=".$this->escape($phone).",`address`=".$this->escape($address).",`notes`=".$this->escape($notes).",`status`=".$this->escape($status).",`added`=".$this->escape($added)." WHERE `id`='$id'");
		//echo "Updated"; 
		redirect("/super_admin/clients/main/");
	}
	
	function do_delete($id)
	{
		$this->load->library('clients_lib');
		$this->clients_lib->delete($id);
		redirect("/super_admin/clients/main/");
	}
	
}

?>