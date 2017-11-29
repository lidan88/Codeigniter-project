function form_validation(thisform) {if (thisform.var_name.value == '') {
									alert ('Var name is required');
									thisform.var_name.focus();
									return false;
									}var re5digit=/^\d*$/ //regular expression defining a 5 digit number
							if (thisform.item_order.value.search(re5digit)==-1) //if match failed
							{
							alert('Please enter a valid digit number inside forms');
							thisform.item_order.focus();
							return false;
							
							}
							return true; }

function type_changed(t)
{

	//alert(t);
	
	if(t=='T' || t=='TA')
	{
		$(".extra_values,.extra_values2,.helper,.dropdown,.library_dropdown").addClass("hidden");
	}
	else if(t=='GRID')
	{
		$(".extra_values").removeClass("hidden");
		$(".helper_grid").removeClass("hidden");
		$(".extra_values2,.dropdown,.library_dropdown,.helper").addClass("hidden");
		//$("#rvar_value_type_l").attr("checked","checked");
	}
	else if(t=='R')
	{
		$(".extra_values").removeClass("hidden");
		$(".helper").removeClass("hidden");
		$(".extra_values2,.dropdown,.library_dropdown,.helper_grid").addClass("hidden");
		$("#rvar_value_type_l").attr("checked","checked");
	}
	else if(t=='C')
	{
		$(".helper").removeClass("hidden");
		$(".extra_values").removeClass("hidden");
		$(".extra_values2,.dropdown,.library_dropdown,.helper_grid").addClass("hidden");
		$("#rvar_value_type_c").attr("checked","checked");
	}else if(t=='S')
	{
		$(".extra_values,.extra_values2").removeClass("hidden");
		$(".helper").removeClass("hidden");
		$(".dropdown,.library_dropdown,.helper_grid").addClass("hidden");
	}
	else if(t=='D' || t=='MSEL')
	{
		$(".extra_values,.extra_values2,.helper,.library_dropdown,.helper_grid").addClass("hidden");
		$(".dropdown").removeClass("hidden");
	}
	else if(t=='LIBRARY' || t=='LIBRARY_MSEL' || t=='MAP')
	{
		$(".extra_values,.extra_values2,.helper,.dropdown,.helper_grid").addClass("hidden");
		$(".library_dropdown").removeClass("hidden");
		
		if(t=='MAP')
		{
			$("#library_item_id_2").addClass("hidden");
			$("#library_dropdown_id").val(current_library_id);
			library_selected(current_library_id,'LIBRARY_MSEL');
		}
	}
	else if (t=='F' || t=='DATE' || t=='TIME' || t=='USERS') {
		$(".extra_values,.extra_values2,.helper,.dropdown,.library_dropdown,.helper_grid").addClass("hidden");
		
	}
}

function library_selected(library_id,type,current_selected_type)
{
	type = typeof type == 'undefined'?'':type;
	current_selected_type = typeof current_selected_type == 'undefined'?'':current_selected_type;
		
	$.post("/"+current_folder+"/library_items/library_items_select_box_ajax",{"library_id":library_id,"type":type},function(ret){
		
		//console.log(ret);
		$(".library_item_id").html(ret);
		$("#library_items_select_box").removeClass("hidden");
		
		if(current_selected_type=='MAP')
		{
			$(".library_item_id:first").val(var_value);
		}
		else {
			$(".library_item_id:first").val(item_selected_0);
			$(".library_item_id:last").val(item_selected_1);
		}
	});
}

$(document).ready(function(){
	var dp = $('.datepicker').datepicker().on('changeDate', function(ev) {
	  dp.datepicker('hide');
	});
	$('.timepicker').timepicker();
	$(".chosen-select").chosen();
});
