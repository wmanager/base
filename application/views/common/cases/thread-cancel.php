<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>Thread cancel</h3>
		</div>
		<!-- /.widget-header -->
		<div class="widget-content">
			<section id="thread_cancel">
				<div>By cancelling this request you will cancel the current thread,
					any (not closed) related activity and any integration as shown
					below. Are you sure you want to proceed?</div>
				<br>
				<div class="clearfix"></div>
				Related activities
				<ul id="reltab" class="nav nav-tabs">
					<li class="active"><a href="#related_activity" data-toggle="tab">Activity
							correlate</a></li>
				</ul>
				<div class="act_detail">
					<div class="tab-content">
						<table class="table table-striped table-hover md-break-text"
							id="related_activity_table">
							<thead>
								<tr>
									<th>Type</th>
									<th>Status</th>
									<th>Created</th>
									<th></th>
								</tr>
    	            				<?php
																					if ((is_array ( $related_activity )) && (count ( related_activity ) > 0)) {
																						foreach ( $related_activity as $row ) {
																							?>
												<tr>
									<td>
														<?php echo $row->setup_title; ?>
													</td>
									<td>
														<?php echo $row->status; ?>
													</td>
									<td>
														<?php echo $row->created; ?>
													</td>
								</tr>			    	            							
  	            					<?php
																						}
																					} else {
																						echo "<tr><td>No record found</td></tr>";
																					}
																					?>
                				</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
				<div>
					<a
						href="/common/cases/update_status/<?php echo $this->uri->segment(4); ?>"
						class="btn btn-success pull-left delete-confirm"
						data-message=" Are you sure you want to cancel?">Ok, Cancel</a>
				</div>
				<div>
					<a href="/common/cases/edit/<?php echo $this->uri->segment(4); ?>"
						class="btn btn-success pull-right">No, keep the thread open</a>
				</div>
			</section>
		</div>
	</div>
</div>