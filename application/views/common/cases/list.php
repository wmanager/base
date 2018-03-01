<div class="col-md-12">
	<div class="widget stacked ">
		<!-- Status messages section -->
				<?php
				if ($this->session->flashdata ( 'msge_success' )) {
					?>
				<div class="alert alert-info fade in">
				  <?= $this->session->flashdata('msge_success');?>
				  <button type="button" class="close" data-dismiss="alert">x</button>
		</div>
				<?php
				}
				?>
				<?php
				if ($this->session->flashdata ( 'msge_error' )) {
					?>
				<div class="alert alert-danger fade in">
				  <?= $this->session->flashdata('msge_error');?>
				  <button type="button" class="close" data-dismiss="alert">x</button>
		</div>
				<?php
				}
				?>
				<!-- Status messages section end -->
		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>Cases</h3>
		</div>
		<!-- /.widget-header -->

		<div class="widget-content">
			<section id="companies">
				<div class="filters pull-left">
					<div class="filters pull-left">
						<form id="filters" name="filter" method="post"
							action="/common/cases/" class="form-inline">
							<div class="form-group ">
								<div class="input-group">
									<select name="process" class="form-control"
										onchange="this.form.submit()" id="filter-combo-process">
										<option value="-">All processes</option>
										<?php
										foreach ( $processes_types as $s ) {
											$selected = '';
											if ($s->type == $this->session->userdata ( 'filter_threads_process' ))
												$selected = 'selected';
											echo "<option value='$s->type' $selected>$s->type</option>";
										}
										?>
								      </select>
								</div>
								<div class="input-group">
									<select name="status" class="form-control"
										onchange="this.form.submit()">
										<option value="-">All the statuses</option>
											<?php
											foreach ( $master_statuses as $s ) {
												$selected = '';
												if ($s->status == $this->session->userdata ( 'filter_threads_status' ))
													$selected = 'selected';
												echo "<option value='$s->status' $selected>$s->status</option>";
											}
											?>
								      </select>
								</div>
								<div class="input-group">
									<select name="macro_process" class="form-control"
										onchange="this.form.submit()" id="filter-combo-process">
										<option value="-">All macro processes</option>
										<?php
										foreach ( $macro_processes_types as $s ) {
											$selected = '';
											if ($s->process == $this->session->userdata ( 'filter_threads_macro_process' ))
												$selected = 'selected';
											echo "<option value='$s->process' $selected>$s->process</option>";
										}
										?>
								      </select>
								</div>

							</div>
							<br /> <br>
							<div class="form-group">
								<div class="input-group">
									<input class="form-control" type="text" name="cliente"
										value="<?=$this->session->userdata('filter_threads_cliente')?>"
										placeholder="Client Name OR Code">
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
								<div class="input-group">
									<button type="submit" class="btn btn-primary">Search</button>
								</div>
							</div>

							<div class="clearfix"></div>
							<br>
							<!-- 							    <div class="form-group"> -->
							<!-- 							    	<div class="input-group"> -->
							<!-- 										<select name="status" class="form-control" onchange="this.form.submit()" > -->
							<!-- 											<option value="-">Micro process</option> -->
											<?php
											// foreach($setup_mps as $mp){
											// $selected = '';
											// if($s->status == $this->session->userdata('filter_threads_status')) $selected = 'selected';
											// echo "<option value='$mp->id' $selected>$mp->mp</option>";
											// }
											// 											?>
<!-- 								      </select> -->
							<!-- 									</div> -->
							<!-- 								</div> -->

							<div class="form-group">
								<div class="checkbox-inline">
									<input type='hidden' value='' name='reclamo'> <label><input
										type="checkbox" onchange="this.form.submit()" name="reclamo"
										value="true"
										<?php if($this->session->userdata('filter_threads_reclamo')) echo 'checked="checked"';?>>
										Complaint</label>
								</div>
							</div>
						</form>
					</div>
					<hr></hr>
				</div>
				<div class="pull-right">
					<div class="input-group pull-right">
						<a class="btn btn-primary" href="export_thread">Export Thread</a>
					</div>
				</div>
				<div class="clearfix"></div>
				<hr></hr>
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>Thread</th>
							<th>Client</th>
							<th>Process</th>
							<th>Activities</th>
							<th>State</th>
							<th>Result</th>
							<th>d_integration</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					    		<?php
											if (is_array ( $cases ) && !empty($cases)) {
												foreach ( $cases as $thread ) {
													$reclamo = '';
													if ($thread->reclamo == 't')
														$reclamo = "<i title='Reclamo' class='fa fa-bell-o red'></i> ";
													echo '<tr>';
													

													echo "<td><a href='/common/cases/edit/$thread->id'>THREAD #$thread->id</a></td>";
													
													// echo "<td>$thread->process<br><small>$thread->type</small></td>";												
														echo "<td><a href='/common/accounts/detail/$thread->user' target='_blank'>$thread->first_name $thread->last_name</a></td>";
													
													if ($thread->wiki_url != '') {
														$info_link = '<a href="' . $thread->wiki_url . '" target="_blank" class="wiki_link"><i class="fa fa-info-circle" aria-hidden="true"></i></a>';
													} else {
														$info_link = '';
													}
													
													if ($thread->new_title != '') {
														$thread_name = $thread->new_title;
													} else {
														$thread_name = $thread->type;
													}

													echo "<td>$reclamo  <a href='/common/cases/edit/$thread->id'>$thread_name</a> $info_link <br><small>Created by $thread->first_name $thread->last_name  on " . date ( 'd/m/Y H:i', datait2ts ( $thread->created ) ) . "</small></td>";


													echo "<td><a href='/common/cases/set_thread/$thread->id'>$thread->act_count</a></td>";
													
													echo "<td><span class='label label-primary'>$thread->status</span></td>";
													echo "<td>$thread->result<br>$thread->result_note</td>";
													// echo "<td width='100'><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$thread->progress."' aria-valuemin='0' aria-valuemax='100' style='width: ".$thread->progress."%;'>".$thread->progress."%</div></div></td>";
													?><td>  <?php if(!empty($thread->d_decorrenza)) echo date("d/m/Y", strtotime(str_replace('/','-',$thread->d_decorrenza))); ?> 
						    							<?php if(!empty($thread->exauto_created)) echo date("d/m/Y", strtotime(str_replace('/','-',$thread->exauto_created))); ?>
						    							<?php if(!empty($thread->exre_created)) echo date("d/m/Y", strtotime(str_replace('/','-',$thread->exre_created))); ?>
						    					</td>
						    					<?php
													echo "<td width='10'>";
													echo '<div class="dropdown">';
													echo '<a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-list"></i></a>';
													echo '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';
													echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="/common/cases/edit/' . $thread->id . '">Details</a></li>';
													
													if ($thread->status != 'CLOSED' && $thread->status != 'CANCELLED') {
														echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="/common/cases/cancel/' . $thread->id . '" data-toggle="modal" data-target="#cancelThreadItem">Cancel</a></li>';
													}
													echo '</ul>';
													echo '</div>';
													echo "</td>";
													echo '</tr>';
												}
											} else {
												echo "<tr>";
												echo "<td colspan='8'>";
												echo "No record found";
												echo "</td>";
												echo "</tr>";
											}
											?>
					    	</tbody>

				</table>
				<p class="pull-right text-muted">(<?=$total_rows?> Cases)</p>
					    <?=$this->pagination->create_links();?>
					</section>
		</div>
	</div>
</div>
<!-- modal- cancel item -->
<div class="modal fade in" id="cancelThreadItem">
	<div class="modal-dialog">
		<div class="modal-content"></div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- modal- cancel item -->