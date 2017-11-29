<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Library_items extends CI_Controller
{
	function __construct() {
       parent::__construct();
       $this->protect();
   }
   
	public function protect()
	{
		$is_admin = $this->session->userdata("superadmin"); 
		if(!$is_admin)
		{
			redirect("/super_admin/login/");
			die;
		}
		else
			return true;  
	}
	
	function index()
	{
		$this->main();	
	}
	
	function add($library_id = 1)
	{
		$this->load->database();
		$this->load->library("library_lib");
		$data['library_details'] = $this->library_lib->detail($library_id);
		
		$this->load->view("super_admin/add/add_library_items",$data);	
	}
	
	function edit($item_id = 0)
	{
		$this->load->library('library_items_lib');
		$data['item'] = $this->library_items_lib->detail($item_id);
		
		$this->load->library("library_lib");
		$data['library_details'] = $this->library_lib->detail($data['item']['library_id']);
		
		$this->load->view("super_admin/edit/edit_library_items",$data);	
	}
	
	function main($library_id = 0)
	{
		$page = 0;
		$this->load->library('library_items_lib');
		$this->load->library('library_lib');

		$sort=$this->input->get('sort')==''?'item_order':$this->input->get('sort');
		$ord=$this->input->get('ord')==''?'ASC':$this->input->get('ord');
		//$page=$this->input->get('page');
		
		$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
		$href1=$_SERVER['PHP_SELF']."?";

		//$list = $this->library_items_lib->get_list($page,'library_id='.$library_id,$sort,$ord);
		
		$data['library_details'] = $this->library_lib->detail($library_id);
	
		$data['pagination'] = $this->library_items_lib->display_pagination('/super_admin/library_items/main/',$page); 
		$data['field_types'] = $this->library_items_lib->get_field_types();
		$data['field_value_types'] = $this->library_items_lib->get_field_value_types();
	
		$data['list'] = $this->library_items_lib->return_rendered_items($library_id);
	
		$this->load->view("super_admin/main/main_library_items",$data);
	}
	
	function library_items_select_box_ajax()
	{
		$this->load->database();
		$library_id=$this->input->post('library_id',TRUE);
		$res = $this->db->query("select id,`var_name` from `library_items` where library_id=".$library_id);
		if($res->num_rows()>0)
		{
			foreach ($res->result_array() as $data)
		 	{
		 		echo '<option value="'.$data['id'].'">'.$data['var_name'].'</option>';
		 	}
		 }
	}
	
	
	function view()
	{
		$this->load->view("super_admin/view/view_library_items");	
	}
	
	function set_sort_order()
	{
		$this->load->library('api_lib');
		
		$sort_order = preg_replace('/[^0-9,:]/', '',$this->input->post('sort_order',TRUE));
		
		$sort_array = explode(',', $sort_order);
		foreach ($sort_array as $key => $value) {
			$temp = explode(":",$value);
			//$sql_query[] = "update library_items set item_order=".$temp[1]." where id=".$temp[0];
			$this->api_lib->query("update library_items set item_order=".$temp[1]." where id=".$temp[0]);
		}
		//print_r($this->input->post());
	}
	
	
	function do_add()
	{
		$this->load->library('library_items_lib');
	
		$library_id = $this->input->post('library_id',TRUE);
		$var_name = $this->input->post('var_name',TRUE);
		$var_type = $this->input->post('var_type',TRUE);
		$var_value = $this->input->post('var_value',TRUE);
		$var_value_type = $this->input->post('var_value_type',TRUE);
		$item_order = $this->input->post('item_order',TRUE);
		$is_required = $this->input->post('is_required',TRUE);
		$show_by_default = $this->input->post('show_by_default',TRUE);
		$help = $this->input->post('help',TRUE);
		
		$dropdown_id = $this->input->post('dropdown_id',TRUE);
		$library_dropdown_id = $this->input->post('library_dropdown_id',TRUE);
		$library_item_id = $this->input->post('library_item_id',TRUE);
	
		if($var_type=='D' or $var_type=='MSEL')
			$var_value=$dropdown_id;
		
		if($var_type=='LIBRARY' or $var_type=='LIBRARY_MSEL')
		{
			$var_value=$library_dropdown_id.OPT_SEPERATOR.implode(',',$library_item_id);
		}
		
		if($var_type=='MAP')
		{
			$var_value = isset($library_item_id[0])?$library_item_id[0]:0;
		}
		
		$this->library_items_lib->add($library_id,$var_name,$var_type,$var_value,$var_value_type,$item_order,$is_required,$help,$show_by_default);
		
		redirect("/super_admin/library_items/main/".$library_id);
	}
	
	function do_update($id)
	{
		$this->load->library('library_items_lib');
		
		$library_id = $this->input->post('library_id',TRUE);
		$var_name = $this->input->post('var_name',TRUE);
		$var_type = $this->input->post('var_type',TRUE);
		$var_value = $this->input->post('var_value',TRUE);
		$var_value_type = $this->input->post('var_value_type',TRUE);
		$item_order = $this->input->post('item_order',TRUE);
		$is_required = $this->input->post('is_required',TRUE);
		$show_by_default = $this->input->post('show_by_default',TRUE);
		$help = $this->input->post('help',TRUE);
		
		$dropdown_id = $this->input->post('dropdown_id',TRUE);
		$library_dropdown_id = $this->input->post('library_dropdown_id',TRUE);
		$library_item_id = $this->input->post('library_item_id',TRUE);
	
		if($var_type=='D' or $var_type=='MSEL')
			$var_value=$dropdown_id;
		
		if($var_type=='LIBRARY' or $var_type=='LIBRARY_MSEL')
		{
			$var_value=$library_dropdown_id.OPT_SEPERATOR.implode(',',$library_item_id);
		}
		
		if($var_type=='MAP')
		{
			$var_value = isset($library_item_id[0])?$library_item_id[0]:0;
		}		
		
		$this->library_items_lib->update($id,$library_id,$var_name,$var_type,$var_value,$var_value_type,$item_order,$is_required,$help,$show_by_default);
		redirect("/super_admin/library_items/main/".$library_id);
	}
	
	function do_delete($id)
	{
		$this->load->library('library_items_lib');
		$library_item = $this->library_items_lib->detail($id);
		$this->library_items_lib->delete($id);
		redirect("/super_admin/library_items/main/".$library_item['library_id']);
	}
	
}

?>