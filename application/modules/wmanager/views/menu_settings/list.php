<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-building-o"></i> Menu Settings
			</h3>
		</div>
		<!-- /.widget-header -->


		<div class="widget-content">
			<section>
			<br>
			<?php
					if($this->session->flashdata('result_error')){
						echo "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>×</a>".$this->session->flashdata('result_error')."</div>";
					}else if($this->session->flashdata('result_success')){
						echo "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert'>×</a>".$this->session->flashdata('result_success')."</div>";
					}
				?>	
				<div class="actions pull-right">
					<a href="/admin/menu_settings/add_menu" data-toggle="modal" data-target="#responseModal" class="btn btn-default"><i class="fa fa-plus-circle"></i> New Menu</a>
				</div>
				<br>
				<h4>Preview</h4>
				<?= menu_display() ?>	
				
				<hr></hr>
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th></th>
							<th>Lable</th>
							<th>Link</th>
							<th>Access</th>
							<th>Is Child?</th>
							<th>Module</th>
							<th>Order</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					    		<?php
											if (is_array ( $menu )) {
												foreach ( $menu as $row ) {
													echo '<tr>';
													echo "<td width='40'></td>";
													echo "<td>$row->label</td>";
													echo "<td>$row->link</td>";
													echo "<td>$row->access</td>";
													
													if($row->is_child == 't'){
														$child = "Yes <br> <b>Parent: $row->parent_name</b>";
													}else{
														$child = 'No';
													}
													
													echo "<td>$child</td>";
													echo "<td>$row->module</td>";
													echo "<td>$row->order</td>";
													
													echo "<td width='100'>";
													echo '<div class="dropdown">';
													echo '<a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-list"></i></a>';
													echo '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';
													echo '<li role="presentation"><a href="/admin/menu_settings/edit_menu/'.$row->id.'/'.$row->parent_id.'" data-toggle="modal" data-target="#responseEditModal">Edit</a></li>';
													if($row->is_child == 'f') 
														echo '<li role="presentation"><a href="/admin/menu_settings/add_child_menu/'.$row->id.'/'. $row->label.'" data-toggle="modal" data-target="#responseAddModal">Add Child</a></li>';
													
													echo '<li role="presentation"><a href="/admin/menu_settings/delete_menu/'.$row->id.'" role="menuitem" tabindex="-1" class="delete-confirm" data-message="Are you sure you want to delete?">Delete</a></li>';
													echo '</ul>';
													echo '</div>';
													echo "</td>";
													
													echo '</tr>';
												}
											}
											?>
					    	</tbody>

				</table>			
			</section>
		</div>
	</div>
<div id="responseModal" class="modal fade in">
	<div class="modal-dialog">
		<div class="modal-content"> </div>
	</div>
</div>
<div id="responseEditModal" class="modal fade in">
	<div class="modal-dialog">
		<div class="modal-content"> </div>
	</div>
</div>
<div id="responseAddModal" class="modal fade in">
	<div class="modal-dialog">
		<div class="modal-content"> </div>
	</div>
</div>
