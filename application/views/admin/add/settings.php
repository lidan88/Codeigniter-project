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
            <a href="#" class="bread-current">Settings</a>
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
	          	                  <div class="pull-left">Settings</div>
	          	                  <div class="widget-icons pull-right">
	          	                    <!--<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	          	                    <a href="#" class="wclose"><i class="icon-remove"></i></a>-->
	          	                  </div>
	          	                  <div class="clearfix"></div>
	          	                </div>
	          	
	          	                <div class="widget-content">
	          	                  <div class="padd">
	          	
	          	                    <!-- Form starts.  -->
	          	                     <form class='form-horizontal' name='frm_add_users' method='post' action='/admin/settings/do_update/' onSubmit='return form_validation(this);' enctype='multipart/form-data'>
	          	                     	<table class="table table-bordered table-striped table-hover content" style="width:100%;" cellpadding="0" cellspacing="0">
	          	                     	<tr>
	          	                     		<td width='35%' class='capitalize' valign='top' align='left'>Default View:</td>
	          	                     		<td width='65%' class='capitalize'>
	          	                     			<input type="radio" name="default_view" value="0" <?=$item['default_view']=='0'?'checked="checked"':''?>  /> Advanced &nbsp;&nbsp;
	          	                     			
	          	                     			<input type="radio" name="default_view" value="1"  <?=$item['default_view']=='1'?'checked="checked"':''?> /> Classic
	          	                     				
	          	                     		</select>
	          	                     	</tr>
	          	                     	
	          	                     	<tr>
											<td width='35%' class='capitalize' valign='top' align='left'>Timezone:</td>
											<td width='65%' class='capitalize'><select class='form-control' name='timezone' id='timezone'><?
											$res = $this->db->query("select * from `timezones`");
											if($res->num_rows()>0)
											{
												foreach ($res->result_array() as $data)
												{
													echo "<option value='".$data['id']."' ".((isset($item) and $item['timezone']==$data['id'])?'selected="selected"':"").">".$data['timezone'].' - '.$data['name']."</option>";
												}
											}
											?></select>
											</td>
										</tr>
										<tr>
											<td width='35%' class='capitalize' valign='top' align='left'>First name:</td>
											<td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='first_name'  value='<?=$item['first_name']?>'  /></td>
										</tr>
										<tr>
											<td width='35%' class='capitalize' valign='top' align='left'>Last name:</td>
											<td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='last_name'  value='<?=$item['last_name']?>'  /></td>
										</tr>
										<tr>
											<td width='35%' class='capitalize' valign='top' align='left'>Password:</td>
											<td width='65%' class='capitalize'>
												<input  class='form-control' type='password' size='40' id="password" name='password' value='<?=$item['password']?>' />
												
												<div class="row" id="pwd-container">
									                <div class="col-sm-4">
									                    <div class="pwstrength_viewport_progress"></div>
									                </div>
									            </div>
									            <div class="row">
									                <div id="messages" class="col-sm-12"></div>
									            </div>
									       
									       </td>
										</tr>
										<tr>
											<td width='35%' class='capitalize' valign='top' align='left'>Email:</td>
											<td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='email'  value='<?=$item['email']?>'  /></td>
										</tr>
										<tr>
											<td width='35%' class='capitalize' valign='top' align='left'>Alternative Email:</td>
											<td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='aemail'  value='<?=$item['aemail']?>'  /></td>
										</tr>
										<tr>
											<td width='35%' class='capitalize' valign='top' align='left'>Phone:</td>
											<td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='phone'  value='<?=$item['phone']?>'  /></td>
										</tr>
										<tr>
										<td class='capitalize' width='35%'>&nbsp;</td>
										<td width='65%' class='capitalize'>
											<input name='Submit' id='Submit' class="btn btn-danger" type='submit' value='Update' />
										</td>
										</tr>
										</table>
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