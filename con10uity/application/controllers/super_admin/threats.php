<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Threats extends CI_Controller
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
		$this->load->library("api_lib");
		
		$data['groups'] = $this->api_lib->get_list("select `group` from `threats` where company_id=0 group by `group` order by `group` ASC");
		$this->load->view("super_admin/add/add_threats",$data);	
	}
	
	function edit($item_id = 0)
	{
		$this->load->database();
		$this->load->library('threats_lib');
		$data['item'] = $this->threats_lib->detail($item_id);
		
		$this->load->library("api_lib");
		$data['groups'] = $this->api_lib->get_list("select `group` from `threats` where company_id=0 group by `group` order by `group` ASC");
		
		$this->load->view("super_admin/edit/edit_threats",$data);	
	}
	
	function main($page = 0)
	{
		$this->load->library('threats_lib');

		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		//$page=$this->input->get('page');
		
		$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
		$href1=$_SERVER['PHP_SELF']."?";

		$list = $this->threats_lib->get_list($page,'company_id=0',$sort,$ord);
	
		$data['pagination'] = $this->threats_lib->display_pagination('/super_admin/threats/main/',$page); 
	
		$data['list'] = $list;
	
		$this->load->view("super_admin/main/main_threats",$data);
	}
	
	function view()
	{
		$this->load->view("super_admin/view/view_threats");	
	}
	
	function do_add()
	{
		$this->load->library('threats_lib');
	
		$company_id = $this->input->post('company_id',TRUE);
		$name = $this->input->post('name',TRUE);
		$group = $this->input->post('group',TRUE);
		$added_by = 0;
		
		$this->threats_lib->add($company_id,$name,$group,$added_by);
		redirect("/super_admin/threats/main/");
	}
	
	function do_update($threat_id)
	{
		$this->load->library('threats_lib');
		
		$company_id = $this->input->post('company_id',TRUE);
		$name = $this->input->post('name',TRUE);
		$group = $this->input->post('group',TRUE);
		$added_by = 0;
		
		$this->threats_lib->update($threat_id,$company_id,$name,$group,$added_by);
		redirect("/super_admin/threats/main/");
	}
	
	function do_delete($threat_id)
	{
		$this->load->library('threats_lib');
		$this->threats_lib->delete($threat_id);
		redirect("/super_admin/threats/main/");
	}
	
}

?>