<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>Engine Testing form</h3>
		</div>
		<!-- /.widget-header -->


		<div class="widget-content">
			<section id="engine">
						<?= $this->form_builder->open_form(array('action' => '','enctype' => 'multipart/form-data')); ?>
								<table width="100%">
					</tr>
					<tr>
						<td valign="top"><label for="first_name">Thread Var</label></td>
					</tr>
					<tr>
						<td valign="top"><textarea readonly name="threadvar" cols="134"
								rows="6"><?php
								foreach ( $thread_vars as $each_vars ) {
									echo $each_vars->key . ' = ' . $each_vars->value . "\n";
								}
								?></textarea></td>
					</tr>
								 <?php if(isset($activity_vars)) {?>
								<tr>
						<td valign="top""><label for="last_name">Activity Var</label></td>
					</tr>
					<tr>
						<td valign="top"><textarea readonly name="actvityvar" cols="134"
								rows="6"><?php
										foreach ( $activity_vars as $each_vars ) {
											echo $each_vars->key . ' = ' . $each_vars->value . "\n";
										}
										?></textarea></td>
					</tr>
								<?php } ?>
								 
								</tr>
					<tr>
						<td valign="top"><label for="telephone">History of current session</label>
						</td>
					</tr>
					<tr>
						<td valign="top">
							<table width="100%" border="1px solid	"
								style="text-align: center;">
								<thead>
									<tr>
										<th>Target Activity</th>
										<th>Target Thread</th>
										<th>Date</th>
										<th>Action</th>
										<th>Variables</th>
										<th>Values</th>
										<th>Note</th>
									</tr>
								</thead>
								<tbody>
													    		<?php
																			
																			if (is_array ( $history_data )) {
																				foreach ( $history_data as $history ) {
																					if ($history->exit_scenario != NULL && $history->exit_scenario < 0) {
																						echo '<tr class="error_row">';
																					} else {
																						echo '<tr>';
																					}
																					echo "<td>$history->id_activity</td>";
																					echo "<td>$history->id_thread</td>";
																					echo "<td>$history->created</td>";
																					echo "<td>$history->action</td>";
																					echo "<td>$history->key</td>";
																					echo "<td>$history->value</td>";
																					echo "<td>$history->note</td>";
																					echo '</tr>';
																				}
																			}
																			?>
													    	</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td valign="top"><label for="comments"><br>Action <br>Eg:$NEXTID=Create_Activity(BO_MODULO_VERIFICA;STATUS=DONE;);<br>
								$RES=Update_Var(ACTIVITY;$ACTID;STATUS=NEW;);</label></td>
					</tr>
					<tr>
						<td valign="top"><textarea name="comments" cols="134" rows="6"></textarea>
						</td>

					</tr>
					<tr>
						<td style="text-align: center"><input type="submit" value="Excute">
						</td>
					</tr>
				</table>
								<?= $this->form_builder->close_form(); ?>
					</section>
		</div>
	</div>
</div>