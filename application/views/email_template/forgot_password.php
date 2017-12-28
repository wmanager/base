<div>
	Hi <?php echo $data['user']->first_name. ' '. $data['user']->last_name?>
	Please use below passowrd to login
	<br>
	<?php echo $data['forgotten_password_code']; ?>
</div>