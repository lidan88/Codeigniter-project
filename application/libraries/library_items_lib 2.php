<?

class Library_items_lib
{
	var $items_per_page = 500;
	var $list;
	var $company_id;

	public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->database();
        
        $user_info = $this->ci->session->userdata("user_info");
        $this->company_id = $user_info['company_id'];
        //$this->company_id=0;
	}
	
	public function escape($item)
	{
		return $this->ci->db->escape($item);	
	}
	
	function add($library_id,$var_name,$var_type,$var_value,$var_value_type,$item_order,$is_required,$help,$show_by_default='Yes')
	{
		$uploaddir = '';
		
		$this->ci->db->query("INSERT INTO `library_items` (`library_id`,`var_name`,`var_type`,`var_value`,`var_value_type`,`item_order`,`is_required`,`help`,`show_by_default`) VALUES (".$this->escape($library_id).",".$this->escape($var_name).",".$this->escape($var_type).",".$this->escape($var_value).",".$this->escape($var_value_type).",".$this->escape($item_order).",".$this->escape($is_required).",".$this->escape($help).",".$this->escape($show_by_default).")");
		return true; 
	}
	
	function update($id,$library_id,$var_name,$var_type,$var_value,$var_value_type,$item_order,$is_required,$help,$show_by_default='Yes')
	{
		$uploaddir = '';
		$this->ci->db->query("UPDATE `library_items` set `library_id`=".$this->escape($library_id).",`var_name`=".$this->escape($var_name).",`var_type`=".$this->escape($var_type).",`var_value`=".$this->escape($var_value).",`var_value_type`=".$this->escape($var_value_type).",`item_order`=".$this->escape($item_order).",`is_required`=".$this->escape($is_required).",`show_by_default`=".$this->escape($show_by_default).",`help`=".$this->escape($help)." WHERE `id`='$id'");
		return true; 
	}
	
	function delete($id)
	{
		$this->ci->db->query("DELETE FROM `library_items` WHERE `id`='$id'");
		return true;
	}
	
	function get_count($filter='1=1')
	{	
		return $this->ci->db->query("SELECT count(*) as cnt from  `library_items` WHERE $filter")->row()->cnt;
	}
	
	function get_list($page = 0,$filter = '',$sort = 'item_order',$ord = 'ASC')
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
				`library_items`.*,

`library`.name as flibrary_id 
				
				from 
					`library_items`,

`library`  
				WHERE 
					
library_items.library_id=`library`.id 
							$filter 
						
							$sort 
						limit $start,".$this->items_per_page);
	}
	
	function detail($item_id)
	{
		if(preg_match('/(WHERE)/i',"select 
				`library_items`.*,

`library`.name as flibrary_id 
				
				from 
					`library_items`,

`library`  
				WHERE 
					
library_items.library_id=`library`.id"))
		{
			return $this->row("select 
				`library_items`.*,

`library`.name as flibrary_id 
				
				from 
					`library_items`,

`library`  
				WHERE 
					
library_items.library_id=`library`.id and `library_items`.id=".$this->escape($item_id));	
			
		}
		else {
			return $this->row("select 
				`library_items`.*,

`library`.name as flibrary_id 
				
				from 
					`library_items`,

`library`  
				WHERE 
					
library_items.library_id=`library`.id WHERE `library_items`.id=".$this->escape($item_id));	
			
		}
	
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
	
	function display_pagination($base_url = '/',$page = 0,$filter ='1=1')
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
		
		return $this->ci->pagination->create_links();
		
	}
	
	function get_library_id_by_name($company_id,$library_name)
	{
		$ret = $this->row("select id from library where company_id=".$company_id." and `name`=".$this->escape($library_name)." limit 1");
		if(isset($ret['id']))
			return $ret['id'];
		else {
			return 0;
		}
	}
	
	function get_field_types()
	{
		return get_global_field_types();
	}
	
	function get_field_value_types()
	{
		return array("T"=>"Simple Text",
					"P"=>"PHP Evaluate",
					"Q"=>"Mysql Query",
					"L"=>"Comma Seperated List"
					);
	}
	
	function return_rendered_items($library_id,$replace_with = '',$replace_with_array = '',$value_id = 0)
	{
		$list_to_return = array();
		
		if(!is_array($this->list))
			$this->list = $this->get_list(0,'library_id='.$library_id);
		
		if(is_array($this->list) and count($this->list)>0)
		{
		    foreach($this->list as $k => $row)
		    {
		    	$item_to_render = '';
		    	switch($row['var_type'])
		    	{
		    		case "T":
		    			$item_to_render = '<input class="form-control" type="text" name="items[replace]['.$row['id'].']" value="[value]" placeholder="'.$row['help'].'" />';
		    		break;
		    		case "TA":
		    			$item_to_render = '<textarea name="items[replace]['.$row['id'].']" class="form-control autogrow" placeholder="'.$row['help'].'">[value]</textarea>';
		    		break;
		    		case "DATE":
		    			$item_to_render = '<input name="items[replace]['.$row['id'].']" class="form-control col-md-2 datepicker" size="16" type="text" value="[value]">';
		    		break;
		    		case "TIME":
		    			$item_to_render = '<input name="items[replace]['.$row['id'].']" class="form-control col-md-2 timepicker" size="16" type="text" value="[value]">';
		    		break;
		    		case "R":
		    			$values = explode(',',$row['var_value']);
		    			foreach($values as $v){
		    				$item_to_render .= '<input type="radio" name="items[replace]['.$row['id'].']" value="'.$v.'" /> '.$v;
		    			}
		    		break;
		    		case "D":
		    			$item_to_render = '<select name="items[replace]['.$row['id'].']" data-placeholder="'.$row['help'].'" class="form-control chosen-select">';
		    			$res = $this->ci->db->query("select dropdown_id,dropdown_item_id,`name` from `dropdown_item` where is_active='Yes' and dropdown_id=".$row['var_value']);
	    				if($res->num_rows()>0)
	    				{
	    					$item_to_render .= '<option value=""></option>';
	    					
	    					foreach ($res->result_array() as $data)
	    				 	{
	    				 		//echo '<option value="'.$data['dropdown_item_id'].'">'.$data['name'].'</option>';
	    				 		if(is_array($replace_with_array) and isset($replace_with_array[$row['id']]) and $replace_with_array[$row['id']]==$data['name'])
	    				 			$item_to_render .= '<option value="'.$data['name'].'" selected="selected">'.$data['name'].'</option>';
	    				 		else
	    				 			$item_to_render .= '<option value="'.$data['name'].'">'.$data['name'].'</option>';
	    				 	}
	    				 }
		    			$item_to_render .= '</select>';
		    			
		    		break;
		    		case "LIBRARY":
		    			$item_to_render = '<select name="items[replace]['.$row['id'].']" data-placeholder="'.$row['help'].'" class="form-control chosen-select">';
		    			$item_to_render .= '<option value=""></option>';
		    			
		    			$this->ci->load->library("library_values");
		    			$temp = explode(":",$row['var_value']);
		    			if(is_numeric($temp[1]))
		    			{
		    				$options = $this->ci->library_values->get_select_box_by_libitemid($temp[0],$temp[1]);
		    			}
		    			else {
		    				//die($temp[0].' '.$temp[1]);
		    				$options = $this->ci->library_values->get_select_box_by_libitemid($temp[0],explode(',',$temp[1]));
		    			}
		    			
		    			if(is_array($options) and count($options)>0)
		    			foreach($options as $option_key => $option)
		    			{
		    				if(is_array($replace_with_array) and isset($replace_with_array[$row['id']]) and $replace_with_array[$row['id']]==$option_key)
		    					$item_to_render .= '<option value="'.$option_key.'" selected="selected">'.$option.'</option>';
		    				else
		    					$item_to_render .= '<option value="'.$option_key.'">'.$option.'</option>';
		    			}
		    			$item_to_render .= '</select>';
		    			
		    		break;
		    		case "LIBRARY_MSEL":
		    			$item_to_render = '<select name="items[replace]['.$row['id'].'][]" data-placeholder="'.$row['help'].'" class="form-control chosen-select" multiple>';
		    			$item_to_render .= '<option value=""></option>';
		    			
		    			$this->ci->load->library("library_values");
		    			$temp = explode(":",$row['var_value']);
		    			if(is_numeric($temp[1]))
		    				$options = $this->ci->library_values->get_select_box_by_libitemid($temp[0],$temp[1]);
		    			else {
		    				$options = $this->ci->library_values->get_select_box_by_libitemid($temp[0],explode(',',$temp[1]));
		    			}
		    			
		    			if(is_array($options) and count($options)>0)
		    			foreach($options as $option_key => $option)
		    			{
		    				if(is_array($replace_with_array) and isset($replace_with_array[$row['id']]) and $replace_with_array[$row['id']]==$option_key)
		    					$item_to_render .= '<option value="'.$option_key.'" selected="selected">'.$option.'</option>';
		    				elseif(is_array($replace_with_array) and isset($replace_with_array[$row['id']]) and is_array($replace_with_array[$row['id']]) and in_array($option_key, $replace_with_array[$row['id']]))
		    					$item_to_render .= '<option value="'.$option_key.'" selected="selected">'.$option.'</option>';
		    				else
		    					$item_to_render .= '<option value="'.$option_key.'">'.$option.'</option>';
		    			}
		    			$item_to_render .= '</select>';
		    			
		    		break;
		    		case "MAP":
		    				if($value_id)
		    				{
		    					$this->ci->load->library("library_values");
			    				$locations = $this->ci->library_values->get_locations_for_map($value_id,$row['var_value']);
			    				
			    				$locationsJson = '';
			    				if(is_array($locations) and count($locations)>0)
			    				{
			    					$locationsJson = json_encode($locations);
			    				}
			    						
			    				$item_to_render = "<script>
			    				var geocoder;
			    				var map;
			    				var locations = ".$locationsJson.";
			    				
			    				function initialize() {
			    					geocoder = new google.maps.Geocoder();
			    					var latlng = new google.maps.LatLng(37.904,-95.712);
			    				  var mapOptions = {
			    				    zoom: 4,
			    				    center: latlng,
			    				    disableDefaultUI: true,
			    				    zoomControl: true
			    				  };
			    				
			    				      map = new google.maps.Map(document.getElementById('map-canvas'),
			    				      mapOptions);
			    				      
			    				  //setTimeout('codeAddress();',3000);
			    				  codeAddress();
			    				}
			    				
			    				function codeAddress() {
			    				  
			    				  for(x in locations)
			    				  {
				    				  geocoder.geocode( { 'address': locations[x]}, function(results, status) {
				    				    if (status == google.maps.GeocoderStatus.OK) {
				    				      map.setCenter(results[0].geometry.location);
				    				      var marker = new google.maps.Marker({
				    				          map: map,
				    				          position: results[0].geometry.location
				    				      });
				    				    } else {
				    				      //alert('Geocode was not successful for the following reason: ' + status);
				    				    }
				    				  });
			    				  }
			    				}
			    				
			    				
			    				function loadScript() {
			    				  var script = document.createElement('script');
			    				  script.type = 'text/javascript';
			    				  script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&' +
			    				      'callback=initialize';
			    				  document.body.appendChild(script);
			    				}
			    				
			    				window.onload = loadScript;</script>
			    				<div style='padding:5px'><a href='javascript:;' onclick='$(\"#map-canvas\").toggleClass(\"hidden\");'>Toggle Map</a></div>
			    				<div id='map-canvas' style='width:100%;height:500px'></div>"; 
		    				}
		    				else {
		    					$item_to_render = 'Module will activate after information is saved';
		    				}
		    		break;
		    		case "MSEL":
		    			$item_to_render = '<select name="items[replace]['.$row['id'].'][]" class="form-control chosen-select" multiple>';
	    				$res = $this->ci->db->query("select dropdown_id,dropdown_item_id,`name` from `dropdown_item` where is_active='Yes' and dropdown_id=".$row['var_value']);
	    				if($res->num_rows()>0)
	    				{
	    					foreach ($res->result_array() as $data)
	    				 	{
	    				 		if(is_array($replace_with_array) and isset($replace_with_array[$row['id']]) and $replace_with_array[$row['id']]==$data['name'])
	    				 			$item_to_render .= '<option value="'.$data['name'].'" selected="selected">'.$data['name'].'</option>';
	    				 		elseif(is_array($replace_with_array) and isset($replace_with_array[$row['id']]) and is_array($replace_with_array[$row['id']]) and in_array($data['name'], $replace_with_array[$row['id']]))
	    				 			$item_to_render .= '<option value="'.$data['name'].'" selected="selected">'.$data['name'].'</option>';
	    				 		else
	    				 			$item_to_render .= '<option value="'.$data['name'].'">'.$data['name'].'</option>';
	    				 	}
	    				 }
	    				$item_to_render .= '</select>';
	    				
		    		break;
		    		case "USERS":
		    			$item_to_render = '<select name="items[replace]['.$row['id'].'][]" class="form-control chosen-select" multiple>';
		    			$res = $this->ci->db->query("select user_id,first_name,last_name from `users`");
		    			
		    			//print_r($replace_with_array);
		    			//die;
		    			
		    			if($res->num_rows()>0)
		    			{
		    				foreach ($res->result_array() as $data)
		    			 	{
		    			 		if(is_array($replace_with_array) and isset($replace_with_array[$row['id']]) and $replace_with_array[$row['id']]==$data['user_id'])
		    			 		{
		    			 			$item_to_render .= '<option value="'.$data['user_id'].'" selected="selected">'.$data['first_name'].'</option>';
		    			 		}elseif(is_array($replace_with_array) and isset($replace_with_array[$row['id']]) and is_array($replace_with_array[$row['id']]) and in_array($data['user_id'], $replace_with_array[$row['id']]))
		    			 			$item_to_render .= '<option value="'.$data['user_id'].'" selected="selected">'.$data['first_name'].'</option>';
		    			 		else
		    			 			$item_to_render .= '<option value="'.$data['user_id'].'">'.$data['first_name'].'</option>';
		    			 	}
		    			 }
		    			$item_to_render .= '</select>';
		    		break;
		    		case "GRID":
		    			$item_to_render = '<table class="table">';
		    			//<select name="items[replace]['.$row['id'].']" class="form-control chosen-select">';
		    			
		    			//$this->ci->load->library("library_values");
		    			$temp = explode("|",$row['var_value']);
		    			
		    			if(count($temp)<2 or count($temp)>3)
		    				break;
		    			
		    			$columns = explode(',', $temp[0]);
		    			$rows = explode(',', $temp[1]);
		    			$choices = isset($temp[2])?explode(',', $temp[2]):array('-');
		    			$item_to_render .= '<tr>';
		    			foreach ($columns as $column) {
		    				$item_to_render .= '<td>'.trim($column).'</td>';
		    			}
		    			$item_to_render .= '</tr>';
		    			
		    			$row_ctr=0;
		    			foreach ($rows as $r) {
		    				$item_to_render .= '<tr>';
		    					$item_to_render .= '<td>'.$r.'</td>';
		    					for ($i = 0; $i < count($columns)-1; $i++) {
		    						$item_to_render .= '<td><select name="items[replace]['.$row['id'].']['.$row_ctr.']['.$i.']" class="form-control chosen-select">
		    								<option value=""></option>';
		    						foreach ($choices as $choice) {
		    							
		    							if(is_array($replace_with_array) and isset($replace_with_array[$row['id']][$row_ctr][$i]) and $replace_with_array[$row['id']][$row_ctr][$i]==$choice)
		    								$item_to_render .= '<option value="'.$choice.'" selected="selected">'.$choice.'</option>';
		    							else	
		    								$item_to_render .= '<option value="'.$choice.'">'.$choice.'</option>';
		    						
		    						}
		    								
		    						$item_to_render .= '</select>
		    						</td>';
		    					}
		    				$item_to_render .= '</tr>';
		    				
		    				$row_ctr++;
		    				
		    			}
		    					    			
		    			$item_to_render .= '</table>';
		    		break;
		    		
		    		case "C":
		    			$values = explode(',',$row['var_value']);
		    			foreach($values as $v)
		    			{
		    				if(is_array($replace_with_array) and isset($replace_with_array[$row['id']]) and $replace_with_array[$row['id']]==$v)
		    					$item_to_render .= '<div class="checkbox"><label><input type="checkbox" checked="checked" name="items[replace]['.$row['id'].'][]" value="'.$v.'" /> '.$v.' </lable></div>';
		    				elseif(is_array($replace_with_array) and isset($replace_with_array[$row['id']]) and is_array($replace_with_array[$row['id']]) and in_array($v, $replace_with_array[$row['id']]))
		    				{
		    					$item_to_render .= '<div class="checkbox"><label><input type="checkbox" checked="checked" name="items[replace]['.$row['id'].'][]" value="'.$v.'" /> '.$v.' </lable></div>';
		    				}else
		    					$item_to_render .= '<div class="checkbox"><label><input type="checkbox" name="items[replace]['.$row['id'].'][]" value="'.$v.'" /> '.$v.' </lable></div>';
		    			}
		    		break;
		    		case "F":
		    		
		    			if(is_array($replace_with_array) and isset($replace_with_array[$row['id']]))
		    			{
		    				
			    			$item_to_render = '<input type="hidden" name="items[replace]['.$row['id'].']" value="[value]" />';
		    				
		    				$item_to_render .= '<a target="_blank" href="/user_data/'.$replace_with_array[$row['id']].'">'.$replace_with_array[$row['id']].'</a><br />';
		    				$item_to_render .= '<input type="file" class="form-control" name="items[replace]['.$row['id'].']" value="" />';
		    			}
		    			else {
		    				$item_to_render .= '<input type="file" class="form-control" name="items[replace]['.$row['id'].']" value="" />';
		    			}
		    		break;
		    		case "TASK":
		    			
		    			if($value_id)
		    			{
			    			$res = $this->ci->db->query("select user_id,first_name,last_name from `users`");
			    			$this->ci->load->library("tasks_lib");
			    			
			    			$tasks = $this->ci->tasks_lib->get_by_library_value($value_id);
			    			
			    			//print_r($replace_with_array);
			    			//die;
			    			$user_options='<option value="">Assign To</option>';
			    			if($res->num_rows()>0)
			    			{
			    				foreach ($res->result_array() as $data)
			    				{
			    					$user_options .= '<option value="'.$data['user_id'].'">'.$data['first_name'].' '.$data['last_name'].'</option>';
			    				}
			    			}
			    			
			    			
			    			$item_to_render = '
			    			<style>
			    				.task_row{ padding:2px; }
			    			</style>
			    			<div id="task_list">
			    				<div id="task_template" class="row hidden" style="margin-bottom:5px">
			    					<div class="col-md-12">
				    					<div class="pull-left text-right" style="width:15px;padding-top:10px">
				    						<span style="cursor: move;" class="icon icon-th-list handle"></span> <span class="num">1</span>
				    					</div>
				    					<div class="pull-left" style="width:350px;margin-left:10px">
				    						<textarea class="form-control task_title" name="" placeholder="Enter Task Here"></textarea>
				    						<!--<input type="text" class="form-control task_title" name="" value="" placeholder="Enter Task Here" />-->
				    						<input type="hidden" class="task_id" name="" value="0" />
				    					</div>
				    					<div class="pull-left" style="width:180px;padding-top:5px;margin-left:10px">
				    						<select name="" class="form-control assigned_to">
				    							'.$user_options.'
				    						</select>
				    					</div>
				    					<div class="pull-left" style="width:10px; padding-top:10px;margin-left:10px">
				    						<a href="javascript:;" class="btn_delete" onclick="delete_this($(this))"><span class="icon icon-remove"></span></a>
				    					</div>
				    					<div class="pull-left" style="margin-left:10px;padding-top:10px">
				    						<span class="label label-default task_status">Pending</span>
				    					</div>
			    					</div>
			    					
			    				</div>
			    			</div>
			    			<div class="padd2">
			    				<a href="javascript:;" onclick="new_task()" class="btn btn-xs btn-success">New Task</a>
			    			</div>
			    			<script>
			    				var current_task_num=1;
			    				var tasks = '.json_encode($tasks).';
			    				function new_task()
			    				{
			    					var $template = $("#task_template").clone();
			    					$template.removeClass("hidden").find("select").addClass("chosen-select");
			    					$template.find(".num").html(current_task_num);
			    					$template.find(".task_id").attr("name","tasks[N"+current_task_num+"][task_id]");
			    					$template.find(".task_title").attr("name","tasks[N"+current_task_num+"][task_title]");
			    					$template.find(".assigned_to").attr("name","tasks[N"+current_task_num+"][assigned_to]");
			    					
			    					$template.appendTo("#task_list");
			    					$(".chosen-select").chosen();
			    					current_task_num++;
			    				}
			    				
			    				function show_task(task_id,title,assigned_to,task_status)
			    				{
			    					var $template = $("#task_template").clone();
			    					$template.removeClass("hidden").find("select").addClass("chosen-select");
			    					$template.find(".num").html(current_task_num);
			    					$template.find(".task_id").val(task_id).attr("name","tasks["+task_id+"][task_id]");
			    					$template.find(".task_title").val(title).attr("name","tasks["+task_id+"][task_title]");
			    					$template.find(".assigned_to").val(assigned_to).attr("name","tasks["+task_id+"][assigned_to]");
			    					$template.find(".btn_delete").attr("onclick","delete_task("+task_id+",$(this))");
			    					if(task_status=="Completed")
			    						$template.find(".task_status").html(task_status).removeClass("label-default").addClass("label-success");
			    					$template.appendTo("#task_list");
			    					$(".chosen-select").chosen();
			    					current_task_num++
			    				}
			    				
			    				function delete_this($obj)
			    				{
			    					$obj.parent().parent().remove();
			    				}
			    				
			    				function delete_task(task_id,$obj)
			    				{
			    					$.get("/admin/tasks/do_delete/"+task_id);
			    					delete_this($obj);
			    				}
			    				
			    				if(tasks.length>0)
			    				{
			    					for(x in tasks)
			    					{
			    						var task = tasks[x];
			    						var task_status = task["completed"]==0?"Pending":"Completed";
			    						show_task(task["task_id"],task["title"],task["assigned_to"],task_status);
			    					}
			    				}
			    			</script>';
		    			
		    			}
		    			else {
		    				$item_to_render = 'Module will activate after information is saved';
		    			}
		    		break;
		    		
		    	}
		    	
		    	$item_to_render = str_replace('[replace]', $replace_with, $item_to_render);
		    	if(is_array($replace_with_array) and isset($replace_with_array[$row['id']]) and !is_array($replace_with_array[$row['id']]))
		    	{
		    		$item_to_render = str_replace('[value]', $replace_with_array[$row['id']], $item_to_render);
		    	}
		    	else {
		    		$item_to_render = str_replace('[value]', '', $item_to_render);
		    	}
		    	
		    	$row['render']=$item_to_render;
		    	
		    	$list_to_return[$row['var_name']]=$row;
		    
		    }
		    
		}
	
	    return $list_to_return;
	
	}
	
	function encode_user_post_data()
	{
		$items = $this->ci->input->post('items',TRUE);

		$uploaddir=$this->ci->config->item('upload_path');
		
		if(isset($_FILES['items']) and isset($_FILES['items']['name']))
		{
			foreach($_FILES['items']['name'] as $item_id =>$item_name)
			{
				//echo $item_name.' ';
				if($_FILES['items']['error'][$item_id]==0)
				{
					$fname= basename($_FILES['items']['name'][$item_id]);
					$fname=str_replace(" ","_",$fname);	
					$fname=trim($fname);
					$uploadfile = $uploaddir.$fname;
				
					if(move_uploaded_file($_FILES['items']['tmp_name'][$item_id],$uploadfile))
					{
						$file = $fname;
						$items[$item_id]=$file;
						//$this->load->library('s3');
						//$input = $this->s3->inputFile($image);
						//$ret = $this->s3->putObject($input, "BUCKET_NAME", 'FOLDER_NAME/'.$fname);
						//unlink($image);
						
					}
					
				}
			}
		}
		
		//print_r($items);
		//die;
		$itemsEncoded = json_encode($items);
		return $itemsEncoded;
	}
	
		
}

?>