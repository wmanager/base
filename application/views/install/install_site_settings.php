<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<div class="row">
		<div class="col-md-12">			
			<div class="page-header">
				<h1>Site settings</h1>
			</div>
			
			<?php
				if (validation_errors()) {
					echo '<div class="alert alert-danger" role="alert">' . validation_errors() . '</div>';
				}
				if (isset($error)) {
					echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
				}
			?>
				<form action="" method="post" enctype="multipart/form-data">
				<div class="row">				
					<div class="col-md-6">
						<div class="form-group">
							<label for="admin_username">Admin username</label>
							<input type="text" class="form-control" id="admin_username" name="admin_username" placeholder="Enter your admin username">
							<p class="help-block">This will be your admin account username on the wmanger</p>
						</div>
						<div class="form-group">
							<label for="admin_email">Admin email</label>
							<input type="email" class="form-control" id="admin_email" name="admin_email" placeholder="Enter your admin account email">
							<p class="help-block">This email will be used to send and receive notifications</p>
						</div>
						<div class="form-group">
							<label for="admin_password">Admin password</label>
							<input type="password" class="form-control" id="admin_password" name="admin_password" placeholder="Enter your admin password">
							<p class="help-block">This will be the password for your admin account</p>
						</div>
						<div class="form-group">
							<label for="admin_password_confirm">Confirm password</label>
							<input type="password" class="form-control" id="admin_password_confirm" name="admin_password_confirm" placeholder="Confirm your admin password">
							<p class="help-block">Must match the password you entered above</p>
						</div>
						<div class="form-group">
							<label for="install_base_url">Base url</label>
							<input type="text" class="form-control" id="install_base_url" name="install_base_url" placeholder="Enter your forum base url" readonly value="<?php echo $this->config->item('base_url'); ?>">
							<p class="help-block"></p>
						</div>						
					</div>
					<div class="col-md-6">
						<hr class="visible-sm visible-xs">
						<div class="form-group">
							<label for="company">Company Name</label>
							<input type="text" class="form-control" id="company" name="company" placeholder="Enter company name">
							<p class="help-block">This will be your company name</p>							
						</div>
						<div class="form-group">
							<label for="first_name">First Name</label>
							<input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter first name">
							<p class="help-block">This will be your first name on the wmanger</p>							
						</div>
						<div class="form-group">
							<label for="last_name">Last Name</label>
							<input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter last name">
							<p class="help-block">This will be your last name on the wmanger</p>									
						</div>
						<div class="form-group">
							<label for="logo">Logo</label>
								<input type="file" class="form-control-file" id="logo" name="logo" accept=".png">
								<p class="help-block">This will be logo for wmanager (300 X 86) pixel and should be less thenn 1MB</p>							
						</div>
					</div>
				</div><!-- .row -->
				<div class="row">
					<div class="col-md-12">
						<hr>
						<button type="submit" class="btn btn-success pull-right btn-disabled">Proceed to Finish <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
					</div>
				</div><!-- .row -->
			</form>
		</div>
	</div><!-- .row -->
