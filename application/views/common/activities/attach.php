
<div ng-repeat="file in attachments" class="col-md-4">
	<!-- <p><b>{{file.attachment_type}}</b></p>-->
	<div class="row-fluid">
		<div class="col-md-1">
			<i class="fa fa-file-o"></i>
		</div>
		<div class="col-md-10">
			<p>
				<b>{{file.attachment_type}}</b>
			</p>
			<p>
				<a href="{{file.link}}">{{file.filename}}</a>
			</p>
			<small> Created on {{file.created.substr(0,10)}} by
				{{file.first_name}} {{file.last_name}} </small>
		</div>

	</div>
</div>

<div ng-repeat="collection in collections" class="col-md-4">
	<div class="row-fluid">
		<div class="col-md-1">
			<i class="fa fa-file-o"></i>
		</div>
		<div class="col-md-10">
			<p>
				<b>{{collection.title}}</b>
			</p>
			<p>
				<a
					href="/common/attachments/download_collection/{{collection.collection_id}}/{{collection.thread}}/{{collection.form_id}}">{{collection.filename}}</a>
			</p>

		</div>

	</div>
</div>
