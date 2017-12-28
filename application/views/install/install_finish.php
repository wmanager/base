<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1>Finish the installation</h1>
			</div>
			<p>The installation process will finish.</p>
			<p>For security reasons, all files related to this installer will be deleted after you click the "Finish the installation" button.</p>
			<p>You the will be redirected to login page.</p>
			<p>Login as admin (with the admin account your created just before) once the installation is finished, and create your first forum.</p>
			<hr>
			<div class="col-md-12">			
				<a class="btn btn-success pull-right btn-disabled" href="<?= base_url('install/delete_files') ?>">Finish the installation <i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
			</div>
		</div>
	</div><!-- .row -->