
<form name="crm" method="post"
	action="/common/cases/create_thread_account" id="crm_create_thread">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
		<h4 class="modal-title">Nuova richiesta CRM</h4>
	</div>
	<!-- /modal-header -->
	<div class="modal-body">
		<div class="form-group">
			<label>Tipologia</label> <select name="type" class="form-control"
				id="types">
            			<?php
															foreach ( $type as $item ) {
																echo "<option value='$item->key'>$item->title</option>";
															}
															?>
            		</select>
		</div>
		<div class="form-group">
			<label>Richiesta</label> <select name="activity" class="form-control"
				id="activities">
				<option></option>
			</select>
		</div>
		<div class="form-group">
			<label>POD</label> <select name="be" class="form-control" id="be">
				<option></option>
			</select> <input type="hidden" name="customer" id="be_user"
				value="<?=$this->uri->segment(4)?>">
		</div>
	</div>
	<!-- /modal-body -->
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
		<button type="submit" class="btn btn-primary">Procedi</button>
	</div>
	<!-- /modal-footer -->


</form>