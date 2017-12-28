
<table class="table table-striped table-hover" id="attachment_table">
	<tbody>
<?php
if (isset ( $attachment_data )) {
	foreach ( $attachment_data as $key => $attachment_item ) {
		?>
		<tr>
			<td><label><?=$attachment_item->title;?><input type="hidden"
					value="<?=$attachment_item->attach_id;?>" class="form-control"
					name="id_attachment[]"></label></td>
			<td><label><?php echo ($attachment_item->required == 't')? 'Required' : 'Not Required'; ?></label>
				<input type="hidden"
				value="<?php echo ($attachment_item->required == 't')? 't' : 'f'; ?>"
				name="hidden_required[]" /></td>
			<td><label><?=$attachment_item->use;?><input type="hidden"
					value="<?=$attachment_item->use;?>" class="form-control"
					name="use[]"></label></td>
			<td><label><?php echo ($attachment_item->multi == 't')? 'Multiple' : 'Not Multiple'; ?></label>
				<input type="hidden"
				value="<?php echo ($attachment_item->multi == 't')? 't' : 'f'; ?>"
				name="hidden_multi[]" /></td>
			<td><a
				href="<?=site_url('/admin/setup_form/delete_attachments/'.$form_id.'/'.$attachment_item->id);?>"
				class="delete-confirm"
				data-message="Are you sure. Do you want to delete the record?"
				title="Delete"><i class="fa fa-trash-o"></i></a></td>
			<td><a
				href="<?=site_url('/admin/setup_form/edit_attachment/'.$form_id.'/'.$attachment_item->id);?>"
				data-toggle="ajaxModal" data-ref="<?=$attachment_item->id;?>"><i
					class="fa fa-pencil"></i></a></td>
			<input type="hidden" name="attach_id[]"
				value="<?=$attachment_item->id;?>" />
		</tr>
<?php
	}
}
?>
</tbody>
</table>
<script id="attachment_new_list" type="text/x-handlebars-template">
	<tr>
		<td width="300"><select name="id_attachment[]" class="form-control">{{#each attachments}}<option value="{{ this.id }}">{{ this.title }}</option>{{/each}}</select></td>
		<td><div class="checkbox"><label><input type="checkbox" name="required[]" value="f" id="disabled" class="checkbox process_checked"> Required</label><input type="hidden" value="f" name="hidden_required[]"/></div></td>
		<td width="300"><select name="use[]" class="form-control"><option value="UPLOAD">UPLOAD</option><option value="LIST">LIST</option></select></td>
		<td><div class="checkbox"><label><input type="checkbox" name="multi[]" value="f" id="disabled" class="checkbox process_checked"> Multi</label><input type="hidden" value="f" name="hidden_multi[]"/></div></td>
		<td><a href="javascript:void(0)" data-newattc="0" class="deleteAttachment" title="Delete"><i class="fa fa-trash-o"></i></a></td>
		<td></td>		
		<input type="hidden" value="" name="attach_id[]">
	</tr>

</script>