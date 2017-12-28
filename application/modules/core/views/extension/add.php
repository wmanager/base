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
		        <div class="input-group">
		            <input class="form-control" ng-model="searchText" placeholder="Search" type="search" ng-change="search()" /> 
		            <span class="input-group-addon">
		      			<span class="glyphicon glyphicon-search"></span>
					</span>
				</div>
		    </div>		   
		        <br>		        
		        <div class="row">		        
		        	<div class="col-md-12">		        	
						<div class="notice notice-lg" ng-repeat="item in ItemsByPage[currentPage] | orderBy:columnToOrder:reverse">	
						    	<div class="col-md-8">
						    	
						    		<strong>{{item.name}}</strong><br>					    		
						       		<small>{{item.description}}</small><br>
						       		<small>Size : {{item.file_size}}</small>						    	
								</div>
						    	<div class="col-md-4">
									<div style="margin-top:5px;" class="pull-right text-right">
										<div class="text-center pull-right" id="spinner_{{item.id}}" style="display:none;"><span><i class="fa fa-spinner load-animate fa-2x"></i></span><br><small>Downloading ...</small></div>
										<div class="text-center" id="install_spinner_{{item.id}}" style="display:none;"><span><i class="fa fa-spinner load-animate fa-2x"></i></span><br><small>Installing ...</small></div>
								    	<span class="badge badge-secondary" ng-hide="item.installed || item.downloaded" id="downloding_{{item.id}}"><a class="ext" href="#" ng-click="download_file(item.file_name, item)">Download</a></span>							    								    	
								    	
								    	<form enctype="multipart/form-data" method="post" id="myForm_{{item.id}">
								    		<input id="upload_{{item.id}}" data-item="{{item.id}}" type="file" name="extention_file" style="display:none" accept=".zip" onchange="triggerFileChange(this, angular.element(this).scope().item);"/>
										</form>
								    	
								    	<div class="pull-right text-right" ng-hide="item.installed" id="install_{{item.id}}" style="display:none">
								    	<span class="badge badge-secondary"><a class="ext" href="#" ng-click="triggerFileUpload(item);">Install</a></span><br><small>(Please add the {{item.file_name}} file to install)</small><br><small><a href="#" ng-click="download_file(item.file_name, item)">Re-Download</a></small></div>
								    	
								    	<div class="pull-right text-right" ng-hide="item.installed" ng-if="item.downloaded" id="downloaded_{{item.id}}">
								    	<span class="badge badge-secondary"><a class="ext" href="#" ng-click="triggerFileUpload(item);"> Install</a></span><br><small>(Please add the {{item.file_name}} file to install)</small><br><small><a href="#" ng-click="download_file(item.file_name, item)">Re-Download</a></small></div>
								    	
								    	<span class="badge label-primary" ng-show="item.installed">Installed</span>
								    	<span class="badge label-primary" id="installed_{{item.id}}" style="display:none">Installed</span>
								    	<br><small id="success_{{item.id}}"></small>
								    	<small id="error_{{item.id}}"></small>								    																															
								    </div> 
								</div>								
					    </div>						
					    <div class="col-md-4" ng-if="!ItemsByPage[currentPage]">
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
		    </div>
		    <!-- Ends Controller -->
		</div>
			</section>
		</div>
	</div>
</div>