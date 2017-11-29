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
<? include(APPPATH."views/header.inc.php"); ?>
<!-- Header ends -->

<!-- Main content starts -->

<div class="content">

	<!-- Sidebar -->
	<? include(APPPATH."views/sidebar.inc.php"); ?>
    <!-- Sidebar ends -->

  	<!-- Main bar -->
  	<div class="mainbar">


	<div class="row ng-scope">
	   <div class="col-lg-3 col-sm-6">
	      <!-- START widget-->
	      <div class="panel widget bg-primary">
	         <div class="row row-table">
	            <div class="col-xs-4 text-center bg-primary-dark pv-lg">
	               <em class="icon-cloud-upload fa-3x"></em>
	            </div>
	            <div class="col-xs-8 pv-lg">
	               <div class="h2 mt0">540</div>
	               <div class="text-uppercase">Uploads</div>
	            </div>
	         </div>
	      </div>
	   </div>
	   <div class="col-lg-3 col-sm-6">
	      <!-- START widget-->
	      <div class="panel widget bg-purple">
	         <div class="row row-table">
	            <div class="col-xs-4 text-center bg-purple-dark pv-lg">
	               <em class="icon-globe fa-3x"></em>
	            </div>
	            <div class="col-xs-8 pv-lg">
	               <div class="h2 mt0">50.5
	                  <small>GB</small>
	               </div>
	               <div class="text-uppercase">Quota</div>
	            </div>
	         </div>
	      </div>
	   </div>
	   <div class="col-lg-3 col-md-6 col-sm-12">
	      <!-- START widget-->
	      <div class="panel widget bg-green">
	         <div class="row row-table">
	            <div class="col-xs-4 text-center bg-green-dark pv-lg">
	               <em class="icon-bubbles fa-3x"></em>
	            </div>
	            <div class="col-xs-8 pv-lg">
	               <div class="h2 mt0">50</div>
	               <div class="text-uppercase">Reviews</div>
	            </div>
	         </div>
	      </div>
	   </div>
	   <div class="col-lg-3 col-md-6 col-sm-12">
	      <!-- START date widget-->
	      <div class="panel widget">
	         <div class="row row-table">
	            <div class="col-xs-4 text-center bg-green pv-lg">
	               <!-- See formats: https://docs.angularjs.org/api/ng/filter/date-->
	               <now format="MMMM" class="text-sm">October</now>
	               <br>
	               <now format="d" class="h2 mt0">26</now>
	            </div>
	            <div class="col-xs-8 pv-lg">
	               <now format="EEEE" class="text-uppercase">Sunday</now>
	               <br>
	               <now format="h:mm" class="h2 m0">11:44</now>
	               <now format="a" class="text-muted text-sm">PM</now>
	            </div>
	         </div>
	      </div>
	      <!-- END date widget    -->
	   </div>
	</div>



		
      <!-- Page heading -->
      <div class="page-head" style="width: 100%;">
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

		<? /*
        <!-- Breadcrumb -->
        <div class="bread-crumb pull-right">
          <a href="index.html"><i class="icon-home"></i> Home</a> 
          <!-- Divider -->
          <span class="divider">/</span> 
          <a href="#" class="bread-current">Dashboard</a>
        </div>
		*/ ?>

        <div class="clearfix"></div>

      </div>
      <!-- Page heading ends -->

	    <!-- Matter -->

	    <div class="matter">
        <div class="container">

            
            		<div class="row">
                        <div class="col-md-12"> 
                          <!-- List starts -->
                          <ul class="today-datas">
                            <!-- List #1 -->
                            <li>
                              <!-- Graph -->
                              <div><span id="todayspark1" class="spark"><canvas width="77" height="30" style="display: inline-block; width: 77px; height: 30px; vertical-align: top;"></canvas></span></div>
                              <!-- Text -->
                              <div class="datas-text">12,000 visitors/day</div>
                            </li>
                            <li>
                              <div><span id="todayspark2" class="spark"><canvas width="77" height="30" style="display: inline-block; width: 77px; height: 30px; vertical-align: top;"></canvas></span></div>
                              <div class="datas-text">30,000 Pageviews</div>
                            </li>
                            <li>
                              <div><span id="todayspark3" class="spark"><canvas width="77" height="30" style="display: inline-block; width: 77px; height: 30px; vertical-align: top;"></canvas></span></div>
                              <div class="datas-text">15.66% Bounce Rate</div>
                            </li>
                            <li>
                              <div><span id="todayspark4" class="spark"><canvas width="77" height="30" style="display: inline-block; width: 77px; height: 30px; vertical-align: top;"></canvas></span></div>
                              <div class="datas-text">$12,000 Revenue/Day</div>
                            </li> 
                            <li>
                              <div><span id="todayspark5" class="spark"><canvas width="250" height="30" style="display: inline-block; width: 250px; height: 30px; vertical-align: top;"></canvas></span></div>
                              <div class="datas-text">15,000000 visitors till date</div>
                            </li>                                                                                                              
                          </ul> 
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