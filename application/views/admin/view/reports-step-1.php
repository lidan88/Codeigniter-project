<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Library</title>

<!--HEAD-->
<? include(APPPATH."views/admin/head.inc.php"); ?>
<link rel="stylesheet" href="/style/library_template.css">  

<script>

var ids_to_hide = [];
var total_groups=0;
var selected_module=0;
var selected_report_template=0;

(function($){
    $.fn.disableSelection = function() {
        return this
                 .attr('unselectable', 'on')
                 .css('user-select', 'none')
                 .on('selectstart', false);
    };
})(jQuery);

Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

function select_module(value)
{
	if(value=="")
	{
		alert("Please select a module from the list below");
		return false;
	}
	
	var result = value.split(":");
	var id = result[0];
	var lib_type = result[1];
	if(lib_type=="lib" || lib_type=="gallery")
	{
		selected_module=id;
		select_library(id,true,lib_type);
	}
	else if (lib_type=="cc")
	{
		//load_callchain();
		$(".custom_report_template").addClass("hidden");
	}
	else if (lib_type=="ra")
	{
		$(".custom_report_template").addClass("hidden");
		//alert("Loading risk assessment");
		//load_ra();
	}
	else if (lib_type=="bia")
	{
		selected_module=id;
		select_library(id,true,lib_type);	
	}
}

function select_library(library_id,auto_clean,lib_type) {
	auto_clean = typeof auto_clean=='undefined'?true:auto_clean;
	lib_type = typeof lib_type=='undefined'?'lib':lib_type;
	
	current_type=lib_type;
	current_library_id=library_id;
	
	select_template("0");
	/*$.post("/admin/reports/get_reports_by_library_id/",{"library_id":library_id},function(ret){
		
		$("#template_id").html("<option value=''>Select Template</option><option value='0'>New Report Template</option>");
		
		$("#reports_template").removeClass("hidden");
		
		if(ret!=false)
		{
			for(x in ret)
			{
				$("#template_id").append("<option value='"+ret[x].id+"'>"+ret[x].name+"</option>");
			}
		}
		$("#template_id").trigger("chosen:updated");
	},'json');*/
	
}

function select_template(report_id)
{
	if(report_id=="")
	{
		alert("Please choose your report template");
		return false;
	}
	
	selected_report_template=report_id;
	
	if(report_id=="0")
	{
		$.post("/admin/reports/get_columns/",{"library_id":selected_module},function(ret) {
			console.log(ret);
				lc=ret;

			$("#ul_columns").html("");
			$("#layout").html("");
			$("#sory_by").html("");
			for (x in ret) {
				$("#ul_columns").append("<li><div class='checkbox'><label><input id='chk_"+x+"' onclick='modify_layout(this.value,$(this));' type='checkbox' value='"+x+"' />&nbsp;"+ret[x].var_name+"</label></div> </li>");
				$("#sort_by").append("<option value='"+x+":"+ret[x].var_name+"'>"+ret[x].var_name+"</option>");
				$("#where_by").append("<option value='"+x+":"+ret[x].var_name+"'>"+ret[x].var_name+"</option>");
				//}
			}
			
			$(".custom_report_template").removeClass("hidden");
							
		},'json');
	}
	else {
			$(".custom_report_template").addClass("hidden");
	}
}


</script>
<script src="/js/library_template.js"></script>
<script>

function save_items()
{
	var template='';
	var items_to_save=[];
	$("#layout li:not(.empty)").each(function(index,itm){
		
		var item_id = $(itm).attr("data");
		var parent_id = $(itm).attr("parent");
		var type = $(itm).attr("type");
		var title = $(itm).find('span.title > label').html();
		if(typeof title =='undefined')
			title = $(itm).html();
			
		var show_attr = $(itm).attr("show_attr");
			if(show_attr=="")
				show_attr="0";
		
		items_to_save.push({'id':item_id,'parentid':parent_id,'title':title,'type':type, 'show_attr':show_attr});
	});	
	
	template= JSON.stringify(unflatten( items_to_save ));
	$.post("/admin/reports/do_add/",{"id":$("#id").val(),
										"name":$("#name").val(),
										"header":$("#header").val(),
										"footer":$("#footer").val(),
										"module_id":$("#module_id").val(),
										"report_id":$("#template_id").val(),
										"sort_by":$("#sort_by").val(),
										"order_by":$("#order_by").val(),
										"template":template},function(report_id){
		
		//console.log(report_id);
		top.location="/admin/reports/step/2/"+report_id;
	});
	
}
</script>
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
		  <a href="/home/"><i class="icon-home"></i> Home</a> 
		  <span class="divider">/</span> 
		  <a href="/admin/reports/main/">Reports </a>
		  <span class="divider">/</span> 
		  <a href="#" class="bread-current">New </a>
		  
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
			                  <div class="pull-left">New Report &raquo; Step 1 (Templates)</div>
			                  <div class="widget-icons pull-right">
			                    <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
			                    <a href="#" class="wclose"><i class="icon-remove"></i></a>
			                  </div>  
			                  <div class="clearfix"></div>
			                </div>
			
			                <div class="widget-content">
			                	
			                	<table class="table">
			                		<tr>
			                			<td>Name:</td>
			                			<td><input class="form-control" type="text" name="name" id="name" value="" /></td>
			                		</tr>
			                		<tr>
			                			<td>Header:</td>
			                			<td><textarea name="header" id="header" class="form-control"></textarea></td>
			                		</tr>
			                		<tr>
			                			<td>Footer:</td>
			                			<td><textarea name="footer" id="footer" class="form-control"></textarea></td>
			                		</tr>
			                		
			                		<tr>
		                				<td width="20%" valign="top">
		                				Module:
		                				</td>
		                				<td>
		                					<select id="module_id" name="module_id" class="form-control chosen-select" onchange="select_module(this.value)">
		                						<option value="">Select Module</option>
		                						<?
		                							foreach ($modules_list as $key => $value) {
		                								echo '<option value="'.$value['module_id'].':'.$value['type'].'">'.$value['name'].'</option>';
		                							}
		                						?>
		                					</select>
		                				</td>
		                			</tr>
		                			<!--
		                			<tr id="reports_template" class="hidden">
		                				<td>	
		                					Report Template:
		                				</td>
		                				<td>
		                					<select data-placeholder="Select Template" class="form-control" name="template_id" id="template_id" onchange="select_template(this.value);">
		                						<option value="0">Default</option>
		                					</select>
		                				</td>
		                				
		                			</tr>
		                			-->
		                			<tr class="custom_report_template hidden">
		                				<td>
		                					Columns to Show
		                					<ul id="ul_columns" class="nav">
		                						
		                					</ul>
		                				</td>
		                				<td style="background-color: white; border-left: 1px solid #ccc;">
		                					<strong>Layout</strong>
		                					<ul id="layout" class="layout">
		                						
		                					</ul>
		                				</td>
		                			</tr>
		                			<tr class="custom_report_template hidden">
		                				<td>Sort By:</td>
		                				<td><select id="sort_by" name="sort_by">
		                					
		                				</select></td>
		                			</tr>
		                			<tr class="custom_report_template hidden">
		                				<td>Sort Order:</td>
		                				<td><select id="order_by" name="order_by">
		                					<option value="ASC">ASC</option>
		                					<option value="DESC">DESC</option>
		                				</select></td>
		                			</tr>
			                		
			                	</table>
			                		
			                </div>
			                <div class="widget-foot">
			                	 <button onclick="save_items()" class="btn btn-danger">Next</button>
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

<div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Edit Group Title</h4>
      </div>
      <div class="modal-body">
        <input type="text" name="group_title" class="form-control" id="group_title" value="" placeholder="Group Title" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="save_group_title()">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="custom-menu hidden">
	<div type="edit" onclick="edit_group()">Edit</div>
	
	<div type="remove" onclick="remove_group()">Remove</div>
	<div type="show_attr" id="show_attr" onclick="show_attr();">Show Attribute</div>
	<div type="show_attr" id="hide_attr" onclick="hide_attr();">Hide Attribute</div>
</div>


</body>
</html>