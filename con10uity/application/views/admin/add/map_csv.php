<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Panel</title>
<!--HEAD-->
<? include(APPPATH."views/admin/head.inc.php"); ?>
<!-- /HEAD -->
<script> 
			
function form_validation(thisform) {if (thisform.name.value == '') {
									alert ('Name is required');
									thisform.name.focus();
									return false;
									}return true; }
									

$(document).ready(function(){
	var dp = $('.datepicker').datepicker().on('changeDate', function(ev) {
	  dp.datepicker('hide');
	});
	$('.timepicker').timepicker();
	$(".chosen-select").chosen();
});									
</script>
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
          <!-- Divider -->
          <span class="divider">/</span> 
          <a href="/admin/import/main/"> Import CSV </a>
          
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
	          	                  <div class="pull-left">Step 2: Map Uploaded CSV</div>
	          	                  <!--<div class="widget-icons pull-right">
	          	                    <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	          	                    <a href="#" class="wclose"><i class="icon-remove"></i></a>
	          	                  </div>-->
	          	                  <div class="clearfix"></div>
	          	                </div>
	          	
	          	                <div class="widget-content">
	          	                  <div class="padd">
	          	
	          	                    <!-- Form starts.  -->
	          	                    <form class='form-horizontal' name='frm_add_role' method='post' action='/admin/import/map_now/'>
	          	                    <input type="hidden" name="library_id" value="<?=$library_id?>" />
	          	                    <input type="hidden" name="full_path" value="<?=$full_path?>" />
	          	                    <table class="table table-bordered table-striped table-hover content" style="width:100%;" cellpadding="0" cellspacing="0">
	          	                    	<tr>
	          	                    		<td>ID:</td>
	          	                    		<td>
	          	                    			<select name="unique_id">
	          	                    				<option value="">- Leave Empty -</option>
	          	                    				<? foreach ($csv_keys as $ckey => $cvalue) { 
	          	                    					$selected='';
	          	                    					if($cvalue=='ID')
	          	                    						$selected='selected="selected"';
	          	                    					echo '<option value="'.$cvalue.'"'.$selected.'>'.$cvalue.'</option>';
	          	                    				 } ?>
	          	                    			</select>
	          	                    		</td>
	          	                    	</tr>
	          	                    	<? foreach($library_keys as $lname =>$lkey){ ?>
	          	                    	<tr>
	          	                    		<td width="10%" style="10%"><?=$lname?>:</td>
	          	                    		<td>
	          	                    			<select name="map[<?=$lkey?>]">
	          	                    				<option value="">- Leave Empty -</option>
	          	                    				<? foreach ($csv_keys as $ckey => $cvalue) { 
	          	                    					$selected='';
	          	                    					if($lname==$cvalue)
	          	                    						$selected='selected="selected"';
	          	                    					echo '<option value="'.$cvalue.'"'.$selected.'>'.$cvalue.'</option>';
	          	                    				 } ?>
	          	                    			</select>
	          	                    		</td>
	          	                 		</tr>
	          	                 		<? } ?>
										<tr>
											<td class='capitalize'>&nbsp;</td>
											<td class='capitalize'>
												<input name='Submit' id='Submit' class="btn btn-danger" type='submit' value='Upload' />
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