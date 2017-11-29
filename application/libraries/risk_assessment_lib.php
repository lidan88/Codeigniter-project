<?

class Risk_assessment_lib
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
	
	function add($name,$description,$modified_by,$company_id,$library_value_id,$status)
	{
		$this->ci->db->query("INSERT INTO `risk_assessment` (`name`,`description`,`modified_on`,`modified_by`,`company_id`,`library_value_id`,`status`) VALUES (".$this->escape($name).",".$this->escape($description).",NOW(),".$this->escape($modified_by).",".$this->escape($company_id).",".$this->escape($library_value_id).",".$this->escape($status).")");
		return $this->ci->db->insert_id();
		//return true; 
	}
	
	function update($risk_assessment_id,$name,$description,$modified_by)
	{
		$uploaddir = '';
		$this->ci->db->query("UPDATE `risk_assessment` set `name`=".$this->escape($name).",`description`=".$this->escape($description).",`modified_on`=NOW(),`modified_by`=".$this->escape($modified_by)." WHERE `risk_assessment_id`='$risk_assessment_id'");
		return true; 
	}
	
	function update_mitigation($risk_assessment_id,$mitigation)
	{
		$this->ci->db->query("UPDATE `risk_assessment` set `mitigation`=".$this->escape($mitigation)." WHERE `risk_assessment_id`='$risk_assessment_id'");
	}
	
	function submit_for_approval($risk_assessment_id)
	{
		$this->ci->db->query("UPDATE `risk_assessment` set `status`='Pending Approval' WHERE `risk_assessment_id`='$risk_assessment_id'");
	}
	
	function approve($risk_assessment_id)
	{
		$this->ci->db->query("UPDATE `risk_assessment` set `status`='Approved' WHERE `risk_assessment_id`='$risk_assessment_id'");
	}
	
	function delete($risk_assessment_id)
	{
		$this->ci->db->query("DELETE FROM `risk_assessment` WHERE `risk_assessment_id`='$risk_assessment_id'");
		$this->ci->db->query("DELETE FROM `threat_analysis` WHERE `risk_assessment_id`='$risk_assessment_id'");
		
		return true;
	}
	
	function delete_user_values($ids)
	{
		$this->ci->db->query("DELETE FROM `risk_assessment` WHERE `risk_assessment_id` in (".$ids.")");
		$this->ci->db->query("DELETE FROM `threat_analysis` WHERE `risk_assessment_id` in (".$ids.")");
		
		return true;
	
	}
	
	function get_count($filter='1=1')
	{	
		return $this->ci->db->query("SELECT count(*) as cnt from  `risk_assessment` WHERE $filter")->row()->cnt;
	}
	
	function get_list($page = 0,$filter = '',$sort = 'ra.risk_assessment_id',$ord = 'ASC')
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
					`ra`.*,
					DATE_FORMAT(ra.modified_on,'%m/%d/%Y %r') as modified_on,
					u.username as modified_by
				from 
					`risk_assessment` ra,
					`users` u
				WHERE
					u.user_id=ra.modified_by and
					ra.company_id=".$this->company_id."
							$filter 
						
							$sort 
						limit $start,".$this->items_per_page);
	}
	
	function get_list_by_ids($values)
	{
		$this->ci->load->library("api_lib");
		return $this->ci->api_lib->get_k_list("select 
					`ra`.risk_assessment_id,
					`ra`.name,
					`ra`.description,
					DATE_FORMAT(ra.modified_on,'%m/%d/%Y %r') as modified_on,
					u.username as modified_by,
					`ra`.`status`
				from 
					`risk_assessment` ra,
					`users` u
				WHERE
					u.user_id=ra.modified_by and 
					ra.risk_assessment_id in (".$values.") order by FIELD(risk_assessment_id,".$values.")","risk_assessment_id");
	}
	
	function copy($risk_assessment_id)
	{
		$this->ci->db->query("insert into risk_assessment (company_id,`name`,`description`,modified_on,modified_by,`status`) (select company_id,CONCAT(`name`,' Copy'),`description`,NOW(),modified_by,`status` from risk_assessment where risk_assessment_id=".$risk_assessment_id.")");
		$new_risk_assessment_id = $this->ci->db->insert_id();
		$this->ci->db->query("insert into threat_analysis (`risk_assessment_id`,threat_id,likelihood,impact,weight) (select ".$new_risk_assessment_id.",threat_id,likelihood,impact,weight from threat_analysis where risk_assessment_id=".$risk_assessment_id.")");
	}
	
	function detail($item_id)
	{
		return $this->row("select 
			r.*,
			lv.`value` as flibrary_value_id
		from 
			`risk_assessment` r,
			library_values lv
		WHERE 
			r.library_value_id=lv.id and
			r.risk_assessment_id=".$this->escape($item_id));	
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
		
		//return $this->ci->pagination->create_links();
		return array("pagination"=>$this->ci->pagination->create_links(),
					 "total_items"=>$total_items,
					 "items_per_page"=>$this->items_per_page);
		
		
	}
		
}

?>