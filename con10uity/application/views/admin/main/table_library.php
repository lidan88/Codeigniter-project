<?
$sort=isset($_REQUEST['sort'])?$_REQUEST['sort']:"id";
$ord=isset($_REQUEST['ord'])?$_REQUEST['ord']:"ASC";
$page=isset($_REQUEST['page'])?$_REQUEST['page']:0;
$search=isset($_REQUEST['search'])?$_REQUEST['search']:'';
$submit=isset($_REQUEST['Submit'])?$_REQUEST['Submit']:"";
$items_per_page=isset($_REQUEST['items_per_page'])?$_REQUEST['items_per_page']:10;

$items_per_page_value = "sort=$sort&ord=$ord&items_per_page=";
$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
$href1=$_SERVER['PHP_SELF']."?";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Panel</title>
<!--HEAD-->
	<? include(APPPATH."views/admin/head.inc.php"); ?>
<!-- /HEAD -->
<script type="text/javascript">
	function delete_items()
	{
		var p = confirm("Are you sure you want to delete the selected items?");
		if(p)
		{
			var searchIDs = $("input:checkbox:checked").map(function(){
		      return $(this).val();
		    }).get();
			
			searchIDs = _.compact(searchIDs);			
			
			if(searchIDs.length>0)
			{
				$.post("/admin/library/delete_user_values/",{"ids":searchIDs.join(",")},function(ret){
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
				/*$.post("/admin/library/delete_user_values/",{"ids":searchIDs.join(",")},function(ret){
					//console.log(ret);
					
					for(x in searchIDs)
					{
						$("#item_no_"+searchIDs[x]).addClass("hidden");
					}
				});*/
				top.location="/admin/library_items/edit_user_data/"+searchIDs[0];
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
				$.post("/admin/library_items/do_copy/",{"id":searchIDs[0]},function(ret){
					//console.log("returned from copy");
					//console.log(ret);
					top.location.href = "?copied=1";
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
	
	
	function export_items(library_id)
	{
		var searchIDs = $("input:checkbox:checked").map(function(){
		  return $(this).val();
		}).get();
		
		if(searchIDs.length>0)
		{
			$("#form_export").find("input#ids").val(searchIDs.join(","));
			$("#form_export").get(0).submit();
			/*$.post("/admin/library_items/export_items/",{"library_id":library_id,"ids":searchIDs.join(",")},function(ret){
				console.log(ret);
				
				//for(x in searchIDs)
				{
					$("#item_no_"+searchIDs[x]).addClass("hidden");
				}
				
				
			});*/
		}
		else {
			if(confirm("Are you sure you want to export all items?"))
			{
				$("#form_export").get(0).submit();
			}
		}
	}
	//## MAlkoo Feat	
	function do_search(search) {
		//if(search.length>0)
		//{
			$.post("/admin/library/table_view/<?=$library_details['id']?>/?output=ajax&search="+search,{},function(ret){
				var test = ret;
				var content = test.substring(0, test.indexOf('<div id="pagination">'));
				var pagination = test.substring(test.indexOf('<div id="pagination">'), test.length);
				$('#list').find('tbody').html(content);
				$('#pagination').html(pagination);
				//console.log(pagination);
				//var dom = $(test).find("#pagination");
				//console.log(dom);
				/*
				var content = dom.length > 0 ? dom[0].innerHTML:'No data';
				
				$('#list').find('tbody').html(ret);
				$('#pagination').html(content);*/
			});
		//}
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

	<? if($CI->permission_lib->has_permission($lpn,"edit")){ ?>
	$(document).ready(function(){
		$( "tr" ).dblclick(function() {
		  //alert( "Hello World! "+$(this).attr('id') );
		  var item_no = $(this).attr('id').replace('item_no_', '');
		  top.location="/admin/library_items/edit_user_data/"+item_no+"/";
		  
		});
	});
	<? } ?>
	
</script>
</head>
<body>
<!--HEADER-->
	<? include(APPPATH."views/admin/header.inc.php"); ?>
<!-- /HEADER-->

<!-- /container -->
<div class="content">

	<!-- Sidebar -->
	<? $inside_library=true; $selected_library_id=$library_details['id']; include(APPPATH."views/admin/sidebar.inc.php"); ?>
    <!-- Sidebar ends -->

  	<!-- Main bar -->
  	<div class="mainbar">

      <!-- Page heading -->
      <div class="page-head">
        <!-- Breadcrumb -->
        <div class="bread-crumb pull-left">
          <a href="/super_admin/"><i class="icon-home"></i> Home</a> 
          <span class="divider">/</span> 
          <a href="/admin/library/listit/"> Library </a>
          <span class="divider">/</span> 
          <a href="#" class="bread-current"><?=$library_details['name']?></a>
        </div>

        <div class="clearfix"></div>

      </div>
      <!-- Page heading ends -->

	    <!-- Matter -->
	    <div class="matter">
	        <div class="container">
	          
	          	<? if(isset($_GET['copied'])){ ?>
	          	<div class="row">
	          		<div class="col-md-12">
		          		<div class="alert alert-success">
		          		<strong>Information Copied</strong>
		          		</div>
	          		</div>
	          	</div>
	          	<? } ?>
	          
	          
	          	<div class="row">
	          	
	          	            <div class="col-md-12">
	          	
	          	              <div class="widget wgreen">
	          	                
	          	                <div class="widget-head">
	          	                  <div class="pull-left"><?=$library_details['name']?></div>
	          	                  <div class="pull-left" style="margin-left: 20px;">
	          	                  
	          	                  	<? if($CI->permission_lib->has_permission($lpn,"add")){ ?>
	          	                  		<a href="/admin/library_items/enter_user_data/<?=$library_details['id']?>/" class="btn btn-mini btn-primary">Add</a>
	          	                  	<? } ?>
	          	                  	<? if($CI->permission_lib->has_permission($lpn,"edit")){ ?>
	          	                  		<a href="javascript:edit_item();" class="btn btn-mini btn-primary">Edit</a>
	          	                  		<a href="javascript:copy_item();" class="btn btn-mini btn-success">Copy</a>
	          	                  	<? } ?>
	          	                  	<? if($CI->permission_lib->has_permission($lpn,"export")){ ?>
	          	                  		<a href="javascript:export_items(<?=$library_details['id']?>);" class="btn btn-mini btn-primary">Export</a>
	          	                  	<? } ?>
	          	                  	<? if($CI->permission_lib->has_permission($lpn,"delete")){ ?>
	          	                  		<a href="javascript:delete_items();" class="btn btn-mini btn-danger">Delete</a>
	          	                  	<? } ?>
	          	                  	
	          	                  	<form id="form_export" method="post" action="/admin/library_items/export_items/">
	          	                  		<input id="ids" type="hidden" name="ids" value="" />
	          	                  		<input type="hidden" name="library_id" id="library_id" value="<?=$library_details['id']?>" />
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
	          	
	          	                <div class="widget-content" style="overflow-x: auto;">
	          	                  <div class="padd">
	          	
	          	                   	<table id="list" class="table table-stripped table-rounded">
	          	                   		<thead>
	          	                   		<tr>
	          	                   			<th width="10"><input type="checkbox" value="" onclick="select_all_boxes(this.checked)" /></th>
	          		          	            <?
	          	                   			if(is_array($library_fields))
	          	                   			foreach($library_fields as $field)
	          	                   			{
	          	                   				if(is_field_skippable($field))
	          	                   					continue;
	          	                   			?>
	          	                   			<th>
	          	                   				<?	
	          	                   				if ($sort==$field['id']) {
	          	                   					if ($ord=='ASC') {
	          	                   						echo  '<a href="'.$href1.'&sort='.$field['id'].'&ord=DESC&items_per_page='.$items_per_page.'&Submit=Search">';
	          	                   					} else {
	          	                   						echo  '<a href="'.$href1.'&sort='.$field['id'].'&ord=ASC&items_per_page='.$items_per_page.'&Submit=Search">';
	          	                   					}
	          	                   				} else {
	          	                   					echo  '<a href="'.$href1.'&sort='.$field['id'].'&ord=ASC&items_per_page='.$items_per_page.'&Submit=Search">';
	          	                   				}
	          	                   				?>
	          	                   					<?=$field['var_name']?>
	          	                   				</a>
	          	                   			</th>
	          	                   			<? } ?>
	          	                   			<th>
	          	                   			<?
	          	                   			if ($sort=='modified_at') {
	          	                   				if ($ord=='ASC') {
	          	                   					echo  '<a href="'.$href1.'&sort=modified_at&ord=DESC&items_per_page='.$items_per_page.'&Submit=Search">';
	          	                   				} else {
	          	                   					echo  '<a href="'.$href1.'&sort=modified_at&ord=ASC&items_per_page='.$items_per_page.'&Submit=Search">';
	          	                   				}
	          	                   			} else {
	          	                   				echo  '<a href="'.$href1.'&sort=modified_at&ord=ASC&items_per_page='.$items_per_page.'&Submit=Search">';
	          	                   			}
	          	                   			?>
	          	                   			Modified On</a>
	          	                   			</th>
	          	                   			<th>
	          	                   			<?
	          	                   			if ($sort=='modified_by_uid') {
	          	                   				if ($ord=='ASC') {
	          	                   					echo  '<a href="'.$href1.'&sort=modified_by_uid&ord=DESC&items_per_page='.$items_per_page.'&Submit=Search">';
	          	                   				} else {
	          	                   					echo  '<a href="'.$href1.'&sort=modified_by_uid&ord=ASC&items_per_page='.$items_per_page.'&Submit=Search">';
	          	                   				}
	          	                   			} else {
	          	                   				echo  '<a href="'.$href1.'&sort=modified_by_uid&ord=ASC&items_per_page='.$items_per_page.'&Submit=Search">';
	          	                   			}
	          	                   			?>
	          	                   			Modified By</a></th>
	          	                   			<th>Actions</th>
	          	                   		</tr>
	          	                   		</thead>
	          	                   		<tbody>
	          	                   			
	          	                   			<? foreach($values as $value_id => $item){ ?>
	          	                   			<tr id="item_no_<?=$value_id?>">
	          	                   				
	          	                   				<td><input type="checkbox" name="items[<?=$value_id?>]" value="<?=$value_id?>" /> <? //$items[$value_id]['unique_id']?></td>
	          	                   				
	          	                   				<?php foreach($library_fields as $field)
	          	                   					{
	          	                   						if(is_field_skippable($field))
	          	                   							continue;
	          	                   					
	          	                   						if(!isset($item[$field['id']]))
	          	                   						{
	          	                   							echo '<td></td>';
	          	                   						}else {
	          	                   				?>
	          	                   						<td>
	          	                   						<? if(!is_array($item[$field['id']])){
	          	                   								
	          	                   								if($field['var_type']=='F')
	          	                   								{
	          	                   									?>
	          	                   									<a target="_blank" href="/user_data/<?=$item[$field['id']]?>"><?=$item[$field['id']]?></a>
	          	                   									<?
	          	                   								}
	          	                   								else {
	          	                   									echo makelink(opt2value($item[$field['id']]));
	          	                   								}
	          	                   							}
	          	                   							else {
	          	                   								echo array_recursive_value($item[$field['id']]);
	          	                   							}
	          	                   						 ?>
	          	                   						
	          	                   						<?//  echo hl($item[$field['id']], arrya($search));?></td>
	          	                   				<?
	          	                   						}
	          	                   					}
	          	                   				?>
	          	                   				<td><?=$items[$value_id]['modified_at']?></td>
	          	                   				<td><?=$items[$value_id]['modified_by']?></td>
	          	                   				<td><a href="/admin/library_items/view/<?=$value_id?>/">Full View</a></td>
	          	                   			</tr>
	          	                   			<? } ?>
	          	                   			
	          	                   		</tbody>
	          	                   	</table>
	          	                   	          	                     
	          	                  </div>
	          	                </div>
	          	                  <div id="pagination" class="widget-foot">
	          	                    <div class="pull-left">
	          	                    	<?=$pagination?>
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
	          	                    		Showing <?=(($page*$items_per_page)-$items_per_page+1)?>-<?=$library_values_count<($page*$items_per_page)?$library_values_count:($page*$items_per_page)?> of <?=$library_values_count?>
	          	                    	</div>
	          	    	            <div class="clearfix"></div>
	          	                  </div>
	          	              </div>  
	          	
	          	            </div>
	          	
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