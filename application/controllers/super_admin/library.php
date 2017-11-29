<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Library extends CI_Controller
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
		$this->load->view("super_admin/add/add_library");	
	}
	
	function edit($item_id = 0)
	{
		$this->load->library('library_lib');
		$data['item'] = $this->library_lib->detail($item_id);
		
		$this->load->view("super_admin/edit/edit_library",$data);	
	}
	
	function main($page = 0)
	{
		$this->load->library('library_lib');
		$this->library_lib->items_per_page=500;

		$sort=$this->input->get('sort');
		$ord=$this->input->get('ord');
		//$page=$this->input->get('page');
		
		$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
		$href1=$_SERVER['PHP_SELF']."?";

		$list = $this->library_lib->get_list($page,'company_id=0',$sort,$ord);
	
		$data['pagination'] = $this->library_lib->display_pagination('/super_admin/library/main/',$page); 
	
		$data['list'] = $list;
	
		$this->load->view("super_admin/main/main_library",$data);
	}
	
	function view()
	{
		$this->load->view("super_admin/view/view_library");	
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

		
		$this->library_lib->add($name,0,$parent_id,$is_system,$is_visible,$file);
		
		redirect("/super_admin/library/main/");
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
		redirect("/super_admin/library/main/");
	}
	
	function do_delete($id)
	{
		$this->load->library('library_lib');
		$this->library_lib->delete($id);
		redirect("/super_admin/library/main/");
	}
	
}

?>