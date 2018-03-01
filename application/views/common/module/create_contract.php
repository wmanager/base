<form name="contract_details">
	<div ng-controller="Contract"
		ng-init='company="<?=$this->ion_auth->user()->row()->id_company?>";roles=<?=htmlspecialchars(json_encode(get_company_role()));?>;
		<?php if($this->uri->segment(4)!='') echo "accountid=".$this->uri->segment(4) ?>;
		<?php if($this->uri->segment(5)!='') echo "be_id=".$this->uri->segment(5) ?>;
		<?php if($this->uri->segment(6)!='') echo "assets_id=".$this->uri->segment(6) ?>;'>
		<div class="clearfix"></div>
		<div class="col-md-9" ng-cloak>
			<div class="widget stacked ">
				<div class="widget-header">
					<i class="icon-pencil"></i>
					<h3>
						<i class="fa fa-magic"></i> Customer Contract
					</h3>
				</div>
				<!-- /.widget-header -->

				<div class="widget-content">
					<section id="caseswizard">

						
						<tabset> <tab heading="Validation">
							
						<alert ng-show="error_message" type="danger">{{error_message}}</alert>
						<alert ng-show="success_message" type="success">{{success_message}}</alert>						
						<alert ng-show="status_verifica==='YES'" type="info">Customer existing</alert> <alert ng-show="status_verifica==='NO'"
							type="info">New Client</alert>

									<?php
									if ($this->uri->segment ( 5 ) == 'new')
										echo '<alert type="success">The contract was created successfully, you can continue to fill in the data.</alert>';
									?>
									
					<ng-form name="forms.validationForm" ng-hide="busy"> 		
						<div class="form-group">
							<div class="col-md-6">
								<label>Account Type *</label>
									<select class="form-control"
											ng-model="form_data.account_type"
											name="account_type" validator="required">
											<option value="p">Personal</option>
											<option value="o">Organization</option>	
										</select>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<div class="col-md-6">
								<label><i class="fa fa-question-circle"
									popover-placement="right"
									popover="Unique identification number of the customer"
									popover-trigger="mouseenter"></i> UIC *</label>
								<input type="text" maxlength="16" class="form-control"
									style="text-transform: uppercase" name="code"
									ng-model="form_data.code" validator="required">
							</div>
						</div>
						
						<div class="clearfix"></div>

						<div class="form-group">
							<div class="col-md-6">
								<label>VAT *</label><input									
									maxlength="15" class="form-control" name="vat"
									ng-model="form_data.vat"
									validator="required">
							</div>
						</div>
						<div class="clearfix"></div>
						<br>
						<div class="form-group">
							<div class="col-md-12">
								<button type="submit" class="btn btn-success"
									ng-disabled="forms.validationForm.$invalid"
									ng-hide="disable_verifica===true || !disable_creation"
									validation-submit="forms.validationForm" ng-click="verify()">Verify</button>
								<button 
									class="btn btn-primary" ng-click="create_contract()"
									type="button" ng-hide="disable_creation === true || new_contract === true ">Create new contract</button>								
							</div>
						</div>
						</ng-form>
						<div ng-show="busy"
							style="width: 100px; height: 50px; margin: auto;">
							<i class="fa fa-4x fa-spinner fa-pulse"></i>
						</div>

						</tab>						
						<tab heading="Accounts" disabled="!disable_verifica">
							<h3>A) Customer Details</h3>							
										<div class="form-group">
											<div class="col-md-6">
												<label>First Name *</label>
												<input type="text" class="form-control" name="first_name" ng-model="form_data.account.first_name" validator="required">
											</div>
											<div class="col-md-6">
												<label>Last Name *</label>
												<input type="text" class="form-control" name="last_name" ng-model="form_data.account.last_name" validator="required">
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-6">
												<label>Account Type *</label>
												<div class="clearfix"></div>
												<select class="form-control"
													ng-model="form_data.account.account_type"
													name="account_type" validator="required" readonly>
													<option value="p">Personal</option>
													<option value="o">Organization</option>	
												</select>
											</div>
											<div class="col-md-6">
												<label>Code *</label>
												<input type="text" class="form-control" name="code" ng-model="form_data.account.code" validator="required">
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-6">
												<label>Tel</label>
												<input type="text" class="form-control" name="code" ng-model="form_data.contact.tel">
											</div>
										</div>
										<div class="clearfix"></div>
										<br>			
							<h3>B) Customer Address</h3>
									<div class="form-group">
											<div class="col-md-6">
												<label>Address </label>
												<textarea rows="3" style="width: 100%;" name="client_address" ng-model="form_data.client_address.address"></textarea>												
											</div>
											<div class="col-md-6">
												<label>City </label>
												<input type="text" class="form-control" name="client_city" ng-model="form_data.client_address.city">												
											</div>											
											
									</div>
									<div class="form-group">
											<div class="col-md-6">
												<label>State </label>
												<input type="text" class="form-control" name="client_state" ng-model="form_data.client_address.state">												
											</div>
											<div class="col-md-6">
												<label>Country </label>
												<input type="text" class="form-control" name="client_country" ng-model="form_data.client_address.country">
											</div>
									</div>
									<div class="form-group">
											<div class="col-md-6">
												<label>Zip </label>
												<input type="text" class="form-control" name="client_zip" ng-model="form_data.client_address.zip">												
											</div>											
									</div>												
						</tab>
						<tab heading="Assets" disabled="!disable_verifica">
							<h3>A) Business Data</h3>							
										<div class="form-group">
											<div class="col-md-6">
												<label>Be Code *</label>
												<input type="text" class="form-control" name="be_code" ng-model="form_data.be.be_code" validator="required" readonly>
											</div>
											<div class="form-group">
											<div class="col-md-6">
												<label>Product *</label>	
												<select class="form-control"  ng-model="form_data.asset.product_id" name="product_id" validator="required">
														<option ng-repeat="n in products" ng-value="n.id" ng-selected="form_data.asset.product_id == n.id">{{n.title}}</option>
												</select>
											</div>
											</div>
										</div>
										<div class="clearfix"></div>
										<br>										
						<h3>B) Invoice Details</h3>
									<div class="form-group">
											<div class="col-md-6">
												<label>Invoice Method *</label>
												<div class="clearfix"></div>
												<select class="form-control"
													ng-model="form_data.be.invoice_method"
													name="invoice_method" validator="required">
													<option value="email">Email</option>
													<option value="address">Address</option>	
												</select>
											</div>
											<div class="col-md-6" ng-show="form_data.be.invoice_method == 'email'">
												<label>Email </label>
												<input type="text" class="form-control" name="invoice_email" ng-model="form_data.be.email">												
											</div>											
										</div>
									<div class="form-group" ng-hide="form_data.be.invoice_method == 'email'">
											<div class="col-md-6">
												<label>Address </label>
												<textarea rows="3" style="width: 100%;" name="invoice_address" ng-model="form_data.invoice_address.address"></textarea>												
											</div>
											<div class="col-md-6">
												<label>City </label>
												<input type="text" class="form-control" name="invoice_city" ng-model="form_data.invoice_address.city">												
											</div>											
											
									</div>
									<div class="form-group" ng-hide="form_data.be.invoice_method == 'email'">
											<div class="col-md-6">
												<label>State </label>
												<input type="text" class="form-control" name="invoice_state" ng-model="form_data.invoice_address.state">												
											</div>
											<div class="col-md-6">
												<label>Country </label>
												<input type="text" class="form-control" name="invoice_country" ng-model="form_data.invoice_address.country">
											</div>
									</div>
									<div class="form-group" ng-hide="form_data.be.invoice_method == 'email'">
											<div class="col-md-6">
												<label>Zip </label>
												<input type="text" class="form-control" name="invoice_zip" ng-model="form_data.invoice_address.zip">												
											</div>											
									</div>
										<div class="clearfix"></div>
										<br>										
						</tab>
									
					</div>
		</div>
		<div class="clearfix"></div>
	</div>
	<div class="col-md-3" ng-cloak ng-show="accountid">	
		<div class="widget stacked widget-box">
			<div class="widget-content">			
				<label><small>Note : Please click on save to update the customer data. And for deleting the customer please click on detele</small></label>
				<br>
				<button type="button" class="btn btn-success" validation-submit="contract_details" ng-click="save()">Save</button>
				<button ng-bootbox-confirm="Are you sure you want to delete the customer?"  ng-bootbox-confirm-action="delete_module('/common/module_inorder/delete_client/<?=$this->uri->segment(4);?>/<?=$this->uri->segment(5);?>')" class="btn btn-danger">Delete</button>
			</div>
		</div>

</div>
</form>
