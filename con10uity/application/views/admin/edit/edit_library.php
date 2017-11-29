<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Panel</title>
<script> 
			
			
function form_validation(thisform) {if (thisform.name.value == '') {
									alert ('Name is required');
									thisform.name.focus();
									return false;
									}if (thisform.icon.value == '') {
									alert ('Icon is required');
									thisform.icon.focus();
									return false;
									}return true; } </script>
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
	<? include(APPPATH."views/sidebar.inc.php"); ?>
    <!-- Sidebar ends -->

  	<!-- Main bar -->
  	<div class="mainbar">

      <!-- Page heading -->
      <div class="page-head">
        <!-- Breadcrumb -->
        <div class="bread-crumb pull-left">
          <a href="/super_admin/"><i class="icon-home"></i> Home</a> 
          <!-- Divider -->
          <span class="divider">/</span> 
          <a href="/admin/library/main/"> View All library </a>
          <span class="divider">/</span> 
          <a href="#" class="bread-current">library</a>
        </div>

        <div class="clearfix"></div>

      </div>
      <!-- Page heading ends -->

	    <!-- Matter -->
	    <div class="matter">
	        <div class="container">
	          	<div class="row">
	          	            <div class="col-md-12">
	          	              <div class="widget wgreen">
	          	                <div class="widget-head">
	          	                  <div class="pull-left">Edit library</div>
	          	                  <div class="widget-icons pull-right">
	          	                    <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	          	                    <a href="#" class="wclose"><i class="icon-remove"></i></a>
	          	                  </div>
	          	                  <div class="clearfix"></div>
	          	                </div>
	          	
	          	                <div class="widget-content">
	          	                  <div class="padd">
	          	
	          	                    <!-- Form starts.  -->
	          	                     <form class='form-horizontal' name='frm_edit_library' method='post' action='/admin/library/do_update/<?=$item['id']?>' onSubmit='return form_validation(this);' enctype='multipart/form-data'>
	          	                     <table class="table table-bordered table-striped table-hover content" style="width:100%;" cellpadding="0" cellspacing="0"><input type='hidden' id='chaabee_post' name='chaabee_post' value=''>
	          	                     <input type='hidden' id='id' name='id' value='<?=$item['id']?>'>
	          	                     <tr>
										<td width='35%' class='capitalize' valign='top' align='left'>Name:</td>
										<td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='name'  value='<?=$item['name']?>'  /></td>
										</tr>
										<tr>
											<td width='35%' class='capitalize' valign='top' align='left'>Parent:</td>
											<td width='65%' class='capitalize'>
												<select id="parent_id" class="form-control" name="parent_id">
													<option value="1" <?=$item['parent_id']==1?'selected="selected"':''?>>Library</option>
													<option value="0"  <?=$item['parent_id']==0?'selected="selected"':''?>>Root</option>
												</select>
											</td>
										</tr>
										<tr>
	          	                     			<td width='35%' class='capitalize'  valign='top' align='left'>Logo:</td>
	          	                     			<td width='65%' class='capitalize'><input type="file" name="library_logo"  /></td>
	          	                     	</tr>
										<!--tr>
											<td width='35%' class='capitalize'  valign='top' align='left'>Is System:</td>
											<td width='65%' class='capitalize'><input name="is_system" type="radio" value="1" <?=$item['is_system']==1?'checked="checked"':''?> />&nbsp;Yes&nbsp;<input name="is_system" type="radio" id="rstatus" value="0" <?=$item['is_system']==0?'checked="checked"':''?> />&nbsp;No&nbsp;</td>
										</tr!-->
										<tr>
											<td width='35%' class='capitalize'  valign='top' align='left'>Is Visible:</td>
											<td width='65%' class='capitalize'><input name="is_visible" type="radio" value="1" <?=$item['is_visible']==1?'checked="checked"':''?> />&nbsp;Yes&nbsp;<input name="is_visible" type="radio" id="rstatus" value="0" <?=$item['is_visible']==0?'checked="checked"':''?> />&nbsp;No&nbsp;</td>
										</tr>
										<tr><td class='capitalize' width='35%'>&nbsp;</td>
										<td width='65%' class='capitalize'><input name='Submit' id='Submit' class="btn btn-danger" type='submit' value='Update' /></td>
										</tr>
									</table>
									</form>
	          	                     
	          	                     
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