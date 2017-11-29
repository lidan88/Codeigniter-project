<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define("OPT_SEPERATOR", "[#]");

//			list($columns,$resourceTypes,$options) = getGridHeaders($fields['Required Resources Over Time']);

function getGridHeaders($gridField)
{
	$parts = explode('|',$gridField);
	$columns = explode(',',$parts[0]);
	$rows = explode(',',$parts[1]);
	$options = explode(',',$parts[2]);
	return array($columns,$rows,$options);	
}

function hasKey($array,$key)
{
	if(is_array($array))
	{
		if(isset($array[$key])){
			return $array[$key];
		}else {
			return "";
		} 
	}
	else {
		return "";
	}
}

function calculateThreatWeight($l,$i)
{
	$weight_matrix = array();

	$weight_matrix['LOW'] = array();
	$weight_matrix['LOW']['LOW']="LOW";
	$weight_matrix['LOW']['MEDIUM']="LOW";
	$weight_matrix['LOW']['HIGH']="MEDIUM";

	$weight_matrix['MEDIUM'] = array();
	$weight_matrix['MEDIUM']['LOW']="LOW";
	$weight_matrix['MEDIUM']['MEDIUM']="MEDIUM";
	$weight_matrix['MEDIUM']['HIGH']="HIGH";
	
	$weight_matrix['HIGH'] = array();
	$weight_matrix['HIGH']['LOW']="MEDIUM";
	$weight_matrix['HIGH']['MEDIUM']="HIGH";
	$weight_matrix['HIGH']['HIGH']="CRITICAL";
	
	return $weight_matrix[$l][$i];
}

function detectHours($str)
{
	$num = preg_replace('/[^0-9]/','',$str);
	$period = strtolower(preg_replace('/[^a-zA-Z]/', '', $str));
	if(is_numeric($num))
	{
		switch($period)
		{
			case "day":
			case "days":
				return $num * 24;
			break;
			case "hour":
			case "hours":
				return $num;
			break;
			default:
				return $num;
		}
	}
	else {
		return 0;
	}
}

function filterByObjToArray($filter_by)
{
	if(!is_array($filter_by))
		$filter_by = json_decode($filter_by,true);

	$search = array();
	if(is_array($filter_by))
	{
		foreach ($filter_by as $key => $value) {
			//$v = json_decode($value,true);
			$search[] = $value['c'].OPT_SEPERATOR.$value['f'];
			//print_r($value).'<br />';
		}
	}
	
	return $search;
}

function detectModuleInternalType($module_type)
{
	if($module_type=="lib" or $module_type=="bia" or $module_type=="gallery")
		return "lib";
	else {
		return $module_type;
	}
}

function filterQueryFromArray($search,$id = "`id`",$value="`value`")
{
	$filter = "";
	$is_numeric=true;
	foreach ($search as $sk => $sv) {
		if(!is_numeric($sv))
			$is_numeric=false;
	}

	if($is_numeric)
	{
		$filter = $id." in (".implode(',',$search).") and ";
	}
	else {
		$filter = '';
		$filterArray = array();
		foreach ($search as $sk => $sv) {
			if($sv!='')
			{
				$temp = explode(OPT_SEPERATOR,$sv);
				$filterArray[] = $value." like '%".$temp[1]."%'";
			}
		}
		
		if(count($filterArray)>0)
			$filter = "(".implode(" or ", $filterArray).") and ";
	}
	
	return $filter;
	
}

function categoriesToTree(&$categories) {

    $map = array(
        0 => array('files' => array())
    );

    foreach ($categories as &$category) {
        if($category['is_folder'])
			$category['files'] = array();
        $map[$category['file_id']] = &$category;
    }

    foreach ($categories as &$category) {
        $map[$category['parent_id']]['files'][] = &$category;
    }

    return $map[0]['files'];

}

function itemsToTree($items)
{
	//$items = $data['categories'];
	$childs = array();
	foreach($items as &$item) $childs[$item['parent_id']][] = &$item;
	unset($item);
	
	foreach($items as &$item) if (isset($childs[$item['id']]))
	        $item['childs'] = $childs[$item['id']];
	unset($item);
	
	$tree = $childs[0];
	return $tree;	
}

function printTree($tree,$parent = 0)
{
	foreach($tree as $t)
	{
		$space = '&nbsp;&nbsp;&nbsp;';
		for($x=0;$x<$parent;$x++)
			$space .= '&nbsp;&nbsp;&nbsp;';
		
		echo '<option value="'.$t['id'].'">'.$space.$t['title'].'</option>';
		
		if(isset($t['childs']))
		{
			printTree($t['childs'],$parent+1);
		}
	}
}

function printTreeTr($tree,$parent = 0)
{
	foreach($tree as $t)
	{
		$space = '&nbsp;&nbsp;&nbsp;';
		for($x=0;$x<$parent;$x++)
			$space .= '&nbsp;&nbsp;&nbsp;';
		
			echo '<tr id="item_no_'.$t['id'].'">
					<td><span style="cursor: move;" class="icon icon-th-list handle"></span>&nbsp;&nbsp;<input type="checkbox" name="items['.$t['id'].']" value="'.$t['id'].'" /></td>
					<td>'.$space.$t['title'].'</td>
				</tr>';
		
		if(isset($t['childs']))
		{
			printTreeTr($t['childs'],$parent+1);
		}
	}
}

function getHost() {
    $possibleHostSources = array('HTTP_X_FORWARDED_HOST', 'HTTP_HOST', 'SERVER_NAME', 'SERVER_ADDR');
    $sourceTransformations = array(
        "HTTP_X_FORWARDED_HOST" => function($value) {
            $elements = explode(',', $value);
            return trim(end($elements));
        }
    );
    $host = '';
    foreach ($possibleHostSources as $source)
    {
        if (!empty($host)) break;
        if (empty($_SERVER[$source])) continue;
        $host = $_SERVER[$source];
        if (array_key_exists($source, $sourceTransformations))
        {
            $host = $sourceTransformations[$source]($host);
        } 
    }

    // Remove port number from host
    $host = preg_replace('/:\d+$/', '', $host);

    return trim($host);
}

function getDDByCompany($company_id = 0)
{
	if(is_numeric($company_id) and $company_id>0)
	{
		return "`continuitypro_".$company_id."`";
	}
	else {
		return "continuitypro";
	}
}

function setDBByCompany($db,$company_id = 0)
{
	if(is_numeric($company_id) and $company_id>0)
	{
		//$this->ci->db->query();
		//$db->query("USE INFORMATION_SCHEMA");
		//$found = $db->query("SELECT IF('".getDDByCompany($company_id)."' IN(SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA), 1, 0) AS found")->row()->found;
		$found = $db->query("SELECT count(*) as cnt from company where company_id=".$company_id)->row()->cnt;
		//echo "setting -> ".getDDByCompany($company_id)." found-> ".$found;
		
		if($found)
		{
			$db->query("USE ".getDDByCompany($company_id));
		}
	}
}

function ArrayMergeKeepKeys() {
      $arg_list = func_get_args();
      foreach((array)$arg_list as $arg){
          foreach((array)$arg as $K => $V){
              $Zoo[$K]=$V;
          }
      }
    return $Zoo;
}

function get_weight_class($weight)
{
	if($weight=='LOW')
		return "alert-success";
	else if($weight=='MEDIUM')
		return "alert-warning";
	else {
		return "alert-danger";
	}
}

function get_global_field_types()
{
	return array("T"=>"Text Box",
			"TA"=>"Text Area",
			"R"=>"Radio",
			"C"=>"Check Box",
		//	"S"=>"Select Box",
			"D"=>"Drop Down",
			"MSEL"=>"Multi Select Drop Down",
			"LIBRARY"=>"Library",
			"LIBRARY_MSEL" => "Library Multi Select",
			"F"=>"File",
			"DATE"=>"Date",
			"TIME"=>"Time",
			"GRID"=>"Grid",
			"USERS"=>"Users",
			"MAP"=>"Map",
			"TASK"=>"Tasks",
			"TEAM"=>"Team"
			);
}

function hl($inp, $words)
{
  $replace=array_flip(array_flip($words)); // remove duplicates
  $pattern=array();
  foreach ($replace as $k=>$fword) {
     $pattern[]='/\b(' . $fword . ')(?!>)\b/i';
     $replace[$k]='<b>$1</b>';
  }
  return preg_replace($pattern, $replace, $inp);
}

function isIphone($user_agent=NULL) {
    if(!isset($user_agent)) {
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    }
    return (strpos($user_agent, 'iPhone') !== FALSE);
}

if ( ! function_exists('valid_email'))
{
	function valid_email($address)
	{
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
	}
}

if ( ! function_exists('set_redirect_after_login'))
{
	function set_redirect_after_login($url)
	{
		$ci = get_instance();
		return $ci->session->set_userdata("redirect_after_login",$url);
	}
}

if ( ! function_exists('get_redirect_after_login'))
{
	function get_redirect_after_login()
	{
		$ci = get_instance();
		$url = $ci->session->userdata("redirect_after_login");
		$ci->session->unset_userdata("redirect_after_login");
		return $url;
	}
}

function render_tree($array)
{
	$employees = array();
	if(is_array($array))
	{
		foreach ($array as $key => $value) {
			$employees[] = $value['id'];
			
			if(isset($value['children']) and is_array($value['children']) and count($value['children'])>0)
			{
				$employees2 = render_tree($value['children']);
			}
		}
		
		if(isset($employees2))
			return array_merge($employees,$employees2);
		else {
			return $employees;
		}
	}
}

function normalize_tree($array,$parent = 0)
{
	$employees = array();
	if(is_array($array))
	{
		foreach ($array as $key => $value) {
			if($parent>0)
				$employees[] = array($parent,$value['id']);
			else {
				if(isset($array[$key+1]))
				{
					$employees[] = array($value['id'],$array[$key+1]['id']);
				}
			}
			
			if(isset($value['children']) and is_array($value['children']) and count($value['children'])>0)
			{
				$employees2 = normalize_tree($value['children'],$value['id']);
				
				//print_r($employees2);
				//die;
				//array_merge($employees,$employees2);
			
			}
			
			if(isset($employees2))
				$employees = array_merge($employees,$employees2);
		}
		
		/*if(isset($employees2))
			return array_merge($employees,$employees2);
		else {
			return $employees;
		}*/
		
		return $employees;
	}
}

function array_recursive_value($array)
{
	if(is_array($array))
	{
		$return=array();
		foreach ($array as $key => $value) 
		{
			if(is_array($value))
			{
				$v =	array_recursive_value($value);
				if($v!='')
					$return[] = opt2value($v);
			}
			else {
				if(trim($value)!='')
					$return[] =	opt2value($value);
			}
		}
		return implode(',',$return);
	}
	else {
		return $array;
	}
}

function opt2value($str)
{
	$arr = explode(OPT_SEPERATOR,$str);
	if(isset($arr[3]))
		return $arr[3];
	else {
		return $str;
	}
}

function opt2id($str)
{
	$arr = explode(OPT_SEPERATOR,$str);
	if(isset($arr[2]))
		return $arr[2];
	else {
		return $str;
	}
}

function opt2valueid($str)
{
	$arr = explode(OPT_SEPERATOR,$str);
	if(isset($arr[3]))
		return $arr[3];
	else {
		return $str;
	}
}


function is_file_name($file)
{
	if(preg_match('/(pdf|jpg|doc|docx|xls)/',$file))
		return true;
	else {
		return false;
	}
}

/*
	This will convert Employees into library-employees
*/
function get_libuserid_from_name($name)
{
	return 'library-'.str_replace(' ', '-', strtolower(trim($name)));
}

function get_module_type($module_type)
{
	$array = explode(":",$module_type);
	if(isset($array[1]))
		return $array[1];
	else {
		return "";
	}
}

function is_field_skippable($field)
{
	if($field['show_by_default']=='No')
		return true;
		
	if($field['var_type']=='GRID')
		return true;
		
	if($field['var_type']=='TA')
		return true;
		
	//if(strlen($field['var_name'])>15)
	//	return true;
		

return false;
	
}

function get_file_icon_by_extension($ext = '',$folder = 'file_v2')
{
	switch($ext)
	{
		case "png":
				return "/img/icons/".$folder."/png.png";	
			break;
		case "jpg":
				return "/img/icons/".$folder."/jpg.png";	
			break;
		case "pdf":
				return "/img/icons/".$folder."/pdf.png";	
			break;
		case "doc":
		case "docx":
			return "/img/icons/".$folder."/doc.png";	
			break;
		case "ppt":
			return "/img/icons/".$folder."/ppt.png";	
			break;
		case "txt":
			return "/img/icons/".$folder."/txt.png";	
			break;
		case "xls":
			return "/img/icons/".$folder."/xls.png";	
			break;
		case "zip":
			return "/img/icons/".$folder."/zip.png";	
			break;
		default:
			echo "/img/icons/".$folder."/file.png";	
	}
}


function get_file_type_by_extension($ext = '')
{
	switch($ext)
	{
		case "png":
		case "jpg":
		case "gif":
		case "jpeg":
			return "Image";	

		case "pdf":
		case "doc":
		case "docx":
			return "Document";
			break;
		case "ppt":
			return "Powerpoint";	
			break;
		case "txt":
			return "Text";	
			break;
		case "xls":
			return "Spreadsheet";	
			break;
		case "zip":
			return "Compressed";	
			break;
		default:
			echo "Other File";	
	}
}

function getIP() 
{
	$ip;
	if (getenv("HTTP_CLIENT_IP"))
	$ip = getenv("HTTP_CLIENT_IP");
	else if(getenv("HTTP_X_FORWARDED_FOR"))
	$ip = getenv("HTTP_X_FORWARDED_FOR");
	else if(getenv("REMOTE_ADDR"))
	$ip = getenv("REMOTE_ADDR");
	else
	$ip = "UNKNOWN";
	return $ip;

} 

if ( ! function_exists('cut'))
{
	function cut($string,$length)
	{
		if(strlen($string)>$length)
		{
			return substr($string,0,$length).'..';	
		}
		else
			return $string;
	}
}

global $global_cmp_key;
if ( ! function_exists('global_cmp'))
{
	function global_cmp($a, $b) {
	    global $global_cmp_key;
	    
	    if(!isset($a[$global_cmp_key]))
	    	return 0;
	    
	    if ($a[$global_cmp_key] == $b[$global_cmp_key]) {
	        return 0;
	    }
	    return ($a[$global_cmp_key] < $b[$global_cmp_key]) ? -1 : 1;
	}
}

if ( ! function_exists('global_rcmp'))
{
	function global_rcmp($a, $b) {
	    global $global_cmp_key;
	    
	    if(!isset($a[$global_cmp_key]))
	    	return 0;
	    
	    if ($a[$global_cmp_key] == $b[$global_cmp_key]) {
	        return 0;
	    }
	    return ($a[$global_cmp_key] > $b[$global_cmp_key]) ? -1 : 1;
	}
}


if ( ! function_exists('generateRandStr'))
{
	function generateRandStr($length){
		  $randstr = "";
		  for($i=0; $i<$length; $i++){
			 $randnum = mt_rand(0,61);
			 if($randnum < 10){
				$randstr .= chr($randnum+48);
			 }else if($randnum < 36){
				$randstr .= chr($randnum+55);
			 }else{
				$randstr .= chr($randnum+61);
			 }
		  }
		  return $randstr;
	}
}

if ( ! function_exists('generateRandID'))
{
	function generateRandID(){
      return md5(generateRandStr(16));
   }
}

function nicetime($date)
{
	date_default_timezone_set('UTC');
    if(empty($date)) {
        return "No date provided";
    }
    
    $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths         = array("60","60","24","7","4.35","12","10");
    
    $now             = time();
    $unix_date       = strtotime($date);
    
       // check validity of date
    if(empty($unix_date)) {    
        return "Bad date";
    }

    // is it future date or past date
    if($now > $unix_date) {    
        $difference     = $now - $unix_date;
        $tense         = "ago";
        
    } else {
        $difference     = $unix_date - $now;
        $tense         = "from now";
    }
    
    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
    
    $difference = round($difference);
    
    if($difference != 1) {
        $periods[$j].= "s";
    }
    
    return "$difference $periods[$j] {$tense}";
}


function nicetime_inmin($difference)
{
	date_default_timezone_set('UTC');
    if(empty($difference) or $difference==0) {
        return "just now";
    }
    
    $periods         = array("minute", "hour", "day", "week", "month", "year", "decade");
    $lengths         = array("60","24","7","4.35","12","10");
    
    // is it future date or past date
	$tense         = "ago";
        
    
    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
    
    $difference = round($difference);
    
    if($difference != 1) {
        $periods[$j].= "s";
    }
    
    return "$difference $periods[$j] {$tense}";
}

function makelink($text) 
{
	return preg_replace('/(http\:\/\/[a-zA-Z0-9_\-\.]*?) /i', '<a href="$1">$1</a> ', $text." "); 
} 
?>