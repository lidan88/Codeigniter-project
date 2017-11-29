<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <!-- Title and other stuffs -->
  <title>Login - MacAdmin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="">

  <!-- Stylesheets -->
 <link type="text/css" href="/style/bootstrap.css" rel="stylesheet">
  <link type="text/css" href="/style/font-awesome.css" rel="stylesheet">
  <link type="text/css" href="/style/style.css" rel="stylesheet">
  <!--<link href="/style/bootstrap-responsive.css" rel="stylesheet">-->
  
  <!-- HTML5 Support for IE -->
  <!--[if lt IE 9]>
  <script src="/js/html5shim.js"></script>
  <![endif]-->

  <!-- Favicon -->
  <link rel="shortcut icon" href="/img/favicon/favicon.png">
<style>
	BODY{
		background-image: none; background-color: white;
	}

	.form-login {
		max-width: 370px;
		padding: 15px;
		margin: 0 auto;
		
		background: #fefefe; /*#f5f5f5;*/
		border: 1px solid #d5d5d5;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		border-radius: 5px;
		box-shadow:  0px 0px 2px #dadada, inset 0px -3px 0px #e6e6e6;

	}
	
	.logo2{
		margin: 0 auto;
		max-width: 500px;
		padding: 15px;
		
	}
	
	input{
		border: 1px solid #b8d2e1;
		outline-color: #b8d2e1;
	}
	
	.copyright{
		max-width: 370px;
		padding: 15px;
		margin: 0 auto;
	}
	
	@media (max-width: 480px) {
				
		BODY{
			padding-top: 5px;
		}
		
		.form-login {
			padding: 0px;
			background: none;
			border: none;
			box-shadow: none;
			border-radius: 0px;
		}
		
		.container{
			margin: 0px;
			padding: 0px;
		}
		
		.copyright{
			padding: 0px;
			text-align: left;
			line-height: 30px;
		}
		
		.img{
			width: 200px;
			margin-left: 20%;
		}
		
		.logo2{
			margin: 0px;
			width: 100%;
			padding: 0px;
			padding-left: 10px;
		}
		
		
	}
			
</style>
</head>

<body>


<div class="container">
	<div class="logo2">
		<img class="img" src="/img/continuitypro.png" alt="" />
	</div>
	<div class="form-login">
	    	<? if(isset($_GET['error'])){?>
		    <div class="alert alert-danger">
		    	Please enter a valid user/pass
		    </div>
		    <? } ?>
			  <h2>Forgot Password</h2>
			  <p>Please enter your email address and we'll send you instructions on how to rest your password</p>
		      <!-- Login form -->
		      <form style="margin-top: 10px;" method="post" action="/super_admin/auth/forget/">
		        <!-- Email -->
		        <div class="form-group">
		          <label  for="inputEmail">Username</label>
		          <input type="text" name="username" class="form-control" id="inputEmail" placeholder="Email">
		        </div>
		        
		        <!-- Remember me checkbox and sign in button -->
		        <div class="">
					<button type="submit" class="btn btn-block btn-lg btn-primary">Submit</button>
					<!--<button type="reset" class="btn btn-default">Reset</button>-->
				</div>
		      </form>
			  <p style='margin-top:15px;' align='center'>
				<a href="/super_admin/auth">Back to Login</a>	
			  </p>
		  
	</div>
	<div class="copyright">
	&copy; Continuity Innovations 2014.
	</div>
	  

</div> 

</body>
</html>