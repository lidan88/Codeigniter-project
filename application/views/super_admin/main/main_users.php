<?
$limit=30;
$sort=isset($_REQUEST['sort'])?$_REQUEST['sort']:"user_id";
$ord=isset($_REQUEST['ord'])?$_REQUEST['ord']:"ASC";
$page=isset($_REQUEST['page'])?$_REQUEST['page']:0;
$submit=isset($_REQUEST['Submit'])?$_REQUEST['Submit']:"";
$search=isset($_REQUEST['search'])?$_REQUEST['search']:"";


$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
$href1=$_SERVER['PHP_SELF']."?";

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Users</title>

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

function do_search(search) {
	$.post("/super_admin/users/main/<?=$company_id?>/?output=ajax&search="+search,{},function(ret){
		var test = ret;
		var content = test.substring(0, test.indexOf('<div id="pagination">'));
		var pagination = test.substring(test.indexOf('<div id="pagination">'), test.length);
		$('#list').find('tbody').html(content);
		$('#pagination').html(pagination);
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
		 <a href="/super_admin/"><i class="icon-home"></i> Home</a> 
		 <span class="divider">/</span> 
		 <a href="/super_admin/company/main/"><?=$company_details['name']?></a>
		 <span class="divider">/</span> 
		 <a href="#" class="bread-current">Users</a>
		 <span class="divider">/</span> 
		 <a href="/super_admin/users/add/<?=$company_id?>/">Add New</a>
		 
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
			                  <div class="pull-left">Users for <?=$company_details['name']?></div>
			                  <div class="pull-left" style="margin-left: 20px;">
			                   
			                   	<a href="/super_admin/users/add/<?=$company_id?>/" class="btn btn-mini btn-primary">Add</a>
			                   </div>
			                  <div class="widget-icons pull-right">
			                    <form method="get" class="form-inline" action="?">
			                    	<input type="text" class="form-control" style="width: 300px;" name="search" value="<?=$search?>" onkeyup="do_search(this.value)" placeholder="Search" />
			                    </form>
			                    <!--<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
			                    <a href="#" class="wclose"><i class="icon-remove"></i></a>-->
			                  </div>  
			                  <div class="clearfix"></div>
			                </div>
			
			                <div class="widget-content">
			                	<table id="list" class="table table-striped table-bordered table-hover">
			                	    <thead>
			                	    <tr>
			                	    
								<td class="rowHeader"><?                 
					                if ($sort=='role_id') {
					                	if ($ord=='ASC') {
					                		echo  '<a href="'.$href1.'&sort=role_id&ord=DESC&Submit=Search">';
					                	} else {
					                		echo  '<a href="'.$href1.'&sort=role_id&ord=ASC&Submit=Search">';
					                	}
					                } else {
					                	echo  '<a href="'.$href1.'&sort=role_id&ord=ASC&Submit=Search">';
					                }
					                ?>Role </a></td>
								<td class="rowHeader"><?                 
					                if ($sort=='first_name') {
					                	if ($ord=='ASC') {
					                		echo  '<a href="'.$href1.'&sort=first_name&ord=DESC&Submit=Search">';
					                	} else {
					                		echo  '<a href="'.$href1.'&sort=first_name&ord=ASC&Submit=Search">';
					                	}
					                } else {
					                	echo  '<a href="'.$href1.'&sort=first_name&ord=ASC&Submit=Search">';
					                }
					                ?>First name</a></td>
								<td class="rowHeader"><?                 
					                if ($sort=='last_name') {
					                	if ($ord=='ASC') {
					                		echo  '<a href="'.$href1.'&sort=last_name&ord=DESC&Submit=Search">';
					                	} else {
					                		echo  '<a href="'.$href1.'&sort=last_name&ord=ASC&Submit=Search">';
					                	}
					                } else {
					                	echo  '<a href="'.$href1.'&sort=last_name&ord=ASC&Submit=Search">';
					                }
					                ?>Last name</a></td>
								<td class="rowHeader"><?                 
					                if ($sort=='username') {
					                	if ($ord=='ASC') {
					                		echo  '<a href="'.$href1.'&sort=username&ord=DESC&Submit=Search">';
					                	} else {
					                		echo  '<a href="'.$href1.'&sort=username&ord=ASC&Submit=Search">';
					                	}
					                } else {
					                	echo  '<a href="'.$href1.'&sort=username&ord=ASC&Submit=Search">';
					                }
					                ?>Username</a></td>
								
								<td class="rowHeader"><?                 
					                if ($sort=='email') {
					                	if ($ord=='ASC') {
					                		echo  '<a href="'.$href1.'&sort=email&ord=DESC&Submit=Search">';
					                	} else {
					                		echo  '<a href="'.$href1.'&sort=email&ord=ASC&Submit=Search">';
					                	}
					                } else {
					                	echo  '<a href="'.$href1.'&sort=email&ord=ASC&Submit=Search">';
					                }
					                ?>Email</a></td>
								
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
								<td class="rowHeader"><?                 
					                if ($sort=='status') {
					                	if ($ord=='ASC') {
					                		echo  '<a href="'.$href1.'&sort=status&ord=DESC&Submit=Search">';
					                	} else {
					                		echo  '<a href="'.$href1.'&sort=status&ord=ASC&Submit=Search">';
					                	}
					                } else {
					                	echo  '<a href="'.$href1.'&sort=status&ord=ASC&Submit=Search">';
					                }
					                ?>Status</a></td>
								<td class="rowHeader"><?                 
					                if ($sort=='added') {
					                	if ($ord=='ASC') {
					                		echo  '<a href="'.$href1.'&sort=added&ord=DESC&Submit=Search">';
					                	} else {
					                		echo  '<a href="'.$href1.'&sort=added&ord=ASC&Submit=Search">';
					                	}
					                } else {
					                	echo  '<a href="'.$href1.'&sort=added&ord=ASC&Submit=Search">';
					                }
					                ?>Added</a></td>
					                <td>Impersonate</td>
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
			                	            <tr>
			                	            
											<td align="left" valign="top" class="rowDark"><?=isset($row['frole_id'])?$row['frole_id']:$row['role_id']?></td>
											<td align="left" valign="top" class="rowDark"><?=isset($row['ffirst_name'])?$row['ffirst_name']:$row['first_name']?></td>
											<td align="left" valign="top" class="rowDark"><?=isset($row['flast_name'])?$row['flast_name']:$row['last_name']?></td>
											<td align="left" valign="top" class="rowDark"><?=isset($row['fusername'])?$row['fusername']:$row['username']?></td>
											<td align="left" valign="top" class="rowDark"><?=isset($row['femail'])?$row['femail']:$row['email']?><br /><?=$row['aemail']?></td>
											<td align="left" valign="top" class="rowDark"><?=isset($row['fphone'])?$row['fphone']:$row['phone']?></td>
											<td align="left" valign="top" class="rowDark"><?=isset($row['fstatus'])?$row['fstatus']:$row['status']?></td>
											<td align="left" valign="top" class="rowDark"><?=isset($row['fadded'])?$row['fadded']:$row['added']?></td>
			                	            <td><a class="btn btn-danger" target="_blank" href="/auth/login/?username=<?=urlencode($row['username'])?>&password=<?=urlencode($row['password'])?>">Impersonate As</a></td>
			                	            
			                	            	<td align="center" valign="top" class="rowDark">
			                	            		<a style="text-decoration:none" class="btn btn-xs btn-warning" href="/super_admin/users/edit/<?=$row['user_id']?>/"><i class="icon-pencil"></i> </a>
			                	            		<a style="text-decoration:none" class="btn btn-xs btn-danger" href="javascript:confirm_delete('/super_admin/users/do_delete/<?=$row['user_id']?>/');"><i class="icon-remove"></i></a>
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
			                <div id="pagination" class="widget-foot">
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