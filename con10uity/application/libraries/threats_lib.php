<?

class Threats_lib
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
	
	function add($company_id,$name,$group,$added_by)
	{
		$uploaddir = '';
		
		
		$this->ci->db->query("INSERT INTO `threats` (`company_id`,`name`,`group`,`added_on`,`added_by`) VALUES (".$this->escape($company_id).",".$this->escape($name).",".$this->escape($group).",NOW(),".$this->escape($added_by).")");
		return true; 
	}
	
	function update($threat_id,$company_id,$name,$group,$added_by)
	{
		$uploaddir = '';
		
		
		$this->ci->db->query("UPDATE `threats` set `company_id`=".$this->escape($company_id).",`name`=".$this->escape($name).",`group`=".$this->escape($group).",`added_by`=".$this->escape($added_by)." WHERE `threat_id`='$threat_id'");
		return true; 
	}
	
	function delete($threat_id)
	{
		$this->ci->db->query("DELETE FROM `threats` WHERE `threat_id`='$threat_id'");
		return true;
	}
	
	function get_count($filter='1=1')
	{	
		return $this->ci->db->query("SELECT count(*) as cnt from  `threats` WHERE $filter")->row()->cnt;
	}
	
	function getGroups()
	{
		$this->ci->load->library("api_lib");
		return $this->ci->api_lib->get_list("select `group` from threats group by `group`");
	}
	
	function get_list_by_group($company_id=0)
	{
		$this->ci->load->library("api_lib");
		
		return $this->ci->api_lib->get_group_list("select 
					`t`.* 
				from 
					`threats` t
				where 
					t.company_id=".$company_id."
				order by `group` ASC,`name` ASC",'group','threat_id');
	}
	
	
	function get_list($page = 0,$filter = '',$sort = 'threat_id',$ord = 'ASC')
	{
		$this->ci->load->library("api_lib");
		
		if($page>0)
			$page--;
		
		$start = $page * $this->items_per_page;
		
		if($sort!='')
			$sort = 'order by `'.$sort.'` '.$ord;
		
		if($filter!='')
			$filter = 'and '.$filter;
		
		return $this->ci->api_lib->get_list("select 
					`threats`.* 
				from 
					`threats` 
				where 
					1=1
							$filter 
						
							$sort 
						limit $start,".$this->items_per_page);
	}
	
	function detail($item_id)
	{
		if(preg_match('/(WHERE)/i',"select 
					`threats`.* 
				from 
					`threats`"))
		{
			return $this->row("select 
					`threats`.* 
				from 
					`threats` and `threats`.threat_id=".$this->escape($item_id));	
			
		}
		else {
			return $this->row("select 
					`threats`.* 
				from 
					`threats` WHERE `threats`.threat_id=".$this->escape($item_id));	
			
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