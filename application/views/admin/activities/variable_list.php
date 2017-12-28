<table class="table table-striped table-hover">
	<tbody>
		<tr>
			<th>Key</th>
			<th>Label</th>
			<th>Layout</th>
			<th>Type</th>
			<th class="col-md-1">Ordering</th>
			<th>Active</th>
			<th></th>
			<th></th>
		</tr>
<?php
if (isset ( $other_data )) {
	foreach ( $other_data as $key => $other_item ) {
		?>	
	<tr>
			<td><?=$other_item->key;?></td>
			<td><?=$other_item->var_label;?></td>
			<td><?=$other_item->layout;?></td>
			<td><?=$other_item->type;?></td>
			<td class="col-md-1"><input type="hidden" name="other_vars_ids[]"
				class="form-control" value="<?=$other_item->id;?>"> <input
				type="text" name="var_ordering[]" class="form-control"
				value="<?=$other_item->ordering;?>"></td>
		<?php
		if ($other_item->disabled == 'f') {
			echo "<td>Active</td>";
		} else {
			echo "<td>InActive</td>";
		}
		?>
		<td><a
				href="<?=site_url('/admin/setup_activities/delete_variable/'.$id_process.'/'.$activity_id.'/'.$other_item->id);?>"
				class="delete-confirm"
				data-message="Are you sure. Do you want to delete the record?"
				data-ref="<?=$other_item->id;?>"><i class="fa fa-trash-o"></i></a></td>
			<td><a
				href="<?=site_url('/admin/setup_activities/edit_other_variable/'.$id_process.'/'.$activity_id.'/'.$other_item->id);?>"
				data-toggle="ajaxModal" data-ref="<?=$other_item->id;?>"><i
					class="fa fa-pencil"></i></a></td>
		</tr>
<?php 
	}
}?>
</tbody>
</table>
