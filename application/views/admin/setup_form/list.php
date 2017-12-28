<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-tasks"></i> Form
			</h3>
		</div>
		<!-- /.widget-header -->

		<div class="widget-content">
			<section id="processes">
				<div class="filters pull-left">
					<form id="filters" name="filter" method="post"
						action="/admin/setup_form/" class="form-inline">
						<div class="form-group">
							<div class="input-group">
								<input class="form-control" type="text" name="filter_setup_form"
									value="<?=$this->session->userdata('filter_setup_form')?>"
									placeholder="Cerca Title">
							</div>
							<div class="input-group">
								<button type="submit" class="btn btn-primary">Cerca</button>
							</div>
						</div>

					</form>
				</div>
				<div class="actions pull-right">
					<a href="<?=site_url('/admin/setup_form/add');?>"
						class="btn btn-default"><i class="fa fa-plus-circle"></i> New Form</a>
				</div>
				<div class="clearfix"></div>
				<hr></hr>
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th></th>
							<th>Form Type</th>
							<th>Title</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					    		<?php
											if (is_array ( $form_data )) {
												foreach ( $form_data as $form ) {
													echo '<tr>';
													echo "<td width='40'></td>";
													echo "<td>$form->type</td>";
													echo "<td>$form->title</td>";
													echo "<td width='100'>";
													echo '<div class="dropdown">';
													echo '<a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-list"></i></a>';
													echo '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';
													echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="' . site_url ( 'admin/setup_form/edit/' . $form->id ) . '">Edit</a></li>';
													echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="' . site_url ( 'admin/setup_form/delete/' . $form->id ) . '" class="delete-confirm" data-message="Sei sicuro di voler eliminare il record?">Elimina</a></li>';
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