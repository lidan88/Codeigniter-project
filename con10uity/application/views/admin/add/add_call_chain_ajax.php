<?
	foreach($employees as $employee_id => $employee)
	{
		?>
		<tr id="emp_<?=$employee_id?>" class="employee">
			<td><span style="cursor: move;" class="icon icon-th-list handle"></span>  &nbsp;<input type="checkbox" class="chk" name="employees[<?=$employee_id?>]" value="<?=$employee_id?>" /></td>
			<td><?=$employee['First Name']?></td>
			<td><?=$employee['Last Name']?></td>
			<td><?=$employee['Department']?></td>
			<td><?=$employee['Location']?></td>
			<td><a style="text-decoration:none" class="btn btn-xs btn-warning" target="_blank" href="/admin/library_items/edit_user_data/<?=$employee_id?>/"><i class="icon-pencil"></i> </a></td>
		</tr>
		<?
	}
?>
	          	                    			