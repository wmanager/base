<script type="text/ng-template" id="customerTpl.html">
   <a tabindex="-1">
   	<span>{{match.model.first_name}} {{match.model.last_name}}</span><br>
   	<span>{{match.model.code}}</span><br>   	
   </a>
</script>

<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header trouble-header">
			<i class="icon-pencil"></i>
	              <?php
															if ($this->uri->segment ( 4 )) {
																echo '<h3 style="color:white;">Edit Trouble #' . sprintf ( "%05d", $this->uri->segment ( 4 ) ) . '</h3>';
															} else {
																echo '<h3 style="color:white;">New Trouble</h3>';
															}
															?>

	              
	            </div>
		<!-- /.widget-header -->
		<div class="widget-content">
			<section id="trouble" ng-controller="Trouble"
				ng-init="trouble_id ='<?=$this->uri->segment(4)?>'">				
				<input type="hidden" id="showpop" value="{{selected.status}}" />
				<div ng-hide="selected.customer">
					<label class="control-label">Client</label> <input type="text"
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
						<a href="/common/accounts/detail/{{selected.customer.id}}"
							ng-click="resetCustomer();">{{selected.customer.first_name}}
							{{selected.customer.last_name}}</a> <br><a href="#"
							ng-click="resetCustomer();">{{selected.customer.code}}</a>
						<br> <span ng-show="selected.customer.address">{{selected.customer.address}}
							{{selected.customer.city}} {{selected.customer.state}},
							{{selected.customer.country}}<br>
							{{selected.customer.province}}
						</span>
					</p>

					<label><b>Select business entity</b></label>
					
					<div class="radio" ng-repeat="contract in contracts">
						<label> <input type="radio" name="contract"
							ng-model="selected.contract" ng-value="contract.be_id" />{{contract.be_code}}
						</label>
					</div>
					<!--<div class="radio">
								    		<label>
										       <input type="radio" name="contract" ng-model="selected.contract" value="" />generica
										    </label>
										</div>-->

					<a
						href="/common/troubles/cancel_trouble/<?php echo $this->uri->segment(4); ?>"
						class="delete-confirm"
						data-message=" Are you sure you want to cancel?"><span
						class="label label-warning ng-binding"
						ng-if="trouble_status && trouble_status == 'NEW'">Cancel trouble</span></a>
					<br>
				</div>
				<div class="clearfix"></div>
				<hr>
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
						<strong>Warning!</strong> This trouble was closed therefore you
						will not be able to edit the data.
					</div>
				</div>
				<div ng-show="selected.contract" class="row-fluid">
					<div class="col-md-12">
						<tabset class="request_detail"> <tab heading="Generale">
						<div ng-form="troubleForm">
							<div class="form-group col-md-12">
								<label class="control-label"><b>Trouble</b></label> <select
									class="form-control" ng-required="true"
									ng-options="item.id as item.title for item in form_types"
									ng-model="selected.type" ng-disabled="trouble_id"
									ng-change="loadSubtype()">
								</select>
							</div>
							<div ng-show="selected.type && form_subtypes.length > 0"
								class="form-group col-md-12">
								<label class="control-label"><b>Trouble Subtype</b></label> <select
									class="form-control"
									ng-required="selected.type && form_subtypes.length > 0"
									ng-options="item.key as item.value for item in form_subtypes"
									ng-model="selected.subtype">
								</select>
							</div>

							<div ng-show="be_contratti.length > 0"
								class="form-group col-md-12">
								<label class="control-label"><b>Contract</b></label> <select
									class="form-control"
									ng-options="item.id as item.value for item in be_contratti"
									ng-model="selected.be_contratti">
								</select>
							</div>

							<div class="form-group col-md-12">
								<label class="control-label"><b>Description</b></label>
								<textarea class="form-control" ng-required="true"
									ng-model="selected.description"></textarea>
							</div>							
							<div class="clearfix"></div>
							<div class="form-group col-md-6"
								ng-show="selected.show_role_company">
								<label><b>Responsible for resolution (troubleshooting)</b></label>
								<select class="form-control" ng-required="false"
									ng-options="item.key as item.key for item in setup_roles"
									ng-model="selected.res_roles">
									<option value="">Select role</option>
								</select>
							</div>
							<div class="clearfix"></div>
							<div class="form-group col-md-6"
								ng-show="selected.show_role_company">
								<label>&nbsp;</b></label> <select class="form-control"
									ng-required="false"
									ng-options="item.id as item.name for item in resolution_companies"
									ng-model="selected.duty_company_resolution">
									<option value="">Select company</option>
								</select>
							</div>
							<div class="form-group col-md-6"
								ng-show="selected.show_role_company">
								<br> <select class="form-control" ng-required="false"
									ng-options="item.id as item.user_name for item in resolution_users"
									ng-model="selected.duty_user_resolution">
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
										ng-model="selected.deadline" name="start_data"> <span
										class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>
							</div>							
							<div class="clearfix"></div>
							<div class="form-group col-md-6">
								<label><b>Status</b></label> <select class="form-control"
									ng-required="true"
									ng-options="item.key as item.label for item in form_status"
									ng-model="selected.status">
								</select>
							</div>

							<div class="clearfix"></div>
							<div class="form-group col-md-6" ng-if="selected.status=='DONE'">
								<label><b>Result</b></label> <select class="form-control"
									ng-model="selected.result" ng-required="true">
									<option value="">Select Result</option>
									<option value="OK">OK</option>
									<option value="KO">KO</option>
								</select>
							</div>
							<div class="form-group col-md-12">
								<button type="button" class="btn btn-success" id="save_trouble"
									ng-click="saveTrouble();" ng-disabled="troubleForm.$invalid"
									ng-hide="trouble_status == 'DONE' || trouble_status == 'CANCELLED'">Save</button>
							</div>
						</div>
						</tab> <tab heading="Attachments" disable="locked"> <alert
							ng-show="filedata.errors" type="danger">{{filedata.error}}</alert>
						<div ng-hide="filedata.busy">
							<div>
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
											ng-click="upload(activity.id_thread)"
											ng-hide="selected.status == 'CANCELLED' || selected.status == 'DONE'">Save</button>
									</div>
								</div>
								<div class="clearfix"></div>
								<hr />
							</div>
							<div ng-repeat="file in listfiles">
							
								<div class="row-fluid">
									<div class="col-md-1">
										<i class="fa fa-file-o"></i>
									</div>
									<div class="col-md-8">
										<p>
											<a href="{{file.link}}">{{file.filename}}</a>
										</p>
										<small>{{file.description}}<br> Created on
											{{file.created.substring(0,16)}} by {{file.first_name}}
											{{file.last_name}}
										</small>
									</div>
									<div class="col-md-1" ng-if="activity.status!='DONE'">
										<a href="#" ng-click="deleteFile(file.id,file.trouble_id)"><i
											class="fa fa-trash"></i></a>
									</div>
								</div>
							</div>
						</div>
						<div ng-show="filedata.busy"
							style="width: 100px; height: 50px; margin: auto;">
							<i class="fa fa-4x fa-spinner fa-pulse"></i>
						</div>

						</tab> 
						<tab heading="Task" disable="locked || trouble_status  == 'DRAFT'">
							<div ng-include="manual_process"></div>
						</tab>
						<tab heading="Related Process" disable="locked">
						<div ng-include="activity_related"></div>
						</tab> <tab heading="Note" disable="locked">
						<div ng-include="activity_followup"></div>
						</tab> 
					</div>

				</div>



			</section>
		</div>
	</div>
</div>
<?php
if ($this->uri->segment ( 4 )) {
	?>
<script>
$(window).on('beforeunload', function(){	
	if($('#showpop').val() == 'DRAFT') {
	    return "You have unsaved changes!";
	}
});

</script>
<?php } ?>