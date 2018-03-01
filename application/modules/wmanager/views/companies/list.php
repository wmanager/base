<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-building-o"></i> Companies
			</h3>
		</div>
		<!-- /.widget-header -->


		<div class="widget-content">
			<section id="companies">
				<div class="filters pull-left">
					<form id="filters" name="filter" method="post"
						action="/admin/companies/" class="form-inline">
						<div class="form-group">
							<select class="form-control" onchange="this.form.submit()"
								name="status">
								<option value="-"
									<?php if(!$this->session->userdata('filter_status')) echo 'selected'; ?>>Select Status</option>
								<option value="t"
									<?php if($this->session->userdata('filter_status')=='t') echo 'selected'; ?>>Active</option>
								<option value="f"
									<?php if($this->session->userdata('filter_status')=='f') echo 'selected'; ?>>Suspended</option>
							</select>
						</div>
						<div class="form-group">
							<div class="input-group">
								<input type="text" class="form-control" name="company"
									value="<?=$this->session->userdata('filter_company')?>"
									placeholder="Enter company name"> <span class="input-group-btn">
									<button class="btn btn-info" type="submit">Search</button>
								</span>
							</div>
						</div>
					</form>
				</div>
				<div class="actions pull-right">
					<a href="/admin/companies/add" class="btn btn-default"><i
						class="fa fa-plus-circle"></i> New Company</a>
				</div>
				<div class="clearfix"></div>
				<hr></hr>
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th></th>
							<th>Name</th>
							<th>Status</th>
							<th>Nation</th>
							<th>Contact</th>
							<th>Users</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					    		<?php
											$tf_array = array (
													't' => 'Active',
													'f' => 'Inactive' 
											);
											if (is_array ( $companies )) {
												foreach ( $companies as $company ) {
													echo '<tr>';
													if ($company->icon != '') {
														echo "<td width='40'><img class='img-circle' width='40' height='40' src='/uploads/companies/$company->icon'></td>";
													} else {
														echo "<td width='40'><img class='img-circle' width='40' height='40' src='/assets/img/anonym.png'></td>";
													}
													echo "<td>$company->name</td>";
													echo "<td>" . $tf_array [$company->active] . "</td>";
													echo "<td>$company->billing_address_country</td>";
													echo "<td>$company->contact</td>";
													echo "<td>" . count ( $this->company->get_users ( $company->id ) ) . "</td>";
													echo "<td width='10'>";
													echo '<div class="dropdown">';
													echo '<a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-list"></i></a>';
													echo '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';
													echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="' . base_url () . 'admin/companies/edit/' . $company->id . '">Modify</a></li>';
													echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="' . base_url () . 'admin/companies/delete/' . $company->id . '" class="delete-confirm" data-message="Are you sure you want to delete the record?">Delete</a></li>';
													echo '</ul>';
													echo '</div>';
													echo "</td>";
													echo '</tr>';
												}
											}
											?>
					    	</tbody>

				</table>
					    <?=$this->pagination->create_links();?>
					</section>
		</div>
	</div>
</div>