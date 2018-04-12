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
</tab> 
<tab heading="Request" ng-if="activity.is_request == 't'" active="isActive"> <alert ng-show="thread.master_status=='PENDING'" type="warning">Warning! The Thread is in PENDING status, it is not you can make changes.</alert>
	<div ng-include="activity_summary+'/'+activity.id_thread"></div>
	<alert type="danger" ng-if="forms[$index].errors">{{forms[$index].errors}}</alert>
	<alert ng-show="filedata.errors" type="danger">{{filedata.error}}</alert>
	<div ng-include="activity.url" ng-if="activity.is_request == 't'"></div>
</tab> 
<tab heading="Attachment" ng-if="!is_thread_view">
	<div attachment attachment-refid='activity.id' attachment-refkey="ACTIVITY" custid='activity.account.id || activity.customer.id' beid='activity.be.id'></div>
</tab>
<tab heading="Attachment" ng-if="is_thread_view">
	<div attachment attachment-refid='activity.id_thread' attachment-refkey="THREAD" custid='activity.account.id || activity.customer.id' beid='activity.be.id'></div>
</tab> 

<tab heading="Note">
<div ng-include="activity_followup"></div>
</tab> 
<tab heading="Trouble" ng-if="trouble_id">
	<div ng-include="activity_trouble"></div>
</tab> 
<tab heading="Activity correlate" ng-if="is_thread_view">
	<div ng-include="activity_related"></div>
</tab> 
<tab heading="Process" ng-if="is_thread_view">
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
