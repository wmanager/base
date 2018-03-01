
<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-tasks"></i> Activity
			</h3>
		</div>
		<!-- /.widget-header -->

		<div class="widget-content">
			<section id="companies">
				<div class="filters">
					<form id="filters" name="filter" method="post"
						action="/common/activities/" class="form-inline">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#processo"
								aria-controls="processo" role="tab" data-toggle="tab">Filter for process</a></li>
							<li role="presentation"><a href="#attivita"
								aria-controls="attivita" role="tab" data-toggle="tab">Filter for activity</a></li>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="processo">
								<div class="form-group ">
									<div class="input-group">
										<select name="process" class="form-control"
											onchange="this.form.submit()" id="filter-combo-process">
											<option value="-">All processes</option>
														<?php
														foreach ( $processes_types as $s ) {
															$selected = '';
															if ($s->id == $this->session->userdata ( 'filter_activities_process' ))
																$selected = 'selected';
															echo "<option value='$s->id' $selected>$s->title</option>";
														}
														?>
											      </select>
									</div>
								</div>
								<div class="form-group ">
									<div class="input-group">
										<select name="type" class="form-control"
											onchange="this.form.submit()">
											<option value="-">All types</option>
														<?php
														foreach ( $activities_types as $s ) {
															$selected = '';
															if ($s->key == $this->session->userdata ( 'filter_activities_type' ))
																$selected = 'selected';
															echo "<option value='$s->key' $selected>$s->title</option>";
														}
														?>
											      </select>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="attivita">
								<div class="form-group">
									<div>
										<input type="text" class="form-control"
											name="activity_autocomplete" id="type_autocomplete"
											placeholder="Search by activity"
											value="<?=$this->session->userdata('filter_activities_label')?>">
										<input type="hidden" name="activity"
											id="type_autocomplete_hidden" value="">
									</div>

								</div>
								<div class="form-group">
									<div class="input-group">
										<select name="role" class="form-control"
											onchange="this.form.submit()">
											<option value="-">All the roles</option>
														<?php
														foreach ( $roles as $role ) {
															$selected = '';
															if ($role->role == $this->session->userdata ( 'filter_activities_role' ))
																$selected = 'selected';
															echo "<option value='$role->role' $selected>$role->role</option>";
														}
														?>
											    </select>
									</div>
								</div>
							</div>
						</div>
						<br>
						
						<div class="form-group">

							<div class="input-group">
								<input type="text" class="form-control" name="cliente"
									placeholder="Client Name OR Code"
									value="<?=$this->session->userdata('filter_activities_cliente')?>">
							</div>
							<div class="input-group">
								<input type="text" class="form-control" name="codice_contratto"
									placeholder="Contract code"
									value="<?=$this->session->userdata('filter_activities_codice_contratto')?>">
							</div>

							<div class="input-group">
								<button type="submit" class="btn btn-primary">Search</button>
								<div class="clearfix"></div>

							</div>
							<div class="input-group">
								<button type="submit" class="btn btn-primary" name="clear"
									value="clear" onclick="this.form.submit()">Clear</button>
								<div class="clearfix"></div>

							</div>
							<div class="clearfix"></div>
							<br>
							<div class="form-group ">
								<div class="input-group">
									<select name="status" class="form-control"
										onchange="this.form.submit()">
										<option value="-">All the statuses</option>
												<?php
												foreach ( $master_statuses as $s ) {
													$selected = '';
													if ($s->status == $this->session->userdata ( 'filter_activities_status' ))
														$selected = 'selected';
													echo "<option value='$s->status' $selected>$s->status</option>";
												}
												?>
									      </select>
								</div>
							</div>
							<div class="input-group">
								<select class="form-control" name="search_esito"
									onchange="this.form.submit()">
									<option value="">Select Result</option>
									<option value="OK"
										<?php if($this->session->userdata('filter_esito_result') =='OK'){ echo 'selected';}?>>OK</option>
									<option value="KO"
										<?php if($this->session->userdata('filter_esito_result') =='KO'){ echo 'selected';}?>>KO</option>
								</select>
							</div>
						</div>
						<div class="clearfix"></div>
						<br>
						<!--  <div class="form-group ">
									<div class="input-group">
										<select name="order" class="form-control" onchange="this.form.submit()" >
							
								      	<option value="created" <?php if($this->session->userdata('filter_activities_order') == 'created') echo 'selected'; ?> >Ordina per data di creazione</option>
								      	<option value="deadline" <?php if($this->session->userdata('filter_activities_order') == 'deadline') echo 'selected'; ?>>Ordina per deadline</option>
								      </select>
									</div>
								</div> -->


					</form>
				</div>
			
				<div class="clearfix"></div>
				<hr></hr>
				<?php ?>

				<a href="/common/activities/export"
					class="btn btn-success pull-right">Export</a>
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th></th>
							<th>Client</th>							
							<th>Contract</th>
							<th>Activity</th>
							<th>Status</th>
							<th></th>
							<th>Result</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					    		<?php

											if (is_array ( $activities ) && !empty($activities)) {
												
												foreach ( $activities as $activity ) {
													echo '<tr>';													
													$link = "/common/activities/detail/$activity->id";
													
													if ($activity->trouble_id != '')
														$trouble = "<br>TROUBLE #" . str_pad ( $activity->trouble_id, 5, '0', STR_PAD_LEFT );
													echo "<td>ACTIVITY #" . str_pad ( $activity->id, 5, '0', STR_PAD_LEFT ) . "<br><a href='/common/cases/edit/" . $activity->thread_id . "'>THREAD #" . str_pad ( $activity->thread_id, 5, '0', STR_PAD_LEFT ) . "</a></td>";
													echo "<td>$activity->client_code<br><a href='/common/accounts/detail/$activity->cliente'>$activity->client_first_name $activity->client_last_name</a></td>";																										
													echo "<td><span class='label label-info'>$activity->be_status</span><br>$activity->contract_code <br><br>";													
													echo "</td>";
													
													if ($activity->thread != '') {
														$thread = "<br><small>$activity->thread</small>";
													} else {
														$thread = '';
													}
													$deadline = $activity->deadline != '' ? '<br><small>To be resolved by the ' . date ( 'd/m/Y H:i', datait2ts ( $activity->deadline ) ) . '</small>' : null;
													$reclamo = "";
													$pending = "";
													if ($activity->reclamo == 't')
														$reclamo = "<i title='Reclamo' class='fa fa-bell-o red'></i> ";
													if ($activity->thread_status == 'PENDING')
														$pending = "<i class='fa fa-hourglass-start'></i> ";
													echo "<td>" . $reclamo . "" . $pending . "<a href='$link'>$activity->activity_title</a> <small style='font-size:70%'>($activity->role)</small> $thread <br><small>Created by $activity->company_name<br>$activity->first_name $activity->last_name  on " . date ( 'd-m-Y H:i', strtotime ( str_replace ( '/', '-', $activity->created ) ) ) . "</small><br><small> Assigned to $activity->duty_company</small>$deadline</td>";
													$statuses = $this->activity->get_statuses ( $activity->type, $activity->status_value );
											
													echo "<td><b>".$statuses[0]->key."</b><br><span class='label label-primary'>$activity->activity_status</span><br><small></small>";										
													echo "</td>";
													if ($activity->reminder != '')
														echo "<td><small><i class='fa fa-comment'></i> $activity->followup<br><i class='fa fa-calendar'></i> $activity->reminder</small></td>";
													if ($activity->reminder == '')
														echo "<td><small><i class='fa fa-comment'></i> $activity->followup</td>";
													
													if ($activity->result_value == 'OK')
														echo "<td>$activity->result_value<br><small>$activity->result_note_value</small></td>";
													if ($activity->result_value == 'KO')
														echo "<td>$activity->result_value<br><small>$activity->result_note_value</small></td>";
													if ($activity->result_value == '')
														echo "<td></td>";
													echo "<td width='10'>";
													echo '<div class="dropdown">';
													echo '<a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-list"></i></a>';
													echo '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';
													echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="/common/activities/detail/' . $activity->id . '">Details</a></li>';													
													if ($this->ion_auth->is_admin ())
														echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="' . base_url () . 'admin/engine_debug/debug/' . $activity->id_thread . '/' . $activity->id . '">Debug</a></li>';
													echo '</ul>';
													echo '</div>';
													echo "</td>";
													echo '</tr>';
												}
											} else {
												echo "<tr>";
												echo "<td colspan='8'>";
												echo "No Record Found";
												echo "</td>";
												echo "</tr>";
											}
											?>
					    	</tbody>

				</table>
				<p class="pull-right text-muted">(<?=$total_rows?> Activity)</p>
					    <?=$this->pagination->create_links();?> 
					</section>
		</div>
	</div>
</div>