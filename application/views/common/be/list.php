<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>Business Entities</h3>
		</div>
		<!-- /.widget-header -->


		<div class="widget-content">
			<section id="companies">
				<div class="filters pull-left">
					<form id="filters" name="filter" method="post"
						action="/common/businessentities/" class="form-inline">
						<div class="form-group">
							<div class="input-group">
								<input class="form-control" type="text" name="cliente"
									value="<?=$this->session->userdata('filter_be_cliente')?>"
									placeholder="Client Name OR Code">
							</div>							
							<div class="input-group">
								<input class="form-control" type="text" name="contratto"
									value="<?=$this->session->userdata('filter_be_contratto')?>"
									placeholder="Contract Code">
							</div>
							<div class="form-group ">
								<div class="input-group">
									<select name="status" class="form-control" onchange="this.form.submit()" >
										<option value="">Filter status</option>
										<?php
										foreach($master_statuses as $s){
											$selected = '';
											if($s->key == $this->session->userdata('filter_be_status')) $selected = 'selected';
											echo "<option value='$s->key' $selected>$s->label</option>";
										}
										?>
							      </select>
								</div>
							</div>								
							<div class="input-group">
								<button type="submit" class="btn btn-primary">Search</button>
							</div>
						</div>			
					</form>
				</div>
						<?php
						if ($this->ion_auth->is_admin ()) {
							echo '<a href="/common/businessentities/export" class="btn btn-success pull-right">Export</a>';
						}
						?>
						<div class="clearfix"></div>
				<hr></hr>
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>Client</th>							
							<th>Contract</th>
							<th>Company</th>



						</tr>
					</thead>
					<tbody>
						    		<?php
												if (is_array ( $be ) && !empty($be)) {
													
													foreach ( $be as $item ) {
														
														echo '<tr>';
														
														echo "<td>$item->code<br><a target='_blank' href='/common/accounts/detail/$item->account_id'>$item->first_name $item->last_name </a></td>";														
														echo "<td>$item->contract_code<br><span class='label label-info'>$item->be_status</span><br><small>";
														if($item->data_contratto)
															echo date ( 'd-m-Y H:i', strtotime ( str_replace ( '/', '-', $item->data_contratto ) ) ); 
														echo  "</small></td>";
														echo "<td>$item->agenzia</td>";
														echo '</tr>';
													}
												} else {
													echo "<tr>";
													echo "<td colspan='4'>";
													echo "No record found";
													echo "</td>";
													echo "</tr>";
												}
												?>
						    	</tbody>

				</table>
					    <?=$this->pagination->create_links();?>
					</section>
		</div>
	</div>
</div>