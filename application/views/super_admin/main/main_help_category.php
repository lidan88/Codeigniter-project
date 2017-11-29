<?
$limit=30;
$sort=isset($_REQUEST['sort'])?$_REQUEST['sort']:"id";
$ord=isset($_REQUEST['ord'])?$_REQUEST['ord']:"ASC";
$page=isset($_REQUEST['page'])?$_REQUEST['page']:0;
$submit=isset($_REQUEST['Submit'])?$_REQUEST['Submit']:"";
$search=isset($_REQUEST['search'])?$_REQUEST['search']:"";
$items_per_page=isset($_REQUEST['items_per_page'])?$_REQUEST['items_per_page']:10;
$items_per_page_value = "sort=$sort&ord=$ord&items_per_page=";

$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord&search=$search&page=$page&items_per_page=$items_per_page";
$href1=$_SERVER['PHP_SELF']."?items_per_page=$items_per_page&search=$search";

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Help category</title>

<!--HEAD-->
<? include(APPPATH."views/super_admin/head.inc.php"); ?>
<script>
function confirm_delete(plink)
{
	if(confirm("Are you sure you want to delete?"))
	{
		top.location=plink;	
	}
}

function delete_items()
{
	var p = confirm("Are you sure you want to delete the selected items?");
	if(p)
	{
		var searchIDs = $("input:checkbox:checked").map(function(){
	      return $(this).val();
	    }).get();
		
		if(searchIDs.length>0)
		{
			$.post("/super_admin/help_category/do_delete_multi/",{"ids":searchIDs.join(",")},function(ret){
				
				for(x in searchIDs)
				{
					$("#item_no_"+searchIDs[x]).addClass("hidden");
				}
			});
		}
	}
}

function select_all_boxes(is_checked)
{
	if(is_checked)
	{
		$("[type='checkbox']").attr("checked","checked");
		$("[type='checkbox']").prop('checked', true);
	}
	else {
		$("[type='checkbox']").removeAttr("checked");
		$("[type='checkbox']").prop('checked', false);
		
	}
}

function edit_item()
{
	var searchIDs = $("input:checkbox:checked").map(function(){
      return $(this).val();
    }).get();
	
	if(searchIDs.length>0)
	{
		if(searchIDs.length==1)
		{
			top.location="/super_admin/help_category/edit/"+searchIDs[0];
		}
		else {
			alert("Please select only one item to edit.");
		}
	}
	else {
		//alert("");
	}
	//console.log(searchIDs);
	//alert("deleted");
}

function do_search(search) {
	$.post("/super_admin/help_category/main/?output=ajax&search="+search,{},function(ret){
		var test = ret;
		var content = test.substring(0, test.indexOf('<div id="pagination">'));
		var pagination = test.substring(test.indexOf('<div id="pagination">'), test.length);
		$('#list').find('tbody').html(content);
		$('#pagination').html(pagination);
		
	});
}


function set_items_per_page(ipp)
{
	//if(top.location.href.toLowerCase().indexOf('?')== -1)
	//{
		top.location='?'+ipp;
	//}
	//else {
	//	top.location=top.location+'&items_per_page='+ipp+'&reset=1';
	//}
}


$(document).ready(function(){
	$( "tr" ).dblclick(function() {
	  var item_no = $(this).attr('id').replace('item_no_', '');
	  top.location="/super_admin/help_category/edit/"+item_no+"/";
	  
	});
	
	
	$("#list tbody").sortable({
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
			$("#list tbody").find('tr').each(function(){
				//console.log($(this).attr('id'));
				if(typeof $(this).attr('id') != 'undefined')
				{
					var item_id = $(this).attr('id').replace('item_no_','');
					sort_order.push(item_id+"[#]"+sort_ctr++);
				}
			});
			
			if(sort_order.length>0)
			{
				$.post("/super_admin/help_category/set_sort_order/",{"sort_order":sort_order.join(',')},function(ret){
					console.log(ret);
				});
			}
			console.log(sort_order);
		}); //.disableSelection();
	
});



</script>
</head>
<body>
<!--HEADER-->
<? include(APPPATH."views/super_admin/header.inc.php"); ?>


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
		  <a href="index.html"><i class="icon-home"></i> Home</a> 
		  <!-- Divider -->
		  <span class="divider">/</span> 
		  <a href="#" class="bread-current">Help category </a>
		  <span class="divider">/</span> 
		  <a href="/super_admin/help_category/add/"> &raquo; Add New</a>
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
			                  <div class="pull-left">Help category</div>
			                  <div class="pull-left" style="margin-left: 20px;">
			                  
			                  	<a href="/super_admin/help_category/add/" class="btn btn-mini btn-primary">Add</a>
			                  	<a href="javascript:edit_item();" class="btn btn-mini btn-primary">Edit</a>
			                  	<a href="javascript:delete_items();" class="btn btn-mini btn-danger">Delete</a>
			                  
			                  </div>
			                  <div class="widget-icons pull-right">
			                  	<form method="get" class="form-inline" action="?">
			                  		<input type="text" class="form-control" style="width: 300px;" name="search" value="<?=$search?>" onkeyup="do_search(this.value)" placeholder="Search" />
			                  	</form>
			                  </div>   
			                  <div class="clearfix"></div>
			                </div>
			                <div class="widget-content">
		                		<table id="list" class="table table-striped table-bordered table-hover">
		                	    	<thead>
		                	    		<tr>
		                	    			<td width="50px"><input type="checkbox" value="" onclick="select_all_boxes(this.checked)" /></td>
		                	    			<td class="rowHeader">Title</a></td>
		                	    		</tr>
		                	    	</thead>
		                	    	<tbody>
			                	     <?    
			                	      	printTreeTr($tree);
			                	     ?>
		                	    	</tbody>
		                	    </table>
			                </div>
			                <div id="pagination" class="widget-foot"></div>
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