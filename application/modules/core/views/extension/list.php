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
				<?php 
					$message = $this->session->flashdata('message');
					if($message['result']){ ?>
					<div class="alert <?php echo ($message['result'] == 'success') ? 'alert-success' : 'alert-danger'?>">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<p><?php echo $message['message']; ?>
						</p>
					</div>
								<?php }?>
				<div class="actions pull-right">
					<a href="/admin/extension/add" class="btn btn-default"><i class="fa fa-plus-circle"></i> Add Extension</a>
					<a data-toggle="modal" data-target="#localExt" class="btn btn-default"><i class="fa fa-gear"></i> Install Local Extension</a>
				</div>

					<div class="col-md-12">
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th>Name</th>
									<th>Status</th>
									<th>Created</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
									  <?php
											if (is_array ( $extensions ) && count ( $extensions ) > 0) {
												foreach ( $extensions as $item ) {
													echo "<tr>";
													echo "<td>" . ucwords ( $item->module_name ) . "</td>";
													echo "<td>" . ucwords ( $item->status ) . "</td>";
													echo "<td>" . date ( 'd/m/Y', strtotime ( str_replace ( "/", "-", $item->created ) ) ) . "</td>";
													echo "<td>";
													echo '<a href="/core/extension/uninstall_extension/'.$item->id.'/'.$item->key.'" data-toggle="tooltip" data-placement="top" title="Uninstall"><i class="fa fa-share-square-o" style="color:#333"></i></a>';
													echo "</td>";
													echo "</tr>";
												}
											} else { 
												echo "<tr>";
												echo "<td colspan='4'>";
												echo "No records found";
												echo "</td>";
												echo "</tr>";
												
											}
											?>
									</tbody>
						</table>
					</div>
				</div>
			</section>
				<div id="localExt" class="modal fade" role="dialog">
				  <div class="modal-dialog">
				
				    <!-- Modal content-->
				    <div class="modal-content">
				      <div class="modal-header" style="background-color: transparent !important; border: none; border-bottom: 1px solid #e5e5e5;">
				        <button type="button" class="close" data-dismiss="modal">&times;</button>
				        <h4 class="modal-title">Install Extension</h4>				        
				      </div>
				      <div class="modal-body">
				      	
								    	
				      	<form action="/core/extension/extension_installer/local" method="post" enctype="multipart/form-data">
				      		<div class="row">
				      			<div>
					      			<div class="col-md-10"><label><h4>Install Local Extension</h4></label></div>
					      			<div class="col-md-12">
					      				<div class="col-md-4">
							      			<div class="form-group">
							      				<input class="form-control" type="text" name="key" placeholder="Extension Key"/>
							      			</div>
					      				</div>
					      			</div>
					      			<br>
					      			<div class="col-md-12">
										<div class="col-md-4">
											<div class="form-group">
												<input class="form-control" type="text" name="name" placeholder="Extension Name" />
											</div>
										</div>
									</div>
									<br>
									<div class="col-md-12">										
										<div class="col-md-4">
											<div class="form-group">
												<input  type="file" name="file" accept=".zip" />
											</div>
										</div>
									</div>
									<div class="col-md-12">
										<div class="col-md-4">
											<div class="form-group">
												<button type="submit" class="btn btn-primary">Install Extension</button>
											</div>
										</div>
									</div>
								</div>	     		
				      		</form>
						
				        
				      </div>
				      <div class="modal-footer">
				      </div>
				    </div>
				  </div>
				</div>
		</div>
	</div>
</div>