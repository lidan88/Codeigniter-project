<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Panel</title>
<script> 
			
function form_validation(thisform) {return true; } 

function toggle_module(module_id,is_checked)
{
	
	if(is_checked)
	{
		//$("[type='checkbox']").attr("checked","checked");
		//$("[type='checkbox']").prop('checked', true);
		
		$(".module_"+module_id).attr("checked","checked");
		$(".module_"+module_id).prop('checked', true);
		
	}
	else {
		//$("[type='checkbox']").removeAttr("checked");
		//$("[type='checkbox']").prop('checked', false);
		
		$(".module_"+module_id).removeAttr("checked");
		$(".module_"+module_id).prop('checked', false);
		
	}
}
</script>
<!--HEAD-->
	<? include(APPPATH."views/super_admin/head.inc.php"); ?>
<!-- /HEAD -->
</head>
<body>
<!--HEADER-->
	<? include(APPPATH."views/super_admin/header.inc.php"); ?>
<!-- /HEADER-->

<!-- /container -->
<div class="content">

	<!-- Sidebar -->
	<? include(APPPATH."views/super_admin/sidebar.inc.php"); ?>
    <!-- Sidebar ends -->

  	<!-- Main bar -->
  	<div class="mainbar">

      <!-- Page heading -->
      <div class="page-head">
        <!-- Breadcrumb -->
        <div class="bread-crumb pull-left">
          <a href="/super_admin/"><i class="icon-home"></i> Home</a> 
          <span class="divider">/</span> 
          <a href="/super_admin/role/main/<?=$role_id?>/"> Roles </a>
          <span class="divider">/</span> 
          <a href="/super_admin/role/main/<?=$role_id?>/"> <?=$role_details['name']?> </a>
          <span class="divider">/</span> 
          <a href="#" class="bread-current">Update Permissions</a>
        </div>

        <div class="clearfix"></div>

      </div>
      <!-- Page heading ends -->

	    <!-- Matter -->
	    <div class="matter">
	            <div class="container">
	            <form class='form-horizontal' name='frm_add_role_permission' method='post' action='/super_admin/role_permission/do_add/' onSubmit='return form_validation(this);' enctype='multipart/form-data'>
	            
	            
	    
	    				            
	              
	              	<div class="row">
	              	
	              	            <div class="col-md-12">
	              	
	              				<? if(isset($_GET['updated'])){ ?>
	              				<div class="alert alert-success"><strong>Permissions Updated</strong><br /><a href="/admin/role/main/">click here</a> to go back to list of roles</div>
	              				<? } ?>
	              	
	              	              <div class="widget wgreen">
	              	                
	              	                <div class="widget-head">
	              	                  <div class="pull-left">Select Permissions for <?=$role_details['name']?></div>
	              	                  <div class="widget-icons pull-right">
	              	                    <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	              	                    <a href="#" class="wclose"><i class="icon-remove"></i></a>
	              	                  </div>
	              	                  <div class="clearfix"></div>
	              	                </div>
	              	
	              	                <div class="widget-content">
	              	                  <div>
	              	
	              	                    <!-- Form starts.  -->
	              	                     <table class="table table-bordered content" style="width:100%;" cellpadding="0" cellspacing="0">
	              	                     <input type='hidden' id='chaabee_post' name='chaabee_post' value=''>
	              	                     <input type='hidden' id='role_id' name='role_id' value='<?=$role_id?>'>
	              	                     <thead>
	              	                     <tr class="alert alert-info">
	              	                     	<th width="70%">Modules</th>
	              	                     	<th width="50px">Module</th>
	              	                     	<th width="50px">Add</th>
	              	                     	<th width="50px">Edit</th>
	              	                     	<th width="50px">Delete</th>
	              	                     	<th width="50px">Export</th>
	              	                     </tr>
	              	                     </thead>
	              	                     <tbody>
	              	                     <?
	              	                     	foreach($permissions as $group_name => $group)
	              	                     	{
	              	                    		?>
	              	                    		<tr class="alert alert-warning">
	              	                    			<td colspan="6" width="60%"><strong> <?=ucwords(str_replace('-',' ',str_replace('_', ' ', $group_name)))?></strong></td>
	              	                    			
	              	                    		</tr>
	              	                    		<?
	              	                    		foreach($group as $p)
	              	                    		{
	              	                    			if(!isset($role_permissions[$p['module_id']]))
	              	                    				$role_permissions[$p['module_id']]=0;
	              	                     ?>
	    		          	                     <tr>
	    		          	                     	<td>
	    		          	                     		<label for="chk_<?=$p['module_id']?>"><?=$p['title']?></label>
	    		          	                     	</td>
	    		          	                     	<td>
	    		          	                     		<input type="checkbox" class="module_<?=$p['module_id']?>" onclick="toggle_module(<?=$p['module_id']?>,this.checked);" id="chk_<?=$p['module_id']?>" name="permissions[<?=$p['module_id']?>]" value="<?=$p['module_id']?>" <?=in_array($p['module_id'],$modules_included)?'checked="checked"':''?> />
	    		          	                     	</td>
	    		          	                     	<td>
	    		          	                     		<input type="checkbox" class="module_<?=$p['module_id']?>" name="permissions[<?=$p['module_id']?>][0]" value="1" <?=(($role_permissions[$p['module_id']] & 1)==1)?'checked="checked"':''?> />
	    		          	                     	</td>
	    		          	                     	<td>
	    		          	                     		<input type="checkbox" class="module_<?=$p['module_id']?>" name="permissions[<?=$p['module_id']?>][1]" value="2" <?=(($role_permissions[$p['module_id']] & 2)==2)?'checked="checked"':''?> />
	    		          	                     	</td>
	    		          	                     	<td>
	    		          	                     		<input type="checkbox" class="module_<?=$p['module_id']?>" name="permissions[<?=$p['module_id']?>][2]" value="4" <?=(($role_permissions[$p['module_id']] & 4)==4)?'checked="checked"':''?> />
	    		          	                     	</td>
	    		          	                     	<td>
	    		          	                     		<input type="checkbox" class="module_<?=$p['module_id']?>" name="permissions[<?=$p['module_id']?>][3]" value="8" <?=(($role_permissions[$p['module_id']] & 8)==8)?'checked="checked"':''?> />
	    		          	                     	</td>
	    		          	                     </tr>
	              	                     <?		}
	              	                     } ?>
	              	                     
	              	                   
	    								</tbody>
	    								</table>
	    								
	              	                     
	              	                     
	              	                  </div>
	              	                </div>
	              	                  <div class="widget-foot">
	              	                    <!-- Footer goes here -->
	              	                    <table>
	              	                    	<tr>
	              	                    	  	<td class='capitalize'>&nbsp;</td>
	              	                    		<td colspan="5" class='capitalize'>
	              	                    			<input name='Submit' id='Submit' class="btn btn-danger" type='submit' value='Update' />
	              	                    		</td>
	              	                    	</tr>
	              	                    </table>
	              	                  </div>
	              	              </div>  
	              	
	              	            </div>
	              	
	              	          </div>
	            </form>
	            </div>
	    	 </div>
		<!-- Matter ends -->

    </div>

   <!-- Mainbar ends -->	    	
   <div class="clearfix"></div>

</div>
<!-- Content ends -->

<!-- Footer starts -->
<?  include(APPPATH."views/footer.inc.php"); ?>
<!-- Footer/Ends-->

</body>
</html>