<div class="col-md-12">
	<div class="widget stacked ">
		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-building-o"></i> Edit Trouble Types
			</h3>
		</div>
		<!-- /.widget-header -->

		<div class="widget-content">
			<section id="companies">
				<?php
				if ($this->session->flashdata ( 'growl_success' )) {
					echo "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert'>×</a>" . $this->session->flashdata ( 'growl_success' ) . "</div>";
				} else {
					echo $this->session->flashdata ( 'growl_success' );
				}
				?>						
				<div class="alert" id="trouble_type_message"></div>
				<ul id="myTab" class="nav nav-tabs">
					<li class="active"><a href="#trouble_type_tab" data-toggle="tab">Trouble
							Type</a></li>
					<li class=""><a href="#report_process_tab" data-toggle="tab">Related
							Process</a></li>
					<li class=""><a href="#subtype_tab" data-toggle="tab">Subtypes</a></li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane active fade in" id="trouble_type_tab">
		            	<?= $this->form_builder->open_form(array('id' => 'edit_trouble_type', 'action' => 'admin/trouble_type/update_trouble_type', 'enctype' => 'multipart/form-data')); ?>												           
						<div class="form-group">
							<label class="col-md-2 control-label">Title*</label>
							<div class="col-md-8">
								<input type="text" id="trouble_type_title"
									name="trouble_type_title" placeholder="Title"
									class="form-control"
									value="<?php echo $this->input->post('trouble_type_title') != null ? $this->input->post('trouble_type_title') : $trouble_type->title; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Key*</label>
							<div class="col-md-8">
								<input type="text" id="trouble_type_key" name="trouble_type_key"
									placeholder="Key" class="form-control"
									onkeyup="check_unique_trouble_type()"
									onblur="check_unique_trouble_type()"
									style='text-transform: uppercase'
									value="<?php echo $this->input->post('trouble_type_key') != null ? $this->input->post('trouble_type_key') : $trouble_type->key; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Description</label>
							<div class="col-md-8">
								<textarea name="trouble_type_description"
									id="trouble_type_description" class="form-control"><?php echo $this->input->post('trouble_type_description') != null ? $this->input->post('trouble_type_description') : $trouble_type->description; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Severity*</label>
							<div class="col-md-8">
								<input type="number" min="1" id="trouble_type_severity"
									name="trouble_type_severity" placeholder="Severity"
									class="form-control"
									value="<?php echo $this->input->post('trouble_type_severity') != null ? $this->input->post('trouble_type_severity') : $trouble_type->severity; ?>">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2"></div>
							<div class="col-md-8">
								<div class="checkbox">
									<?php
									if ($this->input->post ( 'trouble_type_check_manual' )) {
										$trouble_type_check_manual = $this->input->post ( 'trouble_type_check_manual' );
									} else {
										$trouble_type_check_manual = $trouble_type->manual;
									}
									?>
									<label><input type="checkbox" name="trouble_type_check_manual"
										value="t" class="checkbox"
										<?php echo $trouble_type_check_manual == 't' ? 'checked' :''; ?>>
										Manual</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2"></div>
							<div class="col-md-8">
								<div class="checkbox">
									<?php
									if ($this->input->post ( 'trouble_type_check_active' )) {
										$trouble_type_check_active = $this->input->post ( 'trouble_type_check_active' );
									} else {
										$trouble_type_check_active = $trouble_type->active;
									}
									?>
									<label><input type="checkbox" name="trouble_type_check_active"
										value="t" class="checkbox"
										<?php echo $trouble_type_check_active == 't' ? 'checked' :''; ?>>
										Active</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<input type="hidden" name="trouble_type_id" id="trouble_type_id"
									value="<?php echo $trouble_type->id;?>"> <input type="hidden"
									name="page_number" value="<?php echo $page_number;?>">
								<button id="trouble_type_submit" type="submit"
									class="btn btn-success"
									onsubmit="return trouble_type_submit_check()"
									onclick="return trouble_type_submit_check()">Update</button>
								<button type="button" onClick="window.history.back()"
									class="btn btn-default">Cancel</button>

							</div>
						</div>
						<?= $this->form_builder->close_form(); ?>
					</div>
					<div class="tab-pane fade in" id="report_process_tab">	
						<?= $this->form_builder->open_form(array('id' => 'edit_related_process', 'action' => 'admin/trouble_type/update_relared_process', 'enctype' => 'multipart/form-data')); ?>
						<div class="actions pull-right">
							<a href="javascript:void(0);" class="btn btn-default"
								id="add_new_related_process" data-ref="related_process_new"><i
								class="fa fa-plus-circle"></i> Add</a>
						</div>
						<div class="clearfix"></div>
						<?= $form_related_process; ?>
						<div class="form-group">
							<div class="col-md-offset-0 col-md-10">
								<input type="hidden" name="trouble_type_id_for_related_process"
									id="trouble_type_id_for_related_process"
									value="<?php echo $trouble_type->id;?>"> <input type="hidden"
									name="page_number" value="<?php echo $page_number;?>">
								<button id="related_process_submit" type="submit"
									class="btn btn-success pull-left">Save</button>
								&nbsp;
								<button type="button" onClick="window.history.back()"
									class="btn btn-default">Cancel</button>
							</div>
						</div>
						<?= $this->form_builder->close_form(); ?>
					</div>
					<div class="tab-pane fade in" id="subtype_tab">	
						<?= $this->form_builder->open_form(array('id' => 'edit_troubles_subtypes', 'action' => 'admin/trouble_type/update_troubles_subtypes', 'enctype' => 'multipart/form-data')); ?>
						<div class="actions pull-right">
							<a href="javascript:void(0);" class="btn btn-default"
								id="add_new_troubles_subtype" data-ref="troubles_subtype_new"><i
								class="fa fa-plus-circle"></i> Add</a>
						</div>
						<div class="clearfix"></div>
						<?= $form_troubles_subtypes; ?>
						<div class="form-group">
							<div class="col-md-offset-0 col-md-10">
								<input type="hidden"
									name="trouble_type_id_for_troubles_subtypes"
									id="trouble_type_id_for_troubles_subtypes"
									value="<?php echo $trouble_type->id;?>"> <input type="hidden"
									name="page_number" value="<?php echo $page_number;?>">
								<button id="subtype_submit" type="submit"
									class="btn btn-success pull-left">Save</button>
								&nbsp;
								<button type="button" onClick="window.history.back()"
									class="btn btn-default">Cancel</button>
							</div>
						</div>
						<?= $this->form_builder->close_form(); ?>
					</div>
				</div>

			</section>
		</div>
	</div>
</div>

