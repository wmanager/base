<!-- Modal HTML -->

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"
				aria-hidden="true">&times;</button>
			<h4 class="modal-title">Assign Attachment</h4>
		</div>
		<div class="modal-body">
			<section id="attachment">
            	<?php if(isset($error)){?>
            		<h3>
					Some error has occured!! No items can be found.
					<h3>
            	<?php }else{?>
					<?= $this->form_builder->open_form(array('action' => '','id' => 'attachment_form', 'enctype' => 'multipart/form-data')); ?>
                		<?= $form_attachment; ?>
                		<input type="hidden" name="id"
							value="<?php echo $attach_id; ?>" /> <input type="hidden"
							name="id_activity" value="<?php echo $activity_id; ?>" />
                <?php }?>
                <?= $this->form_builder->close_form(); ?>
                
			
			</section>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <?php if(!isset($error)){?>
                <button type="button" class="btn btn-primary"
				id="save_attachment">Save</button>
                <?php }?>
            </div>
	</div>
</div>