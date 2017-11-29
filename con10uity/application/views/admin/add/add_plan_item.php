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
var current_open_tab='';
var current_type='<?=$plan_item_details['plan_type']?>';
var library=<?=$this->library_lib->get_library_json();?>;
var current_library_id=<?=$plan_item_details['library_id']?>;
var ids_to_hide = {<?if(is_array($plan_item_details['library_items'])){ $str_to_return=''; foreach ($plan_item_details['library_items'] as $key => $value) { $str_to_return .= '"'.$value.'":"'.$value.'",'; } echo substr($str_to_return, 0,-1); }?>};
var plan_selected_items = <?=json_encode($plan_selected_items);?>;

function select_all_boxes(is_checked)
{
	if(is_checked)
	{
		$("[type='checkbox'].select_all").removeAttr("checked");
		$("[type='checkbox'].select_all").prop('checked', false);
	}

	if(current_open_tab=='assigned' && is_checked)
	{
		if(!confirm("Are you sure you want remove all selected items from the list?"))
			return;
	}
	
	if(current_open_tab=='assigned')
	{
		$("#assigned_data > table > tbody > tr").find("[type='checkbox']").each(function(){
			do_action($(this),false);
		});
	}
	else {
		//current available tab
		$("#library_data > table > tbody tr:not(.selected)").find("[type='checkbox']").each(function(){
			do_action($(this),false);
		});
	}	
	
}

function de_select_all()
{
	$("[type='checkbox'].chk").removeAttr("checked");
	$("[type='checkbox'].chk").prop('checked', false);
}

function load_plan_selected_items()
{
<? if($plan_item_id!='' and $plan_item_details['plan_type']=="lib"){ ?>
	$.get("/admin/library/table_view/<?=$plan_item_details['library_id']?>",{output:"plan_ajax",search:"<?=$plan_item_id?>",type:"plan_item_id"},function(ret){
		
		$(".tab_items").removeClass("hidden");
		$(".mytabs").addClass("hidden");
		$("#assigned_data").html(ret).removeClass("hidden");
	
	});
<? }else if ($plan_item_details['plan_type']=="cc"){ ?>
	var idsTemp = [];
	for (x in ids_to_hide) {
		idsTemp.push(ids_to_hide[x]);
	}
	
	var strTemp = idsTemp.join(",");
	
	$.get("/admin/call_chain/main/",{"output":"pi","type":"pi","search":strTemp},function(ret){
		
		//console.log("loaded call chain");
		//console.log(ret);
		
		$(".tab_items").removeClass("hidden");
		$(".mytabs").addClass("hidden");
		$("#assigned_data").html(ret).removeClass("hidden");
	});

<? }else if ($plan_item_details['plan_type']=="ra"){ ?>
	var idsTemp = [];
	for (x in ids_to_hide) {
		idsTemp.push(ids_to_hide[x]);
	}
	
	var strTemp = idsTemp.join(",");
	
	$.get("/admin/risk_assessment/main/",{"output":"pi","type":"pi","search":strTemp},function(ret){
		
		//console.log("loaded risk assessment");
		//console.log(ret);
		
		$(".tab_items").removeClass("hidden");
		$(".mytabs").addClass("hidden");
		$("#assigned_data").html(ret).removeClass("hidden");
	});

<? } ?>	
	show_assigned();
	
}

function show_assigned()
{
	current_open_tab='assigned';
	$(".mytabs").addClass("hidden");
	$("#assigned_data").removeClass("hidden");
	
	if(current_type=='lib' || current_type=='gallery')
		$("#search_li").addClass("hidden");
}

function show_available()
{
	current_open_tab='available';
	$(".mytabs").addClass("hidden");
	$("#library_data").removeClass("hidden");
	//$("#library_data").find("#list > tbody tr").show();
	//$("#library_data").find("#list > tbody tr.selected").hide();
	if(current_type=='lib' || current_type=='gallery')
		$("#search_li").removeClass("hidden");
}

function set_items_per_page(ipp)
{
	pagination(1);
}

function select_module(value)
{
	var result = value.split(":");
	var lib_type = result[1];
	if(lib_type=="lib" || lib_type=="gallery")
	{
		select_library(result[0],true,lib_type);
	}
	else if (result[1]=="cc")
	{
		load_callchain();
	}
	else if (result[1]=="ra")
	{
		//alert("Loading risk assessment");
		load_ra();
	}
}

function load_callchain()
{
	//console.log("loading call chain");
	current_type='cc';
	$.get("/admin/call_chain/main/",{"output":"pi"},function(ret){
		
		$(".tab_items").removeClass("hidden");
		$(".mytabs").addClass("hidden");
		$("#library_data").html(ret).removeClass("hidden");
		$("#assigned_data > table > thead").html($("#library_data > table > thead").html());
		
		$("#plan_template_div").addClass("hidden");
		
		disable_assigned_data();	
		load_plan_selected_items();
	});
}

function load_ra()
{
	//console.log("loading call chain");
	current_type='ra';
	$.get("/admin/risk_assessment/main/",{"output":"pi"},function(ret){
		
		$(".tab_items").removeClass("hidden");
		$(".mytabs").addClass("hidden");
		$("#library_data").html(ret).removeClass("hidden");
		$("#assigned_data > table > thead").html($("#library_data > table > thead").html());
		
		$("#plan_template_div").addClass("hidden");
		
		disable_assigned_data();	
		load_plan_selected_items();
	});
}

function select_library(library_id,auto_clean,lib_type) {
	auto_clean = typeof auto_clean=='undefined'?true:auto_clean;
	lib_type = typeof lib_type=='undefined'?'lib':lib_type;
	
	current_type=lib_type;
	current_library_id=library_id;
	
	$.get("/admin/library/table_view/"+library_id,{"output":"plan_ajax"},function(ret){
		
		$(".tab_items").removeClass("hidden");
		$(".mytabs").addClass("hidden");
		$("#library_data").html(ret).removeClass("hidden");
		$("#assigned_data > table > thead").html($("#library_data > table > thead").html());
	
		$("#plan_template_div").removeClass("hidden");
		$.post("/admin/library_template/get_templates_by_library_id/",{"library_id":library_id},function(ret){
			$("#template_id").html("<option value='0'>Default</option>");
			
			if(ret!=false)
			{
				for(x in ret)
				{
					//console.log(ret[x].name);
					$("#template_id").append("<option value='"+ret[x].id+"'>"+ret[x].name+"</option>");
				}
				
				
				//$("#template_id").chosen({width: "95%"});
				//console.log(ret);
			}
			$("#template_id").trigger("chosen:updated");
		},'json');
	
		disable_assigned_data();	
		load_plan_selected_items();
	});
}

function do_action($obj,do_confirm)
{
	$obj.removeAttr("checked");
	$obj.prop('checked', false);
	do_confirm = typeof do_confirm == 'undefined'?true:do_confirm;

	if($obj.parent().parent().hasClass("selected"))
	{
		if(do_confirm)
		{
			if(!confirm("Are you sure you want to remove this item from the list?"))
			{
				return;
			}
		}
		
		delete ids_to_hide[$obj.val()];
		
		//console.log("checking for item_no_"+$obj.val());
		//console.log("size() = "+$("#library_data > table > tbody > tr#item_no_"+$obj.val()).size());
		
		if( $("#library_data > table > tbody > tr#item_no_"+$obj.val()).size()==1 )
		{
			$obj.parent().parent().remove();
			$("#library_data > table > tbody > tr#item_no_"+$obj.val()).find("[type='checkbox']").removeAttr("disabled").addClass("chk");
		}
		else
		{
			$obj.parent().parent().removeClass("selected").appendTo($("#library_data > table > tbody"));
		}
	}
	else {
		console.log("Adding to assigned_data table");
	
		ids_to_hide[$obj.val()]=$obj.val();
		$obj.parent().parent().addClass("selected").appendTo($("#assigned_data > table > tbody"));
	}
	
	//$obj.parent().parent().fadeOut("fast");
}


function pagination(page) {
	page = typeof page != 'undefined'?page:0;
	current_page=page;
	$.get("/admin/library/table_view/"+current_library_id,{"page":page,"items_per_page":$("#items_per_page").val(),"output":"plan_ajax"},function(ret){
		$(".mytabs").addClass("hidden");
		$("#library_data").html(ret).removeClass("hidden");
	
		disable_assigned_data();
	});
}

function do_search(search) {

	console.log("searching: "+search);
	$.get("/admin/library/table_view/"+current_library_id,{"search":search,"page":0,"items_per_page":$("#items_per_page").val(),"output":"plan_ajax"},
		function(ret){
			$(".mytabs").addClass("hidden");
			$("#library_data").html(ret).removeClass("hidden");
			disable_assigned_data();
	});
}


function disable_assigned_data()
{
	for (x in ids_to_hide) {
		//$("#library_data").find("#item_no_"+ids_to_hide[x]).find("[type='checkbox']").attr("disabled","disabled").removeClass("chk");
		$("#library_data").find("#item_no_"+ids_to_hide[x]).remove();
	}	
}

function save_items()
{
	var items_to_save=[];
	$("#assigned_data > table > tbody > tr").each(function(index,itm){
		var item_id = itm.id.replace('item_no_','');
		if(item_id>0)
			items_to_save.push(item_id);
	});	

	if(items_to_save.length>0 && $("#title").val().length > 0)
	{
		var temp = JSON.stringify(items_to_save);
	
		$.post("/admin/plans/save_plan_items/",{
												"plan_item_id":"<?=$plan_item_details['id']?>",
												"plan_id":"<?=$plan_id?>",
												"plan_type":current_type,
												"library_id":current_library_id,
												"template_id":$("#template_id").val(),
												"title":$("#title").val(),
												"description":$("#description").val(),
												"footer":$("#footer").val(),
												"library_items":temp},function(ret){
			
			//console.log(ret);
			top.location="/admin/plans/edit/<?=$plan_id?>/";
		});
	
	}
	else {
		if($("#title").val().length==0)
		alert("Please enter title");
			else
		alert("Please select a few items before you can save");
	}
	
	
}

$(document).ready(function(){
	var dp = $('.datepicker').datepicker().on('changeDate', function(ev) {
	  dp.datepicker('hide');
	});
	$('.timepicker').timepicker();
	$(".chosen-select").chosen();
	/*
		<?=$plan_item_details['library_id']?>
	*/	
	<? if($plan_item_details['plan_type']=='lib' and is_numeric($plan_item_details['library_id']) and $plan_item_details['library_id']>0){ ?>
		$("#library_id").val(<?=$plan_item_details['library_id']?>);
		//select_library(<?=$plan_item_details['library_id']?>,false);
		select_module("<?=$plan_item_details['library_id']?>:<?=$plan_item_details['plan_type']?>");
		$("#library_id").trigger("chosen:updated");
	<? }elseif ($plan_item_details['plan_type']=='cc'){ ?>
		select_module("0:cc");
	<? }elseif ($plan_item_details['plan_type']=='ra'){ ?>
		select_module("0:ra");
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
			                			<td valign="top" style="border-right: 1px solid #ccc;" width="100%">
			                				<div class="padd2"  style="border-bottom: 1px solid #ccc; height: 41px;">
			                					<div class="pull-left" style="width: 80px;">Module:</div>
			                					<div class="pull-left" style="margin-left: 10px; width: 300px;">
			                					<? //$this->library_lib->get_library_selectbox("library_id","select_library(this.value)");?>
			                					<select id="module_id" name="module_id" class="form-control chosen-select" onchange="select_module(this.value)">
			                						<?
			                							foreach ($modules_list as $key => $value) {
			                								echo '<option value="'.$value['module_id'].':'.$value['type'].'">'.$value['name'].'</option>';
			                							}
			                						?>
			                					</select>
			                					</div>
			                				</div>
			                				<div id="plan_template_div" class="padd2"  style="border-bottom: 1px solid #ccc; height: 41px;">
			                					<div class="pull-left" style="width: 80px;">Template:</div>
			                					<div class="pull-left" style="margin-left: 10px; width: 300px;">
				                					<select data-placeholder="Select Template" class="form-control chosen-select" name="template_id" id="template_id">
				                						<option value="0">Default</option>
				                					</select>
			                					</div>
			                				</div>
			                				
			                				<div class="tab_items hidden">
			                					<br />
				                				<ul class="nav nav-tabs">
				                				  <li>&nbsp;</li>
				                				  <li class="active"><a href="#home" onclick="show_assigned();" data-toggle="tab">Assigned</a></li>
				                				  <li><a href="#profile" onclick="show_available()" data-toggle="tab">Available</a></li>
				                				</ul>
			                				</div>
			                				
			                				<div id="search_li" class="padd2 hidden">
			                					<input onkeyup="do_search(this.value);" type="text" class="form-control input-md" placeholder="Search" name="" value="" />
			                				</div>
			                				
			                				<div id="library_data" class="mytabs" style="overflow-x: auto;">
			                					
			                				</div>
			                				<div id="assigned_data" class="mytabs hidden">
			                					<table class="table">
			                						<thead>
			                						</thead>
			                						<tbody>
			                						</tbody>
			                					</table>
			                					<div class="widget-foot" style="padding-left: 7px;">
			                						<a class="btn btn-success" onclick="save_items()">Save Plan Item</a>
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