<?

class Threat_analysis_lib
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
	
	function update($risk_assessment_id,$threats)
	{
		$this->ci->db->query("DELETE FROM `threat_analysis` where risk_assessment_id=".$risk_assessment_id);
		
		$weight_matrix['LOW']['LOW']="LOW";
		$weight_matrix['LOW']['MEDIUM']="LOW";
		$weight_matrix['LOW']['HIGH']="MEDIUM";
	
		$weight_matrix['MEDIUM']['LOW']="LOW";
		$weight_matrix['MEDIUM']['MEDIUM']="MEDIUM";
		$weight_matrix['MEDIUM']['HIGH']="HIGH";
		
		$weight_matrix['HIGH']['LOW']="MEDIUM";
		$weight_matrix['HIGH']['MEDIUM']="HIGH";
		$weight_matrix['HIGH']['HIGH']="CRITICAL";
		
		$sql = array();
		foreach ($threats as $threat_id => $value) {
			$weight = $weight_matrix[$value[0]][$value[1]];
			$sql[] = "(".$risk_assessment_id.",".$threat_id.",'".$value[0]."','".$value[1]."','".$weight."')";
		}

		$this->ci->db->query("INSERT INTO `threat_analysis` (`risk_assessment_id`,`threat_id`,`likelihood`,`impact`,`weight`) VALUES ".implode(",", $sql));
		return true; 
	}
	
	function delete($threat_analysis_id)
	{
		$this->ci->db->query("DELETE FROM `threat_analysis` WHERE `threat_analysis_id`='$threat_analysis_id'");
		return true;
	}
	
	function get_count($filter='1=1')
	{	
		return $this->ci->db->query("SELECT count(*) as cnt from  `threat_analysis` WHERE $filter")->row()->cnt;
	}
	
	function get_list($risk_assessment_id,$filter = '',$sort = '',$ord = 'ASC')
	{
		$this->ci->load->library("api_lib");
		
		if($sort!='')
			$sort = 'order by '.$sort.' '.$ord;
		
		return $this->ci->api_lib->get_k_list("select 
					ta.*,
					t.`name` as threat_name
					 
				from 
					`threat_analysis` ta,
					threats t
				where
					ta.threat_id=t.threat_id and
					ta.risk_assessment_id=".$risk_assessment_id."
				".$sort,"threat_id");
	}
	
	function detail($item_id)
	{
		if(preg_match('/(WHERE)/i',"select 
					`threat_analysis`.* 
				from 
					`threat_analysis`"))
		{
			return $this->row("select 
					`threat_analysis`.* 
				from 
					`threat_analysis` and `threat_analysis`.threat_analysis_id=".$this->escape($item_id));	
			
		}
		else {
			return $this->row("select 
					`threat_analysis`.* 
				from 
					`threat_analysis` WHERE `threat_analysis`.threat_analysis_id=".$this->escape($item_id));	
			
		}
	
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
	
	function display_pagination($base_url = '/',$page = 0)
	{
		$total_items = $this->get_count();
		
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
		
		return $this->ci->pagination->create_links();
		
	}
		
}

?>