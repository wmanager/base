<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1>Tables creation</h1>
			</div>
			<?php if($method == 'NEW'){?>
				<p>Your new database <code><?= $_COOKIE['db_name'] ?></code> has been successfully created.</p>
				<p>Please click the "Proceed" button below to generate the required tables for your application.</p>
			<?php }else if($method == 'OLD'){?>
				<p>The database <code><?= $_COOKIE['db_name'] ?></code> already exists and we have verified the its existance.</p>
				<p>Please click the "Proceed" button below to generate the required tables for your application in same database or else please goto previous step and use a different database name.</p>
			<?php }?>
			<?php
				if (validation_errors()) {
					echo '<div class="alert alert-danger" role="alert">' . validation_errors() . '</div>';
				}
				if (isset($error)) {
					echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
				}
			?>
			<?php echo form_open(); ?>
				<input type="hidden" id="db_name_cookie" name="db_name_cookie" value="<?= $_COOKIE['db_name'] ?>">
				<a href="/install/database_creation" class="btn btn-success pull-left btn-disabled" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Go back to database creation.</a>
				<button type="submit" class="btn btn-success btn-disabled pull-right">Proceed to site settings <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
			</form>
		</div>
	</div><!-- .row -->