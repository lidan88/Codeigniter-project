<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Threat_analysis extends CI_Controller
{
	function __construct() {
		parent::__construct();
		$this->protect();
		
		$user_info = $this->session->userdata("user_info");
		$this->company_id = isset($user_info['company_id'])?$user_info['company_id']:0;
		$this->user_id = isset($user_info['user_id'])?$user_info['user_id']:0;
		$this->role_id = isset($user_info['role_id'])?$user_info['role_id']:0;
		
		//setDBByCompany($this->db,$this->company_id);
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
	
	function index($risk_assessment_id=0)
	{
		$this->main($risk_assessment_id);	
	}
	
	function main($risk_assessment_id=0)
	{
		$this->load->library('threat_analysis_lib');
		$this->load->library('threats_lib');
		$this->load->library('risk_assessment_lib');

		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		$page=$this->input->get('page')==''?0:$this->input->get('page');
		
		$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
		$href1=$_SERVER['PHP_SELF']."?";

		$threats = $this->threats_lib->get_list_by_group($this->company_id); //'company_id='.$this->company_id);
		$list = $this->threat_analysis_lib->get_list($risk_assessment_id,'',$sort,$ord);

		$data['risk_assessment_detail'] = $this->risk_assessment_lib->detail($risk_assessment_id);

		$data['risk_assessment_id']	= $risk_assessment_id;
		$data['threats'] = $threats;
		$data['list'] = $list;
	
		$this->load->view("admin/main/main_threat_analysis",$data);
	}
	
	function do_update()
	{
		$this->load->library('threat_analysis_lib');
		$this->load->library('risk_assessment_lib');
		
		$risk_assessment_id = $this->input->post('risk_assessment_id',TRUE);
		$threats = $this->input->post('threat',TRUE);
		$this->threat_analysis_lib->update($risk_assessment_id,$threats);
		
		$name = $this->input->post('name',TRUE);
		$description = $this->input->post('description',TRUE);
		$submit = $this->input->post('submit',TRUE);
		$this->risk_assessment_lib->update($risk_assessment_id,$name,$description,$this->user_id);
		
		//redirect("/admin/threat_analysis/main/".$risk_assessment_id."/");
		if($submit=='Save')
			redirect("/admin/risk_assessment/main/");
		else
			redirect("/admin/risk_management/main/".$risk_assessment_id."/");
	}
	
	function do_delete($threat_analysis_id)
	{
		$this->load->library('threat_analysis_lib');
		$this->threat_analysis_lib->delete($threat_analysis_id);
		//$this->db->query("DELETE FROM `threat_analysis` WHERE `threat_analysis_id`='$threat_analysis_id'");
		//echo "Deleted"; 
		redirect("/admin/threat_analysis/main/");
	}
	
}

?>