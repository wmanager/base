<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-bar-chart-o"></i> Dettaglio trouble
			</h3>
		</div>
		<!-- /.widget-header -->
		<div class="widget-content">
			<section id="reports">

				<p class="pull-left"><?=$header->p_nome?> <?=$header->p_cognome?> <?=$header->o_ragione_sociale?><br>
							<?php
							if ($header->o_piva != '') {
								echo $header->o_piva;
								echo "Business<br>";
							} else {
								echo "Residenziale<br>";
							}
							?>
							<?=$header->po_codice_cliente?><br>
							<?php
							if ($header->o_ref_contatto != '') {
								echo "Contatto del referente contrattuale: $header->o_ref_contatto";
							}
							?>
						</p>

				<div class="clearfix"></div>
				<hr></hr>
				<!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
					<li class="active"><a href="#general" role="tab" data-toggle="tab">Business
							entity</a></li>
					<li><a href="#dashboard" role="tab" data-toggle="tab">Dashboard
							cliente</a></li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<div class="tab-pane active" id="general">
						<div class="clearfix"></div>
					</div>

					<div class="tab-pane" id="dashboard">
								<?=$dashboard?>
							</div>
				</div>
			</section>
		</div>
	</div>
</div>