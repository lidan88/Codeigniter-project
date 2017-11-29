<?

class Library_values
{
	var $items_per_page = 500;
	var $library_items_explained = false;
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
	
	function delete($id)
	{
		$this->ci->db->query("DELETE FROM `library_values` WHERE `id`='$id'");
		return true;
	}
	
	function get_count($filter='1=1')
	{	
		return $this->ci->db->query("SELECT count(*) as cnt from  `library_values` WHERE $filter")->row()->cnt;
	}
	
	/*
		$this->library_values->get_select_box($this->company_id,'Employees','First Name');
	*/
	function get_select_box($company_id,$library_name,$item_name)
	{
		$this->ci->load->library("api_lib");
		$item_id = $this->row("select id,library_id from library_items where var_name=".$this->escape($item_name)." and library_id=(select l2.`id` from library l2 where l2.name='".$library_name."' and l2.company_id=".$company_id." limit 1)");
		$list = $this->ci->api_lib->get_list("select id,`value` from library_values where company_id=".$company_id." and library_id = (select l2.`id` from library l2 where l2.name='".$library_name."' and l2.company_id=".$company_id.")");
		
		$items=array();
		if(isset($item_id['id']))
		{
			foreach ($list as $key => $value) {
				$t = json_decode($value['value'],TRUE);
				//echo $t[$item_id['id']].'<br />';
				$items[$company_id.OPT_SEPERATOR.$item_id["library_id"].OPT_SEPERATOR.$item_id['id'].OPT_SEPERATOR.$value["id"].OPT_SEPERATOR.$t[$item_id['id']]]=$t[$item_id['id']];
			}
		}
		//var_dump($item_id);
		return $items;
	}
	
	/*
		$this->library_values->get_select_box($this->company_id,'Employees','First Name');
	*/
	function get_select_box_by_libitemid($library_id,$item_id)
	{
		
		if($library_id=='')
			return false;
			
		$this->ci->load->library("api_lib");
		$list = $this->ci->api_lib->get_list("select id,`value` from library_values where library_id = ".$library_id);
		
		$items=array();
		if(is_array($list) and count($list)>0)
		foreach ($list as $key => $value) {
			$t = json_decode($value['value'],TRUE);
			//echo $t[$item_id['id']].'<br />';
			if(!is_array($item_id))
				$items[$library_id.OPT_SEPERATOR.$item_id.OPT_SEPERATOR.$value["id"].OPT_SEPERATOR.$t[$item_id]]=$t[$item_id];
			else {
				$fields_to_display = array();
				foreach ($item_id as $iiv) {
					if(isset($t[$iiv]))
						$fields_to_display[] = $t[$iiv];
				}
				if(is_array($fields_to_display) and count($fields_to_display)>0)
				{
					$fields_to_display = implode(' ', $fields_to_display);
					$items[$library_id.OPT_SEPERATOR.$item_id[0].OPT_SEPERATOR.$value["id"].OPT_SEPERATOR.$fields_to_display]=$fields_to_display;
				}
			}
		}
		//var_dump($item_id);
		return $items;
	}
	
	
	function get_locations_for_map($value_id,$library_item_id)
	{
		$this->ci->load->library("api_lib");
		$locationsList=array();
		
		$value_data = $this->ci->api_lib->row("select `value` from library_values where id=".$value_id);
		$value_data = json_decode($value_data['value'],true);
		$locations = $value_data[$library_item_id];
		
		$location_ids = array();
		$library_id = 0;
		foreach ($locations as $key => $value) {
			$value = explode(OPT_SEPERATOR,$value);
			$location_ids[] = $value[2];
			$library_id = $value[0];
		}
		//$location_attached = $this->ci->api_lib->row("select var_value from library_items where `id`=".$this->ci->api_lib->escape($library_item_id));
		//if($location_attached!=false)
		//{
		//	$location_attached = explode(':',$location_attached['var_value']);
		//	$library_id = $location_attached[0];
			
			$data = $this->select_library_columns_with_data($this->company_id,$library_id,false,$location_ids);
			
			foreach ($data as $key => $address) {
				$possible_address = '';	
				if(isset($address['Address']) and trim($address['Address'])!='')
					$possible_address .= $address['Address'].', ';
					
				if(isset($address['Address 2']) and trim($address['Address 2'])!='')
					$possible_address .= $address['Address 2'].', ';
					
				if(isset($address['City'])  and trim($address['City'])!='')
					$possible_address .= $address['City'].', ';
					
				if(isset($address['State'])  and trim($address['State'])!='')
					$possible_address .= $address['State'].', ';
				
				if(isset($address['Zip'])  and trim($address['Zip'])!='')
					$possible_address .= $address['Zip'].', ';
					
				if(isset($address['Country'])  and trim($address['Country'])!='')
					$possible_address .= $address['Country'].', ';
				
				$locationsList[] = substr($possible_address, 0, strlen($possible_address)-2);
				
			}
		//}
		
		return $locationsList;
	}
	
	
	/*
		$this->library_values->select_library_columns_with_data($this->company_id,'Employees',array('First Name',
																									'Last Name',
																									'Department'));
	*/
	function select_library_columns_with_data($company_id,$library_name,$columns = false,$search = '',$sort = '',$ord = 'ASC')
	{
		global $global_cmp_key;
		
		$this->ci->load->library("api_lib");
		$this->ci->load->library("library_lib");
		
		if($company_id==0)
			$company_id=$this->company_id;
		
		$filter = '';
		if($search!='' and !is_array($search))
			$filter = "`value` like ".$this->ci->db->escape('%'.$search.'%')." and ";
		else if(is_array($search))
		{
			$filter = filterQueryFromArray($search,"`id`","`value`");
		}
	
		if(!is_numeric($library_name))
		{
			$lib_id = $this->ci->library_lib->get_library_id_by_name($company_id,$library_name);
			$library_items = $this->ci->library_lib->explain($lib_id,'var_name');
		}	
		else {
			$lib_id = $library_name;
			$library_items = $this->ci->library_lib->explain($lib_id,'var_name');
			
		}
		
		
		//die("select id,`value` from library_values where ".$filter." company_id=".$company_id." and library_id = ".$lib_id);
		//$list = $this->ci->api_lib->get_kv_list("select id,`value` from library_values where ".$filter." company_id=".$company_id." and library_id = (select l2.`id` from library l2 where l2.name='".$library_name."' and l2.company_id=".$company_id.")","id","value");
		$list = $this->ci->api_lib->get_kv_list("select id,`value` from library_values where ".$filter." company_id=".$company_id." and library_id = ".$lib_id,"id","value");
		
		
		//print_r($list);die;
		//print_r($library_items);die;
		//$item_id = $this->ci->api_lib->get_kv_list("select id,library_id from library_items where var_name IN (".$this->escape($item_name).") and library_id=(select l2.`id` from library l2 where l2.name='".$library_name."' and l2.company_id=".$company_id." limit 1)");
		
		$selected_column_ids=array();
		
		if($columns!=false)
		{
			foreach($columns as $column_name)
			{
				if(isset($library_items[$column_name]))
					$selected_column_ids[$column_name] = $library_items[$column_name]['id'];
			}
		}
		else {
			foreach ($library_items as $column_name => $lv) {
				$selected_column_ids[$column_name] = $lv['id'];
			}
		}
		
		$selected_column_names = array_flip($selected_column_ids);
		
		//print_r($selected_column_ids);die;
		
		//print_r($list);die;
		
		$items=array();
		if(is_array($list))
		{
			foreach ($list as $list_id => $values) {
				$t = json_decode($values,TRUE);
				
				$formatedList = array();
				foreach($t as $library_item_id => $data)
				{
					if(in_array($library_item_id,$selected_column_ids)){
					
					
						if(is_array($data))
						{
							$formatedList[$selected_column_names[$library_item_id]]=array_recursive_value($data);
						}
						else {
							$formatedList[$selected_column_names[$library_item_id]]=$data;
						}
						
					
					}
				}
				
				//print_r($formatedList);die;
				//print_r($t);die;
				$items[$list_id] = $formatedList;
				//$items[$list_id] = 
				//echo $t[$item_id['id']].'<br />';
				//$items[$company_id.OPT_SEPERATOR.$item_id["library_id"].OPT_SEPERATOR.$item_id['id'].OPT_SEPERATOR.$value["id"]]=$t[$item_id['id']];
			}
		
		}
		else {
			return array();
		}
		//print_r($items);die;
		
		$global_cmp_key=$sort;
		if($sort!='')
		{
			if($ord=='ASC')
				uasort($items, 'global_cmp');
			else
				uasort($items, 'global_rcmp');
		}
		
		//var_dump($item_id);
		return $items;
	}

	function format_by_name_data($company_id,$library_name,$values,$allowOutputInArray=false)
	{
		global $global_cmp_key;
		
		$this->ci->load->library("library_lib");
		
		if(!$this->library_items_explained)
			$this->library_items_explained = $this->ci->library_lib->explain($this->ci->library_lib->get_library_id_by_name($company_id,$library_name),'id');
		
		$t = json_decode($values,TRUE);
		
		$formatedList = array();
		if(is_array($t))
		foreach($t as $library_item_id => $data)
		{
			if(isset($this->library_items_explained[$library_item_id]))
			{

				//print_r($t[227]);
				//print_r($this->library_items_explained[$library_item_id]);
//				die;
			
				if(is_array($data) and count($data)>0 and !$allowOutputInArray)
				{
					$formatedList[$this->library_items_explained[$library_item_id]['var_name']]	=	array_recursive_value($data);
				}
				else {
					$formatedList[$this->library_items_explained[$library_item_id]['var_name']]	=	$data;
				}
			}
		}
		//print_r($formatedList);
		//die;
		return $formatedList;
	}

	function detail($item_id)
	{
	
			return $this->row("select 
				*
				from 
					`library_values`
				WHERE 
					id=".$this->escape($item_id));	
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
	
	function display_pagination($base_url = '/',$page = 0,$filter ='1=1')
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
	
	function get_field_types()
	{
		return array("T"=>"Text Box",
					"TA"=>"Text Area",
					"R"=>"Radio",
					"C"=>"Check Box",
					"S"=>"Select Box",
					"D"=>"Dropdown",
					"F"=>"File"
					);
	}
	
	function get_field_value_types()
	{
		return array("T"=>"Simple Text",
					"P"=>"PHP Evaluate",
					"Q"=>"Mysql Query",
					"L"=>"Comma Seperated List"
					);
	}
		
}

?>