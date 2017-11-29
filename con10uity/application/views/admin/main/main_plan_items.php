<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Plans</title>

<!--HEAD-->
<? include(APPPATH."views/admin/head.inc.php"); ?>
<script>

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

var library=<?=$this->library_lib->get_library_json();?>;
var current_library_id=0;
var current_group_id='';
var folders = {};
var ids_to_hide = {};

function update_group_title()
{
	folders[current_group_id]=$("#group_title").val();
	$("#ul-"+current_group_id).find("span.title").html($("#group_title").val());
	$("#editGroupModal").modal("hide");
}

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
			$.post("/admin/plans/do_delete_multi/",{"ids":searchIDs.join(",")},function(ret){
				
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
		$("[type='checkbox'].chk").attr("checked","checked");
		$("[type='checkbox'].chk").prop('checked', true);
	}
	else {
		$("[type='checkbox'].chk").removeAttr("checked");
		$("[type='checkbox'].chk").prop('checked', false);
		
	}
}

function de_select_all()
{
	$("[type='checkbox'].chk").removeAttr("checked");
	$("[type='checkbox'].chk").prop('checked', false);
}

function toggle_folder($ul) {

	if($ul.hasClass("open"))
	{
		$ul.removeClass("open").find("li").addClass("hidden");
		$ul.find("label > span.icon").removeClass("icon-folder-open").addClass("icon-folder-close");
	}
	else {
		$ul.addClass("open").find("li").removeClass("hidden");
		$ul.find("label > span.icon").removeClass("icon-folder-close").addClass("icon-folder-open");
	}
}

function select_selected_items()
{
	$("#folderModal").modal("show");
	//$("#folders_list").find("option[library!='"+current_library_id+"']").attr("disabled","disabled");
}

function generate_new_folder(library_id,title)
{
	var new_folder_id = Object.size(folders)+1; //folders.length+1;
	//folders.push(library_id+":"+new_folder_id+":"+title);
	folders[library_id+"-"+new_folder_id] = title;
	$("#folders_list").append('<option library="'+library_id+'" value="'+library_id+'-'+new_folder_id+'">'+title+'</option>');
	return new_folder_id;
}

function move_to()
{
	var suitable_title = $("#suitable_title").val();
	var selected_folder_id = $("#folders_list").val();
	var ul_id="";
	if(suitable_title.length==0 && selected_folder_id==0)
	{
		alert("Please select the folder or enter new title for the folder");
		return;
	}
	
	if(selected_folder_id!="0")
	{
		//check if user is added to the same library type or not.
		var temp = selected_folder_id.split('-');
		if(temp[0]!=current_library_id)
		{
			$("#folders_list").val("0");
			alert("Please select a compatible table");
			$("#suitable_title").focus();
			return;
		}
	
		ul_id = 'ul-'+selected_folder_id; //current_library_id+':'+selected_folder_id;
		var $ul = $('#'+ul_id);
	}
	else {
		var new_folder_id = generate_new_folder(current_library_id,suitable_title);
		ul_id = 'ul-'+current_library_id+'-'+new_folder_id;
		var $ul = $('<ul id="'+ul_id+'" class="library open"><label onclick="toggle_folder($(this).parent());"><span class="icon icon-folder-open"></span>&nbsp;<span class="title">'+suitable_title+'</span></label></ul>');
		$("#items_list").append($ul);
		
		$ul.find("label").bind("contextmenu", function(event) {
		  event.preventDefault();
		  
		  var lib_id = $(this).parent().attr('id');
		  
		  $(".custom-menu-root")
		  	.attr('data-id',lib_id)
		  	.removeClass('hidden')
		    .css({top: event.pageY-0 + "px", left: event.pageX-0 + "px"});
		
		});
	}

	var searchIDs = $("input:checkbox:checked.chk").map(function(){
	  return $(this).val();
	}).get();
	
	if(searchIDs.length>0)
	{
		for (x in searchIDs) {
			ids_to_hide[searchIDs[x]]=searchIDs[x];
			
			$("#item_no_"+searchIDs[x]).fadeOut("slow");
			var data = $("#item_no_"+searchIDs[x]).find("td").first().attr("data");
			var $li_item = $('<li id="li_'+searchIDs[x]+'" data-id="'+searchIDs[x]+'"><span class="icon icon-ok"></span>&nbsp;'+data+'</li>');
			$ul.append($li_item);
			
		}
		
		$(".library").sortable({
			items:"li"
		});
		
		$("#items_list").sortable({
			items:"ul",
			handle:"label"
		});
		
		$(".library").find("li").disableSelection();
		
		$(".library").find("li").bind("contextmenu", function(event) {
		  event.preventDefault();
		  
		  var employee_id = $(this).attr('data-id');
		  console.log("binding -> "+employee_id);
		  
		  $(".custom-menu")
		  	.attr('data-id',employee_id)
		  	.removeClass('hidden')
		    .css({top: event.pageY-0 + "px", left: event.pageX-0 + "px"});
		
		});   
		
	}else {
		alert("Please select few library items from below before proceeding");
	}

	$("#folderModal").modal("hide");
	$("#suitable_title").val("");
	$("#folders_list").val("0");
	de_select_all();
	
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
			top.location="/admin/plans/edit/"+searchIDs[0];
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
	$.post("/admin/plans/main/?output=ajax&search="+search,{},function(ret){
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

function select_library(library_id) {
	current_library_id=library_id;
	$.get("/admin/library/table_view/"+library_id,{"output":"plan_ajax"},function(ret){
		/*var test = ret;
		var content = test.substring(0, test.indexOf('<div id="pagination">'));
		var pagination = test.substring(test.indexOf('<div id="pagination">'), test.length);
		$('#list').find('tbody').html(content);
		$('#pagination').html(pagination);*/
		
		$("#library_data").html(ret);
		cleanup_library_table();
	});
}

function cleanup_library_table() {
	for(x in ids_to_hide) {
		$("#item_no_"+x).hide().removeClass("chk");
	}
}

function delete_root_item($item)
{
	$item.fadeOut(200, function() {
		var $that = $(this);
		$lis = $that.find("li");
		$.each($lis,function(ev){
			delete_item($(this));
		});
		
		var group_id = $that.attr('id').replace('ul-', '');
		delete folders[group_id];
		
		$that.remove();
	});
}

function delete_item($item)
{
	$item.fadeOut(200, function() {
		var item_id = $(this).attr('id').replace('li_','');
		$("#item_no_"+item_id).fadeIn("fast").find('input:checkbox').addClass('chk');
		delete ids_to_hide[item_id];
		$(this).remove();
	});
	
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
	$(".library li").each(function(index,itm){
		
		var item_id = itm.id.replace('li_','');
		var item_parent_id = $(itm).parent().attr("id").replace('ul_','');
		if(item_id>0)
			items_to_save.push({'id':item_id,'parentid':item_parent_id});
		console.log("reading-> "+itm.id+ " -parent:  "+$(itm).parent().attr("id"));
	});	

	var temp = JSON.stringify(items_to_save); //JSON.stringify(unflatten( items_to_save ));

	$.post("/admin/plans/save_plan_items/",{"plan_id":<?=$plan_id?>,
										"library_items":temp},function(ret){
		
		//console.log(ret);
		top.location="/admin/plans/main/";
	});
	
	
}


$(document).ready(function(){
	var dp = $('.datepicker').datepicker().on('changeDate', function(ev) {
	  dp.datepicker('hide');
	});
	$('.timepicker').timepicker();
	$(".chosen-select").chosen();
	
	$(".custom-menu").on({
	    mouseenter: function () {
	    },
	    mouseleave: function () {
			$(this).addClass('hidden');
			//$(this).css({"background-color":"blue"});
	    },
	    click: function(){
	    	//alert($(this).attr("data"));
	    	$(this).addClass('hidden');
	    	//$('#li_emp_'+$(this).attr("data")).remove();
	    	delete_item($('#li_'+$(this).attr("data-id")));
	    }
	});
	
	$(".custom-menu-root").on({
		mouseenter: function () {
		},
		mouseleave: function () {
			$(this).addClass('hidden');
		}
	});
	
	$(".custom-menu-root > div").on({
	   
	    click: function(){
	    	$(this).parent().addClass('hidden');
	    	//$('#li_emp_'+$(this).attr("data")).remove();
	    	if($(this).hasClass("edit"))
	    	{
	    		var data_id = $(this).parent().attr("data-id");
	    		var group_id = data_id.replace('ul-','');
	    		var title = folders[group_id];
	    		current_group_id=group_id;
	    		$("#group_title").val(title);
	    		$("#editGroupModal").modal("show");
	    	}
	    	else {
	    		delete_root_item($('#'+$(this).parent().attr("data-id")));
	    	}
	    }
	});
/*	$(".library").sortable({
	//		items: "li:not(.disabled)",
	  //  	connectWith: ".droppable",
	    	placeholder: "ui-state-highlight2"
	});
	*/
	
});


</script>
<style>
ul.library{
	margin: 0px;
	padding: 0px;	
}

ul.library > label{
	cursor: move;
}

ul.library > li {
	list-style: none;
	margin-left: 10px;
	cursor: move;
}

.custom-menu,.custom-menu-root {
    z-index:1000;
    position: absolute;
    background-color:#fff;
    
    border: 1px solid #ccc;
    padding: 5px 10px 5px 10px;
    cursor: pointer;
}
</style>
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
		  <a href="#" class="bread-current">Plans </a>
		  <span class="divider">/</span> 
		  <a href="/admin/plans/add/"> &raquo; Add New</a>
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
			                  <div class="pull-left">Manage Plan Items</div>
			                  <div class="pull-left" style="margin-left: 20px;">
			                  
			                  </div>
			                  <div class="widget-icons pull-right">
			                  
			                  </div>   
			                  <div class="clearfix"></div>
			                </div>
			
			                <div class="widget-content">
			                	
			                	<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
			                		<tr>
			                			<td width="30%" valign="top">
			                				<div class="padd2">
			                					<h4>Selected Items <a class="btn btn-xs btn-primary pull-right" href="javascript:;" onclick="save_items()">Save</a></h4>
			                					<hr class="clearfix" />
			                					<div id="items_list" class="padd2">
			                					</div>
			                				</div>
			                			</td>
			                			<td valign="top" style="border-left: 1px solid #ccc;" width="70%">
			                				<div class="padd2"  style="border-bottom: 1px solid #ccc;">
			                					<?=$this->library_lib->get_library_selectbox("library_id","select_library(this.value)");?>
			                				</div>
			                				<div id="library_data" style="overflow-x: auto;">
			                					
			                				</div>
			                				
			                			</td>
			                		</tr>
			                	</table>
			                	
			                		
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

<div class='custom-menu hidden' data="">
	<span>Remove Item</span>
</div>

<div class='custom-menu-root hidden' data="">
	<div class="edit">Edit</div>
	<div class="remove">Remove Group</div>
</div>


<!-- Modal -->
<div class="modal fade" id="folderModal" tabindex="-1" role="dialog" aria-labelledby="folderModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Move To</h4>
      </div>
      <div class="modal-body">
        <label for="">New Group Title:</label>
        <input type="text" class="form-control" id="suitable_title" name="suitable_title" value="" />
        <br />Current Groups:<br />
        <select id="folders_list" class="form-control">
        	<option value="0">Select a folder</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="move_to()">Move</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editGroupModal" tabindex="-1" role="dialog" aria-labelledby="folderModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Edit Group</h4>
      </div>
      <div class="modal-body">
        <label for="">Group Title:</label>
        <input type="text" class="form-control" id="group_title" name="group_title" value="" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="update_group_title()">Update</button>
      </div>
    </div>
  </div>
</div>


</body>
</html>