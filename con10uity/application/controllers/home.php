<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	
	function __construct() {
	    parent::__construct();
	  //  $this->is_user_authorized();
	  
		$user_info = $this->session->userdata("user_info");
		$this->company_id = isset($user_info['company_id'])?$user_info['company_id']:0;
		$this->user_id = isset($user_info['user_id'])?$user_info['user_id']:0;
		$this->role_id = isset($user_info['role_id'])?$user_info['role_id']:0;
		
		//setDBByCompany($this->db,$this->company_id);

	}
	
	public function is_user_authorized()
	{
		$is_admin = $this->session->userdata("is_admin"); 
		if(!$is_admin)
		{
			return false;
			//redirect("/home/login/");
			//die;
		}
		else
			return true;  
	}
	
	public function login() {
		$this->load->view('login');
	}

	public function index()
	{
	
		if(!$this->is_user_authorized())
			redirect('/home/login/');
		
		// select company db
		setDBByCompany($this->db,$this->company_id);
		
		
		$this->load->library("users_lib");
		$me = $this->users_lib->detail($this->user_id);
		
		$this->load->library('tasks_lib');
		$data['tasks'] = $this->tasks_lib->get_my_tasks($this->user_id);
		
		$parent = $this->input->get("parent");
		$parent = $parent==""?0:1;
		
		$this->load->library("permission_lib");
		$activatedLibs = array();
		$activatedSubLibs = array();
		
		$displayNames = array("PLANS" => "Plans",
							 "BIA" => "Business Impact Analysis",
							 "Threat Assessment" => "Risk Assessment");
							 
		$libraryImages = array("Employees" => "TeamBuilder",
								"Teams" => "TeamBuilder",
								"Business Impact Analysis" => "BIA",
								"Plans" => "PlanBuilder",
								"Files" => "DocManager",
								"Document Manager" => "DocManager",
								"Recovery Strategy" => "RecoveryStrategy",
								"Recovery Strategies" => "RecoveryStrategy",
								"Incident Assessment" => "IncidentAssessment",
								"Training Assessment" => "TrainingAssessment",
								"Risk Assessment" => "RiskAssessment",
								"Training" => "TrainingAssessment",
								"Testing Assessment" => "TestingAssessment",
								"Testing" => "TestingAssessment",
								"Assets" => "Assets",
								"Equipment" => "Equipment",
								"Locations" => "Locations",
								"Vendors" => "Vendors",
								"Supplies" => "Supplies",
								"Regulatory Agencies" => "RegulatoryAgencies",
								"Vital Records" => "VitalRecords",
								"Policies" => "Policies",
								"Hardware" => "Hardware",
								"Threat Assessment" => "RiskAssessment",
								"Key Clients" => "KeyClients",
								"Incident Management"=> "IncidentAssessment",
								"Departments" => "Dependencies",
								"Telecom" => "Telecom",
								"Software" => "Software");
		$result = $this->db->query("select * from library where is_visible=1 and parent_id=0 and company_id=". $this->company_id." order by parent_id DESC,id ASC");
		if ($result->num_rows() > 0)
		{
			foreach ($result->result_array() as $row)
			{
				$libid = get_libuserid_from_name($row['name']);
				if(!$this->permission_lib->has_permission($libid))
					continue;
				
				$row['type']='lib';
				$row['image']='Default';
				
				if(isset($displayNames[$row['name']]))
				{
					$row['name'] = $displayNames[$row['name']];
				}
				
				if(isset($libraryImages[$row['name']]))
					$row['image'] = $libraryImages[$row['name']];
				
				$activatedLibs[] = $row;
			}
			
			if($this->permission_lib->has_permission("call-chain")){ 
				$activatedLibs[] = array("id" => 0,
										"company_id" => 0,
										"parent_id" => 0,
										"name" => "Call Chain",
										"is_visible" => 1,
										"is_system" => 1,
										"type" => "lib",
										"image" => "CallChain",
										"logo" =>""
										);
			}
			
			if($this->permission_lib->has_permission("reports")){ 				
				$activatedLibs[] = array("id" => 0,
										"company_id" => 0,
										"parent_id" => 0,
										"name" => "Reports",
										"is_visible" => 1,
										"is_system" => 1,
										"type" => "lib",
										"image" => "Report",
										"logo" =>""	
				);
			}
			
		}
		$result = $this->db->query("select * from library where parent_id=1 and is_visible=1 and company_id=". $this->company_id." order by parent_id DESC,id ASC"); 
		if ($result->num_rows() > 0)
		{
			foreach ($result->result_array() as $row)
			{
				$libid = get_libuserid_from_name($row['name']);
				if(!$this->permission_lib->has_permission($libid))
					continue;
				
				$row['type']='lib';
				$row['image']='Default';
				
				if(isset($displayNames[$row['name']]))
				{
					$row['name'] = $displayNames[$row['name']];
				}
				
				if(isset($libraryImages[$row['name']]))
					$row['image'] = $libraryImages[$row['name']];
				
				$activatedSubLibs[] = $row;
			}

		}
		/*
		echo '<pre>';
		print_r($activatedLibs);
		echo '</pre>';
		die;
		*/
		$data['activatedSubLibs'] = $activatedSubLibs;
		$data['activatedLibs'] = $activatedLibs;
		
	
		if(!$this->is_user_authorized())
			redirect("/home/login/");
				
				
		$which_view = 'home';
		
		if(isset($_GET['v']) and $_GET['v']=='classic')
			$which_view = 'home_classic';
		else if(isset($_GET['v']) and $_GET['v']=='default')
			$which_view = 'home';
		else if(isset($_GET['v']) and $_GET['v']=='classic_lib')
			$which_view = 'home_classic_lib';
		else if(isset($_GET['v']) and $_GET['v']=='classic_admin')
			$which_view = 'home_classic_admin';
		else if($me['default_view']=='1')
			$which_view = 'home_classic';
				
		/*if((isset($_GET['v']) and $_GET['v']=='classic')) //// or $me['default_view']=='1'
		{
			$this->load->view('home_classic',$data);
		}
		elseif(isset($_GET['v']) and $_GET['v']=='2')
		{
			$this->load->view('home_v2');
		}
		else {*/
		$this->load->library('plans_lib');
		$this->load->library('library_lib');
		$this->plans_lib->items_per_page=10;
		$plans_list = $this->plans_lib->get_list(0);
		$data['plans_list'] = $plans_list;
		$data['library_fields'] = $this->library_lib->explain("PLANS");
		
		$this->load->library('bia_lib');
		$bia_list = $this->bia_lib->get_list(0,$this->company_id);
		$data['bia_list'] = $bia_list;
		
		//print_r($data['bia_list'] );
		
		$this->load->view($which_view,$data);
		//}
	}
	
	function dashboard()
	{
		$this->load->view('home_v2');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */