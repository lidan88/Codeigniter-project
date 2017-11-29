<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Plans</title>
	<style>
		table {
		  border-spacing: 0;
		  border-collapse: collapse;
		  width: 100%;
		}
		td,
		th {
		  padding: 0;
		}
		
		
		.table td {
			border-bottom: 1px solid #333;
			padding: 10px;
			margin: 0px;
		}
	</style>
</head>
<body>
<div style="padding: 0px;">
<?
	foreach ($plan_items as $pi_key => $pi) {
		
		echo "<h2>".$pi['title']."</h2>";
		echo "<strong>".$pi['description']."</strong>";
		
		echo '<table class="table">';
			
			foreach ($pi['selected_items'] as $key => $v) {
				echo "<tr>";
				
				$data_array = $v['value'];	
				if($pi['template']!='')
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
					//Use default template i-e show full columns
					/*foreach ($pi['template_default'] as $tkey => $tv) {
						echo "<td>".$data_array[$tv['id']]."</td>";
					}*/
					foreach ($data_array as $key => $value) {
						if(!is_array($value))
							echo "<td>".$value."</td>";
						else {
							echo "<td>".array_recursive_value($value)."</td>";
						}
					}
					
				}
						
				echo "</tr>";
			}
		
		echo "</table>";
		
		echo "<br /><strong>".$pi['footer']."</strong>";
		/*echo '<pre>';
		print_r($value);
		echo '</pre>';*/
		echo '<br />';
	}
?>
</div>
</body>
</html>