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
var ready;
ready = function() {
	$.ajaxSetup({ cache: false });

	$.fn.datepicker.defaults.format = "dd/mm/yyyy";
	$('#date').datepicker({dateFormat: 'dd/mm/yy'});
	$('body').on('hidden.bs.modal', '.modal', function () {
    	$(this).removeData('bs.modal');
	});

	$(function () {
        $('button[type=submit]').click(function (e) {
            window.onbeforeunload = null;
        });
    });

	$(document).ready(function() {
		var anchor = window.location.hash;
		var result = anchor.split('-');
		console.log(result[0]);
		$('[data-toggle="tab"][href="'+result[0]+'"]').tab('show');
		$('#'+result[1]).collapse('show');
	});

	$(document).ready(function() {
		var types = new Bloodhound({
				datumTokenizer: Bloodhound.tokenizers.obj.whitespace('key'),
				queryTokenizer: Bloodhound.tokenizers.whitespace,
				prefetch: '/common/activities/types',
				remote: {
					url: '/common/activities/types/%QUERY',
					wildcard: '%QUERY'
				}
			});
		
		types.initialize();
	
		$('#type_autocomplete').typeahead(null, {
			name: 'type',
			display: 'title',
			valueKey: 'key',
			minLength: 0,
			source: types.ttAdapter()
		}).on('typeahead:selected', function(event, data){    
			$('#type_autocomplete_hidden').val(data.key);      
		});
	});

	$(document).ready(function () {
		$('.collapse').on('shown.bs.collapse', function() {
			$(this).parent()
		 		.find(".fa-chevron-circle-right")
		 		.removeClass("fa-chevron-circle-right")
		 		.addClass("fa-chevron-circle-down");
	    }).on('hidden.bs.collapse', function() {
	    	$(this).parent()
	    		.find(".fa-chevron-circle-down")
	    	 	.removeClass("fa-chevron-circle-down")
	    	 	.addClass("fa-chevron-circle-right");
	    });
	});

	$('body').on('shown.bs.modal', '.modal', function () {
		
        $('button[type=submit]').click(function (e) {
                window.onbeforeunload = null;
        });
    		
    	$("#activities").remoteChained({
		    parents : "#types",
		    url : "/json/forms/activities"
		});

		$("select#be").hide();
		$('select#be').prev('label').hide();

		$("#be").remoteChained({
		    parents : "#activities",
		    url : "/json/forms/be/"+$('#be_user').val()
		});

		$("select#activities").change( function () {
			$('select#be').prev('label').show();
			$('select#be').show();
		});

		$("select#be").change( function () {
			if($(this).children('option').length == 0){
				$(this).prev('label').hide();
				$(this).hide();
			} else {
				$(this).prev('label').show();
				$(this).show();
			}
		});


	});

	$('a.popup-ajax').mouseenter(function() {
		var i = this
		$.ajax({url: $(this).attr('href'), dataType: "html", cache:false, success: function(data){
		$(i).popover({html:true,placement:'left',title:$(i).html(),content:data}).popover('show')
		}})
	});
	
	$('a.popup-ajax').mouseout(function() {
		$('.popover:visible').popover('destroy')
	});

	$(function () {
	  $('[data-toggle="popover"]').popover()
	});

	handleTabLinks();
	
	var rules = {
	    	focusCleanup: false,
			
			wrapper: 'div',
			errorElement: 'span',
			ignore: [],
			
			highlight: function(element) {
				$(element).parents ('.form-group').removeClass ('success').addClass('error');
				$(".tab-content").find("div.tab-pane:hidden:has(div.error)").each( function(){
		            var id = $(this).attr("id");
		            $('.nav-tabs a[href="#'+id+'"]').tab('show');
		        });
			},
			success: function(element) {
				$(element).parents ('.form-group').removeClass ('error').addClass('success');
				$(element).parents ('.form-group:not(:has(.clean))').find ('div:last').before ('<div class="clean"></div>');
			},
			errorPlacement: function(error, element) {
				error.prependTo(element.parent());
			}
	    	
	    };
	
	$('form').validate(rules);

	$('.delete-confirm').click(function(e){
		e.preventDefault();
		var location = $(this).attr('href');
		var text = $(this).data('message');
		bootbox.confirm(text, function(result) {
			if(result){
				window.location.replace(location);
			}
		}); 
	});
	
	if($('#fileupload').length){
		document.getElementById('fileupload').addEventListener('change', handleFileSelect, false);
	}

	$(".input-group.date").datepicker({ autoclose: true, todayHighlight: true });

	//Companies autocomplete
	var companies = new Bloodhound({
		datumTokenizer: function(d) { return Bloodhound.tokenizers.whitespace(d.value); },
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: '/json/companies?q=%QUERY',
		prefetch: '/json/companies/'
	});
	 
	companies.initialize();
	 
	$('#company_autocomplete.typeahead').typeahead(null, {
			valueKey: 'value',
			displayKey: 'name',
			source: companies.ttAdapter(),
			highlight: true,
			templates: {
			suggestion: Handlebars.compile(
				'<p><img class="img-circle" src="/uploads/companies/{{icon}}"> {{name}}</p><i class="fa fa-plus-circle"></i>'
			)
	}
	}).on('typeahead:opened', function (e) {
		if(e.length){
			$("#" + e.target.id).removeClass('selected');
		}
	}).on('typeahead:selected', function (e, datum) {   
     	$('#id_company').val(datum.value);
     	$("#" + e.target.id).addClass('selected');
 	}).on('keyup', function (e) {
 		if (e.keyCode != 13) { 
     		$('#id_company').val('');
     		$("#" + e.target.id).removeClass('selected');
     	}
 	}).on('blur', function(e) {
 		if( $('#id_company').val() == ''){
	    	$("#" + e.target.id).val("");
	    	$("#" + e.target.id).removeClass('selected');
	    }
	});
	
	//SEARCH COMPANIES AUTOCOMPLETE
	$('#company_search').typeahead(null, {
			valueKey: 'value',
			displayKey: 'name',
			source: companies.ttAdapter(),
			highlight: true,
			templates: {
			suggestion: Handlebars.compile(
				'<p><img class="img-circle" src="/uploads/users/{{icon}}"> {{name}}</p>'
			)
		}
	});

	//Users autocomplete
	var users = new Bloodhound({
		datumTokenizer: function(d) { return Bloodhound.tokenizers.whitespace(d.value); },
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: '/json/users?q=%QUERY',
		//prefetch: '/json/users/'
	});
	 
	users.initialize();
	 
	$('#user_autocomplete.typeahead').typeahead(null, {
			valueKey: 'value',
			displayKey: 'name',
			source: users.ttAdapter(),
			highlight: true,
			templates: {
			suggestion: Handlebars.compile(
				'<p><img class="img-circle" src="/uploads/users/{{icon}}"> {{name}}</p><i class="fa fa-plus-circle"></i>'
			)
		}
	}).on('typeahead:opened', function (e) {
		if(e.length){
			$("#" + e.target.id).removeClass('selected');
		}
	}).on('typeahead:selected', function (e, datum) {   
     	$('#id_user').val(datum.value);
     	$("#" + e.target.id).addClass('selected');
 	}).on('keyup', function (e) {
 		if (e.keyCode != 13) { 
     		$('#id_user').val('');
     		$("#" + e.target.id).removeClass('selected');
     	}
 	}).on('blur', function(e) {
 		if( $('#id_user').val() == ''){
	    	$("#" + e.target.id).val("");
	    	$("#" + e.target.id).removeClass('selected');
	    }
	});

	//Users company autocomplete
	var users_company = new Bloodhound({
		datumTokenizer: function(d) { return Bloodhound.tokenizers.whitespace(d.value); },
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {
			url: '/json/users',
			replace: function(url, uriEncodedQuery) {
		    	return url + '/?q='+ uriEncodedQuery + '&company=' + $('#user_company_autocomplete.typeahead').attr('company');
			}
		}
	});
	 
	users_company.initialize();
	 
	$('#user_company_autocomplete.typeahead').typeahead(null, {
			valueKey: 'value',
			displayKey: 'name',
			source: users_company.ttAdapter(),
			highlight: true,
			templates: {
			suggestion: Handlebars.compile(
				'<p><img class="img-circle" src="/uploads/users/{{icon}}"> {{name}}</p><i class="fa fa-plus-circle"></i>'
			)
		}
	}).on('typeahead:opened', function (e) {
		if(e.length){
			$("#" + e.target.id).removeClass('selected');
		}
	}).on('typeahead:selected', function (e, datum) {   
     	$('#id_user').val(datum.value);
     	$("#" + e.target.id).addClass('selected');
 	}).on('keyup', function (e) {
 		if (e.keyCode != 13) { 
     		$('#id_user').val('');
     		$("#" + e.target.id).removeClass('selected');
     	}
 	}).on('blur', function(e) {
 		if( $('#id_user').val() == ''){
	    	$("#" + e.target.id).val("");
	    	$("#" + e.target.id).removeClass('selected');
	    }
	});

	//Users contract autocomplete
	var users_contract = new Bloodhound({
		datumTokenizer: function(d) { return Bloodhound.tokenizers.whitespace(d.value); },
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {
			url: '/json/users/contract/',
			replace: function(url, uriEncodedQuery) {
		    	return url + '/?q='+ uriEncodedQuery + '&contract=' + $('#user_contract_autocomplete.typeahead').attr('contract');
			}
		}
	});
	 
	users_contract.initialize();
	 
	$('#user_contract_autocomplete.typeahead').typeahead(null, {
			valueKey: 'value',
			displayKey: 'name',
			source: users_contract.ttAdapter(),
			highlight: true,
			templates: {
			suggestion: Handlebars.compile(
				'<p><img class="img-circle" src="/uploads/users/{{icon}}"> {{name}}</p><i class="fa fa-plus-circle"></i>'
			)
		}
	}).on('typeahead:opened', function (e) {
		if(e.length){
			$("#" + e.target.id).removeClass('selected');
		}
	}).on('typeahead:selected', function (e, datum) {   
     	$('#id_user').val(datum.value);
     	$("#" + e.target.id).addClass('selected');
 	}).on('keyup', function (e) {
 		if (e.keyCode != 13) { 
     		$('#id_user').val('');
     		$("#" + e.target.id).removeClass('selected');
     	}
 	}).on('blur', function(e) {
 		if( $('#id_user').val() == ''){
	    	$("#" + e.target.id).val("");
	    	$("#" + e.target.id).removeClass('selected');
	    }
	});

	$("#tree").fancytree({
			extensions: ["glyph"],
			glyph: {
				map: {
		          doc: "fa fa-plus-square",
		          docOpen: "fa fa-folder-open",
		          checkbox: "fa fa-square-o",
		          checkboxSelected: "fa fa-check-square-o",
		          checkboxUnknown: "fa fa-minus",
		          error: "fa fa-times",
		          expanderClosed: "",
		          expanderLazy: "",
		          // expanderLazy: "glyphicon glyphicon-expand",
		          expanderOpen: "",
		          // expanderOpen: "glyphicon glyphicon-collapse-down",
		          folder: "fa fa-folder",
		          folderOpen: "fa fa-folder-open",
		          loading: "fa fa-spinner fa-spin"
		          // loading: "icon-spinner icon-spin"
		        }
			},
			click: function(event, data) {
		    	var node = data.node;
		        if(node.data.prev && node.data.prev == 'customer'){
		        	window.location.href = '/admin/contracts/add_role/'+node.data.contract+'/'+node.data.customer;
		        	return false;
		        } else {
		        	
		        	$('.companies_tr').hide();
		        	var target_c = '.c_'+node.data.customer;
		        	var target_b = '.b_'+node.data.customer;
		        	var target_d = '.d_'+node.data.customer;
		        	var target_e = '.e_'+node.data.customer;
		        	var target_f = '.f_'+node.data.customer;
		        	$(target_c).show();
		        	$(target_b).show();
		        	$(target_d).show();
		        	$(target_e).show();
		        	$(target_f).show();
		        	return;
		        }
		  	}
	});

	$('.alert-show').click(function(){
		var text = $(this).data('alert');
		bootbox.alert(text);
	});

	// ajax modal
    $(document).on('click', '[data-toggle="ajaxModal"]', function(e) {
        $('#ajaxModal').remove();
        e.preventDefault();
        var $this = $(this)
          , $remote = $this.data('remote') || $this.attr('href')
          , $modal = $('<div class="modal fade" id="ajaxModal"><div class="modal-body"></div></div>');
        $('body').append($modal);
        $modal.modal();
        $modal.load($remote);
    });
    
    /* validation check for Customer and company in Contarct Module */
    $(document).on('click', '#check_valid',function(){
	    var form_data = $("#wizard").serialize();
	    $.ajax({
	        url: "/common/module/check_validation",
	        type: 'POST',
	        dataType: 'json',
	        data: form_data,
	        success: function(res)
	        {
	        	if(res.status == 0){
	        		$('.val_success').hide();
	        		$('.val_failure').show();
	        		$(".account_id").val(res.status);
	        		$("#create_contract").prop('disabled', false);
	        	} else {
	        		$('.val_failure').hide();
	        		$('.val_success').show();
	        		$(".account_id").val(res.status);
	        		$("#create_contract").prop('disabled', false);
	        	}
	        }
	    });
	return false;
	});
    
};

$(document).ready(ready);
$(document).on('page:load', ready);

function handleFileSelect(evt) {
	var files = evt.target.files; // FileList object

    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {

    	// Only process image files.
    	if (!f.type.match('image.*')) {
    		continue;
    	}
    	var reader = new FileReader();

    	// Closure to capture the file information.
    	reader.onload = (function(theFile) {
    		return function(e) {
    			// Render thumbnail.
    			document.getElementById('upload').innerHTML = ['<img class="thumb" src="', e.target.result,'" title="', escape(theFile.name), '"/>'].join('');
    		};
    	})(f);

    	// Read in the image file as a data URL.
    	reader.readAsDataURL(f);
    }
}

function handleTabLinks() {
	var hash = window.location.href.split("#")[1];
	if (hash !== undefined) {
		var hpieces = hash.split("/");
		for (var i=0;i<hpieces.length;i++) {
			var domelid = hpieces[i];
			var domitem = $('a[href=#' + domelid + '][data-toggle=tab]');
			if (domitem.length > 0) {
				if (i+1 == hpieces.length) {
					// last piece
					setTimeout(function() {
					// Highly unclear why this code needs to be inside a timeout call.
					// Possibly due to the fact that the first ?.tag('show') call needs
					// to have it's animation finishing before the next call is being
					// made.
					domitem.tab('show');
					},
					// This magic timeout is based on trial and error. I bumped it
					// partially to catch the visitor's attention.
					1000);
				} else {
					domitem.tab('show');
				}
			}
		}
	}
}

function bytesToSize(bytes) {
   if(!bytes) return bytes;
   if(isNaN(bytes)) return bytes;
   if(typeof bytes === 'undefined') return bytes;
   var k = 1000;
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes === 0) return '0 Bytes';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(k)),10);
   return (bytes / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i];
}

function toggle_table(element){
	console.log($(element).parent().parent());
	$(element).parent().parent().next('tr').toggle();
	if($(element).find('i.fa-chevron-down').length){
		$(element).find('i.fa-chevron-down').removeClass('fa-chevron-down').addClass('fa-chevron-right');
	} else {
		$(element).find('i.fa-chevron-right').removeClass('fa-chevron-right').addClass('fa-chevron-down');
	}

	return false;
}

function add_owner(){
	if($('.add_owner').css('display') == 'none'){
		$('.add_owner').css('display','block');
	} else {
		var newowner = $('.add_owner').html();
		$('.add_owner input').val();
		$('.add_owner').append(newowner);
	}
	
}

// For thread cancel item
$('body').on('shown.bs.modal', '#cancelThreadItem', function () {
	
	$('#cancelThreadItem button.btn-primary').on('click',function(e){
		var validator = $('form[name=cancelThreadForm]').validate();
	
		$('form[name=cancelThreadForm]').submit(function(e){
		
			if(validator.form()){
			    var data = $('form[name=cancelThreadForm]').serializeArray();
		    	var obj = new Object();
		    	var items = new Object();
		    		
				for (var i = 0, l = data.length; i < l; i++) {
					    items[data[i].name] = data[i].value;
				}
	
				obj = items;
	
				console.log(data);
	
				$.ajax({
					url: "/common/cases/ajax_cancel_thread/",
					data:  obj, 
					dataType: 'json',
					type: 'POST',
					success: function(response) {
						location.reload();
					}
				});
			}
			e.preventDefault();
			$( this ).off( e );
		});
	
		$('form[name=cancelThreadForm]').submit();
	});
});

$('#fast_thread').change(function() {
    var ischecked= $(this).is(':checked');
    if(ischecked){
    	$('#fast_thread_view_container').removeClass('hidden');
    }else{
    	$('#fast_thread_view_container').addClass('hidden');
    	$('#fast_thread_view').val('');
    }
}); 


//save transition
function save_transition(){
	var form = $('form[name=transition_form]')[0];
	formData = new FormData(form);
	$.ajax({
			url: "/admin/setup_activities/save_transition/",
			data: formData,
			contentType: false,
			processData: false,
			cache:false,
			type: 'POST',
			success: function(result) {
				  if (result) {
					  $("#transition_insert_message").html("<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert'>×</a> Transition saved successfully.</div>");
		    	  } else{
		    		  $("#transition_insert_message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>×</a> Failed to save transition changes.</div>");
		    	  }
				}
		});
}

$('.data_effettuazione').datepicker({ autoclose: true, todayHighlight: true });



//setting type and un setting sub type

function setType(){
	type_id = $('#id_trouble_type').val();
	$('#id_touble_sub_type').val('');
	$('#filters').submit();

}




$('#codice_cliente').live('change',function(){


	$('#business_ent').removeClass('hide');
});

$('#codice_contratto').live('change',function(){
	$('#business_ent').addClass('hide');
	$('#FOR_ALL').prop('checked', false);
	$('#FOR_BE').prop('checked', false);
});

$('#be_id').on('change',function(){
	$('#business_ent').addClass('hide');
	$('#FOR_ALL').prop('checked', false);
	$('#FOR_BE').prop('checked', false);
});


	function manage_trouble_thread($type) {
		

		if($type == "trouble") {
			$('#thread_sec').hide();
			$('#trouble_sec').show();
		} else {
			$('#thread_sec').show();
			$('#trouble_sec').hide();
		}
		  
	}
