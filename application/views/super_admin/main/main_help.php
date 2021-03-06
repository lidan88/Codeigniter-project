<?
$limit=30;
$sort=isset($_REQUEST['sort'])?$_REQUEST['sort']:"help_id";
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
<title>Help</title>

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
			$.post("/super_admin/help/do_delete_multi/",{"ids":searchIDs.join(",")},function(ret){
				
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
			top.location="/super_admin/help/edit/"+searchIDs[0];
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

function copy_item()
{
	var searchIDs = $("input:checkbox:checked").map(function(){
      return $(this).val();
    }).get();
	
	if(searchIDs.length>0)
	{
		if(searchIDs.length==1)
		{
			$.post("/super_admin/help/do_copy/",{"id":searchIDs[0]},function(ret){
				top.location.href = "/super_admin/help/edit/"+ret+"?copied=1";
			});
		}
		else {
			alert("Please select only one item to copy.");
		}
	}
	else {
		alert("Please select an item to copy.");
	}
	//console.log(searchIDs);
	//alert("deleted");
}

function do_search(search) {
	$.post("/super_admin/help/main/?output=ajax&search="+search,{},function(ret){
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
	  top.location="/super_admin/help/edit/"+item_no+"/";
	  
	});
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
		  <a href="#" class="bread-current">Help </a>
		  <span class="divider">/</span> 
		  <a href="/super_admin/help/add/"> &raquo; Add New</a>
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
			                  <div class="pull-left">Help</div>
			                  <div class="pull-left" style="margin-left: 20px;">
			                  
			                  	<a href="/super_admin/help/add/" class="btn btn-mini btn-primary">Add</a>
			                  	<a href="javascript:edit_item();" class="btn btn-mini btn-primary">Edit</a>
			                  	<a href="javascript:delete_items();" class="btn btn-mini btn-danger">Delete</a>
			                  	
			                  	<a href="javascript:copy_item();" class="btn btn-mini btn-success">Copy</a>
			                  	
			                  
			                  </div>
			                  <div class="widget-icons pull-right">
			                  	<form method="get" class="form-inline" action="?">
			                  		<input type="text" class="form-control" style="width: 300px;" name="search" value="<?=$search?>" onkeyup="do_search(this.value)" placeholder="Search" />
			                  	</form>
			                    <!--<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
			                    <a href="#" class="wclose"><i class="icon-remove"></i></a>-->
			                  </div>   
			                  <div class="clearfix"></div>
			                </div>
			
			                <div class="widget-content">
			                	<table id="list" class="table table-striped table-bordered table-hover">
			                	    <thead>
			                	    <tr>
			                	    <th><input type="checkbox" value="" onclick="select_all_boxes(this.checked)" /></th>
			                	    
									<td class="rowHeader"><?                 
						                if ($sort=='help_category_id') {
						                	if ($ord=='ASC') {
						                		echo  '<a href="'.$href1.'&sort=help_category_id&ord=DESC&items_per_page='.$items_per_page.'&Submit=Search">';
						                	} else {
						                		echo  '<a href="'.$href1.'&sort=help_category_id&ord=ASC&items_per_page='.$items_per_page.'&Submit=Search">';
						                	}
						                } else {
						                	echo  '<a href="'.$href1.'&sort=help_category_id&ord=ASC&items_per_page='.$items_per_page.'&Submit=Search">';
						                }
						                ?>Help category </a></td>
									<td class="rowHeader"><?                 
						                if ($sort=='title') {
						                	if ($ord=='ASC') {
						                		echo  '<a href="'.$href1.'&sort=title&ord=DESC&items_per_page='.$items_per_page.'&Submit=Search">';
						                	} else {
						                		echo  '<a href="'.$href1.'&sort=title&ord=ASC&items_per_page='.$items_per_page.'&Submit=Search">';
						                	}
						                } else {
						                	echo  '<a href="'.$href1.'&sort=title&ord=ASC&items_per_page='.$items_per_page.'&Submit=Search">';
						                }
						                ?>Title</a></td>
									<td class="rowHeader"><?                 
						                if ($sort=='tags') {
						                	if ($ord=='ASC') {
						                		echo  '<a href="'.$href1.'&sort=tags&ord=DESC&items_per_page='.$items_per_page.'&Submit=Search">';
						                	} else {
						                		echo  '<a href="'.$href1.'&sort=tags&ord=ASC&items_per_page='.$items_per_page.'&Submit=Search">';
						                	}
						                } else {
						                	echo  '<a href="'.$href1.'&sort=tags&ord=ASC&items_per_page='.$items_per_page.'&Submit=Search">';
						                }
						                ?>Tags</a></td>
									<td class="rowHeader"><?                 
						                if ($sort=='description') {
						                	if ($ord=='ASC') {
						                		echo  '<a href="'.$href1.'&sort=description&ord=DESC&items_per_page='.$items_per_page.'&Submit=Search">';
						                	} else {
						                		echo  '<a href="'.$href1.'&sort=description&ord=ASC&items_per_page='.$items_per_page.'&Submit=Search">';
						                	}
						                } else {
						                	echo  '<a href="'.$href1.'&sort=description&ord=ASC&items_per_page='.$items_per_page.'&Submit=Search">';
						                }
						                ?>Description</a></td>
									<td class="rowHeader"><?                 
						                if ($sort=='created_on') {
						                	if ($ord=='ASC') {
						                		echo  '<a href="'.$href1.'&sort=created_on&ord=DESC&items_per_page='.$items_per_page.'&Submit=Search">';
						                	} else {
						                		echo  '<a href="'.$href1.'&sort=created_on&ord=ASC&items_per_page='.$items_per_page.'&Submit=Search">';
						                	}
						                } else {
						                	echo  '<a href="'.$href1.'&sort=created_on&ord=ASC&items_per_page='.$items_per_page.'&Submit=Search">';
						                }
						                ?>Created on</a></td>
			                	    <!--<td width="18%" align="center" class="rowHeader">Actions</td>-->
			                	    </tr>
			                	    </thead>
			                	    <tbody>
			                	    <?                
			                	        if(is_array($list) and count($list)>0)
			                	        {
			                	            foreach($list as $k => $row)
			                	            {
			                	        ?>
			                	            <tr id="item_no_<?=$row['help_id']?>">
			                	            	<td><input type="checkbox" name="items[<?=$row['help_id']?>]" value="<?=$row['help_id']?>" /></td>
			                	           		
												<td align="left" valign="top" class="rowDark"><?=isset($row['fhelp_category_id'])?$row['fhelp_category_id']:$row['help_category_id']?></td>
												<td align="left" valign="top" class="rowDark"><?=isset($row['ftitle'])?$row['ftitle']:$row['title']?></td>
												<td align="left" valign="top" class="rowDark"><?=isset($row['ftags'])?$row['ftags']:$row['tags']?></td>
												<td align="left" valign="top" class="rowDark"><?=isset($row['fdescription'])?$row['fdescription']:$row['description']?></td>
												<td align="left" valign="top" class="rowDark"><?=isset($row['fcreated_on'])?$row['fcreated_on']:$row['created_on']?></td>
			                	            
			                	            	<!--<td align="center" valign="top" class="rowDark">
			                	            		<a style="text-decoration:none" class="btn btn-xs btn-warning" href="/super_admin/help/edit/<?=$row['help_id']?>/"><i class="icon-pencil"></i> </a>
			                	            		<a style="text-decoration:none" class="btn btn-xs btn-danger" href="javascript:confirm_delete('/super_admin/help/do_delete/<?=$row['help_id']?>/');"><i class="icon-remove"></i></a>
			                	            	</td>-->
			                	            </tr>
			                	        <? 	
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
			                	        ?>
			                	        </tbody>
			                	    </table>
			                	
			                		
			                </div>
			                <div id="pagination" class="widget-foot">
			                	 <? if($pagination['total_items']>0){ ?>
			                	 <div class="pull-left">
			                	 	<?=$pagination['pagination']?>
			                	 </div>
			                	 <div class="pull-left" style="margin-top: 15px; margin-left: 15px;">
			                	 Show 
			                	 </div>
			                	 <div class="pull-left">
			                	 <select class="form-control" onchange="set_items_per_page(this.value)" name="items_per_page" style="margin-top: 9px; margin-left: 5px;">
			                	 	<option value="<?=$items_per_page_value?>10" <?=($items_per_page==10)?'selected="selected"':''?>>10</option>
			                	 	<option value="<?=$items_per_page_value?>25" <?=($items_per_page==25)?'selected="selected"':''?>>25</option>
			                	 	<option value="<?=$items_per_page_value?>50" <?=($items_per_page==50)?'selected="selected"':''?>>50</option>
			                	 	<option value="<?=$items_per_page_value?>100" <?=($items_per_page==100)?'selected="selected"':''?>>100</option>
			                	 </select>
			                	 </div>
			                	 <div class="pull-left" style="margin-top: 15px; margin-left: 10px;">
			                	 	Items
			                	 	
			                	 </div>
			                	 <div class="pull-right" style="margin-top: 15px; margin-right: 10px;">
			                	 	<? if($page==0)$page=1; ?>
			                	 	Showing <?=(($page*$items_per_page)-$items_per_page+1)?>-<?=$pagination['total_items']<($page*$items_per_page)?$pagination['total_items']:($page*$items_per_page)?> of <?=$pagination['total_items']?>
			                	 </div>
			                	 <? } ?>
			                	 <div class="clearfix"></div>
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