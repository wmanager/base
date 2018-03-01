<div class="col-md-12">
	<div class="widget stacked ">
		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>Activity Details</h3>
		</div>
		<!-- /.widget-header -->

		<div class="widget-content">
			<section id="thread" ng-controller="Thread"
				ng-init="mode='edit';thread=<?=$activity->id_thread?>;roles=<?=htmlspecialchars(json_encode(get_company_role()))?>">
				<!-- CUSTOMER/CONTRACT DETAILS AND REQUEST TYPE -->
				<div ng-hide="selected.customer.length==0">
					<h5>
						<i class="fa fa-user"></i> <b>Client Details</b>
					</h5>
					<p>						
						<b><a
							href="/common/accounts/detail/{{selected.customer.account_id}}">{{selected.customer.first_name}}
								{{selected.customer.last_name}}</a></b>
						<br> <span ng-show="selected.customer.address"> Resident:<br>
							<div class="col-md-4">
								{{selected.customer.address}} {{selected.customer.city}}
								{{selected.customer.state}}, {{selected.customer.country}}<br>
								{{selected.customer.zip}} {{selected.customer.province}}
							</div>
							<div class="col-md-4">
								Telephone: {{activities[0].customer.tel}}<br> Cell:
								{{activities[0].customer.cell}}<br> Email:
								{{activities[0].customer.email}}
							</div>
						</span>
					</p>

					<div class="clearfix"></div>
					
					<div ng-repeat="contratto in selected.contract track by $index">
						<p>
							<b>Contract:</b> {{contratto.contract_code}} - {{contratto.contract_type}}
						</p>
					</div>

					<p>
						<b>Type:</b> {{selected.template.title}} <a target="_blank"
							ng-href="/common/cases/edit/{{thread.id}}">THREAD #{{thread.id}}</a>
					</p>
				</div>
				<!-- REQUEST FORM LOADER -->

				<div ng-show="selected.customer && loadtemplate" class="row-fluid"
					ng-if="request.description != null">
					<div class="col-md-8">
						<tabset class="request_detail"> <tab heading="Request">
						<div ng-include="selected.template.url"></div>
						</tab> <tab heading="Attachments"> <alert
							ng-show="filedata.errors" type="danger">{{filedata.error}}</alert>
						<div ng-hide="filedata.busy">
							<div ng-if="activity.status!='DONE'">
								<div class="form-group">

									<label class="col-md-3 control-label" for="title">Type</label>
									<div class="col-md-9">
										<select name="attach_type" ng-model="filedata.attach_type"
											class="form-control">
											<option ng-repeat="file in filetypes" ng-value="file.id"
												ng-if="file.required != 'f'">{{file.title}} *</option>
											<option ng-repeat="file in filetypes" ng-value="file.id"
												ng-if="file.required != 't'">{{file.title}}</option>
										</select>
									</div>
								</div>
								<div class="clearfix"></div>
								<br>
								<div class="form-group">
									<label class="col-md-3 control-label" for="title">Description</label>
									<div class="col-md-9">
										<input type="text" class="form-control" label="Description"
											id="description" name="description"
											ng-model="filedata.description">
									</div>
								</div>
								<div class="clearfix"></div>
								<br>
								<div class="form-group">
									<label class="col-md-3 control-label" for="title">File</label>
									<div class="col-md-9">
										<input type="file" ng-model-instant id="fileToUpload" multiple
											onchange="angular.element(this).scope().setFiles(this)" />
									</div>
								</div>
								<div class="clearfix"></div>
								<br>
								<div class="form-group">
									<div class="col-md-12">
										<button type="button" class="btn btn-success"
											ng-click="upload()">Upload</button>
									</div>
								</div>
								<div class="clearfix"></div>
								<hr />
							</div>
							<div ng-repeat="file in listfiles">
								<p>
									<b>{{file.attachment_type}}</b>
								</p>
								<div class="row-fluid">
									<div class="col-md-1">
										<i class="fa fa-file-o"></i>
									</div>
									<div class="col-md-8">
										<p>
											<a
												href="/common/attachments/download_file/{{file.id}}/{{file.thread_id}}/{{file.activity_id}}">{{file.filename}}</a>
										</p>
										<small>{{file.description}}<br> Created on
											{{file.created.substring(0,16)}} by {{file.first_name}}
											{{file.last_name}}
										</small>
									</div>
									<div class="col-md-1" ng-if="activity.status!='DONE'">
										<a href="#"
											ng-click="deleteFile(file.id,file.thread_id,file.activity_id)"><i
											class="fa fa-trash"></i></a>
									</div>
								</div>
							</div>
						</div>
						<div ng-show="filedata.busy"
							style="width: 100px; height: 50px; margin: auto;">
							<i class="fa fa-4x fa-spinner fa-pulse"></i>
						</div>
						</tab> </tabset>
					</div>
					<div class="col-md-4">
						<div class="well">
							<h4 style="color: #419641">
								<span class="label label-info ng-binding">Status
									{{thread.status}}</span>&nbsp; &nbsp;<span
									class="label label-info ng-binding">Result {{thread.result}}</span>
							</h4>

							<br>
							<h5>{{activities.length}} Activity correlate</h5>
							<p>Last modified on {{thread.modified}}</p>
						</div>
						<div class="well">
							<h5 class="pull-right">SLA</h5>
							<h4 style="color: #419641">{{remaining.days}} days
								{{remaining.hours}} hours {{remaining.minutes}} minutes</h4>
							<p>
								This request was opened on<br>{{date | date:'dd-MM-yyyy
								HH:mm'}}<br>and must be resolved by:<br>{{deadline |
								date:'dd-MM-yyyy hh:m'}}
							</p>
						</div>
					</div>
				</div>
			</section>
		</div>

		<div class="widget-content">
			<section id="activity" ng-controller="Activity"
				ng-init="actid=<?=$this->uri->segment(4)?>;roles=<?=htmlspecialchars(json_encode(get_company_role()))?>;">
				<!-- ACTIVITY LOADER -->

				<div ng-repeat="activity in activities track by activity.id">
					<div ng-include="activity_template"
						ng-init="loadRequiredAttachments(activity.form_id);"></div>
				</div>
				
				<!-- Modal for Advanced Attivita -->
						<div class="modal fade" id="attivita-advanced-report" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
						    	<div class="modal-content">
						    		<div class="modal-header onsite-modal-header">
						        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						          			<span aria-hidden="true">&times;</span>
						        		</button>
						        		<h4 class="modal-title" id="myModalLabel">Advanced Activity</h4>
						      		</div>      
						      		<div class="modal-body" style="margin:0px;">
						      			<form action="" name="advanced_activity" id="advanced_activity">
						      			<div class="row">
						      				<div class="col-md-12">
							      				<div class="col-md-6" id="advact_thread_status">
												  <label><b>Thread Status</b></label>
												  	<select name="thread_status" ng-model="advact_thread_status" class="form-control">
												  		<option value="OPEN" selected>Open</option>
												  		<option value="CLOSED">Closed</option>
												  		<option value="CANCELLED">Cancelled</option>
												  	</select>
												</div>
												<div class="col-md-6" ng-show="advact_thread_status=='CLOSED' || advact_thread_status=='CANCELLED'" id="advact_thread_status">
												  <label><b>Thread Result</b></label>
												  	<select name="thread_status" ng-model="advact_thread_result" class="form-control">
												  		<option value="OK" selected>OK</option>
												  		<option value="KO">KO</option>
												  	</select>
												</div>
											</div>
											<div class="col-md-12">
												<hr />
											</div>
											<div class="col-md-12" ng-show="advact_trouble_exist == 'YES'">
							      				<div class="col-md-6" id="advact_thread_status">
												  <label><b>Trouble Status (<a target="_blank" href="/common/troubles/edit/{{advact_trouble_id}}">{{"#"+advact_trouble_id+' - '+advact_trouble_title}}</a>)</b></label>
												  	<select name="thread_status" ng-model="advact_trouble_status" class="form-control">
												  		<option value="NEW" selected>New</option>
												  		<option value="DONE">Done</option>
												  		<option value="WIP">WIP</option>
												  		<option value="CANCELLED">Cancelled</option>
												  	</select>
												</div>
												<div class="col-md-6" ng-show="advact_trouble_status == 'DONE' || advact_trouble_status == 'CANCELLED'" id="advact_trouble_status">
												  <label><b>Trouble Result</b></label>
												  	<select name="thread_status" ng-model="advact_trouble_result" class="form-control">
												  		<option value="OK" selected>OK</option>
												  		<option value="KO">KO</option>
												  	</select>
												</div>
											</div>
											<div class="col-md-12" ng-show="advact_trouble_exist == 'YES'">
												<hr />
											</div>
											<div class="col-md-12">
												<div class="col-md-6" ng-show="(advact_thread_status!='CLOSED' && advact_thread_status!='CANCELLED') && (advact_open_activities_count == 0)">
													<div class="col-md-12">
														<label><b>Would you like to create a activity</b></label>
													  	<select name="activity" class="form-control"  ng-model="new_activity" >
													  		<option ng-repeat="process in advact_current_activities" value="{{process.key}}">{{process.value}}</option>
													  	</select>
													</div>
												</div>
												<div class="col-md-6" ng-show="(advact_thread_status=='CLOSED' || advact_thread_status=='CANCELLED') && ( advact_trouble_status != 'DONE' && advact_trouble_status != 'CANCELLED')">
													<div class="col-md-12">	
													  	<label><b>Would you like to create a new process</b></label>
													  	<select name="process" class="form-control" ng-model="new_process" ng-change="reloadProcessActivity()" >
													  		<option ng-repeat="process in advact_processes" value="{{process.key}}">{{process.value}}</option>
													  	</select>
													</div>	
													<div class="col-md-12">	
													  	<label><b>Request</b></label>
													  	<select name="activity" class="form-control"  ng-model="new_request" >
													  		<option ng-repeat="process in advact_process_rel_activity" value="{{process.key}}">{{process.value}}</option>
													  	</select>
													</div>	
												</div>
											</div>
						      			</div>
						      			<br>
						      				<div class="col-md-12">
												<button class="btn btn-primary pull-left" type="button" name="advact_save" ng-disabled="((advact_thread_status == undefined) || ((advact_thread_status == 'CANCELLED' || advact_thread_status == 'CLOSED') && advact_thread_result == undefined) || ((advact_trouble_status == 'CANCELLED' || advact_trouble_status == 'DONE') && advact_trouble_result == undefined) || (new_process!=undefined && new_request==undefined))" ng-click="saveAdvancedActivity()">Done</button>
												<button class="btn btn-primary pull-right" type="button" name="advact_exit" ng-click="exitAdvancedActivity()">Do Nothing</button>
											</div>
						      			</form>
						      		</div>
						      	</div>
						    </div>
						</div>
			</section>
		</div>
	</div>
</div>