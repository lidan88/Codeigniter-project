<?
//Call Chain Plan Type
$total_cc=count($pi['call_chains']);
$current_cc_count=0;

foreach ($pi['call_chains'] as $ccid => $ccv) {
	$current_cc_count++;
	//$ccv['']
	echo "<h3>".$ccv['name']."</h3>";
	echo $pi['description'];
	
	echo '<br /><br /><table class="table">';
	
	echo "<tr>
			<th align='left'>Caller</th>
			<th align='left'>Person To Be Called</th>
			<th align='left'>Home</th>
			<th align='left'>Cell</th>
			<th align='left'>Work</th>
	</tr>";
	
	$initial_caller_id=0;
	
	foreach($ccv['call_chain'] as $chain_items)
	{
		if($initial_caller_id != $chain_items[0])
		{
			$caller = $ccv['employees'][$chain_items[0]]["First Name"];
			$initial_caller_id=$chain_items[0];
		}
		else {
			$caller = "";
		}
		
		$home = isset($ccv['employees'][$chain_items[1]]["Home Phone"])?$ccv['employees'][$chain_items[1]]["Home Phone"]:"";
		$cell = isset($ccv['employees'][$chain_items[1]]["Cell Phone"])?$ccv['employees'][$chain_items[1]]["Cell Phone"]:"";
		$work = isset($ccv['employees'][$chain_items[1]]["Work Phone"])?$ccv['employees'][$chain_items[1]]["Work Phone"]:"";
		
		echo "<tr>
				<td>".$caller."</td>
				<td>".$ccv['employees'][$chain_items[1]]["First Name"]."</td>
				<td>".$home."</td>
				<td>".$cell."</td>
				<td>".$work."</td>
			</tr>";
	
	}
	
	echo "</table>";
	echo "<br /><br />".$pi['footer']."";
	if($current_cc_count < $total_cc)
		echo "<pagebreak />";
}
?>