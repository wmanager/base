$( document ).ready(function() {	
	/* Add new related process */
	$('body').on('click', '#add_new_related_process',function() { 
		var new_row = $("#trouble_related_process_new").html();
		var template = Handlebars.compile(new_row);	
		var current_ref = $(this).data('ref');
		if(current_ref == 'related_process_new'){
			var uniq_id = uniqId();
			var html_content = new_row.replace("request_key", "request_key_"+uniq_id);
			var html_content_final = html_content.replace("id_related_process_", "id_related_process_"+uniq_id);
			$('#related_process_new tbody').append(html_content_final);
		}
	});
	/* unique Id generating */
	function uniqId() {
		return Math.round(new Date().getTime() + (Math.random() * 100));
	}
	
	/* Add new troubles subtype */
	$('body').on('click', '#add_new_troubles_subtype',function() { 
		var new_row = $("#trouble_subtype_new").html();
		var template = Handlebars.compile(new_row);	
		var current_ref = $(this).data('ref');
		if(current_ref == 'troubles_subtype_new'){
			var html_content = new_row.replace("subtype_", "subtype_"+uniqId());
			$('#troubles_subtype_new tbody').append(html_content);
		}
	});
	
	var mixed_id_classes = '';
	var mixed_id_class = '';
	
	if ($('#relared_process_default_ids').val()) {
		mixed_id_classes = $('#relared_process_default_ids').val();
		mixed_id_class = mixed_id_classes.split(',');
	}
	
	if(mixed_id_class) {
		$.each(mixed_id_class, function (key, value) {
			if (value) {
				var id_class_array = value.split('|');
				var id = id_class_array[0];
				var class_name = id_class_array[1];
				
				set_request_key(id, class_name);
			}
		});
	}
	
	$('body').on('change', '.process_key',function(e) {
		
		check_unique_related_process($(this).attr('id'));	
		
		var select_process_key_value = $(this).val();		
		var nearest_select_classes = $(this).closest('td').next().find('select').attr('class');
		var split_class = nearest_select_classes.split(' ');
		var nearest_select_class = split_class[0];		
		var split_value = res = select_process_key_value.split("|");
		var selected_process_id = split_value[0];
		$("."+nearest_select_class+" option").remove();
		set_request_key(selected_process_id, nearest_select_class);
	});
	
	function set_request_key(selected_process_id, nearest_select_class) {
		
		var process_request_keys_values = $('#process_request_keys_values').val();
		var splited_process_request_keys_values = process_request_keys_values.split(',');

		$.ajax({
			url : '/admin/trouble_type/get_setup_activites/',
			type : 'POST',
			data : {process_id : selected_process_id},
			dataType : 'json',
			success : function(data) {			
				$.each(data, function (key, value) {
					$("."+nearest_select_class).append("<option value='"+value.key+"'>"+value.key+"</option>");
				});
				
				$.each(splited_process_request_keys_values, function (keys_values_key, keys_values_value) {
					keys_values = keys_values_value.split('|');
					
					$("."+nearest_select_class).each(function() {
						$(this).find('option[value="'+keys_values[1]+'"]').prop('selected', true);
					});
				});
			}		
		});
	}
	
});

/**
 * Load alert flash message
 */
function alert_message_load(error_type, idname, message) {
	$('#'+idname).removeClass('alert-danger');
	$('#'+idname).removeClass('alert-success');
	if (error_type == 'danger') {
		$("html, body").animate({ scrollTop: 0 }, "slow");
		$('#'+idname).addClass('alert-danger');
		$('#'+idname).html(message);
		$("#"+idname).fadeTo(3000, 500).slideUp(500, function() {
			$("#"+idname).slideUp(500);
			$('#'+idname).removeClass('alert-danger');
		});
	} else if (error_type == 'success') {
		$("html, body").animate({ scrollTop: 0 }, "slow");
		$('#'+idname).addClass('alert-success');
		$('#'+idname).html(message);
		$("#"+idname).fadeTo(3000, 500).slideUp(500, function() {
			$("#"+idname).slideUp(500);
			$('#'+idname).removeClass('alert-success');
		});
	}
}

function trouble_type_submit_check() {
	var trouble_type_title = '';
	var trouble_type_key = '';
	var trouble_type_severity = '';
	
	if ($.trim($('#trouble_type_title').val()) != '') {
		trouble_type_title = $('#trouble_type_title').val();
	}
	if ($.trim($('#trouble_type_key').val()) != '') {
		trouble_type_key = $('#trouble_type_key').val(); 
	}
	if ($.trim($('#trouble_type_severity').val()) != '') {
		trouble_type_severity = $('#trouble_type_severity').val(); 
	}
	
	if ((trouble_type_title != '') && (trouble_type_key != '') && (trouble_type_severity != '')) {
		return true;
	} else {
		alert_message_load('danger', 'trouble_type_message', "Please fill all mandatory fields");
		
		return false;
	}
}

/**
 * Function to check the unique trouble type name
 */ 
function check_unique_trouble_type() {
	var trouble_type_key = $('#trouble_type_key').val();
	var trouble_type_id = '';
	
	if ($('#trouble_type_id').val()) {
		trouble_type_id = $('#trouble_type_id').val();
	}
	
	$.ajax({
		url : '/admin/trouble_type/check_unique/',
		type : 'POST',
		data : {key : trouble_type_key, id : trouble_type_id},
		dataType : 'json',
		success : function(data) {			
			if(data == 1){				
				$('#trouble_type_key').parents('.form-group:first').addClass('has-error');
				$('#trouble_type_submit').attr('disabled','disabled');
				if($("#trouble_type_key_report_error").length < 1)
					$("#trouble_type_key").after("<span id='trouble_type_key_report_error' class='control-label has-error'>Key already used</span>")	
			}else{
				$('#trouble_type_submit').removeAttr('disabled');
				$('#trouble_type_key').parents('.form-group:first').removeClass('has-error');
				$('#trouble_type_key').next('span[id=trouble_type_key_report_error]').remove();
			}
		}		
	});
}
/**
 * Function to check the unique troubles subtype name
 */ 
function check_unique_subtype_key(arg) {
	var id = arg.getAttribute('id');
	
	if (id) {
		var trouble_subtype_key = $('#'+id).val();
		var trouble_type_id = '';
		
		if ($('#'+id).data('id') !== undefined) {
			trouble_type_id = $('#'+id).data('id');
		}
		
		$.ajax({
			url : '/admin/trouble_type/check_unique_subtype/',
			type : 'POST',
			data : {key : trouble_subtype_key, id : trouble_type_id},
			dataType : 'json',
			success : function(data) {		
				if (trouble_type_id == '') {
					if(data == 1){
						$('#'+id).css('border', '1px solid red');
						$('#subtype_submit').attr('disabled','disabled');
					} else {
						$('#'+id).css('border', '1px solid #DDDDDD');
						$('#subtype_submit').removeAttr('disabled');
					}
				} else {
					if(data == 0){				
						$('#'+id).css('border', '1px solid #DDDDDD');
						$('#subtype_submit').removeAttr('disabled');
					}else{
						$('#'+id).css('border', '1px solid red');
						$('#subtype_submit').attr('disabled','disabled');
					}
				}
			}		
		});
	}
}

function check_unique_related_process(id) {
	if (id) {
		var related_process_value = $('#'+id).val();
		var related_process_id = '';
		var trouble_type_id = $('#trouble_type_id_for_related_process').val();
		
		if ($('#'+id).data('id') !== undefined) {
			related_process_id = $('#'+id).data('id');
		}
		
		$.ajax({
			url : '/admin/trouble_type/check_unique_related_process/',
			type : 'POST',
			data : {key : related_process_value, id : related_process_id, trouble_type_id : trouble_type_id},
			dataType : 'json',
			success : function(data) {
				if (trouble_type_id == '') {
					if(data == 1){
						$('#'+id).css('box-shadow', '0px 0px 0px 1px red');
						$('#related_process_submit').attr('disabled','disabled');
					} else {
						$('#'+id).css('box-shadow', '0px 0px 0px 1px #DDDDDD');
						$('#related_process_submit').removeAttr('disabled');
					}
				} else {
					if(data == 0){
						$('#'+id).css('box-shadow', '0px 0px 0px 1px #DDDDDD');
						$('#related_process_submit').removeAttr('disabled');
					}else{
						$('#'+id).css('box-shadow', '0px 0px 0px 1px red');
						$('#related_process_submit').attr('disabled','disabled');
					}
				}
			}		
		});
	}
}

function check_unique_related_request(arg) {
	var id = arg.getAttribute('id');
}