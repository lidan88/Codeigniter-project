<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dropdown extends CI_Controller
{
	function __construct() {
       parent::__construct();
       $this->protect();
       
       $user_info = $this->session->userdata("user_info");
       $this->company_id = $user_info['company_id'];
       
   }
   
	public function protect()
	{
		$is_admin = $this->session->userdata("is_admin"); 
		if(!$is_admin)
		{
			redirect("/admin/login/");
			die;
		}
		else
		{
			return true;  
		}
	}
	
	function index()
	{
		$this->main();	
	}
	
	function add($dropdown_id = 0)
	{
		$this->load->database();
		$this->load->view("admin/add/add_dropdown");	
	}
	
	function edit($item_id = 0)
	{
		$this->load->database();
		$this->load->library('dropdown_lib');
		$data['item'] = $this->dropdown_lib->detail($item_id);
		
		$this->load->view("admin/edit/edit_dropdown",$data);	
	}
	
	function main($page = 0)
	{
		$this->load->library('dropdown_lib');

		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		//$page=$this->input->get('page');
		
		$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
		$href1=$_SERVER['PHP_SELF']."?";

		$list = $this->dropdown_lib->get_list($page,'`d`.company_id='.$this->company_id,$sort,$ord);
	
		//$data['dropdown_details'] = $this->dropdown_lib->detail($dropdown_id);
	
		$data['pagination'] = $this->dropdown_lib->display_pagination('/admin/dropdown/main/',$page,'company_id='.$this->company_id); 
	
		$data['list'] = $list;
	
		$this->load->view("admin/main/main_dropdown",$data);
	}
	
	function view()
	{
		$this->load->view("admin/view/view_dropdown");	
	}
	
	function do_add()
	{
		$this->load->library('dropdown_lib');
	
		$name = $this->input->post('name',TRUE);
		$is_active = $this->input->post('is_active',TRUE);
		
		$this->dropdown_lib->add($this->company_id,$name,$is_active);
		
		redirect("/admin/dropdown/main/");
	}
	
	function do_update($dropdown_id)
	{
		$this->load->library('dropdown_lib');
		
		$name = $this->input->post('name',TRUE);
		$is_active = $this->input->post('is_active',TRUE);
		
		$this->dropdown_lib->update($dropdown_id,$name,$is_active);
		redirect("/admin/dropdown/main/");
	}
	
	function do_delete($dropdown_id)
	{
		$this->load->library('dropdown_lib');
		$this->dropdown_lib->delete($dropdown_id);
		redirect("/admin/dropdown/main/");
	}
	
}

?>