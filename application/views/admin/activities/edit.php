<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-building-o"></i> Edit Activity
			</h3>
		</div>
		<!-- /.widget-header -->

		<div class="widget-content">
			<section id="companies">
				<div id="transition_insert_message"></div>
				<?php
				if ($this->session->flashdata ( 'growl_error' )) {
					echo "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>×</a>" . $this->session->flashdata ( 'growl_error' ) . "</div>";
				} else if ($this->session->flashdata ( 'growl_success' )) {
					echo "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert'>×</a>" . $this->session->flashdata ( 'growl_success' ) . "</div>";
				}
				?>
				<?= $this->form_builder->open_form(array('action' => '','enctype' => 'multipart/form-data', 'id' => 'setup-activity-workorder-frm')); ?>
				<ul id="myTab" class="nav nav-tabs">
					<li class="active"><a href="#general" data-toggle="tab">General</a></li>
					<li class=""><a href="#form_process" data-toggle="tab">Form</a></li>
					<li class=""><a href="#status" data-toggle="tab">Status</a></li>
					<li class=""><a href="#others" data-toggle="tab">Other variables</a></li>
					<li class=""><a href="#exit_scenarios" data-toggle="tab">Exit
							scenarios</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active fade in" id="general">
						<div id="is_workorder_message" class="alert"></div>
		            	<?= $form_general; ?>
		            	<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<button type="submit" class="btn btn-success">Save</button>
								<a href="<?=site_url('/admin/setup_activities/'.$id_process);?>"
									class="btn btn-default">Cancel</a>
								<!-- <button id="is_workorder_create_button" class="btn btn-info">Create Onsite Report</button> -->
							</div>
						</div>
					</div>
					<div class="tab-pane fade in" id="form_process">
						<?= $form_form; ?>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<button type="submit" class="btn btn-success">Save</button>
								<a href="<?=site_url('/admin/setup_activities/'.$id_process);?>"
									class="btn btn-default">Cancel</a>
							</div>
						</div>
					</div>
					<div class="tab-pane fade in" id="status">
						<div class="filters pull-left"></div>
						<div class="actions pull-right">
							<a href="javascript:void(0);" class="btn btn-default"
								id="add_new_status" data-ref="status"><i
								class="fa fa-plus-circle"></i> Add</a>
						</div>
						<div class="clearfix"></div>
						<?= $form_status; ?>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<button type="submit" class="btn btn-success">Save</button>
								<a href="<?=site_url('/admin/setup_activities/'.$id_process);?>"
									class="btn btn-default">Cancel</a>
							</div>
						</div>
					</div>
					<div class="tab-pane fade in" id="others">
						<div class="filters pull-left"></div>
						<div class="actions pull-right">
							<a
								href="<?=site_url("admin/setup_activities/add_other_variable/$id_process/$activity_id"); ?>"
								class="btn btn-default" data-toggle="ajaxModal"><i
								class="fa fa-plus-circle"></i>Add</a>
						</div>
						<div class="clearfix"></div>
						<?= $form_other; ?>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<button type="submit" class="btn btn-success">Save</button>
								<a href="<?=site_url('/admin/setup_activities/'.$id_process);?>"
									class="btn btn-default">Cancel</a>
							</div>
						</div>
					</div>
					<div class="tab-pane fade in" id="exit_scenarios">
						<div class="filters pull-left"></div>
						<div class="actions pull-right">
							<a
								href="<?=site_url("admin/setup_activities/add_exit_scenario/$activity_id"); ?>"
								class="btn btn-default" data-toggle="ajaxModal"><i
								class="fa fa-plus-circle"></i>Add</a>
						</div>
						<div class="clearfix"></div>
						<?= $form_exit_scenarios; ?>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<button type="submit" id="setup-activity-workorder-save"
									class="btn btn-success">Save</button>
								<a href="<?=site_url('/admin/setup_activities/'.$id_process);?>"
									class="btn btn-default">Cancel</a>
							</div>
						</div>
					</div>
				</div>
				<?= $this->form_builder->close_form(); ?>
			</section>
		</div>
	</div>
</div>

<div id="transition-modal" class="modal fade in">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>