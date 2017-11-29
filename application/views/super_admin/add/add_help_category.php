<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Panel</title>
<script> 
			
			
function form_validation(thisform) {var re5digit=/^\d*$/ //regular expression defining a 5 digit number
							if (thisform.parent_id.value.search(re5digit)==-1) //if match failed
							{
							alert('Please enter a valid digit number inside forms');
							thisform.parent_id.focus();
							return false;
							
							}
							if (thisform.title.value == '') {
									alert ('Title is required');
									thisform.title.focus();
									return false;
									}return true; } </script>
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
          <a href="/super_admin/help_category/main/"> View All Categories </a>
          <span class="divider">/</span> 
          <a href="#" class="bread-current">Add</a>
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
	          	                  <div class="pull-left">Add New Category</div>
	          	                  <div class="widget-icons pull-right">
	          	                    <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	          	                    <a href="#" class="wclose"><i class="icon-remove"></i></a>
	          	                  </div>
	          	                  <div class="clearfix"></div>
	          	                </div>
	          	
	          	                <div class="widget-content">
	          	                  <div class="padd">
	          	
	          	                    <!-- Form starts.  -->
	          	                     <form class='form-horizontal' name='frm_add_help_category' method='post' action='/super_admin/help_category/do_add/' onSubmit='return form_validation(this);' enctype='multipart/form-data'>
	          	                     <table class="table table-bordered table-striped table-hover content" style="width:100%;" cellpadding="0" cellspacing="0">
	          	                     	<input type='hidden' id='chaabee_post' name='chaabee_post' value=''>
	          	                     	<input type='hidden' id='id' name='id' value='<?=$item['id']?>'>
	          	                     	<tr>
										<td width='35%' class='capitalize' valign='top' align='left'>Category:</td>
										<td width='65%' class='capitalize'>
											<select class="form-control" name="parent_id" id="parent_id">
												<option value="0">Root</option>
												<? /*if(is_array($categories)){ foreach($categories as $cat){ ?>
													<option value="<?=$cat['id']?>"><?=$cat['title']?></option>
												<? }}*/ ?>
												<? printTree($tree); ?>
											</select>
										</td>
										</tr>
										<tr>
											<td width='35%' class='capitalize' valign='top' align='left'>Title:</td>
											<td width='65%' class='capitalize'>
												<input  class='form-control ' type='text' size=40 name='title'  value='<?=$item['title']?>'  />
											</td>
										</tr>
										<tr>
											<td class='capitalize' width='35%'>&nbsp;</td>
											<td width='65%' class='capitalize'><input name='Submit' id='Submit' class="btn btn-danger" type='submit' value='Submit' /></td>
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