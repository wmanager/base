
<div class="row">
		<div class="col-lg-3 col-md-6">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<i class="fa fa-car fa-5x"></i>
						</div>
						<div class="col-xs-9 text-right">
							<div class="huge"><?php echo $engine['engine'];?></div>
								<div>Latest Engine calls</div>
							</div>
						</div>
					</div>
				<a href="#">
					<div class="panel-footer">
						<span class="pull-left">View Details</span>
						<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
						<div class="clearfix"></div>
					</div>
				</a>
			</div>
		</div>
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-green">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-bug fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
					<div class="huge"><?php echo $engine['total_trouble'];?></div>
					<div>Total Troubles</div>
					</div>
				</div>
			</div>
			<a href="#">
				<div class="panel-footer">
					<span class="pull-left">View Details</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-yellow">
			<div class="panel-heading">
				<div class="row">
				<div class="col-xs-3">
					<i class="fa fa-shopping-cart fa-5x"></i>
				</div>
				<div class="col-xs-9 text-right">
					<div class="huge"><?php echo $engine['total_threads'];?></div>
					<div>Total Threads</div>
				</div>
			</div>
		</div>
		<a href="#">
			<div class="panel-footer">
				<span class="pull-left">View Details</span>
				<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
				<div class="clearfix"></div>
			</div>
		</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-red">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-shield fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge"><?php echo $engine['total_activities'];?></div>
						<div>Total Activities</div>
					</div>
				</div>
			</div>
			<a href="#">
				<div class="panel-footer">
				<span class="pull-left">View Details</span>
				<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
				<div class="clearfix"></div>
			</div>
			</a>
		</div>
	</div>
</div>
<hr />
<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12">
		<div class="col-md-6">
			<strong>History Data</strong>
			<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>ID</th>
							<th>Activity</th>
							<th>Thread</th>
							<th>Action</th>
							<th>Created</th>
						</tr>
					</thead>
					<tbody>
			    		<?php
							if (is_array ( $engine_data ) && count($engine_data)>0) {
								foreach ( $engine_data as $item ) {
									echo '<tr>';
										echo "<td>$item->id</td>";
										echo "<td>$item->id_activity</td>";
										echo "<td>$item->id_thread</td>";
										echo "<td>$item->action</td>";
										echo "<td>$item->created</td>";
									echo '</tr>';
								}
							}else{
								echo "<tr><td colspan='5'>No Records Found.</td></tr>";
							}
							?>
			    	</tbody>

			</table>
		</div>
		
		<div class="col-md-6" id="">
			<strong>Settings</strong>
			<div class="row">
				<div class="col-md-4 box_right admin-box">
					<div class="admin-box-content text-center ">
						<a href="/admin/menu_settings/index/">
							<i class="fa fa-compass fa-2x" aria-hidden="true"></i>
							<br/>
							Menu Settings
						</a>
					</div>
				</div>
				<div class="col-md-4 box_right admin-box">
					<div class="admin-box-content text-center ">
						<a href="/admin/configuration/index/">
							<i class="fa fa-cog fa-2x" aria-hidden="true"></i>
							<br/>
							Configurations
						</a>
					</div>
				</div>
				<div class="col-md-4 box_bottom_alone admin-box">
					<div class="admin-box-content text-center ">
						<a href="/core/extension">
							<i class="fa fa-puzzle-piece fa-2x" aria-hidden="true"></i>
							<br/>
							Extensions
						</a>
					</div>
				</div>
				<div class="col-md-4 box_right admin-box">
					<div class="admin-box-content text-center ">
						
					</div>
				</div>
				<div class="col-md-4 box_right admin-box">
					<div class="admin-box-content text-center ">
						
					</div>
				</div>
				<div class="col-md-4 box_bottom_alone admin-box">
					<div class="admin-box-content text-center ">
						
					</div>
				</div>
				<div class="col-md-4 box_only_right admin-box">
					<div class="admin-box-content text-center ">
						
					</div>
				</div>
				<div class="col-md-4 box_only_right admin-box">
					<div class="admin-box-content text-center ">
						
					</div>
				</div>
				<div class="col-md-4 admin-box">
					<div class="admin-box-content text-center ">
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
