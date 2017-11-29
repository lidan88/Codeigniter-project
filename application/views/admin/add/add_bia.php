<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Panel</title>
<!--HEAD-->
	<? include(APPPATH."views/admin/head.inc.php"); ?>
<!-- /HEAD -->

<script> 
			
			
function form_validation(thisform) {
	if (thisform.name.value == '') {
		alert ('Name is required');
		thisform.name.focus();
		return false;
	}
	
	if (thisform.conducted_by.value == '') {
		alert ('Conducted by is required');
		thisform.conducted_by.focus();
		return false;
	}
	
	if (thisform.category.value == '') {
		alert ('Category is required');
		thisform.category.focus();
		return false;
	}

return true; 
}

function submit_for_approval(obj)
{
	$.post("/admin/bia/submit_for_approval/<?=$item['bia_id']?>/",{},function(ret){
		//console.log(ret);
		$(obj).html("Submitted").attr("disabled","disabled");
	});
}

function approve(obj)
{
	$.post("/admin/bia/approve/<?=$item['bia_id']?>/",{},function(ret){
		$(obj).html("Approved").attr("disabled","disabled");
	});
}

function reject(obj)
{
	$.post("/admin/bia/reject/<?=$item['bia_id']?>/",{},function(ret){
		$(obj).html("Rejected").attr("disabled","disabled");
	});
}

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
          <a href="/super_admin/"><i class="icon-home"></i> Home</a> 
          <!-- Divider -->
          <span class="divider">/</span> 
          <a href="/admin/bia/main/"> View All BIA's </a>
          <span class="divider">/</span> 
          <a href="#" class="bread-current">New Business Impact Analysis</a>
        </div>

        <div class="clearfix"></div>

      </div>
      <!-- Page heading ends -->

	    <!-- Matter -->
	    <div class="matter">
	        <div class="container">
	
					            
	           <form class='form-horizontal' name='frm_add_bia' method='post' action='/admin/bia/do_add/' onSubmit='return form_validation(this);' enctype='multipart/form-data'>
	          	<input type="hidden" name="library_value_id" value="<?=$item['library_value_id']?>" />
	          	<input type='hidden' id='bia_id' name='bia_id' value='<?=$item['bia_id']?>'>
	          	<input type="hidden" name="status" value="<?=$item['status']?>" />
	          	<div class="row">
	          	
	          	            <div class="col-md-12">
	          	
	          	
	          	              <div class="widget wgreen">
	          	                
	          	                <div class="widget-head">
	          	                  <div class="pull-left">New Business Impact Analysis</div>
	          	                  <div class="pull-right">
	          	                    <!--<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	          	                    <a href="#" class="wclose"><i class="icon-remove"></i></a>-->
	          	                    
	          	                    <? if($item['status']=='In Progress'){ ?>
	          	                    <a href="javascript:;" onclick="submit_for_approval(this);" class="btn btn-mini btn-danger">Submit For Approval</a>
	          	                    <? }else if($item['status']=='Pending Approval'){ ?>
	          	                    	<a href="javascript:;" onclick="approve(this);" class="btn btn-mini btn-success">Approve</a>
	          	                    	<a href="javascript:;" onclick="reject(this);" class="btn btn-mini btn-danger">Reject</a>
	          	                    <? } ?>
	          	                    
	          	                    
	          	                  </div>
	          	                  <div class="clearfix"></div>
	          	                </div>
	          	
	          	                <!--<div class="widget-content">
	          	                  <div class="padd">
	          	                     <table class="table table-bordered table-striped table-hover content" style="width:100%;" cellpadding="0" cellspacing="0">
	          	                     	<tr>
											<td width='35%' class='capitalize'  valign='top' align='left'>Status:</td>
											<td width='65%' class='capitalize'>In Progress</td>
										</tr>
									
									</table>
	          	                     
	          	                     
	          	                  </div>
	          	                </div>-->
	          	                
                                <div class="widget-content">
                                  	<div class="padd">
                                  			<table class="table table-striped table-bordered table-hover">
		                						<tbody>
		                						<?        
		                							if(is_array($list) and count($list)>0)
		                							{
		                							    foreach($list as $item_name => $row)
		                							    {
		                							    	?>
		                							    	<tr>
		                							    		<td><strong><?=$item_name?></strong>:<br />
		                							    		<?=$row['help']?></td>
		                							    		<td><?=$row['render']?></td>
		                							    	</tr>
		                							    	<?
		                							    }
		                							}else
		                							{
		                							?>
		                							    <tr>
		                							        <td>
		                							            <div class='b1 txt padd5'><strong>No items found!</strong></div>
		                							        </td>
		                							    </tr>
		                							<?
		                							}
		                								?>       	            
		                						    <tr>
		                						    	<td class='capitalize' width='35%'>&nbsp;</td>
		                						    	<td width='65%' class='capitalize'><input name='Submit' id='Submit' class="btn btn-danger" type='submit' value='Submit' /></td>
		                						    </tr>
		                						    </tbody>
	                						    </table>
                					</div>
                				</div>
	          	                
	          	                
	          	                  <div class="widget-foot">
	          	                    <!-- Footer goes here -->
	          	                    <div class="pull-left"></div>
	          	                    
	          	                    <div class="clearfix"></div>
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