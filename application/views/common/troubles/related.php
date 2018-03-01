
<div class="row-fluid">
	<div class="col-md-12">
		<div class="form-group">
			<!-- <button class="btn btn-success pull-right" type="button"
				ng-click="selected.showdashboard=true"
				ng-if="!selected.showdashboard">Add related process</button>-->
			<button class="btn btn-default pull-right" type="button"
				ng-click="selected.showdashboard=false"
				ng-if="selected.showdashboard">Cancel</button>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
<div class="col-md-12">
	<table id="related_process" name="related_process"
		class="table table-striped table-hover"
		ng-if="!selected.showdashboard">
		<thead>
			<tr>
				<th>Type</th>
				<th>Customer Name</th>
				<th>Status</th>
				<th>Created</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="rel in related_threads">
				<td>{{rel.type}}</td>
				<td ng-if="rel.o_ragione_sociale == null">{{rel.cust_name}}</td>
				<td ng-if="rel.o_ragione_sociale != null">{{rel.o_ragione_sociale}}</td>
				<td>{{rel.status}}</td>
				<td>{{rel.created}}</td>
				<td><a class="btn btn-sm btn-primary"
					href="/common/cases/edit/{{rel.id}}">Process Detail</a></td>
			</tr>
			<tr ng-show="related_threads.length == 0">
				<td colspan="5">
					<span>No Record Found</span>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<div ng-if="selected.showdashboard" ng-include="dashboard"></div>