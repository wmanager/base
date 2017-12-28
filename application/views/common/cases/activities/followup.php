<div class="row-fluid comment-list" id="userList">
	<div ng-repeat="c in comments" class="col-sm-12">

		<div class="row vcenter">
			<div class="col-xs-2" ng-class-odd="'col-xs-push-10'">
				<p class="text-muted creator" ng-class-even="'pull-right'"
					ng-class-odd="'pull-left'">
					<small>Written by {{c.first_name}} {{c.last_name}} the
						{{c.created.substring(0,16)}}</small>
				</p>
			</div>
			<div class="col-xs-10" ng-class-odd="'col-xs-pull-2'">
				<div class="panel panel-default" ng-class-even="'arrow left'"
					ng-class-odd="'arrow right'">
					<div class="panel-body">
						{{c.description}}<br> <span class="pull-left"><i
							class="fa fa-calendar" ng-if="c.start_day"></i> <span
							ng-if="c.start_day" class="label label-info">{{c.start_day}}
								{{c.start_time.substring(0,5)}}</span>&nbsp;&nbsp; <span
							ng-if="c.isdone=='f' && c.start_day"><a href="#"
								ng-click="followupSetDone(c.id)">Checked!</a></span> <span
							ng-if="c.isdone=='t'  && c.start_day"><i class="fa fa-check"></i></span>
							<br>
						<i class="fa fa-envelope-o" ng-if="c.notification_date"></i> <span
							ng-if="c.notification_date" class="label label-info">{{c.notification_date}}</span>
						</span> <!-- <span class="pull-right" ng-if="formTypeMemos == 'LEGAL'"><a
							href="#" data-toggle="modal" data-target="#responseModal"
							ng-click="setEditMemoPopup(c)"> <i class="fa fa-pencil"
								aria-hidden="true"></a></i></span> -->
					</div>
				</div>
			</div>
			<!--/col-->
		</div>
		<!--/row-->

	</div>
	<!--/col-->

</div>
<!--/row-->

<hr>
<div class="row-fluid">
	<div class="col-md-12">
		<h4>New Note</h4>

		<div class="alert alert-danger" ng-if="dataerror">{{dataerror}}</div>
		<div class="form-group">
			<label>Description</label>
			<textarea placeholder="Description" class="form-control"
				ng-model="followup.description"></textarea>
			<div class="checkbox">
				<label><input type="checkbox" ng-true-value="true"
					ng-false-value="false" ng-model="followup.scheduler"> Reminder</label>
			</div>
			<div class="col-md-4" ng-show="followup.scheduler">
				<label>Schedule for the day</label>
				<div data-date-viewmode="days" data-date-format="dd/mm/yyyy"
					data-date-end-date="" data-date="" class="input-group date"
					new-calendar>
					<input type="text" class="form-control"
						column="col-sm-6 col-md-6 col-lg-6" placeholder="dd/mm/yyyy"
						value="" name="d_firma" ng-model="followup.day"> <span
						class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>

			</div>

			<div class="col-md-4" ng-if="followup.scheduler">
				<label>Schedule for the time</label> <input type="text"
					placeholder="hh:mm" class="form-control" id="followup-time"
					ng-model="followup.time">
			</div>
			<div class="clearfix"></div>
			<div class="col-md-4"
				ng-show="followup.scheduler && formTypeMemos == 'LEGAL'">
				<label>Notification date</label>
				<div data-date-viewmode="days" data-date-format="dd/mm/yyyy"
					data-date-end-date="" data-date="" class="input-group date"
					new-calendar>
					<input type="text" class="form-control"
						column="col-sm-6 col-md-6 col-lg-6" placeholder="dd/mm/yyyy"
						value="" name="notification_date"
						ng-model="followup.notification_date"> <span
						class="input-group-addon"><i class="fa fa-envelope-o"
						aria-hidden="true"></i></i></span>
				</div>

			</div>
			<div class="clearfix"></div>
		</div>
		<div class="form-group">
			<button type="button" class="btn btn-success"
				ng-click="insertFollowup()" id="save_followup_button"
				ng-disabled="!followup.description || (followup.scheduler && !followup.day) || followup_process == true;"
				ng-hide="selected.status == 'CANCELED' || selected.status == 'DONE'">Save</button>
		</div>
	</div>
</div>
<div id="responseModal" class="modal fade in">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Edit Note</h4>
			</div>
			<div class="modal-body">
				<section>
					<div class="alert alert-danger" ng-if="followupPopdataerror">
						{{followupPopdataerror}}</div>
					<div class="alert alert-success" ng-if="followupPopdatasuccess">
						{{followupPopdatasuccess}}</div>
					<div class="form-group col-md-12">
						<div class="col-md-10">
							<label>Description</label>
							<textarea placeholder="Description"
								class="form-control" ng-model="followupPop.description"
								ng-required="true"></textarea>
							<div class="checkbox">
								<label><input type="checkbox" ng-checked="followupPop.scheduler"
									ng-model="followupPop.scheduler"> Reminder</label>
							</div>
							<div class="col-md-4" ng-show="followupPop.scheduler">
								<label>Schedule for the day</label>
								<div data-date-viewmode="days" data-date-format="dd/mm/yyyy"
									data-date-end-date="" data-date="" class="input-group date"
									new-calendar>
									<input type="text" class="form-control"
										column="col-sm-6 col-md-6 col-lg-6" placeholder="dd/mm/yyyy"
										value="" ng-model="followupPop.day" ng-required="true"> <span
										class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>

							</div>

							<div class="col-md-4" ng-if="followupPop.scheduler">
								<label>Schedule for the time</label> <input type="text"
									placeholder="hh:mm" class="form-control" id="followupPop-time"
									ng-model="followupPop.time">
							</div>
							<div class="clearfix"></div>
							<div class="col-md-4"
								ng-show="followupPop.scheduler && formTypeMemos == 'LEGAL'">
								<label>Notification date</label>
								<div data-date-viewmode="days" data-date-format="dd/mm/yyyy"
									data-date-end-date="" data-date="" class="input-group date"
									new-calendar>
									<input type="text" class="form-control"
										column="col-sm-6 col-md-6 col-lg-6" placeholder="dd/mm/yyyy"
										value="" ng-model="followupPop.notification_date"> <span
										class="input-group-addon"><i class="fa fa-envelope-o"
										aria-hidden="true"></i></span>
								</div>

							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					<div class="clearfix"></div>
					<hr>
					<div class="row">
						<div class="col-md-3 pull-right">
							<a class="btn btn-primary" href="javascript:void(0)"
								ng-click="updateFollowup()"
								ng-disabled="!followupPop.description || (followupPop.scheduler && !followupPop.day)">Save</a>
							<button class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
</div>
