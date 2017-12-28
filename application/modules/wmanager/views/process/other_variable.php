<!-- Modal HTML -->

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"
				aria-hidden="true">&times;</button>
			<h4 class="modal-title">New Variable</h4>
		</div>
		<div class="modal-body">
			<section id="variables">
					<?= $this->form_builder->open_form(array('action' => '','id' => 'other_var','enctype' => 'multipart/form-data')); ?>
                	<ul id="myTab" class="nav nav-tabs">
					<li class="active"><a href="#variable" data-toggle="tab">Variable</a></li>
					<li class=""><a href="#values" data-toggle="tab">Pre-defined values</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active fade in" id="variable">
                			<?= $form_variable; ?>
                		</div>
					<div class="tab-pane fade in" id="values">
						<div class="filters pull-left"></div>
						<div class="actions pull-right">
							<a href="javascript:void(0);" class="btn btn-default"
								id="add_new_status" data-ref="other"><i
								class="fa fa-plus-circle"></i> Add</a>
						</div>
						<div class="clearfix"></div>
                			<?= $form_status; ?>
                		</div>
				</div>
                	<?php if(isset($variable_id)){ ?>
                	<input type="hidden" name="varid"
					value="<?php echo $variable_id; ?>" /> 
                	<?php } ?>
                	<?= $this->form_builder->close_form(); ?>
                </section>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <?php if(isset($variable_id)){ ?>
                	<button type="button" class="btn btn-primary"
				id="update_variable">Update</button>
                <?php } else {?>
                <button type="button" class="btn btn-primary"
				id="save_variable">Save</button>
                <?php } ?>
            </div>
	</div>
</div>