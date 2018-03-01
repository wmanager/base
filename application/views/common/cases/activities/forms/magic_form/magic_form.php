<div class="clearfix"></div>

<div class="row-fluid">
	<div class="col-md-12">
		<p class="text-primary" ng-if="activity.help">
			<b>Note:</b> {{activity.help}}
		</p>
	</div>
	<div class="col-md-12">
		<div class="form-group">
			<label>Description</label>
			<textarea ng-readonly="activity.status=='DONE'" class="form-control"
				ng-model="forms[$index].description"></textarea>
		</div>
	</div>
	<div class="col-md-12"
		ng-repeat="magic_var in activity.magic_variables">
		<div class="form-group">
			<label>{{magic_var.var_label}} </label> <span
				ng-if="magic_var.layout == 'text'"> <input type="text"
				ng-model="forms[0][magic_var.key]"
				name="magic_vars-{{magic_var.key}}" class="form-control" />
			</span> <span ng-if="magic_var.layout == 'dropdown'"> <select
				ng-model="forms[0][magic_var.key]" class="form-control"
				name="magic_vars-{{magic_var.key}}">
					<option ng-repeat="options in magic_var.options"
						value="{{options.key}}"
						ng-selected="forms[0][magic_var.key] == options.key">{{options.label}}</option>
			</select>
			</span> <span ng-if="magic_var.layout == 'date'">
				<div data-date-viewmode="days" data-date-format="dd/mm/yyyy"
					class="input-group date" new-calendar="">
					<input type="text"
						class="form-control ng-pristine ng-valid ng-touched"
						column="col-md-12" placeholder="dd/mm/yyyy"
						ng-model="forms[0][magic_var.key]"
						name="magic_vars-{{magic_var.key}}"> <span
						class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
			</span>
		</div>
	</div>
</div>
<!-- ACTIVITY FORM -->

<hr ng-if="activity.status!='DONE'">

<!-- ACTIVITY STATUS -->
<div class="row-fluid" ng-if="activity.status!='DONE'">
	<div class="col-md-12">
		<tabset> <tab heading="Final Status">
		<div class="row-fluid">
			<div class="col-md-12">
				<label class="radio"
					ng-repeat="status in activity.statuses | filter: { final: 't' }"> <input
					type="radio" name="{{$parent.$index}}_status[]"
					ng-model="variables[$parent.$index].status" value="{{status.key}}">{{status.label}}
				</label>
			</div>
		</div>
		</tab> <tab heading="In Progress Status">
		<div class="row-fluid">
			<div class="col-md-12">
				<label class="radio"
					ng-repeat="status in activity.statuses | filter: { final: 'f' }"> <input
					type="radio" name="{{$parent.$index}}_status[]"
					ng-model="variables[$parent.$index].status" value="{{status.key}}">{{status.label}}
				</label>
			</div>
		</div>
		</tab> </tabset>
	</div>
</div>
<!-- ACTIVITY STATUS -->


<!-- ACTION BUTTONS -->
<div class="row-fluid" ng-if="activity.status!='DONE'">
	<div class="col-md-12">
		<button type="button" class="btn btn-success"
			ng-click="saveForm($index,'/actions/magic_form',activity.id)">Save</button>
		<button type="button" class="btn btn-default"
			ng-click="resetForm($index)">Cancel</button>
	</div>
</div>
<!-- ACTION BUTTONS -->