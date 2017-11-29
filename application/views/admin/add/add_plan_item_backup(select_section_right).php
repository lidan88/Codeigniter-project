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

var current_page=0;
var library=<?=$this->library_lib->get_library_json();?>;
var current_library_id=0;
var ids_to_hide = {<?if(is_array($plan_item_details['library_items'])){ $str_to_return=''; foreach ($plan_item_details['library_items'] as $key => $value) { $str_to_return .= '"'.$value.'":"'.$value.'",'; } echo substr($str_to_return, 0,-1); }?>};
var plan_selected_items = <?=json_encode($plan_selected_items);?>;

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

function load_plan_selected_items()
{
	var $ul = get_ul();
	for (x in plan_selected_items) {
		for (index in plan_selected_items[x]['value']) {
			
			console.log(plan_selected_items[x].id);
			// only pick the first entry and discard the rest
			add_li($ul,plan_selected_items[x].id,plan_selected_items[x]['value'][index]);
			break;
		}
	}
	
	apply_sortables();
}

function get_ul()
{
	var ul_id="ul";
	if($('#'+ul_id).size())
	{
		var $ul = $('#'+ul_id);
	}
	else {
		var $ul = $('<ul id="'+ul_id+'" class="library open"><label onclick="toggle_folder($(this).parent());"><span class="title">Selected Items</span></label></ul>');
		$("#items_list").append($ul);
	}
	
	$ul.find("label").bind("contextmenu", function(event) {
		  event.preventDefault();
		  
		  var lib_id = $(this).parent().attr('id');
		  
		  $(".custom-menu-root")
		  	.attr('data-id',lib_id)
		  	.removeClass('hidden')
		    .css({top: event.pageY-0 + "px", left: event.pageX-0 + "px"});
		
		});
	
	return $ul;
}

function add_li($ul,id,name)
{
	ids_to_hide[id]=id;
	//console.log("#item_no_"+id);
	$("#item_no_"+id).find("[type='checkbox'].chk").removeClass("chk").attr("disabled","disabled");
	var $li_item = $('<li id="li_'+id+'" data-id="'+id+'"><span class="icon icon-ok"></span>&nbsp;'+name+'</li>');
	$ul.append($li_item);
}

function apply_sortables()
{
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
	  
	  $(".custom-menu")
	  	.attr('data-id',employee_id)
	  	.removeClass('hidden')
	    .css({top: event.pageY-0 + "px", left: event.pageX-0 + "px"});
	
	});
}

function select_selected_items()
{
	$("#btn_save_div").removeClass("hidden");
	
	var $ul = get_ul();
	
	var searchIDs = $("input:checkbox:checked.chk").map(function(){
	  return $(this).val();
	}).get();
	
	if(searchIDs.length>0)
	{
		for (x in searchIDs) {
			var data = $("#item_no_"+searchIDs[x]).find("td:nth-child(2)").attr("data");
			add_li($ul,searchIDs[x],data);
		}
		
		apply_sortables();
		
	}else {
		alert("Please select few library items from below before proceeding");
	}

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
	pagination(1);
}

function select_library(library_id,auto_clean) {
	current_library_id=library_id;
	auto_clean = typeof auto_clean=='undefined'?true:auto_clean;
	$.get("/admin/library/table_view/"+library_id,{"output":"plan_ajax"},function(ret){
		/*var test = ret;
		var content = test.substring(0, test.indexOf('<div id="pagination">'));
		var pagination = test.substring(test.indexOf('<div id="pagination">'), test.length);
		$('#list').find('tbody').html(content);
		$('#pagination').html(pagination);*/
		
		$("#library_data").html(ret);
		if(auto_clean)
			cleanup_library_table();
	
		load_plan_selected_items();
	});
}

function pagination(page) {
	page = typeof page != 'undefined'?page:0;
	current_page=page;
	$.get("/admin/library/table_view/"+current_library_id,{"page":page,"items_per_page":$("#items_per_page").val(),"output":"plan_ajax"},function(ret){
		$("#library_data").html(ret);
	
		for (x in ids_to_hide) {
			//$("#item_no_"+ids_to_hide[x]).hide().find("[type='checkbox'].chk").removeClass("chk");
			$("#item_no_"+ids_to_hide[x]).find("[type='checkbox']").attr("disabled","disabled").removeClass("chk");
			//.find("[type='checkbox'].chk").removeClass("chk");
		}
	});
}

function cleanup_library_table() {
	ids_to_hide = {};
	plan_selected_items = {};
	$("#items_list").find("ul").remove();
	$("#btn_save_div").addClass("hidden");
	/*for(x in ids_to_hide) {
		$("#item_no_"+x).hide().removeClass("chk");
	}*/
}

function delete_root_item($item)
{
	var $that = $item;
	$lis = $that.find("li");
	$.each($lis,function(ev){
		delete_item($(this));
	});
	
	$("#btn_save_div").addClass("hidden");
}

function delete_item($item)
{
	$item.fadeOut(200, function() {
		var item_id = $(this).attr('id').replace('li_','');
		$("#item_no_"+item_id).find('input:checkbox').addClass('chk').removeAttr("disabled");
		delete ids_to_hide[item_id];
		$(this).remove();
	});
	
} 

function save_items()
{
	var items_to_save=[];
	$(".library li").each(function(index,itm){
		
		var item_id = itm.id.replace('li_','');
		//var item_parent_id = $(itm).parent().attr("id").replace('ul_','');
		if(item_id>0)
			items_to_save.push(item_id);
			//items_to_save.push({'id':item_id});
			//items_to_save.push({'id':item_id,'parentid':item_parent_id});
//		console.log("reading-> "+itm.id+ " -parent:  "+$(itm).parent().attr("id"));
	});	

	var temp = JSON.stringify(items_to_save); //JSON.stringify(unflatten( items_to_save ));

	$.post("/admin/plans/save_plan_items/",{
											"plan_item_id":"<?=$plan_item_details['id']?>",
											"plan_id":"<?=$plan_id?>",
											"library_id":current_library_id,
											"title":$("#title").val(),
											"description":$("#description").val(),
											"footer":$("#footer").val(),
											"library_items":temp},function(ret){
		
		//console.log(ret);
		top.location="/admin/plans/edit/<?=$plan_id?>/";
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
	    	delete_root_item($('#'+$(this).parent().attr("data-id")));
	    }
	});
	
	<? if(is_numeric($plan_item_details['library_id']) and $plan_item_details['library_id']>0){ ?>
		select_library(<?=$plan_item_details['library_id']?>,false);
	<? } ?>

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
			                  <div class="pull-left">Add Plan Item</div>
			                  <div class="pull-left" style="margin-left: 20px;">
			                  
			                  </div>
			                  <div class="widget-icons pull-right">
			                  
			                  </div>   
			                  <div class="clearfix"></div>
			                </div>
			
			                <div class="widget-content">
			                	
			                	<div class="padd">
				                	  <div class="form-group">
				                	    <label for="exampleInputEmail1">Title</label>
				                	    <input type="text" class="form-control" id="title" placeholder="Enter Title" value="<?=$plan_item_details['title']?>">
				                	  </div>
				                	  <div class="form-group">
				                	    <label for="exampleInputPassword1">Description</label>
				                	    <textarea class="form-control" name="description" id="description" placeholder="Description (optional)"><?=$plan_item_details['description']?></textarea>
				                	  </div>
				                	  <div class="form-group">
				                	    <label for="exampleInputPassword1">Footer</label>
				                	    <textarea class="form-control" name="footer" id="footer" placeholder="Footer (Optional)"><?=$plan_item_details['footer']?></textarea>
				                	  </div>
				            	</div>
			               </div>
			               
			               <div class="widget-head" style="border-top: 1px solid #ccc; border-radius: 0px;">
			                 <div class="pull-left">Select Plan Items</div>
			                 <div class="clearfix"></div>
			               </div>
			               
			               <div class="widget-content"> 	
			                	<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
			                		<tr>
			                			<td valign="top" style="border-right: 1px solid #ccc;" width="70%">
			                				<div class="padd2"  style="border-bottom: 1px solid #ccc; height: 41px;">
			                					<?=$this->library_lib->get_library_selectbox("library_id","select_library(this.value)");?>
			                				</div>
			                				<div id="library_data" style="overflow-x: auto;">
			                					
			                				</div>
			                				
			                			</td>
			                			<td width="30%" valign="top" style="background-color: #fefefe; position: relative;">
			                				<div id="btn_save_div" class="<?=!is_array($plan_selected_items)?'hidden':''?>" style="position: absolute; top: 5px; right: 5px;">
			                					<a class="btn btn-danger pull-right" href="javascript:;" onclick="save_items()">Save</a>
			                				</div>
			                				<div class="padd2">
			                					<div id="items_list">
			                					</div>
			                						
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
	<div class="remove">Remove Group</div>
</div>

</body>
</html>