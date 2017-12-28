<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-building-o"></i> Edit Form
			</h3>
		</div>
		<!-- /.widget-header -->

		<div class="widget-content">
			<section id="companies">
						<?= $this->form_builder->open_form(array('action' => '','enctype' => 'multipart/form-data')); ?>
			            <ul id="myTab" class="nav nav-tabs">
					<li class="active"><a href="#general" data-toggle="tab">General</a></li>
					<li class=""><a href="#attachment" data-toggle="tab">Attachment</a></li>
					<li class=""><a href="#plichi" data-toggle="tab">Collections</a></li>
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
					<div class="tab-pane fade in" id="attachment">
						<div class="filters pull-left"></div>
						<div class="actions pull-right">
							<a href="javascript:void(0);"
								class="btn btn-default <?php if(count($unused_attachments) >0){ echo 'fade in'; }else{ echo 'fade';}?>"
								id="add_attachment" data-ref="<?php echo $form_id; ?>"><i
								class="fa fa-plus-circle"></i>Add</a>
						</div>
						<div class="clearfix"></div>
				            	<?= $form_attachment; ?>
				            	<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<button type="submit" class="btn btn-success">Save</button>
								<a href="<?=site_url('/admin/setup_form');?>"
									class="btn btn-default">Cancel</a>
							</div>
						</div>
					</div>
					<div class="tab-pane fade in" id="plichi">
							<?= $form_collection; ?>
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