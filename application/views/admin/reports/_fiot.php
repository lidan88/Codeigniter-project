<?
	$parts = explode('|',$impactDetails);
	$row = explode(',',$parts[0]);
	
	unset($row[0]);
	
	$col = explode(',',$parts[1]);
	$option = explode(',',$parts[2]);
?>
<h3><?=$type_key?> Impact Over Time</h3>
<table class="table">
	<tr>
		<th align='left'>
			Process
		</th>		
		
		<? if($timeframe==""){
		
			foreach ($row as $key => $value) {
				?>
				<th align='left'>
					<?=$value?>
				</th>
				<?
			}
		 ?>
		<? }else{ ?>
		<th align="left">
			<?=$timeframe?>
		</th>
		<? } ?>
	</tr>
<?
	foreach ($items as $key => $v) {
		
		$value = explode(',',$v[$type_key.' Impact']);
		
		if($impact!="")
		{
			if($timeframe=="")
			{
				if(strpos(strtolower(hasKey($v,$type_key.' Impact')),strtolower($impact))===false)
				{
					continue;
				}
			}
			else {
				$shouldSkipRow = false;
				foreach($row as $rk => $rv)
				{
					if($rv==$timeframe)
					{
						if(strtolower(hasKey($value,$rk-1))!=strtolower($impact))
						{
							$shouldSkipRow=true;
						}
					}
				}
				
				if($shouldSkipRow)
					continue;
			}
		}
		
	
?>
	<tr>
		<td align='left'>
			<?=hasKey($v,'Process Name');?>
		</td>		
		<? if($timeframe==""){
			foreach ($row as $k => $v) {
				?>
				<td align='left'>
					<?=hasKey($value,$k-1)?>
				</td>
				<?
			}?>
			
		<?}else{?>
			<td>
				<?
					foreach($row as $rk => $rv)
					{
						if($rv==$timeframe){
							echo hasKey($value,$rk-1);
						}
					}
				?>
			</td>
		<? } ?>
	</tr>
	<? } ?>
</table>