<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Panel</title>
<script> 
			
			
function form_validation(thisform) {
	if (thisform.name.value == '') {
		alert ('Name is required');
		thisform.name.focus();
		return false;
		}
	
	return true; 
} 
</script>
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
          <a href="/super_admin/"><i class="icon-home"></i> Home</a> 
          <!-- Divider -->
          <span class="divider">/</span> 
          <a href="/admin/risk_assessment/main/"> View All Risk Assessments </a>
          <span class="divider">/</span> 
          <a href="#" class="bread-current">Edit</a>
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
	          	                  <div class="pull-left">Edit Details</div>
	          	                  <div class="widget-icons pull-right">
	          	                    
	          	                    <!--<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	          	                    <a href="#" class="wclose"><i class="icon-remove"></i></a>-->
	          	                  </div>
	          	                  <div class="clearfix"></div>
	          	                </div>
	          	
	          	                <div class="widget-content">
	          	                  <div class="padd"><!-- Form starts -->
	          	                     <form class='form-horizontal' name='frm_edit_risk_assessment' method='post' action='/admin/risk_assessment/do_update/<?=$item['risk_assessment_id']?>' onSubmit='return form_validation(this);' enctype='multipart/form-data'>
		          	                     <table class="table table-bordered table-striped table-hover content" style="width:100%;" cellpadding="0" cellspacing="0">
		          	                     	<input type='hidden' id='chaabee_post' name='chaabee_post' value=''>
		          	                     	<input type='hidden' id='risk_assessment_id' name='risk_assessment_id' value='<?=$item['risk_assessment_id']?>'>
			          	                    <tr>
												<td width='35%' class='capitalize' valign='top' align='left'>Name:</td>
												<td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='name'  value='<?=$item['name']?>'  /></td>
											</tr>
											<tr>
												<td width='35%' class='capitalize' valign='top' align='left'>Description:</td>
												<td width='65%' class='capitalize'><textarea  class='form-control'  rows=5 cols=35 name='description' id='description'><?=$item['description']?></textarea></td>
											</tr>
											<tr>
												<td width='35%' class='capitalize'  valign='top' align='left'>Status:</td>
												<td width='65%' class='capitalize'><input name="status" type="radio" id="rstatus" value="In Progress" <? if(isset($item)){ if($item['status']=='In Progress')echo 'checked="checked"'; } ?> />&nbsp;In Progress&nbsp;<input name="status" type="radio" id="rstatus" value="Approved" <? if(isset($item)){ if($item['status']=='Approved')echo 'checked="checked"'; } ?> />&nbsp;Approved&nbsp;</td>
											</tr>
											<tr>
												<td class='capitalize' width='35%'>&nbsp;</td>
												<td width='65%' class='capitalize'>
													<input name='Submit' id='Submit' class="btn btn-danger" type='submit' value='Update' />
												&nbsp;<a href="/admin/threat_analysis/main/<?=$item['risk_assessment_id']?>/" class="btn btn-success">Manage Threats</a>
												</td>
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