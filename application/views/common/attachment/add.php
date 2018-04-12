
<alert ng-show="filedata.errors" type="danger">{{filedata.error}}</alert>
<alert ng-show="filedata.success" type="success">{{filedata.success}}</alert>
<div ng-hide="filedata.busy">
	<div>
           	 	<div class="form-group" ng-if="!setup_attach_hidden">
           	 		<label class="col-md-3 control-label" for="title">Tipo</label>
            		<div class="col-md-9">
	            		<select name="attach_type" ng-model="filedata.attach_type" class="form-control">
							<option ng-repeat="file in setup_attach_list" value="{{file.id}}" >{{file.title}} {{file.required == 't' ? '*' : ''}}</option>							
						</select>
            		</div>
            	</div>
            	<div class="clearfix"></div>
            	<br>
		<div class="form-group">
			<label class="col-md-3 control-label" for="title">Descrizione</label>
			<div class="col-md-9">
				<input type="text" class="form-control" label="Description"
					id="description" name="description" ng-model="filedata.description">
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
					ng-click="upload()">Upload Attachment</button>
			</div>
		</div>
		<div class="clearfix"></div>
		<hr />
	</div>
	<div ng-repeat="file in listfiles" style="padding:15px 0; border-bottom:1px solid #ccc;">
		<div class="row-fluid">
			<div class="col-md-1">
				<i class="fa fa-file-o fa-2x"></i>
			</div>
			<div class="col-md-8">
				<p>
					<a href="{{file.link}}"><strong>{{file.filename}}</strong></a>
				</p>
				<p>
					<strong>Type:</strong> {{file.attachment_type}}
				</p>
				<small>{{file.description}}<br> Created on
					{{file.created.substring(0,16)}} by {{file.first_name}}
					{{file.last_name}}
				</small>
			</div>
			<div class="col-md-1">
				<a href="#" ng-click="deleteFile(file.id,file.reference_id)"><i
					class="fa fa-trash" style='color:#f23617;'></i></a>
			</div>
		</div>
	</div>
</div>
<div ng-show="filedata.busy"
	style="width: 100px; height: 50px; margin: auto;">
	<i class="fa fa-4x fa-spinner fa-pulse"></i>
</div>
