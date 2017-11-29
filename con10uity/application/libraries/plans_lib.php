<?

class Plans_lib
{
	var $items_per_page = 10;
	var $company_id=0;
	var $user_id=0;

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
	
	function add($library_value_id,$modified_by)
	{
		$this->ci->db->query("INSERT INTO `plans` (`library_value_id`,`modified_on`,`modified_by`) VALUES (".$this->escape($library_value_id).",NOW(),".$this->escape($modified_by).")");
		return $this->ci->db->insert_id();
		//return true; 
	}
	
	function update($plan_id,$modified_by)
	{
		$this->ci->db->query("UPDATE `plans` set `modified_on`=NOW(),`modified_by`=".$this->escape($modified_by)." WHERE `plan_id`='$plan_id'");
		return true; 
	}
	
	function copy($plan_id)
	{
		$this->ci->load->library("library_lib");
		$item = $this->detail($plan_id);
		
		$new_library_id = $this->ci->library_lib->copy_user_data($item['library_value_id']);
		
		$this->ci->db->query("insert into plans (library_value_id,modified_on,modified_by)
								VALUES (".$new_library_id.",NOW(),".$this->user_id.")");
	
		$new_plan_id = $this->ci->db->insert_id();
		
		$this->ci->db->query("insert into plan_items (plan_id,plan_type,library_id,template_id,title,description,footer,library_items,order_by,modified_by)  
		(SELECT ".$new_plan_id.",pi2.plan_type,pi2.library_id,pi2.template_id,pi2.title,pi2.description,pi2.footer,pi2.library_items,pi2.order_by,pi2.modified_by from plan_items pi2 where pi2.plan_id=".$plan_id.")");
		
		return $new_plan_id;
	}
	
	function submit_for_approval($plan_id)
	{
		$this->ci->db->query("UPDATE `plans` set `status`='Pending Approval',`modified_on`=NOW(),`modified_by`=".$this->escape($this->user_id)." WHERE `plan_id`='$plan_id'");
		return true; 
	}
	
	function approve($plan_id)
	{
		$this->ci->db->query("UPDATE `plans` set `status`='Approved',`modified_on`=NOW(),`modified_by`=".$this->escape($this->user_id)." WHERE `plan_id`='$plan_id'");
		return true; 
	}
	
	function reject($plan_id,$notes)
	{
		$this->ci->db->query("UPDATE `plans` set `status`='Rejected',`notes`=".$this->escape($notes).",`modified_on`=NOW(),`modified_by`=".$this->escape($this->user_id)." WHERE `plan_id`='$plan_id'");
		return true; 
	}
	
	function save_plan_item($plan_id,$library_id,$plan_type,$template_id,$title,$description,$footer,$library_items)
	{
		$this->ci->db->query("INSERT INTO `plan_items` 
										set 
											`plan_id`=".$this->escape($plan_id).",
											`library_id`=".$this->escape($library_id).",
											`plan_type`=".$this->escape($plan_type).",
											`template_id`=".$this->escape($template_id).",
											`title`=".$this->escape($title).",
											`description`=".$this->escape($description).",
											`footer`=".$this->escape($footer).",
											`modified_by`=".$this->escape($this->user_id).",
											`library_items`=".$this->escape($library_items));
		return true; 
	}
	
	function update_plan_item($plan_item_id,$plan_id,$library_id,$plan_type,$template_id,$title,$description,$footer,$library_items)
	{
		$this->ci->db->query("UPDATE `plan_items` 
										set 
											`plan_id`=".$this->escape($plan_id).",
											`library_id`=".$this->escape($library_id).",
											`plan_type`=".$this->escape($plan_type).",
											`template_id`=".$this->escape($template_id).",
											`title`=".$this->escape($title).",
											`description`=".$this->escape($description).",
											`footer`=".$this->escape($footer).",
											`modified_by`=".$this->escape($this->user_id).",
											`modified_on`=NOW(),
											`library_items`=".$this->escape($library_items)."
										WHERE
											id=".$this->escape($plan_item_id));
		return true; 
	}
	
	function delete($plan_id)
	{
		$this->ci->db->query("DELETE FROM `plans` WHERE `plan_id`='$plan_id'");
		return true;
	}
	
	function delete_plan_item($plan_item_id)
	{
		$this->ci->db->query("DELETE FROM `plan_items` WHERE `id`='$plan_item_id'");
		return true;
	}
	
	function get_count($filter='1=1')
	{	
		return $this->ci->db->query("SELECT count(*) as cnt from  `plans` p WHERE $filter")->row()->cnt;
	}
	
	function get_plan_items($plan_id)
	{
		$this->ci->load->library("api_lib");
		$this->ci->load->library("library_lib");
		$this->ci->load->library("call_chain_lib");
		//$this->ci->load->library("risk_assessment_lib");
		
		$items = $this->ci->api_lib->get_list("select 
													pi.*,
													u.username as modified_by,
													DATE_FORMAT(pi.modified_on,'%m/%d/%Y %r') as modified_on,
													lt.template,
													lt.`sort_by`,
													lt.`order_by`
												from 
													users u,
													plan_items pi LEFT JOIN library_template lt on (lt.id=pi.template_id)
													
													
												where 
													u.user_id=pi.modified_by and
													plan_id=".$plan_id." 
												order by pi.order_by ASC");
		//$items['library_items'] = $this->ci->library_values->format_by_name_data($this->company_id,'PLANS',$value['info']);
		
		if(is_array($items))
		foreach ($items as $key => $value) {
			//if($value['plan_type']!='lib')continue;
			$items[$key]['library_items'] = json_decode($items[$key]['library_items'], true);
			
			if($value['plan_type']=='lib' || $value['plan_type']=='gallery')
			{
				
				if($items[$key]['template']!="")
					$items[$key]['template'] = json_decode($items[$key]['template'],true);
				else {
					$items[$key]['template_default'] = $this->ci->library_lib->explain($value['library_id']);
				}
			}
			else if($value['plan_type']=='cc')
			{
				$items[$key]['call_chains'] = $this->ci->call_chain_lib->fetch_normalized_call_chains_by_ids($items[$key]['library_items']); 
			}
			else if($value['plan_type']=='ra')
			{
				$this->ci->load->library("threats_lib");
				$this->ci->load->library("threat_analysis_lib");
				
				//TBD
				$items[$key]['threats_by_group'] = $this->ci->threats_lib->get_list_by_group(0); //'company_id='.$this->company_id);
			
				foreach ($items[$key]['library_items'] as $risk_assessment_id) {
					$items[$key]['ra'][$risk_assessment_id] = $this->ci->threat_analysis_lib->get_list($risk_assessment_id);
				}
				
			}
		
		}
		
		
		// Load selected items into the array
		if(is_array($items))
		foreach ($items as $key => $value) {
			if($value['plan_type']!='lib' and $value['plan_type']!='gallery')continue;
			
			//$data['plan_items'][$key]['details'] = $this->plans_lib->get_plan_item_details($value['id']);
			$data['plan_selected_items_temp'] = $this->ci->library_lib->get_user_data_by_ids(implode(',', $value['library_items']));
			foreach ($data['plan_selected_items_temp'] as $k => $psiv) {
				$data['plan_selected_items_temp'][$k]['value'] = json_decode($data['plan_selected_items_temp'][$k]['value'],true);
			}
			
			foreach ($data['plan_selected_items_temp'] as $k => $psi) {
				$items[$key]['selected_items'][$k] = $psi;
			}
		}
		
		/*echo '<pre>';
		print_r($items);
		echo '</pre>';
		die;*/
		
		return $items;
	}
	
	function get_plan_item_details($plan_item_id)
	{
		$this->ci->load->library("api_lib");
		$this->ci->load->library("library_values");
		
		$item = $this->ci->api_lib->row("select 
													pi.*,
													u.username as modified_by,
													DATE_FORMAT(pi.modified_on,'%m/%d/%Y %r') as modified_on
												from 
													plan_items pi,
													users u
												where 
													u.user_id=pi.modified_by and
													id=".$plan_item_id);
	
		$item['library_items'] = json_decode($item['library_items'], true);
	
		return $item;
	}
	
	function get_list($page = 0,$filter = '',$sort = 'plan_id',$ord = 'ASC')
	{
		$this->ci->load->library("api_lib");
		$this->ci->load->library("library_values");
		
		if($page>0)
			$page--;
		
		$start = $page * $this->items_per_page;
		
		if($sort!='' and !is_numeric($sort))
			$sort = 'order by '.$sort.' '.$ord;
		else {
			$sort='';
		}
		
		if($filter!='')
			$filter = 'and '.$filter;
		
		$items =  $this->ci->api_lib->get_list("select 
					p.*,
					u.first_name as fmodified_by,
					DATE_FORMAT(p.modified_on,'%m/%d/%Y %r') as modified_on,
					lv.`value` as info 
				from 
					`plans` p,
					library_values lv,
					users u
					
				WHERE
					p.modified_by=u.user_id and
					lv.id=p.library_value_id
					
							$filter 
						
							$sort 
						limit $start,".$this->items_per_page);
		
		if(is_array($items) and count($items)>0)
		foreach ($items as $key => $value) {
			$items[$key]['info'] = $this->ci->library_values->format_by_name_data($this->company_id,'PLANS',$value['info']);
		}
		
		return $items;
		
	}
	
	function detail($item_id)
	{
		$this->ci->load->library("library_values");
		$item = $this->row("select 
					p.*,
					u.first_name as fmodified_by,
					DATE_FORMAT(p.modified_on,'%m/%d/%Y %r') as modified_on,
					lv.`value` as info,
					lv.unique_id
				from 
					`plans` p,
					library_values lv,
					users u
					
				WHERE 
					p.modified_by=u.user_id and
					lv.id=p.library_value_id and
					p.plan_id=".$this->escape($item_id));	
	
		$item['formated_info'] = $this->ci->library_values->format_by_name_data($this->company_id,'PLANS',$item['info']);
		$item['info'] = json_decode($item['info'],TRUE);
		
		return $item;
	}
	
	function empty_row()
	{
		$columns = "`library_value_id`,`notes`,`status`,`modified_on`,`modified_by`";
		$columns_array = explode(',',$columns);
		$empty_row = array();
		foreach ($columns_array as $c) {
			$c = str_replace('`', '', $c);
			$empty_row[$c]="";
		}
		
		$empty_row["plan_id"]="";
		$empty_row['unique_id']="";
		
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