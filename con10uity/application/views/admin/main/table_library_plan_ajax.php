<?

$sort=isset($_REQUEST['sort'])?$_REQUEST['sort']:"id";
$ord=isset($_REQUEST['ord'])?$_REQUEST['ord']:"ASC";
$page=isset($_REQUEST['page'])?$_REQUEST['page']:0;
$search=isset($_REQUEST['search'])?$_REQUEST['search']:"";
$submit=isset($_REQUEST['Submit'])?$_REQUEST['Submit']:"";
$items_per_page=isset($_REQUEST['items_per_page'])?$_REQUEST['items_per_page']:10;

$items_per_page_value = "sort=$sort&ord=$ord&items_per_page=";
$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
$href1=$_SERVER['PHP_SELF']."?";

?>

<script type="text/javascript">
	$(document).ready(function() 
	    { 
	        $("#list").tablesorter({ 
	                // pass the headers argument and assing a object 
	                headers: { 
	                    // assign the secound column (we start counting zero) 
	                    0: { sorter:false}
	                    
	                } 
	            }); 
	    } 
	);
</script>

<table id="list" class="table">
	<thead>
	<tr>
		<th width="10" style="width: 10px;">
			<input type="checkbox" class="select_all" value="" onclick="select_all_boxes(this.checked)" />
		</th>
		<?
		$ctr=0;
		foreach($library_fields as $field)
		{
			if(is_field_skippable($field))
				continue;
			
			$ctr++;	
		?>
		
		<th>
			<? /*if($ctr==1){ ?>
				<input type="checkbox" value="" onclick="select_all_boxes(this.checked)" />&nbsp;
			<? }*/ ?>
			<?
			/*if ($sort==$field['id']) {
				if ($ord=='ASC') {
					echo  '<a href="'.$href1.'&sort='.$field['id'].'&ord=DESC&items_per_page='.$items_per_page.'&Submit=Search">';
				} else {
					echo  '<a href="'.$href1.'&sort='.$field['id'].'&ord=ASC&items_per_page='.$items_per_page.'&Submit=Search">';
				}
			} else {
				echo  '<a href="'.$href1.'&sort='.$field['id'].'&ord=ASC&items_per_page='.$items_per_page.'&Submit=Search">';
			}*/
			?>
				<a href="#"><?=$field['var_name']?></a>
			<!--</a>-->
		</th>
		<? } ?>
		<th>Action</th>
	</tr>
	</thead>
	<tbody>
		
		<? foreach($values as $value_id => $item){ ?>
		<tr id="item_no_<?=$value_id?>">
			<td>
				<input type="checkbox" onclick="do_action($(this));" name="items[<?=$value_id?>]" value="<?=$value_id?>" />
			</td>
			
			<? 
				
				$ctr=0;
				foreach($library_fields as $field)
				{
					if(is_field_skippable($field))
						continue;
				
					$ctr++;
					
					if(!isset($item[$field['id']]))
					{
						echo '<td></td>';
					}else {
			?>
					<td library="" data="<? if(!is_array($item[$field['id']])){ echo $item[$field['id']]; }else{ echo array_recursive_value($item[$field['id']]);  } ?>">
					<?/* if($ctr==1){ ?>
					<input type="checkbox" class="chk" name="items[<?=$value_id?>]" value="<?=$value_id?>" />
					<? }*/ ?>
					
					<? if(!is_array($item[$field['id']])){
							
							if($field['var_type']=='F')
							{
								?>
								<a target="_blank" href="/user_data/<?=$item[$field['id']]?>"><?=$item[$field['id']]?></a>
								<?
							}
							else {
								echo makelink(opt2value($item[$field['id']]));
							}
						}
						else {
							echo array_recursive_value($item[$field['id']]);
						}
					 ?>
					
					<?//  echo hl($item[$field['id']], arrya($search));?></td>
					
			<?
					}
					
				}
			?>
			<td><a style="text-decoration:none" class="btn btn-xs btn-warning" target="_blank" href="/admin/library_items/edit_user_data/<?=$value_id?>/"><i class="icon-pencil"></i> </a></td>
			
			
		</tr>
		<? } ?>
		
	</tbody>
</table>
<div class="widget-foot" style="padding-left: 7px;">
	<? if($page_type==""){ ?>
		<a class="btn btn-success" onclick="save_items()">Save Plan Item</a>
	<? }elseif($page_type=="report"){ ?>
		<a class="btn btn-success" onclick="save_items()">Print Report</a>
	<? } ?>
</div>
<? if($library_values_count>0){ ?>
<div id="pagination" class="widget-foot">
  <div class="pull-left">
  	<?=$pagination?>
  </div>
  	<div class="pull-left" style="margin-top: 15px; margin-left: 15px;">
  	Show 
  	</div>
  	<div class="pull-left">
  	<select class="form-control" onchange="set_items_per_page(this.value)" id="items_per_page" name="items_per_page" style="margin-top: 9px; margin-left: 5px;">
		<option value="5" <?=($items_per_page==5)?'selected="selected"':''?>>5</option>
		<option value="10" <?=($items_per_page==10)?'selected="selected"':''?>>10</option>
  		<option value="25" <?=($items_per_page==25)?'selected="selected"':''?>>25</option>
  		<option value="50" <?=($items_per_page==50)?'selected="selected"':''?>>50</option>
  		<option value="100" <?=($items_per_page==100)?'selected="selected"':''?>>100</option>
  	</select>
  	</div>
  	<div class="pull-left" style="margin-top: 15px; margin-left: 10px;">
  		Items
  		
  	</div>
  	<div class="pull-right" style="margin-top: 15px; margin-right: 10px;">
  		<? if($page==0)$page=1; ?>
  		Showing <?=(($page*$items_per_page)-$items_per_page+1)?>-<?=$library_values_count<($page*$items_per_page)?$library_values_count:($page*$items_per_page)?> of <?=$library_values_count?>
  	</div>
  <div class="clearfix"></div>
</div>
<? } ?>