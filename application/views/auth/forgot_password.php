<div class="row">
	<div class="col-md-12">
		<h4>Forgot password</h4>
		<p><?php echo sprintf(lang('forgot_password_subheading'), $identity_label);?></p>

		<?php if(!empty($message)) echo '<div id="infoMessage" class="alert alert-warning">'.$message.'</div>';?>

		<?php echo form_open("auth/forgot_password", array('class' => 'form-horizontal'));?>

		      <div class="form-group">
			<label for="email" class="col-sm-2 control-label"><?php echo sprintf(lang('forgot_password_email_label'), $identity_label);?></label>
			<div class="col-sm-8"><?php echo form_input($email, NULL, 'class="form-control"');?></div>
			
			<label for="email" class="col-sm-2 control-label"></label>
			<div class="col-sm-8" style="margin-left: 60px;margin-top: 14px;"><?php echo form_submit(array('class' => 'btn btn-primary', 'name' => 'send', 'value' => 'Send'));?></div>
		
		      </div>

		      

		<?php echo form_close();?>
	</div>
</div>