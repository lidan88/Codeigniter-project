<?

class Dropdown_lib
{
	var $items_per_page = 10;

	public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->database();
        
        $user_info = $this->ci->session->userdata("user_info");
        $this->company_id = isset($user_info['company_id'])?$user_info['company_id']:0;
        $this->user_id = isset($user_info['user_id'])?$user_info['user_id']:0;
        
        setDBByCompany($this->ci->db,$this->company_id);
	}
	
	public function escape($item)
	{
		return $this->ci->db->escape($item);	
	}
	
	function add($company_id,$name,$is_active)
	{
		$uploaddir = '';
		
		
		$this->ci->db->query("INSERT INTO `dropdown` (`company_id`,`name`,`is_active`) VALUES (".$this->escape($company_id).",".$this->escape($name).",".$this->escape($is_active).")");
		return true; 
	}
	
	function update($dropdown_id,$name,$is_active)
	{
		$uploaddir = '';
		
		$this->ci->db->query("UPDATE `dropdown` set `name`=".$this->escape($name).",`is_active`=".$this->escape($is_active)." WHERE `dropdown_id`='$dropdown_id'");
		return true; 
	}
	
	function delete($dropdown_id)
	{
		$this->ci->db->query("DELETE FROM `dropdown` WHERE `dropdown_id`='$dropdown_id'");
		$this->ci->db->query("DELETE FROM `dropdown_item` where `dropdown_id`='$dropdown_id'");
		return true;
	}
	
	function get_count($filter='1=1')
	{	
		return $this->ci->db->query("SELECT count(*) as cnt from  `dropdown` WHERE $filter")->row()->cnt;
	}
	
	function get_list($page = 0,$filter = '',$sort = 'dropdown_id',$ord = 'ASC')
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
				`d`.*,

`c`.name as fcompany_id 
				
				from 
					`dropdown` d
					LEFT JOIN

`company`  c ON (d.company_id=c.company_id)
				WHERE 
				1=1					
							$filter 
						
							$sort 
						limit $start,".$this->items_per_page);
	}
	
	function get_select_box_items($company_id,$name)
	{
		$this->ci->load->library("api_lib");
		return $this->ci->api_lib->get_list("select dropdown_id,dropdown_item_id,`name` from `dropdown_item` where is_active='Yes' and dropdown_id=(select dropdown_id from dropdown where company_id=".$company_id." and `name`=".$this->ci->db->escape($name).")");
	}
	
	function detail($item_id)
	{
		return $this->row("select 
					`dropdown`.*,
					`company`.name as fcompany_id 
				
				from 
					`dropdown`
					LEFT JOIN `company` ON (dropdown.company_id=company.company_id)
				WHERE 
					`dropdown`.dropdown_id=".$this->escape($item_id));	
			
			
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
		
		return $this->ci->pagination->create_links();
		
	}
		
}

?>