<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Add New Threat</title>
<script> 
			
function form_validation(thisform) {

	if (thisform.name.value == '') {
		alert ('Name is required');
		thisform.name.focus();
		return false;
	}
	
	if (thisform.group.value == '') {
		alert ('Group is required');
		thisform.group.focus();
		return false;
	}
							
	return true; 
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
          <!-- Divider -->
          <span class="divider">/</span> 
          <a href="/super_admin/threats/main/"> View All threats </a>
          <span class="divider">/</span> 
          <a href="#" class="bread-current">threats</a>
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
	          	                  <div class="pull-left">Add New threats</div>
	          	                  <div class="widget-icons pull-right">
	          	                    <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	          	                    <a href="#" class="wclose"><i class="icon-remove"></i></a>
	          	                  </div>
	          	                  <div class="clearfix"></div>
	          	                </div>
	          	
	          	                <div class="widget-content">
	          	                  <div class="padd">
	          	
	          	                    <!-- Form starts.  -->
	          	                     <form class='form-horizontal' name='frm_add_threats' method='post' action='/super_admin/threats/do_add/' onSubmit='return form_validation(this);' enctype='multipart/form-data'>
		          	                     <table class="table table-bordered table-striped table-hover content" style="width:100%;" cellpadding="0" cellspacing="0">
			          	                     <input type='hidden' id='chaabee_post' name='chaabee_post' value=''>
			          	                     <input type='hidden' id='threat_id' name='threat_id' value=''>
			          	                     <input type='hidden' id='threat_id' name='company_id' value='0'>
			          	                     <tr>
												<td width='35%' class='capitalize' valign='top' align='left'>Name:</td>
												<td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='name'  value=''  /></td>
											</tr>
											<tr>
												<td width='35%' class='capitalize' valign='top' align='left'>Group:</td>
												<td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 id="group" name='group'  value=''  />
												<div style="padding-top: 5px;">
												<?if(is_array($groups) and count($groups)>0)
												{
													$gr = array();
													foreach ($groups as $key => $value) {
														$gr[] = '<a href="javascript:;" name="'.$value['group'].'" onclick="$(\'#group\').val($(this).attr(\'name\'));">'.$value['group'].'</a>';
													}
													
													echo implode(', ', $gr);
												}
												?>
												</div>
												</td>
											</tr>
											<tr>
												<td class='capitalize' width='35%'>&nbsp;</td>
												<td width='65%' class='capitalize'><input name='Submit' id='Submit' class="btn btn-danger" type='submit' value='Add' /></td>
											</tr>
										</table>
									</form>
	          	                     
	          	                     
	          	                  </div>
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