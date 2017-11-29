<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Risk_management extends CI_Controller
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
	
	function main($risk_assessment_id = 0)
	{
		$this->load->library('threat_analysis_lib');
		$this->load->library('risk_assessment_lib');
		$this->load->library('threats_lib');
		$this->load->library('library_values');
		$this->load->library('users_lib');
		$this->load->library('library_items_lib');
		$this->load->library('tasks_lib');

		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		$page=$this->input->get('page')==''?0:$this->input->get('page');
		
		$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
		$href1=$_SERVER['PHP_SELF']."?";

		$threats = $this->threats_lib->get_list_by_group(0); //'company_id='.$this->company_id);
		$list = $this->threat_analysis_lib->get_list($risk_assessment_id,'',$sort,$ord);

		$data['risk_assessment_id']	= $risk_assessment_id;
		$data['threats'] = $threats;
		$data['list'] = $list;
		//$data['rendered_items'] = $this->library_items_lib->return_rendered_items($this->library_items_lib->get_library_id_by_name($this->company_id,'Threat Assessment'),'[replace]','[value]');
		$risk_assessment_data = $this->risk_assessment_lib->detail($risk_assessment_id);
		$data['risk_assessment_mitigation_data'] = json_decode($risk_assessment_data['flibrary_value_id'],TRUE); 
		$data['tasks'] = $this->tasks_lib->get_by_risk_assessment($risk_assessment_id);
		
		$data['risk_assessment_detail'] = $this->risk_assessment_lib->detail($risk_assessment_id);
		
		/*echo '<pre>';
		print_r($data['risk_assessment_mitigation_data']);
		echo '</pre>';
		*/
		//$data['employees'] = $this->library_values->get_select_box($this->company_id,'Employees','First Name');
		$this->users_lib->items_per_page=100;
		$data['employees'] = $this->users_lib->get_list();

		$this->load->view("admin/main/main_risk_management",$data);
	}
	
	function do_update($risk_assessment_id,$library_value_id)
	{
		$this->load->library('risk_assessment_lib');
		$this->load->library('library_items_lib');
		$this->load->library('library_lib');
		
		//$risk_assessment_id = $this->input->post('');
		$itemsEncoded = $this->library_items_lib->encode_user_post_data();
		//$this->risk_assessment_lib->update_mitigation($risk_assessment_id,$itemsEncoded);
		$this->library_lib->update_user_data($library_value_id,$this->user_id,$itemsEncoded);	
		
		//redirect("/admin/risk_management/main/".$risk_assessment_id."/");
		redirect("/admin/risk_assessment/main/");
	}
	

}

?>