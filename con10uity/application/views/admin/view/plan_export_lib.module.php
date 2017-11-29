<?

echo "<h3>".$pi['title']."</h3>";
echo $pi['description'];

echo '<br /><br /><table class="table">';

echo '<tr>';
//For printing header before printing the data
if($pi['template']!='')
{
	foreach ($pi['template'] as $tkey => $tv) {
		echo '<th align="left">'.$tv['title'].'</th>';
	}
}
else {

	foreach ($pi['template_default'] as $dtkey => $dtv) {
		if($dtv['show_by_default']!='Yes')
			continue;
		
		echo '<th align="left">'.$dtv['var_name'].'</th>';
	}
}
echo '</tr>';


//echo '<pre>';
//print_r($pi);
//echo '</pre>';
//die;

//print_r($pi);
//For printing data tr items
foreach ($pi['selected_items'] as $key => $v) {
	echo "<tr>";
	
	$data_array = $v['value'];	
	
	if($pi['template']!='')
	{
		//Use template items
		foreach ($pi['template'] as $tkey => $tv) {
			if($tv['type']=='item')
			{
				echo "<td>".array_recursive_value($data_array[$tv['id']])."</td>";
			}
			else if($tv['type']=="group")
			{
				echo "<td>";
					$t=array();
					foreach ($tv['children'] as $ck => $cv) {
						if(trim($data_array[$cv['id']])!='')
						{
							if(isset($cv['show_attr']) and $cv['show_attr']=="1" and isset($pi['template_default'][$cv['id']]))
							{
								$t[] = $pi['template_default'][$cv['id']]['var_name'].': '.$data_array[$cv['id']].'<br />';
							}
							else {
								$t[] = $data_array[$cv['id']];
							}
						}
					}
					echo implode(", ", $t);
				echo "</td>";
			}
		}
	
	}
	
	else {
		//Use default template i-e show full columns
		foreach ($pi['template_default'] as $dtkey => $dtv) {
			if($dtv['show_by_default']=='No')
				continue;
			if($dtv['var_type'] =='LIBRARY_MSEL' || $dtv['var_type'] =='LIBRARY')
			{
				if(isset($data_array[$dtkey]))
				{
					$index = strrpos($data_array[$dtkey],'[#]');
					$data =substr($data_array[$dtkey],($index+3));
					echo "<td>".array_recursive_value($data)."</td>";
				}
				else {
					echo "<td></td>";
				}
			}
			else
			{
				if(isset($data_array[$dtkey]))
				{

					echo "<td>".array_recursive_value($data_array[$dtkey])."</td>";
				}
				else {
					echo "<td></td>";
				}
			}
		
		}
		
		/*foreach ($data_array as $key => $value) {
			if(!is_array($value))
				echo "<td>".$value."</td>";
			else {
				echo "<td>".array_recursive_value($value)."</td>";
			}
		}*/
		
	}
			
	echo "</tr>";
}

echo "</table>";

echo "<br /><br />".$pi['footer']."";
?>