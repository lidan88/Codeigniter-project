<?

class Tasks_lib
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
	
	function add($company_id,$threat_id,$risk_assessment_id,$assigned_by,$assigned_to,$title,$description,$priority,$due_by)
	{
		$uploaddir = '';
		
		$this->ci->db->query("INSERT INTO `tasks` (`company_id`,`threat_id`,`risk_assessment_id`,`assigned_by`,`assigned_to`,`title`,`description`,`priority`,`due_by`,`created_on`) VALUES (".$this->escape($company_id).",".$this->escape($threat_id).",".$this->escape($risk_assessment_id).",".$this->escape($assigned_by).",".$this->escape($assigned_to).",".$this->escape($title).",".$this->escape($description).",".$this->escape($priority).",".$this->escape($due_by).",NOW())");
		return true; 
	}
	
	function quick_add($task_id,$lv_id,$assigned_to,$title,$priority=0)
	{
		if($task_id==0)
			$this->ci->db->query("INSERT INTO `tasks` (`company_id`,`lv_id`,`assigned_by`,`assigned_to`,`title`,`priority`,`created_on`) VALUES (".$this->escape($this->company_id).",".$this->escape($lv_id).",".$this->escape($this->user_id).",".$this->escape($assigned_to).",".$this->escape($title).",".$this->escape($priority).",NOW())");
		else {
			$this->ci->db->query("UPDATE `tasks` set 
									`assigned_to`=".$this->escape($assigned_to).",
									`priority`=".$this->escape($priority).",
									`title`=".$this->escape($title)."
								WHERE
									task_id=".$task_id);
		}
	}
	
	function re_assign($company_id,$threat_id,$risk_assessment_id,$assigned_by,$assigned_to,$title,$description,$priority,$due_by)
	{
		$uploaddir = '';
		
		
		$this->ci->db->query("UPDATE `tasks` set `assigned_to`=".$this->escape($assigned_to).",`title`=".$this->escape($title).",`description`=".$this->escape($description).",`priority`=".$this->escape($priority).",`due_by`=".$this->escape($due_by)." WHERE `assigned_by`=".$this->escape($assigned_by)." and risk_assessment_id=".$risk_assessment_id." and threat_id=".$threat_id." and company_id=".$company_id);
		return true; 
	}

	
	function update($task_id,$threat_id,$risk_assessment_id,$assigned_by,$assigned_to,$title,$priority,$due_by)
	{
		$uploaddir = '';
		
		
		$this->ci->db->query("UPDATE `tasks` set `threat_id`=".$this->escape($threat_id).",`risk_assessment_id`=".$this->escape($risk_assessment_id).",`assigned_by`=".$this->escape($assigned_by).",`assigned_to`=".$this->escape($assigned_to).",`title`=".$this->escape($title).",`priority`=".$this->escape($priority).",`due_by`=".$this->escape($due_by)." WHERE `task_id`='$task_id'");
		return true; 
	}
	
	function complete_task($task_id)
	{
		$this->ci->db->query("UPDATE `tasks` set `completed`=1 where task_id=".$task_id);
	}
	
	function delete($task_id)
	{
		$this->ci->db->query("DELETE FROM `tasks` WHERE `task_id`='$task_id'");
		return true;
	}
	
	function get_count($filter='1=1')
	{	
		return $this->ci->db->query("SELECT count(*) as cnt from  `tasks` WHERE $filter")->row()->cnt;
	}
	
	function get_by_risk_assessment($risk_assessment_id)
	{
		$this->ci->load->library("api_lib");
		return $this->ci->api_lib->get_k_list("select 
					`t`.*,
					u.first_name
				from 
					`tasks` t,
					users u
				where
					t.assigned_to=u.user_id and
					t.risk_assessment_id=".$risk_assessment_id,'threat_id');
	}
	
	function get_by_library_value($lv_id)
	{
		$this->ci->load->library("api_lib");
		return $this->ci->api_lib->get_list("select 
					t.task_id,
					t.title,
					t.assigned_by,
					t.assigned_to,
					t.completed,
					t.priority,
					u.first_name
					
				from 
					`tasks` t,
					users u
				where
					t.assigned_to=u.user_id and
					t.lv_id=".$lv_id."
				order by
					t.priority ASC");
	}
	
	
	function get_my_tasks($user_id)
	{
		$this->ci->load->library("api_lib");
		
		if($user_id!='' and $user_id!=0)
		{
		return $this->ci->api_lib->get_list("select 
					`t`.*,
					u.first_name
				from 
					`tasks` t,
					users u
				where
					t.assigned_by=u.user_id and
					t.completed=0 and
					t.assigned_to=".$user_id);
		}
		else {
			return false;
		}
	}
	
	function get_tasks_details_by_threat_id($threat_id,$risk_assessment_id)
	{
		return $this->row("select * from tasks where threat_id=".$threat_id." and risk_assessment_id=".$risk_assessment_id);
	}
	
	function get_list($page = 0,$filter = '',$sort = 'task_id',$ord = 'ASC')
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
					`tasks`.* 
				from 
					`tasks` 
							$filter 
						
							$sort 
						limit $start,".$this->items_per_page);
	}
	
	function detail($item_id)
	{
		if(preg_match('/(WHERE)/i',"select 
					`tasks`.* 
				from 
					`tasks`"))
		{
			return $this->row("select 
					`tasks`.* 
				from 
					`tasks` and `tasks`.task_id=".$this->escape($item_id));	
			
		}
		else {
			return $this->row("select 
					`tasks`.* 
				from 
					`tasks` WHERE `tasks`.task_id=".$this->escape($item_id));	
			
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