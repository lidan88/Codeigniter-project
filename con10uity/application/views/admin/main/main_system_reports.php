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
		  <a href="#" class="bread-current">System Generated Reports </a>
		  
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
						<form method="post" action="/admin/sys_reports/show/">
							<input type="hidden" name="set" value="1" />
							<table class="table" style="width: 700px;">
								<tr>
									<td>Report</td>
									<td>
										<select class="form-control" name="report_id" id="report_id">
											<option value="1">RTO GAP Analysis</option>
											<option value="2">RPO GAP Analysis</option>
											<option value="3">Qualitative and Quantitative Impacts Over Time</option>
											<option value="4">Impacts Over Time</option>
											<option value="5">Required Resources Over Time</option>
											<option value="6">Risk Assessment</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>Title:</td>
									<td>
										<input type="text" name="title" id="title" class="form-control" />
									</td>
								</tr>
								<tr>
									<td>Header:</td>
									<td>
										<textarea name="header" id="header" class="form-control"></textarea>
									</td>
								</tr>
								<tr>
									<td>Footer:</td>
									<td>
										<textarea name="footer" id="footer" class="form-control"></textarea>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<button class="btn btn-success">View Report</button>
									</td>
								</tr>
							</table>
						</form>
						<!--<div class="pull-left well">
							<a href="/admin/sys_reports/show/1/"><h3>RTO GAP Analysis</h3></a>
						</div>
						
						<div class="pull-left well" style="margin-left: 20px;">
							<a href="/admin/sys_reports/show/2/"><h3>RPO GAP Analysis</h3></a>
						</div>
						
						<div class="pull-left well" style="margin-left: 20px;">
							<a href="/admin/sys_reports/show/3/"><h3>Qualitative and Quantitative Impacts Over Time</h3></a>
						</div>
						
						<div class="pull-left well" style="margin-left: 20px;">
							<a href="/admin/sys_reports/show/4/"><h3>Impacts Over Time</h3></a>
						</div>
						
						<div class="pull-left well" style="margin-left: 20px;">
							<a href="/admin/sys_reports/show/5/"><h3>Required Resources Over Time</h3></a>
						</div>-->
						
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