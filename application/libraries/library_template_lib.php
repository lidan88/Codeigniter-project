<?

class Library_template_lib
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
	
	function add($library_id,$name,$template,$sort_by = '',$order_by = 'ASC')
	{
		$this->ci->db->query("INSERT INTO `library_template` (`library_id`,`name`,`template`,`sort_by`,`order_by`,`modified_on`,`modified_by`) VALUES (".$this->escape($library_id).",".$this->escape($name).",".$this->escape($template).",".$this->escape($sort_by).",".$this->escape($order_by).",NOW(),".$this->escape($this->user_id).")");
		return true; 
	}
	
	function update($id,$library_id,$name,$template,$sort_by = '',$order_by = 'ASC')
	{
		$uploaddir = '';
		
		
		$this->ci->db->query("UPDATE `library_template` set `library_id`=".$this->escape($library_id).",`name`=".$this->escape($name).",`template`=".$this->escape($template).",`sort_by`=".$this->escape($sort_by).",`order_by`=".$this->escape($order_by).",`modified_on`=NOW(),`modified_by`=".$this->escape($this->user_id)." WHERE `id`='$id'");
		return true; 
	}
	
	function delete($id)
	{
		$this->ci->db->query("DELETE FROM `library_template` WHERE `id`='$id'");
		return true;
	}
	
	function get_count($filter='1=1')
	{	
		return $this->ci->db->query("SELECT count(*) as cnt from  `library_template` WHERE $filter")->row()->cnt;
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
					`library_template` lt,
					`library` l,
					`users` u
				WHERE 
					lt.library_id=`l`.id and
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
					`library_template` lt,
					`library` l,
					`users` u
				WHERE 
					lt.library_id=`l`.id and
					lt.modified_by=u.user_id and
					lt.library_id=".$this->ci->api_lib->escape($library_id));
	}
	
	function detail($item_id)
	{
	
			return $this->row("select 
				`library_template`.*,

`library`.name as flibrary_id 
				
				from 
					`library_template`,

`library`  
				WHERE 
					
library_template.library_id=`library`.id and `library_template`.id=".$this->escape($item_id));	
			
		
	
	}
	
	function empty_row()
	{
		$columns = "`library_id`,`name`,`template`,`modified_on`,`modified_by`";
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