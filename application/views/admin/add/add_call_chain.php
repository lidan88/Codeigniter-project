<? 
$sort=isset($_REQUEST['sort'])?$_REQUEST['sort']:"First Name";
$ord=isset($_REQUEST['ord'])?$_REQUEST['ord']:"ASC";
$page=isset($_REQUEST['page'])?$_REQUEST['page']:0;
$submit=isset($_REQUEST['Submit'])?$_REQUEST['Submit']:"";
$search=isset($_REQUEST['search'])?$_REQUEST['search']:"";

$href=$_SERVER['PHP_SELF']."?sort=$sort&ord=$ord&search=$search";
$href1=$_SERVER['PHP_SELF']."?";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Panel</title>
<!--HEAD-->
<? include(APPPATH."views/admin/head.inc.php"); ?>
<!-- /HEAD -->
<script> 
			
function form_validation(thisform) {
if (thisform.name.value == '') {
	alert ('Name is required');
	thisform.name.focus();
	return false;
	}
	

return true; } 
</script>

 <style>
  #droppable { width: 100%; min-height: 550px; padding: 0.5em; float: left; }
 ul.droppable{
 	list-style-type: none;
 	padding: 0px;
 	/*min-height:10px;*/
 }
 
 ul.droppable > li {
 	padding: 0px;
 	padding-left: 15px;
 	margin-top: 10px;
 	/*min-height: 35px;*/
 	/*overflow: auto;*/
 	cursor: move;
 	font-size: 14px;
 }
 
 ul.droppable > li.empty{
 	height: 5px;
 	margin-top:0px;
 	/*border-top: 2px solid #333;*/
 }
 
 ul.droppable > li.empty.li_hover{
 	/*min-height: 0;*/
 	margin-top:0px;
 	border-top: 1px dotted #ccc;
 	padding: 0px;
 	/*border-left: 1px solid #333;
 	font-weight: bold;
 	color: blue;*/
 }
 
 .li_hover{
 	/*border: 1px solid #efefef;*/
 	border-left: 1px solid #333;
 	font-weight: bold;
 	color: blue;
 }
 
 .employee{
 	cursor: move;
 }
 
 .custom-menu {
     z-index:1000;
     position: absolute;
     background-color:#fff;
     
     border: 1px solid #ccc;
     padding: 5px 10px 5px 10px;
     cursor: pointer;
 }
 
/*
li.ui-state-highlight2{
	height: 3px;
	background-color: grey;
}
 
#droppable > ul > li > ul > li.ui-state-highlight2{
 	height: 3px;
 	background-color: red;
 }

#droppable > ul > li > ul > li > ul > li.ui-state-highlight2{
 	height: 3px;
 	background-color: green;
 }

#droppable > ul > li > ul > li > ul > li > ul > li.ui-state-highlight2{
 	height: 3px;
 	background-color: blue;
 }
 
 #droppable > ul > li > ul > li > ul > li > ul > li > ul > li.ui-state-highlight2{
  	height: 3px;
  	background-color: brown;
  }
  
 #droppable > ul > li > ul > li > ul > li > ul > li > ul > li > ul > li.ui-state-highlight2{
  	height: 3px;
  	background-color: black;
  }
*/ 
 
 
 </style>


<script type="text/javascript">
var employees = <?=json_encode($employees)?>;
var call_chain = <?=isset($call_chain)?$call_chain['call_chain']:'[]'; ?>;
var tree;

(function($){
    $.fn.disableSelection = function() {
        return this
                 .attr('unselectable', 'on')
                 .css('user-select', 'none')
                 .on('selectstart', false);
    };
})(jQuery);

function render_tree(tre)
{
	//console.log("running render_tree");
	var render_text = '';
	for(x in tre)
	{
		if(tre[x]==null)
			continue;
		//console.log(tre[x]);
		render_text += "<ul class='droppable empty'><li parent='"+tre[x].parentid+"' class='li_object empty'>&nbsp;</li></ul> <ul class='droppable'><li id='li_emp_"+tre[x].id+"' class='li_object' parent='"+tre[x].parentid+"'><span class='icon icon-file'></span> <span data-id='"+tre[x].id+"' class='call_chain_item_span'>"+employees[tre[x].id]['First Name']+" "+employees[tre[x].id]['Last Name']+"</span>";
		
		$('#emp_'+tre[x].id).hide().find('input:checkbox').removeClass('chk');
		
		if(typeof tre[x].children!='undefined')
		{
			render_text += render_tree(tre[x].children);	
		}
		
		//render_text += " <ul class='droppable'><li class='empty'></li></ul> </li></ul>";
		render_text += " </li> </ul>";
		//var $li_item = $("<ul class='droppable'><li id='li_emp_"+employee_id+"' parent='"+parent+"'><span class='icon icon-file'></span> "+employees[employee_id]['First Name']+"</li></ul>");
		
			
	}
	
	return render_text;
}

$(function() {

/*	$(document).bind("click", function(event) {
	    $("div.custom-menu").addClass('hidden');
	});
*/	
	
	$(".custom-menu").on({
	    mouseenter: function () {
	    },
	    mouseleave: function () {
			$(this).addClass('hidden');
			//$(this).css({"background-color":"blue"});
	    },
	    click: function(){
	    	//alert($(this).attr("data"));
	    	$(this).addClass('hidden');
	    	//$('#li_emp_'+$(this).attr("data")).remove();
	    	delete_item($('#li_emp_'+$(this).attr("data")));
	    }
	});
	
	//$('.custom-menu').bind("mouseleave", function(event) {
	 //   $("div.custom-menu").hide();
//	});
	/*$('.custom-menu').mouseleave(function(){
		$("div.custom-menu").hide();
	});*/

  var $table = $("#table");
   
   tree = call_chain;
   call_chain = flatten(call_chain);
   // temp: change
   //tree = unflatten( call_chain );
   var rtree = render_tree(tree);
   $("#emp_0").append(rtree);
   
   $("tr",$table).draggable({
      		//revert:true,
      		revert:"invalid",
      		containment:"document",
      		//helper:"clone",
      		helper:function(){ return '<span class="icon icon-th-list"></span>'; },
      		cursor:"move"//,
      		//handle:".handle",
      		//snap: true
      });
   
   // $( "#draggable" ).draggable();
	setTimeout("apply_initial_droppable();", 200);
    
    $("#trash").droppable({
    	accept: "#droppable ul > li",
    	drop: function(event,ui){
    		delete_item(ui.draggable); //$(this),
    	}
    });
    
   /*$(".droppable").sortable({
		items: "li:not(.disabled)",
       	connectWith: ".droppable",
       	placeholder: "ui-state-highlight2"
   });*/
    
});

function append_item($source,$item)
{
	$item.fadeOut(function(){
		var employee_id = $item.attr("id").replace('li_emp_','').replace('emp_','');
		
		var parent=0;
		if($source.attr('id'))
			parent = $source.attr('id').replace("li_emp_",'').replace('emp_','');
		
		add_to_call_chain(employee_id,parent);
		
		//console.log("parent "+parent);
		var $li_item = $("<ul class='droppable empty'><li parent='"+parent+"' class='li_object empty'>&nbsp;</li></ul> <ul class='droppable'><li id='li_emp_"+employee_id+"' class='li_object' parent='"+parent+"'><span class='icon icon-file'></span> <span data-id='"+employee_id+"' class='call_chain_item_span'>"+employees[employee_id]['First Name']+" "+employees[employee_id]['Last Name']+"</span> </li></ul>");
		$source.append($li_item);
		
		apply_droppable($li_item.find("li"));
				
		/*$(".droppable").sortable({
			items: "li:not(.disabled)",
		   	connectWith: ".droppable",
		    placeholder: "ui-state-highlight2"
		});*/
		       
	}).find('input:checkbox').removeClass('chk');
}

function move_item($destination,$source)
{
	$destination.parent().addClass("aboutToMove");
	
	/*if($source.parent().next().find("li.empty:not(.aboutToMove)").size())
	{
		console.log("Next to Source found empty and is being deleted");
		$source.parent().next().remove();
	}
	
	console.log("Prev Size: "+$source.parent().prev().find("li:first.empty:not(.aboutToMove)").size());
	
	if($source.parent().prev().find("li.empty:first:not(.aboutToMove)").size())
	{
		console.log("Prev to Source found empty and is being deleted");
		$source.parent().prev().remove();
	}*/
	
	//console.log($destination.attr("class"));
	
	//return;
	
	
	/*if($source.parent().next().first().is(".empty:not(.aboutToMove)"))  // .find("li.empty:first:not(.aboutToMove)").size())
	{
		console.log("Next to Source found empty and is being deleted");
		$source.parent().next().remove();
	}
	
	*/
	
	if($source.parent().next().is(".empty:not(.aboutToMove)"))
	{
		console.log("Next to Source found empty and is being deleted");
		$source.parent().next().remove();
	}
	
	if($source.parent().prev().is(".empty:not(.aboutToMove)"))
	{
		console.log("Prev to Source found empty and is being deleted");
		$source.parent().prev().remove();
	}
	
	//console.log($source.parent().prev().first().hasClass("empty"));
	//return;
	
	$destination.parent().removeClass("aboutToMove");
	
	if($destination.hasClass("empty"))
	{
		console.log("destination has empty class");
		/*$('.droppable li.empty').each(function() {
		    while($(this).prop('tagName') == $(this).next().prop('tagName'))
		        $(this).next().parent().remove();
		});*/
		$source.parent().addClass("ul_remove");
		$source.css("left","0px").css("top","0px").appendTo($destination.parent());
		$(".ul_remove").remove();
		var parent_id=$destination.parent().find("li.empty").attr("parent");
		$destination.parent().find("li.empty").remove();
		$source.attr("parent",parent_id);
		
		//$destination.removeClass("empty");
		
		$source.parent().before("<ul class='droppable empty'><li class='li_object empty'>&nbsp;</li></ul>");
		$source.parent().after("<ul class='droppable empty'><li class='li_object empty'>&nbsp;</li></ul>");
		
	}
	else {
		console.log("destination is not empty");
	
		//console.log($source);
		//console.log($destination);
		
		$source.css("left","0px").css("top","0px").parent().appendTo($destination);
		
		$destination.removeClass("empty");
		$destination.parent().removeClass("empty");
		
		var parent_id=0;
		if($destination.attr('id'))
		{
			parent_id = $destination.attr('id').replace("li_emp_",'').replace('emp_','');
			$source.attr("parent",parent_id);
		}
		
		$source.parent().before("<ul class='droppable empty'><li class='li_object empty'>&nbsp;</li></ul>");
	}
	
	apply_droppable($(".droppable li.empty"));
}

function apply_initial_droppable()
{
	apply_droppable($(".droppable li"));
	//$(".droppable li").css("background-color", "red");
}

function apply_droppable($selector)
{
	console.log("apply_droppable on -> "+$selector.selector);
	$selector.draggable({
	   		//connectToSortable: ".droppable",
	   		revert:"invalid",
	   		//scroll: false,
	   		containment:"document",
	   		helper:function(){ return '<span class="icon icon-pushpin"></span>'; },//"original", //original
	   		cursor:"move",
	   		start:function() {
	   			//$(".empty").css("border","solid 3px red").css("padding-top","3px");
	   			//$("BODY").css("overflow","none");
	   			//$(this).css("z-index","100000000000000000").css("display","block");
	   		},
	   		stop:function() {
	   			//$(".empty").css("border","none").css("padding","0px");
	   			
	   			$(".li_hover").removeClass("li_hover");
	   		}
	   		//handle:".handle",
	   		//snap: true
	   });    
	
	$selector.droppable({
		accept: function($source) {
		    //dropElem was the dropped element, return true or false to accept/refuse it
			if($source.hasClass("employee") && !$(this).hasClass("empty"))
				return true;
			else if ($source.hasClass("li_object")) {
				/*console.log("*"+$source.find("ul").size());
				if($source.find("ul").size()>0)
					return false;
				else {
					return true
				}*/
				return true;
			}
			else {
				return false;
			}
		},
		//accept: ".employee,.li_object",
			hoverClass: "li_hover",
			greedy: true,
	  	drop: function( event, ui ) {
	    
	    	//console.log(ui);
	    	
	    	if($(ui.draggable).hasClass("employee"))
	    	{
		    	var searchIDs = $("input:checkbox:checked.chk").map(function(){
		    	  return $(this).val();
		    	}).get();
		    	
		    	searchIDs = _.compact(searchIDs);
		    	
		    	if(searchIDs.length>0)
		    	{
		    		for(x in searchIDs)
		    		{
		    			//console.log("Appending item "+searchIDs[x]);
		    			append_item($(this),$('#emp_'+searchIDs[x]));
		    		}
		    		
		    		$("[type='checkbox']").removeAttr("checked");
		    		$("[type='checkbox']").prop('checked', false);
		    		
		    	}
		    	else {
		    		//console.log("Appending only "+$(ui.draggable).attr('id'));
					append_item($(this),ui.draggable);
		    	}
	    	
	    	}
	    	else {
	    		//This is the li item being sorted.
	    		move_item($(this),ui.draggable);
	    	}
	   
	  }
	});


	$selector.disableSelection();
	//TBD
	$selector.find('.call_chain_item_span').bind("contextmenu", function(event) {
	  event.preventDefault();
	  
	  var employee_id = $(this).attr('data-id');
	 // console.log("binding -> "+employee_id);
	  
	  $(".custom-menu")
	  	.attr('data',employee_id)
	  	.removeClass('hidden')
	    .css({top: event.pageY-0 + "px", left: event.pageX-0 + "px"});
	
	
	});
	
}  
  

function delete_call_chain(employee_id,tre)
{
	console.log("employee to delete = "+employee_id);
	for(x in tre)
	{
		console.log("going through "+tre[x].id);
		
		var needs_deleting=false;
		if(tre[x].id==employee_id)
			needs_deleting=true;
		
		if(tre[x].parentid==employee_id)
			needs_deleting=true;
		
		if(needs_deleting)
		{
			$('#emp_'+tre[x].id).fadeIn().find('input:checkbox').addClass('chk');
				
			delete tre[x];
		}
		
		if(typeof tre[x]!='undefined' && tre[x]!=null)
		{
			if(typeof tre[x].children!='undefined')
			{
				delete_call_chain(employee_id,tre[x].children);
			}	
		}
			//break;
	}
	
	return tre;
}  
 
function delete_item($item)
{
	$item.fadeOut(200, function() {
		//console.log(); 
		var employee_id = $(this).attr('id').replace('li_emp_','').replace('emp_id',''); 
		var parent_id = $(this).attr('parent');
		
		//console.log(parent_id+" "+employee_id+"*");
		//$('#emp_'+employee_id).fadeIn().find('input:checkbox').addClass('chk');
		
		//console.log(call_chain);
		call_chain = delete_call_chain(employee_id,call_chain);
		call_chain = _.compact(call_chain);
		//console.log(call_chain);
		
		//console.log("after cleaning up");
		//console.log(call_chain);
		$(this).remove();
	});
	
} 

function add_to_call_chain(employee_id,parent)
{
	call_chain.push({'id':employee_id,'parentid':parent});
}


var unflatten = function( array, parent, tree ){

    tree = typeof tree !== 'undefined' ? tree : [];
    parent = typeof parent !== 'undefined' ? parent : { id: 0 };

    var children = _.filter( array, function(child){ return child.parentid == parent.id; });

    if( !_.isEmpty( children )  ){
        if( parent.id == 0 ){
           tree = children;   
        }else{
           parent['children'] = children
        }
        _.each( children, function( child ){ unflatten( array, child ) } );                    
    }

    return tree;
};

var flatten = function(array,li2){
	list = typeof li2 !== 'undefined' ? li2 : [];
	
	for(x in array)
	{
		list.push({'id':array[x].id,'parentid':array[x].parentid});
		
		if(typeof array[x].children != 'undefined')
		{
			var temp = flatten(array[x].children,list);
			list.concat(temp);
		}
	}
	return list; 
};

/*var flatten_new = function(array,li2,parent){
	list = typeof li2 !== 'undefined' ? li2 : [];
	parent = typeof parent !== 'undefined' ? parent : 0;
	
	for(x in array)
	{
		
		if(typeof array[x].id != 'undefined')
		{
			if(array[x].id!="" && array[x].nodeName=="LI")
			{
				list.push({'id':array[x].id,'parentid':parent});
				parent = array[x].id;
			}
		}
		else {
			//parent=0;
		}
		
			//list.push({'id':array[x].id,'parentid':array[x].parentid});
		
		if(typeof array[x].children != 'undefined' && array[x].nodeName=="UL")
		{
			var temp = flatten_new(array[x].children,list,array[x].id);
			list.concat(temp);
		}
	}
	return list; 
};
//	var ar = $("#droppable li").toArray();var l = flatten_new(ar);	console.log(l);

*/

function save_items(save_type)
{
	save_type = typeof save_type == 'undefined'? 'save':save_type;

	var items_to_save=[];
	$("#droppable li:not(.empty)").each(function(index,itm){
		
		var emp_id = itm.id.replace('li_emp_','').replace('emp_id','').replace('emp_','');
		var parent_id = $(itm).parent().parent().attr("id").replace('li_emp_','').replace('emp_','');
		if(emp_id>0)
			items_to_save.push({'id':emp_id,'parentid':parent_id});
		//console.log("reading-> "+itm.id+ " -parent:  "+$(itm).parent().parent().attr("id"));
	});	

	var temp= JSON.stringify(unflatten( items_to_save ));
//	var temp= JSON.stringify(unflatten( call_chain ));
//	var temp = JSON.stringify(call_chain);
	$.post("/admin/call_chain/do_add/",{"call_chain_id":$("#call_chain_id").val(),
										"name":$("#name").val(),
										"save_type":save_type,
										"description":$("#description").val(),
										"call_chain":temp},function(ret){
		
		//console.log(ret);
		top.location="/admin/call_chain/main/";
	});
	

	/*tree = unflatten( call_chain );
	console.log(tree);
	
	for(x in tree)
	{
		console.log(typeof tree[x]);
		console.log(tree[x].id+"*");
		
		if(typeof tree[x].children !='undefined')
		{
			console.log("has_children");
		}
	}
	*/
}

function do_search(search) {
	$.post("/admin/call_chain/add/?output=ajax&search="+search,{},function(ret){
		//var test = ret;
		//var content = test.substring(0, test.indexOf('<div id="pagination">'));
		//var pagination = test.substring(test.indexOf('<div id="pagination">'), test.length);
		$('#table').find('tbody').html(ret);
		
		var $table = $("#table");
		 
		 $("tr",$table).draggable({
		    		revert:"invalid",
		    		containment:"document",
		    		helper:function(){ return '<span class="icon icon-th-list"></span>'; },
		    		cursor:"move",
		    		handle:".handle",
		    		snap: true
		    });
		//$('#pagination').html(pagination);
	});
}

function select_all_boxes(is_checked)
{
	if(is_checked)
	{
		$("[type='checkbox'].chk").attr("checked","checked");
		$("[type='checkbox'].chk").prop('checked', true);
	}
	else {
		$("[type='checkbox'].chk").removeAttr("checked");
		$("[type='checkbox'].chk").prop('checked', false);
		
	}
}

</script>

</head>
<body>
<!--HEADER-->
	<? include(APPPATH."views/admin/header.inc.php"); ?>
<!-- /HEADER-->

<!-- /container -->
<div class="content">

	<!-- Sidebar -->
	<? include(APPPATH."views/admin/sidebar.inc.php"); ?>
    <!-- Sidebar ends -->

  	<!-- Main bar -->
  	<div class="mainbar">

      <!-- Page heading -->
      <div class="page-head">
        <!-- Breadcrumb -->
        <div class="bread-crumb pull-left">
          <a href="/super_admin/"><i class="icon-home"></i> Home</a> 
          <!-- Divider -->
          <span class="divider">/</span> 
          <a href="/admin/call_chain/main/"> Call Chain </a>
          <span class="divider">/</span> 
          <a href="#" class="bread-current">Add</a>
        </div>

        <div class="clearfix"></div>

      </div>
      <!-- Page heading ends -->

	    <!-- Matter -->
	    <div class="matter">
	        <div class="container">
	
					            
	          <form class='form-horizontal' name='frm_add_call_chain' method='post' action='/admin/call_chain/do_add/' onSubmit='return form_validation(this);' enctype='multipart/form-data'>
	          	<input type="hidden" name="call_chain_id" id="call_chain_id" value="<?=$call_chain['call_chain_id']?>" />
	          	<div class="row">
	          	
	          	            <div class="col-md-12">
	          	
	          	
	          	              <div class="widget wgreen">
	          	                
	          	                <div class="widget-head">
	          	                  <div class="pull-left">Add New Call Chain</div>
	          	                  <div class="pull-left" style="margin-left: 20px;">
	          	                  	<input name='save' id='Submit' class="btn btn-danger" type='button' onclick="save_items()" value='Save' />
	          	                  	<? if($call_chain['call_chain_id']){ ?>
	          	                  		<input name='save' id='btnCopy' class="btn btn-primary" type='button' onclick="save_items('copy')"  value='Copy' />
	          	                    <? } ?>
	          	                  </div>
	          	                  <div class="widget-icons pull-right">
	          	                  	<form method="get" class="form-inline" action="?">
	          	                  		<input type="text" class="form-control" style="width: 300px;" name="search" value="" onkeyup="do_search(this.value)" placeholder="Search" />
	          	                  	</form>
	          	                    <!--<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
	          	                    <a href="#" class="wclose"><i class="icon-remove"></i></a>-->
	          	                  </div>
	          	                  <div class="clearfix"></div>
	          	                </div>
	          	
	          	                <div class="widget-content">
	          	                  <div class="padd">
	          	
	          	                    <!-- Form starts.  -->
	          	                     <table class="table table-bordered table-striped table-hover content" style="width:100%;" cellpadding="0" cellspacing="0">
	          	                     <input type='hidden' id='chaabee_post' name='chaabee_post' value=''>
	          	                     <input type='hidden' id='call_chain_id' name='call_chain_id' value='<?=$call_chain['call_chain_id']?>'>
	          	                     <tr>
										<td width='35%' class='capitalize' valign='top' align='left'>Name:</td>
										<td width='65%' class='capitalize'><input  class='form-control ' type='text' size=40 name='name' id="name"  value='<?=$call_chain['name']?>'  /></td></tr><tr>
										<td width='35%' class='capitalize' valign='top' align='left'>Description:</td>
										<td width='65%' class='capitalize'><textarea  class='form-control'  rows=5 cols=35 name='description' id='description'><?=$call_chain['description']?></textarea></td></tr>
										
										</table>
										
	          	                     
	          	                     
	          	                  </div>
	          	                </div>
	          	                  <div class="widget-foot">
	          	                    <!-- Footer goes here -->
	          	                    
	          	                    <div class="row">
	          	                    	<div class="col-md-4">
	          	                    		<!--<div id="draggable" class="ui-widget-content">
	          	                    		  <p>Drag me to my target</p>
	          	                    		</div>
	          	                    		 
	          	                    		<div id="droppable" class="ui-widget-header">
	          	                    		  <p>Drop here</p>
	          	                    		</div>-->
	          	                    		<div id="trash" class="well" style="color: brown;">
	          	                    			<span class="icon-trash"></span> Trash - Drop items from below to remove 
	          	                    		</div>
	          	                    		
	          	                    		
	          	                    		<div id="droppable" class="well">
	          	                    			<ul class="droppable">
		          	                    			<li id="emp_0" parent="0" class="disabled">
		          	                    				<span class="icon-folder-close"></span> Start
		          	                    			</li>
		          	                    		</ul>
	          	                    		</div>
	          	                    		
	          	                    		
	          	                   		</div>
	          	                    	<div class="col-md-8">
	          	                    		<div style="height: 600px; overflow-x:hidden;">
	          	                    		<table id="table" class="table">
	          	                    			<thead>
	          	                    				<tr>
	          	                    					<th>ID &nbsp;<input type="checkbox" value="" onclick="select_all_boxes(this.checked,this)" /></th>
	          	                    					<th><?                 
	          	                    					    if ($sort=='First Name') {
	          	                    					    	if ($ord=='ASC') {
	          	                    					    		echo  '<a href="'.$href1.'&sort=First Name&ord=DESC&Submit=Search">';
	          	                    					    	} else {
	          	                    					    		echo  '<a href="'.$href1.'&sort=First Name&ord=ASC&Submit=Search">';
	          	                    					    	}
	          	                    					    } else {
	          	                    					    	echo  '<a href="'.$href1.'&sort=First Name&ord=ASC&Submit=Search">';
	          	                    					    }
	          	                    					    ?>
	          	                    					First Name</th>
	          	                    					<th><?                 
	          	                    					    if ($sort=='Last Name') {
	          	                    					    	if ($ord=='ASC') {
	          	                    					    		echo  '<a href="'.$href1.'&sort=Last Name&ord=DESC&Submit=Search">';
	          	                    					    	} else {
	          	                    					    		echo  '<a href="'.$href1.'&sort=Last Name&ord=ASC&Submit=Search">';
	          	                    					    	}
	          	                    					    } else {
	          	                    					    	echo  '<a href="'.$href1.'&sort=Last Name&ord=ASC&Submit=Search">';
	          	                    					    }
	          	                    					    ?>
	          	                    					Last Name</th>
	          	                    					<th><?                 
	          	                    					    if ($sort=='Department') {
	          	                    					    	if ($ord=='ASC') {
	          	                    					    		echo  '<a href="'.$href1.'&sort=Department&ord=DESC&Submit=Search">';
	          	                    					    	} else {
	          	                    					    		echo  '<a href="'.$href1.'&sort=Department&ord=ASC&Submit=Search">';
	          	                    					    	}
	          	                    					    } else {
	          	                    					    	echo  '<a href="'.$href1.'&sort=Department&ord=ASC&Submit=Search">';
	          	                    					    }
	          	                    					    ?>Department</th>
	          	                    					<th><?                 
	          	                    					    if ($sort=='Location') {
	          	                    					    	if ($ord=='ASC') {
	          	                    					    		echo  '<a href="'.$href1.'&sort=Location&ord=DESC&Submit=Search">';
	          	                    					    	} else {
	          	                    					    		echo  '<a href="'.$href1.'&sort=Location&ord=ASC&Submit=Search">';
	          	                    					    	}
	          	                    					    } else {
	          	                    					    	echo  '<a href="'.$href1.'&sort=Location&ord=ASC&Submit=Search">';
	          	                    					    }
	          	                    					    ?>Location</th><th>Actions</th>
	          	                    				</tr>
	          	                    			</thead>
	          	                    			<tbody>
	          	                    			<?
	          	                    				foreach($employees as $employee_id => $employee)
	          	                    				{
	          	                    					?>
	          	                    					<tr id="emp_<?=$employee_id?>" class="employee">
	          	                    						<td><span style="cursor: move;" class="icon icon-th-list handle"></span> &nbsp;<input type="checkbox" class="chk" name="employees[<?=$employee_id?>]" value="<?=$employee_id?>" /></td>
	          	                    						<td><?=isset($employee['First Name'])?$employee['First Name']:''?></td>
	          	                    						<td><?=isset($employee['Last Name'])?$employee['Last Name']:''?></td>
	          	                    						<td><?=isset($employee['Department'])?opt2value($employee['Department']):''?></td>
	          	                    						<td><?=isset($employee['Location'])?opt2value($employee['Location']):''?></td>
	          	                    						<td><a style="text-decoration:none"  target="_blank"  class="btn btn-xs btn-warning" href="/admin/library_items/edit_user_data/<?=$employee_id?>/"><i class="icon-pencil"></i> </a></td>
	          	                    					</tr>
	          	                    					<?
	          	                    				}
	          	                    			?>
	          	                    			</tbody>
	          	                    		</table>
	          	                    		</div>
	          	                    	</div>
	          	                    </div>
	          	                    
	          	                  </div>
	          	              </div>  
	          	
	          	            </div>
	          	
	          	          </div>
	          	          
	          	          </form>
	         
	
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

<div class='custom-menu hidden' data="">
	<span>Remove</span>
</div>

</body>
</html>