<?

class Modules_lib
{
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
	
	function get()
	{
		$this->ci->load->library("api_lib");
		$this->ci->load->library("library_lib");
		
		
		$list =  $this->ci->api_lib->get_list("select 
					`id` as module_id,
					`name`,
					'lib' as type
				from 
					`library` 
				where
					company_id=".$this->company_id." and
					is_system=0
				order by name ASC");
		
		if(is_array($list))
		{
			foreach($list as $index => $value)
			{
				if(strtolower($value['name'])=="document manager")
					$list[$index]['type'] = "gallery";
					
				if(strtolower($value['name'])=="files")
					$list[$index]['type'] = "gallery";
			}
		}
		
		$bia_library_id = $this->ci->library_lib->get_library_id_by_name($this->company_id,"BIA");
		
		$list2 = array(
			array("module_id"=>"0",
						"name"=>"Call Chain",
						"type"=>"cc"),
		
			array("module_id"=>"0",
						"name"=>"Risk Assessment",
						"type"=>"ra"),
						
			array("module_id"=>$bia_library_id,
						"name"=>"Business Impact Analysis (BIA)",
						"type"=>"bia")
		);
						
		/*$list2 =  $this->ci->api_lib->get_list("select 
					`call_chain_id` as module_id,
					`name`,
					'cc' as type
				from 
					`call_chain` 
				where
					company_id=".$this->company_id."
				order by name ASC");
		
		$list3 =  $this->ci->api_lib->get_list("select 
					`risk_assessment_id` as module_id,
					`name`,
					'ra' as type
				from 
					`risk_assessment` 
				where
					company_id=".$this->company_id."
				order by name ASC");*/
		
		//$new_array = array_merge($list,$list2,$list3);
		$new_array = array_merge($list,$list2);
		
		return $new_array;
	}
}

?>