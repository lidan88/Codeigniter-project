<h3>RTO (Recovery Time Objective) Gap Analysis</h3>
<table class="table">
	<tr>
		<th align='left'>
			Process
		</th>		
		<th align='left'>
			RTO
		</th>
		<th align='left'>
			Actual RTO
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
			$actualRTO = detectHours(hasKey($bia,'Actual RTO'));
			$RTO = detectHours(hasKey($bia,'Recovery Time Objective (RTO)'));
			if(is_numeric($actualRTO))
			{ 
				//die("her");
				if(abs($RTO-$actualRTO) < 24){
					$raiseAlert = '<img src="/img/ok_icon.png" />';
				}
			}
			echo '
			<tr>
				<td>
					'.hasKey($bia,'Process Name').'
				</td>
				<td>
					'.hasKey($bia,'Recovery Time Objective (RTO)').'
				</td>
				<td>
					'.hasKey($bia,'Actual RTO').'
				</td>
				<td>
					'.$raiseAlert.'
				</td>
			</tr>';
		}
	}
?>
</table>