<div class="col-md-12">
	<div class="widget stacked ">

	            <div class="widget-header">
	              <i class="icon-pencil"></i>
	              <h3><i class="fa fa-key"></i> API Keys</h3>
	            </div> <!-- /.widget-header -->

					
				<div class="widget-content">
					<section id="keys">
						<div class="actions pull-right">
							<a href="/admin/api_keys/add" class="btn btn-default"><i class="fa fa-plus-circle"></i> Nuova API Key</a>
						</div>
						<div class="clearfix"></div>
						<hr></hr>
						<table class="table table-striped table-hover">
					    	<thead>
					    		<tr>
					    			<th> </th>
					    			<th> </th>
					    			<th>Key</th>
					    			<th>Status</th>
					    			<th> </th>
					    			<th> </th>
					    		</tr>
					    	</thead>
					    	<tbody>
					    		<?php
					    			$tf_array = array('t' => 'Active','f' => 'Suspended');
					    			if(is_array($keys)){
						    			foreach($keys as $key){
						    				echo '<tr>';
						    					if($key->icon!=''){
						    						echo "<td width='40'><img class='img-circle' width='40' height='40' src='/uploads/companies/$company->icon'></td>";
						    					} else {
						    						echo "<td width='40'><img class='img-circle' width='40' height='40' src='/assets/img/anonym.png'></td>";
						    					}
						    					echo "<td>$key->name</td>";
						    					echo "<td>$key->key</td>";
						    					echo "<td>".$tf_array[$key->active]."</td>";
						    					echo "<td>Created on ".date('d-m-Y',strtotime($key->created))."</td>";
						    					echo "<td><a href='".base_url()."admin/api_keys/edit/$key->id'>Modifica</a></td>";
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