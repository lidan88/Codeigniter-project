<?

class Permission_lib
{
	var $items_per_page = 500;

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
	
	function add($name,$group)
	{
		$uploaddir = '';
		
		$this->ci->db->query("INSERT INTO `modules` (`name`,`group`) VALUES (".$this->escape($name).",".$this->escape($group).")");
		return true; 
	}
	
	function adjust_role_permissions($role_id,$permissions)
	{
		$this->ci->db->query("delete from role_permission where role_id=".$role_id);
		
		$sql = '';
		
		if(is_array($permissions) and count($permissions)>0)
		{
			foreach ($permissions as $p => $value) {
				
				$result = 0;
				if(is_array($value))
				foreach ($value as $k => $v) {
					$result = $result ^ $v;
				}
				$sql[] = "(".$role_id.",".$p.",".$result.")";
			}
			
			$this->ci->db->query("insert into role_permission (role_id,module_id,permissions) VALUES ".implode(",", $sql));
		}
	}
	
	function get_role_permissions($role_id)
	{
		$rp = $this->ci->api_lib->get_list("select 
							`rp`.*,
							`p`.name as fpermission_id 
						
						from 
							`role_permission` rp,
							`modules` p 
		
						WHERE 
							rp.module_id=`p`.module_id and
							rp.role_id=".$role_id);

		if(is_array($rp) and count($rp)>0)
		{
			foreach($rp as $r)
			{
				//if($r['module_id'] =='45' || $r['module_id'] =='46'|| $r['module_id'] =='63')
				//	continue;

				$rarray[$r['module_id']] = $r['permissions'];
			}
			
			return $rarray;
		}
		else {
			return array();
		}
	}
	
	function update($permission_id,$name,$group)
	{
		$uploaddir = '';
		
		
		$this->ci->db->query("UPDATE `modules` set `name`=".$this->escape($name).",`group`=".$this->escape($group)." WHERE `module_id`='$permission_id'");
		return true; 
	}
	
	function delete($permission_id)
	{
		$this->ci->db->query("DELETE FROM `modules` WHERE `module_id`='$permission_id'");
		return true;
	}
	
	function get_count($filter='1=1')
	{	
		return $this->ci->db->query("SELECT count(*) as cnt from  `modules` WHERE $filter")->row()->cnt;
	}
	
	function get_user_permission_list($force_db=false)
	{
		$user_permissions = $this->ci->session->userdata("user_permissions");
		if(is_array($user_permissions) and !$force_db)
		{
			return $user_permissions;
		}
		else 
		{
			$this->ci->load->library("api_lib");
			$up = $this->ci->api_lib->get_list("SELECT 
											m.name as module_name,
											rp.permissions
										FROM
											modules m,
											role_permission rp
										WHERE
											m.module_id=rp.module_id and
											rp.role_id=".$this->role_id);
											
				$this->ci->session->unset_userdata("user_permissions");
			 $this->ci->session->set_userdata("user_permissions",$up);
			 return $up;
		}
	}
	
	function has_permission($module,$permission='')
	{
		$has_permission = false;
		$permission_type = array("add"=>1,
								"edit"=>2,
								"delete"=>4,
								"export"=>8);
		
		$up = $this->get_user_permission_list();
		
		if(is_array($up))
		{
			foreach ($up as $key => $value) {
				
				if($value['module_name']==$module)
				{
					if($permission=='' or !isset($permission_type[$permission]))
					{
						$has_permission=true;
						break;
					}
					else if(($permission_type[$permission] & $value['permissions']) == $permission_type[$permission])
					{
						$has_permission=true;
						break;
					}
				}
				
				
			}
		}
		
		return $has_permission;
	}
	
	
	function get_permissions_by_group($filter = '')
	{
		$ls = $this->get_list(0,$filter);
		
		$groups = array();
		foreach($ls as $p)
		{
			$groups[$p['group']][$p['module_id']] = $p;
		}
		
		return $groups;
	}
	
	function get_list($page = 0,$filter = '',$sort = 'module_id',$ord = 'ASC')
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
					`modules`.* 
				from 
					`modules` 
							$filter 
						
							$sort 
						limit $start,".$this->items_per_page);
	}
	
	function detail($item_id)
	{
		if(preg_match('/(WHERE)/i',"select 
					`permission`.* 
				from 
					`permission`"))
		{
			return $this->row("select 
					`permission`.* 
				from 
					`permission` and `permission`.permission_id=".$this->escape($item_id));	
			
		}
		else {
			return $this->row("select 
					`permission`.* 
				from 
					`permission` WHERE `permission`.permission_id=".$this->escape($item_id));	
			
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
		
}

?>