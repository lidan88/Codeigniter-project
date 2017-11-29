<?
$limit=30;
$sort=isset($_REQUEST['sort'])?$_REQUEST['sort']:"risk_assessment_id";
$ord=isset($_REQUEST['ord'])?$_REQUEST['ord']:"ASC";
$page=isset($_REQUEST['page'])?$_REQUEST['page']:0;
$submit=isset($_REQUEST['Submit'])?$_REQUEST['Submit']:"";
$search=isset($_REQUEST['search'])?$_REQUEST['search']:"";
$items_per_page=isset($_REQUEST['items_per_page'])?$_REQUEST['items_per_page']:10;

$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
$href1=$_SERVER['PHP_SELF']."?";

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Risk assessment</title>

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
			$.post("/admin/risk_assessment/delete_user_values/",{"ids":searchIDs.join(",")},function(ret){
				//console.log(ret);
				
				for(x in searchIDs)
				{
					$("#item_no_"+searchIDs[x]).addClass("hidden");
				}
			});
		}
		//console.log(searchIDs);
		//alert("deleted");
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
			//top.location="/admin/risk_assessment/edit/"+searchIDs[0];
			top.location="/admin/threat_analysis/main/"+searchIDs[0];
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
			top.location="/admin/risk_assessment/copy/"+searchIDs[0];
		}
		else {
			alert("Please select only one item to copy.");
		}
	}
	else {
		alert("Please select an item to copy");
	}
	//console.log(searchIDs);
	//alert("deleted");
}


function export_items(library_id)
{
	var searchIDs = $("input:checkbox:checked").map(function(){
	  return $(this).val();
	}).get();
	
	if(searchIDs.length>0)
	{
		$("#form_export").find("input#ids").val(searchIDs.join(","));
		$("#form_export").get(0).submit();
	}
	else {
		alert("Please select items below to export");
	}
}
//## MAlkoo Feat	
function do_search(search) {
		$.post("/admin/risk_assessment/main/?output=ajax&search="+search,{},function(ret){
			var test = ret;
			var content = test.substring(0, test.indexOf('<div id="pagination">'));
			var pagination = test.substring(test.indexOf('<div id="pagination">'), test.length);
			$('#list').find('tbody').html(content);
			$('#pagination').html(pagination);
		});
}

function set_items_per_page(ipp)
{
	if(top.location.href.toLowerCase().indexOf('?')== -1)
	{
		top.location='?items_per_page='+ipp;
	}
	else {
		top.location=top.location+'&items_per_page='+ipp+'&reset=1';
	}
}

$(document).ready(function(){
	$( "tr" ).dblclick(function() {
	  //alert( "Hello World! "+$(this).attr('id') );
	  var item_no = $(this).attr('id').replace('item_no_', '');
	  //top.location="/admin/risk_assessment/edit/"+item_no+"/";
	  top.location="/admin/threat_analysis/main/"+item_no+"/";
	});
});

</script>
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
		  <a href="#" class="bread-current">Risk Assessment </a>
		  <span class="divider">/</span> 
		  <a href="/admin/risk_assessment/add/"> &raquo; Add New</a>
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
			                  <div class="pull-left">Risk Assessment</div>
			                  <div class="pull-left" style="margin-left: 20px;">
			                   
			                   	<a href="/admin/risk_assessment/add/" class="btn btn-mini btn-primary">Add</a>
			                   	<a href="javascript:edit_item();" class="btn btn-mini btn-primary">Edit</a>
			                   	<a href="javascript:copy_item();" class="btn btn-mini btn-success">Copy</a>
			                   	<a href="javascript:export_items();" class="btn btn-mini btn-primary">Export</a>
			                   	<a href="javascript:delete_items();" class="btn btn-mini btn-danger">Delete</a>
			                   	
			                   	<form id="form_export" method="post" action="/admin/risk_assessment/export_items/">
			                   		<input id="ids" type="hidden" name="ids" value="" />
			                   		<input type="hidden" name="library_id" id="library_id" value="" />
			                   	</form>
			                   
			                   </div>
			                  
			                   
			                   <div class="pull-right">
			                   	<form method="get" class="form-inline" action="?">
			                   		<input type="text" class="form-control" style="width: 300px;" name="search" value="<?=$search?>" onkeyup="do_search(this.value)" placeholder="Search" />
			                   	</form>
			                   	<? if($search!=''){ ?>
			                   	<div class="pull-left" style="margin-left: 5px;"><a href="?search=" class="btn btn-mini btn-warning">Clear Search</a></div>
			                   	<? } ?>
			                     
			                     <!--<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
			                     <a href="#" class="wclose"><i class="icon-remove"></i></a>-->
			                   </div>
			                  
			                  <div class="clearfix"></div>
			                </div>
			
			                <div class="widget-content">
			                	<table  id="list" class="table table-striped table-bordered table-hover">
			                	    <thead>
			                	    <tr>
			                	    	<td width="30px"><input type="checkbox" value="" onclick="select_all_boxes(this.checked)" /></td>
										<td class="rowHeader"><?                 
							                if ($sort=='name') {
							                	if ($ord=='ASC') {
							                		echo  '<a href="'.$href1.'&sort=name&ord=DESC&Submit=Search">';
							                	} else {
							                		echo  '<a href="'.$href1.'&sort=name&ord=ASC&Submit=Search">';
							                	}
							                } else {
							                	echo  '<a href="'.$href1.'&sort=name&ord=ASC&Submit=Search">';
							                }
							                ?>Name</a></td>
										<td class="rowHeader"><?                 
							                if ($sort=='description') {
							                	if ($ord=='ASC') {
							                		echo  '<a href="'.$href1.'&sort=description&ord=DESC&Submit=Search">';
							                	} else {
							                		echo  '<a href="'.$href1.'&sort=description&ord=ASC&Submit=Search">';
							                	}
							                } else {
							                	echo  '<a href="'.$href1.'&sort=description&ord=ASC&Submit=Search">';
							                }
							                ?>Description</a></td>
										
										<td width="10%" class="rowHeader"><?                 
							                if ($sort=='status') {
							                	if ($ord=='ASC') {
							                		echo  '<a href="'.$href1.'&sort=status&ord=DESC&Submit=Search">';
							                	} else {
							                		echo  '<a href="'.$href1.'&sort=status&ord=ASC&Submit=Search">';
							                	}
							                } else {
							                	echo  '<a href="'.$href1.'&sort=status&ord=ASC&Submit=Search">';
							                }
							                ?>Status</a></td>
							                <td width="15%" class="rowHeader"><?                 
							                    if ($sort=='modified_on') {
							                    	if ($ord=='ASC') {
							                    		echo  '<a href="'.$href1.'&sort=modified_on&ord=DESC&Submit=Search">';
							                    	} else {
							                    		echo  '<a href="'.$href1.'&sort=modified_on&ord=ASC&Submit=Search">';
							                    	}
							                    } else {
							                    	echo  '<a href="'.$href1.'&sort=modified_on&ord=ASC&Submit=Search">';
							                    }
							                    ?>Modified on</a></td>
							                <td width="10%" class="rowHeader"><?                 
							                    if ($sort=='modified_by') {
							                    	if ($ord=='ASC') {
							                    		echo  '<a href="'.$href1.'&sort=modified_by&ord=DESC&Submit=Search">';
							                    	} else {
							                    		echo  '<a href="'.$href1.'&sort=modified_by&ord=ASC&Submit=Search">';
							                    	}
							                    } else {
							                    	echo  '<a href="'.$href1.'&sort=modified_by&ord=ASC&Submit=Search">';
							                    }
							                    ?>Modified by</a></td>
			                	    
			                	    
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
			                	            <tr  id="item_no_<?=$row['risk_assessment_id']?>">
			                	            	<td><input type="checkbox" name="items[<?=$row['risk_assessment_id']?>]" value="<?=$row['risk_assessment_id']?>" /></td>
												<td align="left" valign="top" class="rowDark"><?=$row['name']?></td>
												<td align="left" valign="top" class="rowDark"><?=$row['description']?></td>
												<td align="left" valign="top" class="rowDark"><?=$row['status']?></td>
			                	            	<td align="left" valign="top" class="rowDark"><?=isset($row['fmodified_on'])?$row['fmodified_on']:$row['modified_on']?></td>
			                	            	<td align="left" valign="top" class="rowDark"><?=isset($row['fmodified_by'])?$row['fmodified_by']:$row['modified_by']?></td>
			                	            	
			                	            	<!--<td align="center" valign="top" class="rowDark">
			                	            		<a style="text-decoration:none" class="btn btn-xs btn-warning" href="/admin/risk_assessment/edit/<?=$row['risk_assessment_id']?>/"><i class="icon-pencil"></i> </a>
			                	            		<a style="text-decoration:none" class="btn btn-xs btn-danger" href="javascript:confirm_delete('/admin/risk_assessment/do_delete/<?=$row['risk_assessment_id']?>/');"><i class="icon-remove"></i></a>
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
			                <? if($pagination['total_items']>0){ ?>
			                <div id="pagination" class="widget-foot">
			                  <div class="pull-left">
			                  	<?=$pagination['pagination']?>
			                  </div>
			                  	<div class="pull-left" style="margin-top: 15px; margin-left: 15px;">
			                  	Show 
			                  	</div>
			                  	<div class="pull-left">
			                  	<select class="form-control" onchange="set_items_per_page(this.value)" id="items_per_page" name="items_per_page" style="margin-top: 9px; margin-left: 5px;">
			                		<option value="5" <?=($items_per_page==5)?'selected="selected"':''?>>5</option>
			                		<option value="10" <?=($items_per_page==10)?'selected="selected"':''?>>10</option>
			                  		<option value="25" <?=($items_per_page==25)?'selected="selected"':''?>>25</option>
			                  		<option value="50" <?=($items_per_page==50)?'selected="selected"':''?>>50</option>
			                  		<option value="100" <?=($items_per_page==100)?'selected="selected"':''?>>100</option>
			                  	</select>
			                  	</div>
			                  	<div class="pull-left" style="margin-top: 15px; margin-left: 10px;">
			                  		Items
			                  		
			                  	</div>
			                  	<div class="pull-right" style="margin-top: 15px; margin-right: 10px;">
			                  		<? if($page==0)$page=1; ?>
			                  			Showing <?=(($page*$items_per_page)-$items_per_page+1)?>-<?=($page*$items_per_page)?> of <?=$pagination['total_items']?>
			                  	</div>
			                  <div class="clearfix"></div>
			                </div>
			                <? } ?>
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