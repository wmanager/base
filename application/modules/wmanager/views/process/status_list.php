 <?php
	$other_variable_class = 'status_variable';
	if (isset ( $status_data ) && isset ( $status_data ['other_variable'] )) {
		$other_variable_class = 'other_variable';
		unset ( $status_data ['other_variable'] );
	}
	?>
<table
	class="table table-striped table-hover <?php echo $other_variable_class; ?>"
	id="status_var_table">
	<thead>
		<tr>
			<th>KEY</th>
			<th>LABEL</th>
			<th>Description</th>
			<th>Order</th>
			<th>Initial</th>
			<th>Final</th>
			<th>Final Default</th>
			<th>Disabled</th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php
if (isset ( $status_data )) {
	foreach ( $status_data as $key => $status_item ) {
		?>
		<tr>
			<td><input type="text" value="<?php echo $status_item->key;?>"
				class="form-control" name="status_key[]"></td>
			<td><input type="text" value="<?php echo $status_item->label;?>"
				class="form-control" name="label[]"></td>
			<td><input type="text"
				value="<?php echo $status_item->description;?>" class="form-control"
				name="status_description[]"></td>
			<td><input type="number" value="<?php echo $status_item->ordering;?>"
				min="0" name="ordering[]" class="form-control"></td>
			<td><input type="checkbox" name="initial[]" value="f"
				class="checkbox process_checked"
				<?php echo ($status_item->initial == 't')? 'checked':''; ?>> <input
				type="hidden"
				value="<?php echo ($status_item->initial == 't')? 't':'f'; ?>"
				name="hidden_initial[]" /></td>
			<td><input type="checkbox" name="final[]" value="f"
				class="checkbox process_checked"
				<?php echo ($status_item->final == 't')? 'checked':''; ?>> <input
				type="hidden"
				value="<?php echo ($status_item->final == 't')? 't':'f'; ?>"
				name="hidden_final[]" /></td>
			<td width="100"><input type="checkbox" name="final_default[]"
				value="f" class="checkbox process_checked"
				<?php echo ($status_item->final_default == 't')? 'checked':''; ?>> <input
				type="hidden"
				value="<?php echo ($status_item->final_default == 't')? 't':'f'; ?>"
				name="hidden_final_default[]" /></td>
			<td><input type="checkbox" name="status_disabled[]" value="f"
				class="checkbox process_checked"
				<?php echo ($status_item->disabled == 't')? 'checked':''; ?>> <input
				type="hidden"
				value="<?php echo ($status_item->disabled == 't')? 't':'f'; ?>"
				name="hidden_status_disabled[]" /></td>
			<td><a
				href="<?=site_url('/admin/setup_processes/delete_status/'.$process_id.'/'.$status_item->id);?>"
				class="delete-confirm"
				data-message="Are you sure. Do you want to delete the record?"
				title="Delete"><i class="fa fa-trash-o"></i></a></td>
			<input type="hidden" name="val_id[]"
				value="<?php echo $status_item->id; ?>" />
		</tr>
<?php
	}
}
?>
</tbody>
</table>

<script id="status_new_list" type="text/x-handlebars-template">
<tr>
		<td><input type="text" value="" class="form-control" name="status_key[]"></td>
		<td><input type="text" value="" class="form-control" name="label[]"></td>
		<td><input type="text" value="" class="form-control" name="status_description[]"></td>
		<td><input type="number" min="0" value="" class="form-control" name ="ordering[]"></td>
		<td><input type="checkbox" name="initial[]" value="f" class="checkbox process_checked" ><input type="hidden" value="f" name="hidden_initial[]"/></td>
		<td><input type="checkbox" name="final[]" value="f"  class="checkbox process_checked" ><input type="hidden" value="f" name="hidden_final[]"/></td>
		<td width="100"><input type="checkbox" name="final_default[]" value="f"  class="checkbox process_checked" ><input type="hidden" value="f" name="hidden_final_default[]"/></td>
		<td><input type="checkbox" name="status_disabled[]" value="f"  class="checkbox process_checked" ><input type="hidden" value="f" name="hidden_status_disabled[]"/></td>
		<td><a href="javascript:void(0)" data-newstatus="0" class="deleteStatus"><i class="fa fa-trash-o"></i></a></td>
		<input type="hidden" value="" name="val_id[]">
</tr>
</script>
