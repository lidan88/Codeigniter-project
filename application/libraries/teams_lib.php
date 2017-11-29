<?

class Teams_lib
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
	
	function add($team_id,$lv_id,$member,$role,$responsibility,$priority=0)
	{
		if($team_id==0)
			$this->ci->db->query("INSERT INTO `teams` (`company_id`,`lv_id`,`modified_by`,`member`,`role`,`responsibility`,`priority`,`modified_on`) VALUES (".$this->escape($this->company_id).",".$this->escape($lv_id).",".$this->escape($this->user_id).",".$this->escape($member).",".$this->escape($role).",".$this->escape($responsibility).",".$this->escape($priority).",NOW())");
		else {
			$this->ci->db->query("UPDATE `teams` set 
									`member`=".$this->escape($member).",
									`role`=".$this->escape($role).",
									`responsibility`=".$this->escape($responsibility).",
									`priority`=".$this->escape($priority)."
								WHERE
									team_id=".$team_id);
		}
	}
	
	function delete($team_id)
	{
		$this->ci->db->query("DELETE FROM `teams` WHERE `team_id`='$team_id'");
		return true;
	}
	
	function get_count($filter='1=1')
	{	
		return $this->ci->db->query("SELECT count(*) as cnt from  `teams` WHERE $filter")->row()->cnt;
	}
	
	function get_by_library_value($lv_id)
	{
		$this->ci->load->library("api_lib");
		return $this->ci->api_lib->get_list("select 
					t.team_id,
					t.member,
					t.role,
					t.responsibility,
					t.priority
				from 
					`teams` t
				where
					t.lv_id=".$lv_id."
				order by
					t.priority ASC");
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
		
}

?>