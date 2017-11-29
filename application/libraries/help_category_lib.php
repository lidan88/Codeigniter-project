<?

class Help_category_lib
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
	
	function add($parent_id,$title)
	{
		$this->ci->db->query("INSERT INTO `help_category` (`parent_id`,`title`) VALUES (".$this->escape($parent_id).",".$this->escape($title).")");
		return true; 
	}
	
	function update($id,$parent_id,$title)
	{
		$this->ci->db->query("UPDATE `help_category` set `parent_id`=".$this->escape($parent_id).",`title`=".$this->escape($title)." WHERE `id`='$id'");
		return true; 
	}
	
	function delete($id)
	{
		$this->ci->db->query("DELETE FROM `help_category` WHERE `id`='$id'");
		return true;
	}
	
	function get_count($filter='1=1')
	{	
		return $this->ci->db->query("SELECT count(*) as cnt from  `help_category` WHERE $filter")->row()->cnt;
	}
	
	function get_list()
	{
		$this->ci->load->library("api_lib");
		
		return $this->ci->api_lib->get_list("select 
					* 
				from 
					`help_category`
				order by `priority` ASC");
	}
	
	function detail($item_id)
	{
			return $this->row("select 
					`help_category`.* 
				from 
					`help_category` WHERE `help_category`.id=".$this->escape($item_id));	
	}
	
	function empty_row()
	{
		$columns = "`parent_id`,`title`";
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
		
}

?>