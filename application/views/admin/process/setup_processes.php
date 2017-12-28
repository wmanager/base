
<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-tasks"></i> Processes
			</h3>
		</div>
		<!-- /.widget-header -->

		<div class="widget-content">
			<section id="processes">
				<div class="filters pull-left"></div>
				<div class="actions pull-right">
					<a href="<?=site_url('/admin/setup_processes/add');?>"
						class="btn btn-default"><i class="fa fa-plus-circle"></i> New
						Process</a>
				</div>
				<div class="clearfix"></div>
				<hr></hr>
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th></th>
							<th>PROCESSES</th>
							<th></th>
							<th>STATUS</th>
							<th>POA Activities</th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					    		<?php
											$tf_array = array (
													't' => 'ATTIVO',
													'f' => 'DISATTIVO' 
											);
											if (is_array ( $processes )) {
												foreach ( $processes as $process ) {
													echo '<tr>';
													echo "<td width='40'></td>";
													echo "<td>$process->macro <p>$process->title</p></td>";
													if ($process->initial_count == 0) {
														echo "<td><i class='fa fa-warning' popover-placement='right' popover='Initial status is missing' popover-trigger='mouseenter'></i></td>";
													} else {
														echo "<td></td>";
													}
													echo "<td>$process->statuss</td>";
													echo "<td>";
													if ($process->act_count == 0) {
														echo $process->act_count;
													} else {
														echo '<a role="menuitem" tabindex="-1" href="' . site_url ( 'admin/setup_activities/' . $process->id ) . '">' . $process->act_count . "</a>";
													}
													echo "</td>";
													echo "<td width='100'>";
													echo '<div class="dropdown">';
													echo '<a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-list"></i></a>';
													echo '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';
													echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="' . site_url ( 'admin/setup_processes/edit/' . $process->id ) . '">Edit</a></li>';
													echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="' . site_url ( 'admin/setup_activities/' . $process->id ) . '">Setup POA</a></li>';
													if ($process->act_count == 0) {
														echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="' . base_url () . 'admin/setup_processes/delete/' . $process->id . '" class="delete-confirm" data-message="Sei sicuro di voler eliminare il record?">Elimina</a></li>';
													}
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