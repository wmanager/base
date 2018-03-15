<script type="text/ng-template" id="customerTpl.html">
   <a tabindex="-1">
   	<span>{{match.model.first_name}} {{match.model.last_name}}</span><br>
   	<span>{{match.model.code}}</span>
   </a>
</script>

<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>New Case</h3>
		</div>
		<!-- /.widget-header -->


		<div class="widget-content">
			<section id="thread" ng-controller="Thread"
				ng-init="process=<?=$this->uri->segment(4)?>">

				<div ng-hide="selected.customer">
					<label class="control-label">Cliente</label> <input type="text"
						ng-model="selected.customer_model"
						typeahead="c for c in getCustomers($viewValue)"
						typeahead-on-select="setCustomer($item)"
						typeahead-template-url="customerTpl.html" class="form-control">
				</div>
				<div ng-show="selected.customer">
					<h5>
						<i class="fa fa-user"></i> <b>Client Details</b>
					</h5>
					<p>
						<a href="#" ng-click="resetCustomer();">{{selected.customer.first_name}}
							{{selected.customer.last_name}}</a> <a href="#"
							ng-click="resetCustomer();">{{selected.customer.code}}</a>
						<br> <span ng-show="selected.customer.indirizzo">{{selected.customer.address}}
							{{selected.customer.city}} {{selected.customer.state}},
							{{selected.customer.country}}<br> {{selected.customer.zip}}
							{{selected.customer.province}}
						</span>
					</p>

					<label><b>Select the business entity</b></label>
					<div class="radio" ng-repeat="contract in contracts">
						<label> <input type="radio" name="contract"
							ng-model="selected.contract" ng-value="contract.be_id" />{{contract.be_code}}
							- {{contract.address}} {{contract.state}} {{contract.country}}
						</label>
					</div>
					<div class="radio">
						<label> <input type="radio" name="contract"
							ng-model="selected.contract" value="" />generic
						</label>
					</div>
				</div>
				<div ng-show="selected.customer" class="container">
					<div class="row">
						<div class="col-md-1">
							<label class="control-label"><b>Type</b></label>
						</div>
						<div class="col-md-8">
							<select class="form-control"
								ng-options="item.title for item in form_types"
								ng-model="selected.template">

							</select>
						</div>
						<div class="col-md-3">
							<button type="button" ng-disabled="!selected.template"
								class="btn btn-success" ng-click="createThread()">Proceed</button>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
				<hr>

				<div ng-show="selected.customer && loadtemplate" class="row-fluid">
					<div class="col-md-8">
						<tabset class="request_detail"> <tab heading="Request">
						<div ng-include="selected.template.url"></div>
						</tab> <tab heading="Attachments"> <alert
							ng-show="filedata.errors" type="danger">{{filedata.error}}</alert>
						<div ng-hide="filedata.busy">
							<div>
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
										<button type="submit" class="btn btn-success"
											ng-click="upload(activity.id_thread)">Charge</button>
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
												href="/common/attachments/download_file/{{file.id}}/{{file.thread_id}}">{{file.filename}}</a>
										</p>
										<small>{{file.description}}<br> Uploaded the
											{{file.created.substring(0,16)}} from {{file.first_name}}
											{{file.last_name}}
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

						</tab> <tab heading="Followup"> </tab> <tab heading="Variables"> </tab>
						<tab heading="History"> </tab> </tabset>
					</div>
					<div class="col-md-4">
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
	</div>
</div>