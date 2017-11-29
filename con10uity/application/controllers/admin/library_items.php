<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Library_items extends CI_Controller
{
	function __construct() {
       parent::__construct();
       $this->protect();
       
       $this->load->database();
       
       $user_info = $this->session->userdata("user_info");
       $this->company_id = isset($user_info['company_id'])?$user_info['company_id']:0;
       $this->user_id = isset($user_info['user_id'])?$user_info['user_id']:0;
       $this->role_id = isset($user_info['role_id'])?$user_info['role_id']:0;
       
       setDBByCompany($this->db,$this->company_id);
   }
   
	public function protect()
	{
		$is_admin = $this->session->userdata("is_admin"); 
		if(!$is_admin)
		{
			redirect("/admin/login/");
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
		$this->load->library("library_lib");
		$data['library_details'] = $this->library_lib->detail($library_id);
		
		$this->load->view("admin/add/add_library_items",$data);	
	}
	
	function edit($item_id = 0)
	{
		$this->load->library('library_items_lib');
		$data['item'] = $this->library_items_lib->detail($item_id);
		
		$this->load->library("library_lib");
		$data['library_details'] = $this->library_lib->detail($data['item']['library_id']);
		
		//print_r($data['item']);die;
		
		$this->load->view("admin/edit/edit_library_items",$data);	
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
	
		$data['pagination'] = $this->library_items_lib->display_pagination('/admin/library_items/main/',$page); 
		$data['field_types'] = $this->library_items_lib->get_field_types();


		//$data['field_value_types'] = $this->library_items_lib->get_field_value_types();
	
		//$data['list'] = $list;
		$data['list'] = $this->library_items_lib->return_rendered_items($library_id);
	
		$this->load->view("admin/main/main_library_items",$data);
	}
	
	function library_items_select_box_ajax()
	{
		$library_id=$this->input->post('library_id',TRUE);
		$type=$this->input->post('type',TRUE);
		
		if($type!='')
			$type = ' var_type='.$this->db->escape($type).' and ';
		
		//die("select id,`var_name` from `library_items` where ".$type." library_id=".$library_id);
		
		$res = $this->db->query("select id,`var_name` from `library_items` where ".$type." library_id=".$library_id);
		if($res->num_rows()>0)
		{
			echo '<option value="0">Select Library Item</option>';
			foreach ($res->result_array() as $data)
		 	{
		 		echo '<option value="'.$data['id'].'">'.$data['var_name'].'</option>';
		 	}
		 }
	}
	
	function enter_user_data($library_id = 0)
	{
		$page = 0;
		$this->load->library('library_items_lib');
		$this->load->library('library_lib');

		$sort=$this->input->get('sort')==''?'item_order':$this->input->get('sort');
		$ord=$this->input->get('ord')==''?'ASC':$this->input->get('ord');
		//$page=$this->input->get('page');
		
		$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
		$href1=$_SERVER['PHP_SELF']."?";

		//$list = $this->library_items_lib->get_list(0,'library_id='.$library_id,$sort,$ord);
		$data['library_details'] = $this->library_lib->detail($library_id);
	
		$data['pagination'] = $this->library_items_lib->display_pagination('/admin/library_items/main/',$page); 
		$data['field_types'] = $this->library_items_lib->get_field_types();
		$data['field_value_types'] = $this->library_items_lib->get_field_value_types();
	
		$session_saved_data='';
		
		if(isset($_GET['error']))
		{
			$session_saved_data = $this->session->userdata("user_posted_data");
			if(isset($session_saved_data['items']))
				$session_saved_data = $session_saved_data['items'];
		}
		//print_r($session_saved_data);
		//$data['list'] = $list;
		$data['list'] = $this->library_items_lib->return_rendered_items($library_id,'',$session_saved_data);
		$this->load->view("admin/add/add_library_items_data",$data);
	}
	
	function process_user_data($library_id)
	{
		//print_r($this->input->post());
		$this->load->library('library_lib');
		$this->load->library('library_items_lib');
		$company_id = $this->input->post('company_id',TRUE);
		$unique_id = $this->input->post('unique_id',TRUE);
		
		$itemsEncoded = $this->library_items_lib->encode_user_post_data();
		$save_id = $this->library_lib->save_user_data($this->user_id,$library_id,$company_id,$itemsEncoded,$unique_id,true);
		
		if(!$save_id)
		{
			$this->session->set_userdata('user_posted_data',$this->input->post());
			redirect("/admin/library_items/enter_user_data/".$library_id."/?error=1");
		}
		else
			redirect("/admin/library/table_view/".$library_id."/");
	}
	
	function edit_user_data($value_id = 0)
	{
		$this->load->library('library_items_lib');
		$this->load->library('library_lib');

		$data['item'] = $this->library_lib->get_user_data_by_id($value_id);
		
		$library_id=$data['item']['library_id'];
						
		$data['library_details'] = $this->library_lib->detail($library_id);
	
		$data['values'] = json_decode($data['item']['value'],TRUE);
		$data['value_id'] = $value_id;
		echo $data['value_id'];
		$data['list'] = $this->library_items_lib->return_rendered_items($library_id,'',$data['values'],$value_id);
		//print_R($data['list']);
		//echo '<pre>';
		//print_r($data['item']);
		//print_r($data['values']);
		//print_r($data['list']);
		//echo '</pre>';
		//die;
	
		$this->load->view("admin/edit/edit_library_items_data",$data);
	}
	
	function export_items()
	{
		$this->load->database();
		$this->load->dbutil();
		$this->load->library('library_lib');
		$this->load->helper('download');
		$this->load->helper('csv');
		
		$ids = $this->input->post('ids',TRUE);
		$library_id = $this->input->post('library_id',TRUE);
		$ids = ltrim ($ids,',');
		
		$library_fields = $this->library_lib->explain($library_id);
		$library_details = $this->library_lib->detail($library_id);
			
		if($ids!="")
			$items = $this->library_lib->get_user_data_by_ids($ids);
		else {
			$items = $this->library_lib->get_user_data_by_libid($library_id);
		}
		
		$values = array();
		
		$header_array=array();
		$header_array[]="id";
		foreach ($library_fields as $key => $value) {
			$header_array[] = $value['var_name'];
		}
		$header_array[]="Modified On";
		$header_array[]="Modified By";
		
		$temp_array = array($header_array);
		
		if(is_array($items) and count($items)>0)
		{
			foreach($items as $item){
				
				$decodedItems=array();
				$decodedItems = json_decode($item['value'],TRUE);
				
				$itemsToExport = array();
				$itemsToExport["ID"] = $item['unique_id'];
				
				//$decodedItems = ArrayMergeKeepKeys($unique_id,$decodedItems_2);
				
				// Convert any array items into simple text
				// Find empty items and fill them up with their ids too.
				foreach ($library_fields as $key => $value) {
					
					if(isset($decodedItems[$key]))
					{
						$itemsToExport["F".$key]=$decodedItems[$key];
					
					}else
					{
						$itemsToExport["F".$key]="";
					}
				}
				
				foreach ($itemsToExport as $key => $value) {
					if(is_array($itemsToExport[$key]))
						$itemsToExport[$key]=array_recursive_value($value);
					else	
						$itemsToExport[$key]=opt2value($value);
				}
				
				$itemsToExport["mat"]=$item['modified_at'];
				$itemsToExport["mby"]=$item['modified_by'];
				
				/*echo '<pre>';
				print_r($library_fields);
				echo '</pre>';
				
				echo '<pre>';
				print_r($itemsToExport);
				echo '</pre>';
				die;*/
				
				$values[$item['id']] = $itemsToExport;
			}
		}
		
		$add_to_csv = array_to_csv($temp_array);
		$add_to_csv .= array_to_csv($values);
		
		//$data = file_get_contents("/path/to/photo.jpg"); // Read the file's contents
		$name = 'download_'.$library_details['name'].'.csv';
		
		force_download($name, $add_to_csv);
	}
	
	function view($value_id = 0)
	{
		$this->load->library('library_items_lib');
		$this->load->library('library_lib');

		$data['item'] = $this->library_lib->get_user_data_by_id($value_id);
		
		$library_id=$data['item']['library_id'];
		$list = $this->library_items_lib->get_k_list('select * from `library_items` WHERE library_id='.$library_id,'id');
						
		$data['library_details'] = $this->library_lib->detail($library_id);
	
		$data['field_types'] = $this->library_items_lib->get_field_types();
		$data['field_value_types'] = $this->library_items_lib->get_field_value_types();
	
		$data['list'] = $list;
		$data['values'] = json_decode($data['item']['value'],TRUE);
		$data['value_id'] = $value_id;
		/*echo '<pre>';
		print_r($data['item']);
		print_r($data['values']);
		print_r($data['list']);
		echo '</pre>';
		die;*/
	
		$this->load->view("admin/view/view_library_items_data",$data);
	}
	
	function do_update_user_data($value_id)
	{
		/*echo "<pre>";
		print_r($this->input->post());
		echo "</pre>";
		die;*/
		$this->load->library('library_lib');
		$this->load->library('library_values');
		$this->load->library('library_items_lib');
		
		$submit = $this->input->post('Submit',TRUE);
		$action = $this->input->post('action',TRUE);
		$library_id = $this->input->post('library_id',TRUE);
		$company_id = $this->input->post('company_id',TRUE);
		$unique_id = $this->input->post('unique_id',TRUE);
		$items = $this->input->post('items',TRUE);
		$tasks = $this->input->post('tasks',TRUE);
		$teams = $this->input->post('teams',TRUE);

		$itemsEncoded = $this->library_items_lib->encode_user_post_data();
		
		//print_r($itemsEncoded);
		//die;
		
		//$itemsEncoded = json_encode($items);
		if(is_array($tasks))
		{
			$this->load->library("tasks_lib");
			foreach ($tasks as $key => $task) {
				$this->tasks_lib->quick_add($task["task_id"],$value_id,$task['assigned_to'],$task["task_title"],$task["task_priority"]);
			}
		}
		
		if(is_array($teams))
		{
			$this->load->library("teams_lib");
			foreach ($teams as $key => $team) {
				$this->teams_lib->add($team["team_id"],$value_id,$team['team_member'],$team["team_role"],$team["team_responsibility"],$team["team_priority"]);
			}
		}
		//die;
				
		if($action=='Delete')
		{
			$this->library_values->delete($value_id);
			redirect("/admin/library/table_view/".$library_id);
			return;
		}
		
		if($submit=='Save')	
		{
			$this->library_lib->update_user_data($value_id,$this->user_id,$itemsEncoded,$unique_id);
			redirect("/admin/library/table_view/".$library_id."/");
		}
		else if($submit=='Save and New')
		{
			$this->library_lib->update_user_data($value_id,$this->user_id,$itemsEncoded,$unique_id);
			redirect("admin/library_items/enter_user_data/".$library_id."/");
		}
		else if($submit=='Copy')
		{
			$value_id = $this->library_lib->save_user_data($this->user_id,$library_id,$company_id,$itemsEncoded,$unique_id.'-copy');
			redirect("/admin/library_items/edit_user_data/".$value_id."/");
		}
		else if($submit=='Delete')
		{
			$this->library_values->delete($value_id);
			redirect("/admin/library/table_view/".$library_id);
		}
		else {
			redirect("/admin/library/table_view/".$library_id);
		}
	}
	
	function set_sort_order()
	{
		$this->load->library('api_lib');
		
		$sort_order = preg_replace('/[^0-9,:#\[\]]/', '',$this->input->post('sort_order',TRUE));
		
		//echo $sort_order;
		
		$sort_array = explode(',', $sort_order);
		foreach ($sort_array as $key => $value) {
			$temp = explode(OPT_SEPERATOR,$value);
			//$sql_query[] = "update library_items set item_order=".$temp[1]." where id=".$temp[0];
			//echo "update library_items set item_order=".$temp[1]." where id=".$temp[0];
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
		
		redirect("/admin/library_items/main/".$library_id);
	}
	
	function do_copy()
	{
		$id = $this->input->post("id",TRUE);
		
		$this->load->library('library_lib');
		
		$new_library_id = $this->library_lib->copy_user_data($id);
		echo "1";
		//echo $new_library_id;
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
		redirect("/admin/library_items/main/".$library_id);
	}
	
	function do_delete($id)
	{
		$this->load->library('library_items_lib');
		$library_item = $this->library_items_lib->detail($id);
		$this->library_items_lib->delete($id);
		redirect("/admin/library_items/main/".$library_item['library_id']);
	}
	
}

?>