<form action="/common/troubles/manual_process_save" method="post">
<input type="" name="trouble_id" ng-model="trouble_id" style="display: none;"/>
<input type="text" name="customer_id" ng-model="selected.customer.id" style="display: none;"/>
<input type="text" name="be_id" ng-model="selected.contract" style="display: none;"/>
<input type="text" name="request_activity" id="request_activity" style="display: none;"/>
<input type="text" name="thread_type" id="thread_type" style="display: none;"/>
<div class="col-md-12">
<div ng-repeat="(key, request) in related_threads_activities track by $index" style="border: 1px solid #ccc;padding: 17px;margin-top: 5px;">
	<span>Process : <a target="_blank" href="/common/cases/edit/{{request[0].thread_id}}">{{request[0].thread_type}} ({{request[0].bpm}})</a></span>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>Activities</th>
				<th></th>
				<th>Status</th>
				<th>Result</th>				
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="rel in request">
				<td>
					<a href="/common/activities/detail/{{rel.id}}" target="_blank">#{{rel.id}} {{rel.type}}</a>
					<br><small>Assigned to {{rel.company_name}}<br>
						on {{rel.created}} by {{rel.first_name + ' ' + rel.last_name}}</small>				
				</td>
				<td>Assigned to:<br> {{rel.duty_company}}</td>
				<td><span class='label label-primary'>{{rel.status}}</span><br>{{rel.result_status.label}}<small>	</td>
				<td>{{rel.result_value}}</td>
			</tr>
		</tbody>
	</table>

	<div class="col-md-12"><label class="control-label"><b>Activity Type</b></label></div>
	<div class="form-group">
		<div class="col-md-4">
			<select ng-model="manualForm.request_activity" class="form-control" ng-change="set_process(manualForm.request_activity,request[0].thread_type)" ng-disabled="trouble_status == 'DONE' || trouble_status == 'CANCELLED' || request[0].bpm == 'AUTOMATIC' ">
				<option ng-repeat="req in related_threads_activities[key][0].request_activity" ng-value="req.act_key">{{req.act_key}}</option>
			</select>
		</div>
		<button type="submit" name="activity_save" value="submit" class="btn btn-success" ng-disabled="trouble_status == 'DONE' || trouble_status == 'CANCELLED' || request[0].bpm == 'AUTOMATIC'">Create</button>
	</div>		
</div>
</div>


	
<div class="col-md-12">
<div class="col-md-12" style="border: 1px solid #ccc;padding: 17px;margin-top: 5px;">
<div class="col-md-12"><label><b>Create New Process</b></label></div>
<br>
<div class="col-md-12"><label class="control-label"><b>Process Type</b></label></div>
	<div class="form-group">
		<div class="col-md-4">
			<select name="process" ng-model="manualForm.all_process" class="form-control" ng-change="get_request(manualForm.all_process)" ng-disabled="trouble_status == 'DONE' || trouble_status == 'CANCELLED'">
				<option ng-repeat="process in all_process" ng-value="process.process_key">{{process.title}}</option>
			</select>
		</div>		
	</div>	
<br>
<div class="col-md-12"><label class="control-label"><b>Request Type</b></label></div>
	<div class="form-group">
		<div class="col-md-4">
			<select name="request" ng-model="manualForm.process_request" class="form-control" ng-disabled="trouble_status == 'DONE' || trouble_status == 'CANCELLED'">
				<option ng-repeat="request in process_request" ng-value="request.act_key">{{request.act_key}}</option>
			</select>
		</div>	
		
		<button type="submit" class="btn btn-success" ng-disabled="!process_request || process_request.length == 0" ng-disabled="trouble_status == 'DONE' || trouble_status == 'CANCELLED'">Create</button>	
	</div>	
</div>
</div>
</form>	



