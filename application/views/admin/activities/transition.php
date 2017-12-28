<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Transition For "<?php echo $status;?>"</h4>
</div>
<div class="modal-body">
	<section>
		<form name="transition_form" id="add_transition_form" method="post"
			action="/admin/setup_activities/save_transition">
			<div class="row">

				<div class="col-md-12">
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th>Key</th>
								<th>Label</th>
								<th>Description</th>
								<th>Order</th>
								<th></th>
							</tr>
						</thead>
										<?php
										if (count ( $status_data ) > 0) {
											$i = 1;
											foreach ( $status_data as $key => $item ) {
												if ($item->key != $status) {
													?>
												<tr>
							<td><?php echo $item->key;?></td>
							<td><?php echo $item->label;?></td>
							<td><?php echo $item->description;?></td>
							<td><?php echo $item->ordering;?></td>
							<td><input type="checkbox" name="status[]"
								value="<?php echo $item->key;?>"
								<?php if($item->transition)echo "checked";?>></td>
						</tr>
											<?php
													$i ++;
												}
											}
										} else {
											echo "<tr><td>No status found.</td></tr>";
										}
										?>
									</table>
				</div>
				<input type="hidden" name="act_id"
					value="<?php echo $activity_type_id;?>" /> <input type="hidden"
					name="process_id" value="<?php echo $process_id;?>" /> <input
					type="hidden" name="master_status" value="<?php echo $status;?>" />

				<div class="row">
					<div class="col-md-3 pull-right">
						<a class="btn btn-primary" href="javascript:void(0)"
							onclick="save_transition()" data-dismiss="modal">Save</a>
						<button class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<!-- /.modal-content -->
