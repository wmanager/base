<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-building-o"></i> Nuovo utente
			</h3>
		</div>
		<!-- /.widget-header -->


		<div class="widget-content">
			<section id="companies">
						<?php
						if ($this->session->userdata ( 'errors' )) {
							echo "<div class='alert alert-danger'>" . $this->session->userdata ( 'errors' ) . "</div>";
						}
						?>
						<?= $this->form_builder->open_form(array('action' => '','enctype' => 'multipart/form-data')); ?>
						
			          
				            	<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<a href="javascript:void(0);"
							onclick="javascript:$('#fileupload').click();" class="upload"
							id="upload"></a> <input type="file" id="fileupload" name="icon"
							style="opacity: 0; margin-top: -20px;">
						<div class="clearfix"></div>
					</div>
				</div>
								<?= $form; ?>
								<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<button type="submit" class="btn btn-success">Salva</button>
						<a href="/admin/companies/edit/<?=$this->uri->segment(4)?>/#users"
							class="btn btn-default">Annulla</a>
					</div>
				</div>

			</section>
		</div>
	</div>