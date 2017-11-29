<?
$limit=30;
$sort=isset($_REQUEST['sort'])?$_REQUEST['sort']:"call_chain_id";
$ord=isset($_REQUEST['ord'])?$_REQUEST['ord']:"ASC";
$page=isset($_REQUEST['page'])?$_REQUEST['page']:0;
$submit=isset($_REQUEST['Submit'])?$_REQUEST['Submit']:"";
$search=isset($_REQUEST['search'])?$_REQUEST['search']:'';
$items_per_page=isset($_REQUEST['items_per_page'])?$_REQUEST['items_per_page']:10;

$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
$href1=$_SERVER['PHP_SELF']."?items_per_page=$items_per_page";

?>
			                	    <?                
			                	        if(is_array($list) and count($list)>0)
			                	        {
			                	            foreach($list as $k => $row)
			                	            {
			                	        ?>
			                	            <tr id="item_no_<?=$row['call_chain_id']?>">
			                	            	
			                	            	<td><input type="checkbox" name="items[<?=$row['call_chain_id']?>]" value="<?=$row['call_chain_id']?>" /></td>
			                	            
												<td align="left" valign="top" class="rowDark"><?=$row['name']?></td>
												<td align="left" valign="top" class="rowDark"><?=$row['description']?></td>
												<td align="left" valign="top" class="rowDark"><?=isset($row['fmodified_on'])?$row['fmodified_on']:$row['modified_on']?></td>
												<td align="left" valign="top" class="rowDark"><?=isset($row['fmodified_by'])?$row['fmodified_by']:$row['modified_by']?></td>
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
	 		Showing <?=(($page*$items_per_page)-$items_per_page+1)?>-<?=($page*$items_per_page)?> of <?=$pagination['total_items']?>
	 </div>
	 <div class="clearfix"></div>
 </div>
