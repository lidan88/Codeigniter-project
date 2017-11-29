<?

class Users_lib
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
	
	public function change_db($company_id = 0)
	{
		setDBByCompany($this->ci->db,$company_id);
		//$this->ci->db->query("USE ".getDDByCompany($company_id));
	}
	
	public function escape($item)
	{
		return $this->ci->db->escape($item);	
	}
	
	function add($company_id,$role_id,$first_name,$last_name,$username,$password,$email,$aemail,$phone,$status,$timezone)
	{
		$this->ci->db->query("INSERT INTO `users` (`company_id`,`role_id`,`first_name`,`last_name`,`username`,`password`,`email`,`aemail`,`phone`,`status`,`added`,`timezone`) VALUES (".$this->escape($company_id).",".$this->escape($role_id).",".$this->escape($first_name).",".$this->escape($last_name).",".$this->escape($username).",".$this->escape($password).",".$this->escape($email).",".$this->escape($aemail).",".$this->escape($phone).",".$this->escape($status).",".$this->escape($timezone).",NOW())");
		return true; 
	}
	
	function update($user_id,$company_id,$role_id,$first_name,$last_name,$username,$password,$email,$aemail,$phone,$status,$timezone)
	{
		$this->ci->db->query("UPDATE `users` set `company_id`=".$this->escape($company_id).",`role_id`=".$this->escape($role_id).",`first_name`=".$this->escape($first_name).",`last_name`=".$this->escape($last_name).",`username`=".$this->escape($username).",`password`=".$this->escape($password).",`email`=".$this->escape($email).",`aemail`=".$this->escape($aemail).",`phone`=".$this->escape($phone).",`status`=".$this->escape($status).",`timezone`=".$this->escape($timezone)." WHERE `user_id`='$user_id'");
		return true; 
	}
	
	function update_settings($user_id,$first_name,$last_name,$password,$email,$aemail,$phone,$timezone,$default_view = 0)
	{
		$this->ci->db->query("UPDATE `users` set 
								`first_name`=".$this->escape($first_name).",
								`last_name`=".$this->escape($last_name).",
								`password`=".$this->escape($password).",
								`email`=".$this->escape($email).",
								`aemail`=".$this->escape($aemail).",
								`phone`=".$this->escape($phone).",
								`timezone`=".$this->escape($timezone)." ,
								`default_view`=".$this->escape($default_view)."
							WHERE 
								`user_id`='$user_id'");
		return true; 
	}
	
	
	function delete($user_id)
	{
		$this->ci->db->query("DELETE FROM `users` WHERE `user_id`='$user_id'");
		return true;
	}
	
	function get_count($filter='1=1')
	{	
		return $this->ci->db->query("SELECT count(*) as cnt from  `users` WHERE $filter")->row()->cnt;
	}
	
	function get_list($page = 0,$filter = '',$sort = 'user_id',$ord = 'ASC')
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
				`users`.*,

`company`.username as fcompany_id,
`role`.name as frole_id 
				
				from 
					`users`,

`company` ,
`role`  
				WHERE 
					
users.company_id=`company`.company_id and 
users.role_id=`role`.role_id 
							$filter 
						
							$sort 
						limit $start,".$this->items_per_page);
	}
	
	function get_permissions()
	{
		$this->ci->load->library("permission_lib");
		return $this->ci->permission_lib->get_user_permission_list($this->user_id);
	}	
	
	function detail($item_id)
	{
		return $this->row("select 
						`users`.*,
		`company`.name as fcompany_id,
		`role`.name as frole_id 
		from 
			`users`,
			`company`,
			`role`  
		WHERE 
							
			users.company_id=`company`.company_id and 
			users.role_id=`role`.role_id and 
			`users`.user_id=".$this->escape($item_id));	
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
	
	function display_pagination($base_url = '/',$page = 0,$filter = '1=1')
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
		
		return $this->ci->pagination->create_links();
		
	}
		
}

?>