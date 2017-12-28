<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content"></div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-bar-chart-o"></i> Client Details
			</h3>
		</div>
		<!-- /.widget-header -->
		<div class="widget-content">
			<section id="reports">
				<div class="col-md-12 inner-padding">
					<div class="row">
						<div class="col-md-6 col-sm-5">
							<p class="pull-left"><?php if(isset($header->first_name)) echo $header->first_name; ?> <?php if(isset($header->last_name)) echo $header->last_name; ?> <br>
            							<?php if(isset($header->code)) echo $header->code; ?><br>
            							<?php
																			if (isset($header->email)) {
																				echo " Email:" . $header->email;
																			}
																			if (isset($header->tel)) {
																				echo " Tel:" . $header->tel;
																			}
																			if (isset($header->cell)) {
																				echo " Cell:" . $header->cell;
																			}
																			?>
            						</p>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
				<hr></hr>
				<!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
					<li class="active"><a href="#be" role="tab" data-toggle="tab">Business entity</a></li>
					<li><a href="#contratti" role="tab" data-toggle="tab">Contracts</a></li>
					<li><a href="#impianti" role="tab" data-toggle="tab">Impianti</a></li>
					<li><a href="#indirizzi" role="tab" data-toggle="tab">Address</a></li>
					<li><a href="#attachment" role="tab" data-toggle="tab">Attachment</a></li>
					<li><a href="#troubles" role="tab" data-toggle="tab">Troubles</a></li>
					<!-- <li><a href="#contatti" role="tab" data-toggle="tab">Contacts</a></li> -->
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<div class="tab-pane active" id="be">
								<div class="clearfix"></div>
								<table class="table table-striped table-hover">
						    	<thead>

						    		<tr>
						    			<th>BE</th>
						    			<th>Company</th>
						    			<th>Immobile Address</th>
						    			
						    		</tr>
						    	</thead>
						    	<tbody>
						    		<?php
						    		
						    			if(is_array($be) && !empty($be)){
							    			foreach($be as $item){
												
							    				echo '<tr>';							    					
							    					echo "<td>#".str_pad($item->be_id, 5, "0", STR_PAD_LEFT )."<br><small>$item->contract_type</small></td>";
							    					echo "<td>$item->company_name</td>";					    				
							    					echo "<td><small>$item->address $item->state $item->country<br></small></td>";							    												    													    					
							    				echo '</tr>';
							    			}
							    		} else {
												echo "<tr>";
												echo "<td colspan='3'>";
												echo "No record found";
												echo "</td>";
												echo "</tr>";
											}
						    		?>
						    	</tbody>

						    </table>					
					</div>
							<div class="tab-pane" id="contratti">
								<div class="clearfix"></div>
								<table class="table table-striped table-hover">
						    	<thead>

						    		<tr>
						    			<th>BE/Contract</th>
						    			<th>Type</th>
						    			<th>Product</th>
						    			<th>Signature Date</th>
						    		</tr>
						    	</thead>
						    	<tbody>
						    		<?php
						    		
						    			if(is_array($contratti) && !empty($contratti)){
						    				
							    			foreach($contratti as $item){

							    				echo '<tr>';
							    					echo "<td>#".str_pad($item->be_id, 5, "0", STR_PAD_LEFT )."<br>$item->contract_code</td>";
							    					echo "<td>$item->contract_type</td>";
							    					echo "<td>$item->prod_type $item->prod_code</td>";
							    					echo "<td>$item->contract_d_sign</td>";
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
							</div>	
							<div class="tab-pane" id="impianti">
								<div class="clearfix"></div>
								<table class="table table-striped table-hover">
						    	<thead>

						    		<tr>
						    			<th>BE</th>
						    			<th>Address</th>
						    			<th>Status</th>
						    			<th>Power</th>						    			
						    		</tr>
						    	</thead>
						    	<tbody>
						    		<?php
						    		
						    			if(is_array($impianti) && !empty($impianti)){
						    				
							    			foreach($impianti as $item){

							    				echo '<tr>';
							    					echo "<td>#".str_pad($item->be_id, 5, "0", STR_PAD_LEFT )."</td>";
							    					echo "<td><small>$item->address $item->state $item->country<br></small></td>";		
							    					echo "<td><span class='label label-primary'>$item->status</span></td>";							    												    					
							    					echo "<td><small>Installation: $item->installed_power kW<br>Installable (max): $item->pot_installable kW<br>Consumption hours: $item->capacity</small></td>";							    					
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
							</div>
							<div class="tab-pane" id="indirizzi">
								<div class="clearfix"></div>
								<table class="table table-striped table-hover">
						    	<thead>

						    		<tr>
						    			<th>BE</th>
						    			<th>Type</th>
						    			<th>Address</th>
						    		</tr>
						    	</thead>
						    	<tbody>
						    		<?php
						    		
						    			if(is_array($indirizzi) && !empty($indirizzi)){
						    				
							    			foreach($indirizzi as $item){

							    				echo '<tr>';
							    					echo "<td>#".str_pad($item->be_id, 5, "0", STR_PAD_LEFT )."</td>";
							    					echo "<td>$item->type</td>";
							    					echo "<td>$item->first_name $item->last_name <br> $item->address $item->city $item->state $item->country <br>$item->tel $item->cell<br>$item->fax<br>$item->email</td>";
							    				echo '</tr>';
							    			}
							    		} else {
												echo "<tr>";
												echo "<td colspan='3'>";
												echo "No record found";
												echo "</td>";
												echo "</tr>";
											}
						    		?>
						    	</tbody>

						    </table>
							</div>
							<div class="tab-pane" id="attachment">
								<table class="table table-striped table-hover">
									<thead>
											<tr>
												<th>Created</th>
												<th>Attachment Type</th>
												<th>Filename</th>
												<th>Description</th>
											</tr>
									</thead>
									<tbody>
										<?php 
										if(is_array($attachments) && !empty($attachments)){
											foreach($attachments as $item){

												echo "<tr>";
													echo "<td>".date('d-m-Y',strtotime(str_replace('/', '-',$item->created)))."</td>";
													echo "<td style='width:20%'>".$item->attachment_type."</td>";
													$item->id = crypt_params($item->id);
													$item->thread_id = crypt_params($item->thread_id);
													if($item->activity_id && $item->activity_id!='') $item->activity_id = crypt_params($item->activity_id);
													echo "<td style='width:30%'><a href='".site_url("common/attachments/download_file/$item->id/$item->thread_id/$item->activity_id")."' target='blank'>".trim($item->filename," ")."</a></td>";
													echo "<td style='width:30%'>".$item->attachment_description."</td>";
												echo "</tr>";
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
							</div>
							<div class="tab-pane" id="troubles">
								<table class="table table-striped table-hover">
						    	<thead>
						    		<tr>
						    			<th>Trouble</th>
						    			<th>Resp. risoluzione</th>						    			
						    			<th></th>
						    
						    		</tr>
						    	</thead>
						    	<tbody>
						    		<?php
						    			if(is_array($troubles) && !empty($troubles)){
						    				
							    			foreach($troubles as $item){
							    				echo '<tr>';
							    					echo "<td>#".sprintf("%05d", $item->id)."<br>$item->type<br><span class='label label-primary'>$item->status</span> <span class='label label-default'>$item->result</span></td>";
							    					echo "<td>$item->resp_risoluzione_company<br>$item->resp_risoluzione_user</td>";					    												    					
							    					echo "<td>Created the ".date('d-m-Y H:i',strtotime(str_replace('/', '-',$item->created)))."<br> from $item->creator<br>Deadline ".date('d-m-Y',strtotime($item->deadline))."</td>";
							    					echo "<td width='10'>";
						    						echo '<div class="dropdown">';
						    							 echo '<a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-list"></i></a>';
														  echo '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';
														  	
														   	 	echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="/common/troubles/edit/'.$item->id.'">Details</a></li>';
															
														  echo '</ul>';
													echo '</div>';
						    					echo "</td>";
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
							</div>

				</div>
			</section>
		</div>
	</div>
</div>
