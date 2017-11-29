<?
$limit=30;
$sort=isset($_REQUEST['sort'])?$_REQUEST['sort']:"company_id";
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
<title>Company</title>

<!--HEAD-->
<? include(APPPATH."views/super_admin/head.inc.php"); ?>
<script>
function confirm_delete(plink)
{
	if(confirm("Are you sure you want to delete?"))
	{
		top.location=plink;	
	}
}

function toggle_enable(company_id,is_enabled)
{
	if(is_enabled)
		is_enabled=1;
	else {
		is_enabled=0;
	}

	$.post("/super_admin/company/set_enable_status",{"company_id":company_id,"enabled":is_enabled},function(ret){
		console.log(ret);
	});
}
</script>
</head>
<body>
<!--HEADER-->
<? include(APPPATH."views/super_admin/header.inc.php"); ?>


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
		  <a href="index.html"><i class="icon-home"></i> Home</a> 
		  <!-- Divider -->
		  <span class="divider">/</span> 
		  <a href="#" class="bread-current">Company </a>
		  <span class="divider">/</span> 
		  <a href="/super_admin/company/add/"> &raquo; Add New</a>
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
			                  <div class="pull-left">Company</div>
			                  <div class="widget-icons pull-right">
			                    <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
			                    <a href="#" class="wclose"><i class="icon-remove"></i></a>
			                  </div>  
			                  <div class="clearfix"></div>
			                </div>
			
			                <div class="widget-content">
			                	<table class="table table-striped table-bordered table-hover">
			                	    <thead>
			                	    <tr>
			                	    
										<td class="rowHeader"><?                 
							                if ($sort=='name') {
							                	if ($ord=='ASC') {
							                		echo  '<a href="'.$href1.'&sort=name&ord=DESC&Submit=Search">';
							                	} else {
							                		echo  '<a href="'.$href1.'&sort=name&ord=ASC&Submit=Search">';
							                	}
							                } else {
							                	echo  '<a href="'.$href1.'&sort=name&ord=ASC&Submit=Search">';
							                }
							                ?>Name</a></td>
										<td class="rowHeader"><?                 
										    if ($sort=='main_contact') {
										    	if ($ord=='ASC') {
										    		echo  '<a href="'.$href1.'&sort=main_contact&ord=DESC&Submit=Search">';
										    	} else {
										    		echo  '<a href="'.$href1.'&sort=main_contact&ord=ASC&Submit=Search">';
										    	}
										    } else {
										    	echo  '<a href="'.$href1.'&sort=main_contact&ord=ASC&Submit=Search">';
										    }
										    ?>Main contact</a></td>
										<td class="rowHeader"><?                 
							                if ($sort=='phone') {
							                	if ($ord=='ASC') {
							                		echo  '<a href="'.$href1.'&sort=phone&ord=DESC&Submit=Search">';
							                	} else {
							                		echo  '<a href="'.$href1.'&sort=phone&ord=ASC&Submit=Search">';
							                	}
							                } else {
							                	echo  '<a href="'.$href1.'&sort=phone&ord=ASC&Submit=Search">';
							                }
							                ?>Phone</a></td>
									
										
										<td width="5%" class="rowHeader" class="center">Enabled</a></td>
				                	    <td width="10%" align="center" class="rowHeader">Actions</td>
				                	    </tr>
				                	    </thead>
				                	    <tbody>
				                	    <?                
				                	        if(is_array($list) and count($list)>0)
				                	        {
				                	            foreach($list as $k => $row)
				                	            {
				                	        ?>
				                	            <tr>
				                	            
													<td align="left" valign="top" class="rowDark"><?=$row['username']?></td>
													<td align="left" valign="top" class="rowDark"><?=isset($row['fmain_contact'])?$row['fmain_contact']:$row['main_contact']?></td>
													<td align="left" valign="top" class="rowDark"><?=isset($row['fphone'])?$row['fphone']:$row['phone']?></td>
													<td align="center" valign="top" class="rowDark">
														<input type="checkbox" name="enabled" value="<?=isset($row['enabled'])?$row['enabled']:0?>" onclick="toggle_enable(<?=$row['company_id']?>,this.checked)" <?=(isset($row['enabled']) and $row['enabled']==1)?'checked="checked"':''?> />
													</td>
				                	            	<td align="center" valign="top" class="rowDark">
			                	            			<a style="text-decoration:none" class="btn btn-xs btn-success" title="Log In" href="/auth/al/<?=$row['company_id']?>/"><i class="icon-signin"></i> </a>
				                	            		<a style="text-decoration:none" class="btn btn-xs btn-warning" title="Edit Company" href="/super_admin/company/edit/<?=$row['company_id']?>/"><i class="icon-pencil"></i> </a>
				                	            		<a style="text-decoration:none" class="btn btn-xs btn-danger" title="Delete Company" href="javascript:confirm_delete('/super_admin/company/do_delete/<?=$row['company_id']?>/');"><i class="icon-remove"></i></a>
				                	            	</td>
				                	            </tr>
				                	        <? 	
				                	            }
				                	        }	
				                	        else
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
				                	        </tbody>
				                	    </table>
			                	
			                		
			                </div>
			                <div class="widget-foot">
			                	 <?=$pagination?>
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

</body>
</html>