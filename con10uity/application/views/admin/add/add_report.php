<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Panel</title>
<!--HEAD-->
<? include(APPPATH."views/admin/head.inc.php"); ?>
<!-- /HEAD -->
<style>
.ui-state-highlight2{
	border: 3px solid yellow;
	width: 20px;
	height: 20px;
}

.custom-menu {
    z-index:1000;
    position: absolute;
    background-color:#fff;
    
    border: 1px solid #ccc;
    padding: 5px 10px 5px 10px;
    cursor: pointer;
}

.empty{
	width: 20px;
	height: 20px;
	border: 1px dashed gray;
}


li.root{
	border: 2px solid #ffffff;
}

.pointer {
	cursor: pointer;
}

.li_hover{
	/*border: 1px solid #efefef;*/
	/*border-left: 1px solid #333;*/
	border: 1px solid #333;
	/*font-weight: bold;*/
	color: green;
	padding: 3px;
}

</style>
<script>
var lc = [];
var current_group_id="";
var current_group_type="";
var total_groups=0;
var global_tr=1;
var template = <?=$item['template']==''?'[]':json_encode($item['template']); ?>;
var filter_by = <?=$item['filter_by']==''?'[]':json_encode($item['filter_by']);?>;
</script>
<script src="/js/library_template.js"></script>
<script>

function copy_where()
{
	global_tr++;
	$tr = $("#where_template").clone();
	
	$tr.attr("id","where_template_"+global_tr);
	$tr.find(".where_column").attr("id","where_column_"+global_tr);
	$tr.find(".where_op").attr("id","where_op_"+global_tr);
	$tr.find(".where_filter").attr("id","where_filter_"+global_tr);
	
	$tr.appendTo("#where_table");
}


$(document).ready(function(){	
	<? if(is_numeric($item['library_id']) and $item['library_id']>0){ ?>
		select_library(<?=$item['library_id']?>,true);
	<? } ?>	

});

function select_library(library_id,first_time)
{
	first_time = typeof first_time == 'undefined'?false:first_time;
	$.post("/admin/library_template/get_columns/",{"library_id":library_id},function(ret) {
		//console.log(ret);
			lc=ret;
			
			$("#ul_columns").html("");
			$("#layout").html("");
			$("#sory_by").html("");
			for (x in ret) {
				$("#ul_columns").append("<li><div class='checkbox'><label><input id='chk_"+x+"' onclick='modify_layout(this.value,$(this));' type='checkbox' value='"+x+"' />&nbsp;"+ret[x].var_name+"</label></div> </li>");
				$("#sort_by").append("<option value='"+x+":"+ret[x].var_name+"'>"+ret[x].var_name+"</option>");
				$(".where_column").append("<option value='"+x+":"+ret[x].var_name+"'>"+ret[x].var_name+"</option>");
				//}
			}
			
			<? if($item['template']!=""){ ?>
			if(first_time)
				render_tree(template);
			<? } ?>
			
			if(filter_by.length>0)
			{
				//console.log(filter_by.length);
				var ctr=0;
				for (x in filter_by) {
					ctr++;
					if(ctr>1)
						copy_where();
				
					$("#where_column_"+global_tr).val(filter_by[x].c);	
					$("#where_filter_"+global_tr).val(filter_by[x].f);
					
					console.log("filtering -> "+filter_by[x].c);	
				
				}
			}
			
	},'json');
}



function save_items(is_print)
{
	var items_to_save=[];
	$("#layout li:not(.empty)").each(function(index,itm){
		
		var item_id = $(itm).attr("data");
		var parent_id = $(itm).attr("parent");
		var type = $(itm).attr("type");
		var title = $(itm).find('span.title > label').html();
		if(typeof title =='undefined')
			title = $(itm).html();
		
		items_to_save.push({'id':item_id,'parentid':parent_id,'title':title,'type':type});
		//console.log(item_id+" - "+parent_id+" - "+title);
		//var parent_id = $(itm).parent().parent().attr("id").replace('li_emp_','').replace('emp_','');
		//if(emp_id>0)
		//	items_to_save.push({'id':emp_id,'parentid':parent_id});
		//console.log("reading-> "+itm.id+ " -parent:  "+$(itm).parent().parent().attr("id"));
	});	
	
	var template= JSON.stringify(unflatten( items_to_save ));
	//console.log(template);
	
	var whereArray = [];
	$("#where_table tr").each(function(){
		var v1 = $(this).find(".where_column").val();
		var v2 = $(this).find(".where_op").val();
		var v3 = $(this).find(".where_filter").val();
		
		whereArray.push({"c":v1,"o":v2,"f":v3});
	});
	
	var filter_by = JSON.stringify(whereArray);
	
	$.post("/admin/reports/do_add/",{"id":$("#id").val(),
										"name":$("#name").val(),
										"library_id":$("#library_id").val(),
										"sort_by":$("#sort_by").val(),
										"order_by":$("#order_by").val(),
										"filter_by":filter_by,
										"template":template},function(ret){
		
		//console.log(ret);
		if(!is_print)
			top.location="/admin/reports/main/";
		else {
			top.location="/admin/reports/export/"+ret+"?output=pdf";
		}
	});
	
}

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
          <a href="/admin/library_template/main/"> View All Reports </a>
          <span class="divider">/</span> 
          <a href="#" class="bread-current">Report</a>
        </div>

        <div class="clearfix"></div>

      </div>
      <!-- Page heading ends -->

	    <!-- Matter -->
	    <div class="matter">
	        <div class="container">
	
					            
	          
	          	<div class="row">
	          	
	          	            <div class="col-md-12">
	          	
	          	
	          	              <div class="widget wgreen">
	          	                
	          	                <div class="widget-head">
	          	                  <div class="pull-left">Report</div>
	          	                  <div class="widget-icons pull-right">
	          	                    <!--<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	          	                    <a href="#" class="wclose"><i class="icon-remove"></i></a>-->
	          	                  </div>
	          	                  <div class="clearfix"></div>
	          	                </div>
	          	
	          	                <div class="widget-content">
	          	                  <div class="padd">
	          	
	          	                    <!-- Form starts.  -->
	          	                     <form class='form-horizontal' name='frm_add_library_template' method='post' action='/admin/reports/do_add/' onSubmit='return form_validation(this);' enctype='multipart/form-data'>
	          	                     <table class="table table-bordered table-striped table-hover content" style="width:100%;" cellpadding="0" cellspacing="0">
	          	                     <input type='hidden' id='chaabee_post' name='chaabee_post' value=''>
	          	                     <input type='hidden' id='id' name='id' value='<?=$item['id']?>'>
	          	                     
										<tr>
											<td class='capitalize' valign='top' align='left'>Name:</td>
											<td class='capitalize'><input  class='form-control ' type='text' size=40 name='name' id="name"  value='<?=$item['name']?>'  /></td>
										</tr>
										<tr>
											<td width='15%' class='capitalize' valign='top' align='left'>Library :</td>
											<td class='capitalize'><select class='form-control chosen-select' name='library_id' id='library_id' onchange="select_library(this.value);">
											<option value="">Select Library</option>
											<?
												
												if(is_array($library_list))
												{
													foreach ($library_list as $data)
													{
														echo "<option value='".$data['id']."' ".((isset($item) and $item['library_id']==$data['id'])?'selected="selected"':"").">".(isset($data['name'])?$data['name']:$data['library'])."</option>";
													}
												}
												?></select></td>
											</tr>
										<tr>
											<td colspan="2" style="padding: 0px;">
												<table id="library_items" class="table table-bordered">
													<thead>
														<tr>
															<th width="15%">Columns to Show</th>
															<th>Layout</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>
																<ul id="ul_columns" class="nav">
																	
																</ul>
															</td>
															<td>
																<ul id="layout" class="layout">
																	
																</ul>
															</td>
														</tr>
													</tbody>
												</table>
											
											</td>
											
										</tr>
										<tr>
											<td>Sort By:</td>
											<td><select class="form-control" id="sort_by" name="sort_by">
												
											</select></td>
										</tr>
										<tr>
											<td>Sort Order:</td>
											<td><select class="form-control" id="order_by" name="order_by">
												<option value="ASC">ASC</option>
												<option value="DESC">DESC</option>
											</select></td>
										</tr>
										<tr>
											<td>Where:</td>
											<td style="border: none;border-bottom: none;">
												<table id="where_table" class="notable" border="0" cellpadding="0" cellspacing="0">
													<tr id="where_template">
														<td>
															<select class="form-control where_column" id="where_column_1">
															</select>
														</td>
														<td>
															<select class="form-control where_op" id='where_op_1'>
																<option value="=">=</option>
															</select>
														</td>
														<td><input type="text" class="form-control where_filter" id="where_filter_1" value="" /></td>
														<td> 
															<div style="padding-top: 10px;">
																<a href="javascript:copy_where();"><i style="color: green;" class="icon icon-plus"></i></a>
															</div>
														</td>
												</table>
											</td>
										</tr>
										<tr>
											<td class='capitalize'>&nbsp;</td>
											<td class='capitalize'>
												<input name='Submit' id='Submit' class='btn btn-danger' onclick="save_items(false);" type='button' value='Save Report' />
												&nbsp;
												<input name='Submit' id='Submit' class='btn btn-danger' onclick="save_items(true);" type='button' value='Save And Print Report' />
											</td>
											</tr>
										</table>
									</form>
	          	                  </div>
	          	                </div>
	          	                  <div class="widget-foot">
	          	                    <!-- Footer goes here -->
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
</div>

</body>
</html>