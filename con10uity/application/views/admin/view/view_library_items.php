<?
include("includes/master.inc.php");

$result = mysql_query("select * from library_items WHERE id = $_REQUEST[id]");
if(mysql_num_rows($result)>0)
	$item = mysql_fetch_array($result);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? include("common_head.inc.php"); ?>
<style>	.capitalize { text-transform:capitalize; } </style>
</head>

<body>
<div id="container">
    <div class="hidden">
    <div id="sample-modal"><h2 style="font-size:160%; font-weight:bold; margin:10px 0;">Modal Box Content</h2><p>Place your desired modal box content here</p></div>
    </div><!-- end of hidden -->
    <? include("header.php"); ?>
    <!-- end of #header -->

<div id="content">
	<div id="content-top">
    <h2>View - <a href="main_library_items.php">Library items</a></h2>
      <!--<a href="#" id="topLink">Change Order</a> -->
      <span class="clearFix">&nbsp;</span>
      </div><!-- end of div#content-top -->
      <div id="left-col">
          <? include("sidebar.php"); ?>
      </div> <!-- end of div#left-col -->
      
      <div id="mid-col" class="full-col"><!-- end of div.box -->
      	
   	  <div class="box">
      		<h4 class="white">library_items Details - <a href="javascript:history.go(-1);">Go Back</a></h4>
        <div class="box-container">     		
            <form class='form-horizontal' name='frm_edit_library_items' method='post' action='/admin/library_items/do_update/' onSubmit='return form_validation(this);' enctype='multipart/form-data'><table class="table table-bordered table-striped table-hover content" style="width:100%;" cellpadding="0" cellspacing="0"><input type='hidden' id='chaabee_post' name='chaabee_post' value=''><input type='hidden' id='id' name='id' value='<?=$item['id']?>'><tr>
							<td>Library :</td>
							<td></td>
						</tr><tr>
							<td>Var name:</td>
							<td><?=$item['var_name']?></td>
						</tr><tr>
							<td>Var type:|var_type</td>
							<td></td>
						</tr><tr>
							<td>Var value:</td>
							<td><?=$item['var_value']?></td>
						</tr><tr>
							<td>Var value type:|var_value_type</td>
							<td></td>
						</tr><tr>
							<td>Item order:</td>
							<td><?=$item['item_order']?></td>
						</tr><tr>
							<td>Submit</td>
							<td></td>
						</tr></table></form> 
      	</div><!-- end of div.box-container -->
      	</div> <!-- end of div.box -->
      
      </div><!-- end of div#mid-col --><!-- end of div#right-col -->     
      
      <span class="clearFix">&nbsp;</span>     
</div><!-- end of div#content -->
<div class="push"></div>
</div><!-- end of #container -->

<div id="footer-wrap">
	<? include("footer.php"); ?>
</div>


</body>
</html>