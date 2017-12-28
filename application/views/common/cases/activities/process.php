<div class="clearfix"></div>

<div class="class="row-fluid"">
	<table id="process_list" name="process_list"
		class="table table-striped table-hover"
		ng-if="!selected.showdashboard">
		<thead>
			<tr>
				<th>Title</th>
				<th>Key</th>
				<th>Description</th>
				<th>Role</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="rel in process_activity_list">
				<td>{{rel.title}}</td>
				<td>{{rel.key}}</td>
				<td>{{rel.description}}</td>
				<td>{{rel.role}}</td>
			</tr>
		</tbody>
	</table>
</div>