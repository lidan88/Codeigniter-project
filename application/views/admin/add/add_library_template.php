<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Panel</title>
<!--HEAD-->
<? include(APPPATH."views/admin/head.inc.php"); ?>
<!-- /HEAD -->
<link rel="stylesheet" href="/style/library_template.css">  

<script>
var lc = [];
var current_group_id="";
var current_group_type="";
var total_groups=0;
var template = <?=$item['template']==''?'[]':$item['template']; ?>;

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
				$("#where_by").append("<option value='"+x+":"+ret[x].var_name+"'>"+ret[x].var_name+"</option>");
				//}
			}
			
			<? if($item['template']!=""){ ?>
			if(first_time)
				render_tree(template);
			<? } ?>
			
	},'json');
}
</script>
<script src="/js/library_template.js"></script>
<script>

function save_items()
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
	
	$.post("/admin/library_template/do_add/",{"id":$("#id").val(),
										"name":$("#name").val(),
										"library_id":$("#library_id").val(),
										"sort_by":$("#sort_by").val(),
										"order_by":$("#order_by").val(),
										"template":template},function(ret){
		
		//console.log(ret);
		top.location="/admin/library_template/main/";
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
          <a href="/admin/library_template/main/"> View All Templates </a>
          <span class="divider">/</span> 
          <a href="#" class="bread-current">Template</a>
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
	          	                  <div class="pull-left">Library Template</div>
	          	                  <div class="widget-icons pull-right">
	          	                    <!--<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	          	                    <a href="#" class="wclose"><i class="icon-remove"></i></a>-->
	          	                  </div>
	          	                  <div class="clearfix"></div>
	          	                </div>
	          	
	          	                <div class="widget-content">
	          	                  <div class="padd">
	          	
	          	                    <!-- Form starts.  -->
	          	                     <form class='form-horizontal' name='frm_add_library_template' method='post' action='/admin/library_template/do_add/' onSubmit='return form_validation(this);' enctype='multipart/form-data'>
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
											<td><select id="sort_by" name="sort_by">
												
											</select></td>
										</tr>
										<tr>
											<td>Sort Order:</td>
											<td><select id="order_by" name="order_by">
												<option value="ASC">ASC</option>
												<option value="DESC">DESC</option>
											</select></td>
										</tr>
										
										<!--<tr>
											<td>Group By:</td>
											<td><select>
												<option>None</option>
												<option>Name</option>
											</select></td>
										</tr>	-->
											<tr>
												<td class='capitalize'>&nbsp;</td>
												<td class='capitalize'><input name='Submit' id='Submit' class='btn btn-danger' onclick="save_items();" type='button' value='Save' /></td>
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