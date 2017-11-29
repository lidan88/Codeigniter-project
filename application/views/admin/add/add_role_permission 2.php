<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Panel</title>
<script> 
			
			
function form_validation(thisform) {return true; } </script>
<!--HEAD-->
	<? include(APPPATH."views/admin/head.inc.php"); ?>
<!-- /HEAD -->
</head>
<body>
<!--HEADER-->
	<? include(APPPATH."views/admin/header.inc.php"); ?>
<!-- /HEADER-->

<!-- /container -->
<div class="content">

	<!-- Sidebar -->
	<? include(APPPATH."views/admin/sidebar.inc.php"); ?>
    <!-- Sidebar ends -->

  	<!-- Main bar -->
  	<div class="mainbar">

      <!-- Page heading -->
      <div class="page-head">
        <!-- Breadcrumb -->
        <div class="bread-crumb pull-left">
          <a href="/admin/"><i class="icon-home"></i> Home</a> 
          <span class="divider">/</span> 
          <a href="/admin/role/main/<?=$role_id?>/"> Roles </a>
          <span class="divider">/</span> 
          <a href="/admin/role/main/<?=$role_id?>/"> <?=$role_details['name']?> </a>
          <span class="divider">/</span> 
          <a href="#" class="bread-current">Update Permissions</a>
        </div>

        <div class="clearfix"></div>

      </div>
      <!-- Page heading ends -->

	    <!-- Matter -->
	    <div class="matter">
	        <div class="container">
	
					            
	          
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
	          	                  <div class="padd">
	          	
	          	                    <!-- Form starts.  -->
	          	                     <form class='form-horizontal' name='frm_add_role_permission' method='post' action='/admin/role_permission/do_add/' onSubmit='return form_validation(this);' enctype='multipart/form-data'>
	          	                     <table class="table table-bordered table-striped table-hover content" style="width:100%;" cellpadding="0" cellspacing="0">
	          	                     <input type='hidden' id='chaabee_post' name='chaabee_post' value=''>
	          	                     <input type='hidden' id='role_id' name='role_id' value='<?=$role_id?>'>
	          	                     <?
	          	                     	foreach($permissions as $group_name => $group)
	          	                     	{
	          	                    		?>
	          	                    		<tr>
	          	                    			<td colspan="2"><?=ucwords(str_replace('_', ' ', $group_name))?></td>
	          	                    		</tr>
	          	                    		<?
	          	                    		foreach($group as $p)
	          	                    		{
	          	                    	
	          	                     ?>
	          	                     <tr>
	          	                     	<td width="20px">
	          	                     		<input type="checkbox" id="chk_<?=$p['permission_id']?>" name="permissions[<?=$p['permission_id']?>]" value="<?=$p['permission_id']?>" <?=in_array($p['permission_id'],$current_permissions)?'checked="checked"':''?> />
	          	                     		</td>
	          	                     	<td><label for="chk_<?=$p['permission_id']?>"><?=$p['name']?></label></td>
	          	                     </tr>
	          	                     <?		}
	          	                     } ?>
	          	                     
	          	                    <tr><td class='capitalize'>&nbsp;</td>
<td class='capitalize'><input name='Submit' id='Submit' class="btn btn-danger" type='submit' value='Update' /></td></tr></table></form>
	          	                     
	          	                     
	          	                  </div>
	          	                </div>
	          	                  <div class="widget-foot">
	          	                    <!-- Footer goes here -->
	          	                  </div>
	          	              </div>  
	          	
	          	            </div>
	          	
	          	          </div>
	         
	
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