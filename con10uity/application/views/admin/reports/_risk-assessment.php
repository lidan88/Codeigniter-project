<h3>Risk Assessment: <?=$risk_assessment_detail['name']?></h3>
<table class="table">
<?
	foreach ($items['threats_by_group'] as $group_name => $threat_list) 
	{
		if($groupFilter!='' and $groupFilter!=$group_name)
		{
			continue;
		}
		?>
		<tr>
			<th colspan='4' align='left'><?=$group_name?> Threats</th>
		</tr>
		<tr>
			<th align='left' width='30%'>Threat</th>
			<th align='left'>Likelihood</th>
			<th align='left'>Impact</th>
			<th align='left' width='30%'>Weight</th>
		</tr>	
		<?
		foreach($threat_list as $threat_id => $threat)
		{
			//echo "<tr><td>".$threat_id."</td></tr>";
			if($weightFilter!='' and strtolower($weightFilter)!=strtolower($items['ra'][$threat_id]['weight']))
			{
				continue;
			}
			
			echo "<tr>
					<td>".$items['ra'][$threat_id]['threat_name']."</td>
					<td>".$items['ra'][$threat_id]['likelihood']."</td>
					<td>".$items['ra'][$threat_id]['impact']."</td>
					<td>".$items['ra'][$threat_id]['weight']."</td>
				</tr>";
		}
	}
?>
</table>