<h3>RPO (Recovery Point Objective) Gap Analysis</h3>
<table class="table">
	<tr>
		<th align='left'>
			Process
		</th>		
		<th align='left'>
			RPO
		</th>
		<th align='left'>
			Actual RPO
		</th>
		<th align='left'>
			Gap
		</th>
	</tr>
<?
	if(is_array($items))
	{
		foreach ($items as $key => $bia) {
			
			$raiseAlert = '<img src="/img/alert2_icon.png" />';
			$actualRTO = detectHours(hasKey($bia,'Actual RPO'));
			$RPO = detectHours(hasKey($bia,'Recovery Point Objective (RPO)'));
			if(is_numeric($actualRTO))
			{ 
				//die("her");
				if(abs($RPO-$actualRTO) < 24){
					$raiseAlert = '<img src="/img/ok_icon.png" />';
				}
			}
			echo '
			<tr>
				<td>
					'.hasKey($bia,'Process Name').'
				</td>
				<td>
					'.hasKey($bia,'Recovery Point Objective (RPO)').'
				</td>
				<td>
					'.hasKey($bia,'Actual RPO').'
				</td>
				<td>
					'.$raiseAlert.'
				</td>
			</tr>';
		}
	}
?>
</table>