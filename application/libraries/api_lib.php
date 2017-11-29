<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_lib {
	var $debug_db;
	var $items_per_page = 10;
    
	public function __construct()
    {
        $this->ci = &get_instance();	
		$this->ci->load->database();
		$this->debug_db = false;
		// Nab:mysql
		//$this->ci->master_db = $this->ci->load->database('master', TRUE);
		//if(!$this->ci->master_db)
		//$this->ci->master_db = $this->ci->db;
		
		$user_info = $this->ci->session->userdata("user_info");
		$this->company_id = isset($user_info['company_id'])?$user_info['company_id']:0;
		$this->user_id = isset($user_info['user_id'])?$user_info['user_id']:0;
		$this->role_id = isset($user_info['role_id'])?$user_info['role_id']:0;
	
		$is_admin = $this->ci->session->userdata("superadmin");
		//if(!$is_admin)
		//	setDBByCompany($this->ci->db,$this->company_id);
	}
	
	public function change_db($company_id = 0)
	{
		setDBByCompany($this->ci->db,$company_id);
		//$this->ci->db->query("USE ".getDDByCompany($company_id));
	}	
	public function escape($str)
	{
		return $this->ci->db->escape($str);
	}
		
	/**
	 * Determines if a query is a "write" type.
	 *
	 * @access	public
	 * @param	string	An SQL query string
	 * @return	boolean
	 */
	function is_write_type($sql)
	{
		if ( ! preg_match('/^\s*"?(SET|INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|TRUNCATE|LOAD DATA|COPY|ALTER|GRANT|REVOKE|LOCK|UNLOCK)\s+/i', $sql))
		{
			return FALSE;
		}
		return TRUE;
	}
		
	public function insert_id()
	{
		return $this->ci->db->insert_id();	
	}
		
	public function query($query)
	{
		if($this->debug_db)
			echo '<br />'.$query.'<br />';
		return $this->ci->db->query($query);
	}
	
	function get_list($query)
	{
		$result = $this->query($query);
	
	   	$rows = array();
	
		if ($result->num_rows() > 0)
		{
		   foreach ($result->result_array() as $row)
		   {
			   $rows[] = $row;
		   }
		}
			   
		return (count($rows)>0)?$rows:false;
	}
	
	function row($query)
	{
		$result = $this->query($query);
	
	    $row = array();
	
		if ($result->num_rows() > 0)
		{
		  return $result->row_array();
		}
		else
			return false;
			   
	}
	
	function get_kv_list($query,$k = 'id',$v = 'name')
	{
		$result = $this->query($query);
	
	   $rows = array();
	
		if ($result->num_rows() > 0)
		{
		   foreach ($result->result_array() as $row)
		   {
			   $rows[$row[$k]] = $row[$v];
		   }
		}
			   
		return (count($rows)>0)?$rows:false;
	}
	
	function get_k_list($query,$k = 'id')
	{
		$result = $this->query($query);
	
	   	$rows = array();
	
		if ($result->num_rows() > 0)
		{
		   foreach ($result->result_array() as $row)
		   {
			   $rows[$row[$k]] = $row;
		   }
		}
			   
		return (count($rows)>0)?$rows:false;
	}
	
	function get_group_list($query,$k = 'id',$k2)
	{
		$result = $this->query($query);
	
	   	$rows = array();
	
		if ($result->num_rows() > 0)
		{
		   foreach ($result->result_array() as $row)
		   {
			   $rows[$row[$k]][$row[$k2]] = $row;
		   }
		}
			   
		return (count($rows)>0)?$rows:false;
	}
	
	function get_count($table,$filter='1=1')
	{	
		return $this->ci->db->query("SELECT count(*) as cnt from  `".$table."` WHERE $filter")->row()->cnt;
	}
	
	
	function display_ajax_pagination($page = 0,$table = '',$filter='1=1')
	{
		return $this->display_pagination($table,'/',$page,5,$filter,true);
	}
	
	function display_pagination($table = '',$base_url = '/',$page = 0,$uri_segment = 5,$filter='1=1',$is_ajax=false)
	{
		$total_items = $this->get_count($table,$filter);
		
		$this->ci->load->library('pagination');

		$config['use_page_numbers'] = TRUE;
		$config['uri_segment'] = $uri_segment;
		//$config['num_links'] = 2;
		$config['base_url'] = $base_url; //'http://example.com/index.php/test/page/';
		$config['total_rows'] = $total_items; //200;
		$config['per_page'] = $this->items_per_page; 
		$config['full_tag_open'] = '<div><ul class="pagination">';
		$config['full_tag_close'] = '</ul></div>';
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'page';
		
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
		
		if(!$is_ajax)
			return $this->ci->pagination->create_links();
		else {
			return $this->ci->pagination->create_ajax_links($page);
		}
	}
	
		
}
?>
