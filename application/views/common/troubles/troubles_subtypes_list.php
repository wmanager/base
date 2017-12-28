<table class="table table-striped table-hover" id="troubles_subtype_new">
	<thead>
		<tr>
			<th>Subtype Key</th>
			<th>Subtype Value</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
    <?php
				$relared_process_default_ids = '';
				if (isset ( $troubles_subtypes_list )) {
					foreach ( $troubles_subtypes_list as $key => $troubles_subtype_item ) {
						?>
        	<tr>
			<td width="40%"><input type="text"
				data-id="<?php echo $troubles_subtype_item->id;?>"
				id="subtype_<?php echo $troubles_subtype_item->id;?>"
				value="<?php echo $troubles_subtype_item->key;?>"
				class="form-control" name="subtype_key[]"
				style='text-transform: uppercase'
				onkeyup="check_unique_subtype_key(this)"
				onblur="check_unique_subtype_key(this)"></td>
			<td><input type="text"
				value="<?php echo $troubles_subtype_item->value;?>"
				class="form-control" name="subtype_value[]"></td>
			<td><a
				href="<?=site_url('/common/trouble_type/delete_troubles_subtype/'.$troubles_subtype_item->id.'/'.$edit_id.'/'.$page_number);?>"
				class="delete-confirm"
				data-message="Are you sure.Do you want to delete the record?"
				title="Delete"><i class="fa fa-trash-o"></i></a></td>
			<input type="hidden" name="val_id[]"
				value="<?=$troubles_subtype_item->id;?>" />
		</tr>
    <?php
					}
				}
				?>
    </tbody>
</table>
<script id="trouble_subtype_new" type="text/x-handlebars-template">
<tr>
    <td><input type="text" data-id="" id="subtype_" value="" class="form-control" name="subtype_key[]" style='text-transform:uppercase' onkeyup="check_unique_subtype_key(this)" onblur="check_unique_subtype_key(this)"></td>
	<td><input type="text" value="" class="form-control" name="subtype_value[]"></td>
	<td><a href="javascript:void(0)" data-newstatus="0" class="deleteStatus"><i class="fa fa-trash-o"></i></a></td>
	<input type="hidden" value="" name="val_id[]">
</tr>
</script>
