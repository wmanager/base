<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-cubes"></i> New Product
			</h3>
		</div>
		<!-- /.widget-header -->


		<div class="widget-content">
			<section id="products">
				<?= $this->form_builder->open_form(array('action' => '','enctype' => 'multipart/form-data')); ?>
				<div class="row">
					
					<?= $form_add; ?>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<button type="submit" class="btn btn-success">Save</button>
							<a href="/admin/setup_products" class="btn btn-default">Cancel</a>
						</div>
					</div>			
				</div>
			</section>
		</div>
	</div>