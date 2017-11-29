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
 	
 	$("#search").keyup(function( event ) {
 	  //xTriggered++;
 	  //var msg = "Handler for .keyup() called " + xTriggered + " time(s).";
 	  //$.print( msg, "html" );
 	  //$.print( event );
 	}).keydown(function( event ) {
 	  
 	  /*console.log(event.which);
 	  if ( event.which == 13 ) {
 	    event.preventDefault();
 	  }*/
 	  
 	  if(event.which==8)
 	  {
	 	  if($("#search").val().length<=1)
	 	  {
	 	  	top.location = "?search=";
	 	  }
 	  }
 	  
 	}); });
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
 
 .category{
 	width: 370px;
 	float: left;
 	min-height: 200px;
 }
 
 .category_title {
 	font-size: 20px;
 	/*color: #222222;*/
 }
 
 .category_question{
 	
 }
 .category_question a {
 
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

      <!-- Page heading -->
      <div class="page-head" style="width: 100%;">
		<div class="row">
			<form method="get" action="?">
				<div class="input-group" style="margin-top: 5px;">
				  <span style="height: 50px;" class="input-group-addon"><span class="icon icon-search"></span></span>
				  <input type="text" style=" height: 50px;font-size: 20px !important;"  class="form-control" placeholder="How Can We Help?" name="search" id="search" value="<?=$search?>">
				</div>
			</form>
		</div>

        <div class="clearfix"></div>
      </div>
      <!-- Page heading ends -->

	    <!-- Matter -->
	    <div class="matter">
    	    <div class="container">
	    		<?
	    			foreach($categories as $cat)
	    			{
	    				if(!isset($helpByCategory[$cat['id']]))
	    					continue;
	    				?>
	    				<div class="category">
		    				<div class="category_title"><?=$cat['title']?></div>
		    				<?
		    				if(isset($helpByCategory[$cat['id']]))
		    				foreach($helpByCategory[$cat['id']] as $help)
		    				{
		    					?>
		    					<div class="category_question">&raquo; <a href="/admin/help/<?=$help['help_id']?>"><?=ucfirst($help['title'])?></a></div>
		    					<?
		    				}
		    				?>
	    				</div>
	    				<?
	    			}
	    			
	    			if(count($helpByCategory)==0)
	    			{
	    			?>
	    			<div class="alert alert-danger">No results found for your search.Please try with another search.</div>
	    			<?
	    			}
	    		?>
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