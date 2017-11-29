<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tasks extends CI_Controller
{
	function __construct() {
       parent::__construct();
       $this->protect();
       
       $user_info = $this->session->userdata("user_info");
       $this->company_id = $user_info['company_id'];
       $this->user_id = $user_info['user_id'];
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
			return true;  
	}
	
	function index()
	{
		$this->main();	
	}
	
	function add()
	{
		$this->load->database();
		$this->load->view("admin/add/add_tasks");	
	}
	
	function edit($item_id = 0)
	{
		$this->load->database();
		$this->load->library('tasks_lib');
		$data['item'] = $this->tasks_lib->detail($item_id);
		
		$this->load->view("admin/edit/edit_tasks",$data);	
	}
	
	function main($page = 0)
	{
		$this->load->library('tasks_lib');

		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		//$page=$this->input->get('page');
		
		$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
		$href1=$_SERVER['PHP_SELF']."?";

		$list = $this->tasks_lib->get_list($page,'',$sort,$ord);
	
		$data['pagination'] = $this->tasks_lib->display_pagination('/admin/tasks/main/',$page); 
	
		$data['list'] = $list;
	
		$this->load->view("admin/main/main_tasks",$data);
	}
	
	function get_task_details_by_threat()
	{
		$this->load->library("tasks_lib");
		$threat_id = $this->input->post("threat_id",TRUE);
		$risk_assessment_id = $this->input->post("risk_assessment_id",TRUE);
		
		$details = $this->tasks_lib->get_tasks_details_by_threat_id($threat_id,$risk_assessment_id);
		echo json_encode($details);
	}
	
	function view()
	{
		$this->load->view("admin/view/view_tasks");	
	}
	
	function do_add()
	{
		$this->load->library('tasks_lib');
	
		$threat_id = $this->input->post('threat_id',TRUE);
		$risk_assessment_id = $this->input->post('risk_assessment_id',TRUE);
		$assigned_by = $this->user_id;//$this->input->post('assigned_by',TRUE);
		$assigned_to = $this->input->post('assigned_to',TRUE);
		$title = $this->input->post('title',TRUE);
		$description = $this->input->post('description',TRUE);
		$priority = $this->input->post('priority',TRUE);
		$due_by = $this->input->post('due_by',TRUE);
		
		//$due_array = explode('/',$due_by);
		//$due_by = $due_array[2].'-'.$due_array[0].'-'.$due_array[1];
		
		$this->tasks_lib->add($this->company_id,$threat_id,$risk_assessment_id,$assigned_by,$assigned_to,$title,$description,$priority,$due_by);
		echo "1";
		//redirect("/admin/tasks/main/");
	}
	
	function do_re_assign()
	{
		$this->load->library('tasks_lib');
	
		$threat_id = $this->input->post('threat_id',TRUE);
		$risk_assessment_id = $this->input->post('risk_assessment_id',TRUE);
		$assigned_by = $this->user_id;//$this->input->post('assigned_by',TRUE);
		$assigned_to = $this->input->post('assigned_to',TRUE);
		$title = $this->input->post('title',TRUE);
		$description = $this->input->post('description',TRUE);
		$priority = $this->input->post('priority',TRUE);
		$due_by = $this->input->post('due_by',TRUE);
		
		//$due_array = explode('/',$due_by);
		//$due_by = $due_array[2].'-'.$due_array[0].'-'.$due_array[1];
		
		//$created_on = $this->input->post('created_on',TRUE);
		
		$this->tasks_lib->re_assign($this->company_id,$threat_id,$risk_assessment_id,$assigned_by,$assigned_to,$title,$description,$priority,$due_by);
		echo "1";
		//redirect("/admin/tasks/main/");
	}
	
	function do_update($task_id)
	{
		$this->load->library('tasks_lib');
		
		$threat_id = $this->input->post('threat_id',TRUE);
		$risk_assessment_id = $this->input->post('risk_assessment_id',TRUE);
		$assigned_by = $this->user_id;//$this->input->post('assigned_by',TRUE);
		$assigned_to = $this->input->post('assigned_to',TRUE);
		$title = $this->input->post('title',TRUE);
		$priority = $this->input->post('priority',TRUE);
		$due_by = $this->input->post('due_by',TRUE);
		$created_on = $this->input->post('created_on',TRUE);
		
		$this->tasks_lib->update($task_id,$threat_id,$risk_assessment_id,$assigned_by,$assigned_to,$title,$priority,$due_by);
		redirect("/admin/tasks/main/");
	}
	function complete_task() {
		$this->load->library('tasks_lib');
		
		$task_id = $this->input->post('task_id',TRUE);
		$this->tasks_lib->complete_task($task_id);
		echo "1";
	}
	
	function do_delete($task_id)
	{
		$this->load->library('tasks_lib');
		$this->tasks_lib->delete($task_id);
		redirect("/admin/tasks/main/");
	}
	
}

?>