<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-building-o"></i> New Process
			</h3>
		</div>
		<!-- /.widget-header -->


		<div class="widget-content">
			<section id="companies">
						<?= $this->form_builder->open_form(array('action' => '','enctype' => 'multipart/form-data')); ?>
						<ul id="myTab" class="nav nav-tabs">
					<li class="active"><a href="#general" data-toggle="tab">General</a></li>
					<li class=""><a href="#form_process" data-toggle="tab">Form</a></li>
					<li class=""><a href="#status" data-toggle="tab">Status</a></li>
					<li class="disabled"><a href="javascript:void(0);"
						data-toggle="tab">Other variables</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active fade in" id="general">
				            	<?= $form_general; ?>
				            	<div class="form-group hidden"
							id="fast_thread_view_container">
							<div class="col-md-offset-2 col-md-8">
								<label for="fast_thread_view" class="col-md-2 control-label">View</label>
								<div class="col-md-9">
									<input name="fast_thread_view" value="" id="fast_thread_view"
										label="Fast thread view" placeholder="Fast thread view"
										class="form-control" type="text">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<button type="submit" class="btn btn-success">Save</button>
								<a href="<?=site_url('/admin/setup_processes')?>"
									class="btn btn-default">Cancel</a>
							</div>
						</div>
					</div>
					<div class="tab-pane fade in" id="form_process">
								<?= $form_form; ?>
								<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<button type="submit" class="btn btn-success">Save</button>
								<a href="<?=site_url('/admin/setup_processes')?>"
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
								<a href="<?=site_url('/admin/setup_processes')?>"
									class="btn btn-default">Cancel</a>
							</div>
						</div>
					</div>
				</div> 
						<?= $this->form_builder->close_form(); ?>
					</section>
		</div>
	</div>