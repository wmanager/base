<div class="col-md-12">
	<div class="widget stacked ">
		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-building-o"></i> Add Activity
			</h3>
		</div>
		<!-- /.widget-header -->

		<div class="widget-content">
			<section id="companies">
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
					<li class="disabled"><a href="javascript:void(0);">Form</a></li>
					<li class="disabled"><a href="javascript:void(0);">Status</a></li>
					<li class="disabled"><a href="javascript:void(0);">Other variables</a></li>
					<li class="disabled"><a href="javascript:void(0);">Exit scenarios</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active fade in" id="general">
						<div id="is_workorder_message" class="alert"></div>
	            	<?= $form_general; ?>
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