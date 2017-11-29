<!DOCTYPE html>
<html lang="en">
<head>
 <? include(APPPATH."views/head.inc.php"); ?>
 <script src="/js/jquery.easypiechart.min.js"></script>
 <script>
 $(function() {
 	$('.chart').easyPieChart({
 		animate: 2000,
 		lineWidth:5,
 		trackColor:'#cccccc',
 		/*easing: 'easeOutBounce',*/
 		onStep: function(from, to, percent) {
 			$(this.el).find('.percent').text(Math.round(percent));
 		}
 	});
 	var chart = window.chart = $('.chart').data('easyPieChart');
 	
 	/*$('.js_update').on('click', function() {
 		chart.update(Math.random()*200-100);
 	});*/
 });
 </script>
 
 <style>
 .stats_item{
 	min-width: 200px;
 	text-align: center;
 }
 .chart {
   position: relative;
   display: inline-block;
   width: 110px;
   height: 70px;
   margin-top: 50px;
   margin-bottom: 50px;
   text-align: center;
 }
 .chart canvas {
   position: absolute;
   top: 0;
   left: 0;
 }
 .percent {
   display: inline-block;
   line-height: 110px;
   z-index: 2;
 }
 .percent:after {
   content: '%';
   margin-left: 0.1em;
   font-size: .8em;
 }
 .angular {
   margin-top: 100px;
 }
 .angular .chart {
   margin-top: 0;
 }
 
 </style>
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
          <!-- Divider -->
          <span class="divider">/</span> 
          <a href="#" class="bread-current">Dashboard</a>
        </div>

        <div class="clearfix"></div>

      </div>
      <!-- Page heading ends -->

	    <!-- Matter -->
	    <div class="matter">
	        <div class="container">
	
					            
	          <div class="row">
	              <!-- Task widget -->
	              <div class="pull-left stats_item">
	              	<span class="chart" data-percent="86" data-bar-color="green">
	              		<span class="percent"></span>
	              	</span>
	              	<p class="text-center"># Plans or completion </p>
	              </div>
	              <div class="pull-left stats_item">
	          		<span class="chart" data-percent="70"  data-bar-color="red">
	          			<span class="percent"></span>
	          		</span>
	          		<p class="text-center">Employees </p>
	              </div>
	              <div class="pull-left stats_item">
	              	<span class="chart" data-percent="50"  data-bar-color="orange">
	              		<span class="percent"></span>
	              	</span>
	              	<p class="text-center">Tasks </p>
	              </div>
	              <div class="pull-left stats_item">
	              	<span class="chart" data-percent="60"  data-bar-color="blue">
	              		<span class="percent"></span>
	              	</span>
	              	<p class="text-center">Incidents </p>
	              </div>
	              <div class="pull-left stats_item">
	              	<span class="chart" data-percent="20"  data-bar-color="purple">
	              		<span class="percent"></span>
	              	</span>
	              	<p class="text-center">Locations </p>
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