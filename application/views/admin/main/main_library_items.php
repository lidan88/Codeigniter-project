<?
$limit=30;
$sort=isset($_REQUEST['sort'])?$_REQUEST['sort']:"item_order";
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
<title>Library items</title>

<!--HEAD-->
<? include(APPPATH."views/admin/head.inc.php"); ?>

<script>
function confirm_delete(plink)
{
	if(confirm("Are you sure you want to delete?"))
	{
		top.location=plink;	
	}
}

$(document).ready(function(){
	//$("#sort tbody").sortable().disableSelection();
	
	var dp = $('.datepicker').datepicker().on('changeDate', function(ev) {
	  //ev.hide();
	  dp.datepicker('hide');
	});
	
	$(".chosen-select").chosen();
	$('.timepicker').timepicker();
	
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
				sort_order.push(item_id+"<?=OPT_SEPERATOR?>"+sort_ctr++);
			}
		});
		
		if(sort_order.length>0)
		{
			console.log(sort_order.join(','));
			
			$.post("/admin/library_items/set_sort_order/",{"sort_order":sort_order.join(',')},function(ret){
				console.log(ret);
			});
		}
		//console.log(sort_order);
	}); //.disableSelection();
	
});

</script>
<style>
.sortable-dragging {
	border: 2px dashed green;
}

.sortable-placeholder{
	border-left: 3px solid brown;
}
</style>
</head>
<body>
<!--HEADER-->
<? include(APPPATH."views/admin/header.inc.php"); ?>


<div class="content">

	<!-- Sidebar -->
	<? include(APPPATH."views/sidebar.inc.php"); ?>
    <!-- Sidebar ends -->

  	<!-- Main bar -->
  	<div class="mainbar">

		<!-- Page heading -->
		<div class="page-head">
		
		<!-- Breadcrumb -->
		<div class="bread-crumb pull-left">
		  <a href="/admin/"><i class="icon-home"></i> Home</a> 
		  <!-- Divider -->
		  <span class="divider">/</span> 
		  <a href="/admin/library/main/">Library </a>
		  <span class="divider">/</span> 
		  <a href="/admin/library_items/main/<?=$library_details['id']?>" class="bread-current"><?=$library_details['name']?> </a>
		  <span class="divider">/</span> 
		  
		  <a href="/admin/library_items/add/<?=$library_details['id']?>"> &raquo; Add New Field</a>
		</div>
		
		<div class="clearfix"></div>
		
		</div>
		<!-- Page heading ends -->
		
		<!-- Matter -->
		
		<div class="matter">
			<div class="container">
			
				<div class="row">
					<div class="col-md-12">
						<div class="widget">
					        <div class="widget-head">
			                  <div class="pull-left">Fields for (<?=$library_details['name']?>)</div>
			                  <!--<div class="widget-icons pull-right">
			                    <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
			                    <a href="#" class="wclose"><i class="icon-remove"></i></a>
			                  </div>  -->
			                  <div class="clearfix"></div>
			                </div>
			
			                <div class="widget-content">
			                	<table id="sort" class="table"> <? /*table-striped table-bordered table-hover*/?>
			                	    <thead>
			                	    <tr>
			                	    <td width="30" class="rowHeader"><?                 
			                	        if ($sort=='item_order') {
			                	        	if ($ord=='ASC') {
			                	        		echo  '<a href="'.$href1.'&sort=item_order&ord=DESC&Submit=Search">';
			                	        	} else {
			                	        		echo  '<a href="'.$href1.'&sort=item_order&ord=ASC&Submit=Search">';
			                	        	}
			                	        } else {
			                	        	echo  '<a href="'.$href1.'&sort=item_order&ord=ASC&Submit=Search">';
			                	        }
			                	        ?>Order</a></td>
										<td class="rowHeader"><?                 
							                if ($sort=='var_name') {
							                	if ($ord=='ASC') {
							                		echo  '<a href="'.$href1.'&sort=var_name&ord=DESC&Submit=Search">';
							                	} else {
							                		echo  '<a href="'.$href1.'&sort=var_name&ord=ASC&Submit=Search">';
							                	}
							                } else {
							                	echo  '<a href="'.$href1.'&sort=var_name&ord=ASC&Submit=Search">';
							                }
							                ?>Name</a></td>
										<td class="rowHeader"><?                 
							                if ($sort=='var_type') {
							                	if ($ord=='ASC') {
							                		echo  '<a href="'.$href1.'&sort=var_type&ord=DESC&Submit=Search">';
							                	} else {
							                		echo  '<a href="'.$href1.'&sort=var_type&ord=ASC&Submit=Search">';
							                	}
							                } else {
							                	echo  '<a href="'.$href1.'&sort=var_type&ord=ASC&Submit=Search">';
							                }
							                ?>Type</a></td>
										
										
							                <td>Visual</td>
			                	    <td width="18%" align="center" class="rowHeader">Actions</td>
			                	    </tr>
			                	    </thead>
			                	    <tbody>
			                	    <?
			                	    if(is_array($list) and count($list)>0)
			                	    {
			                	        foreach($list as $item_name => $row)
			                	        {
			                	        	?>
			                	        	<tr id="item_<?=$row['id']?>">
			                	        	
			                	        		<td align="left" valign="top" class="rowDark"><span style="cursor: move;" class="icon icon-th-list handle"></span></td>
			                	        		<td align="left" valign="top" class="rowDark"><?=$item_name?></td>
			                	        		<td align="left" valign="top" class="rowDark"><?=$field_types[$row['var_type']]?></td>
			                	        		<td align="left" valign="top" class="rowDark"><?=$row['render']?></td>
			                	        		<td align="center" valign="top" class="rowDark">
			                	        			<a style="text-decoration:none" class="btn btn-xs btn-warning" href="/admin/library_items/edit/<?=$row['id']?>/"><i class="icon-pencil"></i> </a>
			                	        			<a style="text-decoration:none" class="btn btn-xs btn-danger" href="javascript:confirm_delete('/admin/library_items/do_delete/<?=$row['id']?>/');"><i class="icon-remove"></i></a>
			                	        		</td>
			                	        	</tr>
			                	        		
			                	        	<?
			                	        }
			                	    }else{
			                	    ?>
			                	    	<tr>
			                	    	    <td colspan="5">
			                	    	        <div class="b1 txt padd5"><strong>No items found!</strong></div>
			                	    	    </td>
			                	    	</tr>
			                	    <?
			                	    }
			                	    ?>
			                	        </tbody>
			                	    </table>
			                	
			                		
			                </div>
			                <div class="widget-foot">
			                	 <?=$pagination?>
			                </div>
			            </div>
			        </div>
			    </div>
				
			
				
			
			</div>
		</div>
	</div>
	
	<div class="clearfix"></div>
</div>

<!-- Footer starts -->
<?  include(APPPATH."views/footer.inc.php"); ?>
<!-- Footer/Ends-->

</body>
</html>