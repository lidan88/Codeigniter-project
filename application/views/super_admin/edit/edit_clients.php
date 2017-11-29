<!DOCTYPE html>
<html lang="en">
<head>
<? include(APPPATH."views/head.inc.php"); ?>
<script> 
 			
 function form_validation(thisform) {var re5digit=/^\d*$/ //regular expression defining a 5 digit number
	if (thisform.role_id.value.search(re5digit)==-1) //if match failed
	{
		alert('Please enter a valid digit number inside forms');
		thisform.role_id.focus();
		return false;
	}
	
	if (thisform.first_name.value == '') 
	{
		alert ('First name is required');
			thisform.first_name.focus();
			return false;
		}
		
		if (thisform.last_name.value == '') {
			alert ('Last name is required');
			thisform.last_name.focus();
			return false;
		}
		
		if (thisform.username.value == '') {
			alert ('Username is required');
			thisform.username.focus();
			return false;
		}
		
		if (thisform.password.value == '') {
			alert ('Password is required');
			thisform.password.focus();
			return false;
		}
		
		if (thisform.email.value == '') {
			alert ('Email is required');
			thisform.email.focus();
			return false;
		}
		
		return true; 
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
		  <a href="/super_admin/clients/main/<?=$company_id?>/"><?=$company_details['name']?></a>
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
	          	                  <div class="pull-left">Edit Client </div>
	          	                  <div class="widget-icons pull-right">
	          	                    <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	          	                    <a href="#" class="wclose"><i class="icon-remove"></i></a>
	          	                  </div>
	          	                  <div class="clearfix"></div>
	          	                </div>
	          	
	          	                <div class="widget-content">
	          	                  <div class="padd">
	          	
	          	   	          	    <form class='form-horizontal' name='frm_edit_clients' method='post' action='/super_admin/clients/do_update/<?=$item['id']?>' onSubmit='return form_validation(this);' enctype='multipart/form-data'>
	          	                    <table class="table table-bordered table-striped table-hover content" style="width:100%;" cellpadding="0" cellspacing="0">
	          	                    <input type='hidden' id='chaabee_post' name='chaabee_post' value=''>
	          	                    <input type='hidden' id='id' name='id' value='<?=$item['id']?>'>
	          	                    <tr>
	          	                    <td width='10%' class='capitalize' valign='top' align='left'>First name</td>
	          	                    <td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='first_name'  value='<?=$item['first_name']?>'  /></td>
	          	                    </tr>
		      	                    <tr>
		      	                    <td width='10%' class='capitalize' valign='top' align='left'>Last name</td>
	          	                    <td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='last_name'  value='<?=$item['last_name']?>'  /></td></tr><tr>
	          	                    <td width='10%' class='capitalize' valign='top' align='left'>Username</td>
	          	                    <td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='username'  value='<?=$item['username']?>'  /></td></tr><tr>
	          	                    <td width='10%' class='capitalize' valign='top' align='left'>Password</td>
	          	                    <td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='password'  value='<?=$item['password']?>'  /></td></tr><tr>
	          	                    <td width='10%' class='capitalize' valign='top' align='left'>Email</td>
	          	                    <td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='email'  value='<?=$item['email']?>'  /></td></tr><tr>
	          	                    <td width='10%' class='capitalize' valign='top' align='left'>Alternate Email</td>
	          	                    <td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='aemail'  value='<?=$item['aemail']?>'  /></td></tr>
	          	                    <tr>
	          	                    <td width='10%' class='capitalize' valign='top' align='left'>Phone</td>
	          	                    <td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='phone'  value='<?=$item['phone']?>'  /></td></tr><tr>
	          	                    <td width='10%' class='capitalize' valign='top' align='left'>Address</td>
	          	                    <td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='address'  value='<?=$item['address']?>'  /></td></tr><tr>
	          	                    <td width='10%' class='capitalize' valign='top' align='left'>Notes</td>
	          	                    <td width='65%' class='capitalize'><textarea  class='form-control'  rows=5 cols=35 name='notes' id='notes'><?=$item['notes']?></textarea></td></tr><tr>
	          	                    <td width='10%' class='capitalize'  valign='top' align='left'>Status</td>
	          	                    <td width='65%' class='capitalize'>
	          	                    	
	          	                    	<div class="radio">
	          	                    	  <label>
	          	                    	    <input type="radio" name="status" id="optionsRadios1" value="ACTIVE" <? if(isset($item)){ if($item['status']=='ACTIVE')echo 'checked="checked"'; } ?> />
	          	                    	    Active
	          	                    	  </label>
	          	                    	</div>
	          	                    	
	          	                    	<div class="radio">
	          	                    	  <label>
	          	                    	    <input type="radio" name="status" id="optionsRadios1" value="INACTIVE" <? if(isset($item)){ if($item['status']=='INACTIVE')echo 'checked="checked"'; } ?> />
	          	                    	    In-Active
	          	                    	  </label>
	          	                    	</div>
	          	                    	
	          	                    	</td></tr>
	          	                    <tr><td class='capitalize' width='10%'>&nbsp;</td>
	          	                    <td width='65%' class='capitalize'><input name='Submit' id='Submit' class=btn type='submit' value='Submit' /></td></tr></table>
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