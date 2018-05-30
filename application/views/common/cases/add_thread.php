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

						echo '<h3 style="color:white;">New Thread</h3>';

				   ?>

	              
	            </div>
		<!-- /.widget-header -->
		<div class="widget-content">
			<section id="trouble" ng-controller="NewRequest"
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
					<div class="row" ng-form="threadForm">
						<div class="col-md-6">
							<label><b>Select Process Type</b></label>
							
								<select class="form-control" name="process_key" ng-model="selected.process_key" ng-change="getRequest()" required/>
									<option ng-repeat="process in processKeys" ng-value="process.key">{{process.title}}</option>
								</select>
							
						</div>
						<div class="clearfix"></div>
						<div class="col-md-6">
							<label><b>Select Request</b></label>
							<select class="form-control" name="request_key" ng-model="selected.request_key" required/>
								<option ng-repeat="request in requestKeys" ng-value="request.key">{{request.title}}</option>
							</select>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
				<hr>
				<div class="col-md-12">
					<div class="form-group col-md-12">
						<button type="button" class="btn btn-success" id="create_thread"
							ng-click="createThread();" ng-disabled="threadForm.$invalid">Save</button>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>
