<div class="row">
	<div>
		<div class="col-xs-6">
			<a href="/"><img style="height: 50px" title=""
						alt="Wmanager" src="/assets/img/logo.png"></a>
		</div>
		<div class="col-xs-6 text-right">
			<h3 class="login-title">User login</h3>
		</div>
	</div>
	<div class="col-md-12">
		<hr></hr>
      <?php if(!empty($message)) echo '<div id="infoMessage" class="alert alert-warning">'.$message.'</div>';?>

      <?php echo form_open("auth/login", array('class' => 'form-horizontal')); ?>
      <form class="form-horizontal" accept-charset="utf-8" method="post"
			action="/auth/login">
      <?php $error = (isset($field['name'])) ? form_error($field['name']) : NULL; ?>
      <div
				class="control-group md-login-text <? if (!empty($error)): ?>error<? endif; ?>">
				<div class="form-group">
              <?php echo form_label($this->lang->line('login_identity_label'), 'identity', array('class' => 'col-sm-4 control-label')); ?>
              <div class="col-sm-8"><?php echo form_input($identity, NULL, 'class="form-control"'); ?></div>
				</div>

				<div class="form-group">
              <?php echo form_label($this->lang->line('login_password_label'), 'password', array('class' => 'col-sm-4 control-label')); ?>
              <div class="col-sm-8"><?php echo form_input($password, NULL, 'class="form-control"'); ?></div>
				</div>

				<div class="form-group">
					<div class="col-sm-offset-4 col-sm-8">
						<div class="checkbox">
							<label>
                  <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"'); ?> Remember me
              </label>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-offset-4 col-sm-8"><?php echo form_submit(array('class' => 'btn btn-primary btn-large', 'name' => 'login', 'value' => 'Login')); ?></div>
				</div>
			</div>
      <?php echo form_close(); ?>
    
	
	</div>
</div>