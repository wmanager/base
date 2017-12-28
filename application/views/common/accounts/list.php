

<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>Client</h3>
		</div>
		<!-- /.widget-header -->


		<div class="widget-content">
			<section id="companies">
				<div class="filters pull-left">
					<form id="filters" name="filter" method="post"
						action="/common/accounts/" class="form-inline">
						<div class="form-group">
							<div class="input-group">
								<input class="form-control" type="text" name="search"
									value="<?=$this->session->userdata('filter_accounts')?>"
									placeholder="Client Name or Code">
							</div>
							<div class="input-group">
								<button type="submit" class="btn btn-primary">Search</button>
							</div>
						</div>

					</form>
				</div>
				<div class="clearfix"></div>
				<hr></hr>
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>Client</th>
							<th>Address</th>
							<th>Telephone</th>
							<th></th>

						</tr>
					</thead>
					<tbody>
						    		<?php
												if (is_array ( $accounts ) && !empty($accounts)) {
													
													foreach ( $accounts as $item ) {
														echo '<tr>';
														
														echo "<td><a href='/common/accounts/detail/$item->id'>$item->first_name $item->last_name</a></td>";
														
														echo "<td>$item->address $item->city $item->state<br>$item->zip $item->country</td>";
														echo "<td>$item->tel</td>";
														echo "<td>Created " . date ( 'd-m-Y H:i', strtotime ( str_replace ( '/', '-', $item->created ) ) ) . "</td>";														
														echo "<td width='10'>";
														
														echo "</td>";
														echo '</tr>';
													}
												} else {
													echo "<tr>";
													echo "<td colspan='5'>";
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