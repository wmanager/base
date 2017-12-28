	<div class="row">
		<div class="col-md-12">			
			<div class="page-header">
				<h1>Postgresql connection</h1>
			</div>
			
			<?php
				if (validation_errors()) {
					echo '<div class="alert alert-danger" role="alert">' . validation_errors() . '</div>';
				}
				if (isset($error)) {
					echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
				}
			?>
			<?php echo form_open(); ?>
				<div class="form-group">
					<label for="install_db_hostname">Hostname</label>
					<input type="text" class="form-control" id="install_db_hostname" name="install_db_hostname" placeholder="Enter your pgsql hostname">
				</div>
				<div class="form-group">
					<label for="install_db_username">Username</label>
					<input type="text" class="form-control" id="install_db_username" name="install_db_username" placeholder="Enter your pgsql username">
				</div>
				<div class="form-group">
					<label for="install_db_password">Password</label>
					<input type="text" class="form-control" id="install_db_password" name="install_db_password" placeholder="Enter your pgsql password">
				</div>
				
				<button type="submit" class="pull-right btn btn-success btn-disabled">Proceed to database creation <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
			</form>
		</div>
	</div><!-- .row -->