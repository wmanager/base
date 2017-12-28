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

									<label class="col-md-3 control-label" for="title">Tipo</label>
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
									<label class="col-md-3 control-label" for="title">Descrizione</label>
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
											ng-click="upload()">Carica</button>
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
										<small>{{file.description}}<br> Caricato il
											{{file.created.substring(0,16)}} da {{file.first_name}}
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
									class="label label-info ng-binding">Esito {{thread.result}}</span>
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
			</section>
		</div>
	</div>
</div>