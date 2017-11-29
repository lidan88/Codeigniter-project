<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gallery extends CI_Controller
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
	
	function add()
	{
		//$this->load->database();
		$this->load->library('gallery_lib');
		$data['item'] = $this->gallery_lib->empty_row();
		
		$this->load->view("super_admin/add/add_gallery",$data);	
	}
	
	function edit($item_id = 0)
	{
		//$this->load->database();
		$this->load->library('gallery_lib');
		$data['item'] = $this->gallery_lib->detail($item_id);
		
		$this->load->view("super_admin/edit/edit_gallery",$data);	
	}
	
	function main($page = 0)
	{
		$this->load->library('gallery_lib');

		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		$search=$this->input->get('search');
		$items_per_page = $this->input->get('items_per_page');
		$items_per_page = $items_per_page==''?10:$items_per_page;
		$output=$this->input->get('output');
		
		//$page=$this->input->get('page');
		//$page = $page==''?0:$page;
		//$reset = $this->input->get('reset');
		
		
		$href=$_SERVER['PHP_SELF']."?items_per_page=$items_per_page&page=$page&sort=$sort&ord=$ord&search=".$search;
		$href1=$_SERVER['PHP_SELF']."?";
		
		$filter = '1=1';
		if($search!='')
			$filter = "`title` like '%".$search."%'";
		
		$this->gallery_lib->items_per_page=$items_per_page;
		$list = $this->gallery_lib->get_list($page,$filter,$sort,$ord);
	
		$data['pagination'] = $this->gallery_lib->display_pagination('/super_admin/gallery/main/',$page,$filter); 
	
		$data['list'] = $list;
	
		
		if($output=='ajax'){
			$this->load->view("super_admin/main/main_gallery_ajax",$data);		
		}
		else {
			$this->load->view("super_admin/main/main_gallery",$data);
		}
	}
	
	function view()
	{
		$this->load->view("super_admin/view/view_gallery");	
	}
	
	function do_add()
	{
		$this->load->library('gallery_lib');
	
		$id = $this->input->post('id',TRUE);
		if($id!=''){
			$this->do_update($id);
			return;
		}		
	
		$title = $this->input->post('title',TRUE);
		$file = $this->input->post('file',TRUE);
		
		$uploaddir = $this->config->item("upload_path");
		if(isset($_FILES['file']) and $_FILES['file']['error']==0)
		{
			$fname= basename($_FILES['file']['name']);
			$fname=str_replace(" ","_",$fname);	
			$fname=trim($fname);
			$uploadfile = $uploaddir.$fname;
		
			if(move_uploaded_file($_FILES['file']['tmp_name'],$uploadfile))
			{
				$file = $fname;
				
				/*
					$this->load->library('s3');
					$input = $this->s3->inputFile($image);
					$ret = $this->s3->putObject($input, "BUCKET_NAME", 'FOLDER_NAME/'.$fname);
					unlink($image);
				*/
			}
		}
		else
			$file = "";
		
		$this->gallery_lib->add($title,$file);
		
		//$this->db->query("INSERT INTO `gallery` (`title`,`file`,`added`) VALUES (".$this->escape($title).",".$this->escape($file).",".$this->escape($added).")");
		//echo "Added";
		redirect("/super_admin/gallery/main/");
	}
	
	function do_update($id)
	{
		$this->load->library('gallery_lib');
		
		$title = $this->input->post('title',TRUE);
		$file = $this->input->post('file',TRUE);
		
		
		$uploaddir = $this->config->item("upload_path");
		if(isset($_FILES['file']) and $_FILES['file']['error']==0)
		{
			$fname= basename($_FILES['file']['name']);
			$fname=str_replace(" ","_",$fname);	
			$fname=trim($fname);
			$uploadfile = $uploaddir.$fname;
		
			if(move_uploaded_file($_FILES['file']['tmp_name'],$uploadfile))
			{
				$file = $fname;
				
				/*
					$this->load->library('s3');
					$input = $this->s3->inputFile($image);
					$ret = $this->s3->putObject($input, "BUCKET_NAME", 'FOLDER_NAME/'.$fname);
					unlink($image);
				*/
			}
		}
		else
			$file = "";
		
		$this->gallery_lib->update($id,$title,$file);
		//$this->db->query("UPDATE `gallery` set `title`=".$this->escape($title).",`file`=".$this->escape($file).",`added`=".$this->escape($added)." WHERE `id`='$id'");
		//echo "Updated"; 
		redirect("/super_admin/gallery/main/");
	}
	
	function do_delete($id)
	{
		$this->load->library('gallery_lib');
		$this->gallery_lib->delete($id);
		//$this->db->query("DELETE FROM `gallery` WHERE `id`='$id'");
		//echo "Deleted"; 
		redirect("/super_admin/gallery/main/");
	}
	
	function do_delete_multi($id=0)
	{
		$this->load->library('gallery_lib');
		
		$ids = $this->input->post("ids",TRUE);
		
		if($id==0)
		{
			$array = explode(',', $ids);
			if(is_array($array) and count($array)>0)
			foreach ($array as $value) {
				$this->gallery_lib->delete($value);
			}
		
		}else {
			$this->gallery_lib->delete($id);
			
		}
		
		$this->gallery_lib->delete($id);
		//$this->db->query("DELETE FROM `gallery` WHERE `id`='$id'");
		//echo "Deleted"; 
		redirect("/super_admin/gallery/main/");
	}
	
}

?>