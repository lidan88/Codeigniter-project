<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Plans</title>

<!--HEAD-->
<? //include(APPPATH."views/admin/head.inc.php"); ?>
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
<div style="border: 3px solid #333; width: 100%; height: 100%;">
<?
	$ctr=0;
	foreach ($plan_items as $pi_key => $pi) {
		if($ctr>0)
			echo "<pagebreak />";
		
		$ctr++;
		
			
		if($pi['plan_type']=='lib')
		{	
			include(APPPATH."views/admin/view/plan_export_lib.module.php");		
		}  // end of lib play type
		else if ($pi['plan_type']=='cc')
		{
			include(APPPATH."views/admin/view/plan_export_call_chain.module.php");
		} // End of Call Chain Type
		else if ($pi['plan_type']=='ra')
		{
			include(APPPATH."views/admin/view/plan_export_ta.module.php");
		}
		else if ($pi['plan_type']=='gallery')
		{
			include(APPPATH."views/admin/view/plan_export_gallery.module.php");
		}	
		
		/*echo '<pre>';
		print_r($value);
		echo '</pre>';*/
		echo '<br />';
		
		
	}
?>
</div>
</body>
</html>