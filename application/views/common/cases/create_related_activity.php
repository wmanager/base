
<form name="related" method="post"
	action="/common/cases/create_related_activity"
	id="create_related_activity">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
		<h4 class="modal-title">Aggiungi attività correlate</h4>
	</div>
	<!-- /modal-header -->
	<div class="modal-body">
            
            		<?php
														foreach ( $related as $item ) {
															echo "<div class='checkbox'><label><input type='checkbox' name='related[]' value='$item->key'>$item->title</label></div>";
														}
														?>
            
                <input type="hidden" value="<?=$thread?>" name="thread">
		<input type="hidden" value="<?=$trouble?>" name="trouble">
	</div>
	<!-- /modal-body -->
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
		<button type="submit" class="btn btn-primary">Procedi</button>
	</div>
	<!-- /modal-footer -->
</form>