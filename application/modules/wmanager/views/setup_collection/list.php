<div class="col-md-12">
	<div class="widget stacked ">

	            <div class="widget-header">
	              <i class="icon-pencil"></i>
	              <h3><i class="fa fa-tasks"></i> Collections</h3>
	            </div> <!-- /.widget-header -->

				<div class="widget-content">
					<section id="processes">
						<div class="filters pull-left">
							
						</div>
						<div class="actions pull-right">
							<a href="<?=site_url('/admin/setup_collection/add');?>" class="btn btn-default"><i class="fa fa-plus-circle"></i> New Collection </a>
						</div>
						<div class="clearfix"></div>
						<hr></hr>
						<table class="table table-striped table-hover">
					    	<thead>
					    		<tr>
					    			<th> </th>
					    			<th>Collection name</th>
					    			<th>Description</th>
					    			<th> </th>
					    		</tr>
					    	</thead>
					    	<tbody>
					    		<?php
					    			if((is_array($collections)) && !empty($collections)) {
					    				foreach($collections as $collection){
						    				echo '<tr>';						    					
						    					echo "<td width='40'></td>";
						    					echo "<td>$collection->title</td>";
						    					echo "<td>$collection->description</td>";
						    					echo "<td width='100'>";
						    						echo '<div class="dropdown">';
						    							 echo '<a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-list"></i></a>';
														  echo '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';
														    echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="'.site_url('admin/setup_collection/edit/'.$collection->id).'">Edit</a></li>';
														    echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="'.site_url('admin/setup_collection/delete/'.$collection->id).'" class="delete-confirm" data-message="Sei sicuro di voler eliminare il record?">Elimina</a></li>';
														  echo '</ul>';
													echo '</div>';
						    					echo "</td>";
						    					
						    					
						    				echo '</tr>';
						    			}
						    		} else {
												echo '<tr>';
												echo '<td colspan="4">';
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