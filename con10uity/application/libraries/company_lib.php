<?

class Company_lib
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
	
	function add($username,$notes,$phone,$address,$web,$main_contact)
	{
		$this->ci->load->library("sql_lib");
		//die;
		$this->ci->db->query("INSERT INTO `company` (`username`,`notes`,`phone`,`address`,`web`,`main_contact`,`added`) VALUES (".$this->escape($username).",".$this->escape($notes).",".$this->escape($phone).",".$this->escape($address).",".$this->escape($web).",".$this->escape($main_contact).",NOW())");
		
		$company_id =  $this->ci->db->insert_id();
		
		$new_company_db = getDDByCompany($company_id);
		$super_db = "`continuitypro`";
		
		$this->ci->db->query("CREATE DATABASE ".$new_company_db);
		$this->ci->db->query("USE ".$new_company_db);
		$this->ci->sql_lib->processFile("sample_sql.sql",$new_company_db);
		
		$this->ci->db->query("INSERT INTO users set company_id=".$company_id.",
													role_id=1,
													first_name='Admin',
													last_name='User',
													username='admin',
													password='admin123',
													email='admin@localhost.com',
													`status`='ACTIVE',
													timezone='+05:00'");
		
		$this->ci->db->query("INSERT INTO company (select * from ".$super_db.".company where company_id=".$company_id.")");
		
		$this->ci->db->query("insert into library (select * from ".$super_db.".library where company_id=0)");
		$this->ci->db->query("UPDATE library set company_id=".$company_id);
		
		$this->ci->db->query("insert into library_items (select li.* from ".$super_db.".library_items li,".$super_db.".library l where li.library_id=l.id and l.company_id=0)");
		
		$this->ci->db->query("insert into dropdown (select * from ".$super_db.".`dropdown` where company_id=0)");		
		$this->ci->db->query("UPDATE dropdown set company_id=".$company_id);
		
		$this->ci->db->query("insert into dropdown_item (select di.* from ".$super_db.".dropdown_item di,".$super_db.".dropdown d where di.dropdown_id=d.dropdown_id and d.company_id=0)");
			
		$this->ci->db->query("insert into role (select * from ".$super_db.".role where company_id=0)");
		$this->ci->db->query("UPDATE role set company_id=".$company_id);
		
		$this->ci->db->query("insert into role_permission (select rp.* from ".$super_db.".role_permission rp,".$super_db.".role r where r.role_id=rp.role_id and r.company_id=0)");
		
		$this->ci->db->query("insert into threats (select * from ".$super_db.".`threats` where company_id=0)");		
		$this->ci->db->query("UPDATE threats set company_id=".$company_id);
		
		$this->ci->db->query("insert into modules (select * from ".$super_db.".`modules`)");		
		
		
		//$this->ci->
		//$this->copy_default_db($company_id);
		
		return $company_id;
		//return true; 
	}
	
	function update_logo($company_id,$logo)
	{
		$this->ci->db->query("UPDATE `continuitypro`.`company` set `logo`=".$this->escape($logo)." WHERE `company_id`=".$company_id);
		$this->ci->db->query("UPDATE ".getDDByCompany($company_id).".`company` set `logo`=".$this->escape($logo)." WHERE `company_id`=".$company_id);
	}
	
	function set_enable_status($company_id,$enabled)
	{
		$this->ci->db->query("UPDATE `continuitypro`.`company` set `enabled`=".$this->escape($enabled)." WHERE `company_id`=".$company_id);
		$this->ci->db->query("UPDATE ".getDDByCompany($company_id).".`company` set `enabled`=".$this->escape($enabled)." WHERE `company_id`=".$company_id);
	}
	
	function update($company_id,$username,$notes,$phone,$address,$web,$main_contact)
	{
		$this->ci->db->query("UPDATE `company` set `username`=".$this->escape($username).",`notes`=".$this->escape($notes).",`phone`=".$this->escape($phone).",`address`=".$this->escape($address).",`web`=".$this->escape($web).",`main_contact`=".$this->escape($main_contact)." WHERE `company_id`='$company_id'");
		$this->ci->db->query("UPDATE ".getDDByCompany($company_id).".`company` set `username`=".$this->escape($username).",`notes`=".$this->escape($notes).",`phone`=".$this->escape($phone).",`address`=".$this->escape($address).",`web`=".$this->escape($web).",`main_contact`=".$this->escape($main_contact)." WHERE `company_id`='$company_id'");
		
		return true; 
	}
	
	/*
		### copy default library,dropdowns,role ro new company
		$this->company_lib->copy_default_db($company_id);
	*/
	function copy_default_db($company_id)
	{
		// Copying library items.
		$this->ci->db->query("insert into library (company_id,`name`) (select ".$company_id.",`name` from library where company_id=0)");
		$this->ci->db->query("insert into library_items (library_id,var_name,var_type,var_value,var_value_type,is_required,item_order,help) 
		(select (select l2.id from library l2 where l2.name=l.name and l2.company_id=".$company_id.") as library_id,var_name,var_type,var_value,var_value_type,is_required,item_order,help from library_items li,library l where li.library_id=l.id and l.company_id=0);");

		$this->ci->db->query("insert into dropdown (company_id,`name`,is_active) (select ".$company_id.",`name`,is_active from `dropdown` where company_id=0)");		
		$this->ci->db->query("insert into dropdown_item (dropdown_id,`name`,is_active)
		(select (select d2.dropdown_id from dropdown d2 where d2.name=d.name and d2.company_id=".$company_id.") as dropdown_id,di.name,di.is_active from dropdown_item di,dropdown d where di.dropdown_id=d.dropdown_id and d.company_id=0)");
	
		$this->ci->db->query("update library_items li,
				(select li.id library_item_id,li.var_value,d.name,(select d2.dropdown_id from dropdown d2 where company_id=".$company_id." and d2.name=d.name) as new_var_value from library_items li,dropdown d where li.library_id in (select l2.`id` from library l2 where l2.company_id=".$company_id.") and li.var_type='D' and d.dropdown_id=li.var_value) as tbl
				
			set li.var_value=tbl.new_var_value
		where
			li.id=tbl.library_item_id");
	
		$this->ci->db->query("insert into role (company_id,`name`) (select ".$company_id.",`name` from role where company_id=0)");
		$this->ci->db->query("insert into role_permission (role_id,permissions) (select (select r2.role_id from role r2 where r2.company_id=".$company_id." and r2.`name`=r.`name`) as role_id,rp.permissions from role_permission rp,role r where r.role_id=rp.role_id and r.company_id=0)");
	}
	
	function delete($company_id)
	{
		$this->ci->db->query("DELETE FROM `company` WHERE `company_id`='$company_id'");
		$this->ci->db->query("DROP DATABASE IF EXISTS ".getDDByCompany($company_id));
		//$this->ci->db->query("USE `continuitypro_".$company_id."`");
		
		/*
		$this->ci->db->query("DELETE from library_items where library_id in (select id from library where company_id=".$company_id.")");
		$this->ci->db->query("DELETE from dropdown_item where dropdown_id in (select dropdown_id from dropdown where company_id=".$company_id.")");
		$this->ci->db->query("delete from role_permission where role_id in (select r.role_id from role r where company_id=".$company_id.")");
		$this->ci->db->query("delete from library_values where company_id=".$company_id);
		
		$this->ci->db->query("DELETE from `library` where company_id=".$company_id);
		$this->ci->db->query("DELETE from `dropdown` where company_id=".$company_id);
		$this->ci->db->query("DELETE from `role` where company_id=".$company_id);
		$this->ci->db->query("DELETE FROM `users` WHERE `company_id`=".$company_id);
		*/
		
		
		return true;
	}
	
	function exists($username)
	{
		return $this->ci->db->query("SELECT count(*) as cnt from `continuitypro`.company where username=".$this->ci->db->escape($username))->row()->cnt;
	}
	
	function get_count($filter='1=1')
	{	
		return $this->ci->db->query("SELECT count(*) as cnt from  `company` WHERE $filter")->row()->cnt;
	}
	
	function get_list($page = 0,$filter = '',$sort = 'company_id',$ord = 'ASC')
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
					`company`.* 
				from 
					`company` 
							$filter 
						
							$sort 
						limit $start,".$this->items_per_page);
	}
	
	function detail($company_id)
	{
		return $this->row("select 
				* 
			from 
				`company` WHERE company_id=".$this->escape($company_id));	
			
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