/**
 * WManager
 *
 * An open source application for business process management
 * and a process automation development framework
 *
 * This content is released under the MIT License (MIT)
 *
 * WManager
 * Copyright (c) 2017 JAMAIN SOCIAL AND SERVICES SRL (http://jamain.co)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package     WManager
 * @author      Eng. Gianluca Pelliccioli and JAMAIN SOCIAL AND SERVICES SRL development team
 * @copyright   Copyright (c) 2017 JAMAIN SOCIAL AND SERVICES SRL (http://jamain.co)
 * @license     http://opensource.org/licenses/MIT      MIT License
 * @link        http://wmanager.org
 * @since       Version 1.0.0
 * @filesource
 */

$( document ).ready(function() {
	
	/* Add new status Process / Activities */
	$('body').on('click', '#add_new_status',function(e){
		var new_row = $("#status_new_list").html();
		var template = Handlebars.compile(new_row);		
		var current_ref = $(this).data('ref');
		if(current_ref == 'other'){
			$('.other_variable tbody').append(new_row);
		}else{
			$('.status_variable tbody').append(new_row);
		}
	});
	
	/* Checkbox checked value for Process / Actvities */
	$('body').on('click', '.process_checked',function(e){
	       var name = $(this).attr('name');
	       var hname = 'hidden_'+name;
	       if($(this).is(':checked')){
	    	 $(this).parents('td').find("[name='" + hname + "']").val('t');
	       } else {
	    	   $(this).parents('td').find("[name='" + hname + "']").val('f');
	       }
	    });
	
	$('body').on('change', '#attachment_form .checkbox .checkbox', function(e){
		var name = $(this).attr('name');
	       var hname = 'hidden_'+name;
	       if($(this).is(':checked')){
	    	   $("[name='" + hname + "']").val('t');
	       } else {
	    	   $("[name='" + hname + "']").val('f');
	       }	       
	});
	
	/* Delete Status Process / Activities */	
	$('body').on('click', '.deleteStatus',function(event){
	     var newstatus = $(this).data("newstatus");
	     var btn = this;
	     if(newstatus == '0'){
	    	 $(btn).closest('tr').remove();
	    	 event.preventDefault();
	     } 
	  })
	
	/* Save variable Process */
	$('body').on('click', '#save_variable',function(){
	    var form_data = $("#other_var").serialize();
	    $.ajax({
	        url: "/admin/setup_processes/add_variable",
	        type: 'POST',
	        data: form_data,
	        success: function(msg)
	        {
	        	 window.location.reload();
	        }
	    });
	return false;
	});
	
	/* Save variable Activities */
	$('body').on('click', '#save_active_variable',function(){
	    var form_data = $("#other_var").serialize();
	    $.ajax({
	        url: "/admin/setup_activities/add_variable",
	        type: 'POST',
	        data: form_data,
	        success: function(msg)
	        {
	        	 window.location.reload();
	        }
	    });
	return false;
	});
	
	/* Update variable Process */
	$('body').on('click', '#update_variable',function(){
	    var form_data = $("#other_var").serialize();
	    $.ajax({
	        url: "/admin/setup_processes/edit_variable",
	        type: 'POST',
	        data: form_data,
	        success: function(msg)
	        {
	        	 window.location.reload();
	        }
	    });
	return false;
	});
	
	/* Update variable Activities */
	$('body').on('click', '#update_active_variable',function(){
	    var form_data = $("#other_var").serialize();
	    $.ajax({
	        url: "/admin/setup_activities/edit_variable",
	        type: 'POST',
	        data: form_data,
	        success: function(msg)
	        {
	        	 window.location.reload();
	        }
	    });
	return false;
	});
	

	
	  /* Add attachments Activities */
	  $('body').on('click', '#add_attachment',function(e){
			var activity_id = $(this).data('ref');
			$.ajax({
		        type: "GET",
		        url: '/admin/setup_form/get_unused_attachment/'+activity_id,
		        dataType: "json",
		        success: function(response) {
		        	if(typeof response.data != 'undefined'){
		        		 var attachments = response.data;
		        		 var raw_template = $('#attachment_new_list').html();
		        		 var template = Handlebars.compile(raw_template);
		        		 var placeHolder = $("#attachment_table tbody");
		        		 var context = {"attachments": attachments, };
		        		 var html = template(context);
		        		 placeHolder.append(html);	        		
		        		
						if(attachments.length <=1){
							$('#add_attachment').removeClass('in');
						}
		        	}
		        }
		      });
		});
	
	 /* Delete attachments Activities */
	  $('body').on('click', '.deleteAttachment',function(e){
		 var newattc = $(this).data("newattc");
	     var btn = this;
	     if(!$('#add_attachment').hasClass('in')){
	    	 $('#add_attachment').addClass('in');
	     }
	     if(newattc == '0'){
	    	 $(btn).closest('tr').remove();
	    	 e.preventDefault();
	     }
	  });
	  
	  $('body').on('click', '#save_attachment',function(e){
		var form_data = $("#attachment_form").serialize();
		$.ajax({
	        url: "/admin/setup_form/save_attachment",
	        type: 'POST',
	        data: form_data,
	        success: function(msg)
	        {
	        	window.location.reload();
	        }
	    });
		return false;
	  });

	  
	  /* Save exit scenario Activities */
		$('body').on('click', '#save_exit_scenario',function(){
		    var form_data = $("#new_scenario").serialize();
		    $.ajax({
		        url: "/admin/setup_activities/add_scenario",
		        type: 'POST',
		        data: form_data,
		        success: function(msg)
		        {
		        	 window.location.reload();
		        }
		    });
		return false;
		});
		
		/* Update exit scenario Activities */
		$('body').on('click', '#update_exit_scenario',function(){
		    var form_data = $("#new_scenario").serialize();
		    $.ajax({
		        url: "/admin/setup_activities/edit_scenario_value",
		        type: 'POST',
		        data: form_data,
		        success: function(msg)
		        {
		        	 window.location.reload();
		        }
		    });
		return false;
		});
		
		/* Ajax ordering for Activities */
		
		function moveUp(item) {
		    var prev = item.prev();
		    if (prev.length == 0)
		        return;
		    prev.css('z-index', 999).css('position','relative').animate({ top: item.height() }, 250);
		    item.css('z-index', 1000).css('position', 'relative').animate({ top: '-' + prev.height() }, 300, function () {
		        prev.css('z-index', '').css('top', '').css('position', '');
		        item.css('z-index', '').css('top', '').css('position', '');
		        item.insertBefore(prev);
		    });
		}
		function moveDown(item) {
		    var next = item.next();
		    if (next.length == 0)
		        return;
		    next.css('z-index', 999).css('position', 'relative').animate({ top: '-' + item.height() }, 250);
		    item.css('z-index', 1000).css('position', 'relative').animate({ top: next.height() }, 300, function () {
		        next.css('z-index', '').css('top', '').css('position', '');
		        item.css('z-index', '').css('top', '').css('position', '');
		        item.insertAfter(next);
		    });
		}
		
		$(".FieldContainer tr:first-child td a.ascending:first-child").hide();
		$(".FieldContainer tr:last-child td a.descending:last-child").hide();
		$(".FieldContainer").sortable({ items: ".OrderingField", distance: 10 });
		$('.sorting').click(function() { 
		    var btn = $(this);
		    var val = btn.data('value');
		    if (val == 'up'){
		    	var current_id = btn.parents('.OrderingField').attr('id');
		    	var prev_id = btn.parents('.OrderingField').prev().attr('id');
				$('#'+prev_id+' .sorting').attr('data-index',parseInt($('#'+prev_id+' .fa-sort-asc').attr('data-index'))+1);
				$('#'+current_id+' .sorting').attr('data-index',parseInt($('#'+current_id+' .fa-sort-asc').attr('data-index'))-1);
		    	moveUp(btn.parents('.OrderingField'));
		    	var cid = current_id.replace ( /[^\d.]/g, '' );
		    	var pid = prev_id.replace ( /[^\d.]/g, '' );
		    	var data = {};
		    	data[cid] = $('#'+current_id+' .fa-sort-asc').attr('data-index');
		    	data[pid] = $('#'+prev_id+' .fa-sort-asc').attr('data-index');
		    	setTimeout(function(){
		    		$(".FieldContainer tr td a.descending").show();
		    		$(".FieldContainer tr td a.ascending").show();
		    		$(".FieldContainer tr:first-child td a.ascending").hide();
		    		$(".FieldContainer tr:last-child td a.descending").hide();
		    	}, 400);
		    	console.log(data);
		    		
		    } else {
		    	var current_id = btn.parents('.OrderingField').attr('id');
		    	var next_id = btn.parents('.OrderingField').next().attr('id');
		    	$('#'+next_id+' .sorting').attr('data-index',parseInt($('#'+next_id+' .fa-sort-asc').attr('data-index'))-1);
				$('#'+current_id+' .sorting').attr('data-index',parseInt($('#'+current_id+' .fa-sort-asc').attr('data-index'))+1);
		    	moveDown(btn.parents('.OrderingField'));
		    	var cid = current_id.replace ( /[^\d.]/g, '' );
		    	var pid = next_id.replace ( /[^\d.]/g, '' );
		    	var data = {};
		    	data[cid] = $('#'+current_id+' .fa-sort-asc').attr('data-index');
		    	data[pid] = $('#'+next_id+' .fa-sort-asc').attr('data-index');
		    	setTimeout(function(){
		    		$(".FieldContainer tr td a.ascending").show();
		    		$(".FieldContainer tr td a.descending").show();
		    		$(".FieldContainer tr:last-child td a.descending").hide();
		    		$(".FieldContainer tr:first-child td a.ascending").hide();
		    	}, 400);
		    	console.log(data);
		    }
		    
		    $.ajax({
		        url: "/admin/setup_activities/update_ordering",
		        type: 'POST',
		        data: {'data':data},
		        success: function(msg)
		        {
		        	return false;
		        }
		    }); 
		    
		});
		
		
		// adding date picker to filter_dalla_data and filter_alla_data
		$('#filter_dalla_data').datepicker({ autoclose: true, todayHighlight: true});
		$("#filter_alla_data").datepicker({ autoclose: true, todayHighlight: true});
		$('#filter_quntity_dalla_data').datepicker({ autoclose: true, todayHighlight: true});
		$("#filter_quntity_alla_data").datepicker({ autoclose: true, todayHighlight: true});
		$('#data_effettuazione').datepicker({ autoclose: true, todayHighlight: true});
});