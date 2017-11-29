<?

class Gallery_lib
{
	var $items_per_page = 10;

	public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->database();
	}
	
	public function escape($item)
	{
		return $this->ci->db->escape($item);	
	}
	
	function add($title,$file)
	{
		$this->ci->db->query("INSERT INTO `continuitypro`.`gallery` (`title`,`file`,`added`) VALUES (".$this->escape($title).",".$this->escape($file).",NOW())");
		return true; 
	}
	
	function update($id,$title,$file)
	{
		$this->ci->db->query("UPDATE `continuitypro`.`gallery` set `title`=".$this->escape($title).",`file`=".$this->escape($file).",`added`=NOW() WHERE `id`='$id'");
		return true; 
	}
	
	function delete($id)
	{
		$this->ci->db->query("DELETE FROM `continuitypro`.`gallery` WHERE `id`='$id'");
		return true;
	}
	
	function get_count($filter='1=1')
	{	
		return $this->ci->db->query("SELECT count(*) as cnt from  `continuitypro`.`gallery` WHERE $filter")->row()->cnt;
	}
	
	function get_list($page = 0,$filter = '',$sort = 'id',$ord = 'ASC')
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
					`gallery`.* 
				from 
					`continuitypro`.`gallery` 
				WHERE
					1=1
							$filter 
						
							$sort 
						limit $start,".$this->items_per_page);
	}
	
	function detail($item_id)
	{
			return $this->row("select 
					`gallery`.* 
				from 
					`continuitypro`.`gallery` WHERE `gallery`.id=".$this->escape($item_id));	
	}
	
	function empty_row()
	{
		$columns = "`title`,`file`,`added`";
		$columns_array = explode(',',$columns);
		$empty_row = array();
		foreach ($columns_array as $c) {
			$c = str_replace('`', '', $c);
			$empty_row[$c]="";
		}
		
		$empty_row["id"]="";
		
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