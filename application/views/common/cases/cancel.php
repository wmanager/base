<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Cancel thread</h4>
</div>
<div class="modal-body">
	<h5>
		<strong>You are about to cancel the thread <?=$details->title?> (#<?=$thread_id?>), the corresponding contract and the corresponding non-closed activities.</strong>
	</h5>
	<form name="cancelThreadForm" method="post" action=""
		class="form-horizontal col-md-12">
		<div class="form-group">
			<label>Enter the reason for cancellation </label> <input
				type="hidden" name="thread_id" value="<?=$thread_id?>"
				class="form-control"> <select name="reason" class="form-control"
				required>
				<option value="">Select</option>
	    	<?php
						foreach ( $cancel_reasons as $reason ) {
							echo '<option value"' . $reason->key . '">' . $reason->key . '</option>';
						}
						?>
	    	</select>
		</div>
		<div class="form-group">
			<input type="hidden" name="cancel_activities" value="yes"
				ng-model="wheelbase" ng-init="wheelbase='yes'">			
		</div>
	</form>
	<div class="clearfix"></div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" class="btn btn-primary" name="Save">Proceed</button>
</div>