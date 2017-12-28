
<table class="table table-striped table-hover" id="exit_table">
	<thead>
		<tr>
			<th>Code</th>
			<th>Title</th>
		</tr>
	</thead>
	<tbody>
<?php
if (isset ( $scenario_data )) {
	foreach ( $scenario_data as $key => $scenario_item ) {
		?>
		<tr>
			<td><label><?php echo $scenario_item->code; ?></label></td>
			<td><label><?php echo $scenario_item->title; ?></label></td>
			<td><a
				href="<?=site_url('/admin/setup_activities/delete_scenario/'.$id_process.'/'.$activity_id.'/'.$scenario_item->id); ?>"
				class="delete-confirm"
				data-message="Are you sure.Do you want to delete the record?"
				title="Delete"><i class="fa fa-trash-o"></i></a></td>
			<td><a
				href="<?=site_url('/admin/setup_activities/edit_scenario/'.$id_process.'/'.$activity_id.'/'.$scenario_item->id); ?>"
				data-toggle="ajaxModal" data-ref="<?php echo $scenario_item->id; ?>"><i
					class="fa fa-pencil"></i></a></td>
		</tr>
<?php
	}
}
?>
</tbody>
</table>