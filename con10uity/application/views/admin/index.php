<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Panel</title>
<!--HEAD-->
<? include(APPPATH."views/admin/head.inc.php"); ?>
</head>
<body>
<!--HEADER-->
<? include(APPPATH."views/admin/header.inc.php"); ?>


<div class="container-fluid">
<div class="row-fluid">
	<div class="col-lg-12">
	    <center>
        	<div class="well" style="background-color:#fff; width: 500px;">
            
            
                        <form class="text-left form-horizontal" role="form" action="/admin/post/login/" method="post">
            	<legend>Admin Panel</legend>
              <div class="form-group">
                <label class="control-label" for="inputUser">User</label>
                <div class="controls">
                  <input type="text" id="inputUser" class="form-control" name="user" placeholder="User">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label" for="inputPassword">Password</label>
                <div class="controls">
                  <input type="password" name="password"  class="form-control"  id="inputPassword" placeholder="Password">
                </div>
              </div>
              
              <div class="form-group">
	              <div class="controls">
	                <button type="submit" class="btn btn-default">Sign in</button>
	              </div>
	            </div>
                  
                
            </form>
        
        </div>
    	</center>
    </div>
</div>
</div> <!-- /container -->

</body>
</html>