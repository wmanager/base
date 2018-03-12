

<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>Troubles</h3>
		</div>
		<!-- /.widget-header -->


		<div class="widget-content">
			<section id="companies">
				<div class="">
					<form id="filters" name="filter" method="post"
						action="/common/troubles/" class="form-inline">
						<div class="clearfix"></div>
						<div class="">
							<div class="input-group">
								<select class="form-control" name="filter_type"
									onchange="setType()" id="id_trouble_type">
									<option value="-" selected>Select Type</option>
											<?php
											if (count ( $trouble_types ) > 0) {
												foreach ( $trouble_types as $item ) {
													$selected = '';
													if ($item->id == $this->session->userdata ( 'troubles_type_filter' )) {
														$selected = 'selected';
													}
													
													echo "<option value='$item->id' $selected>$item->title</option>";
												}
											}
											?>
										</select>
							</div>

							<div class="input-group">
								<select class="form-control" name="sub_type"
									onchange="this.form.submit()" id="id_touble_sub_type">
									<option value="-" selected>Select Sub Type</option>
											<?php
											if (count ( $trouble_sub_types ) > 0) {
												foreach ( $trouble_sub_types as $item ) {
													$selected = '';
													if ($item->key == $this->session->userdata ( 'troubles_sub_type_filter' )) {
														$selected = 'selected';
													}
													
													echo "<option value='$item->key' $selected>$item->key</option>";
												}
											}
											?>
										</select>
							</div>
							
							<div class="form-group ">
								<div class="input-group">
									<select name="search_status" class="form-control" onchange="this.form.submit()" >
										<option value="">Filter status</option>
										<?php
										foreach($trouble_status as $s){
											$selected = '';
											if($s->key == $this->session->userdata('filter_trouble_status')) $selected = 'selected';
											echo "<option value='$s->key' $selected>$s->label</option>";
										}
										?>
							      </select>
								</div>
							</div>


							<div class="input-group">
								<div data-date-viewmode="days" data-date-format="dd/mm/yyyy"
									data-date-end-date="" data-date="" class="input-group date"
									new-calendar="">
									<input type="text"
										class="form-control ng-pristine ng-valid ng-touched"
										column="col-sm-6 col-md-6 col-lg-6"
										placeholder="Date of reminder"
										value="<?=$this->session->userdata('filter_trouble_reminder')?>"
										name="reminder"> <span class="input-group-addon"><i
										class="fa fa-calendar"></i></span>
								</div>
							</div>

							<div class="clearfix"></div>
							<br>

							<div class="input-group">
								<div data-date-viewmode="days" data-date-format="dd/mm/yyyy"
									data-date-end-date="" data-date="" class="input-group date"
									new-calendar="">
									<input type="text"
										class="form-control ng-pristine ng-valid ng-touched"
										column="col-sm-6 col-md-6 col-lg-6"
										placeholder="Created date from"
										value="<?=$this->session->userdata('filter_trouble_created_from')?>"
										name="created_from"> <span class="input-group-addon"><i
										class="fa fa-calendar"></i></span>
								</div>
							</div>

							<div class="input-group">
								<div data-date-viewmode="days" data-date-format="dd/mm/yyyy"
									data-date-end-date="" data-date="" class="input-group date"
									new-calendar="">
									<input type="text"
										class="form-control ng-pristine ng-valid ng-touched"
										column="col-sm-6 col-md-6 col-lg-6"
										placeholder=" Created date to"
										value="<?=$this->session->userdata('filter_trouble_created_to')?>"
										name="created_to"> <span class="input-group-addon"><i
										class="fa fa-calendar"></i></span>
								</div>
							</div>

							<div class="input-group">
								<select class="form-control" name="search_result"
									value="<?=$this->session->userdata('filter_trouble_result')?>"
									placeholder="Result">
									<option value="">Select Result</option>
									<option value="OK"
										<?php if($this->session->userdata('filter_trouble_result') =='OK'){ echo 'selected';}?>>OK</option>
									<option value="KO"
										<?php if($this->session->userdata('filter_trouble_result') =='KO'){ echo 'selected';}?>>KO</option>
								</select>
							</div>

							<div class="clearfix"></div>
							<br>

							<div class="input-group">
								<input class="form-control" type="text" name="search_account"
									value="<?=$this->session->userdata('filter_accounts')?>"
									placeholder="Client Name OR code">
							</div>


							<div class="input-group">
								<button type="submit" class="btn btn-primary" name="search"
									value="Search">Search</button>
							</div>

							<div class="input-group">
								<button type="submit" class="btn btn-primary" name="clear"
									value="clear" onclick="this.form.submit()">Clear</button>
							</div>

							<div class="clearfix"></div>
							<br>

							
							<div class="input-group pull-right">
								<a href="/common/troubles/add"
									class="btn btn-primary pull-right">New Trouble</a> <span> </span>
								<a href="/common/troubles/export_troubles"
									class="btn btn-primary pull-right btn-margin-right">Export
									Trouble</a>
							</div>


						</div>

					</form>
				</div>

				<div class="clearfix"></div>
				<hr></hr>
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>Trouble</th>
							<th>Sub Type</th>
							<th>Client</th>
							<th>Resp. Resolution</th>
							<th></th>
							<th>Esito</th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						    		<?php
												if (is_array ( $troubles ) && count ( $troubles ) > 0) {
													
													foreach ( $troubles as $item ) {
														echo '<tr>';
															echo "<td><a href='/common/troubles/edit/$item->id'>#" . sprintf ( "%05d", $item->id ) . "</a><br><a href='/common/troubles/edit/$item->id'>$item->type</a><br>";
																if ($item->status) {													
																		echo "<span class='label label-primary'>$item->status</span>";
																}
															echo "</td>";
															echo "<td>$item->subtype</td>";
															echo "<td>$item->code<br><a href='/common/accounts/detail/$item->accounts_id'>$item->first_name $item->last_name </a></td>";														
															echo "<td>$item->resp_risoluzione_company<br>$item->resp_risoluzione_user</td>";
															
															echo "<td>Created on ";
																if($item->created)
																	echo  date ( 'd-m-Y H:i', strtotime ( str_replace ( '/', '-', $item->created ) ) );
																echo "<br>  Deadline ";
																if($item->deadline)
																	echo  date ( 'd-m-Y H:i', strtotime ( str_replace ( '/', '-', $item->deadline ) ) );
															echo "</td>";
															
															if ($item->reminder != '')
																echo "<td><small><i class='fa fa-comment'></i> $item->followup<br><i class='fa fa-calendar'></i> $item->reminder</small></td>";
															if ($item->reminder == '')
																echo "<td><small><i class='fa fa-comment'></i> $item->followup</td>";
															echo "<td><span class='label label-primary'>$item->result</span></td>";														
															echo "<td><span class='badge'>" . $this->trouble->count_related ( $item->id ) . " PROCESS</span></td>";
														echo '</tr>';
													}
												} else {
													echo "<tr><td colspan='8'>No record found</td></tr>";
												}
												?>
						    	</tbody>

				</table>
				<p class="pull-right text-muted">(<?=$total_rows?> Troubles)</p>
					    <?=$this->pagination->create_links();?>
					</section>
		</div>
	</div>
</div>