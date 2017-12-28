<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1>Database creation</h1>
			</div>
			<p>Connection to PostgresSQL was Successfull!</p>
			<p>Please Enter a name for the database that will host your application data.The database will be <strong>created automatically</strong> for you if the database doesn't exist already.</p>
			<br>
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
					<label for="database_name">Database name</label>
					<input type="text" class="form-control" id="database_name" name="database_name" placeholder="Enter a database name">
				</div>
	
				<button type="submit" class="btn btn-success pull-right btn-disabled">Proceed to Table Creation <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
			</form>
		</div>
	</div><!-- .row -->