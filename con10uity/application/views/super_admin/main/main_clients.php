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
 <? include(APPPATH."views/head.inc.php"); ?>
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



<!-- Header starts -->
<? include(APPPATH."views/super_admin/header.inc.php"); ?>
<!-- Header ends -->

<!-- Main content starts -->

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
          <a href="/super_admin/clients/add/<?=$company_id?>/">Add New</a>
          
        </div>

        <div class="clearfix"></div>

      </div>
      <!-- Page heading ends -->

	    <!-- Matter -->
	    <div class="matter">
	        <div class="container">
	
					            
	          <div class="widget">
	          
	                          <div class="widget-head">
	                            <div class="pull-left">Users <span class="divider">/</span> <a href="/super_admin/clients/add/<?=$company_id?>/">Add New</a></div>
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
	                                    <th>#</th>
	                                    <th>Name</th>
	                                    <th>Email</th>
	                                    <th>Username</th>
	                                    <th>Impersonate</th>
	                                    <th>Status</th>
	                                    <th>Actions</th>
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
	                                    <td><?=$row['id']?></td>
	                                    <td><?=$row['first_name']?> <?=$row['last_name']?></td>
	                                    <td><?=$row['email']?></td>
	                                    <td><?=$row['username']?></td>
	                                    <td><a target="_blank" href="/auth/login/?username=<?=urlencode($row['username'])?>&password=<?=urlencode($row['password'])?>">Impersonate As</a></td>
	                                   
	                                    <td>
	                                    	 <? if($row['status']=='ACTIVE'){ ?>
	                                    		<span class="label label-success">Active</span>
	                                    	<?}else{?>
	                                    		<span class="label label-danger">In Active</span>
	                                    	<?}?>
	                                    </td>
	                                    <td>
	          
	                                        <? /*<button class="btn btn-xs btn-success"><i class="icon-ok"></i> </button>*/ ?>
	                                        <a href="/super_admin/clients/edit/<?=$row['id']?>/" class="btn btn-xs btn-warning"><i class="icon-pencil"></i> </a>
	                                        <button onclick="confirm_delete('/super_admin/clients/do_delete/<?=$row['id']?>/');" class="btn btn-xs btn-danger"><i class="icon-remove"></i> </button>
	                                    
	                                    </td>
	                                  </tr>
	          							<?	}
	          							}?>
	          
	          
	          <? /*
	                                  <tr>
	                                    <td>2</td>
	                                    <td>Parneethi Chopra</td>
	                                    <td>USA</td>
	                                    <td>13/02/2012</td>
	                                    <td>Free</td>
	                                    <td><span class="label label-danger">Banned</span></td>
	                                    <td>
	          
	                                        <button class="btn btn-xs btn-default"><i class="icon-ok"></i> </button>
	                                        <button class="btn btn-xs btn-default"><i class="icon-pencil"></i> </button>
	                                        <button class="btn btn-xs btn-default"><i class="icon-remove"></i> </button>
	          
	                                    </td>
	                                  </tr>
	          
	                                  <tr>
	                                    <td>3</td>
	                                    <td>Kumar Ragu</td>
	                                    <td>Japan</td>
	                                    <td>12/03/2012</td>
	                                    <td>Paid</td>
	                                    <td><span class="label label-success">Active</span></td>
	                                    <td>
	          
	                                      <div class="btn-group">
	                                        <button class="btn btn-xs btn-default"><i class="icon-ok"></i> </button>
	                                        <button class="btn btn-xs btn-default"><i class="icon-pencil"></i> </button>
	                                        <button class="btn btn-xs btn-default"><i class="icon-remove"></i> </button>
	                                      </div>
	          
	                                    </td>
	                                  </tr>
	          
	                                  <tr>
	                                    <td>4</td>
	                                    <td>Vishnu Vardan</td>
	                                    <td>Bangkok</td>
	                                    <td>03/11/2012</td>
	                                    <td>Paid</td>
	                                    <td><span class="label label-success">Active</span></td>
	                                    <td>
	          
	                                      <div class="btn-group">
	                                        <button class="btn btn-xs btn-success"><i class="icon-ok"></i> </button>
	                                        <button class="btn btn-xs btn-warning"><i class="icon-pencil"></i> </button>
	                                        <button class="btn btn-xs btn-danger"><i class="icon-remove"></i> </button>
	                                      </div>
	          
	                                    </td>
	                                  </tr>
	          
	                                  <tr>
	                                    <td>5</td>
	                                    <td>Anuksha Sharma</td>
	                                    <td>Singapore</td>
	                                    <td>13/32/2012</td>
	                                    <td>Free</td>
	                                    <td><span class="label label-danger">Banned</span></td>
	                                    <td>
	          
	                                      <div class="btn-group1">
	                                        <button class="btn btn-xs btn-success"><i class="icon-ok"></i> </button>
	                                        <button class="btn btn-xs btn-warning"><i class="icon-pencil"></i> </button>
	                                        <button class="btn btn-xs btn-danger"><i class="icon-remove"></i> </button>
	                                      </div>
	          
	                                    </td>
	                                  </tr>                                                            
	          */
	          ?>
	                                </tbody>
	                              </table>
	          
	                              <div class="widget-foot">
	          
	                               
	                               	<?=$pagination?>
	                               	<? /*
	                                  <ul class="pagination pull-right">
	                                    <li><a href="#">Prev</a></li>
	                                    <li><a href="#">1</a></li>
	                                    <li><a href="#">2</a></li>
	                                    <li><a href="#">3</a></li>
	                                    <li><a href="#">4</a></li>
	                                    <li><a href="#">Next</a></li>
	                                  </ul>
	                                  */
	                                  ?>
	                               
	                                <div class="clearfix"></div> 
	          
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