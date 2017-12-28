<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Wmanager Installer</title>
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="author" content="">

<!-- css -->
<link href="/assets/css/bootstrap.css"
	rel="stylesheet">
	<style type="text/css">
		body{
			position: relative;			    
    		min-height: calc(100vh - 72px);
		}
		.nav-pills.nav-wizard > li {
		  width: 20%;
		  position: relative;
		  overflow: visible;
		  border-right: 15px solid transparent;
		  border-left: 15px solid transparent;
		}
		.nav-pills.nav-wizard > li + li {
		  margin-left: 0;
		}
		.nav-pills.nav-wizard > li:first-child {
		  border-left: 0;
		}
		
		.nav-pills.nav-wizard > li:last-child {
		  border-right: 0;
		}
		
		.nav-pills.nav-wizard > li a {
		  border-radius: 0;
		  color : #777;
		  font-weight : bold;
		  background-color: #eee;
		}
		.nav-pills.nav-wizard > li .nav-arrow {
		  position: absolute;
		  top: 0px;
		  right: -20px;
		  width: 0px;
		  height: 0px;
		  border-style: solid;
		  border-width: 20px 0 20px 20px;
		  border-color: transparent transparent transparent #eee;
		  z-index: 150;
		}
		.nav-pills.nav-wizard > li .nav-wedge {
		  position: absolute;
		  top: 0px;
		  left: -20px;
		  width: 0px;
		  height: 0px;
		  border-style: solid;
		  border-width: 20px 0 20px 20px;
		  border-color: #eee #eee #eee transparent;
		  z-index: 150;
		}
		.nav-pills.nav-wizard > li:hover .nav-arrow {
		  border-color: transparent transparent transparent #aaa;
		}
		.nav-pills.nav-wizard > li:hover .nav-wedge {
		  border-color: #aaa #aaa #aaa transparent;
		}
		.nav-pills.nav-wizard > li:hover a {
		  background-color: #aaa;
		  color: #fff;
		}
		.nav-pills.nav-wizard > li.active .nav-arrow {
		  border-color: transparent transparent transparent #5cb85c;
		}
		.nav-pills.nav-wizard > li.active .nav-wedge {
		  border-color: #5cb85c #5cb85c #5cb85c transparent;
		}
		.nav-pills.nav-wizard > li.active a {
		  background-color: #5cb85c;
		  color: #fff;
		}
		.nav>li>a {
		    position: relative;
		    display: block;
		    text-align: center;
		}
		.status_green{
			text-align:center;
			background-color: #5cb85c !important;
			color:white;
			font-weight:bold;
		}
		
		.status_red{
			text-align:center;
			color:white;
			background-color:#d9534f !important;
			font-weight:bold;
		}
		.footer-txt{
			top: 100%;
		    margin: 20px 0 0;
		    position: absolute;
		    width: 100%;
		    bottom: 0;
    	}
		.footer-txt nav{
		  	padding: 15px 0px;
		    margin-bottom:0px
    	}
    	.card{		
    		margin-top:15px;   
		    position: relative;
		    padding:25px;
		    -webkit-box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
		  -moz-box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
		  box-shadow: 4 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
		}
		.page-header {
		     margin: 10px 0 20px;
		}
	</style>
<link rel="stylesheet"
	href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
</head>
<body>

	<header id="site-header">
		<nav class="navbar navbar-default" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="#" style="padding-top: 5px;"><img style="height: 40px" title=""
					alt="WManager" src="/assets/img/logo.png" ></a>
				</div>
			</div>
		</nav>
	</header>
	<?php $current_url = current_url();?>
	
	<div class="container">
		
		<?php if($this->uri->segment(1) != '' && $this->uri->segment(2) != 'help') { ?>
		<div class="col-md-12">
			<ul class="nav nav-pills nav-wizard">
	        <li class="active"><a href="#" data-toggle="tab">System Check</a><div class="nav-arrow"></div></li>
	        <li class="<?php if(strpos($current_url,'create_host') > 0 || strpos($current_url,'database_creation') > 0 || strpos($current_url,'tables_creation') > 0 || strpos($current_url,'site_settings') > 0 || strpos($current_url,'finish') > 0) echo "active";?>"><div class="nav-wedge"></div><a href="#" data-toggle="tab">Database Connection</a><div class="nav-arrow"></div></li>
	        <li class="<?php if(strpos($current_url,'database_creation') > 0 || strpos($current_url,'tables_creation') > 0 || strpos($current_url,'site_settings') > 0 || strpos($current_url,'finish') > 0) echo "active";?>"><div class="nav-wedge"></div><a href="#" data-toggle="tab">Database Creation</a><div class="nav-arrow"></div></li>
	        <li class="<?php if(strpos($current_url,'site_settings') > 0 || strpos($current_url,'finish') > 0 )echo "active";?>"><div class="nav-wedge"></div><a href="#" data-toggle="tab">Site Settings<div class="nav-arrow"></div></a></li>
	        <li class="<?php if(strpos($current_url,'finish') > 0)echo "active";?>"><div class="nav-wedge"></div><a href="#" data-toggle="tab">Finish Installation</a></li>			
		</div>
		<?php } ?>
		<br>
		<div class="col-md-12">
			<div class="card">
				<?php echo $content; ?>
			</div>
		</div>		
	</div>
	<footer class="footer-txt">
		<div>
			<nav class="navbar navbar-default text-center">
				Jamain Social and Services s.r.l. &copy;
					2017
			</nav>
		</div>
	</footer>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script type="text/javascript">
		$(function(){
			$('form').submit(function(){
				$('.btn-disabled').prop('disabled', true);			      
			});
		});
	</script>
</body>
</html>