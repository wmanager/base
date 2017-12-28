<tabset class="act_detail"
	ng-if="!is_thread_view || activity.is_request == 't'"
	ng-init="isActive=true"> <tab heading="Activities"
	ng-if="activity.is_request == 'f' || !activity.is_request"
	active="isActive"> <alert ng-show="request.master_status=='PENDING'"
	type="warning">Warning! The Thread is in PENDING status, it is not you can make changes.</alert>
<div ng-include="activity_summary+'/'+activity.id"></div>
<alert type="danger" ng-if="forms[$index].errors">{{forms[$index].errors}}</alert>
<alert type="danger"
	ng-if="activity.timer_info.status != null && activity.timer_info.status == false">{{activity.timer_info.message}}{{activity.timer_info.message_extra}}</alert>
<alert ng-show="filedata.errors" type="danger">{{filedata.error}}</alert>
<div ng-include="activity.url"></div>
</tab> <tab heading="Request" ng-if="activity.is_request == 't'"
	active="isActive"> <alert ng-show="thread.master_status=='PENDING'"
	type="warning">Warning! The Thread is in PENDING status, it is not you can make changes.</alert>
<div ng-include="activity_summary+'/'+activity.id_thread"></div>
<alert type="danger" ng-if="forms[$index].errors">{{forms[$index].errors}}</alert>
<alert ng-show="filedata.errors" type="danger">{{filedata.error}}</alert>
<div ng-include="activity.url" ng-if="activity.is_request == 't'"></div>
</tab> <tab heading="Attachment"> <alert ng-show="filedata.errors"
	type="danger">{{filedata.error}}</alert>
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
					ng-click="upload(activity.id_thread,activity.id)">Carica</button>
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
					<a href="{{file.link}}" target="_blank">{{file.filename}}</a>
				</p>
				<small>{{file.description}}<br> Uploaded the
					{{file.created.substring(0,16)}} from {{file.first_name}}
					{{file.last_name}}
				</small>
			</div>
			<div class="col-md-1" ng-if="activity.status!='DONE'">
				<a href="#"
					ng-click="deleteFile(file.id,file.thread_id,file.activity_id,$parent.$index)"><i
					class="fa fa-trash"></i></a>
			</div>
		</div>
	</div>
</div>
<div ng-show="filedata.busy"
	style="width: 100px; height: 50px; margin: auto;">
	<i class="fa fa-4x fa-spinner fa-pulse"></i>
</div>
</tab> <!--<tab heading="Annulla attività" ng-if="thread.draft=='t'">
		<p><b>Vuoi annullare l'attività?</b><br>Procedendo tutti i dati inseriti verranno eliminati definitivamente.</p>
		<button type="button" class="btn btn-danger" ng-click="annullaThread(thread.id)">Annulla attività</button>
	</tab>--> <tab heading="Pending">
<div ng-include="activity_pending"></div>
</tab> <tab heading="Note">
<div ng-include="activity_followup"></div>
</tab> <tab heading="Trouble" ng-if="trouble_id">
<div ng-include="activity_trouble"></div>
</tab> <tab heading="Activity correlate" ng-if="is_thread_view">
<div ng-include="activity_related"></div>
</tab> <tab heading="Process" ng-if="is_thread_view">
<div ng-include="process_list"></div>
</tab> 
</tab> <!--
	<tab heading="Variables">
	</tab>
	<tab heading="History">
	</tab>--> </tabset>

<!--<div ng-if="roles.indexOf(activity.role) == -1 && (processdata.role_can_create!='CRM' && roles.indexOf('CRM') < -1)" ng-include="activity_summary"></div>-->

<tabset class="act_detail"
	ng-if="is_thread_view && activity.is_request == 'f'">
<tab heading="Activities">
<div ng-include="activity_summary+'/'+activity.id_thread"></div>
</tab></tabset>
