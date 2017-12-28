<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-building-o"></i> Add Form
			</h3>
		</div>
		<!-- /.widget-header -->

		<div class="widget-content">
			<section id="companies">
						<?= $this->form_builder->open_form(array('action' => '','enctype' => 'multipart/form-data')); ?>
			            <ul id="myTab" class="nav nav-tabs">
					<li class="active"><a href="#general" data-toggle="tab">General</a></li>
					<li class="disabled"><a href="javascript:void(0);">Attachment</a></li>
					<li class="disabled"><a href="javascript:void(0);">Plichi</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active fade in" id="general">
				            	<?= $form_form; ?>
				            	<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<button type="submit" class="btn btn-success">Save</button>
								<a href="<?=site_url('/admin/setup_form');?>"
									class="btn btn-default">Cancel</a>
							</div>
						</div>
					</div>
				</div>
						<?= $this->form_builder->close_form(); ?>
					</section>

		</div>
	</div>