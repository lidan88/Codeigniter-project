<?
$limit=30;
$sort=isset($_REQUEST['sort'])?$_REQUEST['sort']:"user_id";
$ord=isset($_REQUEST['ord'])?$_REQUEST['ord']:"ASC";
$page=isset($_REQUEST['page'])?$_REQUEST['page']:0;
$submit=isset($_REQUEST['Submit'])?$_REQUEST['Submit']:"";
$search=isset($_REQUEST['search'])?$_REQUEST['search']:"";

$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord";
$href1=$_SERVER['PHP_SELF']."?";

?>

<?                
if(is_array($list) and count($list)>0)
{
    foreach($list as $k => $row)
    {
?>
    <tr>
    
	<td align="left" valign="top" class="rowDark"><?=isset($row['frole_id'])?$row['frole_id']:$row['role_id']?></td>
	<td align="left" valign="top" class="rowDark"><?=isset($row['ffirst_name'])?$row['ffirst_name']:$row['first_name']?></td>
	<td align="left" valign="top" class="rowDark"><?=isset($row['flast_name'])?$row['flast_name']:$row['last_name']?></td>
	<td align="left" valign="top" class="rowDark"><?=isset($row['fusername'])?$row['fusername']:$row['username']?></td>
	<td align="left" valign="top" class="rowDark"><?=isset($row['femail'])?$row['femail']:$row['email']?><br /><?=$row['aemail']?></td>
	<td align="left" valign="top" class="rowDark"><?=isset($row['fphone'])?$row['fphone']:$row['phone']?></td>
	<td align="left" valign="top" class="rowDark"><?=isset($row['fstatus'])?$row['fstatus']:$row['status']?></td>
	<td align="left" valign="top" class="rowDark"><?=isset($row['fadded'])?$row['fadded']:$row['added']?></td>
    <td><a class="btn btn-danger" target="_blank" href="/auth/login/?username=<?=urlencode($row['username'])?>&password=<?=urlencode($row['password'])?>">Impersonate As</a></td>
    
    	<td align="center" valign="top" class="rowDark">
    		<a style="text-decoration:none" class="btn btn-xs btn-warning" href="/admin/users/edit/<?=$row['user_id']?>/"><i class="icon-pencil"></i> </a>
    		<a style="text-decoration:none" class="btn btn-xs btn-danger" href="javascript:confirm_delete('/admin/users/do_delete/<?=$row['user_id']?>/');"><i class="icon-remove"></i></a>
    	</td>
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
	 <?=$pagination?>
</div>