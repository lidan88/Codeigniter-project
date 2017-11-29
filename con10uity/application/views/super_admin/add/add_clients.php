<!DOCTYPE html>
<html lang="en">
<head>
 <? include(APPPATH."views/head.inc.php"); ?>
 <style>
 .control-label{
 	min-width: 150px;
 }
 </style>
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
          <a href="/super_admin/clients/main/<?=$company_id?>/">Users</a>
          <span class="divider">/</span> 
          <a href="#" class="bread-current">Add New</a>
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
	          	                  <div class="pull-left">Add New User</div>
	          	                  <div class="widget-icons pull-right">
	          	                    <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	          	                    <a href="#" class="wclose"><i class="icon-remove"></i></a>
	          	                  </div>
	          	                  <div class="clearfix"></div>
	          	                </div>
	          	
	          	                <div class="widget-content">
	          	                  <div class="padd">
	          	
	          	                    <h6>User Details</h6>
	          	                    <hr />
	          	                    <!-- Form starts.  -->
	          	                     <form class="form-horizontal" role="form" method="post" action="/super_admin/clients/do_add/">
	          	                     <input type="hidden" name="company_id" value="<?=$company_id?>" />         
          	                                <div class="form-group">
          	                                  <label class="col-lg-4 control-label">First Name</label>
          	                                  <div class="col-lg-8">
          	                                    <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name">
          	                                  </div>
          	                                </div>
          	                                
          	                                <div class="form-group">
          	                                  <label class="col-lg-4 control-label">Last Name</label>
          	                                  <div class="col-lg-8">
          	                                    <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name">
          	                                  </div>
          	                                </div>
          	                                
          	                                <div class="form-group">
          	                                  <label class="col-lg-4 control-label">User Name</label>
          	                                  <div class="col-lg-8">
          	                                    <input type="text" name="username" id="username" class="form-control" placeholder="User Name">
          	                                  </div>
          	                                </div>
          	                                
          	                                <div class="form-group">
          	                                  <label class="col-lg-4 control-label">Email</label>
          	                                  <div class="col-lg-8">
          	                                    <input type="text" name="email" id="email" class="form-control" placeholder="Email">
          	                                  </div>
          	                                </div>
          	                                
          	                                <div class="form-group">
          	                                  <label class="col-lg-4 control-label">Password</label>
          	                                  <div class="col-lg-8">
          	                                    <input type="text" name="password" id="password" class="form-control" placeholder="Password">
          	                                  </div>
          	                                </div>
          	                                
          	                                         	                                
          	                                <div class="form-group">
          	                                  <label class="col-lg-4 control-label">Alternate Email</label>
          	                                  <div class="col-lg-8">
          	                                    <input type="text" name="aemail" id="aemail" class="form-control" placeholder="Alternate Email">
          	                                  </div>
          	                                </div>
          	                                
          	                                <div class="form-group">
          	                                  <label class="col-lg-4 control-label">Phone</label>
          	                                  <div class="col-lg-8">
          	                                    <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone">
          	                                  </div>
          	                                </div>
          	                                
          	                                <div class="form-group">
          	                                  <label class="col-lg-4 control-label">Address</label>
          	                                  <div class="col-lg-8">
          	                                    <input type="text" name="address" id="address" class="form-control" placeholder="Address">
          	                                  </div>
          	                                </div>
          	                                
          	                                <div class="form-group">
          	                                  <label class="col-lg-4 control-label">Notes</label>
          	                                  <div class="col-lg-8">
          	                                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Notes"></textarea>
          	                                  </div>
          	                                </div>    
          	                                
          	                                <div class="form-group">
          	                                  <label class="col-lg-4 control-label">Status</label>
          	                                  <div class="col-lg-8">
          	                                    <div class="radio">
          	                                      <label>
          	                                        <input type="radio" name="status" id="optionsRadios1" value="ACTIVE" checked>
          	                                        Active
          	                                      </label>
          	                                    </div>
          	                                    <div class="radio">
          	                                      <label>
          	                                        <input type="radio" name="status" id="optionsRadios2" value="INACTIVE">
          	                                        In-Active
          	                                      </label>
          	                                    </div>
          	                                  </div>
          	                                </div>
          	                                <? /*
          	                                <div class="form-group">
          	                                  <label class="col-lg-4 control-label">Role</label>
          	                                  <div class="col-lg-8">
          	                                    <select class="form-control">
          	                                      <option>Admin</option>
          	                                      <option>Super Admin</option>
          	                                      <option>BIA</option>
          	                                      
          	                                    </select>
          	                                  </div>
          	                                </div>     
          									*/
          									?>
          	                                <hr />
          	                                <div class="form-group">
          	                                  <div class="col-lg-offset-1 col-lg-9">
          	                                    <button type="submit" class="btn btn-primary">Add</button>
          	                                  </div>
          	                                </div>
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