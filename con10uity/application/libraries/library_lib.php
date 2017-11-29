<?

class Library_lib
{
	// code to copy
	//INSERT IGNORE INTO modules (`title`,`name`,`group`) (select distinct `name`,CONCAT_WS('-','library',REPLACE(LCASE(`name`),' ','-')),"library" from library)
	var $items_per_page = 100;
	var $company_id=0;

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
	
	function add($name,$company_id=0,$parent_id =1,$is_system=0,$is_visible=1,$logo)
	{
		$this->ci->db->query("INSERT INTO `library` (`company_id`,`name`,`parent_id`,`is_system`,`is_visible`,`added`,`logo`) VALUES (".$this->escape($company_id).",".$this->escape($name).",".$this->escape($parent_id).",".$this->escape($is_system).",".$this->escape($is_visible).",NOW(),".$this->escape($logo).")");
		$this->ci->db->query("INSERT IGNORE INTO `modules` (`name`,`title`,`group`) VALUES (".$this->escape(get_libuserid_from_name($name)).",".$this->escape($name).",'library')");
		return true; 
	}
	
	function update($id,$name,$parent_id =1,$is_system=0,$is_visible=1,$logo)
	{
		$this->ci->db->query("UPDATE `library` set `name`=".$this->escape($name).",`parent_id`=".$this->escape($parent_id).",`is_system`=".$this->escape($is_system).",`logo`=".$this->escape($logo).",`is_visible`=".$this->escape($is_visible)." WHERE `id`='$id'");
		return true; 
	}
	
	function save_user_data($user_id,$library_id,$company_id,$itemsEncoded,$unique_id = '',$force_no_override=false)
	{
		if(!is_numeric($library_id))
		{
			$library_id=$this->get_library_id_by_name($company_id,$library_id);	
		}
		
		if($unique_id=='')
			$unique_id = $this->ci->db->query('SELECT COALESCE(MAX(id),0) as max_id from library_values')->row()->max_id + 1;
	
		
		if(!$force_no_override)
		{
			$this->ci->db->query("INSERT INTO `library_values` set
										`unique_id`=".$this->escape($unique_id).",
										`user_id`=".$this->escape($user_id).",
										`library_id`=".$this->escape($library_id).",
										`company_id`=".$this->escape($company_id).",
										`value`=".$this->escape($itemsEncoded).",
										`modified_by_uid`=".$this->escape($user_id).",
										`modified_at`=NOW()
								ON DUPLICATE KEY UPDATE
										`value`=".$this->escape($itemsEncoded));
		}
		else {
			$this->ci->db->query("INSERT IGNORE INTO `library_values` set
										`unique_id`=".$this->escape($unique_id).",
										`user_id`=".$this->escape($user_id).",
										`library_id`=".$this->escape($library_id).",
										`company_id`=".$this->escape($company_id).",
										`value`=".$this->escape($itemsEncoded).",
										`modified_by_uid`=".$this->escape($user_id).",
										`modified_at`=NOW()");
		}
	
		if(!$this->ci->db->affected_rows())	
			return 0;
		else
			return $this->ci->db->insert_id();
	}

	function update_user_data($value_id,$user_id,$itemsEncoded,$unique_id = '')
	{
		if($unique_id!='')
		{
			$this->ci->db->query("UPDATE `library_values` set
										`unique_id`=".$this->escape($unique_id).",
										`value`=".$this->escape($itemsEncoded).",
										`modified_by_uid`=".$user_id.",
										`modified_at`=NOW()
									WHERE
									id=".$value_id);
		}
		else {
			$this->ci->db->query("UPDATE `library_values` set
										`value`=".$this->escape($itemsEncoded).",
										`modified_by_uid`=".$user_id.",
										`modified_at`=NOW()
									WHERE
									id=".$value_id);
		}

	}
	
	function copy_user_data($value_id)
	{
		$new_unique_id = $this->ci->db->query('SELECT COALESCE(MAX(id),0) as max_id from library_values')->row()->max_id + 1;
		
		$this->ci->db->query("INSERT IGNORE INTO `library_values` (company_id,user_id,library_id,unique_id,value,added,modified_by_uid,modified_at)
								(select company_id,".$this->user_id.",library_id,".$new_unique_id.",value,NOW(),".$this->user_id.",NOW() from `library_values` lv2 where lv2.id=".$value_id.")");
	
		return $this->ci->db->insert_id();
	}

	function return_rendered_items($library_id,$replace_with = '',$replace_with_array = '')
	{
		if(!is_numeric($library_id))
		{
			$library_id=$this->get_library_id_by_name($this->company_id,$library_id);
		}
		
		$this->ci->load->library("library_items_lib");
		return $this->ci->library_items_lib->return_rendered_items($library_id,$replace_with,$replace_with_array);
	}
	
	function encode_user_post_data()
	{
		$this->ci->load->library("library_items_lib");
		return $this->ci->library_items_lib->encode_user_post_data();
	}

	function get_user_data($page = 0,$library_id,$search = '')
	{
		if($page>0)
			$page--;
		
		$start = $page * $this->items_per_page;
		
		$filter = '';
		if($search!='' and !is_array($search))
			$filter = "lv.`value` like ".$this->ci->db->escape('%'.$search.'%')." and ";
		else if(is_array($search))
		{
			$filter = filterQueryFromArray($search,"lv.`id`","lv.`value`");
		}
		
		$this->ci->load->library("api_lib");
		return $this->ci->api_lib->get_k_list("select lv.*,DATE_FORMAT(lv.modified_at,'%m/%d/%Y %r') as modified_at,u.username as modified_by from library_values lv,users u where ".$filter." lv.modified_by_uid=u.user_id and lv.library_id=".$library_id." order by id ASC limit $start,".$this->items_per_page);
		
	
	}
	
	function get_library_values_count($library_id,$search)
	{
		$filter = '';
		if($search!='' and !is_array($search))
			$filter = "`value` like ".$this->ci->db->escape('%'.$search.'%')." and ";
		else if(is_array($search))
		{
			$filter = filterQueryFromArray($search,"`id`","`value`");
		}
		
		return $this->ci->api_lib->get_count("library_values",$filter." library_id=".$library_id);
		
	}
	
	function get_user_data_by_id($value_id)
	{
		$this->ci->load->library("api_lib");
		return $this->ci->api_lib->row("select * from library_values where id=".$value_id);
	}
	
	function get_user_data_by_ids($values)
	{
		$this->ci->load->library("api_lib");
		return $this->ci->api_lib->get_k_list("select lv.*,DATE_FORMAT(lv.modified_at,'%m/%d/%Y %r') as modified_at,u.username as modified_by from library_values lv,users u where lv.modified_by_uid=u.user_id and lv.id in (".$values.") order by FIELD(id,".$values.")");
	}
	
	// filter input: [{"c":"114:First Name","o":"=","f":""}]
	function get_user_data_by_filter($filter)
	{
		$this->ci->load->library("api_lib");
		$searchArray = filterByObjToArray($filter);
		$filter = filterQueryFromArray($searchArray,"lv.`id`","lv.`value`");
		
		return $this->ci->api_lib->get_k_list("select lv.*,DATE_FORMAT(lv.modified_at,'%m/%d/%Y %r') as modified_at,u.username as modified_by from library_values lv,users u where ".$filter." lv.modified_by_uid=u.user_id");
	}
	
	function get_user_data_by_libid($library_id)
	{
		$this->ci->load->library("api_lib");
		return $this->ci->api_lib->get_k_list("select lv.*,DATE_FORMAT(lv.modified_at,'%m/%d/%Y %r') as modified_at,u.username as modified_by from library_values lv,users u where lv.modified_by_uid=u.user_id and lv.library_id = ".$library_id." order by lv.id ASC");
	}
	
	function get_user_data_by_plan_item_id($plan_item_id = 0,$library_id)
	{
		$this->ci->load->library("api_lib");
		$row = $this->ci->api_lib->row("select pi.library_items from plan_items pi where pi.id=".$plan_item_id);
		$row['library_items']=json_decode($row['library_items'],true);
		return $this->ci->api_lib->get_k_list("select lv.*,DATE_FORMAT(lv.modified_at,'%m/%d/%Y %r') as modified_at,u.username as modified_by from library_values lv,users u where lv.modified_by_uid=u.user_id and lv.library_id=".$library_id." and lv.id in (".implode(",", $row['library_items']).") order by id ASC");
	}

	function get_user_data_handle_by_ids($values)
	{
		return $this->ci->db->query("select lv.*,DATE_FORMAT(lv.modified_at,'%m/%d/%Y %r') as modified_at,u.username as modified_by from library_values lv,users u where lv.modified_by_uid=u.user_id and lv.id in (".$values.")");
	}
	
	function get_library_id_by_name($company_id,$library_name)
	{
		$ret = $this->row("select id from library where company_id=".$company_id." and `name`=".$this->escape($library_name)." limit 1");
		if(isset($ret['id']))
			return $ret['id'];
		else {
			return 0;
		}
	}
	
	function delete_user_values($ids)
	{
		$this->ci->db->query("delete from library_values where id in (".$ids.")");
	}
	
	/*
		This function will list all fields in the library
	*/
	function explain($library_id,$k = 'id')
	{
		$this->ci->load->library("api_lib");
		
		if(!is_numeric($library_id))
		{
			$library_id = $this->get_library_id_by_name($this->company_id,$library_id);
		}
		
		return $this->ci->api_lib->get_k_list("select * from library_items where library_id=".$library_id." order by item_order ASC",$k);
	}
	
	/*
		This function will list all fields by name in the library
	*/
	function rexplain($library_id,$k = 'id')
	{
		$this->ci->load->library("api_lib");
		//$this->ci->load->library("library_items_lib");
		
		if(!is_numeric($library_id))
		{
			$library_id = $this->get_library_id_by_name($this->company_id,$library_id);
		}
		
		$list = $this->ci->api_lib->get_k_list("select * from library_items where library_id=".$library_id." order by item_order ASC",$k);
		$rlist = array();
		
		if(is_array($list) and count($list)>0)
		foreach ($list as $key => $value) {
			$rlist[$value['var_name']]=$value[$k];
		}
		
		return $rlist;
	}
	
	function delete($id)
	{
		$lib_details = $this->detail($id);
		if($lib_details!=false)
		{
			$this->ci->db->query("DELETE FROM `library` WHERE `id`=".$id);
			$this->ci->db->query("DELETE FROM `library_items` WHERE `library_id`=".$id);
			$this->ci->db->query("DELETE FROM `library_values` WHERE `library_id`=".$id);
			
			$is_module = $this->ci->db->query("select module_id from `modules` where `name`=".$this->ci->db->escape(get_libuserid_from_name($lib_details['name'])));
			if($is_module->num_rows>0)
			{
				$module_id = $is_module->row()->module_id;
				$this->ci->db->query("DELETE FROM `modules` WHERE module_id=".$module_id);
				$this->ci->db->query("DELETE FROM `role_permission` where module_id=".$module_id);
			}
			
			return true;
		}
		else {
			return false;
		}
	}
	
	function get_count($filter='1=1')
	{	
		return $this->ci->db->query("SELECT count(*) as cnt from  `library` WHERE $filter")->row()->cnt;
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
					`library`.* 
				from 
					`library` 
					WHERE
					1=1
							$filter 
						
							$sort 
						limit $start,".$this->items_per_page);
	}
	
	function get_library_selectbox($name = 'library_id',$callback = '')
	{
		$this->ci->load->library("api_lib");
		
		if($callback!='')
			$callback='onchange="'.$callback.'"';
		
		$render = '<select id="'.$name.'" name="'.$name.'" '.$callback.' class="chosen-select form-control">
						<option value=""></option>';
		
		$list =  $this->ci->api_lib->get_list("select 
					`library`.* 
				from 
					`library` 
				where
					company_id=".$this->company_id." and is_visible=1 and is_system=0
				order by name ASC");
		
		foreach ($list as $key => $value) {
			$render .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
		}
		
		$render .= '</select>';
		
		return $render;
	}
	
	function get_library_json()
	{
		$this->ci->load->library("api_lib");
		
		$list =  $this->ci->api_lib->get_list("select 
					`library`.* 
				from 
					`library` 
				where
					company_id=".$this->company_id."
				order by name ASC");
		
		$return_array=array();
		foreach ($list as $key => $value) {
			$return_array[$value['id']] = $value['name'];
		}
		
		return json_encode($return_array);
	}
	
	function detail($item_id)
	{
			return $this->row("select 
					`library`.* 
				from 
					`library` WHERE `library`.id=".$this->escape($item_id));	
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