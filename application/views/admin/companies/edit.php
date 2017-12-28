<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-building-o"></i> Modifica azienda
			</h3>
		</div>
		<!-- /.widget-header -->


		<div class="widget-content">
			<section id="companies">
						<?php
						if ($this->session->userdata ( 'upload_errors' )) {
							echo "<div class='alert alert-danger'>" . $this->session->userdata ( 'upload_errors' ) . "</div>";
						}
						?>
						<?= $this->form_builder->open_form(array('action' => '','enctype' => 'multipart/form-data')); ?>
						<ul id="myTab" class="nav nav-tabs">
					<li class="active"><a href="#general" data-toggle="tab">Generale</a></li>
					<li class=""><a href="#billing" data-toggle="tab">Fatturazione</a></li>
					<li class=""><a href="#shipping" data-toggle="tab">Spedizione</a></li>
					<li class=""><a href="#users" data-toggle="tab">Utenti</a></li>
					<li class=""><a href="#api_keys" data-toggle="tab">API keys</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active fade in" id="general">
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<a href="javascript:void(0);"
									onclick="javascript:$('#fileupload').click();" class="upload"
									id="upload">
						            		<?php
																				if ($company->icon != '') {
																					echo "<img src='/uploads/companies/$company->icon' width='90' height='90' class='img-circle'>";
																				}
																				?>
						            	</a> <input type="file" id="fileupload" name="icon"
									style="opacity: 0; margin-top: -20px;" value="">
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
					<div class="tab-pane fade in" id="users">
						<div class="actions pull-right">
							<a href="/admin/companies/add_user/<?=$this->uri->segment(4);?>"
								class="btn btn-default"><i class="fa fa-plus-circle"></i> Nuovo
								utente</a>
						</div>
						<div class="clearfix"></div>
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th></th>
									<th>Nome</th>
									<th>Ruolo</th>
									<th>Status</th>
									<th></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
							    		<?php
													if (is_array ( $users )) {
														$c = $this->uri->segment ( 4 );
														$tf_array = array (
																'' => 'Sospeso',
																'1' => 'Attivo',
																'0' => 'Sospeso' 
														);
														foreach ( $users as $user ) {
															echo '<tr>';
															if ($user->icon != '') {
																echo "<td width='40'><img src='/uploads/users/$user->icon' width='40' height='40' class='img-circle'></td>";
															} else {
																echo "<td width='40'><img src='/assets/img/anonym.png' width='40' height='40' class='img-circle'></td>";
															}
															echo "<td><b>$user->first_name $user->last_name</b><br>$user->email</td>";
															echo "<td>" . ucfirst ( strtolower ( $user->role1 ) ) . "</td>";
															echo "<td>" . $tf_array [$user->active] . "</td>";
															echo "<td><small>Ultimo login " . date ( 'd-m-Y H:i:s', $user->last_login ) . "</small></td>";
															echo "<td><a href='/admin/companies/edit_user/$c/$user->id/'>Modifica</a></td>";
															echo '</tr>';
														}
													}
													?>
							    	</tbody>
						</table>
					</div>
					<div class="tab-pane fade in" id="api_keys">
						<div class="actions pull-right">
							<a href="/admin/companies/add_api/<?=$this->uri->segment('4')?>"
								class="btn btn-default"><i class="fa fa-plus-circle"></i> Nuova
								API Key</a>
						</div>
						<div class="clearfix"></div>
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th></th>
									<th></th>
									<th>Key</th>
									<th>Status</th>
									<th></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
							    		<?php
													$tf_array = array (
															't' => 'Attiva',
															'f' => 'Sospesa' 
													);
													if (is_array ( $keys )) {
														foreach ( $keys as $key ) {
															echo '<tr>';
															if ($key->icon != '') {
																echo "<td width='40'><img class='img-circle' width='40' height='40' src='/uploads/companies/$company->icon'></td>";
															} else {
																echo "<td width='40'><img class='img-circle' width='40' height='40' src='/assets/img/anonym.png'></td>";
															}
															echo "<td>$key->name</td>";
															echo "<td>$key->key</td>";
															echo "<td>" . $tf_array [$key->active] . "</td>";
															echo "<td>Rilasciata il " . date ( 'd-m-Y', strtotime ( $key->created ) ) . "</td>";
															echo "<td><a href='" . base_url () . "admin/companies/edit_api/" . $this->uri->segment ( '4' ) . "/$key->id'>Modifica</a></td>";
															echo '</tr>';
														}
													}
													?>
							    	</tbody>
						</table>
					</div>

				</div>
			</section>
		</div>
	</div>