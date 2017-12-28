<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-building-o"></i> Aziende
			</h3>
		</div>
		<!-- /.widget-header -->


		<div class="widget-content">
			<section id="companies">
				<div class="filters pull-left">
					<form id="filters" name="filter" method="post"
						action="/admin/companies/" class="form-inline">
						<div class="form-group">
							<select class="form-control" onchange="this.form.submit()"
								name="status">
								<option value="-"
									<?php if(!$this->session->userdata('filter_status')) echo 'selected'; ?>>Tutti
									gli status</option>
								<option value="t"
									<?php if($this->session->userdata('filter_status')=='t') echo 'selected'; ?>>Attivo</option>
								<option value="f"
									<?php if($this->session->userdata('filter_status')=='f') echo 'selected'; ?>>Sospeso</option>
							</select>
						</div>
						<div class="form-group">
							<div class="input-group">
								<input type="text" class="form-control" name="company"
									value="<?=$this->session->userdata('filter_company')?>"
									placeholder="Azienda o contatto"> <span class="input-group-btn">
									<button class="btn btn-info" type="submit">Cerca</button>
								</span>
							</div>
						</div>
					</form>
				</div>
				<div class="actions pull-right">
					<a href="/admin/companies/add" class="btn btn-default"><i
						class="fa fa-plus-circle"></i> Nuova azienda</a>
				</div>
				<div class="clearfix"></div>
				<hr></hr>
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th></th>
							<th>Nome</th>
							<th>Status</th>
							<th>Nazione</th>
							<th>Contatto</th>
							<th>Utenti</th>
							<th>Contratti</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					    		<?php
											$tf_array = array (
													't' => 'ATTIVO',
													'f' => 'DISATTIVO' 
											);
											if (is_array ( $companies )) {
												foreach ( $companies as $company ) {
													echo '<tr>';
													if ($company->icon != '') {
														echo "<td width='40'><img class='img-circle' width='40' height='40' src='/uploads/companies/$company->icon'></td>";
													} else {
														echo "<td width='40'><img class='img-circle' width='40' height='40' src='/assets/img/anonym.png'></td>";
													}
													echo "<td>$company->name</td>";
													echo "<td>" . $tf_array [$company->active] . "</td>";
													echo "<td>$company->billing_address_country</td>";
													echo "<td>$company->contact</td>";
													echo "<td>" . count ( $this->company->get_users ( $company->id ) ) . "</td>";
													echo "<td>" . $this->company->get_contracts ( $company->id ) . "</td>";
													echo "<td width='10'>";
													echo '<div class="dropdown">';
													echo '<a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-list"></i></a>';
													echo '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';
													echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="' . base_url () . 'admin/companies/edit/' . $company->id . '">Modifica</a></li>';
													echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="' . base_url () . 'admin/companies/delete/' . $company->id . '" class="delete-confirm" data-message="Sei sicuro di voler eliminare il record?">Elimina</a></li>';
													echo '</ul>';
													echo '</div>';
													echo "</td>";
													echo '</tr>';
												}
											}
											?>
					    	</tbody>

				</table>
					    <?=$this->pagination->create_links();?>
					</section>
		</div>
	</div>
</div>