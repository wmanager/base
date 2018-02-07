<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>Add Extensions</h3>
		</div>
		<!-- /.widget-header -->


		<div class="widget-content">
			<section id="extension">
		<div>
		    <div ng-controller="extentionCtrl">
		    <div class="row">
			    <div class="btn-group-input">
			    	<div class="input-group col-md-12">
	                    <input type="text" class="form-control" ng-model="searchText" placeholder="Search" type="search" ng-change="search()" />
	                    <span class="input-group-btn">
	                        <button class="btn btn-info" type="button">
	                            <i class="glyphicon glyphicon-search"></i>
	                        </button>
	                    </span>
	                </div>
	              </div>
		    
		        <!-- <div class="input-group">
		            <input class="form-control" ng-model="searchText" placeholder="Search" type="search" ng-change="search()" /> 
		            <span class="input-group-addon">
		      			<span class="glyphicon glyphicon-search"></span>
					</span>
				</div> -->
		    </div>		   
		        <br>		      
		        <div class="row">		        
		        	<div class="col-md-12">			
						<div class="notice notice-lg" ng-repeat="item in ItemsByPage[currentPage] | orderBy:columnToOrder:reverse">	
						    	<div class="col-md-8">
						    	
						    		<strong>{{item.name}}</strong><br>					    		
						       		<small>{{item.title}}</small><br>
						       		<small>Size : {{item.file_size}}</small><br>
						       		<small><a ng-href="http://marketplace/extensions/details/{{item.id}}">Know more</a></small>	
								</div>
						    	<div class="col-md-4">
									<!--<div style="margin-top:5px;" class="pull-right text-right">
										<div class="text-center pull-right" id="spinner_{{item.id}}" style="display:none;"><span><i class="fa fa-spinner load-animate fa-2x"></i></span><br><small>Downloading ...</small></div>
										<div class="text-center" id="install_spinner_{{item.id}}" style="display:none;"><span><i class="fa fa-spinner load-animate fa-2x"></i></span><br><small>Installing ...</small></div>
								    	<span class="badge badge-secondary" ng-hide="item.installed || item.downloaded" id="downloding_{{item.id}}" ng-if="item.free=='t'"><a class="ext" href="#" ng-click="download_file(item.file_name, item, item.key)">Download</a></span>							    								    	
								    	<a href="#" data-toggle="modal" data-target="#myModal"><span class="badge badge-secondary" ng-hide="item.installed || item.downloaded"  ng-if="item.free=='f'" ng-click="set_item(item);"> Buy Now</span></a>								   
								    	<form enctype="multipart/form-data" method="post" id="myForm_{{item.id}">
								    		<input id="upload_{{item.id}}" data-item="{{item.id}}" type="file" name="extention_file" style="display:none" accept=".zip" onchange="triggerFileChange(this, angular.element(this).scope().item);"/>
										</form>
								    	
								    	<div class="pull-right text-right" ng-hide="item.installed" id="install_{{item.id}}" style="display:none">
								    	<span class="badge badge-secondary"><a class="ext" href="#" ng-click="triggerFileUpload(item);">Install</a></span><br><small>(Please add the {{item.file_name}} file to install)</small><br><small><a href="#" ng-click="download_file(item.file_name, item, item.key)">Re-Download</a></small></div>
								    	
								    	<div class="pull-right text-right" ng-hide="item.installed" ng-if="item.downloaded" id="downloaded_{{item.id}}">
								    	<span class="badge badge-secondary"><a class="ext" href="#" ng-click="triggerFileUpload(item);"> Install</a></span><br><small>(Please add the {{item.file_name}} file to install)</small><br><small><a href="#" ng-click="download_file(item.file_name, item, item.key)">Re-Download</a></small></div>
								    	
								    	<span class="badge label-primary" ng-show="item.installed">Installed</span>
								    	<span class="badge label-primary" id="installed_{{item.id}}" style="display:none">Installed</span>
								    	<br><small id="success_{{item.id}}"></small>
								    	<small id="error_{{item.id}}"></small>								    																															
								    </div>  -->
								    <div style="margin-top:5px;" class="pull-right text-right">								   
								    	<div class="pull-right text-right">
								    	<a ng-hide="item.installed" href="#" data-toggle="modal" data-target="#myModal">
								    		<span class="badge badge-btn badge-secondary" ng-click="set_item(item);"> Install</span>
								    	</a>
								    	<a ng-hide="!item.installed" href="#" data-toggle="modal" data-target="#myModal">
								    		<span class="badge badge-btn badge-secondary" ng-click="set_item(item);"> Download</span>
								    	</a>
								    	<span ng-show="item.installed" class="badge badge-btn label-primary">Installed</span>							    								   
								    </div>
								    </div>
								    
								</div>								
					    </div>						
					    <div class="col-sm-12 alert alert-info" ng-if="!ItemsByPage[currentPage]">
								No Extention found
						</div>
		        	</div>
				</div>		    
		        <ul class="pagination pagination-sm" ng-if="ItemsByPage.length > 1">
		            <li ng-class="{active:0}"><a href="#" ng-click="firstPage()">1</a>
		            </li>
		            <li ng-repeat="n in range(ItemsByPage.length)"> <a href="#" ng-click="setPage()" ng-bind="n+1">1</a>		
		            </li>
		            <li><a href="#" ng-click="lastPage()">{{ItemsByPage.length}}</a>
		            </li>
		        </ul>
		        <!-- Modal -->
				<div id="myModal" class="modal fade" role="dialog">
				  <div class="modal-dialog">
				
				    <!-- Modal content-->
				    <div class="modal-content">
				      <div class="modal-header" style="background-color: transparent !important; border: none; border-bottom: 1px solid #e5e5e5;">
				        <button type="button" class="close" data-dismiss="modal">&times;</button>
				        <h4 class="modal-title" ng-if="!selected_item.installed">Install Extension</h4>
				        <h4 class="modal-title" ng-if="selected_item.installed">Download Extension</h4>
				      </div>
				      <div class="modal-body">
				      	<div class="form-group">
						<form enctype="multipart/form-data" method="post" id="myForm_{{selected_item.id}">
							<input id="upload_{{selected_item.id}}" data-item="{{selected_item.id}}" type="file" name="extention_file" style="display:none" accept=".zip" onchange="triggerFileChange(this, angular.element(this).scope().selected_item);"/>
						</form>
								    	
				      	<form method="post" id="ext" name="ext" action="/api/service/download_file/">
				      		<div class="row">
				      		
								<input type="hidden" id="key" name="key" ng-value="selected_item.key"/>
				      			<input type="hidden" id="module_name" name="module_name" ng-value="selected_item.name"/>
				      			<input type="hidden" id="file_name" name="file_name" ng-value="selected_item.file_name"/>
				      			<input type="hidden" id="title" name="title" ng-value="selected_item.title"/>
				      			<div ng-if="selected_item.free=='f'">
						      			<div class="col-sm-5">
						      				<label>
						      					<h4><b>{{selected_item.name}} </b> is paid extension.</h4>
						      				</label>
						      				<div>
						      					<a href="http://marketplace/extensions/details/{{selected_item.id}}" target="_blank"><span class="badge badge-btn badge-primary"> Buy Now</span></a>
						      				</div>
						      			</div>
									<div class="col-sm-2 or">OR</div>
					      			<div class="col-sm-5">
					      				<label><h4>Enter the token to download the extention</h4></label>
					      				<div class="btn-group-input">
					      					<div class="input-group col-md-12">	
						                    	<input type="text" id="token" name="token" ng-model="token" placeholder="Enter token Value" class="form-control"> 	
						                    	<span class="input-group-btn">
						                        	<a class="download" href="#" ng-click="download_file(selected_item);"><span class="badge badge-btn badge-primary"> Download</span></a>
						                    	</span>
						                    </div>
							             </div>
							             <span class="spinner" style="display:none"> <i class="fa fa-spinner load-animate fa-2x"></i>
						      				<small>Downloading ...</small>
						      			</span>
						      			<small style="color:red;" class="error"></small>
						      			<small style="color:green;" class="success"></small>
					      			</div>
									<br>
					      			<br>
				      			</div>
				      			<div ng-if="selected_item.free=='t'">
					      			<div class="col-md-10"><label><h4><b>{{selected_item.name}}</b> is free extension. Proceed to download the extension.</h4></label></div>
									<div class="col-md-2">								
						      			<a class="download" href="#" ng-click="download_file(selected_item);"><span class="badge badge-btn badge-primary"> Download</span></a>						      			
						      				<span class="spinner" style="display:none"> <i class="fa fa-spinner load-animate fa-2x"></i>
						      				<small>Downloading ...</small></span>
						      			<small style="color:red;" class="error"></small>
						      			<small style="color:green;" class="success"></small>
									</div>
								</div>	     		
				      		</form>
						</div>
				        
				      </div>
				      <div class="modal-footer">
				        	<div class="col-md-12 text-center">
				      				<label class="install_{{selected_item.id}}" ng-hide="selected_item.installed"><h4>Upload <b>{{selected_item.file_name}}</b> file to install the extention:</h4>
				      				</label>
									<label ng-show="selected_item.installed"><h4><b>{{selected_item.file_name}}</b> is already installed</h4>
				      				</label>
				      			</div>
				      			<br>
				      			<br>
								<div class="col-md-12 text-center">									
									<a ng-hide="selected_item.installed" class="install_{{selected_item.id}}" class="ext" href="#" ng-click="triggerFileUpload(selected_item);">						    	
							    	<span class="badge badge-btn badge-secondary">
							    		Install
							    	</span>
							    	</a>
							    	<a ng-if="selected_item.installed" class="installed_{{selected_item.id}}" style="display:none">						    	
							    	<span class="badge badge-btn badge-primary" >
							    		Installed
							    	</span>
							    	</a>
							    	<span class="install_spinner" style="display:none"> <i class="fa fa-spinner load-animate fa-2x"></i>
						      				<small>Installing ...</small></span>
					      			<small style="color:red;" class="install_error"></small>
					      			<small style="color:green;" class="install_success"></small>								
								</div>
				      </div>
				    </div>
				
				  </div>
				</div>
		    </div>
		    <!-- Ends Controller -->
		</div>
			</section>
		</div>
	</div>

</div>