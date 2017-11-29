<?
//Call Chain Plan Type
$total_ra=count($pi['ra']);
$current_ra_count=0;
foreach ($pi['ra'] as $raid => $rav) {
	$current_ra_count++;
	//$ccv['']
	echo "<h3>".$pi['title']."</h3>";
	echo $pi['description'];
	
	echo '<br /><br /><table class="table">';
	
	echo "<tr>
			<th align='left' width='30%'>Threat</th>
			<th align='left'>Likelihood</th>
			<th align='left'>Impact</th>
			<th align='left' width='30%'>Weight</th>
	</tr>";	
	
	foreach ($pi['threats_by_group'] as $group_name => $threat_list) {
		
		echo "<tr><th colspan='4' align='left'>".$group_name."</th></tr>";
		
		foreach($threat_list as $threat_id => $threat)
		{
			echo "<tr>
					<td>".$rav[$threat_id]['threat_name']."</td>
					<td>".$rav[$threat_id]['likelihood']."</td>
					<td>".$rav[$threat_id]['impact']."</td>
					<td>".$rav[$threat_id]['weight']."</td>
				</tr>";
		
		}
	}
	
	echo "</table>";
	echo "<br /><br />".$pi['footer']."";
	if($current_ra_count < $total_ra)
		echo "<pagebreak />";
}
?>