<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company extends CI_Controller
{
	function __construct() {
       parent::__construct();
       $this->protect();
       
       $user_info = $this->session->userdata("user_info");
       $this->company_id = $user_info['company_id'];
       $this->user_id = $user_info['user_id'];
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
		$this->settings();	
	}
	
	function settings()
	{	
		$this->load->library("company_lib");
		$this->load->view("admin/add/company_settings");
	}
	
	function do_update()
	{
		$this->load->library('company_lib');
		
		$uploaddir=$this->config->item('upload_path');
		//print_r($_FILES);
		
		if(isset($_FILES['company_logo']) and isset($_FILES['company_logo']['name']))
		{
			//echo $_FILES['company_logo']['name'];
			if($_FILES['company_logo']['error']==0)
			{
				$fname= basename($_FILES['company_logo']['name']);
				$fname=str_replace(" ","_",$fname);	
				$fname=trim($fname);
				$uploadfile = $uploaddir.$fname;
			
				if(move_uploaded_file($_FILES['company_logo']['tmp_name'],$uploadfile))
				{
					$file = $fname;
					$this->company_lib->update_logo($this->company_id,$file);
					$user_info = $this->session->userdata('user_info');
					$user_info['logo']=$file;
					$this->session->set_userdata('user_info',$user_info);
					//$this->load->library('s3');
					//$input = $this->s3->inputFile($image);
					//$ret = $this->s3->putObject($input, "BUCKET_NAME", 'FOLDER_NAME/'.$fname);
					//unlink($image);
					
				}
				
			}
		}
		
		redirect("/admin/company/settings/?updated=1");
	}
	
}

?>