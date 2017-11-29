<?

class Call_chain_lib
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
	
	function add($name,$description,$call_chain,$modified_by)
	{
		$this->ci->db->query("INSERT INTO `call_chain` (`company_id`,`name`,`description`,`call_chain`,`modified_on`,`modified_by`) VALUES (".$this->escape($this->company_id).",".$this->escape($name).",".$this->escape($description).",".$this->escape($call_chain).",NOW(),".$this->escape($modified_by).")");
		return true; 
	}
	
	function update($call_chain_id,$name,$description,$call_chain,$modified_by)
	{
		$this->ci->db->query("UPDATE `call_chain` set `name`=".$this->escape($name).",`description`=".$this->escape($description).",`call_chain`=".$this->escape($call_chain).",`modified_on`=NOW(),`modified_by`=".$this->escape($modified_by)." WHERE `call_chain_id`='$call_chain_id'");
		return true; 
	}
	
	function delete($call_chain_id)
	{
		$this->ci->db->query("DELETE FROM `call_chain` WHERE `call_chain_id`='$call_chain_id'");
		return true;
	}
	
	function get_count($filter='1=1')
	{	
		return $this->ci->db->query("SELECT count(*) as cnt from  `call_chain` WHERE $filter")->row()->cnt;
	}
	
	function get_list($page = 0,$filter = '',$sort = 'call_chain_id',$ord = 'ASC')
	{
		$this->ci->load->library("api_lib");
		
		if($page>0)
			$page--;
		
		$start = $page * $this->items_per_page;
		
		if($sort!='')
			$sort = ' order by '.$sort.' '.$ord;
		
		if($filter!='')
			$filter = ' and '.$filter;
		
		return $this->ci->api_lib->get_list("select 
					c.*,
					DATE_FORMAT(c.modified_on,'%m/%d/%Y %r') as modified_on,
					u.first_name as fmodified_by
				from 
					`call_chain` c,
					users u
				WHERE
					c.modified_by=u.user_id and
					c.company_id=".$this->company_id."
						$filter 
						
							$sort 
						limit $start,".$this->items_per_page);
	}
	
	function get_user_data_by_ids($values)
	{
		$this->ci->load->library("api_lib");
		
		if(is_array($values))
			$values = implode(",", $values);
		
		return $this->ci->api_lib->get_k_list("select 
					c.*,
					u.first_name as fmodified_by
				from 
					`call_chain` c,
					users u
				WHERE
					c.modified_by=u.user_id and
					c.company_id=".$this->company_id." and
				 	c.call_chain_id in (".$values.") order by FIELD(call_chain_id,".$values.")","call_chain_id");
	}
	
	function fetch_normalized_call_chains_by_ids($values)
	{
		$data = $this->get_user_data_by_ids($values);
		
		$this->ci->load->library("library_values");
		
		$call_chains = array();
		
		if(is_array($data))
		{
			foreach ($data as $key => $value) {
				$data[$key]['call_chain'] = normalize_tree(json_decode($value['call_chain'],true));
				
				$call_chains[] = array("name"=>$value['name'],
										"call_chain"=>$data[$key]['call_chain']);
			}
		
			$employees_to_get_info_on = array();
			foreach ($call_chains as $key => $value) {
				
				foreach($value['call_chain'] as $arr)
				{
					$employees_to_get_info_on[$arr[0]] = $arr[0];
					$employees_to_get_info_on[$arr[1]] = $arr[1];
				}
			
				if(isset($employees_to_get_info_on[0]))
					unset($employees_to_get_info_on[0]);
				
				$employees = array();
				$employees = $this->ci->library_values->select_library_columns_with_data($this->company_id,'Employees',false,$employees_to_get_info_on);
				
				$call_chains[$key]['employees'] = $employees;
			}
		}

		return $call_chains;
	}
	
	function detail($item_id)
	{
			return $this->row("select 
			`call_chain`.* 
		from 
			`call_chain` WHERE `call_chain`.call_chain_id=".$this->escape($item_id));	
	}
	
	function copy($item_id)
	{
		$this->ci->db->query("INSERT IGNORE INTO call_chain (company_id,`name`,description,call_chain,modified_by) (select 
			c.company_id,
			CONCAT_WS(' ',c.`name`,'Copy'),
			c.description,
			c.call_chain,
			c.modified_by
		from 
			`call_chain` c WHERE c.call_chain_id=".$this->escape($item_id).")");	
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