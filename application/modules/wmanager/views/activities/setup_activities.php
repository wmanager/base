
<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-tasks"></i> Setup Types of activities - <?=$process_title;?></h3>
		</div>
		<!-- /.widget-header -->


		<div class="widget-content">
			<section id="activities">
						<?php
						if ($this->session->flashdata ( 'growl_error' )) {
							echo "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>×</a>" . $this->session->flashdata ( 'growl_error' ) . "</div>";
						} else if ($this->session->flashdata ( 'growl_success' )) {
							echo "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert'>×</a>" . $this->session->flashdata ( 'growl_success' ) . "</div>";
						}
						?>
						<div class="filters pull-left"></div>
				<div class="actions pull-right">
					<a
						href="<?=site_url('/admin/setup_activities/add/'.$process_id);?>"
						class="btn btn-default"><i class="fa fa-plus-circle"></i> Add
						Activity</a>
				</div>
				<div class="clearfix"></div>
				<hr></hr>
				<table class="table table-striped table-hover FieldContainer">
					<thead>
						<tr>
							<th></th>
							<th>ACTIVITY</th>
							<th>Description</th>
							<th>Form type</th>
							<th>Role</th>
							<th></th>
							<th>Status</th>
							<th>Variables</th>
							<th>Exit Scenarios</th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					    		<?php

											if ((is_array ( $activities )) && !empty($activities)) {
												foreach ( $activities as $key => $activity ) {
													$index = intval ( $key ) + 1;
													echo '<tr class="OrderingField" id="actvity' . $activity->id . '">';
													echo "<td width='40'>" . $index . "</td>";
													echo "<td width='200'>" . str_replace ( "_", " ", $activity->key ) . "</td>";
													echo "<td >" . $activity->description . "</td>";
													echo "<td >" . str_replace ( "_", " ", $activity->form_title ) . "</td>";
													echo "<td >" . $activity->role . "</td>";
													if ($activity->initial_count == 0) {
														echo "<td><i class='fa fa-warning' popover-placement='right' popover='Initial status is missing' popover-trigger='mouseenter'></i></td>";
													} else {
														echo "<td></td>";
													}
													echo "<td >" . $activity->statuss . "</td>";
													echo "<td width='30'>" . $activity->count . "</td>";
													echo "<td width='60'>" . $activity->exit_count . "</td><td width='30'>";
													echo "<a class='h4 ascending' href='javascript:void(0)' data-ref='' title='Order'><i class='fa fa-sort-asc sorting' data-value='up' data-index='$activity->ordering' data-actid='$activity->id'></i></a>";
													echo "<a class='h4 descending' href='javascript:void(0)' data-ref='' title='Order'><i class='fa fa-sort-desc sorting' data-value='down' data-index='$activity->ordering' data-actid='$activity->id'></i></a>";
													echo "</td><td width='100'>";
													echo '<div class="dropdown">';
													echo '<a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-list"></i></a>';
													echo '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';
													echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="' . site_url ( 'admin/setup_activities/edit/' . $process_id . '/' . $activity->id ) . '">Edit</a></li>';													
													echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="' . base_url () . 'admin/setup_activities/delete/' . $process_id . '/' . $activity->id . '" class="delete-confirm" data-message="Are you sure you want to delete the record?">Delete</a></li>';
													echo '</ul>';
													echo '</div>';
													echo "</td>";
													
													echo '</tr>';
												}
											} else {
												echo '<tr>';
												echo '<td colspan="11">';
												echo 'No Record Found';
												echo '</td>';
												echo '</tr>';
											}
											?>
											
					    	</tbody>

				</table>
					    <?=$this->pagination->create_links();?>
					</section>
		</div>
	</div>
</div>
