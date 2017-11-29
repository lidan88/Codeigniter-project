<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

global $key;

function cmp($a, $b) {
    global $key;
    
    if(!isset($a[$key]))
    	return 0;

	if(!isset($b[$key]))
		return 0;
    
    if ($a[$key] == $b[$key]) {
        return 0;
    }
    return ($a[$key] < $b[$key]) ? -1 : 1;
}

function rcmp($a, $b) {
    global $key;
    
    if(!isset($a[$key]))
    	return 0;
    
    if(!isset($b[$key]))
    	return 0;
    
    if ($a[$key] == $b[$key]) {
        return 0;
    }
    return ($a[$key] > $b[$key]) ? -1 : 1;
}

class Library extends CI_Controller
{
	function __construct() {
       parent::__construct();
       $this->protect();
       
       $user_info = $this->session->userdata("user_info");
       $this->company_id = $user_info['company_id'];
   }
   
	public function protect()
	{
		$is_admin = $this->session->userdata("is_admin"); 
		if(!$is_admin)
		{
			redirect("/home/login/");
			die;
		}
		else
			return true;  
	}
	
	function index()
	{
		$this->main();	
	}
	
	function add()
	{
		$this->load->database();
		$this->load->view("admin/add/add_library");	
	}
	
	function edit($item_id = 0)
	{
		$this->load->library('library_lib');
		$data['item'] = $this->library_lib->detail($item_id);
		
		$this->load->view("admin/edit/edit_library",$data);	
	}
	
	function get_id_by_name()
	{
		$library_name = $this->input->post("name",TRUE);
		$this->load->library('library_lib');
		echo $this->library_lib->get_library_id_by_name($this->company_id,"BIA");
	}
	
	function main($page = 0)
	{
		$this->load->library('library_lib');
		
		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		//$page=$this->input->get('page');
		
		$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
		$href1=$_SERVER['PHP_SELF']."?";

		$list = $this->library_lib->get_list($page,'company_id='.$this->company_id,$sort,$ord);
	
		$data['pagination'] = $this->library_lib->display_pagination('/admin/library/main/',$page,'company_id='.$this->company_id); 
	
		$data['list'] = $list;
	
		$this->load->view("admin/main/main_library",$data);
	}
	
	function listit()
	{
		$page=0;
		$this->load->library('library_lib');
				
		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		//$page=$this->input->get('page');
		
		$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
		$href1=$_SERVER['PHP_SELF']."?";

		$list = $this->library_lib->get_list($page,'company_id='.$this->company_id,$sort,$ord);
	
		$data['pagination'] = $this->library_lib->display_pagination('/admin/library/main/',$page,'company_id='.$this->company_id); 
	
		$data['list'] = $list;
	
		$this->load->view("admin/main/list_library",$data);
	}
	
	function table_view($library_id)
	{
		if(!is_numeric($library_id))
			redirect("/admin/library/");
		
		global $key;
		$this->load->library('library_lib');
		$this->load->library('api_lib');
		
		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		$search=$this->input->get('search');
		$filter_by = $this->input->get('filter_by');
		$output=$this->input->get('output');
		$page=$this->input->get('page');
		$page = $page==''?0:$page;
		$reset = $this->input->get('reset');
		$type = $this->input->get('type')==''?'':$this->input->get('type');
		$page_type = $this->input->get('page_type')==''?'':$this->input->get('page_type');
		
		if($filter_by!="")
		{
			/*$filter_by = json_decode($filter_by,true);
			if(is_array($filter_by))
			{
				foreach ($filter_by as $key => $value) {
					//$v = json_decode($value,true);
					$search[] = $value['c'].OPT_SEPERATOR.$value['f'];
					//print_r($value).'<br />';
				}
			}*/
			$search = filterByObjToArray($filter_by);
		}
		
		if($reset==1)
			$page=0;
		
		$items_per_page = $this->input->get('items_per_page');
		$items_per_page = $items_per_page==''?10:$items_per_page;
		
		$this->library_lib->items_per_page=$items_per_page;
		$this->api_lib->items_per_page=$items_per_page;
		
		$href=$_SERVER['PHP_SELF']."?items_per_page=$items_per_page&page=$page&sort=$sort&ord=$ord&search=".$search;
		$href1=$_SERVER['PHP_SELF']."?";
		
		$data['library_fields'] = $this->library_lib->explain($library_id);
		$data['library_details'] = $this->library_lib->detail($library_id);
		$data['lpn'] = get_libuserid_from_name($data['library_details']['name']);
		
		if($type=='')
		{
			$items = $this->library_lib->get_user_data($page,$library_id,$search);
			$data['library_values_count'] = $this->library_lib->get_library_values_count($library_id,$search);
		}
		else if($type=='plan_item_id')
		{
			$items = $this->library_lib->get_user_data_by_plan_item_id($search,$library_id);
			$data['library_values_count'] = 0;
		}	
		
		if($output=='plan_ajax') {
			$data['pagination'] = $this->api_lib->display_ajax_pagination($page,'library_values','library_id='.$library_id); 
		}
		else {
			if($search=='')
				$data['pagination'] = $this->api_lib->display_pagination('library_values','/admin/library/table_view/'.$library_id."?items_per_page=$items_per_page&sort=$sort&ord=$ord&search=".$search,$page,5,'library_id='.$library_id); 
			else {
				$data['pagination'] = $this->api_lib->display_pagination('library_values','/admin/library/table_view/'.$library_id."?items_per_page=$items_per_page&sort=$sort&ord=$ord&search=".$search,$page,5,"library_id=".$library_id." and `value` like '%".$search."%'"); 
			}
		}
		
		$data['library_id'] = $library_id;
		
		if(is_array($data['library_fields']))
		foreach($data['library_fields'] as $lf)
		{
			if($sort=='')
			{
				$sort=$lf['id'];
				break;
			}
		}
		
		$values = array();
		
		if(is_array($items) and count($items)>0)
		foreach($items as $item){
			$decodedItems = json_decode($item['value'],TRUE);
			$values[$item['id']] = $decodedItems;
		}
		
		$key=$sort;
		if($ord=='ASC')
			uasort($values, 'cmp');
		else
			uasort($values, 'rcmp');

		/*echo '<pre>';
		print_r($values);
		echo '</pre>';
		die;*/
		
		$data['items'] = $items;
		$data['values'] = $values;
		$data['page_type']=$page_type;
		
		if($output=='')
			$this->load->view("admin/main/table_library",$data);		
		else if($output=='plan_ajax') {
			$this->load->view("admin/main/table_library_plan_ajax",$data);
		}else {
			$this->load->view("admin/main/table_library_ajax",$data);
		}
	}
	
	function delete_user_values()
	{
		//print_r($this->input->post());
		$ids = $this->input->post("ids",TRUE);
		$this->load->library("library_lib");
		
		$this->library_lib->delete_user_values($ids);
		echo json_encode(array("success"=>"1"));			
	}
	
	function do_add()
	{
		$this->load->library('library_lib');
	
		$name = $this->input->post('name',TRUE);
		$parent_id = $this->input->post('parent_id',TRUE);
		$is_system = $this->input->post('is_system',TRUE);
		$is_visible = $this->input->post('is_visible',TRUE);
		
		$uploaddir=$this->config->item('upload_path');

		if(isset($_FILES['library_logo']) and isset($_FILES['library_logo']['name']))
		{
			if($_FILES['library_logo']['error']==0)
			{
				$fname= basename($_FILES['library_logo']['name']);
				$fname=str_replace(" ","_",$fname);	
				$fname=trim($fname);
				$uploadfile = $uploaddir.$fname;
			
				if(move_uploaded_file($_FILES['library_logo']['tmp_name'],$uploadfile))
				{
					$file = $fname;
					//$this->company_lib->update_logo($this->company_id,$file);
				
				}
				
			}
		}

		$user_info = $this->session->userdata("user_info");
		$company_id = $user_info['company_id'];
		
		$this->library_lib->add($name,$company_id,$parent_id,$is_system,$is_visible,$file);
		
		redirect("/admin/library/main/");
	}
	
	function do_update($id)
	{
		$this->load->library('library_lib');
		
		$name = $this->input->post('name',TRUE);
		$parent_id = $this->input->post('parent_id',TRUE);
		$is_system = $this->input->post('is_system',TRUE);
		$is_visible = $this->input->post('is_visible',TRUE);
		
		$uploaddir=$this->config->item('upload_path');

		if(isset($_FILES['library_logo']) and isset($_FILES['library_logo']['name']))
		{
			if($_FILES['library_logo']['error']==0)
			{
				$fname= basename($_FILES['library_logo']['name']);
				$fname=str_replace(" ","_",$fname);	
				$fname=trim($fname);
				$uploadfile = $uploaddir.$fname;
			
				if(move_uploaded_file($_FILES['library_logo']['tmp_name'],$uploadfile))
				{
					$file = $fname;
				
				}
				
			}
		}

		$this->library_lib->update($id,$name,$parent_id,$is_system,$is_visible,$file);
		redirect("/admin/library/main/");
	}
	
	function do_delete($id)
	{
		$this->load->library('library_lib');
		$this->library_lib->delete($id);
		//$this->db->query("DELETE FROM `library` WHERE `id`='$id'");
		//echo "Deleted"; 
		redirect("/admin/library/main/");
	}
	
}

?>