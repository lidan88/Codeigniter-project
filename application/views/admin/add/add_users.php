<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Panel</title>
<!--HEAD-->
	<? include(APPPATH."views/admin/head.inc.php"); ?>
<!-- /HEAD -->
<script src="/js/pwstrength.js"></script>
<script> 
			
			
function form_validation(thisform) {
			if (thisform.first_name.value == '') {
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
			
			
			//---
			if(thisform.password.value.length < 8) {
				alert("Error: Password must contain at least 8 characters!");
				thisform.password.focus();
				return false;
		    }
			      
			re = /[0-9]/;
			if(!re.test(thisform.password.value)) {
				alert("Error: password must contain at least one number (0-9)!");
				thisform.password.focus();
				return false;
			}
			
			re = /[a-z]/;
			if(!re.test(thisform.password.value)) {
				alert("Error: password must contain at least one lowercase letter (a-z)!");
				thisform.password.focus();
				return false;
			}
			re = /[A-Z]/;
			if(!re.test(thisform.password.value)) {
				alert("Error: password must contain at least one uppercase letter (A-Z)!");
				thisform.password.focus();
				return false;
			}
			
			re = /[!@#$%^&*()_+=-?{}|<>\[\]~]/; //
			if(!re.test(thisform.password.value)) {
				alert("Error: Password must contain at least one special character (!@#$%&*()_+=-|<>?{}[]~)");
				thisform.password.focus();
				return false;
			}
			//---
			
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
         <a href="/admin/"><i class="icon-home"></i> Home</a> 
            <span class="divider">/</span> 
            <a href="/admin/users/main/">Users</a>
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
	          	                    <!--<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	          	                    <a href="#" class="wclose"><i class="icon-remove"></i></a>-->
	          	                  </div>
	          	                  <div class="clearfix"></div>
	          	                </div>
	          	
	          	                <div class="widget-content">
	          	                  <div class="padd">
	          	
	          	                    <!-- Form starts.  -->
	          	                     <form class='form-horizontal' name='frm_add_users' method='post' action='/admin/users/do_add/' onSubmit='return form_validation(this);' enctype='multipart/form-data'>
	          	                     	<input type="hidden" name="company_id" value="<?=$company_id?>" />
	          	                     	<table class="table table-bordered table-striped table-hover content" style="width:100%;" cellpadding="0" cellspacing="0">
	          	                     	<input type='hidden' id='chaabee_post' name='chaabee_post' value=''>
	          	                     	<input type='hidden' id='user_id' name='user_id' value=''>
	          	        				<tr>
											<td width='35%' class='capitalize' valign='top' align='left'>Role:</td>
											<td width='65%' class='capitalize'><select class='form-control' name='role_id' id='role_id'><?
											$res = $this->db->query("select * from `role` where company_id=".$company_details['company_id']);
											if($res->num_rows()>0)
											{
												foreach ($res->result_array() as $data)
												{
													echo "<option value='".$data['role_id']."' ".((isset($item) and $item['role_id']==$data['role_id'])?'selected="selected"':"").">".(isset($data['name'])?$data['name']:$data['role'])."</option>";
												}
											}
											?></select>
											</td>
										</tr>
										<tr>
											<td width='35%' class='capitalize' valign='top' align='left'>Timezone:</td>
											<td width='65%' class='capitalize'><select class='form-control' name='timezone' id='timezone'><?
											$res = $this->db->query("select * from `timezones`");
											if($res->num_rows()>0)
											{
												foreach ($res->result_array() as $data)
												{
													if(!isset($item))
														$item['timezone'] = 3;
													echo "<option value='".$data['id']."' ".((isset($item) and $item['timezone']==$data['id'])?'selected="selected"':"").">".$data['timezone'].' - '.$data['name']."</option>";
												}
											}
											?></select>
											</td>
										</tr>
										<tr>
											<td width='35%' class='capitalize' valign='top' align='left'>First name:</td>
											<td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='first_name'  value=''  /></td></tr><tr>
											<td width='35%' class='capitalize' valign='top' align='left'>Last name:</td>
											<td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='last_name'  value=''  /></td></tr><tr>
											<td width='35%' class='capitalize' valign='top' align='left'>Username:</td>
											<td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='username'  value=''  /></td></tr><tr>
											<td width='35%' class='capitalize' valign='top' align='left'>Password:</td>
											<td width='65%' class='capitalize'>
												<input  class='form-control ' type='password' size=40 id="password" name='password'  value=''  />
												<div class="row" id="pwd-container">
												    <div class="col-sm-4">
												        <div class="pwstrength_viewport_progress"></div>
												    </div>
												</div>
												<div class="row">
												    <div id="messages" class="col-sm-12"></div>
												</div>
											</td></tr><tr>
											<td width='35%' class='capitalize' valign='top' align='left'>Email:</td>
											<td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='email'  value=''  /></td></tr><tr>
											<td width='35%' class='capitalize' valign='top' align='left'>Alternate Email:</td>
											<td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='aemail'  value=''  /></td></tr><tr>
											<td width='35%' class='capitalize' valign='top' align='left'>Phone:</td>
											<td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='phone'  value=''  /></td></tr>
											<tr>
											<td width='35%' class='capitalize'  valign='top' align='left'>Status:</td>
											<td width='65%' class='capitalize'><input name="status" type="radio" id="rstatus" value="ACTIVE" checked="checked" />&nbsp;ACTIVE&nbsp;<input name="status" type="radio" id="rstatus" value="INACTIVE" />&nbsp;INACTIVE&nbsp;</td>
											</tr>
										<tr>
										<td class='capitalize' width='35%'>&nbsp;</td>
										<td width='65%' class='capitalize'><input name='Submit' id='Submit' class="btn btn-danger" type='submit' value='Add' /></td></tr></table></form>
	          	                     
	          	                     
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

<script type="text/javascript">
$(document).ready(function () {
    "use strict";
    var options = {};
    options.ui = {
        container: "#pwd-container",
        showVerdictsInsideProgressBar: true,
        viewports: {
            progress: ".pwstrength_viewport_progress"
        }
    };
    options.common = {
        debug: true,
        onLoad: function () {
            $('#messages').text('Start typing password');
        }
    };
    $(':password').pwstrength(options);
});
</script>


</body>
</html>