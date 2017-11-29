<?
$limit=30;
$sort=isset($_REQUEST['sort'])?$_REQUEST['sort']:"id";
$ord=isset($_REQUEST['ord'])?$_REQUEST['ord']:"ASC";
$page=isset($_REQUEST['page'])?$_REQUEST['page']:0;
$submit=isset($_REQUEST['Submit'])?$_REQUEST['Submit']:"";

$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
$href1=$_SERVER['PHP_SELF']."?";

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Library items</title>

<!--HEAD-->
<? include(APPPATH."views/admin/head.inc.php"); ?>
<script>
function confirm_delete(plink)
{
	if(confirm("Are you sure you want to delete?"))
	{
		top.location=plink;	
	}
}

$(document).ready(function(){
	var dp = $('.datepicker').datepicker().on('changeDate', function(ev) {
	  dp.datepicker('hide');
	});
	$(".autogrow").autoGrow();
	$('.timepicker').timepicker();
	$(".chosen-select").chosen();
});
</script>
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
					        	
					        <? if(isset($_GET['error'])){ ?>
					        	<div class="alert alert-danger">Unique ID already exists in our system.</div>
					        <? } ?>
					        
			                  <div class="pull-left">Add <?=$library_details['name']?></div>
			                  <!--<div class="widget-icons pull-right">
			                    <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
			                    <a href="#" class="wclose"><i class="icon-remove"></i></a>
			                  </div>  -->
			                  <div class="clearfix"></div>
			                </div>
			
			                <div class="widget-content">
			                	<form method="post" action="/admin/library_items/process_user_data/<?=$library_details['id']?>" enctype="multipart/form-data" onsubmit="return validate_form()">
			                		<input type="hidden" name="company_id" value="<?=$library_details['company_id']?>" />
			                		<table class="table table-striped table-bordered table-hover">
			                	    <tbody>
			                	    <tr>
			                	    	<td width="10%">ID: (<span class="text-info">Leave Blank to get an auto generated ID</span>)</td>
			                	    	<td width="90%"><input type="text" class="form-control" name="unique_id" value="" placeholder="Enter Unique ID" />
			                	    	</td>
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
			                	        	<td class='capitalize' width='35%'>&nbsp;</td>
			                	        	<td width='65%' class='capitalize'><input name='Submit' id='Submit' class="btn btn-danger" type='submit' value='Add' /></td>
			                	        </tr>
			                	        </tbody>
			                	    </table>
			                	</form>
			                </div>
			            </div>
			        </div>
			    </div>
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