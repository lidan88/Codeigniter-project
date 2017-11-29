<?

class Role_permission_lib
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
	
	function add($role_id,$permission_id)
	{
		$uploaddir = '';
		
		
		$this->ci->db->query("INSERT INTO `role_permission` (`role_id`,`module_id`) VALUES (".$this->escape($role_id).",".$this->escape($permission_id).")");
		return true; 
	}
	
	function add_multi($role_id,$permissions)
	{
		$this->ci->db->query("delete from role_permission where role_id=".$role_id);
		
		$sql = '';
		
		if(is_array($permissions) and count($permissions)>0)
		{
			foreach ($permissions as $p => $value) {
				
				$result = 0;
				foreach ($value as $k => $v) {
					$result = $result ^ $v;
				}
				
				//echo $result."<br />";
				$sql[] = "(".$role_id.",".$p.",".$result.")";
				
				//print_r($value);
			}
			
			//foreach($permissions as $p)
			
			$this->ci->db->query("insert into role_permission (role_id,module_id,permissions) VALUES ".implode(",", $sql));
		
		}
		
		
	}
	
	function update($role_id,$permission_id)
	{
		$uploaddir = '';
		
		
		$this->ci->db->query("UPDATE `role_permission` set `permission_id`=".$this->escape($permission_id)." WHERE `role_id`='$role_id'");
		return true; 
	}
	
	function delete($role_id)
	{
		$this->ci->db->query("DELETE FROM `role_permission` WHERE `role_id`='$role_id'");
		return true;
	}
	
	function get_count($filter='1=1')
	{	
		return $this->ci->db->query("SELECT count(*) as cnt from  `role_permission` WHERE $filter")->row()->cnt;
	}
	
	function get_permissions($role_id)
	{
		$rp = $this->get_list(0,"rp.role_id=".$role_id);
		if(is_array($rp) and count($rp)>0)
		{
			foreach($rp as $r)
			{
				$rarray[$r['module_id']] = $r['module_id'];
			}
			
			return $rarray;
		}
		else {
			return array();
		}
	}
	
	function get_list($page = 0,$filter = '',$sort = 'role_id',$ord = 'ASC')
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
					`rp`.*,
					`p`.name as fpermission_id 
				
				from 
					`role_permission` rp,
					`permission` p 

				WHERE 
					rp.module_id=`p`.permission_id 
							$filter 
						
							$sort 
						limit $start,".$this->items_per_page);
	}
	
	function detail($item_id)
	{
		
			return $this->row("select 
				`role_permission`.*,

`permission`.name as fpermission_id 
				
				from 
					`role_permission`,

`permission`  
				WHERE 
					
role_permission.module_id=`permission`.permission_id WHERE `role_permission`.role_id=".$this->escape($item_id));	
			
		
	
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