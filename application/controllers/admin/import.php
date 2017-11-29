<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import extends CI_Controller {

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
	
	public function main()
	{
		$this->load->library("library_lib");
		$data['library_select_box'] = $this->library_lib->get_library_selectbox('library_id');
		$this->load->view("admin/add/import_csv",$data);
	}
	
	public function upload_csv()
	{
		$library_id = $this->input->post('library_id',TRUE);
		
		$config['upload_path'] = $this->config->item('upload_path'); //'./uploads/';
		$config['allowed_types'] = 'csv';
		$config['max_size'] = '5000';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('csv_file'))
		{
			$error = array('error' => $this->upload->display_errors());

			//print_r($error);
			//$this->load->view('upload_form', $error);
			redirect("/admin/import/main/?error=1");
		}
		else
		{
			$fdata = array('upload_data' => $this->upload->data());
			//redirect("/admin/import/map_csv?library_id=".$library_id."&f=".urlencode($data['upload_data']['raw_name']));
			
			$this->load->library('library_lib');
			$this->load->library('csvreader');
			$keys = $this->csvreader->get_csv_header($fdata['upload_data']['full_path']);
			
			$data['library_id'] = $library_id;
			$data['full_path'] = $fdata['upload_data']['full_path'];
 			$data['library_keys'] = $this->library_lib->rexplain($library_id);
			$data['csv_keys']=$keys;
			
			$this->load->view('admin/add/map_csv',$data);
		}
	}
	
	public function map_now()
	{
		ini_set("auto_detect_line_endings", "1");
		$library_id = $this->input->post("library_id",TRUE);
		$full_path = $this->input->post("full_path",TRUE);
		$unique_id_map = $this->input->post("unique_id",TRUE);
		$map = $this->input->post('map',TRUE);
		$this->load->library('csvreader');
		$this->load->library("library_lib");
		$this->load->library("library_values");
		//print_r($this->input->post());
		
		$library_fields = $this->library_lib->explain($library_id);
		
		$data = $this->csvreader->parse_file($full_path);
		
		
		foreach ($data as $row) {
			$items = array();
			foreach ($map as $litid => $mapped_name) {
			
				if(isset($library_fields[$litid]))
				{
					if($library_fields[$litid]['var_type']=='LIBRARY_MSEL' || $library_fields[$litid]['var_type']=='LIBRARY')
					{
						if(isset($row[$mapped_name]))
						{
							// Multi select format Library_id#item_id_1,item_id_2
							$tempA = explode(OPT_SEPERATOR,$library_fields[$litid]['var_value']);
							$rLibId = $tempA[0];
							$rLibItemIds = explode(',',$tempA[1]);
							
							if(is_numeric($tempA[1]))
							{
								$options = $this->library_values->get_select_box_by_libitemid($rLibId,$tempA[1]);
							}
							else {
								$options = $this->library_values->get_select_box_by_libitemid($rLibId,$rLibItemIds);
							}
							
							if(is_array($options))
							{
								$multiValues = explode(',',$row[$mapped_name]);
								$multiResults = array();
								foreach ($options as $ok => $ov) {
									if(in_array($ov, $multiValues))
									{
										//$items[$litid]=$ok;
										//break;
										$multiResults[] = $ok;
									}
								}
								
								$items[$litid] = $multiResults;
								
								
							
							}else {
								$items[$litid]='';
							}
							
							//$items[$litid]=$rLibId.OPT_SEPERATOR.$rLibItemIds[0].OPT_SEPERATOR.$row[$mapped_name];
						}else {
							$items[$litid]='';
						}
						
						
					}
					else {
						$items[$litid]=isset($row[$mapped_name])?$row[$mapped_name]:'';
					}
				}
				else {
					$items[$litid]=isset($row[$mapped_name])?$row[$mapped_name]:'';	
				}
		
			}
		
			$itemsEncoded = json_encode($items);
			
			if($unique_id_map!="" and isset($row[$unique_id_map]))
			{
				$this->library_lib->save_user_data($this->user_id,$library_id,$this->company_id,$itemsEncoded,$row[$unique_id_map]);
			}
			else
			{
				$this->library_lib->save_user_data($this->user_id,$library_id,$this->company_id,$itemsEncoded);
			}
			//print_r($row);
		}
		
		redirect("/admin/library/table_view/".$library_id."/");

	}

}

/* End of file Post.php */
/* Location: ./application/controllers/admin/import.php */