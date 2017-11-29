<?
print_r($pi);
echo "<h3>".$pi['title']."</h3>";
//echo $pi['description'];

/*echo '<br /><br /><table class="table">';

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
*/

//For printing data tr items
foreach ($pi['selected_items'] as $key => $v) {
//	echo "<tr>";

	$data_array = $v['value'];	
/*	if($pi['template']!='')
	{
		//Use template items
		foreach ($pi['template'] as $tkey => $tv) {
			if($tv['type']=='item')
			{
				echo "<td>".$data_array[$tv['id']]."</td>";
			}
			else if($tv['type']=="group")
			{
				echo "<td>";
					$t=array();
					foreach ($tv['children'] as $ck => $cv) {
						if(trim($data_array[$cv['id']])!='')
							$t[] = $data_array[$cv['id']];
					}
					echo implode(", ", $t);
				echo "</td>";
			}
		}
	
	}
	else {
*/	

		//print_r($pi['template_default']);

		//Use default template i-e show full columns
		foreach ($pi['template_default'] as $dtkey => $dtv) {
	
			if($dtv['show_by_default']=='No')
				continue;
	
			if(isset($data_array[$dtkey]))
			{
				if(!is_array($data_array[$dtkey]))
				{
					echo "<td>".$data_array[$dtkey]."</td>";
					if(is_file_name($data_array[$dtkey]))
					{
						//echo "ls ".$this->config->item('upload_path').$data_array[$dtkey]."*.jpg",$output."<br />";
						exec("ls ".$this->config->item('upload_path').$data_array[$dtkey]."*.jpg",$output);
						
						if(is_array($output))
						{
							foreach($output as $img)
							{
								$img = str_replace("/user/shared/nginx/html/continuitypro/user_data/",'',$img);
								echo '<img src="http://continuitypro.net/user_data/'.$img.'" />';
							}
						}
						//print_r($output);
						//echo $output;
						/*
						$outputArray = explode(" ",$output);
						if(is_arary($outputArray))
						{
							foreach($outputArray as $img)
								echo '<img src="http://continuitypro.net/user_data/'.$img.'" />';
						}*/
					
					}
				}
				else {
					//echo "<td>".array_recursive_value($data_array[$dtkey])."</td>";
				}
			}
			else {
				//echo "<td></td>";
			}
		
		}
		
		/*foreach ($data_array as $key => $value) {
			if(!is_array($value))
				echo "<td>".$value."</td>";
			else {
				echo "<td>".array_recursive_value($value)."</td>";
			}
		}*/
		
//	}
			
//	echo "</tr>";
}

//echo "</table>";

//echo "<br /><br />".$pi['footer']."";
?>