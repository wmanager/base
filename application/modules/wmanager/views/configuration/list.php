<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>
				<i class="fa fa-cog"></i>Setup Configurations
			</h3>
		</div>
		<!-- /.widget-header -->


		<div class="widget-content">
			<section id="configuartion">
				<?php 
					if(!empty($message)){
						echo "<div class='col-md-12'>";
							echo '<div class="alert alert-'.$message['status'].' alert-dismissable">
									  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									  '.$message['message'].'
									</div>';
						echo "</div>";
					}
				
				?>
				<div class="filters pull-left">
					<form name="config_filter" action="" method="post" class="form-inline">
						<div class="form-group">
							<strong>Module Filter:</strong>
							<select class="form-control" onchange="this.form.submit()" name="module">
								<option value="" <?php if(!$this->session->userdata('module_filter')) echo 'selected'; ?>>All</option>
								<?php 
									if(count($modules)>0){
										foreach ($modules as $module){
									?>	
										<option value='<?php echo $module?>' <?php if($this->session->userdata('module_filter') == $module)echo 'selected';?>><?php echo ucfirst($module)?></option>
									<?php 
										}
									}
								?>
							</select>
						</div>
					</form>
				</div>
				<?= $this->form_builder->open_form(array('action' => 'admin/configuration/save_config','enctype' => 'multipart/form-data')); ?>
				<div class="col-md-12">
					<?php 
						if(count($modules)>0){
							foreach ($modules as $module){
								$form_name = "form_".$module;
								if(isset($forms[$form_name])){
									
					?>
						<strong><?php echo ucfirst($module);?></strong>
						<div class="col-md-12">
								<?= $forms[$form_name]; ?>
						</div>
						<div class="clearfix"></div>
						<hr>		
					<?php
								}
							}	
						}?>
				</div>
				<div class="col-md-offset-2 col-md-8">
					<button type="submit" class="btn btn-success">Save</button>
					<a href="<?=site_url('/dashboard')?>"
						class="btn btn-default">Cancel
					</a>
				</div>	
				<?= $this->form_builder->close_form(); ?>
			</section>
		</div>
	</div>