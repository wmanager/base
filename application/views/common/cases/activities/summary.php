<!-- SUMMARY -->
<div class="well">
	<div class="row">
		<div class="col-md-6">
			<h5>
				<b>{{activity.description}}</b>
			</h5>
			<p>
				<b>{{activity.title}}</b><br> <small>Created by
					{{activity.creator_first_name}} {{activity.creator_last_name}} on
					{{activity.created | dateToISO | date : "dd-MM-yyyy H:mm"}}</small><br>
				<small ng-if="activity.modified_by">Modified by
					{{activity.modifier_first_name}} {{activity.modifier_last_name}} on
					{{activity.modified | dateToISO | date : "dd-MM-yyyy H:mm" :
					'GMT+1'}}</small>
			</p>
			<p>
				<span
					ng-if="activity.duty_operator && activity.duty_operator!='<?=$this->ion_auth->user()->row()->id?>'"><small>Created by :<br>{{activity.duty_first_name}}
						{{activity.duty_last_name}}
				</small></span>
				<!--<span ng-if="!activity.duty_operator || activity.duty_operator=='<?=$this->ion_auth->user()->row()->id?>'"><label><input type="checkbox" ng-model="forms[$index].duty_operator" ng-value="'<?=$this->ion_auth->user()->row()->id?>'"> Assegna a me</label></span>-->
			</p>


			<a
				ng-if="activity.is_request=='f' && (roles.indexOf(activity.role) != -1 || roles.indexOf('ADMIN') != -1)"
				href="/common/activities/detail/{{activity.id}}"
				class="btn btn-sm btn-primary">Activity detail</a> <a
				ng-if="activity.is_request=='t' && !is_thread_view && (roles.indexOf(activity.role) != -1 || roles.indexOf('ADMIN') != -1)"
				href="/common/cases/edit/{{activity.id_thread}}"
				class="btn btn-sm btn-primary">Process detail</a>
		</div>
		<div class="col-md-3">
			<p>
				<span class="label label-primary">Status {{activity.status}}</span>
			</p>
			<p>
				<span ng-if="activity.result" class="label label-primary">Result
					{{activity.result}}</span> <span ng-if="!activity.result"
					class="label label-default">Result N/D</span>
			</p>
			<p>
			
			
			<h4>
				<span class="label label-info">{{activity.role}}</span>
			</h4>
			</p>


		</div>
		<div class="col-md-3">
			<div class="row">
				<div class="col-md-6" style="text-align: center">
					<i class="fa fa-2x fa-file-pdf-o"></i>
					<p>
						<small>0 attachments</small>
					</p>
				</div>
				<div class="col-md-6" style="text-align: center">
					<i class="fa fa-2x fa-comments"></i>
					<p ng-if="is_thread_view">
						<small><?=$this->followup->countfollowups('THREAD',$this->uri->segment(4))?> follow-up</small>
					</p>
					<p ng-if="!is_thread_view">
						<small><?=$this->followup->countfollowups('ACTIVITY',$this->uri->segment(4))?> follow-up</small>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>