<?
$limit=30;
$sort=isset($_REQUEST['sort'])?$_REQUEST['sort']:"bia_id";
$ord=isset($_REQUEST['ord'])?$_REQUEST['ord']:"ASC";
$page=isset($_REQUEST['page'])?$_REQUEST['page']:0;
$submit=isset($_REQUEST['Submit'])?$_REQUEST['Submit']:"";
$search=isset($_REQUEST['search'])?$_REQUEST['search']:"";
$items_per_page=isset($_REQUEST['items_per_page'])?$_REQUEST['items_per_page']:10;
$items_per_page_value = "sort=$sort&ord=$ord&items_per_page=";

$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord&search=$search&page=$page&items_per_page=$items_per_page";
$href1=$_SERVER['PHP_SELF']."?items_per_page=$items_per_page&search=$search";

?>
<?                
    if(is_array($list) and count($list)>0)
    {
        foreach($list as $k => $row)
        {
    ?>
        <tr id="item_no_<?=$row['bia_id']?>">
        	<td><input type="checkbox" name="items[<?=$row['bia_id']?>]" value="<?=$row['bia_id']?>" /></td>
       		
	<td align="left" valign="top" class="rowDark"><?=isset($row['fname'])?$row['fname']:$row['name']?></td>
	<td align="left" valign="top" class="rowDark"><?=isset($row['fdescription'])?$row['fdescription']:$row['description']?></td>
	<td align="left" valign="top" class="rowDark"><?=isset($row['fconducted_by'])?$row['fconducted_by']:$row['conducted_by']?></td>
	<td align="left" valign="top" class="rowDark"><?=isset($row['fcategory'])?$row['fcategory']:$row['category']?></td>
	<td align="left" valign="top" class="rowDark"><?=isset($row['fstatus'])?$row['fstatus']:$row['status']?></td>
	<td align="left" valign="top" class="rowDark"><?=isset($row['fmodified_on'])?$row['fmodified_on']:$row['modified_on']?></td>
	<td align="left" valign="top" class="rowDark"><?=isset($row['fmodified_by'])?$row['fmodified_by']:$row['modified_by']?></td>
        
        	<!--<td align="center" valign="top" class="rowDark">
        		<a style="text-decoration:none" class="btn btn-xs btn-warning" href="/admin/bia/edit/<?=$row['bia_id']?>/"><i class="icon-pencil"></i> </a>
        		<a style="text-decoration:none" class="btn btn-xs btn-danger" href="javascript:confirm_delete('/admin/bia/do_delete/<?=$row['bia_id']?>/');"><i class="icon-remove"></i></a>
        	</td>-->
        </tr>
    <? 	
        }
    }	
    else
    {
    ?>
        <tr>
            <td>
                <div class='b1 txt padd5'><strong>No items found!</strong></div>
            </td>
        </tr>
    <?
    }	
    ?>
	        
<div id="pagination">
	 <div class="pull-left">
	 	<?=$pagination['pagination']?>
	 </div>
	 <div class="pull-left" style="margin-top: 15px; margin-left: 15px;">
	 Show 
	 </div>
	 <div class="pull-left">
	 <select class="form-control" onchange="set_items_per_page(this.value)" name="items_per_page" style="margin-top: 9px; margin-left: 5px;">
	 	<option value="<?=$items_per_page_value?>10" <?=($items_per_page==10)?'selected="selected"':''?>>10</option>
	 	<option value="<?=$items_per_page_value?>25" <?=($items_per_page==25)?'selected="selected"':''?>>25</option>
	 	<option value="<?=$items_per_page_value?>50" <?=($items_per_page==50)?'selected="selected"':''?>>50</option>
	 	<option value="<?=$items_per_page_value?>100" <?=($items_per_page==100)?'selected="selected"':''?>>100</option>
	 </select>
	 </div>
	 <div class="pull-left" style="margin-top: 15px; margin-left: 10px;">
	 	Items
	 	
	 </div>
	 <div class="pull-right" style="margin-top: 15px; margin-right: 10px;">
	 	Showing <?=(($page*$items_per_page)-$items_per_page+1)?>-<?=($page*$items_per_page)+$items_per_page?> of <?=$pagination['total_items']?>
	 </div>
	 <div class="clearfix"></div>
</div>
