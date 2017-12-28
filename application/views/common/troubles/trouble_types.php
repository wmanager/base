<div class="col-md-12">
	<div class="widget stacked ">
		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-tasks"></i> Trouble Types
			</h3>
		</div>
		<!-- /.widget-header -->

		<div class="widget-content">
			<section id="processes">
				<?php
				if ($this->session->flashdata ( 'growl_success' )) {
					echo "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert'>×</a>" . $this->session->flashdata ( 'growl_success' ) . "</div>";
				} else {
					echo $this->session->flashdata ( 'growl_success' );
				}
				?>						
				<div class="actions pull-right">
					<a href="<?=site_url('/common/trouble_type/add');?>"
						class="btn btn-default"><i class="fa fa-plus-circle"></i> New </a>
				</div>
				<div class="clearfix"></div>
				<hr></hr>
				<table class="table table-striped table-hover md-break-text">
					<thead>
						<tr>
							<th>Title</th>
							<th>Description</th>
							<th>Bloccante Credito</th>
							<th>Bloccante Manutenzione</th>
							<th>Severity</th>
							<th>Manual</th>
							<th>Active</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
			    		<?php
									if (! empty ( $trouble_type_list )) {
										foreach ( $trouble_type_list as $result ) {
											?>
									<tr>
							<td><?php echo $result['title'];?></td>
							<td><?php echo $result['description'] ?></td>
							<td><?php echo ($result['bloccante_credito'] == 't') ? 'true' : 'false'; ?></td>
							<td><?php echo ($result['bloccante_tecnico'] == 't') ? 'true' : 'false'; ?></td>
							<td><?php echo $result['severity']; ?></td>
							<td><?php if($result['manual'] == 't'){ echo 'Yes';} else { echo 'No';} ?></td>
							<td><?php if($result['active'] == 't'){ echo 'Active';} else { echo 'Inactive';} ?></td>
							<td width='100'>
								<div class="dropdown">
									<a href="#" data-toggle="dropdown" class="dropdown-toggle"><i
										class="fa fa-list"></i></a>
									<ul class="dropdown-menu" role="menu"
										aria-labelledby="dropdownMenu1">
										<li role="presentation"><a role="menuitem" tabindex="-1"
											href="<?= site_url('common/trouble_type/edit/'.$result['id'].'/'.$page_number) ?>">Edit</a></li>
										<li role="presentation"><a role="menuitem" tabindex="-1"
											href="<?= site_url('common/trouble_type/delete/'.$result['id'].'/'.$page_number) ?>"
											class="delete-confirm"
											data-message="Sei sicuro di voler eliminare il trouble type?">Elimina</a></li>
									</ul>
								</div>
							</td>
						</tr>					    					
			    				<?php
										}
									} else {
										echo "<tr>";
										echo "<td colspan='7'>";
										echo "No reports found";
										echo "</td>";
										echo "</tr>";
									}
									?>								
			    	</tbody>

				</table>
			    <?=$this->pagination->create_links();?>
			</section>
			<div class="pull-right text-muted">(<?php echo $total_rows?> count)</div>
		</div>
	</div>
</div>