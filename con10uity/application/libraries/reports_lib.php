<?

class Reports_lib
{
	var $items_per_page = 10;

	public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->database();
        
        $user_info = $this->ci->session->userdata("user_info");
        $this->company_id = isset($user_info['company_id'])?$user_info['company_id']:0;
        $this->user_id = isset($user_info['user_id'])?$user_info['user_id']:0;
        $this->role_id = isset($user_info['role_id'])?$user_info['role_id']:0;
        
        setDBByCompany($this->ci->db,$this->company_id);
   	}
	
	public function escape($item)
	{
		return $this->ci->db->escape($item);	
	}
	
	function add($library_id,$module_id,$name,$header,$footer,$template,$sort_by = '',$order_by = 'ASC',$filter_by = '')
	{
		$this->ci->db->query("INSERT INTO `reports` (`company_id`,`library_id`,`module_id`,`name`,`header`,`footer`,`template`,`sort_by`,`order_by`,`filter_by`,`modified_on`,`modified_by`) VALUES (".$this->escape($this->company_id).",".$this->escape($library_id).",".$this->escape($module_id).",".$this->escape($name).",".$this->escape($header).",".$this->escape($footer).",".$this->escape($template).",".$this->escape($sort_by).",".$this->escape($order_by).",".$this->escape($filter_by).",NOW(),".$this->escape($this->user_id).")");
		return $this->ci->db->insert_id();
		//return true; 
	}
	
	function update($id,$library_id,$name,$template,$sort_by = '',$order_by = 'ASC',$filter_by = '')
	{
		$this->ci->db->query("UPDATE `reports` set `library_id`=".$this->escape($library_id).",`name`=".$this->escape($name).",`template`=".$this->escape($template).",`sort_by`=".$this->escape($sort_by).",`order_by`=".$this->escape($order_by).",`filter_by`=".$this->escape($filter_by).",`modified_on`=NOW(),`modified_by`=".$this->escape($this->user_id)." WHERE `id`='$id'");
		return true; 
	}
	
	function save_selected_records($report_id,$selected_records)
	{
		$this->ci->db->query("UPDATE `reports` set `selected_records`=".$this->escape($selected_records)." WHERE id=".$report_id);
	}
	
	function save_filters($report_id,$filter_by)
	{
		$this->ci->db->query("UPDATE `reports` set `filter_by`=".$this->escape($filter_by)." WHERE id=".$report_id);
	}
	
	function delete($id)
	{
		$this->ci->db->query("DELETE FROM `reports` WHERE `id`='$id'");
		return true;
	}
	
	function get_report_items($report_id)
	{
		$this->ci->load->library("api_lib");
		$this->ci->load->library("library_lib");
		$this->ci->load->library("call_chain_lib");
		//$this->ci->load->library("risk_assessment_lib");
		
		$report = $this->detail($report_id);
		
		$isFilter = false;
		
		if($report['selected_records']=="" and $report['filter_by']=="")
		{
			return $report;	
		}else
		{
			if($report['selected_records']=="")
			{
				$isFilter=true;
			}
		}
		
		/*
		echo '<pre>';
		print_r($report);
		echo '</pre>';
		die;
		*/
		//$report['selected_records'] = json_decode($report['selected_records'], true);
		
		if(detectModuleInternalType($report['module_type'])=='lib')
		{
			//if($report['template']=="")
			//{
			$report['template_default'] = $this->ci->library_lib->explain($report['library_id']);
			//}
			
			if($isFilter)
				$data['plan_selected_items_temp'] = $this->ci->library_lib->get_user_data_by_filter($report['filter_by']);
			else
				$data['plan_selected_items_temp'] = $this->ci->library_lib->get_user_data_by_ids(implode(',', $report['selected_records']));
			
			foreach ($data['plan_selected_items_temp'] as $k => $psiv) {
				$data['plan_selected_items_temp'][$k]['value'] = json_decode($data['plan_selected_items_temp'][$k]['value'],true);
			}
			
			foreach ($data['plan_selected_items_temp'] as $k => $psi) {
				$report['selected_items'][$k] = $psi;
			}
		}
		else if($report['module_type']=='cc')
		{
			$report['call_chains'] = $this->ci->call_chain_lib->fetch_normalized_call_chains_by_ids($report['selected_records']); 
		}
		else if($report['module_type']=='ra')
		{
			$this->ci->load->library("threats_lib");
			$this->ci->load->library("threat_analysis_lib");
			
			//TBD
			$report['threats_by_group'] = $this->ci->threats_lib->get_list_by_group($this->company_id); //'company_id='.$this->company_id);
		
			foreach ($report['selected_records'] as $risk_assessment_id) {
				$report['ra'][$risk_assessment_id] = $this->ci->threat_analysis_lib->get_list($risk_assessment_id);
			}
			
		}
				
		/*echo '<pre>';
		print_r($report);
		echo '</pre>';
		die;*/
		
		return $report;
	}
	
	function get_template_from_report($report_id)
	{
		return $this->ci->db->query("select `template`,`sort_by`,`order_by`,`filter_by` from reports WHERE id=".$report_id)->row_array();
	}
	
	function get_count($filter='1=1')
	{	
		return $this->ci->db->query("SELECT count(*) as cnt from  `reports` WHERE $filter")->row()->cnt;
	}
	
	function get_by_library_id($library_id)
	{
		$this->ci->load->library("api_lib");
		return $this->ci->api_lib->get_list("SELECT `id`,`library_id`,`name` from `reports` where library_id=".$library_id);
	}
	
	function get_list($page = 0,$filter = '',$sort = 'id',$ord = 'ASC')
	{
		$this->ci->load->library("api_lib");
		
		if($page>0)
			$page--;
		
		$start = $page * $this->items_per_page;
		
		if($sort!='')
			$sort = 'order by '.$sort.' '.$ord;
		
		if($filter!='')
			$filter = 'and '.$filter;
		
		return $this->ci->api_lib->get_list("select 
				`lt`.*,
				`l`.name as flibrary_id ,
				u.first_name as fmodified_by
				
				from 
					`users` u,
					`reports` lt LEFT JOIN `library` l ON (lt.library_id=`l`.id)
				WHERE 
					lt.modified_by=u.user_id
					
							$filter 
						
							$sort 
						limit $start,".$this->items_per_page);
	}
	
	function get_templates_by_library_id($library_id)
	{
		$this->ci->load->library("api_lib");
		
		return $this->ci->api_lib->get_list("select 
				`lt`.*,
				`l`.name as flibrary_id ,
				u.first_name as fmodified_by
				
				from 
					`reports` lt,
					`library` l,
					`users` u
				WHERE 
					lt.library_id=`l`.id and
					lt.modified_by=u.user_id and
					lt.library_id=".$this->ci->api_lib->escape($library_id));
	}
	
	function detail($report_id)
	{
		$report = $this->row("select * from reports where id=".$this->escape($report_id));
		
		if(!is_array($report))
			return false;
		
		$module_type = get_module_type($report['module_id']);
		
		if(detectModuleInternalType($module_type)=="lib")
		{
			$report['template'] = json_decode($report['template'],true);
			$report['filter_by'] = json_decode($report['filter_by'],true);
		}
		else{

		}

		$report['selected_records'] = json_decode($report['selected_records'],true);

		$report['module_type'] = $module_type;
		$report['description'] = $report['name'];
		
		return $report;
	}
	
	function empty_row()
	{
		$columns = "`library_id`,`name`,`template`,`filter_by`,`sort_by`,`order_by`,`modified_on`,`modified_by`";
		$columns_array = explode(',',$columns);
		$empty_row = array();
		foreach ($columns_array as $c) {
			$c = str_replace('`', '', $c);
			$empty_row[$c]="";
		}
		
		$empty_row["id"]="";
		
		return $empty_row;
	}
	
	function row($query)
	{
		$this->ci->load->library("api_lib");
		return $this->ci->api_lib->row($query);
	}
	
	function get_kv_list($query,$k = 'id',$v = 'name')
	{
		$this->ci->load->library("api_lib");
		return $this->ci->api_lib->get_kv_list($query,$k,$v);
	}
	
	function get_k_list($query,$k = 'id')
	{
		$this->ci->load->library("api_lib");
		return $this->ci->api_lib->get_k_list($query,$k);
	}
	
	function display_pagination($base_url = '/',$page = 0,$filter='1=1')
	{
		$total_items = $this->get_count($filter);
		
		$this->ci->load->library('pagination');

		$config['use_page_numbers'] = TRUE;
		$config['uri_segment'] = 4;
		//$config['num_links'] = 2;
		$config['base_url'] = $base_url; //'http://example.com/index.php/test/page/';
		$config['total_rows'] = $total_items; //200;
		$config['per_page'] = $this->items_per_page; 
		$config['full_tag_open'] = '<div><ul class="pagination">';
		$config['full_tag_close'] = '</ul></div>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
	
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		
		$this->ci->pagination->initialize($config); 
		
		return array("pagination"=>$this->ci->pagination->create_links(),
					 "total_items"=>$total_items,
					 "items_per_page"=>$this->items_per_page);
		
	}
		
}

?>