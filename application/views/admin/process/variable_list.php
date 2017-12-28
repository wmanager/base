<table class="table table-striped table-hover">
	<tbody>
<?php
if (isset ( $other_data )) {
	foreach ( $other_data as $key => $other_item ) {
		?>	<tr>
			<td><?=$other_item->key;?></td>
			<td><?=$other_item->type;?></td>
			<td><?=$other_item->totalcount;?></td>
			<td>Active</td>
			<td><a
				href="<?=site_url('/admin/setup_processes/delete_variable/'.$process_id.'/'.$other_item->id);?>"
				class="delete-confirm"
				data-message="Are you sure. Do you want to delete the record?"
				data-ref="<?=$other_item->id;?>"><i class="fa fa-trash-o"></i></a></td>
			<td><a
				href="<?=site_url('/admin/setup_processes/edit_other_variable/'.$process_id.'/'.$other_item->id);?>"
				data-toggle="ajaxModal" data-ref="<?=$other_item->id;?>"><i
					class="fa fa-pencil"></i></a></td>
		</tr>
<?php 
	}
}?>
</tbody>
</table>
