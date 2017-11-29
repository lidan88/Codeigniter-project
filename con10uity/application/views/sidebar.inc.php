<?
$user_info = $this->session->userdata("user_info");
$this->company_id = $user_info['company_id'];
$this->user_id = $user_info['user_id'];
//$this->load->library("permission_lib");
//die("asd");
//$has_permission = $this->permission_lib->has_permission("users","edit");
//echo $has_permission==true?'TRUE':"FALSE";

$CI = &get_instance();

$result = $this->db->query("select * from library where is_system=0 and is_visible=1 and company_id=". $this->company_id); 
$rows = array();

$library_items = '';
$root_items = '';
$library_menu = false;
if ($result->num_rows() > 0)
{
	
   foreach ($result->result_array() as $row)
   {
   		$libid = "library-".str_replace(' ', '-',strtolower($row['name']));
   		if(!$CI->permission_lib->has_permission($libid))
   			continue;
   			
   		$openClass = '';
   			
   		if($row['parent_id']==1)
   		{
	   		if(isset($inside_library) and $selected_library_id==$row['id'])
	   		{
	   			$library_menu=true;
	   			$openClass = ' class="open"';
	   		}	
	   	
	   		$library_items .= '<li><a '.$openClass.' href="/admin/library/table_view/'.$row['id'].'/">'.$row['name'].'</a></li>';
   		}
   		else
   		{
   			if(isset($inside_library) and $selected_library_id==$row['id'])
   			{
   				$openClass = ' class="open"';
   			}	
   		
   			$root_items .= '<li><a '.$openClass.' href="/admin/library/table_view/'.$row['id'].'/">'.$row['name'].'</a></li>';
   		}
   }
}
?>

<div class="sidebar">
	<div class="sidebar-dropdown"><a href="#">Navigation</a></div>

		<!--- Sidebar navigation -->
		<!-- If the main navigation has sub navigation, then add the class "has_sub" to "li" of main navigation. -->
		<ul id="nav">
		<li class="sidebar-search">
				<div class="input-group custom-search-form">
					<input type="text" class="form-control" placeholder="Search...">
					<span class="input-group-btn">
						<i class="icon-search" style='color:#000;'></i>
					</span>
				</div>
				<!-- /input-group -->
          </li>	
		<!-- Main menu with font awesome icon -->
		<li><a href="/"><!--i class="icon-home"></i!--> Dashboard</a></li>
		<li class="has_sub">
			<a href="/admin/library/listit/" <?=($this->router->class=='library' and $library_menu==true)?'class="open subdrop"':'' ?>> Library <span class="icon-chevron-right pull-right"></span></a>
			<ul style="<?=($this->router->class=='library')?'display: block;':'';?>">
			 	<?=$library_items?>		  
			</ul>
		</li> 
		<?=$root_items?>
		<? if($CI->permission_lib->has_permission("library-bia")){ ?>
			<li><a href="/admin/bia/main/" <?=($this->router->class=='bia')?'class="open"':''?>><!--i class="icon-bar-chart"></i!--> Business Impact Analysis</a></li> 
		<? } ?>
		
		<? if($CI->permission_lib->has_permission("library-plans")){ ?>
			<li><a href="/admin/plans/main/" <?=($this->router->class=='plans')?'class="open"':''?>><!--i class="icon-bar-chart"></i!--> Plans</a></li> 
		<? } ?>
		
		<? if($CI->permission_lib->has_permission("library-risk-assessment")){ ?>
			<li><a href="/admin/risk_assessment/main/" <?=($this->router->class=='risk_assessment')?'class="open"':''?>><!--i class="icon-bar-chart"></i!--> Risk Assessment</a></li> 
		<? } ?>
		
		<? if($CI->permission_lib->has_permission("call-chain")){ ?>
			<li><a href="/admin/call_chain/main/" <?=($this->router->class=='call_chain')?'class="open"':''?>><!--i class="icon-bar-chart"></i!--> Call Chain</a></li> 
		<? } ?>
		<!--<li><a href="/admin/dependencies/main/" <?=($this->router->class=='dependencies')?'class="open"':''?>><i class="icon-bar-chart"></i> Dependencies</a></li> -->
		
		<? if($CI->permission_lib->has_permission("reports")){ ?>
			<li><a href="/admin/reports/main/" <?=($this->router->class=='reports')?'class="open"':''?>><!--i class="icon-bar-chart"></i!--> Reports</a></li> 
		<? } ?>
		
		
		<? if($CI->permission_lib->has_permission("administration")){ ?>
		<li class="has_sub"><a href="#"><!--i class="icon-list-alt"></i!--> Administration  <span class="icon-chevron-right pull-right"></span></a>
		<ul>
		  <? if($CI->permission_lib->has_permission("company-settings")){ ?>
		  	<li><a href="/admin/company/settings/">Company Settings</a></li>
		  <? } ?>
		  <? if($CI->permission_lib->has_permission("user-setup")){ ?>
		  	<li><a href="/admin/users/main/">User Setup</a></li>
		  <? } ?>
		  <? if($CI->permission_lib->has_permission("import-csv")){ ?>
		  	<li><a href="/admin/import/main/">Import</a></li>
		  <? } ?>
		  <? if($CI->permission_lib->has_permission("library-items")){ ?>
		  	<li><a href="/admin/library/main/">Library Management</a></li>
		  <? } ?>
		  <? if($CI->permission_lib->has_permission("library-templates")){ ?>
		  <li><a href="/admin/library_template/main/">Library Templates</a></li>
		  <? } ?>
		  <? if($CI->permission_lib->has_permission("roles-permissions")){ ?>
		  <li><a href="/admin/role/main/">Roles &amp; Permissions</a></li>
		  <? } ?>
		  <? if($CI->permission_lib->has_permission("dropdowns")){ ?>
		  <li><a href="/admin/dropdown/main/">Drop Downs</a></li>
		  <? } ?>
		</ul>
		</li>
		<? } ?>
		
		<? if($CI->permission_lib->has_permission("approvals")){ ?>
		<li class="has_sub">
			<a href="#">Approvals <span class="icon-chevron-right pull-right"></span></a> 
			<ul>
				<li><a href="/admin/risk_assessment/main/?status=Pending Approval">Risk Assessment</a></li>
				<li><a href="/admin/plans/main/?status=Pending Approval">Plans</a></li>
				<li><a href="/admin/bia/main/?status=Pending Approval">Business Impact Analysis</a></li>
			</ul>
		</li>  
		<? } ?>      
		
		<li><a href="/admin/help">Help Center</a></li>
		</ul>
</div>



<!-- style="<?=($this->router->class=='library')?'display: block;':'';?>" -->

<script type="text/javascript">
$(document).ready(function(){
	
});
</script>