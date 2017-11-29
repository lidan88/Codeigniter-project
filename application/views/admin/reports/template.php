<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Export Report</title>

<!--HEAD-->
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
		
		th{
			background-color: #eee;
		}
		
		.table td,th {
			border-bottom: 1px solid #333;
			padding: 10px;
			margin: 0px;
		}
		
		div.mpdf_toc {font-family: sans-serif; font-size: 11pt;}
		a.mpdf_toc_a  {text-decoration: none; color: #5b82b3;}
		/* Whole line level 0 */
		div.mpdf_toc_level_0 {line-height: 1.5; margin-left: 0; padding-right: 2em;}
		/* padding-right should match e.g <dottab outdent="2em" /> 0 is default */
		/* Title level 0 - may be inside <a> */
		span.mpdf_toc_t_level_0 {font-weight: bold;}
		/* Page no. level 0 - may be inside <a> */
		span.mpdf_toc_p_level_0 {}
		/* Whole line level 1 */
		div.mpdf_toc_level_1 {margin-left: 2em; text-indent: -2em; padding-right: 2em;}
		/* padding-right should match <dottab outdent="2em" /> 2em is default */
		/* Title level 1 */
		span.mpdf_toc_t_level_1 {font-style: italic; font-weight: bold;}
		/* Page no. level 1 - may be inside <a> */
		span.mpdf_toc_p_level_1  {}
		/* Whole line level 2 */
		div.mpdf_toc_level_2 {margin-left: 4em; text-indent: -2em; padding-right: 2em;}
		/* padding-right should match <dottab outdent="2em" /> 2em is default */
		/* Title level 2 */
		span.mpdf_toc_t_level_2 {}
		/* Page no. level 2 - may be inside <a> */
		span.mpdf_toc_p_level_2 {}
				
	</style>
</head>
<body>
<!--<tocpagebreak>-->
<div>
	<h2><?=$title?></h2>
	<div><?=$header?></div>
	<div>&nbsp;</div>
<?
	if($type=="RTO")
	{
		include(APPPATH."views/admin/reports/_rto-gap-analysis.php");	
	}elseif($type=="RPO")
	{
		include(APPPATH."views/admin/reports/_rpo-gap-analysis.php");	
	}
	elseif($type=="QQIOT")
	{
		include(APPPATH."views/admin/reports/_qqiot2.php");	
	}
	elseif ($type=="FIOT") 
	{
		include(APPPATH."views/admin/reports/_fiot.php");	
	}elseif ($type=="RROT") 
	{
		include(APPPATH."views/admin/reports/_rrot.php");	
	}elseif ($type=="RISKASSESSMENT")
	{
		include(APPPATH."views/admin/reports/_risk-assessment.php");
	}
	
	
?>
	<div>&nbsp;</div>
	<div><?=$footer?></div>

</div>
</body>
</html>