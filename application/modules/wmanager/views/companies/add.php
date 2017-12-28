<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-building-o"></i> Nuova azienda
			</h3>
		</div>
		<!-- /.widget-header -->


		<div class="widget-content">
			<section id="companies">
						<?php
						if ($this->session->userdata ( 'upload_errors' )) {
							echo "<div class='alert alert-danger'>" . $this->session->userdata ( 'errors' ) . "</div>";
						}
						?>
						<?= $this->form_builder->open_form(array('action' => '','enctype' => 'multipart/form-data')); ?>
						<ul id="myTab" class="nav nav-tabs">
					<li class="active"><a href="#general" data-toggle="tab">Generale</a></li>
					<li class=""><a href="#billing" data-toggle="tab">Fatturazione</a></li>
					<li class=""><a href="#shipping" data-toggle="tab">Spedizione</a></li>
					<li class="disabled"><a href="javascript:void(0);">Utenti</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active fade in" id="general">
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<a href="javascript:void(0);"
									onclick="javascript:$('#fileupload').click();" class="upload"
									id="upload"></a> <input type="file" id="fileupload" name="icon"
									style="opacity: 0; margin-top: -20px;">
								<div class="clearfix"></div>
							</div>
						</div>
								<?= $form_general; ?>
								<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<button type="submit" class="btn btn-success">Salva</button>
								<a href="/admin/companies" class="btn btn-default">Annulla</a>
							</div>
						</div>
					</div>
					<div class="tab-pane fade in" id="billing">
								<?= $form_billing; ?>	
								<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<button type="submit" class="btn btn-success">Salva</button>
								<a href="/admin/companies" class="btn btn-default">Annulla</a>
							</div>
						</div>
					</div>
					<div class="tab-pane fade in" id="shipping">
								<?= $form_shipping; ?>	
								<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<button type="submit" class="btn btn-success">Salva</button>
								<a href="/admin/companies" class="btn btn-default">Annulla</a>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>