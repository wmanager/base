<div class="modal-header" style="background-color: transparent !important; border: none; border-bottom: 1px solid #e5e5e5;">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<?php if($edit_id) { ?>
		<h4 class="modal-title">Edit Menu</h4>
	<?php } else if($parent_id) { ?>
		<h4 class="modal-title">Add Child Menu - <?php echo $parent_name; ?></h4>
	<?php } else { ?>
		<h4 class="modal-title">Add New Menu</h4>
	<?php } ?>
</div>
<?php if($edit_id) { ?>
<?= $this->form_builder->open_form(array('action' => 'admin/menu_settings/edit_menu/'.$edit_id)); ?>
<?php } else if($parent_id) { ?>
<?= $this->form_builder->open_form(array('action' => 'admin/menu_settings/add_child_menu/'.$parent_id)); ?>
<?php } else { ?>
<?= $this->form_builder->open_form(array('action' => 'admin/menu_settings/add_menu')); ?>
<?php } ?>
<div class="modal-body">
	<?php echo $form_menu; ?>
</div>

<div class="modal-footer">
	<button type="submit" class="btn btn-success">Save</button>
	<button class="btn btn-default" data-dismiss="modal">Close</button>
</div>
<?= $this->form_builder->close_form(); ?>
