<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content"></div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<div class="row-fluid" ng-if="thread.bpm=='MANUAL'">
	<div class="col-md-12">
		<div class="form-group">

			<a data-toggle="modal" data-target="#myModal"
				href="/common/cases/create_related_activity/{{thread.id}}"
				class="btn btn-success pull-right"
				ng-disabled="thread.status=='DONE'">Aggiungi attività correlate</a>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<!-- <div ng-repeat="rel in activities" class="row-fluid">
			 <div  ng-include="activity_summary+'/'+activity.id" class="col-md-12"></div> 	
		</div> -->

<div class="class="row-fluid"">
	<table id="related_activity" name="related_activity"
		class="table table-striped table-hover"
		ng-if="!selected.showdashboard">
		<thead>
			<tr>
				<th>Type</th>
				<th>Status</th>
				<th>Created</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="rel in rel_activities">
				<td>{{rel.setup_title}}</td>
				<!-- <td ng-if="rel.o_ragione_sociale == null">{{rel.cust_name}}</td>
					<td ng-if="rel.o_ragione_sociale != null">{{rel.o_ragione_sociale}}</td> -->
				<td>{{rel.status}}</td>
				<td>{{rel.created}}</td>
				<td><a class="btn btn-sm btn-primary" href="{{rel.link}}">Dettaglio
						Attivita</a></td>
			</tr>
		</tbody>
	</table>
</div>