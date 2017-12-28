<div class="col-md-12">
	<div class="widget stacked ">
		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-building-o"></i> Add Trouble Types
			</h3>
		</div>
		<!-- /.widget-header -->

		<div class="widget-content">
			<section id="companies">						
				<?php
				if ($this->session->flashdata ( 'growl_error' )) {
					echo "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>×</a>" . $this->session->flashdata ( 'growl_error' ) . "</div>";
				}
				?>		
				<div class="alert" id="trouble_type_message"></div>			
				<?= $this->form_builder->open_form(array('id' => 'add_trouble_type', 'action' => 'common/trouble_type/add', 'enctype' => 'multipart/form-data')); ?>
	            
	            <ul id="myTab" class="nav nav-tabs">
					<li class="active"><a href="#general" data-toggle="tab">Trouble
							Type</a></li>
					<li class="disabled"><a href="javascript:void(0);"
						data-toggle="tab">Related Process</a></li>
					<li class="disabled"><a href="javascript:void(0);"
						data-toggle="tab">Subtypes</a></li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane active fade in" id="general">
						<div class="form-group">
							<label class="col-md-2 control-label">Title*</label>
							<div class="col-md-8">
								<input type="text" id="trouble_type_title"
									name="trouble_type_title" placeholder="Title"
									class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Key*</label>
							<div class="col-md-8">
								<input type="text" id="trouble_type_key" name="trouble_type_key"
									placeholder="Key" class="form-control"
									onkeyup="check_unique_trouble_type()"
									onblur="check_unique_trouble_type()"
									style='text-transform: uppercase'>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Description</label>
							<div class="col-md-8">
								<textarea name="trouble_type_description"
									id="trouble_type_description" class="form-control"></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2"></div>
							<div class="col-md-8">
								<div class="checkbox">
									<label><input type="checkbox" name="trouble_type_check_credito"
										value="t" class="checkbox"> Bloccante Credito</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2"></div>
							<div class="col-md-8">
								<div class="checkbox">
									<label><input type="checkbox"
										name="trouble_type_check_manutenzione" value="t"
										class="checkbox"> Bloccante Manutenzione</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Severity*</label>
							<div class="col-md-8">
								<input type="number" min="1" id="trouble_type_severity"
									name="trouble_type_severity" placeholder="Severity"
									class="form-control">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2"></div>
							<div class="col-md-8">
								<div class="checkbox">
									<label><input type="checkbox" name="trouble_type_check_manual"
										value="t" class="checkbox" checked> Manual</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2"></div>
							<div class="col-md-8">
								<div class="checkbox">
									<label><input type="checkbox" name="trouble_type_check_active"
										value="t" class="checkbox" checked> Active</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<button type="submit" class="btn btn-success"
									onsubmit="return trouble_type_submit_check()"
									onclick="return trouble_type_submit_check()">Save</button>
								<button type="button" onClick="window.history.back()"
									class="btn btn-default">Cancel</button>
							</div>
						</div>
					</div>
				</div>
				<?= $this->form_builder->close_form(); ?>
			</section>
		</div>
	</div>
</div>