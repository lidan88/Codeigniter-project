<?
$limit=30;
$sort=isset($_REQUEST['sort'])?$_REQUEST['sort']:"threat_analysis_id";
$ord=isset($_REQUEST['ord'])?$_REQUEST['ord']:"ASC";
$page=isset($_REQUEST['page'])?$_REQUEST['page']:0;
$submit=isset($_REQUEST['Submit'])?$_REQUEST['Submit']:"";

$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
$href1=$_SERVER['PHP_SELF']."?";

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Risk Management</title>

<!--HEAD-->
<? include(APPPATH."views/admin/head.inc.php"); ?>
<script>
$(document).ready(function(){
	
	 $(".autogrow").autoGrow();
	 
	 var dp = $('.datepicker').datepicker({format:'yyyy-mm-dd'}).on('changeDate', function(ev) {
	   dp.datepicker('hide');
	 });
	 
});

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
function follow_up(threat_id)
{
	
	$('#form_task').find("input[name='risk_assessment_id']").val(<?=$risk_assessment_id?>);
	$('#form_task').find("input[name='threat_id']").val(threat_id);
	$('#btn_re_assign').addClass("hidden");
	$('#btn_do_assign').removeClass("hidden");
	
	$('#myModal').modal('show');
}

function re_assign(threat_id)
{
	$('#form_task').find("input[name='risk_assessment_id']").val(<?=$risk_assessment_id?>);
	$('#form_task').find("input[name='threat_id']").val(threat_id);
	$('#btn_do_assign').addClass("hidden");
	$('#btn_re_assign').removeClass("hidden");
	$.post("/admin/tasks/get_task_details_by_threat/",$('#form_task').serialize(),function(ret){
		$('#form_task').find("input[name='title']").val(ret.title);
		$('#form_task').find("textarea[name='description']").html(ret.description);
		$('#form_task').find("input[name='due_by']").val(ret.due_by);
		$('#form_task').find("select[name='priority']").val(ret.priority);
		$('#form_task').find("select[name='assigned_to']").val(ret.assigned_to);
		//$('#description').val(ret.description);
		//alert(ret.description);
		//console.log(ret);
		$('#myModal').modal('show');
	},'json');
	
}

function do_assign()
{
	$('#myModal').modal('hide');
	var threat_id = $('#form_task').find("input[name='threat_id']").val();
	//$('#form_task').get(0).submit();
	$.post("/admin/tasks/do_add/",$('#form_task').serialize(),function(ret){
		//alert(ret);
		$("#follow_up_"+threat_id).html("Assigned");
	});
}

function do_re_assign()
{
	$('#myModal').modal('hide');
	var threat_id = $('#form_task').find("input[name='threat_id']").val();
	//$('#form_task').get(0).submit();
	$.post("/admin/tasks/do_re_assign/",$('#form_task').serialize(),function(ret){
		//alert(ret);
		
		$("#re_assign_"+threat_id).html("Updated");
	});
}

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
		  <a href="/admin/threat_analysis/main/<?=$risk_assessment_id?>/" >Risk Assessment </a>
		  <span class="divider">/</span> 
		  <a href="#" class="bread-current"> Risk Management</a>
		</div>
		
		<div class="clearfix"></div>
		
		</div>
		<!-- Page heading ends -->
		
		<!-- Matter -->
		<form method="post" action="/admin/risk_management/do_update/<?=$risk_assessment_id?>/<?=$risk_assessment_detail['library_value_id']?>/">
			<input type="hidden" name="risk_assessment_id" value="<?=$risk_assessment_id?>" />
			<div class="matter">
			<div class="container">
			
				<div class="row">
					<div class="col-md-12">
							<div class="widget">
					        <div class="widget-head">
			                  <div class="pull-left">Risk Management</div>
			                  <div class="pull-left" style="margin-left: 20px;">
									<? if($risk_assessment_detail['status']=='In Progress'){ ?>
									<a href="javascript:;" onclick="submit_for_approval(this);" class="btn btn-mini btn-danger">Submit For Approval</a>
									<? }else if($risk_assessment_detail['status']=='Pending Approval'){ ?>
									<a href="javascript:;" onclick="approve(this);" class="btn btn-mini btn-success">Approve</a>
									<? } ?>		
									
									<a href="/admin/threat_analysis/main/<?=$risk_assessment_id?>/" class="btn btn-mini btn-primary">Go Back</a>
										                  
							</div>
			                  <div class="pull-right">
			                  	<form method="get" class="form-inline" action="?">
			                  		<input type="text" class="form-control" style="width: 300px;" name="search" value="" onkeyup="do_search(this.value)" placeholder="Search" />
			                  	</form>
			                  				                    
			                    <!--<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
			                    <a href="#" class="wclose"><i class="icon-remove"></i></a>-->
			                  </div> 
			                  <div class="clearfix"></div>
			                </div>
			
			                <div class="widget-content">
			                		<table class="table table-bordered">
			                	    <thead>
			                	    <tr>
										<th width="15%" class="rowHeader"><?                 
							                if ($sort=='threat_id') {
							                	if ($ord=='ASC') {
							                		echo  '<a href="'.$href1.'&sort=threat_id&ord=DESC&Submit=Search">';
							                	} else {
							                		echo  '<a href="'.$href1.'&sort=threat_id&ord=ASC&Submit=Search">';
							                	}
							                } else {
							                	echo  '<a href="'.$href1.'&sort=threat_id&ord=ASC&Submit=Search">';
							                }
							                ?>Threat</a></th>
										<th  width="5%" class="rowHeader"><?                 
							                if ($sort=='weight') {
							                	if ($ord=='ASC') {
							                		echo  '<a href="'.$href1.'&sort=weight&ord=DESC&Submit=Search">';
							                	} else {
							                		echo  '<a href="'.$href1.'&sort=weight&ord=ASC&Submit=Search">';
							                	}
							                } else {
							                	echo  '<a href="'.$href1.'&sort=weight&ord=ASC&Submit=Search">';
							                }
							                ?>Score</a></th>
										 <?
						                	$rendered_items = $this->library_items_lib->return_rendered_items($this->library_items_lib->get_library_id_by_name($this->company_id,'Threat Assessment'));
						                	
						                	foreach ($rendered_items as $field_names => $value) {
						                		?>
						                		<th class="rowHeader"><?=$field_names?></th>
						                		<?
						                	}
						                ?>
						                <th class="rowHeader">Follow Up</th>
						            </tr>
			                	    </thead>
			                	    <tbody>
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
			                	            	foreach ($items as $key => $threat) 
			                	            	{
			                	            	
			                	            		$score = 0;
			                	            	 	if(isset($list[$threat['threat_id']]))
			                	            	 	{
			                	            	 		$score = $list[$threat['threat_id']]['weight'];
			                	            	 	}
			                	        ?>
			                	            <tr  class="items" id="item_no_<?=$threat['threat_id']?>">
												<td align="left" valign="top"><?=$threat['name']?></td>
												<td align="left" valign="top" class="alert <?=get_weight_class($score)?>"><span><?=$score?></span> </td>
												<?
												
													$threat_values_array = isset($risk_assessment_mitigation_data[$threat['threat_id']])?$risk_assessment_mitigation_data[$threat['threat_id']]:array();
													$rendered_items = $this->library_items_lib->return_rendered_items($this->library_items_lib->get_library_id_by_name($this->company_id,'Threat Assessment'),'[replace]',$threat_values_array);
													
													foreach ($rendered_items as $field_names => $value) {
														?>
														<td align="left" valign="top" class="rowDark"><?=str_replace('[replace]', '['.$threat['threat_id'].']', $value['render'])?></td>
														<?
													}
												?>
										    	<td valign="top" class="rowDark">
			                	            		<? if(!isset($tasks[$threat['threat_id']])){ ?>
			                	            		<div id="follow_up_<?=$threat['threat_id']?>"><a href="javascript:;" class="btn btn-mini btn-primary" onclick="follow_up(<?=$threat['threat_id']?>)">Follow Up</a></div>
			                	            		<? }else{ ?>
			                	            		Assigned to <?=$tasks[$threat['threat_id']]['first_name']?><br />
			                	            		<div id="re_assign_<?=$threat['threat_id']?>"><a href="javascript:;" class="btn btn-mini btn-danger" onclick="re_assign(<?=$threat['threat_id']?>)">Update</a></div>
			                	            		
			                	            		<? } ?>
			                	            	</td>
			                	            	
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
			                	      }else {
			                	      	
	                	      	    	foreach ($list as $key => $threat) 
	                	      	    	{
	                	      	    		$score = 0;
	                	      	    	 	if(isset($list[$threat['threat_id']]))
	                	      	    	 	{
	                	      	    	 		$score = $list[$threat['threat_id']]['weight'];
	                	      	    	 	}
			                	      	?>
			                	      	    <tr  class="items" id="item_no_<?=$threat['threat_id']?>">
			                	      			<td align="left" valign="top"><?=$threat['threat_name']?></td>
			                	      			<td align="left" valign="top" class="alert <?=get_weight_class($score)?>"><span><?=$score?></span> </td>
			                	      			<?
			                	      			
			                	      				$threat_values_array = isset($risk_assessment_mitigation_data[$threat['threat_id']])?$risk_assessment_mitigation_data[$threat['threat_id']]:array();
			                	      				$rendered_items = $this->library_items_lib->return_rendered_items($this->library_items_lib->get_library_id_by_name($this->company_id,'Threat Assessment'),'[replace]',$threat_values_array);
			                	      				
			                	      				foreach ($rendered_items as $field_names => $value) {
			                	      					?>
			                	      					<td align="left" valign="top" class="rowDark"><?=str_replace('[replace]', '['.$threat['threat_id'].']', $value['render'])?></td>
			                	      					<?
			                	      				}
			                	      			?>
			                	      	    	<td valign="top" class="rowDark">
			                	      	    		<? if(!isset($tasks[$threat['threat_id']])){ ?>
			                	      	    		<div id="follow_up_<?=$threat['threat_id']?>"><a href="javascript:;" class="btn btn-mini btn-primary" onclick="follow_up(<?=$threat['threat_id']?>)">Follow Up</a></div>
			                	      	    		<? }else{ ?>
			                	      	    		Assigned to <?=$tasks[$threat['threat_id']]['first_name']?><br />
			                	      	    		<div id="re_assign_<?=$threat['threat_id']?>"><a href="javascript:;" class="btn btn-mini btn-danger" onclick="re_assign(<?=$threat['threat_id']?>)">Update</a></div>
			                	      	    		
			                	      	    		<? } ?>
			                	      	    	</td>
			                	      	    	
			                	      	    </tr>
			                	      	<? 	
			                	      			}
			                	      	
			                	      	
			                	      }
			                	        ?>
			                	        </tbody>
			                	    </table>
			                	
			                		
			                </div>
			                <div class="widget-foot">
			               		<input type="submit" class="btn btn-success btn-block" name="submit" value="Save" />
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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Task Assignment</h4>
      </div>
      <div class="modal-body">
        <form id="form_task" method="post" action="/admin/tasks/do_add/<?=$risk_assessment_id?>/">
        <input type="hidden" name="risk_assessment_id" value="" />
        <input type="hidden" name="threat_id" value="" />
        <table class="table">
        	<tr>
        		<td>Task Title:</td>
        		<td>
        			<input type="text" name="title" class="form-control" value="" />
        		</td>
        	</tr>
        	<tr>
        		<td>Task Details:</td>
        		<td>
        			<textarea id="description" class="form-control autogrow" name="description"></textarea>
        		</td>
        	</tr>
        	
        	<tr>
        		<td>Priority:</td>
        		<td>
        			<select class="form-control" name="priority" id="priority">
        				<option value="0">Low</option>
        				<option value="1">Medium</option>
        				<option value="2">High</option>
        			</select>
        		</td>
        	</tr>
        	<tr>
        		<td>Assign To:</td>
        		<td>
        			<select class="form-control" name="assigned_to" id="assigned_to">
        				<? foreach ($employees as $key => $value) { ?>
        					<option value="<?=$value['user_id']?>"><?=$value['first_name']?> <?=$value['last_name']?></option>	
        				<? } ?>
        				
        			</select>
        		</td>
        	</tr>
        	<tr>
        		<td>Due Date:</td>
        		<td>
        			<input type="text" name="due_by" data-format="yyyy-mm-dd" class="form-control datepicker" value="" />
        		</td>
        	</tr>
        </table>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button id="btn_do_assign" type="button" class="btn btn-primary" onclick="do_assign();">Assign</button>
        <button id="btn_re_assign" type="button" class="btn btn-primary hidden" onclick="do_re_assign();">Update</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>