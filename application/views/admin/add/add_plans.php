<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Panel</title>
<!--HEAD-->
	<? include(APPPATH."views/admin/head.inc.php"); ?>
<!-- /HEAD -->
<script> 
		
function form_validation(thisform) {
	return true; 
} 

function submit_for_approval($obj)
{
	$.post("/admin/plans/submit_for_approval/<?=$plan_id?>/",{},function(ret){
		$obj.html("Submitted").attr("disabled","disabled");
	});
}

function approve($obj)
{
	$.post("/admin/plans/approve/<?=$plan_id?>/",{},function(ret){
		$obj.html("Approved").attr("disabled","disabled");
	});
}

function reject()
{
	$("#reject_modal").modal("show");
	
}

function reject_with_comment() {
	$.post("/admin/plans/reject/<?=$plan_id?>/",{notes:$("#reject_reason").val()},function(ret){
		$("#btn_reject").html("Rejected").attr("disabled","disabled");
		$("#reject_modal").modal("hide");
	});	
}

function confirm_delete()
{
	if(confirm("Are you sure you want to delete?"))
	{
		$('#form_control').get(0).submit();
		$('#action').val('Delete');
	}
	else {
		return false;
	}
}

function confirm_delete_link(link)
{
	if(confirm("Are you sure you want to delete?"))
	{
		top.location=link;
	}
}

$(document).ready(function(){
	var dp = $('.datepicker').datepicker().on('changeDate', function(ev) {
	  dp.datepicker('hide');
	});
	$('.timepicker').timepicker();
	$(".chosen-select").chosen();
	
	$( "tr.plan_items" ).dblclick(function() {
	  var item_no = $(this).attr('id').replace('item_', '');
	  top.location="/admin/plans/edit_plan_item/<?=$plan_id?>/"+item_no+"/";
	  
	});
	
	$("#sort tbody").sortable({
		forcePlaceholderSize: true,
		handle: 'span',
		helper: function(e, tr)
		  {
		    var $originals = tr.children();
		    var $helper = tr.clone();
		    $helper.children().each(function(index)
		    {
		      // Set helper cell sizes to match the original sizes
		      $(this).width($originals.eq(index).width());
		    });
		    return $helper;
		  }
	}).bind('sortupdate', function(e, ui) {
	    //ui.item contains the current dragged element.
	    //Triggered when the user stopped sorting and the DOM position has changed.
		//console.log("sorting");
		var sort_order=Array();
		var sort_ctr=0;
		$("#sort tbody").find('tr').each(function(){
			//console.log($(this).attr('id'));
			if(typeof $(this).attr('id') != 'undefined')
			{
				var item_id = $(this).attr('id').replace('item_','');
				sort_order.push(item_id+":"+sort_ctr++);
			}
		});
		
		if(sort_order.length>0)
		{
			console.log("sending sort data-> "+sort_order.join(','));
			$.post("/admin/plans/set_items_sort_order/",{"sort_order":sort_order.join(',')},function(ret){
				console.log(ret);
			});
		}
		//console.log(sort_order);
	}); //.disableSelection();
	
});

</script>
</head>
<body>
<!--HEADER-->
	<? include(APPPATH."views/admin/header.inc.php"); ?>
<!-- /HEADER-->

<!-- /container -->
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
          <a href="/super_admin/"><i class="icon-home"></i> Home</a> 
          <!-- Divider -->
          <span class="divider">/</span> 
          <a href="/admin/plans/main/"> View All plans </a>
          <span class="divider">/</span> 
          <a href="#" class="bread-current">plans</a>
        </div>

        <div class="clearfix"></div>

      </div>
      <!-- Page heading ends -->
		<form class='form-horizontal' name='frm_add_plans' method='post' action='/admin/plans/do_add/' onSubmit='return form_validation(this);' enctype='multipart/form-data'>
		    <!-- Matter -->
		    <div class="matter">
		        <div class="container">
		
						            
		          
		          	<div class="row">
		          	
		          	            <div class="col-md-12">
		          	
		          	
		          	              <div class="widget wgreen">
		          	                
		          	                <div class="widget-head">
		          	                  <div class="pull-left"><? if($item['plan_id']==''){?>Add New Plan<? }else{ ?>Update Plan<? } ?>
		          	                  
		          	                  </div>
		          	                  <div class="pull-left" style="margin-left: 10px;">
		          	                  	<? if($item['status']=='In Progress' or $item['status']=='Rejected'){?>
		          	                  		<a class="btn btn-success" onclick="submit_for_approval($(this));">Submit For Approval</a>
		          	                  	<? }elseif($item['status']=='Pending Approval'){ ?>
		          	                  		<a class="btn btn-success" onclick="approve($(this));">Approve</a>
		          	                  		<a id="btn_reject" class="btn btn-danger" onclick="reject();">Reject</a>
		          	                  	<? } ?>
		          	                  </div>
		          	                  
		          	                 
		          	                  <div class="widget-icons pull-right">
		          	                    <!--<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
		          	                    <a href="#" class="wclose"><i class="icon-remove"></i></a>-->
		          	                    <? 
		          	                    
		          	                    if($item['status']=='In Progress'){ 
		          	                    	echo '<span class="label label-warning">Status: '.$item['status'].'</span>'; 
		          	                    } elseif ($item['status']=='Pending Approval') {
		          	                    	echo '<span class="label label-warning">Status: '.$item['status'].'</span>';
		          	                    } elseif ($item['status']=='Approved') {
		          	                    	echo '<span class="label label-success">Status: '.$item['status'].'</span>';
		          	                    } elseif ($item['status']=='Rejected') {
		          	                    	echo '<span class="label label-danger">Status: '.$item['status'].'</span> <a href=\'javascript:$("#reject_reason_modal").modal("show");\'>Reason?</a>';
		          	                    }
		          	                    ?>
		          	                  </div>
		          	                  <div class="clearfix"></div>
		          	                </div>
		          	
		          	                <div class="widget-content">
		          	                  <div class="padd">
		          	
		          	                    <!-- Form starts.  -->
		          	                         <input type='hidden' name='library_value_id' id='library_value_id'  value='<?=$item['library_value_id']?>' />
			          	                     <input type='hidden' id='chaabee_post' name='chaabee_post' value=''>
			          	                     <input type='hidden' id='plan_id' name='plan_id' value='<?=$item['plan_id']?>'>
			          	                     <input type="hidden" name="unique_id" value="<?=$item['unique_id']?>" />
			          	                     <input type="hidden" name="action" id="action" value="" />
			          	                     
			          	                     <table class="table table-bordered table-striped table-hover" style="width:100%;" cellpadding="0" cellspacing="0">
			          	                    	<?        
			          	                    		if(is_array($list) and count($list)>0)
			          	                    		{
			          	                    		    foreach($list as $item_name => $row)
			          	                    		    {
			          	                    		    	?>
			          	                    		    	<tr>
			          	                    		    		<td><strong><?=$item_name?></strong>:<br />
			          	                    		    		<?=$row['help']?></td>
			          	                    		    		<td><?=$row['render']?></td>
			          	                    		    	</tr>
			          	                    		    	<?
			          	                    		    }
			          	                    		}else
			          	                    		{
			          	                    		?>
			          	                    		    <tr>
			          	                    		        <td>
			          	                    		            <div class='b1 txt padd5'><strong>No items found!</strong></div>
			          	                    		        </td>
			          	                    		    </tr>
			          	                    		<?
			          	                    		}
			          	                    			?>
			          	                    	
													<tr>
														<td>&nbsp;</td>
														<td>
															<input name='Submit' id='Submit' class="btn btn-primary" type='submit' value='Save' />
															<input name='Submit' id='Submit' class="btn btn-primary" type='submit' value='Save and New' />
											     	 		
											     	 		<input name='Submit' id='Submit' class="btn btn-success" type='button' onclick="top.location='/admin/plans/add/';" value='New' />
											     	 		<? if($plan_id!=''){?>
											     	 		<a target="_blank" href="/admin/plans/export/<?=$item['plan_id']?>?output=pdf" class="btn btn-success"><i class="icon icon-print"></i> Print</a>
											     	 		<!--<input name='Submit' id='Submit' class="btn btn-success" type='button' onclick="top.location='/admin/plans/export/<?=$item['plan_id']?>?output=pdf';" value='Print' />-->
											     	 		<button name='Submit' id='Submit' class="btn btn-success" type='submit' value="Copy"><i class="icon icon-print"></i> Copy</button>
											     	 		<? } ?>
											     	 		<input name='Submit' id='Submit' class="btn btn-danger" type='button' onclick="confirm_delete()" value='Delete' />
											     	 		<input name='Submit' id='Submit' class="btn btn-danger" type='button' onclick="history.go(-1);" value='Cancel' />
														</td>
													</tr>
											</table>
										
		          	                     
		          	                     
		          	                  </div>
		          	                </div>
		          	                <? if($item['plan_id']!=''){?>
		          	                <div class="widget-head">
	                                  <div class="pull-left">Plan Items</div>
	                                  
	                                  <div class="pull-left" style="margin-left: 20px;">
	                                  
	                                  	<a href="/admin/plans/add_plan_item/<?=$item['plan_id']?>/" class="btn btn-sm btn-primary">Add New Plan Item</a>
	                                  	                                  
	                                  </div>
	                                  
	                                  <div class="widget-icons pull-right">
	                                   
	                                  </div>
	                                  <div class="clearfix"></div>
	                                </div>
	                
	                                <div class="widget-content">
	                                  <div class="padd">
	                                  		<table id="sort" class="table table-striped table-bordered table-hover">
	                                  		  <thead>
	                                  		    <tr>
	                                  		      <th width="10">&nbsp;</th>
	                                  		      <th>Title</th>
	                                  		      <th width="180">Modified On</th>
	                                  		      <th width="200">Modified by</th>
	                                  		      <th width="80">Actions</th>
	                                  		    </tr>
	                                  		  </thead>
	                                  		  <tbody>
	                                  		    <?
	                                  		    	if(is_array($plan_items) and count($plan_items)>0)
	                                  		    	{
	                                  		    		foreach ($plan_items as $key => $value) 
	                                  		    		{
	                                  		    ?>
				                                  		    <tr id="item_<?=$value['id']?>" class="plan_items">
				                                  		      <td><span style="cursor: move;" class="icon icon-th-list handle"></span></td>
				                                  		      <td><?=$value['title']?></td>
				                                  		      <td><?=$value['modified_on']?></td>
				                                  		      <td><?=$value['modified_by']?></td>
				                                  		      <td>
				                                  		      	<a style="text-decoration:none" class="btn btn-xs btn-warning" href="/admin/plans/edit_plan_item/<?=$plan_id?>/<?=$value['id']?>/"><i class="icon-pencil"></i> </a>
				                                  		      	<a style="text-decoration:none" class="btn btn-xs btn-danger" href="javascript:confirm_delete_link('/admin/plans/do_delete_plan_item/<?=$plan_id?>/<?=$value['id']?>/');"><i class="icon-remove"></i></a>
				                                  		      </td>
				                                  		    </tr>
	                                  		    <?  	}
	                                  		    	} 
	                                  		    ?>
	                                  		  </tbody>
	                                  		</table>
	                                  </div>
	                                </div>
		          	                <? } ?>
		          	                
		          	                
		          	                  <div class="widget-foot">
		          	                    <!-- Footer goes here -->
		          	                  </div>
		          	              </div>  
		          	
		          	            </div>
		          	
		          	          </div>
		         
		
		        </div>
			 </div>
			<!-- Matter ends -->
		</form>
		

    </div>

   <!-- Mainbar ends -->	    	
   <div class="clearfix"></div>

</div>
<!-- Content ends -->

<!-- Footer starts -->
<?  include(APPPATH."views/footer.inc.php"); ?>
<!-- Footer/Ends-->

<!-- Modal -->
<div class="modal fade" id="reject_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Reject Reason</h4>
      </div>
      <div class="modal-body">
        <textarea class="form-control" id="reject_reason"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" onclick="reject_with_comment()" class="btn btn-primary">Reject</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="reject_reason_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Reject Reason</h4>
      </div>
      <div class="modal-body">
        <?=$item['notes']?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>