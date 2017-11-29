<?
$limit=30;
$sort=isset($_REQUEST['sort'])?$_REQUEST['sort']:"";
$ord=isset($_REQUEST['ord'])?$_REQUEST['ord']:"ASC";
$page=isset($_REQUEST['page'])?$_REQUEST['page']:0;
$submit=isset($_REQUEST['Submit'])?$_REQUEST['Submit']:"";
$search=isset($_REQUEST['search'])?$_REQUEST['search']:"";

$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
$href1=$_SERVER['PHP_SELF']."?";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Threat analysis</title>

<!--HEAD-->
<? include(APPPATH."views/admin/head.inc.php"); ?>
<script>
function do_search(search)
{
	$('.items').removeClass("hidden");
	$('.items').each(function(){
		var str = $(this).children().first().html();
		var patt = new RegExp(search,'i');
		var res = patt.test(str);
		if(res==false)
			$(this).addClass("hidden");
		//console.log(res);
	});
}

function confirm_delete(plink)
{
	if(confirm("Are you sure you want to delete?"))
	{
		top.location=plink;	
	}
}

function submit_for_approval(obj)
{
	$.post("/admin/risk_assessment/submit_for_approval/<?=$risk_assessment_id?>/",{},function(ret){
		$(obj).html("Submitted").attr("disabled","disabled");
	});
}

function approve(obj)
{
	$.post("/admin/risk_assessment/approve/<?=$risk_assessment_id?>/",{},function(ret){
		$(obj).html("Approved").attr("disabled","disabled");
	});
}

function calculate_weight(threat_id)
{
	var weight_matrix = {};
	weight_matrix['LOW'] = {};
	weight_matrix['LOW']['LOW']="LOW";
	weight_matrix['LOW']['MEDIUM']="LOW";
	weight_matrix['LOW']['HIGH']="MEDIUM";

	weight_matrix['MEDIUM'] = {};
	weight_matrix['MEDIUM']['LOW']="LOW";
	weight_matrix['MEDIUM']['MEDIUM']="MEDIUM";
	weight_matrix['MEDIUM']['HIGH']="HIGH";
	
	weight_matrix['HIGH'] = {};
	weight_matrix['HIGH']['LOW']="MEDIUM";
	weight_matrix['HIGH']['MEDIUM']="HIGH";
	weight_matrix['HIGH']['HIGH']="CRITICAL";

	//console.log($('#t'+threat_id+'_01').val()+" - "+$('#t'+threat_id+'_02').val());
	var weight = weight_matrix[$('#t'+threat_id+'_01').val()][$('#t'+threat_id+'_02').val()];
	
	$("#weight_"+threat_id).html(weight);
	$("#weight_"+threat_id).parent().removeClass("alert-success").removeClass("alert-danger").removeClass("alert-warning");
	
	if(weight=="CRITICAL" || weight=="HIGH")
	{
		$("#weight_"+threat_id).parent().addClass("alert-danger");
	}
	else if(weight=="MEDIUM")
	{
		$("#weight_"+threat_id).parent().addClass("alert-warning");
	}
	else if(weight=="LOW") 
	{
		$("#weight_"+threat_id).parent().removeClass("alert-danger").addClass("alert-success");
	}
}

function update_threat_analysis()
{
	$.post("/admin/threat_analysis/do_update/",$('#form_analysis').serialize(),function(){
		console.log("updated..");
	});
}

var timer=null;
$(document).ready(function(){
	$(".ref").tooltip();

	timer=setInterval('update_threat_analysis();', 60*1000);
	var dp = $('.datepicker').datepicker().on('changeDate', function(ev) {
	  dp.datepicker('hide');
	});
	$('.timepicker').timepicker();
	$(".chosen-select").chosen();
});
</script>
<style>
.myinput{
	border: none;
	background: none;
}

.rowDark{
	background-color: white;
}


</style>
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
		  <a href="/admin/risk_assessment/main/">Risk Assessment </a>
		  <span class="divider">/</span> 
		  <a href="#" class="bread-current"> Threat Assessment</a>
		</div>
		
		<div class="clearfix"></div>
		
		</div>
		<!-- Page heading ends -->
		
		<!-- Matter -->
		<form id="form_analysis" method="post" action="/admin/threat_analysis/do_update/">
			<input type="hidden" name="risk_assessment_id" value="<?=$risk_assessment_id?>" />
			<div class="matter">
			<div class="container">
			
				<div class="row">
					<div class="col-md-12">
						<div class="widget">
					        
					        
					        <div class="widget-head">
	                          <div class="pull-left">Edit Risk Assessment</div>
	                          <div class="pull-right">
	                          </div>
	                          <div class="clearfix"></div>
	                        </div>
	        
	                        <div class="widget-content">
                    	       <table class="table table-bordered table-striped table-hover content" style="width:100%;" cellpadding="0" cellspacing="0">
                    	          <tr>
                    				<td width='20%' class='capitalize' valign='top' align='left'>Name:</td>
                    				<td width='80%' class='capitalize'><input  class='form-control ' type='text' size=40 name='name' value='<?=$risk_assessment_detail['name']?>'  /></td>
                    			</tr>
                    			<tr>
                    				<td class='capitalize' valign='top' align='left'>Description:</td>
                    				<td class='capitalize'><textarea  class='form-control'  rows=5 cols=35 name='description' id='description'><?=$risk_assessment_detail['description']?></textarea></td>
                    			</tr>
                    		</table>
	                        </div>
					        
					        <div class="widget-head">
			                  <div class="pull-left">
			                  	<input type="submit" class="btn btn-success" name="submit" value="Save" />
			                  	<? if($risk_assessment_detail['status']=='In Progress'){ ?>
			                  	<a href="javascript:;" onclick="submit_for_approval(this);" class="btn btn-mini btn-danger">Submit For Approval</a>
			                  	<? }else if($risk_assessment_detail['status']=='Pending Approval'){ ?>
			                  	<a href="javascript:;" onclick="approve(this);" class="btn btn-mini btn-success">Approve</a>
			                  	<? } ?>
			                  	<a href="/admin/risk_assessment/main/" class="btn btn-mini btn-primary">Go Back</a>
			                  	
			                  </div>
			                  <div class="pull-right">
			                  		<input type="text" class="form-control" style="width: 300px;" name="search" value="" onkeyup="do_search(this.value)" placeholder="Search" />
			                  	<!--<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
			                    <a href="#" class="wclose"><i class="icon-remove"></i></a>-->
			                  </div>
			                  
			                  
			                  <div class="clearfix"></div>
			                </div>
			
			                <div class="widget-content">
			                	<table class="table table-bordered">
			                	    <thead>
			                	    <tr>
										<td width="30%" class="rowHeader"><?                 
							                if(is_array($list))
							                {
								                if ($sort=='threat_id') {
								                	if ($ord=='ASC') {
								                		echo  '<a href="'.$href1.'&sort=threat_id&ord=DESC&Submit=Search">';
								                	} else {
								                		echo  '<a href="'.$href1.'&sort=threat_id&ord=ASC&Submit=Search">';
								                	}
								                } else {
								                	echo  '<a href="'.$href1.'&sort=threat_id&ord=ASC&Submit=Search">';
								                }
							                }
							                else {
							                	echo  '<a href="#">';
							                }
							                ?>Threat</a></td>
										<td class="rowHeader"><?                 
							               if(is_array($list))
							               {
								                if ($sort=='likelihood') {
								                	if ($ord=='ASC') {
								                		echo  '<a href="'.$href1.'&sort=likelihood&ord=DESC&Submit=Search">';
								                	} else {
								                		echo  '<a href="'.$href1.'&sort=likelihood&ord=ASC&Submit=Search">';
								                	}
								                } else {
								                	echo  '<a href="'.$href1.'&sort=likelihood&ord=ASC&Submit=Search">';
								                }
							                }
							                else {
							                	echo  '<a href="#">';
							                }
							                
							                ?>Likelihood</a>
							                <a href="#" data-toggle="tooltip" class="ref" title="1-3"><span class="icon-info-sign"></span></a>
							                </td>
									          <td class="rowHeader"><?                 
						                    if(is_array($list))
						                    {
						                        if ($sort=='impact') {
							                    	if ($ord=='ASC') {
							                    		echo  '<a href="'.$href1.'&sort=impact&ord=DESC&Submit=Search">';
							                    	} else {
							                    		echo  '<a href="'.$href1.'&sort=impact&ord=ASC&Submit=Search">';
							                    	}
							                    } else {
							                    	echo  '<a href="'.$href1.'&sort=impact&ord=ASC&Submit=Search">';
							                    }
						                    }else {
						                    	echo  '<a href="#">';
						                    }
						                    
						                    ?>Impact</a>
						                    <a href="#" data-toggle="tooltip" class="ref" title="1-3"><span class="icon-info-sign"></span></a>
						                    </td>
						              		<td width="30%" class="rowHeader"><?                 
							                if ($sort=='weight') {
							                	if ($ord=='ASC') {
							                		echo  '<a href="'.$href1.'&sort=weight&ord=DESC&Submit=Search">';
							                	} else {
							                		echo  '<a href="'.$href1.'&sort=weight&ord=ASC&Submit=Search">';
							                	}
							                } else {
							                	echo  '<a href="'.$href1.'&sort=weight&ord=ASC&Submit=Search">';
							                }
							                ?>Weight</a></td>
			                	    </tr>
			                	    </thead>
			                	    <tbody>
			                	    	<tr>
			                	    		<th colspan="8" class="text-center alert alert-warning"><a href="javascript:$('#riskMatrixModal').modal('show');">Click to view Risk Matrix</a></th>
			                	    	</tr>
			                	    <?                
			                	        if($sort=='')
			                	        {
				                	        if(is_array($threats) and count($threats)>0)
				                	        {
				                	            foreach($threats as $group_name => $items)
				                	            {
				                	            	?>
				                	            	<tr>
				                	            		<th colspan="8"><h3><?=$group_name?> Threats</h3></th>
				                	            	</tr>
				                	            	<?
				                	            	foreach ($items as $key => $row) 
				                	            	{
				                	            	
				                	            		$rv = array('LOW','LOW','LOW');
				                	            		if(isset($list[$row['threat_id']]))
				                	            	 	{
				                	            	 		$rv = array($list[$row['threat_id']]['likelihood'],
				                	            	 					$list[$row['threat_id']]['impact'],
				                	            	 					$list[$row['threat_id']]['weight']);
				                	            	 					
				                	            	 	}
				                	        ?>
				                	            <tr class="items" id="item_no_<?=$row['threat_id']?>">
													<td align="left" valign="top"><?=$row['name']?></td>
													<td align="left" valign="top" class="alert rowDark">
														<select class="chosen-select form-control" style="width: 200px;" name="threat[<?=$row['threat_id']?>][0]" id="t<?=$row['threat_id']?>_01" onchange="calculate_weight(<?=$row['threat_id']?>);">
															<option value="LOW" <?=($rv[0]=='LOW')?'selected="selected"':'';?>>Low</option>
															<option value="MEDIUM" <?=($rv[0]=='MEDIUM')?'selected="selected"':'';?>>Medium</option>
															<option value="HIGH" <?=($rv[0]=='HIGH')?'selected="selected"':'';?>>High</option>
														</select>
													</td>
													<td align="left" valign="top" class="alert rowDark">
														<select class="chosen-select form-control" style="width: 200px;"  name="threat[<?=$row['threat_id']?>][1]" id="t<?=$row['threat_id']?>_02" onchange="calculate_weight(<?=$row['threat_id']?>);">
															<option value="LOW" <?=($rv[1]=='LOW')?'selected="selected"':'';?>>Low</option>
															<option value="MEDIUM" <?=($rv[1]=='MEDIUM')?'selected="selected"':'';?>>Medium</option>
															<option value="HIGH" <?=($rv[1]=='HIGH')?'selected="selected"':'';?>>High</option>
														</select>
													</td>
													<td align="left" valign="top" class="alert <?=get_weight_class($rv[2])?>">
														<span id="weight_<?=$row['threat_id']?>"><?=$rv[2]?></span></td>
				                	            </tr>
				                	        <? 	
				                	        		}
				                	            }
				                	        }	
				                	        else
				                	        {
				                	        ?>
				                	            <tr>
				                	                <td>
				                	                    <div class='b1 txt padd5'><strong>No items found!</strong></div>
				                	                </td>
				                	            </tr>
				                	        <?
				                	        }	
			                	        }
			                	        else {
			                	        
			                	        	foreach ($list as $key => $row) 
	                	        	    	{
	                	        	    		$rv = array('LOW','LOW','LOW');
	                	        	    	 	if(isset($list[$row['threat_id']]))
	                	        	    	 	{
	                	        	    	 		$rv = array($list[$row['threat_id']]['likelihood'],
	                	        	    	 					$list[$row['threat_id']]['impact'],
	                	        	    	 					$list[$row['threat_id']]['weight']);
	                	        	    	 	}
			                	        	?>
			                	        	    <tr class="items" id="item_no_<?=$row['threat_id']?>">
			                	        			<td align="left" valign="top"><?=$row['threat_name']?></td>
			                	        			<td align="left" valign="top" class="alert rowDark">
			                	        				<select class="chosen-select form-control"  name="threat[<?=$row['threat_id']?>][0]" id="t<?=$row['threat_id']?>_01" onchange="calculate_weight(<?=$row['threat_id']?>);">
			                	        					<option value="LOW" <?=($rv[0]=='LOW')?'selected="selected"':'';?>>Low</option>
			                	        					<option value="MEDIUM" <?=($rv[0]=='MEDIUM')?'selected="selected"':'';?>>Medium</option>
			                	        					<option value="HIGH" <?=($rv[0]=='HIGH')?'selected="selected"':'';?>>High</option>
			                	        				</select>
			                	        			</td>
			                	        			<td align="left" valign="top" class="alert rowDark">
			                	        				<select class="chosen-select form-control"  name="threat[<?=$row['threat_id']?>][1]" id="t<?=$row['threat_id']?>_02" onchange="calculate_weight(<?=$row['threat_id']?>);">
			                	        					<option value="LOW" <?=($rv[1]=='LOW')?'selected="selected"':'';?>>Low</option>
			                	        					<option value="MEDIUM" <?=($rv[1]=='MEDIUM')?'selected="selected"':'';?>>Medium</option>
			                	        					<option value="HIGH" <?=($rv[1]=='HIGH')?'selected="selected"':'';?>>High</option>
			                	        				</select>
			                	        			</td>			                	        			
			                	        			<td align="left" valign="top" class="alert alert-success"><span id="weight_<?=$row['threat_id']?>"><?=$rv[2]?></span></td>
			                	        	    </tr>
			                	        	<? 	
			                	        	}
			                	        	
			                	        
			                	        }
			                	        ?>
			                	        </tbody>
			                	    </table>
			                	
			                		
			                </div>
			                <div class="widget-foot">
			               		<input type="submit" class="btn btn-success btn-block" name="submit" value="Risk Management" />
			                </div>
			            </div>
			        </div>
			    </div>
				
			
				
			
			</div>
		</div>
		</form>
	</div>
	
	<div class="clearfix"></div>
</div>

<!-- Footer starts -->
<?  include(APPPATH."views/footer.inc.php"); ?>
<!-- Footer/Ends-->

<!-- Modal -->
<div class="modal fade" id="riskMatrixModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog"style="width: 900px;">
    <div class="modal-content"style="width: 900px;">
      <div class="modal-header"style="width: 898px;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Risk Matrix</h4>
      </div>
      <div class="modal-body" style="width: 890px;">
        <center>
        <img style="display: block;" src="/img/risk-matrix.png" alt="" />
        </center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$(".items").each(function(index,itm){
			var threat_id = parseInt(itm.id.replace('item_no_',''));
			calculate_weight(threat_id);
		});
			
	});
</script>

</body>
</html>