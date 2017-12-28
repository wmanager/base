<div class="clearfix"></div>



<div class="row-fluid">
	<div class="col-md-12">
		<div class="form-group">
			<label>Titolo</label>
			<textarea ng-readonly="activity.status=='DONE'" class="form-control"
				ng-model="forms[$index].title"></textarea>
		</div>
		<div class="form-group">
			<label>Descrizione</label>
			<textarea ng-readonly="activity.status=='DONE'" class="form-control"
				ng-model="forms[$index].description"></textarea>
		</div>
	</div>
</div>
<!-- ACTIVITY FORM -->

<hr ng-if="activity.status!='DONE'">

<!-- ACTIVITY STATUS -->
<div class="row-fluid" ng-if="activity.status!='DONE'">
	<div class="col-md-12">
		<tabset> <tab heading="Ho finito">
		<div class="row-fluid">
			<div class="col-md-12">
				<label class="radio"
					ng-repeat="status in activity.statuses | filter: { final: 't' }"> <input
					type="radio" name="{{$parent.$index}}_status[]"
					ng-model="variables[$parent.$index].status" value="{{status.key}}">{{status.label}}
				</label>
			</div>
		</div>
		</tab> <tab heading="Non ho finito">
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
			ng-click="saveForm($index,'/actions/generic_title_description',activity.id)">Salva</button>
		<button type="button" class="btn btn-default"
			ng-click="resetForm($index)">Annulla</button>
	</div>
</div>
<!-- ACTION BUTTONS -->


