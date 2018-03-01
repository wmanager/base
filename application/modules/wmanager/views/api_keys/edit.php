<div class="col-md-12">
	<div class="widget stacked ">

	            <div class="widget-header">
	              <i class="icon-pencil"></i>
	              <h3><i class="fa fa-exchange"></i> Modifica incarico</h3>
	            </div> <!-- /.widget-header -->

					
				<div class="widget-content">
					<section id="providers">
						<?= $this->form_builder->open_form(array('action' => '')); ?>	
						<?= $form_general; ?>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<button type="submit" class="btn btn-success">Save</button> <a href="/admin/contracts/edit/<?=$this->uri->segment(4)?>/#keys" class="btn btn-default">Cancel</a>
							</div>
						</div>
					</section>
				</div>
	</div>
</div>