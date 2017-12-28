<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>Extension Manager</h3>
		</div>
		<!-- /.widget-header -->


		<div class="widget-content">
			<section id="extension">
				<div class="clearfix"></div>
				<div class="row">
				<div class="actions pull-right">
					<a href="/admin/extension/add" class="btn btn-default"><i class="fa fa-plus-circle"></i> Add Extension</a>
				</div>
					<div class="col-md-12">
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th>Name</th>
									<th>Description</th>
									<th>Status</th>
									<th>Created</th>
									<th>Options</th>
								</tr>
							</thead>
							<tbody>
									  <?php
											if (is_array ( $extensions ) && count ( $extensions ) > 0) {
												foreach ( $extensions as $item ) {
													echo "<tr>";
													echo "<td>" . ucwords ( $item->module_name ) . "</td>";
													echo "<td>$item->description</td>";
													echo "<td>" . ucwords ( $item->status ) . "</td>";
													echo "<td>" . date ( 'd/m/Y H:i:s', strtotime ( str_replace ( "/", "-", $item->created ) ) ) . "</td>";
													echo "<td>";
													echo '<a href="#" class="btn" title="Download Zip"><i class="fa fa-download" aria-hidden="true"></i></a>';
													echo '<a href="#" class="btn" title="Uninsatall Extension"><i class="fa fa-chain-broken" aria-hidden="true"></i></a>';
													echo "</td>";
													echo "</tr>";
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
</div>