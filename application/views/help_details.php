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
  
 .category{
 	width: 370px;
 	float: left;
 }
 
 .title {
 	font-size: 20px;
 }
 
 .description{
 	font-size: 16px;
 	margin-top: 20px;
 }
 .category_question a {
 
 }
 
 .tag{
 	color: #999;
 	font-size: 16px;
 	margin-top: 5px;
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

      <!-- Page heading ends -->

	    <!-- Matter -->
	    <div class="matter">
    	    <div class="container">
	    		<div class="title">
	    			<div class="pull-left">
	    				<h2 style="color: #222222;"><?=$item['title']?></h2>
	    			</div>
	    			<!--<div class="pull-right">
	    				<a href="javascript:history.go(-1);" class="btn btn-primary">Back</a>
	    			</div>-->
	    			<div class="clearfix"></div>
	    		</div>
	    		<!--<div class="tag">Tags: <?=strtoupper($item['tags'])?></div>-->
	    		<div class="description"><?=nl2br($item['description'])?></div>
	    		<br />
	    		<div>
	    			<a href="javascript:history.go(-1);" class="btn btn-default"><i class="icon icon-chevron-left"></i> Back</a>
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