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
<? foreach($values as $value_id => $item){ ?>
<tr id="item_no_<?=$value_id?>">
	<td><input type="checkbox" name="items[<?=$value_id?>]" value="<?=$value_id?>" /></td>
	<? foreach($library_fields as $field)
		{
			if(is_field_skippable($field))
				continue;
		
			if(!isset($item[$field['id']]))
			{
				echo '<td></td>';
			}else {
	?>
			<td>
			<? if(!is_array($item[$field['id']])){
					
					if($field['var_type']=='F')
					{
						?>
						<a target="_blank" href="/user_data/<?=$item[$field['id']]?>"><?=$item[$field['id']]?></a>
						<?
					}
					else {
						echo opt2value($item[$field['id']]);
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
	<td><?=$items[$value_id]['modified_at']?></td>
	<td><?=$items[$value_id]['modified_by']?></td>
	<td><a href="/admin/library_items/view/<?=$value_id?>/">Full View</a></td>
</tr>
<? } ?>
<div id="pagination">
<?=$pagination?>
</div>