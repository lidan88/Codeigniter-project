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
		  <a href="/admin/library_items/main/<?=$library_details['id']?>" class="bread-current"><?=$library_details['name']?> </a>
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
			                  <div class="pull-left">View <?=$library_details['name']?></div>
			                  <div class="pull-right">
			                  	<a href="javascript:history.go(-1);" class="btn btn-primary">Go Back</a>
			                    <? /*<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
			                    <a href="#" class="wclose"><i class="icon-remove"></i></a>*/ ?>
			                  </div>  
			                  <div class="clearfix"></div>
			                </div>
			
			                <div class="widget-content">
			                	<form method="post" action="/admin/library_items/do_update_user_data/<?=$value_id?>/">
			                		<input type="hidden" name="company_id" value="<?=$library_details['company_id']?>" />
			                		<input type="hidden" name="library_id" value="<?=$library_details['id']?>" />
			                		<table class="table table-striped table-bordered table-hover">
			                	    <tbody>
			                	    <?                
			                	        if(is_array($list) and count($list)>0)
			                	        {
			                	            foreach($list as $k => $row)
			                	            {
			                	        ?>
			                	            <tr>
			                	            
												<td align="left" valign="top" class="rowDark"><?=isset($row['fvar_name'])?$row['fvar_name']:$row['var_name']?></td>
												<!--<td align="left" valign="top" class="rowDark"><?=($row['var_value']=='')?'-':$row['var_value']?></td>
												<td align="left" valign="top" class="rowDark"><?=$field_value_types[$row['var_value_type']]?></td>-->
												<td align="left" valign="top" class="rowDark">
			                	            		<?
			                	            			
			                	            			if(isset($values[$row['id']]))
			                	            			{
			                	            				if(is_array($values[$row['id']]))
			                	            				{
			                	            					echo array_recursive_value($values[$row['id']]);
			                	            					//echo implode(', ', $values[$row['id']]);
			                	            				}
			                	            				else {
			                	            					echo makelink((opt2value($values[$row['id']])));	
			                	            				}
			                	            			}
			                	            		?>
			                	            		
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

</body>
</html>