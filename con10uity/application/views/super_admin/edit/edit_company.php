<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Panel</title>
<script> 
			
			
function form_validation(thisform) {if (thisform.phone.value == '') {
									alert ('Phone is required');
									thisform.phone.focus();
									return false;
									}
									
									return true; } </script>
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
          <!-- Divider -->
          <span class="divider">/</span> 
          <a href="/super_admin/company/main/"> View All company </a>
          <span class="divider">/</span> 
          <a href="#" class="bread-current">company</a>
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
	          	                  <div class="pull-left">Edit company</div>
	          	                  <div class="widget-icons pull-right">
	          	                    <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	          	                    <a href="#" class="wclose"><i class="icon-remove"></i></a>
	          	                  </div>
	          	                  <div class="clearfix"></div>
	          	                </div>
	          	
	          	                <div class="widget-content">
	          	                  <div class="padd">
	          	
	          	                    <!-- Form starts.  -->
	          	                     <form class='form-horizontal' name='frm_edit_company' method='post' action='/super_admin/company/do_update/<?=$item['company_id']?>' onSubmit='return form_validation(this);' enctype='multipart/form-data'>
	          	                     <table class="table table-bordered table-striped table-hover content" style="width:100%;" cellpadding="0" cellspacing="0">
	          	                     <input type='hidden' id='chaabee_post' name='chaabee_post' value=''>
	          	                     <input type='hidden' id='company_id' name='company_id' value='<?=$item['company_id']?>'>
	          	                     <tr>
	          	                     	<td width='35%' class='capitalize' valign='top' align='left'>Company (Unique):</td>
	          	                     	<td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='username'  value='<?=$item['username']?>'  /></td>
	          	                     </tr>
	          	                     
									
									<tr>
										<td width='35%' class='capitalize' valign='top' align='left'>Phone:</td>
										<td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='phone'  value='<?=$item['phone']?>'  /></td>
									</tr>
									<tr>
										<td width='35%' class='capitalize' valign='top' align='left'>Address:</td>
										<td width='65%' class='capitalize'>
										<textarea  class='form-control' name='address'><?=$item['address']?></textarea></td>
										</tr>
									<tr>
										<td width='35%' class='capitalize' valign='top' align='left'>Web:</td>
										<td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='web'  value='<?=$item['web']?>'  /></td></tr><tr>
										<td width='35%' class='capitalize' valign='top' align='left'>Main contact:</td>
										<td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='main_contact'  value='<?=$item['main_contact']?>'  /></td>
									</tr>
									<tr>
										<td width='35%' class='capitalize' valign='top' align='left'>Notes:</td>
										<td width='65%' class='capitalize'><textarea  class='form-control'  rows=5 cols=35 name='notes' id='notes'><?=$item['notes']?></textarea></td>
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