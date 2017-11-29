<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Reports</title>

<!--HEAD-->
<? include(APPPATH."views/admin/head.inc.php"); ?>
<script>
</script>
</head>
<body>
<!--HEADER-->
<? include(APPPATH."views/admin/header.inc.php"); ?>


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
		  <a href="index.html"><i class="icon-home"></i> Home</a> 
		  <!-- Divider -->
		  <span class="divider">/</span> 
		  <a href="#" class="bread-current">Reports </a>
		  
		</div>
		
		<div class="clearfix"></div>
		
		</div>
		<!-- Page heading ends -->
		
		<!-- Matter -->
		
		<div class="matter">
			<div class="container">
			
				<div class="row">
					<div class="col-md-12">
						<h2>What would you like to do?</h2>
					</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-12">
						
						<div class="pull-left well">
							<a href="/admin/reports/view/"><h3>Print User Reports</h3></a>
						</div>
						
						<div class="pull-left" style="padding-top: 10px; padding-left: 10px;">
							<h3>OR</h3>
						</div>
						
						<div class="pull-left well" style="padding-top: 10px; padding-left: 10px; margin-left: 10px;">
							<a href="/admin/sys_reports/main/"><h3>System Generated Reports</h3></a>
						</div>
						
						<div class="pull-left" style="padding-top: 10px; padding-left: 10px;">
							<h3>OR</h3>
						</div>
						
						<div class="pull-left well" style="margin-left: 20px;">
							<a href="/admin/reports/step/1"><h3>Create a New Report</h3></a>
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