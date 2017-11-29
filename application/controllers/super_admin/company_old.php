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
	
		$name = $this->input->post('name',TRUE);
		
		$this->company_lib->add($name);
		
		//$this->db->query("INSERT INTO `company` (`name`,`added`) VALUES (".$this->escape($name).",".$this->escape($added).")");
		//echo "Added";
		redirect("/super_admin/company/main/");
	}
	
	function do_update($company_id)
	{
		$this->load->library('company_lib');
		
		$name = $this->input->post('name',TRUE);
		$this->company_lib->update($company_id,$name);
		//$this->db->query("UPDATE `company` set `name`=".$this->escape($name).",`added`=".$this->escape($added)." WHERE `company_id`='$company_id'");
		//echo "Updated"; 
		redirect("/super_admin/company/main/");
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