<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>Detail Case</h3>
		</div>
		<!-- /.widget-header -->


		<div class="widget-content">
			<section id="thread" ng-controller="Thread"
				ng-init="mode='edit';thread=<?=$thread?>;roles=<?=htmlspecialchars(json_encode(get_company_role()))?>">

				<div class="row-fluid">

					<!-- CUSTOMER/CONTRACT DETAILS AND REQUEST TYPE -->
					<div ng-show="selected.customer" class="col-md-8">
						<div class="well">
							<h5>
								<i class="fa fa-user"></i> <b>Client Details</b>
							</h5>

							<p>
								<b><a target="_blank"
									ng-href="/common/accounts/detail/{{selected.customer.id}}">{{selected.customer.first_name}}
										{{selected.customer.last_name}}</a></b>
								<br> {{selected.customer.address}}
								{{selected.customer.state}} {{selected.customer.city}},
								{{selected.customer.country}}<br> {{selected.customer.zip}}
								{{selected.customer.province}}
							</p>

							<p ng-repeat="item in selected.contract">
								<b>Contract:</b> {{item.contract_code}} {{item.contract_type}}
							</p>
							<p>
								<b>Type:</b> {{selected.template.title}}
							</p>
						</div>
					</div>

					<div class="col-md-4">
						<div class="well">
							<h4 style="color: #419641">
								<span class="label label-info ng-binding">Status
									{{thread.master_status}}</span>&nbsp; &nbsp;<span
									class="label label-info ng-binding">Esito {{thread.result}}</span>
							</h4>
							<small>{{thread.status}}</small> <br>
							<h5>{{activities.length}} Related Activities</h5>
							<p>
								<small>Last modified on {{thread.modified | date:'dd-MM-yyyy
									HH:mm'}}</small>
							</p>
							<h4 style="color: #419641" ng-if="thread.trouble_id">
								<a href="/common/troubles/edit/{{thread.trouble_id}}"><span
									class="label label-warning ng-binding">Trouble
										#{{('00000'+thread.trouble_id).slice(-'00000'.length)}}</span></a>
							</h4>
							<h4 style="color: #419641"
								ng-if="(thread.type.indexOf('INORDER') < 0) && (thread.master_status != 'ANNULATO') && (thread.master_status != 'CHIUSO')">
								<a href="/common/cases/thread_cancel/{{thread.id}}"><span
									class="label label-warning ng-binding">Cancel request</span></a>
							</h4>
						</div>
						<div class="well">
							<h5 class="pull-right">SLA</h5>
							<h6 style="color: #419641">{{remaining.days}} days
								{{remaining.hours}} hours {{remaining.minutes}} minutes</h6>
							<p>
								<small>This request was opened on {{date |
									date:'dd-MM-yyyy HH:mm'}}<br>and must be resolved by:<br>{{deadline
									| date:'dd-MM-yyyy hh:m'}}
								</small>
							</p>
						</div>
					</div>
				</div>
				<!-- REQUEST FORM LOADER -->
				
				<div ng-show="selected.customer && loadtemplate" class="row-fluid">
					<div class="col-md-12">

						<tabset class="request_detail"
							ng-if="activities[0].is_request=='f' || !activities.length"> <tab
							heading="Request">
						<div ng-include="selected.template.url"></div>
						</tab> <tab heading="Attachments"> <alert ng-show="filedata.errors"
							type="danger">{{filedata.error}}</alert>
						<div ng-hide="filedata.busy">
							<div>
								<div class="form-group">
									<label class="col-md-3 control-label" for="title">Tipo</label>
									<div class="col-md-9">
										<select name="attach_type" ng-model="filedata.attach_type"
											class="form-control">
											<option ng-repeat="file in filetypes" ng-value="file.id">{{file.title}}<span
													ng-if="file.required=='t'">*</span></option>
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
										<button type="submit" class="btn btn-success"
											ng-click="upload(activity.id_thread)">Save</button>
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
												href="{{file.link}}">{{file.filename}}</a>
										</p>
										<small>{{file.description}}<br> Uploaded the {{file.created}}
											from {{file.first_name}} {{file.last_name}}
										</small>
									</div>
									<div class="col-md-1" ng-if="activity.status!='DONE'">
										<a href="#" ng-click="deleteFile(file.id,file.thread_id)"><i
											class="fa fa-trash"></i></a>
									</div>
								</div>
							</div>
						</div>
						<div ng-show="filedata.busy"
							style="width: 100px; height: 50px; margin: auto;">
							<i class="fa fa-4x fa-spinner fa-pulse"></i>
						</div>

						</tab> <!--<tab heading="Risposte">
												</tab>
												<tab heading="Variabili">
												</tab>
												<tab heading="Storia">
												</tab>--> </tabset>
					</div>

				</div>
				<!-- ACTIVITIES LOADER -->
				<div class="clearfix"></div>
				<hr>
				<div ng-show="selected.customer && loadactivities"
					ng-init="is_thread_view = true; activity = activities[0]">
					<div ng-repeat="activity in activities track by $index">
						<div ng-include="activity_template" class="col-md-12"
							ng-if="activity.sidebar!='t' && activity.is_request=='t'"></div>
						<div ng-include="activity_template" class="col-md-8"
							ng-if="activity.sidebar=='t' && activity.is_request=='t'"></div>
						<div ng-include="activity_sidebar" class="col-md-4"
							ng-if="activity.sidebar=='t' && activity.is_request=='t'"></div>
					</div>
				</div>

			</section>
		</div>
	</div>
</div>