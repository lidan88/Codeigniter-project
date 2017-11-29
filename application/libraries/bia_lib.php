<?

class Bia_lib
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
	
	function add($answer_id,$status,$modified_by)
	{
		$this->ci->db->query("INSERT INTO `bia` (`library_value_id`,`status`,`modified_on`,`modified_by`) VALUES (".$this->escape($answer_id).",".$this->escape($status).",NOW(),".$this->escape($modified_by).")");
		return true; 
	}
	
	function update($bia_id,$status,$modified_by)
	{
		$this->ci->db->query("UPDATE `bia` set `status`=".$this->escape($status).",`modified_on`=NOW(),`modified_by`=".$this->escape($modified_by)." WHERE `bia_id`='$bia_id'");
		return true; 
	}
		
	function delete($bia_id)
	{
		$this->ci->db->query("DELETE FROM `bia` WHERE `bia_id`='$bia_id'");
		return true;
	}
	
	function submit_for_approval($bia_id)
	{
		//die($bia_id);
		//echo "UPDATE `bia` set `status`='Waiting' WHERE `bia_id`=".$bia_id;
		$this->ci->db->query("UPDATE `bia` set `status`='Pending Approval' WHERE `bia_id`=".$bia_id);
	}
	
	function approve($bia_id)
	{
		$this->ci->db->query("UPDATE `bia` set `status`='Approved' WHERE `bia_id`=".$bia_id);
	}
	
	function reject($bia_id)
	{
		$this->ci->db->query("UPDATE `bia` set `status`='Rejected' WHERE `bia_id`=".$bia_id);
	}
	
	function get_count($filter='1=1')
	{	
		return $this->ci->db->query("SELECT count(*) as cnt from  `bia` b WHERE $filter")->row()->cnt;
	}
	
	function get_list($page = 0,$company_id,$filter = '',$sort = 'bia_id',$ord = 'ASC')
	{
		$this->ci->load->library("library_values");
		$this->ci->load->library("api_lib");
		
		
		if($page>0)
			$page--;
		
		$start = $page * $this->items_per_page;
		
		if($sort!='')
			$sort = 'order by '.$sort.' '.$ord;
		
		if($filter!='')
			$filter = 'and '.$filter;
			
		$items = $this->ci->api_lib->get_list("select 
					b.*,
					u.first_name as fmodified_by,
					DATE_FORMAT(b.modified_on,'%m/%d/%Y %r') as modified_on,
					lv.`value` as info 
				from 
					`bia` b,
					library_values lv,
					users u
				WHERE
					b.modified_by=u.user_id and
					lv.id=b.library_value_id
							$filter 
						
							$sort 
						limit $start,".$this->items_per_page);
	
	
		if(is_array($items) and count($items)>0)
		{
			foreach ($items as $key => $value) {
				$items[$key]['info'] = $this->ci->library_values->format_by_name_data($company_id,'BIA',$value['info']);
				//print_r($items[$key]);
				//die;
			}
		}
		
//		print_r($items);
//		die;
		
		
		return $items;
	}
	
	function empty_row()
	{
		$columns = "`name`,`description`,`library_value_id`,`status`,`modified_on`,`modified_by`";
		$columns_array = explode(',',$columns);
		$empty_row = array();
		foreach ($columns_array as $c) {
			$c = str_replace('`', '', $c);
			$empty_row[$c]="";
		}
		
		$empty_row["bia_id"]="";
		
		return $empty_row;
	}
	
	function detail($item_id)
	{
		
		return $this->row("select 
				`bia`.*,
				lv.value as survey
			from 
				`bia`,
				library_values lv
			WHERE 
				lv.id=bia.library_value_id and
				`bia`.bia_id=".$this->escape($item_id));	
	
	}
	
	function copy($bia_id)
	{
		$this->ci->load->library("library_lib");
		$item = $this->detail($bia_id);
		
		$new_library_id = $this->ci->library_lib->copy_user_data($item['library_value_id']);
		
		$this->ci->db->query("insert into bia (library_value_id,status,modified_on,modified_by)
								VALUES (".$new_library_id.",'In Progress',NOW(),".$this->user_id.")");
	
		$new_bia_id = $this->ci->db->insert_id();
		return $new_bia_id;
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