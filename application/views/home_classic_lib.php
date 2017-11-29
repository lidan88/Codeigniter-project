<!DOCTYPE html>
<html lang="en">
<head>
 <? include(APPPATH."views/head.inc.php"); ?>
 <style>
 	.menu_item{
 		margin-right: 30px;
 		min-width: 200px;
 		min-height: 100px;
 	}
 </style>
 <script src="/js/app/home.js" type="text/javascript"></script>
 
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
  	<div class="mainbar" style="background-color: white; background-image: none;">

      <!-- Page heading -->
      <div class="page-head">
			<div class="row pull-left" style="width: 400px; padding-top: 5px;">
				<div class="col-md-12">
					<select class="form-control" onchange="select_where(this.value)">
						<option value="">What do you want to do?</option>
					      <?
					      	foreach($activatedLibs as $lib)
					      	{
					      	?>
					      	<option value="<?=$lib['type']?>:<?=$lib['id']?>:<?=$lib['is_system']?>:<?=$lib['name']?>">Add or update <?=$lib['name']?></option>
					      	<?
					      	}	
					      ?>
                  </select>
	        		<!--<h2 class="pull-left"><i class="icon-list-alt"></i> Widgets</h2>-->
	        	</div>
			</div>
			<div class="pull-left" style="padding-top: 5px;">
				&nbsp;<a class="btn btn-primary" href="?v=default">Switch to My View</a>
			</div>

        <!-- Breadcrumb -->
        <div class="bread-crumb pull-right">
          <a href="index.html"><i class="icon-home"></i> Home</a> 
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
				<div class="col-md-12">
				
			<?
				$ctr=0;
				foreach($activatedSubLibs as $lib)
				{
					$ctr++;
				?>
				<div class="pull-left menu_item">	
					<a href="javascript:;" onclick="select_where('<?=$lib['type']?>:<?=$lib['id']?>:<?=$lib['is_system']?>:<?=$lib['name']?>')">
					<?php if ($lib['logo'] !='') {?>
						<img src="/user_data/<?=$lib['logo']?>" alt="" />
					<?php } else {?>
						<img src="/img/<?=$lib['image']?>_48.png" alt="" />
					<?php }?>
					<?=$lib['name']?>
					</a>
				</div>
				<? if($ctr%4==0){ ?>
					<div class="clearfix"></div>
				<? } ?>	
			<? } ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
		 			<div class="pull-left menu_item">	
						<a href="javascript:history.back();"><img src="/img/Actions-go-previous-icon.png" alt="" />Previous Menu</a>
						</a>
					</div>
				</div>
			</div>
	  		<!--
	  		<div class="row">
	  			<div class="col-md-12">
	  				
	  				<div class="pull-left menu_item">
	  					<img src="/img/Library_48.png" alt="" />
	  					Library
	  				</div>
	  				<div class="pull-left menu_item">
	  					<img src="/img/RiskAssessment_48.png" alt="" />
	  					Risk Assessment
	  				</div>
	  				<div class="pull-left menu_item">
	  					<img src="/img/BIA_48.png" alt="" />
	  					Business Impact Analysis
	  				</div>
	  				
	  				<div class="pull-left menu_item">
	  					<img src="/img/TeamBuilder_48.png" alt="" />
	  					Teams
	  				</div>
	  				
	  			</div>
	  		</div>
	  		<div class="row">
	  			<div class="col-md-12">
	  				<div class="pull-left menu_item">
	  					<a href="/admin/call_chain/main/">
		  					<img src="/img/CallChain_48.png" alt="" />
		  					Call Chain
	  					</a>
	  				</div>
	  				
	  				<div class="pull-left menu_item">
	  					<img src="/img/Dependencies_48.png" alt="" />
	  					Dependencies
	  				</div>
	  				
	  				<div class="pull-left menu_item">
	  					<img src="/img/RecoveryStrategy_48.png" alt="" />
	  					Recovery Strategies
	  				</div>
	  				
	  				<div class="pull-left menu_item">
	  					<a href="/admin/plans/main/">
	  						<img src="/img/PlanBuilder_48.png" alt="" />
	  						Plan
	  					</a>
	  				</div>
	  			</div>
	  		</div>
	  		<div class="row">
	  			<div class="col-md-12">

					<div class="pull-left menu_item">
						<img src="/img/DocManager_48.png" alt="" />
						Document Manager
					</div>
					
					<div class="pull-left menu_item">
						<img src="/img/IncidentAssessment_48.png" alt="" />
						Incident Manager
					</div>
					
					<div class="pull-left menu_item">
						<img src="/img/TrainingAssessment_48.png" alt="" />
						Training Assessment
					</div>
					
					<div class="pull-left menu_item">
						<img src="/img/TestingAssessment_48.png" alt="" />
						Testing Assessment
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">

					
					<div class="pull-left menu_item">
						<a href="/admin/reports/main/">
							<img src="/img/Report_48.png" alt="" />
							Report
						</a>
					</div>
					
					<div class="pull-left menu_item">
						<a href="/admin/help/">
							<img src="/img/Help_48.png" alt="" />
							Help Center
						</a>
					</div>
	  				
	  				
	  				
	  			
	  			</div>
	  		</div>
	 		-->
	 
	 
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