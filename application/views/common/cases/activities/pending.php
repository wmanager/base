<div class="row-fluid">
	<div class="col-md-12">
		<div class="form-group">
			<div class="checkbox"
				ng-init="request.pending = (request.master_status=='PENDING') ? true : false;">
				<label><input type="checkbox"
					ng-checked="request.master_status=='PENDING'" ng-true-value="true"
					ng-false-value="false" ng-model="request.pending"> The Thread current is in PENDING</label>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="form-group">
			<label>Pending reason</label>
			<textarea class="form-control" ng-model="request.pending_reason"
				ng-init="request.pending_reason = request.master_status_detail"></textarea>
		</div>
	</div>
	<div class="col-md-12"
		ng-init="request.pending_related = request.pending_parent_thread">
		<div class="form-group">
			<label>Thread on which it depends (optional)</label>
			<div class="radio">
				<label> <input type="radio" name="related[$parent.$index]"
					id="related0" ng-value="" ng-model="request.pending_related"
					checked> Nessuno
				</label>
			</div>

			<div class="radio" ng-repeat="item in related_threads">
				<label> <input type="radio" name="related[$parent.$index]"
					id="related{{$index}}" ng-value="item.id"
					ng-model="request.pending_related"
					ng-checked="request.pending_parent_thread == item.id"> <span
					ng-if="item.type!='INORDER'">{{item.title}} #{{item.id}}</span> <span
					ng-if="item.type=='INORDER'">Inorder #{{item.id}}</span>
				</label>
			</div>

		</div>
	</div>
	<div class="col-md-12">
		<div class="form-group">
			<button class="btn btn-success" type="button" ng-click="setPending()">Save</button>
		</div>
	</div>
	<div>