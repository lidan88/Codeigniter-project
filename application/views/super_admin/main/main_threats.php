<?
$limit=30;
$sort=isset($_REQUEST['sort'])?$_REQUEST['sort']:"threat_id";
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
<title>Threats</title>

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
		  <a href="#" class="bread-current">Threats </a>
		  <span class="divider">/</span> 
		  <a href="/super_admin/threats/add/"> &raquo; Add New</a>
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
			                  <div class="pull-left">Threats</div>
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
					                if ($sort=='group') {
					                	if ($ord=='ASC') {
					                		echo  '<a href="'.$href1.'&sort=group&ord=DESC&Submit=Search">';
					                	} else {
					                		echo  '<a href="'.$href1.'&sort=group&ord=ASC&Submit=Search">';
					                	}
					                } else {
					                	echo  '<a href="'.$href1.'&sort=group&ord=ASC&Submit=Search">';
					                }
					                ?>Group</a></td>
								<td class="rowHeader"><?                 
					                if ($sort=='added_on') {
					                	if ($ord=='ASC') {
					                		echo  '<a href="'.$href1.'&sort=added_on&ord=DESC&Submit=Search">';
					                	} else {
					                		echo  '<a href="'.$href1.'&sort=added_on&ord=ASC&Submit=Search">';
					                	}
					                } else {
					                	echo  '<a href="'.$href1.'&sort=added_on&ord=ASC&Submit=Search">';
					                }
					                ?>Added on</a></td>
			                	    <td width="18%" align="center" class="rowHeader">Actions</td>
			                	    </tr>
			                	    </thead>
			                	    <tbody>
			                	    <?                
			                	        if(is_array($list) and count($list)>0)
			                	        {
			                	            foreach($list as $k => $row)
			                	            {
			                	        ?>
			                	            <tr onClick="javascript:toggleBgColor( this )">
			                	            
												<td align="left" valign="top" class="rowDark"><?=isset($row['fname'])?$row['fname']:$row['name']?></td>
												<td align="left" valign="top" class="rowDark"><?=isset($row['fgroup'])?$row['fgroup']:$row['group']?></td>
												<td align="left" valign="top" class="rowDark"><?=isset($row['fadded_on'])?$row['fadded_on']:$row['added_on']?></td>
											
			                	            	<td align="center" valign="top" class="rowDark">
			                	            		<a style="text-decoration:none" class="btn btn-xs btn-warning" href="/super_admin/threats/edit/<?=$row['threat_id']?>/"><i class="icon-pencil"></i> </a>
			                	            		<a style="text-decoration:none" class="btn btn-xs btn-danger" href="javascript:confirm_delete('/super_admin/threats/do_delete/<?=$row['threat_id']?>/');"><i class="icon-remove"></i></a>
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