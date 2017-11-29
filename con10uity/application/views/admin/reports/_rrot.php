<h3>Required Resources Over Time</h3>
<table class="table">
	<tr>
		<?
			//die($fields['Required Resources Over Time']);
			list($columns,$resourceTypes,$options) = getGridHeaders($fields['Required Resources Over Time']);
			
			foreach ($columns as $index => $lv) {
				?>
				<th align="left"><?=$lv?></th>
				<?	
			}
		?>
	</tr>
<?

//echo '<tr><td><pre>';
//$test = explode(",",$items["Required Resources Over Time"]);
//print_r($items["Required Resources Over Time"]);
//echo '</pre></td></tr>';

if(is_array($items["Required Resources Over Time"]))
{
	foreach ($resourceTypes as $resourceKey => $resourceType) {
			//echo '<tr><td>'.$resourceFilter.' - '.$resourceType.'</td></tr>';
			if($resourceFilter!="" and $resourceFilter!=$resourceType)
				continue;
		?>
		<tr>
			<td><?=$resourceType?></td>
			<?
				foreach ($items["Required Resources Over Time"][$resourceKey] as $columnValue) {
					?>
					<td><?=$columnValue?></td>
					<?
				}
			?>
		</tr>
		<?
	}
}
?>
</table>