<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dropdown extends CI_Controller
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
		$this->load->view("super_admin/add/add_dropdown");	
	}
	
	function edit($item_id = 0)
	{
		$this->load->database();
		$this->load->library('dropdown_lib');
		$data['item'] = $this->dropdown_lib->detail($item_id);
		
		$this->load->view("super_admin/edit/edit_dropdown",$data);	
	}
	
	function main($page = 0)
	{
		$this->load->library('dropdown_lib');
		$this->dropdown_lib->items_per_page=500;

		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		//$page=$this->input->get('page');
		
		$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
		$href1=$_SERVER['PHP_SELF']."?";

		$list = $this->dropdown_lib->get_list($page,'d.company_id=0',$sort,$ord);
	
		$data['pagination'] = $this->dropdown_lib->display_pagination('/super_admin/dropdown/main/',$page); 
	
		$data['list'] = $list;
	
		$this->load->view("super_admin/main/main_dropdown",$data);
	}
	
	function view()
	{
		$this->load->view("super_admin/view/view_dropdown");	
	}
	
	function do_add()
	{
		$this->load->library('dropdown_lib');
	
		$company_id = $this->input->post('company_id',TRUE);
		$name = $this->input->post('name',TRUE);
		$is_active = $this->input->post('is_active',TRUE);
		
		
		$uploaddir = '';
		
		
		$this->dropdown_lib->add($company_id,$name,$is_active);
		
		//$this->db->query("INSERT INTO `dropdown` (`company_id`,`name`,`is_active`) VALUES (".$this->escape($company_id).",".$this->escape($name).",".$this->escape($is_active).")");
		//echo "Added";
		redirect("/super_admin/dropdown/main/");
	}
	
	function do_update($dropdown_id)
	{
		$this->load->library('dropdown_lib');
		
		$company_id = $this->input->post('company_id',TRUE);
		$name = $this->input->post('name',TRUE);
		$is_active = $this->input->post('is_active',TRUE);
		
		
		$uploaddir = '';
		
		
		$this->dropdown_lib->update($dropdown_id,$company_id,$name,$is_active);
		//$this->db->query("UPDATE `dropdown` set `company_id`=".$this->escape($company_id).",`name`=".$this->escape($name).",`is_active`=".$this->escape($is_active)." WHERE `dropdown_id`='$dropdown_id'");
		//echo "Updated"; 
		redirect("/super_admin/dropdown/main/");
	}
	
	function do_delete($dropdown_id)
	{
		$this->load->library('dropdown_lib');
		$this->dropdown_lib->delete($dropdown_id);
		//$this->db->query("DELETE FROM `dropdown` WHERE `dropdown_id`='$dropdown_id'");
		//echo "Deleted"; 
		redirect("/super_admin/dropdown/main/");
	}
	
}

?>