<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Library</title>

<!--HEAD-->
<? include(APPPATH."views/admin/head.inc.php"); ?>

<style>
/*.notable{
	border-bottom: none;
}

.notable td {
	border-bottom: none !important;
}

*/
.table_1 td{
	border-bottom: 1px solid #ccc;
	padding: 5px;
	padding-left: 10px;
}

.table_1 td:first-child{
	border-right: 1px solid #ccc;
}

.table_1 td table td:first-child{
	border-right: none;
}

.table_1 td table td {
	border-bottom: none;
	padding: 2px;

}

</style>
<script>

var report_id = "<?=$report_id?>";
var module_type = "<?=$report['module_type']?>";
var ids_to_hide = [];
var global_tr=1;
var current_open_tab='';
var current_library_id="<?=$report['library_id']?>";

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

$(document).ready(function(){
	load_module("<?=$report['module_id']?>");
});

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

function disable_assigned_data()
{
	for (x in ids_to_hide) {
		//$("#library_data").find("#item_no_"+ids_to_hide[x]).find("[type='checkbox']").attr("disabled","disabled").removeClass("chk");
		$("#library_data").find("#item_no_"+ids_to_hide[x]).remove();
	}	
}

function load_module(value)
{
	var result = value.split(":");
	var lib_type = result[1];
	var lib_id = result[0];
	
	$(".table_1").addClass("hidden");
	
	if(lib_type=="lib" || lib_type=="gallery")
	{
		$(".table_1").removeClass("hidden");
		select_library(lib_id,true,lib_type);
	}
	else if (lib_type=="cc")
	{
		load_callchain();
	}
	else if (lib_type=="ra")
	{
		load_ra();
	}
	else if (lib_type=="bia")
	{
		load_bia();
	}
}

function do_filter_by_where() {

	var whereArray = [];
	$("#where_table tr").each(function(){
		var v1 = $(this).find(".where_column").val();
		var v2 = $(this).find(".where_op").val();
		var v3 = $(this).find(".where_filter").val();
		
		whereArray.push({"c":v1,"o":v2,"f":v3});
	});
	
	var filter_by = JSON.stringify(whereArray);

	console.log("filter_by: "+filter_by);
	$.get("/admin/library/table_view/"+current_library_id,{"filter_by":filter_by,"page":0,"items_per_page":$("#items_per_page").val(),"output":"plan_ajax","page_type":"report"},
		function(ret){
			$(".mytabs").addClass("hidden");
			$("#library_data").html(ret).removeClass("hidden");
			disable_assigned_data();
	});
}

function do_search(search) {

	var whereArray = [];
	$("#where_table tr").each(function(){
		var v1 = $(this).find(".where_column").val();
		var v2 = $(this).find(".where_op").val();
		var v3 = $(this).find(".where_filter").val();
		
		whereArray.push({"c":v1,"o":v2,"f":v3});
	});
	
	var filter_by = JSON.stringify(whereArray);

	console.log("searching: "+search);
	$.get("/admin/library/table_view/"+current_library_id,{"filter_by":filter_by,"search":search,"page":0,"items_per_page":$("#items_per_page").val(),"output":"plan_ajax","page_type":"report"},
		function(ret){
			$(".mytabs").addClass("hidden");
			$("#library_data").html(ret).removeClass("hidden");
			disable_assigned_data();
	});
}

function load_callchain()
{
	//console.log("loading call chain");
	current_type='cc';
	$.get("/admin/call_chain/main/",{"output":"pi","page_type":"report"},function(ret){
		
		load_available_data(ret);
		/*$(".tab_items").removeClass("hidden");
		$(".mytabs").addClass("hidden");
		$("#library_data").html(ret).removeClass("hidden");
		$("#assigned_data > table > thead").html($("#library_data > table > thead").html());
		
		$("#plan_template_div").addClass("hidden");
		
		disable_assigned_data();	
		//load_plan_selected_items();*/
	});
}

function load_ra()
{
	//console.log("loading call chain");
	current_type='ra';
	$.get("/admin/risk_assessment/main/",{"output":"pi","page_type":"report"},function(ret){
		
		load_available_data(ret);
		/*$(".tab_items").removeClass("hidden");
		$(".mytabs").addClass("hidden");
		$("#library_data").html(ret).removeClass("hidden");
		$("#assigned_data > table > thead").html($("#library_data > table > thead").html());
		
		$("#plan_template_div").addClass("hidden");
		
		disable_assigned_data();	
		//load_plan_selected_items();*/
	});
}

function load_bia()
{
	current_type='bia';
	$.post("/admin/library/get_id_by_name",{"name":"BIA"},function(library_id){
		select_library(library_id);
	});
}

function load_available_data(data)
{
	$(".tab_items").removeClass("hidden");
	$(".mytabs").addClass("hidden");
	$("#library_data").html(data).removeClass("hidden");
	$("#assigned_data > table > thead").html($("#library_data > table > thead").html());
	
	$("#plan_template_div").addClass("hidden");
	
	disable_assigned_data();
}

function select_library(library_id,auto_clean,lib_type) {
	auto_clean = typeof auto_clean=='undefined'?true:auto_clean;
	lib_type = typeof lib_type=='undefined'?'lib':lib_type;
	
	current_type=lib_type;
	current_library_id=library_id;
	
	$.get("/admin/library/table_view/"+library_id,{"output":"plan_ajax","page_type":"report"},function(ret){
		
		$(".tab_items").removeClass("hidden");
		$(".mytabs").addClass("hidden");
		$("#library_data").html(ret).removeClass("hidden");
		$("#assigned_data > table > thead").html($("#library_data > table > thead").html());
	
		$("#plan_template_div").removeClass("hidden");
		disable_assigned_data();	
		//load_plan_selected_items();
	});
}

function changeFilter(filter_type){
	if(filter_type=="Manual"){
		$("#filter_type_div").removeClass("hidden");
		$("#btnPrintReport").addClass("hidden");
		$("#btnFilter").removeClass("hidden");
	}else {
		$("#filter_type_div").addClass("hidden");
		$("#btnPrintReport").removeClass("hidden");
		$("#btnFilter").addClass("hidden");
	}
}

function save_items()
{
	printReportModal();
}

function printReportModal()
{
	$("#modalPrintReport").modal('show');
}

function printReport()
{
	var items_to_save=[];
	
	var includeCoverPage=$('input[name="includeCoverPage"]:checked').val();
	var saveAsTemplate=$('input[name="saveAsTemplate"]:checked').val();
	var filterType = $('#filter_type').val();
	var filterBy = "";
	var selected_records = "";
	
	var whereArray = [];
	$("#where_table tr").each(function(){
		var v1 = $(this).find(".where_column").val();
		var v2 = $(this).find(".where_op").val();
		var v3 = $(this).find(".where_filter").val();
		
		whereArray.push({"c":v1,"o":v2,"f":v3});
	});
	
	if(filterType=="Automatic")
	{
		filterBy = JSON.stringify(whereArray);
		
		
	}
	else {
		
		$("#assigned_data > table > tbody > tr").each(function(index,itm){
			var item_id = itm.id.replace('item_no_','');
			if(item_id>0)
				items_to_save.push(item_id);
		});	
	
		if(items_to_save.length>0)
		{
			selected_records = JSON.stringify(items_to_save);
		}
		else {
			alert("Please select a few items before proceeding");
			return;
		}
	}
	
	$.post("/admin/reports/save_selected_records/",{
											"report_id":report_id,
											"includeCoverPage":includeCoverPage,
											"saveAsTemplate":saveAsTemplate,
											"filterType":filterType,
											"filterBy":filterBy,
											"selected_records":selected_records},function(ret){
		
		//console.log(ret);
		top.location="/admin/reports/export/"+report_id+"?output=pdf&includeCoverPage="+includeCoverPage+"&saveAsTemplate="+saveAsTemplate;
	});
	
	//console.log(temp);
	
}

$(document).ready(function(){
	$('#tooltiphover').tooltip();
	
	<? if(is_array($report['filter_by'])){ ?>
		$('#filter_type').val('Automatic');
		changeFilter('Automatic');
	<? } ?>
});
//select_module("18:lib");

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
			                  <div class="pull-left">
			                  <? if($this->input->get('edit')=="1"){ ?>
			                  Print Report
			                  <? }else{ ?>
			                  New Report &raquo; Step 2 (Select Items)
			                  <? } ?>
			                  </div>
			                  <div class="widget-icons pull-right">
			                    <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
			                    <a href="#" class="wclose"><i class="icon-remove"></i></a>
			                  </div>  
			                  <div class="clearfix"></div>
			                </div>
			
			                <div class="widget-content">
			                	
			                	<table class="table_1 hidden" style="width:100%;" cellpadding="0" cellspacing="0">
				                	<tr>
				                		<td>Filter Type <a href="#" id="tooltiphover" class="tooltiphover" title="Some tooltip text!"><span class="tooltiphover icon icon-info-sign"></span></a></td>
				                		<td>
				                			<select class="form-control" name="filter_type" id="filter_type" onchange="changeFilter(this.value)">
				                				<option value="Manual">Manual</option>
				                				<option value="Automatic">Automatic</option>
				                			</select>
				                		</td>
				                	</tr>
				                	<tr id="where_tr">
				                		<td style="border-right: 1px solid #ccc;">Where:</td>
				                		<td>
				                			<? if(is_array($report['filter_by'])){ 
				                					foreach($report['filter_by'] as $options){ ?>
							                			<table id="where_table" class="notable" border="0" cellpadding="0" cellspacing="0">
							                				<tr id="where_template">
							                					<td>
							                						<select class="form-control where_column" id="where_column_1">
							                						<?
							                							foreach ($columns as $key => $col) {
							                							
							                								$selected = '';
							                								if($options['c']==$key)
							                									$selected=' selected="selected"';
							                							
							                								echo '<option value="'.$key.'"'.$selected.'>'.$col['var_name'].'</option>';
							                							}
							                						?>
							                						</select>
							                					</td>
							                					<td>
							                						<select class="form-control where_op" id='where_op_1'>
							                							<option value="=">=</option>
							                						</select>
							                					</td>
							                					<td><input type="text" class="form-control where_filter" id="where_filter_1" value="<?=$options['f']?>" /></td>
							                					<td> 
							                						<div style="padding-top: 10px;">
							                							<a href="javascript:copy_where();"><i style="color: green;" class="icon icon-plus"></i></a>
							                						</div>
							                					</td>
							                			</table>
				                			<? }
				                			}else{ ?>
				                			<table id="where_table" class="notable" border="0" cellpadding="0" cellspacing="0">
				                				<tr id="where_template">
				                					<td>
				                						<select class="form-control where_column" id="where_column_1">
				                						<?
				                							foreach ($columns as $key => $col) {
				                								echo '<option value="'.$key.'">'.$col['var_name'].'</option>';
				                							}
				                						?>
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
				                			<? } ?>
				                		</td>
				                	</tr>
				                	<tr>
				                		<td></td>
				                		<td>
				                			<button id="btnFilter" class="btn btn-info" onclick="do_filter_by_where()">Filter</button>
				                			<button id="btnPrintReport" class="btn btn-info hidden" onclick="printReportModal()">Print Report</button>
				                		</td>
				                	</tr>
			                	</table>
			                	
			                	
			                	
			                	<div id="filter_type_div">
				                	<div class="tab_items hidden">
				                		<br />
				                		<ul class="nav nav-tabs">
				                		  <li>&nbsp;</li>
				                		  <li><a href="#home" onclick="show_assigned();" data-toggle="tab">Assigned</a></li>
				                		  <li class="active"><a href="#profile" onclick="show_available()" data-toggle="tab">Available</a></li>
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
				                			<a class="btn btn-success" onclick="printReportModal()">Print Report</a>
				                		</div>
				                	</div>
			                	</div>
			                				                		
			                </div>
			                <div class="widget-foot">
			                	 
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


<div id="modalPrintReport" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Print Options</h4>
      </div>
      <div class="modal-body">
        <p>
        	<div class="row">
        		<div class="col-md-5">Include Cover Page:</div>
        		<div class="col-md-6">
      	        	  <label>
		        	    <input type="radio" name="includeCoverPage" id="optionsRadios1" value="1" checked>
		        	    Yes
		        	  </label>
		        	  <label>
		        	    <input type="radio" name="includeCoverPage" id="optionsRadios2" value="0">
		        	    No
		        	  </label>
		        </div>
        	</div>
			<div class="row">
				<div class="col-md-5">Save Report As Template:</div>
				<div class="col-md-6">
			      	  <label>
			    	    <input type="radio" name="saveAsTemplate" id="optionsRadios3" value="1" checked>
			    	    Yes
			    	  </label>
			    	  <label>
			    	    <input type="radio" name="saveAsTemplate" id="optionsRadios4" value="0">
			    	    No
			    	  </label>
			    </div>
			</div>
        </p>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
        <button type="button" onclick="printReport()" class="btn btn-primary">Print Report</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


</body>
</html>