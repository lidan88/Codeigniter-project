<h3>Qualitative and Quantitative Impacts Over Time</h3>
<table class="table">
	<tr>
		<?
			$list = explode('|',$fields['Financial Impact']);
			$list = explode(',',$list[0]);
			
			foreach ($list as $index => $lv) {
				?>
				<th align="left"><?=$lv?></th>
				<?	
			}
		?>
	</tr>
<?
	$financial = explode(',',$items['Financial Impact']);
	$reputation = explode(',',$items['Reputation Impact']);
	$legal = explode(',',$items['Legal Impact']);
	$operational = explode(',',$items['Operational Impact']);
	$list = array('Financial Impact' => $financial,
					'Reputation Impact' => $reputation,
					'Legal Impact' => $legal,
					'Operational Impact' => $operational);
	foreach ($list as $key => $value) {
?>
	<tr>
		<td align='left'>
			<?=$key?>
		</td>		
		<?
			foreach ($value as $key => $v) {
				?>
				<td align="left"><?=$v?></td>
				<?
			}
		?>
	</tr>
	<? } ?>
</table>