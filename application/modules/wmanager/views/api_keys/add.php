<div class="col-md-12">
	<div class="widget stacked ">

	            <div class="widget-header">
	              <i class="icon-pencil"></i>
	              <h3><i class="fa fa-key"></i> New API-Key</h3>
	            </div> <!-- /.widget-header -->

					
				<div class="widget-content">
					<section id="lists">
						<?= $this->form_builder->open_form(array('action' => '')); ?>		        
						<?= $form_general; ?>		
						<div class="form-group">
							<div class="col-md-2"></div>
							<div class="col-md-9">
								<button type="submit" class="btn btn-success">Save</button> <a href="/admin/api_keys/" class="btn btn-default">Cancel</a>
							</div>
						</div>
					</section>
	</div>
</div>