(function($){
    $.fn.disableSelection = function() {
        return this
                 .attr('unselectable', 'on')
                 .css('user-select', 'none')
                 .on('selectstart', false);
    };
})(jQuery);

$(document).ready(function(){
	var dp = $('.datepicker').datepicker().on('changeDate', function(ev) {
	  dp.datepicker('hide');
	});
	$('.timepicker').timepicker();
	$(".chosen-select").chosen();
	
	$("BODY").disableSelection();
	
	$("BODY").on("contextmenu","span.title", function(event) {
		event.preventDefault();
		current_group_id=$(this).parent().attr('id');
		var current_parent_id = $(this).parent().attr('parent');
		var show_attr_status = $(this).parent().attr('show_attr');
		
		console.log("current group id "+current_group_id);
		//console.log("current Group being right clicked "+current_group_id);
		current_group_type=$(this).parent().attr('type');
		
		// By default hide the attributes menu items.
		$(".custom-menu").find("[type='show_attr']").addClass("hidden");
		
		if(current_group_type=="item")
		{
			$(".custom-menu").find("[type='edit']").addClass("hidden");
			
			if(current_parent_id!="0")
			{
				if(show_attr_status=="1")
					$(".custom-menu").find("#hide_attr").removeClass("hidden");
				else {
					$(".custom-menu").find("#show_attr").removeClass("hidden");					
				}
			}
		}
		else 
		{
			$(".custom-menu").find("[type='edit']").removeClass("hidden");
		}
		
		$(".custom-menu").removeClass('hidden').css({top: event.pageY-0 + "px", left: event.pageX-0 + "px"});
	});
	
	$(".custom-menu").on({
	    mouseenter: function () {
	    },
	    mouseleave: function () {
			$(this).addClass('hidden');
			//$(this).css({"background-color":"blue"});
	    }/*,
	    click: function(){
	    	//alert($(this).attr("data"));
	    	$(this).addClass('hidden');
	    	//$('#li_emp_'+$(this).attr("data")).remove();
	    	//delete_item($('#li_emp_'+$(this).attr("data")));
	    }*/
	});


});

function show_attr()
{
	$("#"+current_group_id).attr("show_attr","1");
	$(".custom-menu").addClass("hidden");
}

function hide_attr()
{
	$("#"+current_group_id).attr("show_attr","0");
	$(".custom-menu").addClass("hidden");
}

function form_validation(thisform) {

	if (thisform.name.value == '') {
		alert ('Name is required');
		thisform.name.focus();
		return false;
	}
	
	return true; 
}


function render_tree(tre,parent)
{
	parent = typeof parent == 'undefined'?0:parent;
	//console.log("running render_tree");
	var render_text = '';
	for(x in tre)
	{
		if(tre[x]==null)
			continue;
		//console.log(tre[x]);
		if(tre[x].type=="item")
		{
			$("#chk_"+tre[x].id).attr("checked","checked");
			add_column(tre[x].id,parent);
		}
		else {
			add_group(tre[x].id,tre[x].title);
			if(typeof tre[x].children!='undefined')
			{
				render_tree(tre[x].children,tre[x].id);
			}
		}
	}
	
	return render_text;
}



function add_column(column_id,parent)
{
	parent = typeof parent == 'undefined'?0:parent;

		var $li = $("<li class='root' type='item' data='"+column_id+"' parent='0' id='li_"+column_id+"'><span class='title pointer'><label class='pointer'>"+lc[column_id].var_name+"</label></span>  </li>"); //<ul class='layout_sub'></ul> 
		
		$li.draggable({
			revert:"invalid",
			containment:"document",
			helper:function(){ return '<span class="icon icon-pushpin"></span>'; },
			cursor:"move",
			start:function() {
				
				//$("#layout > li").addClass("hidden");
				$( '<li class="empty"></li>' ).insertAfter( "li.root,li.sub" );
				
				$(".empty").droppable({
					accept: "li",
					hoverClass: "li_hover",
					drop: function(event,ui){
						//console.log("dropped");
						
						//########
						var group_to_delete=false;
						if(ui.draggable.parent().parent().attr("type")=="group")
						{
							console.log("group is the parent");
							console.log(ui.draggable.parent().find("li.sub").length);
							if(ui.draggable.parent().find("li.sub").length==1)
							{
								group_to_delete=ui.draggable.attr("parent");
							}
						}
						//###############
						
						if($(this).prev().hasClass("root") || $(this).next().hasClass("root"))
						{
							$(ui.draggable).removeClass("sub").addClass("root").attr("parent","0");
						}
						else {
							//console.log(ui.droppable);
							$(ui.draggable).removeClass("root").addClass("sub");
						}
						
						$(ui.draggable).insertAfter($(this));
						
						if(group_to_delete!=false)
						{
							console.log("deleting "+group_to_delete);
							$("li[data='"+group_to_delete+"']").remove();
						}
						
						var parent_id = ui.draggable.parent().parent().attr("data");
						ui.draggable.attr("parent",parent_id);
	
					}
				});
				
			},
			stop:function() {
				$(".empty").remove();
			}
		});
		
		$li.droppable({
			accept: function($source) {
			    //dropElem was the dropped element, return true or false to accept/refuse it
				if($(this).hasClass("root"))
					return true;
				else {
					return false;
				}
			},
			hoverClass: "li_hover",
			greedy: true,
			drop: function(event,ui){
				//console.log("dropped");
				var source_id = ui.draggable.attr("data");
				var type_of_source=ui.draggable.attr("type");
				
				var destination_id = $(this).attr("data");
				var type_of_destination = $(this).attr("type");
				
				
				//########
				var group_to_delete=false;
				if(ui.draggable.parent().parent().attr("type")=="group")
				{
					if(ui.draggable.parent().find("li.sub").length==1)
					{
						group_to_delete=ui.draggable.attr("parent");
					}
				}
				//###############
				
				if(typeof destination_id != 'undefined')
				{
					destination_id = parseInt(destination_id);
					source_id = parseInt(source_id);
					
					// If type being dragged is an Item and not a group
					if(type_of_source=="group")
					{
						console.log("sorry groups cannot be dropped");
						return;
					}
					
					if(type_of_destination=="item")
					{
						total_groups++;
						
						var group_li = $("<li class='root' type='group' data='g"+total_groups+"' parent='0' id='group_"+destination_id+"_"+source_id+"'><span class='title'><label class='pointer'>Group</label></span> <ul class='layout_sub'></ul> </li>");
						group_li.insertBefore($(this));
						
						ui.draggable.attr("parent","g"+total_groups).removeClass("root").addClass("sub");
						$(this).attr("parent","g"+total_groups).removeClass("root").addClass("sub");
						group_li.find('ul').append($(this));
						group_li.find('ul').append(ui.draggable);
						
					}else {
						//its a group
						var parent_id = $(this).attr("data");
						
						ui.draggable.attr("parent",parent_id).removeClass("root").addClass("sub");
						$(this).find('ul').append(ui.draggable);
						//$(this).find('ul').append('<li data="'+source_id+'" type="item" parent="'+parent_id+'" class="sub">'+lc[source_id].var_name+'</li>');
					}
					
					if(group_to_delete!=false)
					{
						console.log("deleting "+group_to_delete);
						$("li[data='"+group_to_delete+"']").remove();
					}
					
					//console.log(source_id+" - "+destination_id+" - "+type_of_source + " - "+type_of_destination);
				}
				
			}
		});
		
		
	if(parent=="0")
	{
		$("#layout").append($li);
	}
	else {
		$li.attr("parent",parent);
		$li.removeClass("root").addClass("sub");
		$li.attr("data",column_id);
		$li.attr("type", "item");
		$("#"+parent).find('ul').append($li);
		//$("#"+parent).find('ul').append('<li data="'+column_id+'" type="item" parent="'+parent+'" class="sub">'+lc[column_id].var_name+'</li>');
	}
}

function add_group(group_id,title)
{
	$("#layout").append("<li class='root' type='group' data='"+group_id+"' parent='0' id='"+group_id+"'><span class='title'><label>"+title+"</label></span> <ul class='layout_sub'></ul> </li>");
}

function modify_layout(column_id,$obj) {
	if($obj.is(':checked'))
	{
		add_column(column_id);
	}
	else {
		//$("#li_"+column_id).remove();
		$("li[data='"+column_id+"']").remove();
	}
}

function edit_group()
{
	$(".custom-menu").addClass("hidden");
	$("#edit_modal").modal("show");
}

function remove_group()
{
	$(".custom-menu").addClass("hidden");
	
	if(current_group_type=="item")
	{
		var grp_id = current_group_id.replace('li_', '');
		$("#chk_"+grp_id).removeAttr("checked");	
		$("#chk_"+grp_id).prop('checked', false);
	}
	
	$("#"+current_group_id).remove();
}


function save_group_title()
{
	var title = $("#group_title").val();
	$("#edit_modal").modal("hide");
	$("#"+current_group_id).find("label:first").html(title);
	$("#group_title").val("");
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
