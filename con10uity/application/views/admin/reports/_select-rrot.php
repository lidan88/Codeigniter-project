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
				<form method="post" action="/admin/sys_reports/show/5">
					<input type="hidden" name="output" value="pdf" />
			
					<div class="row">
						<div class="col-md-12">
							<h2>Required Resources Over Time</h2>
						</div>
					</div>
					<br />
					<div class="row">
						<div class="col-md-12">
							<table class="table">
								<tr>
									<td width="25%">
									Process Name:
									</td>
									<td>
										<select class="form-control" id="process_id" name="process_id">
											<option value="">Select Process List</option>
											<? foreach ($items as $key => $value) {
												echo '<option value="'.$key.'">'.$value.'</option>';
											} ?>
										</select>
									</td>
								</tr>
								<tr class="">
									<td width="25%">
									Resource Type:
									</td>
									<td>
										<select class="form-control" id="resource" name="resource">
											<option value="">Any</option>
											<?
												list($columns,$resourceTypes,$options) = getGridHeaders($fields['Required Resources Over Time']);
												foreach ($resourceTypes as $key => $resourceType) {
													echo '<option value="'.$resourceType.'">'.$resourceType.'</option>';
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<button type="submit" class="btn btn-primary">Select</button>
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
function selectReport(process_id)
{
	//alert(process_id);
	top.location = "/admin/sys_reports/show/5/"+process_id+"?output=pdf";
}
</script>

</body>
</html>