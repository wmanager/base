<table class="table table-striped table-hover" id="related_process_new">
	<thead>
		<tr>
			<th>Process Key</th>
			<th>Request Key</th>
			<th>Auto create</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
    <?php
				$relared_process_default_ids = '';
				
				if (isset ( $related_process_list )) {
					$process_request_keys_values = '';
					foreach ( $related_process_list as $key => $related_process_item ) {
						$process_request_keys_values = $process_request_keys_values . $related_process_item->process_key . '|' . $related_process_item->request_key . '|' . $related_process_item->id . ',';
						?>
        	<tr>
			<td width="40%"><select
				data-id="<?php echo $related_process_item->id;?>"
				id="related_process_<?php echo $related_process_item->id;?>"
				class="process_key" name="process_key[]" aria-required="true"
				required="required">
                        <?php
						$id = '';
						foreach ( $related_process_setup_processes as $key => $value ) {
							?>
                        	<option
						value="<?php echo $value->id.'|'.$value->key;?>"
						<?php echo $related_process_item->process_key == $value->key ? 'selected="selected"' : '';?>><?php echo $value->key;?></option>
                        <?php
							if ($related_process_item->process_key == $value->key) {
								$process_key_id = $value->id;
							}
						}
						?>
                    </select></td>
			<td width="46%">
                	<?php
						$class = 'request_key_' . uniqid ();
						?>
                	<select
				data-id="<?php echo $related_process_item->id;?>"
				id="related_request_<?php echo $related_process_item->id;?>"
				class="<?php echo $class;?>" name="request_key[]"
				aria-required="true" required="required"
				style="width: 35em !important;"
				onchange="check_unique_related_request(this)"></select>
			</td>
			<td><input type="checkbox" name="related_process_auto_create[]"
				value="f" class="checkbox process_checked"
				<?php echo ($related_process_item->autocreate == 't')? 'checked':''; ?>>
				<input type="hidden"
				value="<?php echo ($related_process_item->autocreate == 't')? 't':'f'; ?>"
				name="hidden_related_process_auto_create[]" /></td>
			<td><a
				href="<?=site_url('/common/trouble_type/delete_relared_process/'.$related_process_item->id.'/'.$edit_id.'/'.$page_number);?>"
				class="delete-confirm"
				data-message="Are you sure.Do you want to delete the record?"
				title="Delete"><i class="fa fa-trash-o"></i></a></td>
			<input type="hidden" name="val_id[]"
				value="<?=$related_process_item->id;?>" />
		</tr>
    <?php
						$relared_process_default_ids = $relared_process_default_ids . ',' . $process_key_id . '|' . $class;
					}
					?>
    	<input type="hidden" name="process_request_keys_values"
			id="process_request_keys_values"
			value="<?php echo rtrim($process_request_keys_values, ',');?>" />
    	<?php
				}
				?>
    <input type="hidden"
			value="<?php echo ltrim($relared_process_default_ids, ',');?>"
			id="relared_process_default_ids" name="relared_process_default_ids" />
	</tbody>
</table>
<script id="trouble_related_process_new"
	type="text/x-handlebars-template">
<tr>
    <td><select data-id="" id="id_related_process_" value="" class="process_key" name="process_key[]" aria-required="true" required="required">
    <option value="--">Select process key</option>
    <?php
				foreach ( $related_process_setup_processes as $key => $value ) {
					?>
        <option value="<?php echo $value->id.'|'.$value->key;?>"><?php echo $value->key;?></option>
    <?php
				}
				?>
    </select></td>
	<td><select data-id="" class="request_key" name="request_key[]" aria-required="true" required="required" style="width: 35em !important;" onchange="check_unique_related_request(this)"></select></td>
	<td><input type="checkbox" name="related_process_auto_create[]" value="f" class="checkbox process_checked"><input type="hidden" name="hidden_related_process_auto_create[]"/></td>
	<td><a href="javascript:void(0)" data-newstatus="0" class="deleteStatus"><i class="fa fa-trash-o"></i></a></td>
	<input type="hidden" value="" name="val_id[]">
</tr>
</script>
