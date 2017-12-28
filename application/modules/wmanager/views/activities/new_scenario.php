<!-- Modal HTML -->

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"
				aria-hidden="true">&times;</button>
			<h4 class="modal-title">New Scenario</h4>
		</div>
		<div class="modal-body">
			<section id="variables">
					<?= $this->form_builder->open_form(array('action' => '','id' => 'new_scenario','enctype' => 'multipart/form-data')); ?>
                	<?= $form_scenario; ?>
                	<?php if(isset($scenario_id)){ ?>
                	<input type="hidden" name="sceneid"
					value="<?php echo $scenario_id; ?>" /> 
                	<?php } ?>
                	<?= $this->form_builder->close_form(); ?>
                </section>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <?php if(isset($scenario_id)){ ?>
                	<button type="button" class="btn btn-primary"
				id="update_exit_scenario">Update</button>
                <?php } else {?>
                <button type="button" class="btn btn-primary"
				id="save_exit_scenario">Save</button>
                <?php } ?>
            </div>
	</div>
</div>
