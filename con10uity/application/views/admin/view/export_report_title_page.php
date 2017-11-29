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
		
		.plan_detail{
			font-size: 20px;
			margin-bottom: 25px;
		}
		
	</style>
</head>
<body>
<div style="border: 3px solid #333; width: 100%; height: 100%;">
	<div style="text-align: center; width: 500px;margin: 0 auto; margin-top: 30%;">
		<h1 style="margin: 0px; font-size: 50px;"><?=$company['name']?></h1>
		<h1 style="margin: 0px;"><strong><?=$pi['name']?></strong></h1>
	</div>
	
	<div style="text-align: center; width: 500px; height: 60%;margin: 0 auto;">
		<br />
		<br />
		<? /*<h2 style="margin: 0px;">Report Name: <?=$pi['name']?></h2><br />
		<h2 style="margin: 0px;"><?=date("M, Y")?></h2>*/ ?>
	</div>
	
	<div style="text-align: center; width: 500px;margin: 0 auto;">
		<? if($company['logo']!=''){ ?>
			<img src="/user_data/<?=$company['logo']?>" alt="" />
		<? }else{ ?>
			<img src="/img/logo.png" alt="" />
		<? } ?>
	</div>
</div>
<? /*
<pagebreak />
<div>
	<?
		foreach($item['formated_info'] as $pdik => $pdiv)
		{
			if($pdik!="Plan Users")
			echo "<div class='plan_detail'><strong>".$pdik.":</strong> ".opt2value($pdiv).'</div>';
		}
	?>
	<div class='plan_detail'><strong>Date:</strong> <?=date("M d, Y");?></div>
	
	<div class='plan_detail'><strong>Last Modified By:</strong> <?=$item['fmodified_by']?></div>
	
	<div class='plan_detail'><strong>Last Modified On:</strong> <?=$item['modified_on']?></div>
</div>
*/
?>
</body>
</html>