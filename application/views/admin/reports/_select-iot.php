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
						<h2>Impacts Over Time</h2>
					</div>
				</div>
				<br />
				<form method="post" action="/admin/sys_reports/show/4">
					<input type="hidden" name="output" value="pdf" />
					<div class="row">
					<div class="col-md-12">
						<table class="table">
							<tr>
								<td width="25%">
								Type:
								</td>
								<td>
									<select class="form-control" id="type" name="type" onchange="getTimeFrame(this.value)">
										<option value="">Select Type</option>
										<option value="financial">Financial Impact</option>
										<option value="market_share">Market Share Impact</option>
										<option value="reputation">Reputation Impact</option>
										<option value="operational">Operational Impact</option>
										<option value="legal">Legal Impact</option>
									</select>
								</td>
							</tr>
							<tr class="timeframetr hidden">
								<td width="25%">
								Timeframe:
								</td>
								<td>
									<select class="form-control" id="timeframe" name="timeframe">
										<option value="">All</option>
										<option value="0">Day 1</option>
										<option value="1">Day 2</option>
										<option value="2">Day 3</option>
										<option value="3">Day 4</option>
										<option value="4">Day 5</option>
										<option value="5">Week 1</option>
										<option value="6">Week 2</option>
									</select>
								</td>
							</tr>
							<tr class="impacttr hidden">
								<td width="25%">
								Impact:
								</td>
								<td>
									<select class="form-control" id="impact" name="impact">
										<option value="">Select Impact</option>
										<option value="all">All</option>
										<option value="low">LOW</option>
										<option value="medium">MEDIUM</option>
										<option value="high">HIGH</option>
										<option value="critical">CRITICAL</option>
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

function getTimeFrame(impactType)
{
	$.post("/admin/sys_reports/getImpactColumnDetails/",{impact:impactType},function(data){
		var parts = data.split('|');
		var col = parts[0].split(',');
		var row = parts[1].split(',');
		var val = parts[2].split(',');
		
		if(typeof col[0] !== 'undefined')
			delete col[0];
		
		$("#timeframe").html("<option value=''>Any</option>");
		for (var x in col) {
			$("#timeframe").append('<option value="'+col[x]+'">'+col[x]+'</option>');
		}
		
		$(".timeframetr").removeClass("hidden");
		
		$("#impact").html("<option value=''>Any</option>");
		for (var x in val) {
			$("#impact").append('<option value="'+val[x]+'">'+val[x]+'</option>');
		}
		
		$(".impacttr").removeClass("hidden");
		
		//console.log(col);
		//console.log(data);
	});
}
</script>

</body>
</html>