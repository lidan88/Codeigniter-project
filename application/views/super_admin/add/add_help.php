<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Panel</title>

<!--<script src="/ckeditor/ckeditor.js"></script>-->
<script src="//cdn.ckeditor.com/4.4.4/full/ckeditor.js"></script>

<script> 
			
			
function form_validation(thisform) {if (thisform.title.value == '') {
									alert ('Title is required');
									thisform.title.focus();
									return false;
									}if (thisform.tags.value == '') {
									alert ('Tags is required');
									thisform.tags.focus();
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
          <a href="/super_admin/help/main/"> View All </a>
          <span class="divider">/</span> 
          <a href="#" class="bread-current">Help</a>
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
	          	                  <div class="pull-left">Add New Q &amp; A</div>
	          	                  <div class="widget-icons pull-right">
	          	                    <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	          	                    <a href="#" class="wclose"><i class="icon-remove"></i></a>
	          	                  </div>
	          	                  <div class="clearfix"></div>
	          	                </div>
	          	
	          	                <div class="widget-content">
	          	                  <div class="padd">
	          	
	          	                    <!-- Form starts.  -->
	          	                     <form class='form-horizontal' name='frm_add_help' method='post' action='/super_admin/help/do_add/' onSubmit='return form_validation(this);' enctype='multipart/form-data'>
	          	                     <table class="table table-bordered table-striped table-hover content" style="width:100%;" cellpadding="0" cellspacing="0">
	          	                     <input type='hidden' id='chaabee_post' name='chaabee_post' value=''>
	          	                     <input type='hidden' id='help_id' name='help_id' value='<?=$item['help_id']?>'>
	          	                     <tr>
										<td width='25%' class='capitalize' valign='top' align='left'>Category :</td>
										<td width='75%' class='capitalize'>
										<select class='form-control' name='help_category_id' id='help_category_id'><?
											printTree($categories); 
										?></select></td></tr><tr>
										<td class='capitalize' valign='top' align='left'>Question:</td>
										<td class='capitalize'><input  class='form-control ' type='text' size=40 name='title'  value='<?=$item['title']?>'  /></td></tr><tr>
										<td class='capitalize' valign='top' align='left'>Tags:</td>
										<td class='capitalize'><input  class='form-control ' type='text' size=40 name='tags'  value='<?=$item['tags']?>'  /></td></tr><tr>
										<td class='capitalize' valign='top' align='left'>Answer:</td>
										<td class='capitalize'><textarea  class='ckeditor' name='description' id='description'><?=$item['description']?></textarea></td>
										</tr>
										<tr>
											<td class='capitalize' width='35%'>&nbsp;</td>
											<td width='65%' class='capitalize'>
												<input name='Submit' id='Submit' class="btn btn-danger" type='submit' value='Submit' />&nbsp;
												<input type="button" name="" value="Cancel" class="btn btn-default" onclick="history.go(-1);" />
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

<script type="text/javascript">
<? if($item['help_category_id']!=""){ ?>
$(document).ready(function(){
	$('#help_category_id').val(<?=$item['help_category_id']?>);
});	
<? } ?>
</script>

</body>
</html>