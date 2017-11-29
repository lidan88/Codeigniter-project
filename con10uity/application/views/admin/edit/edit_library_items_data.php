<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Library items</title>

<!--HEAD-->
<? include(APPPATH."views/admin/head.inc.php"); ?>
<script>
function confirm_delete()
{
	if(confirm("Are you sure you want to delete?"))
	{
		//return true;
		$('#form_control').get(0).submit();
		$('#action').val('Delete');
	}
	else {
		return false;
	}
}

$(document).ready(function(){
	var dp = $('.datepicker').datepicker().on('changeDate', function(ev) {
	  dp.datepicker('hide');
	});
	$(".autogrow").autoGrow();
	$('.timepicker').timepicker();
	$(".chosen-select").chosen();
	
	$("#task_list").sortable({
		forcePlaceholderSize: true,
		handle: 'span',
		helper: 'default'
	}).bind('sortupdate', function(e, ui) {
	    //ui.item contains the current dragged element.
	    //Triggered when the user stopped sorting and the DOM position has changed.
		var sort_ctr=0;
		$("#task_list > .row").find('.task_priority').each(function(){
			$(this).val(sort_ctr++);
		});
		
		var sort_ctr=0;
		$("#task_list > .row").find('.num').each(function(){
			$(this).html(sort_ctr++);
		});
			//console.log(sort_order);
	}); //.disableSelection();
	
});



</script>
<style>
.sortable-dragging {
	border: 2px dashed green;
}

.sortable-placeholder{
	border-left: 3px solid brown;
}
</style>
</head>
<body>
<!--HEADER-->
<? include(APPPATH."views/admin/header.inc.php"); ?>


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
		  <a href="/admin/"><i class="icon-home"></i> Home</a> 
		  <span class="divider">/</span> 
		  <a href="/admin/library/main/">Library </a>
		  <span class="divider">/</span> 
		  <a href="#" class="bread-current"><?=$library_details['name']?> </a>
		</div>
		
		<div class="clearfix"></div>
		
		</div>
		<!-- Page heading ends -->
		
		<!-- Matter -->
		
		<div class="matter">
			<div class="container">
			
				<div class="row">
					
					<div class="col-md-12">
						<div class="widget">
					        <div class="widget-head">
			                  <div class="pull-left">Update <?=$library_details['name']?></div>
			                  <div class="widget-icons pull-right">
			                    <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
			                    <a href="#" class="wclose"><i class="icon-remove"></i></a>
			                  </div>  
			                  <div class="clearfix"></div>
			                </div>
			
			                <div class="widget-content">
			                	<form id="form_control" method="post" action="/admin/library_items/do_update_user_data/<?=$value_id?>/" enctype="multipart/form-data" onsubmit="return validate_form()">
			                		<input type="hidden" name="value_id" value="<?=$value_id?>" />
			                		<input type="hidden" name="company_id" value="<?=$library_details['company_id']?>" />
			                		<input type="hidden" name="library_id" value="<?=$library_details['id']?>" />
			                		<input type="hidden" name="action" id="action" value="" />
			                		<table class="table table-striped table-bordered table-hover">
			                	    <tbody>
			                	    <tr>
			                	    	<td width="10%">ID:</td>
			                	    	<td width="90%"><input type="text" class="form-control" name="unique_id" value="<?=$item['unique_id']?>" placeholder="Enter Unique ID" /></td>
			                	    </tr>
			                	    <?        
			                	    	if(is_array($list) and count($list)>0)
			                	    	{
			                	    	    foreach($list as $item_name => $row)
			                	    	    {
			                	    	    	?>
			                	    	    	<tr>
			                	    	    		<td><?=$item_name?></td>
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
			                	        	<td class='capitalize' width='10%'>&nbsp;</td>
			                	        	<td width='90%' class='capitalize'>
			                	        		<input name='Submit' id='Submit' class="btn btn-primary" type='submit' value='Save' />
			                	        		<input name='Submit' id='Submit' class="btn btn-primary" type='submit' value='Save and New' />
			                	        		
			                	        		<input name='Submit' id='Submit' class="btn btn-success" type='button' onclick="top.location='/admin/library_items/enter_user_data/<?=$library_details['id']?>/';" value='New' />
			                	        		<input name='Submit' id='Submit' class="btn btn-success" type='submit' value='Copy' />
			                	        		
			                	        		<input name='Submit' id='Submit' class="btn btn-danger" type='button' onclick="confirm_delete()" value='Delete' />
			                	        		
			                	        		<input name='Submit' id='Submit' class="btn btn-danger" type='button' onclick="history.go(-1);" value='Cancel' />
			                	        		
			                	        	</td>
			                	        </tr>
			                	        
			                	        </tbody>
			                	    </table>
			                	</form>
			                		
			                </div>
			                
			            </div>
			        </div>
			        <? /*<div class="col-md-2">
			        	<h1>Versions</h1>
			        	<br />
			        	<br />
			        	
			        	<a href="#">Version 1</a><br />
			        	Modified On 12 Apr 2014
			        </div>*/
			        ?>
			        
			    </div>
			    
			    <!--<div class="row">
			    	<div class="col-md-8">
			    	
			    		<a href="#">Version 1</a><br />
			    		Modified On 12 Apr 2014
			    	</div>
			    </div>-->
				
			
				
			
			</div>
		</div>
	</div>
	
	<div class="clearfix"></div>
</div>

<!-- Footer starts -->
<?  include(APPPATH."views/footer.inc.php"); ?>
<!-- Footer/Ends-->

<script type="text/javascript">
function validate_form()
{
<? 	if(is_array($list) and count($list)>0)
	{
    	foreach($list as $item_name => $row)
    	{
    		if($row['is_required']=='Yes')
    		{
    			?>
    			if($("#item_<?=$row['id']?>").size)
    			{
	    			if($("#item_<?=$row['id']?>").val()=="")
	    			{
	    				alert("Please enter <?=$row['var_name']?>");
	    				$("#item_<?=$row['id']?>").focus();
	    				return false;
	    			}
    			}
    			<?
    		}
		}
	}
?>

	return true;
}
</script>

</body>
</html>