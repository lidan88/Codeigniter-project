<!DOCTYPE html>
<html lang="en">
<head>
 <? include(APPPATH."views/head.inc.php"); ?>

<script type="text/javascript">
function complete_task(task_id)
{
	//alert(task_id);
	$.post("/admin/tasks/complete_task/",{"task_id":task_id},function(ret){
		$('#li_'+task_id).addClass("hidden");
	});
}
</script>

<script src="/js/app/home.js" type="text/javascript"></script>
</head>

<body>



<!-- Header starts -->
<? include(APPPATH."views/header.inc.php"); ?>
<!-- Header ends -->

<!-- Main content starts -->

<div class="content">

	<!-- Sidebar -->
	<? include(APPPATH."views/sidebar.inc.php"); ?>
    <!-- Sidebar ends -->

  	<!-- Main bar -->
  	<div class="mainbar">

      <!-- Page heading -->
      <div class="page-head">
			<div class="row pull-left" style="width: 400px; padding-top: 5px;">
				<div class="col-md-12">
					<select class="form-control" onchange="select_where(this.value)">
						<option value="">What do you want to do?</option>
                          <?
                          	foreach($activatedLibs as $lib)
                          	{
                          	?>
                          	<option value="<?=$lib['type']?>:<?=$lib['id']?>:<?=$lib['is_system']?>:<?=$lib['name']?>">Add or update <?=$lib['name']?></option>
                          	<?
                          	}	
                          ?>
                        </select>
	        		<!--<h2 class="pull-left"><i class="icon-list-alt"></i> Widgets</h2>-->
	        	</div>
			</div>
			<div class="pull-left" style="padding-top: 5px;">
				&nbsp;<a class="btn btn-primary" href="?v=classic">Switch to Dashboard View</a>
			</div>

        <!-- Breadcrumb -->
        <div class="bread-crumb pull-right">
          <a href="index.html"><i class="icon-home"></i> Home</a> 
          <!-- Divider -->
          <span class="divider">/</span> 
          <a href="#" class="bread-current">Dashboard</a>
        </div>

        <div class="clearfix"></div>

      </div>
      <!-- Page heading ends -->

	    <!-- Matter -->

	    <div class="matter">
        <div class="container">

            <div class="row">

            <!-- Task widget -->
            <div class="col-md-5">
              <div class="widget">
                <!-- Widget title -->
                <div class="widget-head">
                  <div class="pull-left">My Tasks</div>
                  <!--<div class="widget-icons pull-right">
                    <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                    <a href="#" class="wclose"><i class="icon-remove"></i></a>
                  </div>  -->
                  <div class="clearfix"></div>
                </div>
                <div class="widget-content">
                  <!-- Widget content -->
                  <!-- Task list starts -->
                  <ul class="task">

                   <? 
                   if(is_array($tasks))
                   foreach ($tasks as $key => $task) { ?>
                    <li id="li_<?=$task['task_id']?>">
                      <!-- Checkbox -->
                      <span class="uni"><input value="<?=$task['task_id']?>" onclick="complete_task(this.value)" type="checkbox"></span> 
                      <!-- Task -->
                      <?=$task['title']?><br /> Assigned By: <strong><?=$task['first_name']?></strong>
                      
                      <? if($task['priority']==0){ ?>
                      <span class="label label-warning pull-right">Low</span>
                      <? }else if($task['priority']==1){?>
                      <span class="label label-danger pull-right">Medium</span>
						<? }else{ ?>
                      <span class="label label-danger pull-right">Important</span>
                      <? } ?>
                    </li>
					<? }else{ ?> 
					 <li>No Tasks Found</li>
					<? }?>
                   <? /* <li>
                      <!-- Checkbox -->
                      <span class="uni"><input value="check1" type="checkbox"></span> 
                      <!-- Task -->
                      Download some action movies
                      <!-- Delete button -->
                      <a href="#" class="pull-right"><i class="icon-remove"></i></a>
                    </li>

                    <li>
                      <!-- Checkbox -->
                      <span class="uni"><input value="check1" type="checkbox"></span> 
                      <!-- Task -->
                      Read Harry Potter VII Book <span class="label label-danger">Important</span>
                      <!-- Delete button -->
                      <a href="#" class="pull-right"><i class="icon-remove"></i></a>
                    </li>

                    <li>
                      <!-- Checkbox -->
                      <span class="uni"><input value="check1" type="checkbox"></span> 
                      <!-- Task -->
                      Collect cash from friends for camp
                      <!-- Delete button -->
                      <a href="#" class="pull-right"><i class="icon-remove"></i></a>
                    </li>

                    <li>
                      <!-- Checkbox -->
                      <span class="uni"><input value="check1" type="checkbox"></span> 
                      <!-- Task -->
                      Sleep till tomorrow everning
                      <!-- Delete button -->
                      <a href="#" class="pull-right"><i class="icon-remove"></i></a>
                    </li>     
                    */ 
                    ?>                                                                                                        
                  </ul>
                  <div class="clearfix"></div>  

                  <div class="widget-foot">
                  </div>

                </div>
              </div>
            </div>
            
            <div class="col-md-7">
                          <div class="widget">
                            <!-- Widget title -->
                            <div class="widget-head">
                              <div class="pull-left">My BIA's</div>
                              <div class="widget-icons pull-right">
                                <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                                <a href="#" class="wclose"><i class="icon-remove"></i></a>
                              </div>  
                              <div class="clearfix"></div>
                            </div>
                            <div class="widget-content">
                              <!-- Widget content -->
                              <!-- Task list starts -->
                              
                              
                              <table class="table">
                                  <thead>
                                  <tr>
                                  <!--th><input type="checkbox" value="" onclick="select_all_boxes(this.checked)" /></th!-->
                                  
                              	<td class="rowHeader">Name</a></td>
                              	<td class="rowHeader">Description</a></td>
                              	<td class="rowHeader">Status</a></td>
                              	  <!--<td width="18%" align="center" class="rowHeader">Actions</td>-->
                                  </tr>
                                  </thead>
                                  <tbody>
                                  <?                
                                      if(is_array($bia_list) and count($bia_list)>0)
                                      {
                                          foreach($bia_list as $k => $row)
                                          {
                                      ?>
                                          <tr id="item_no_<?=$row['bia_id']?>">
                                          	<!--td><input type="checkbox" name="items[<?=$row['bia_id']?>]" value="<?=$row['bia_id']?>" /></td!-->
                                         		
                              				<td align="left" valign="top" class="rowDark"><? foreach($row['info'] as $nm => $val){ if(preg_match('/name/i',$nm)){ echo $val; break; } }?></td>
                              				<td align="left" valign="top" class="rowDark"><? foreach($row['info'] as $nm => $val){ if(preg_match('/desc/i',$nm)){ echo $val; break; } }?></td>
                              				<td align="left" valign="top" class="rowDark"><span class="label label-danger"><?=$row['status']?></span></td>
                              		      
                                          	<!--<td align="center" valign="top" class="rowDark">
                                          		<a style="text-decoration:none" class="btn btn-xs btn-warning" href="/admin/bia/edit/<?=$row['bia_id']?>/"><i class="icon-pencil"></i> </a>
                                          		<a style="text-decoration:none" class="btn btn-xs btn-danger" href="javascript:confirm_delete('/admin/bia/do_delete/<?=$row['bia_id']?>/');"><i class="icon-remove"></i></a>
                                          	</td>-->
                                          </tr>
                                      <? 	
                                          }
                                      }	
                                      else
                                      {
                                      ?>
                                          <tr>
                                              <td colspan="8">
                                                  <div class='b1 txt padd5'><strong>No items found!</strong></div>
                                              </td>
                                          </tr>
                                      <?
                                      }	
                                      ?>
                                      </tbody>
                                  </table>
                              
                              
                              
                              
                              <? /*
                              
                              <ul class="task">
            
                                <li>
                                  <!-- Checkbox -->
                                  <span class="uni"><input value="check1" type="checkbox"></span> 
                                  <!-- Task -->
                                  Goto Shopping in Walmart <span class="label label-danger">Important</span>
                                  <!-- Delete button -->
                                  <a href="#" class="pull-right"><i class="icon-remove"></i></a>
                                </li>
            
                                <li>
                                  <!-- Checkbox -->
                                  <span class="uni"><input value="check1" type="checkbox"></span> 
                                  <!-- Task -->
                                  Download some action movies
                                  <!-- Delete button -->
                                  <a href="#" class="pull-right"><i class="icon-remove"></i></a>
                                </li>
            
                                <li>
                                  <!-- Checkbox -->
                                  <span class="uni"><input value="check1" type="checkbox"></span> 
                                  <!-- Task -->
                                  Read Harry Potter VII Book <span class="label label-danger">Important</span>
                                  <!-- Delete button -->
                                  <a href="#" class="pull-right"><i class="icon-remove"></i></a>
                                </li>
            
                                <li>
                                  <!-- Checkbox -->
                                  <span class="uni"><input value="check1" type="checkbox"></span> 
                                  <!-- Task -->
                                  Collect cash from friends for camp
                                  <!-- Delete button -->
                                  <a href="#" class="pull-right"><i class="icon-remove"></i></a>
                                </li>
            
                                <li>
                                  <!-- Checkbox -->
                                  <span class="uni"><input value="check1" type="checkbox"></span> 
                                  <!-- Task -->
                                  Sleep till tomorrow everning
                                  <!-- Delete button -->
                                  <a href="#" class="pull-right"><i class="icon-remove"></i></a>
                                </li>                                                                                                             
                              </ul>
                              */
                              
                              ?>
                              
                              <div class="clearfix"></div>  
            
                              <div class="widget-foot">
                              </div>
            
                            </div>
                          </div>
                        </div>
          </div>
          
          <!-- table-->
          <div class="row">
          	<div class="col-md-12">
          		<div class="widget">
          		
          		                <div class="widget-head">
          		                  <div class="pull-left">My Plans</div>
          		                  <div class="widget-icons pull-right">
          		                    <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
          		                    <a href="#" class="wclose"><i class="icon-remove"></i></a>
          		                  </div>  
          		                  <div class="clearfix"></div>
          		                </div>
          		
          		                  <div class="widget-content">
          		
          		                    <table class="table table-striped table-bordered table-hover">
          		                    	
          		                    	    <thead>
          		                    	    <tr>
          		                    	    <?
          		                    	    if(is_array($library_fields))
          		                    	    {
          		                    	       foreach($library_fields as $field)
          		                    		    {
          		                    		    	if($field['show_by_default']=='No')
          		                    		    		continue;
          		                    		    ?>
          		                    		   <th>
          		                    		   		<?=$field['var_name']?>
          		                    		   	</th>
          		                    		   <? }
          		                    	   } ?>
          		                    		
          		                    		<th class="rowHeader">Status</a></th>
          		                    		<th class="rowHeader">Modified on</a></th>
          		                    		<th class="rowHeader">Modified by</a></th>
          		                    	    <th width="30px" align="center">Actions</th>
          		                    	    </tr>
          		                    	    </thead>
          		                    	    <tbody>
          		                    	    <?                
          		                    	        if(is_array($plans_list) and count($plans_list)>0)
          		                    	        {
          		                    	            foreach($plans_list as $k => $row)
          		                    	            {
          		                    	            	$item = $row['info'];
          		                    	            	//die('<tr>'..'</tr>');
          		                    	        ?>
          		                    	            <tr id="item_no_<?=$row['plan_id']?>">
          		                    	           		
          		                    	           		<? 
          		                    	           			if(is_array($library_fields))
          		                    	           			foreach($library_fields as $field)
          		                    	           			{
          		                    	           				if($field['show_by_default']=='No')
          		                    	           					continue;
          		                    	           			
          		                    	           				if(!isset($item[$field['var_name']]))
          		                    	           				{
          		                    	           					echo '<td></td>';
          		                    	           				}else {
          		                    	           		?>
          		                    	           				<td>
          		                    	           				<? if(!is_array($item[$field['var_name']])){
          		                    	           						
          		                    	           						if($field['var_type']=='F')
          		                    	           						{
          		                    	           							?>
          		                    	           							<a target="_blank" href="/user_data/<?=$item[$field['var_name']]?>"><?=$item[$field['var_name']]?></a>
          		                    	           							<?
          		                    	           						}
          		                    	           						else {
          		                    	           							echo makelink(opt2value($item[$field['var_name']]));
          		                    	           						}
          		                    	           					}
          		                    	           					else {
          		                    	           						echo array_recursive_value($item[$field['var_name']]);
          		                    	           					}
          		                    	           				 ?>
          		                    	           				
          		                    	           				<?//  echo hl($item[$field['id']], arrya($search));?></td>
          		                    	           		<?
          		                    	           				}
          		                    	           			}
          		                    	           		?>
          		                    	           		
          		                    					<td align="left" valign="top" class="rowDark"><?=isset($row['fstatus'])?$row['fstatus']:$row['status']?></td>
          		                    					<td align="left" valign="top" class="rowDark"><?=isset($row['fmodified_on'])?$row['fmodified_on']:$row['modified_on']?></td>
          		                    					<td align="left" valign="top" class="rowDark"><?=isset($row['fmodified_by'])?$row['fmodified_by']:$row['modified_by']?></td>
          		                    	            	<td>
          		                    	            		<a style="text-decoration:none" class="btn btn-xs btn-warning" href="/admin/plans/edit/<?=$row['plan_id']?>/">View </a>
          		                    	            	</td>
          		                    	            	<!--<td align="center" valign="top" class="rowDark">
          		                    	            		<a style="text-decoration:none" class="btn btn-xs btn-warning" href="/admin/plans/edit/<?=$row['plan_id']?>/"><i class="icon-pencil"></i> </a>
          		                    	            		<a style="text-decoration:none" class="btn btn-xs btn-danger" href="javascript:confirm_delete('/admin/plans/do_delete/<?=$row['plan_id']?>/');"><i class="icon-remove"></i></a>
          		                    	            	</td>-->
          		                    	            </tr>
          		                    	        <? 	
          		                    	            }
          		                    	        }	
          		                    	        else
          		                    	        {
          		                    	        ?>
          		                    	            <tr>
          		                    	                <td colspan="7" align="center">
          		                    	                    <div class='b1 txt padd5'><strong>No items found!</strong></div>
          		                    	                </td>
          		                    	            </tr>
          		                    	        <?
          		                    	        }	
          		                    	        ?>
          		                    	        </tbody>
          		                    	    
          		                    </table>
          								<!--
          		                    <div class="widget-foot">
          		
          		                      
          		                        <ul class="pagination pull-right">
          		                          <li><a href="#">Prev</a></li>
          		                          <li><a href="#">1</a></li>
          		                          <li><a href="#">2</a></li>
          		                          <li><a href="#">3</a></li>
          		                          <li><a href="#">4</a></li>
          		                          <li><a href="#">Next</a></li>
          		                        </ul>
          		                      
          		                      <div class="clearfix"></div> 
          		
          		                    </div>
          		                    -->
          		
          		                  </div>
          		                </div>
          	</div>
          </div>
			<!-- /table-->

        </div>
		  </div>

		<!-- Matter ends -->

    </div>

   <!-- Mainbar ends -->	    	
   <div class="clearfix"></div>

</div>
<!-- Content ends -->

<!-- Footer starts -->
<?  include(APPPATH."views/footer.inc.php"); ?>
<!-- Footer/Ends-->
</body>
</html>