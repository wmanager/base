<div class="row-fluid">
	<div ng-if="selected.status == 'CANCELLED'">
		<div class="alert alert-warning alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Warning!</strong> This trouble was cancelled therefore you
			will not be able to edit the data.
		</div>
	</div>
	<div ng-if="selected.status == 'DONE'">
		<div class="alert alert-warning alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Warning!</strong> This trouble was closed therefore you will
			not be able to edit the data.
		</div>
	</div>
	<div class="form-group col-md-12">
		<label class="control-label"><b>Trouble</b></label> <select
			class="form-control" ng-required="true"
			ng-options="item.id as item.title for item in form_types"
			ng-model="selected.type" disabled>
		</select>
	</div>
	<div ng-show="selected.type && form_subtypes.length > 0"
		class="form-group col-md-12" disabled>
		<label class="control-label"><b>Trouble Subtype</b></label> <select
			class="form-control"
			ng-required="selected.type && form_subtypes.length > 0"
			ng-options="item.key as item.value for item in form_subtypes"
			ng-model="selected.subtype" disabled>
		</select>
	</div>

	<div ng-show="be_contratti.length > 0" class="form-group col-md-12"
		disabled>
		<label class="control-label"><b>Contratti</b></label> <select
			class="form-control"
			ng-options="item.id as item.value for item in be_contratti"
			ng-model="selected.be_contratti" disabled>
		</select>
	</div>

	<div class="form-group col-md-12">
		<label class="control-label"><b>Description</b></label>
		<textarea class="form-control" ng-required="true"
			ng-model="selected.description" disabled></textarea>
	</div>
	<div class="form-group col-md-6">
		<label><b>Responsabile del controllo lato CRM</b></label> <select
			class="form-control" ng-required="true"
			ng-options="item.id as item.name for item in crm_companies"
			ng-model="selected.duty_company_crm" disabled>
			<option value="">Select company</option>
		</select>
	</div>
	<div class="form-group col-md-6">
		<br> <select class="form-control" ng-required="false"
			ng-options="item.id as item.user_name for item in crm_users"
			ng-model="selected.duty_user_crm" disabled>
			<option value="">Select company user</option>
		</select>
	</div>
	<div class="clearfix"></div>
	<div class="form-group col-md-6" ng-show="selected.show_role_company">
		<label><b>Responsabile della risoluzione (troubleshooting)</b></label>
		<select class="form-control" ng-required="false"
			ng-options="item.key as item.key for item in setup_roles"
			ng-model="selected.res_roles" disabled>
			<option value="">Select role</option>
		</select>
	</div>
	<div class="clearfix"></div>
	<div class="form-group col-md-6" ng-show="selected.show_role_company">
		<label>&nbsp;</b></label> <select class="form-control"
			ng-required="false"
			ng-options="item.id as item.name for item in resolution_companies"
			ng-model="selected.duty_company_resolution" disabled>
			<option value="">Select company</option>
		</select>
	</div>
	<div class="form-group col-md-6" ng-show="selected.show_role_company">
		<br> <select class="form-control" ng-required="false"
			ng-options="item.id as item.user_name for item in resolution_users"
			ng-model="selected.duty_user_resolution" disabled>
			<option value="">Select user</option>
		</select>
	</div>
	<div class="clearfix"></div>
	<div class="form-group col-md-3">
		<label class="control-label"><b>Deadline</b></label>
		<div data-date-viewmode="days" data-date-format="dd/mm/yyyy"
			data-date="" class="input-group date" new-calendar>
			<input type="text" class="form-control"
				column="col-sm-6 col-md-6 col-lg-6" placeholder="dd/mm/yyyy"
				ng-model="selected.deadline" name="start_data" disabled> <span
				class="input-group-addon"><i class="fa fa-calendar"></i></span>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="form-group col-md-6">
		<label><b>Status</b></label> <select class="form-control"
			ng-required="true"
			ng-options="item.key as item.label for item in form_status"
			ng-model="selected.status" disabled>
		</select>
	</div>

	<div class="clearfix"></div>
	<div class="form-group col-md-6" ng-if="selected.status=='DONE'">
		<label><b>Result</b></label> <select class="form-control"
			ng-model="selected.result" ng-required="true" disabled>
			<option value="">Select Result</option>
			<option value="OK">OK</option>
			<option value="KO">KO</option>
		</select>
	</div>
	<div class="form-group col-md-12">
		<a type="button" target="_blank"
			ng-href="/common/troubles/edit/{{trouble_id}}"
			class="btn btn-primary"
			ng-hide="trouble_status == 'DONE' || trouble_status == 'CANCELLED'">Edit
			Trouble</a>
	</div>
	<div>