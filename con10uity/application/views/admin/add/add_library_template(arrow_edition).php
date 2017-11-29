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
	width: 5px;
}

.custom-menu {
    z-index:1000;
    position: absolute;
    background-color:#fff;
    
    border: 1px solid #ccc;
    padding: 5px 10px 5px 10px;
    cursor: pointer;
}

</style>
<script>
var lc = [];
var current_group="";
var total_groups=0;
var template = <?=$item['template']==''?'[]':$item['template']; ?>;

(function($){
    $.fn.disableSelection = function() {
        return this
                 .attr('unselectable', 'on')
                 .css('user-select', 'none')
                 .on('selectstart', false);
    };
})(jQuery);

function render_tree(tre,parent)
{
	parent = typeof parent == 'undefined'?0:parent;
	//console.log("running render_tree");
	var render_text = '';
	for(x in tre)
	{
		if(tre[x]==null)
			continue;
		//console.log(tre[x]);
		
		
		if(tre[x].type=="item")
		{
			$("#chk_"+tre[x].id).attr("checked","checked");
			add_column(tre[x].id,parent);
		}
		else {
			add_group(tre[x].id,tre[x].title);
			if(typeof tre[x].children!='undefined')
			{
				render_tree(tre[x].children,tre[x].id);
			}
		}
	}
	
	return render_text;
}


function form_validation(thisform) {

	if (thisform.name.value == '') {
		alert ('Name is required');
		thisform.name.focus();
		return false;
	}
	
	return true; 
}

$(document).ready(function(){
	var dp = $('.datepicker').datepicker().on('changeDate', function(ev) {
	  dp.datepicker('hide');
	});
	$('.timepicker').timepicker();
	$(".chosen-select").chosen();
	
	//$( "tr.plan_items" ).dblclick(function() {
	 // var item_no = $(this).attr('id').replace('item_', '');
	 // top.location="/admin/plans/edit_plan_item//"+item_no+"/";
	  
	//});
	
	$("BODY").on("click",".pointer-left",function(){
		var current_id = $(this).attr("data");
		var left_id = $(this).parent().prev().attr("data");
		var type=$(this).parent().prev().attr("type");
		
		//console.log(current_id);
		if(typeof left_id != 'undefined')
		{
			left_id = parseInt(left_id);
			current_id = parseInt(current_id);
			
			if(type=="item")
			{
				$(this).parent().prev().attr("type","group");
				total_groups++;
				$(this).parent().prev().attr("data","g"+total_groups);
				$(this).parent().prev().attr("id","group_"+left_id+"_"+current_id);
				$(this).parent().prev().find('span.title').html("<label>Group</label>");
				$(this).parent().prev().find('ul').append('<li data="'+left_id+'" type="item" parent="g'+total_groups+'" class="sub">'+lc[left_id].var_name+'</li>').append('<li data="'+current_id+'" type="item" parent="g'+total_groups+'" class="sub">'+lc[current_id].var_name+'</li>');
				$(this).parent().prev().find('.icon').hide();
			}else {
				//its a group
				var parent_id = $(this).parent().prev().attr("data");
				$(this).parent().prev().find('ul').append('<li data="'+current_id+'" type="item" parent="'+parent_id+'" class="sub">'+lc[current_id].var_name+'</li>');
			}
			
			$(this).parent().remove();
			refresh_sub_li();
			//$("#li_24").append("Hello");
		}
		else {
			//user is clicking on the first item
		}
	});
	
	$("BODY").disableSelection();
	$("BODY").on("contextmenu","span.title", function(event) {
		event.preventDefault();
		current_group=$(this).parent().attr('id');
		var t=$(this).parent().attr('type');
		if(t=="item")
			$(".custom-menu").find("[type='edit']").addClass("hidden");
		else {
			$(".custom-menu").find("[type='edit']").removeClass("hidden");
		}
		
		$(".custom-menu").removeClass('hidden').css({top: event.pageY-0 + "px", left: event.pageX-0 + "px"});
	});
	
	$("BODY").on("click",".pointer-right",function(){
		var current_id = $(this).attr("data");
		var right_id = $(this).parent().next().attr("data");
		var type=$(this).parent().next().attr("type");
		
		if(typeof right_id != 'undefined')
		{
			//console.log(right_id);
			right_id = parseInt(right_id);
			current_id = parseInt(current_id);
			
			if(type=="item")
			{
				$(this).parent().next().attr("type","group");
				total_groups++;
				$(this).parent().next().attr("data","g"+total_groups);
				$(this).parent().next().attr("id","group_"+right_id+"_"+current_id);
				$(this).parent().next().find('span.title').html("<label>Group</label>");
				$(this).parent().next().find('ul').append('<li data="'+right_id+'"  type="item" parent="g'+total_groups+'" class="sub">'+lc[right_id].var_name+'</li>').append('<li data="'+current_id+'"  type="item" parent="g'+total_groups+'" class="sub">'+lc[current_id].var_name+'</li>');
				$(this).parent().next().find('.icon').hide();
			}
			else {
				//its a group
				var parent_id = $(this).parent().next().attr("data");
				$(this).parent().next().find('ul').append('<li data="'+current_id+'" type="item" parent="'+parent_id+'" class="sub">'+lc[current_id].var_name+'</li>');
			}
			
			$(this).parent().remove();
			refresh_sub_li();
			//$("#col_"+right_id).append("*");
		}
		else {
			//user is clicking on the last item
		}
	});
	
	$("#layout").sortable({
		//items: "li:not(.disabled)",
		items:"li.root",
		helper:function(){ return '<span class="icon icon-pushpin"></span>'; },
	   	//connectWith: "#layout",
	    placeholder: "ui-state-highlight2"
	});
	
	$(".custom-menu").on({
	    mouseenter: function () {
	    },
	    mouseleave: function () {
			$(this).addClass('hidden');
			//$(this).css({"background-color":"blue"});
	    }/*,
	    click: function(){
	    	//alert($(this).attr("data"));
	    	$(this).addClass('hidden');
	    	//$('#li_emp_'+$(this).attr("data")).remove();
	    	//delete_item($('#li_emp_'+$(this).attr("data")));
	    }*/
	});
	
	<? if(is_numeric($item['library_id']) and $item['library_id']>0){ ?>
		select_library(<?=$item['library_id']?>,true);
	<? } ?>	
	
});

function refresh_sub_li()
{
	$(".layout_sub").sortable({
		//items: "li:not(.disabled)",
	   	//connectWith: "#layout",
	   	items: "li.sub",
	    placeholder: "ui-state-highlight"
	});
	
}

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
			}
			
			<? if($item['template']!=""){ ?>
			if(first_time)
				render_tree(template);
			<? } ?>
			
	},'json');
}

function add_column(column_id,parent)
{
	parent = typeof parent == 'undefined'?0:parent;

	if(parent=="0")
		$("#layout").append("<li class='root' type='item' data='"+column_id+"' parent='0' id='li_"+column_id+"'><span data='"+column_id+"' class='pointer-left icon icon-chevron-left'></span>&nbsp;<span class='title'><label>"+lc[column_id].var_name+"</label></span>&nbsp;<span data='"+column_id+"' class='pointer-right icon icon-chevron-right'></span>  <ul class='layout_sub'></ul> </li>");
	else {
		$("#"+parent).find('ul').append('<li data="'+column_id+'" type="item" parent="'+parent+'" class="sub">'+lc[column_id].var_name+'</li>');
	}
}

function add_group(group_id,title)
{
	$("#layout").append("<li class='root' type='group' data='"+group_id+"' parent='0' id='"+group_id+"'><span class='title'><label>"+title+"</label></span> <ul class='layout_sub'></ul> </li>");
}

function modify_layout(column_id,$obj) {
	if($obj.is(':checked'))
	{
		add_column(column_id);
	}
	else {
		$("#li_"+column_id).remove();
		$("li[data='"+column_id+"']").remove();
	}
}

function edit_group()
{
	$(".custom-menu").addClass("hidden");
	$("#edit_modal").modal("show");
}

function save_group_title()
{
	var title = $("#group_title").val();
	$("#edit_modal").modal("hide");
	$("#"+current_group).find("label").html(title);
	$("#group_title").val("");
}

var unflatten = function( array, parent, tree ){

    tree = typeof tree !== 'undefined' ? tree : [];
    parent = typeof parent !== 'undefined' ? parent : { id: 0 };

    var children = _.filter( array, function(child){ return child.parentid == parent.id; });

    if( !_.isEmpty( children )  ){
        if( parent.id == 0 ){
           tree = children;   
        }else{
           parent['children'] = children
        }
        _.each( children, function( child ){ unflatten( array, child ) } );                    
    }

    return tree;
};

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
		console.log(item_id+" - "+parent_id+" - "+title);
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