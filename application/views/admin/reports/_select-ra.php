<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Reports - Risk Assessment</title>

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
						<h2>Impacts Over Time</h2>
					</div>
				</div>
				<br />
				<form method="post" action="/admin/sys_reports/show/6/">
					<input type="hidden" name="output" value="pdf" />
					<div class="row">
					<div class="col-md-12">
						<table class="table">
							<tr>
								<td width="25%">
									Risk Assessment:
								</td>
								<td>
									<select class="form-control" id="risk_assessment_id" name="risk_assessment_id">
										<option value="">Select Type</option>
										<?
											foreach ($list as $risk_id => $value) 
											{
												?>
												<option value="<?=$value['risk_assessment_id']?>"><?=$value['name']?></option>
												<?
											}
										?>
									</select>
								</td>
							</tr>
							<tr class="impacttr">
								<td width="25%">
									Group:
								</td>
								<td>
									<select class="form-control" id="group" name="group">
										<option value="">Any</option>
										<?
											foreach ($groups as $key => $value) 
											{
												?>
												<option value="<?=$value['group']?>"><?=$value['group']?></option>
												<?
											}
										?>
									</select>
								</td>
							</tr>
							<tr class="impacttr">
								<td width="25%">
									Weight:
								</td>
								<td>
									<select class="form-control" id="weight" name="weight">
										<option value="">Any</option>
										<option value="low">LOW</option>
										<option value="medium">MEDIUM</option>
										<option value="high">HIGH</option>
									</select>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<button class="btn btn-primary" type="submit">Show Report</button>
								</td>
							</tr>
						</table>
			        </div>
			    </div>
				</form>
			</div>
		</div>
	</div>
	
	<div class="clearfix"></div>
</div>

<!-- Footer starts -->
<?  include(APPPATH."views/footer.inc.php"); ?>
<!-- Footer/Ends-->

<script type="text/javascript">
function showReport()
{
	//alert(process_id);
	top.location = "/admin/sys_reports/show/4/"+type+"/?output=pdf";
}
</script>

</body>
</html>