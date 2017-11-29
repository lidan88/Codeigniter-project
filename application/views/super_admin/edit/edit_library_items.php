<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Panel</title>
<!--HEAD-->
<? include(APPPATH."views/super_admin/head.inc.php"); ?>
<!-- /HEAD -->
<? 
if($item['var_type']=='LIBRARY')
{
	$library_selected_values  = explode(OPT_SEPERATOR,$item['var_value']);
	$library_selected=$library_selected_values[0];
	$library_items_selected = explode(',',$library_selected_values[1]);
	if(count($library_items_selected)==1)
		$library_items_selected[1]=0;
?>
	<script type="text/javascript">
		$(document).ready(function(){
			library_selected(<?=$library_selected?>);
		});
	</script>
<?

} else {
	$library_selected=0;
	$library_items_selected=array(0,0);
}
?>
<script> 
			
			
function form_validation(thisform) {
	if (thisform.var_name.value == '') {
		alert ('Var name is required');
		thisform.var_name.focus();
		return false;
	}
	return true; 
} 

function type_changed(t)
{
	if(t=='T' || t=='TA')
	{
		$(".extra_values,.extra_values2,.helper,.dropdown").addClass("hidden");
	}
	else if(t=='R')
	{
		$(".extra_values").removeClass("hidden");
		$(".helper").removeClass("hidden");
		$(".extra_values2,.dropdown,.library_dropdown").addClass("hidden");
		$("#rvar_value_type_l").attr("checked","checked");
	}
	else if(t=='C')
	{
		$(".helper").removeClass("hidden");
		$(".extra_values").removeClass("hidden");
		$(".extra_values2,.dropdown,.library_dropdown").addClass("hidden");
		$("#rvar_value_type_c").attr("checked","checked");
	}else if(t=='S')
	{
		$(".extra_values,.extra_values2").removeClass("hidden");
		$(".helper").removeClass("hidden");
		$(".dropdown,.library_dropdown").addClass("hidden");
	}
	else if(t=='D' || t=='MSEL')
	{
		$(".extra_values,.extra_values2,.helper,.library_dropdown").addClass("hidden");
		$(".dropdown").removeClass("hidden");
	}
	else if(t=='LIBRARY' || t=='LIBRARY_MSEL'  || t=='MAP')
	{
		$(".extra_values,.extra_values2,.helper,.dropdown").addClass("hidden");
		$(".library_dropdown").removeClass("hidden");
		
		if(t=='MAP')
		{
			$("#library_item_id_2").addClass("hidden");
			$("#library_dropdown_id").val(<?=$library_details['id']?>);
			library_selected(<?=$library_details['id']?>,'LIBRARY_MSEL',t);
		}
	}
	else if (t=='F' || t=='DATE' || t=='TIME' || t=='USERS') {
		$(".extra_values,.extra_values2,.helper,.dropdown,.library_dropdown").addClass("hidden");
		
	}
}

// 

function library_selected(library_id,type,current_selected_type)
{
	type = typeof type == 'undefined'?'':type;
	current_selected_type = typeof current_selected_type == 'undefined'?'':current_selected_type;
	$.post("/super_admin/library_items/library_items_select_box_ajax",{"library_id":library_id,"type":type},function(ret){
		
		//console.log(ret);
		$(".library_item_id").html(ret);
		$("#library_items_select_box").removeClass("hidden");
		
		if(current_selected_type=='MAP')
		{
			$(".library_item_id:first").val("<?=$item['var_value']?>");
		}
		else {
			$(".library_item_id:first").val("<?=$library_items_selected[0]?>");
			$(".library_item_id:last").val("<?=$library_items_selected[1]?>");
		}
		
	});
}


$(document).ready(function(){
	type_changed("<?=$item['var_type']?>");
});

$(document).ready(function(){
	var dp = $('.datepicker').datepicker().on('changeDate', function(ev) {
	  dp.datepicker('hide');
	});
	$('.timepicker').timepicker();
	$(".chosen-select").chosen();
});
</script>

</head>
<body>
<!--HEADER-->
	<? include(APPPATH."views/super_admin/header.inc.php"); ?>
<!-- /HEADER-->

<!-- /container -->
<div class="content">

	<!-- Sidebar -->
	<? include(APPPATH."views/super_admin/sidebar.inc.php"); ?>
    <!-- Sidebar ends -->

  	<!-- Main bar -->
  	<div class="mainbar">

      <!-- Page heading -->
      <div class="page-head">
        <!-- Breadcrumb -->
        <div class="bread-crumb pull-left">
          <a href="/super_super_admin/"><i class="icon-home"></i> Home</a> 
          <!-- Divider -->
          <span class="divider">/</span> 
          <a href="/super_admin/library/main/">Library </a>
          <span class="divider">/</span> 
          <a href="/super_admin/library_items/main/<?=$library_details['id']?>"><?=$library_details['name']?> </a>
          <span class="divider">/</span> 
          <a href="#" class="bread-current"> Edit Field</a>
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
	          	                  <div class="pull-left">Edit Field (<?=$item['var_name']?>)</div>
	          	                  <div class="widget-icons pull-right">
	          	                    <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	          	                    <a href="#" class="wclose"><i class="icon-remove"></i></a>
	          	                  </div>
	          	                  <div class="clearfix"></div>
	          	                </div>
	          	
	          	                <div class="widget-content">
	          	                  <div class="padd">
	          	
	          	                    <!-- Form starts.  -->
	          	                     <form class='form-horizontal' name='frm_edit_library_items' method='post' action='/super_admin/library_items/do_update/<?=$item['id']?>' onSubmit='return form_validation(this);' enctype='multipart/form-data'>
	          	                     <table class="table table-bordered table-striped table-hover content" style="width:100%;" cellpadding="0" cellspacing="0">
	          	                     <input type='hidden' id='chaabee_post' name='chaabee_post' value=''>
	          	                     <input type='hidden' id='id' name='id' value='<?=$item['id']?>'>
	          	                     <input type="hidden" name="library_id" value="<?=$item['library_id']?>" />
	          	                     
									<tr>
									<td width='35%' class='capitalize' valign='top' align='left'>Field Name:</td>
									<td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='var_name'  value='<?=$item['var_name']?>'  /></td></tr>
									<tr>
										<td width='35%' class='capitalize'  valign='top' align='left'>Field Type:</td>
										<td width='65%' class='capitalize'>
											<select name="var_type" id="var_type" class="chosen-select form-control" onchange="type_changed(this.value);">
												<?
													$field_types = get_global_field_types();
													foreach ($field_types as $key => $value) {
														?>
														<option value="<?=$key?>" <? if(isset($item)){ if($item['var_type']==$key)echo 'selected="selected"'; } ?>><?=$value?></option>
														<?
													}
												?>
												
											</select>
										</td>
									</tr>
									<tr class="extra_values2 hidden">
										<td width='35%' class='capitalize'  valign='top' align='left'>Field Value Type:</td>
										<td width='65%' class='capitalize'>
											<div class="radio">
												<label>
													<input name="var_value_type" type="radio" id="rvar_value_type" value="T" <? if(isset($item)){ if($item['var_value_type']=='T')echo 'checked="checked"'; } ?> />Simple Text
												</label>
											</div>
											<div class="radio">
												<label>
													<input name="var_value_type" type="radio" id="rvar_value_type" value="P" <? if(isset($item)){ if($item['var_value_type']=='P')echo 'checked="checked"'; } ?> />Php Evaluate
												</label>
											</div>
											<div class="radio">
												<label>
													<input name="var_value_type" type="radio" id="rvar_value_type" value="Q" <? if(isset($item)){ if($item['var_value_type']=='Q')echo 'checked="checked"'; } ?> />Mysql Query
												</label>
											</div>
											<div class="radio">
												<label>
													<input name="var_value_type" type="radio" id="rvar_value_type" value="L" <? if(isset($item)){ if($item['var_value_type']=='L')echo 'checked="checked"'; } ?> />List (comma separated)
												</label>
											</div>
											
										</td>
									</tr>
									<tr class="extra_values <?=in_array($item['var_type'],array('GRID','C','R'))?'':' hidden'?>">
										<td width='35%' class='capitalize' valign='top' align='left'>Value:</td>
										<td width='65%' class='capitalize'>
											<textarea  class='form-control'  rows=5 cols=35 name='var_value' id='var_value'><?=$item['var_value']?></textarea>
											<div class="hidden helper">Enter comma separated items e.g Yes,No</div>
											<div class="<?=in_array($item['var_type'],array('GRID'))?'':' hidden'?> helper_grid">E.g Column 1,Column 2 | Row 1,Row 2, Row 3 | Low,Med,High</div>
										</td>
									</tr>
									<tr class="dropdown <?=in_array($item['var_type'],array('D','MSEL'))?'':' hidden'?>">
										<td valign='top' align='left'>Dropdown</td>
										<td>
											<select name="dropdown_id" id="dropdown_id">
												<?
												$res = $this->db->query("select dropdown_id,`name` from `dropdown` where company_id=".$library_details['company_id']);
												if($res->num_rows()>0)
												{
													foreach ($res->result_array() as $data)
												 	{
												 		if($data['dropdown_id']==$item['var_value'])
												 			echo '<option value="'.$data['dropdown_id'].'" selected="selected">'.$data['name'].'</option>';
												 		else
												 			echo '<option value="'.$data['dropdown_id'].'">'.$data['name'].'</option>';
												 	}
												 }
												?>
											</select>
										</td>
									</tr>
									<tr class="library_dropdown<?=in_array($item['var_type'], array('LIBRARY','MAP','LIBRARY_MSEL'))?'':' hidden'?>">
										<td valign='top' align='left'>Library:</td>
										<td>
											<div class="pull-left">
											
											<select name="library_dropdown_id" id="library_dropdown_id" onchange="library_selected(this.value)">
												<option value="" selected="">Please select Library</option>
												<?
												$res = $this->db->query("select id,`name` from `library` where company_id=".$library_details['company_id']);
												if($res->num_rows()>0)
												{
													foreach ($res->result_array() as $data)
												 	{
												 		if($library_selected==$data['id'])
												 			echo '<option value="'.$data['id'].'" selected="selected">'.$data['name'].'</option>';
												 		else
												 			echo '<option value="'.$data['id'].'">'.$data['name'].'</option>';
												 	}
												 }
												?>
											</select>
											</div>
											
											<div id="library_items_select_box" class="hidden pull-left" style="margin-left: 15px;">
												<select id="library_item_id_1" name="library_item_id[]" class="library_item_id">
													<option value="" disabled="">Please select Library first</option>
												</select>
												<select id="library_item_id_2" name="library_item_id[]" class="library_item_id">
													<option value="" disabled="">Please select Library first</option>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td>Required Field?</td>
										<td>
											<input type="radio" name="is_required" value="Yes" <? if(isset($item)){ if($item['is_required']=='Yes')echo 'checked="checked"'; } ?> /> Yes
											<input type="radio" name="is_required" value="No" <? if(isset($item)){ if($item['is_required']=='No')echo 'checked="checked"'; } ?> /> No
										</td>
									</tr>
									<tr>
										<td>Show By Default?</td>
										<td>
											<input type="radio" name="show_by_default" value="Yes" <? if(isset($item)){ if($item['show_by_default']=='Yes')echo 'checked="checked"'; } ?> /> Yes
											<input type="radio" name="show_by_default" value="No" <? if(isset($item)){ if($item['show_by_default']=='No')echo 'checked="checked"'; } ?> /> No
										</td>
									</tr>
									<tr>
										<td width='35%' class='capitalize' valign='top' align='left'>Help Text:</td>
										<td width='65%' class='capitalize'>
											<textarea class='form-control' name='help'><?=$item['help']?></textarea>
										</td>
									</tr>
									<tr>
										<td width='35%' class='capitalize' valign='top' align='left'>Item Order:</td>
										<td width='65%' class='capitalize'>
											<input class='form-control' type='text' name='item_order' id='item_order'  value='<?=$item['item_order']?>' />
										</td>
									</tr>
									<tr>
										<td class='capitalize' width='35%'>&nbsp;</td>
										<td width='65%' class='capitalize'><input name='Submit' id='Submit' class="btn btn-danger" type='submit' value='Update' /></td>
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

</body>
</html>