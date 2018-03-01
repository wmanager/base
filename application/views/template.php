<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    
    <?php
				if (! isset ( $title ) || $title == '') {
					$title = 'Wmanager';
				}
				?>
    <title><?php echo $title;?></title>

<meta name="viewport"
	content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">

<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

<style type="text/css">
[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak,
	.x-ng-cloak {
	display: none !important;
}
</style>

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="/assets/js/libs/jquery-migrate-1.2.1.min.js"></script>
<script src="/assets/js/libs/bootstrap.min.js"></script>
<script src="/assets/js/libs/bootstrap/bootbox.min.js"></script>

<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="/assets/css/bootstrap-responsive.min.css" rel="stylesheet">

<link
	href="//fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600"
	rel="stylesheet">
<link href="/assets/css/font-awesome.min.css" rel="stylesheet">

<link href="/assets/css/ui-lightness/jquery-ui-1.10.0.custom.min.css"
	rel="stylesheet">

<link href="/assets/js/plugins/msgGrowl/css/msgGrowl.css"
	rel="stylesheet">

<link href="/assets/css/datepicker.css" rel="stylesheet">
<!--<link href="/assets/css/datepicker3.css" rel="stylesheet">-->

<link href="/assets/css/base-admin-3.css" rel="stylesheet">
<link href="/assets/css/base-admin-3-responsive.css" rel="stylesheet">

<link href="/assets/css/pages/dashboard.css" rel="stylesheet">

<link href="/assets/css/bootstrapValidator.min.css" rel="stylesheet">

<link href="/assets/css/jquery.handsontable.full.css" rel="stylesheet"
	type="text/css">

<link href="/assets/js/plugins/fancytree/skin-xp/ui.fancytree.css"
	rel="stylesheet" type="text/css" class="skinswitcher">

<link href="/assets/css/custom.css" rel="stylesheet">

<link rel="stylesheet"
	href="/assets/js/plugins/fullcalendar/fullcalendar.css" type="text/css" />
<link rel="stylesheet" href="/assets/js/plugins/fullcalendar/theme.css"
	type="text/css" />



<link href="/assets/css/pnotify.custom.min.css" media="all"
	rel="stylesheet" type="text/css" />

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

<style type="text/css">
[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak,
	.x-ng-cloak {
	display: none !important;
}
</style>

<?php 
		$dependencies_css = get_dependencies("application","css");
		
		if(is_array($dependencies_css) &&  count($dependencies_css)>0){
			foreach ($dependencies_css as $item){
				echo"<link href='$item' rel='stylesheet'>";
			}
		}
	?>

</head>

<body>
	<nav class="navbar navbar-inverse" role="navigation">

		<div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse"
					data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span> <i class="icon-cog"></i>
				</button>
				<a class="navbar-brand" href="/"><img style="height: 50px" title=""
					alt="Wmanager" src="/assets/img/logo.png"></a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div
				class="collapse navbar-collapse navbar-ex1-collapse calendar_top">
				<!-- <div class="calendar_container">
  		<a href="/common/calendar" title="Calendar" ><i class="fa fa-building-o"></i></a>
  	</div>-->

				<ul class="nav navbar-nav navbar-right">
					<!--<li>
      		<a href="/common/reminders">
				<i class="fa fa-comment"></i> 
			</a>
      	</li>-->
      	<?php
							$userdomain = $this->session->userdata;
							$role = $userdomain ['userdomain']->role;
							
							if ((in_array ( 'ADMIN', $role )) || (in_array ( 'admin', $role ))) {
								?>
						<li>
							<a href="/dashboard"> 
								<i class="fa fa-cog"></i> Wmanager</b>
							</a>
						</li>
						
		<?php } ?>
		<li class="dropdown"><a href="javscript:void(0);"
						class="dropdown-toggle" data-toggle="dropdown"> <i
							class="fa fa-user"></i> 
				<?=$this->ion_auth->user()->row()->first_name?> <?=$this->ion_auth->user()->row()->last_name?>
				<b class="caret"></b>
					</a>

						<ul class="dropdown-menu">
							<li><a href="/auth/change_password">Change Password</a></li>
							<li class="divider"></li>
							<li><a href="/auth/logout">Logout</a></li>
						</ul></li>
				</ul>


			</div>
			<!-- /.navbar-collapse -->
		</div>
		<!-- /.container -->
	</nav>





	<div class="subnavbar">

		<div class="subnavbar-inner">

			<div class="container">

				<a href="javascript:;" class="navbar-toggle" data-toggle="collapse"
					data-target=".subnav-collapse"> <span class="sr-only"></span> <span
					class="icon-bar" style="background-color: #FFF"></span> <span
					class="icon-bar" style="background-color: #FFF"></span> <span
					class="icon-bar" style="background-color: #FFF"></span>
				</a>

				<div class="collapse subnav-collapse">
				<?php
				top_menu ();
				?>
			</div>
				<!-- /.subnav-collapse -->

			</div>
			<!-- /container -->

		</div>
		<!-- /subnavbar-inner -->

	</div>
	<!-- /subnavbar -->


	<div class="main">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
				<?=$this->breadcrumb->output()?>
			</div>
			</div>
		</div>
		<div class="container" ng-app="WmanagerApp">
			<div class="row"><?=$content?></div>
		</div>
		<!-- /container -->

	</div>
	<!-- /main -->








	<div class="footer">

		<div class="container">

			<div class="row">

				<div id="footer-copyright" class="col-md-12 text-center">Jamain Social and Services s.r.l. &copy;
					2017</div>
				<!-- /span6 -->

				<div id="footer-terms" class="col-md-6">
					<!--Theme by <a href="http://jumpstartui.com" target="_blank">Jumpstart UI</a>-->
				</div>
				<!-- /.span6 -->

			</div>
			<!-- /row -->

		</div>
		<!-- /container -->

	</div>
	<!-- /footer -->





	<!-- Le javascript
================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="/assets/js/libs/jquery-ui-1.10.0.custom.min.js"></script>
	<script src="/assets/js/libs/jquery-sortable.js"></script>

	<script src="/assets/js/plugins/validate/jquery.validate.min.js"></script>
	<script src="/assets/js/plugins/validate/messages_it.js"></script>


	<script src="/assets/js/plugins/datepicker/bootstrap-datepicker.js"></script>
	<script type="text/javascript"
		src="/assets/js/plugins/datepicker/locales/bootstrap-datepicker.IT.js"
		charset="UTF-8"></script>

	<script src="/assets/js/plugins/handlebars/handlebars-v1.3.0.js"></script>
	<script src="/assets/js/plugins/typeahead/typeahead.bundle.min.js"></script>

	<script src="/assets/js/plugins/fancytree/jquery.fancytree.js"
		type="text/javascript"></script>
	<script src="/assets/js/plugins/fancytree/jquery.fancytree.glyph.js"
		type="text/javascript"></script>

	<script src="/assets/js/plugins/chained/jquery.chained.remote.min.js"
		type="text/javascript"></script>

	<script
		src="/assets/js/plugins/handsontable/jquery.handsontable.full.js"
		type="text/javascript"></script>

	<script src="/assets/js/plugins/flot/jquery.flot.js"></script>
	<script src="/assets/js/plugins/flot/jquery.flot.pie.js"></script>
	<script src="/assets/js/plugins/flot/jquery.flot.resize.js"></script>
	<script src="/assets/js/plugins/flot/jquery.flot.time.js"></script>

	<script src="/assets/js/Application.js"></script>


	<script
		src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.9/angular.min.js"></script>
	<script src="/assets/js/libs/angular-filter.js"></script>
	<script
		src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.9/i18n/angular-locale_it-it.js"></script>
	<!--<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>-->

	<script src="/assets/js/charts/pie.js"></script>
	<script src="/assets/js/charts/line.js"></script>
	<script src="/assets/js/checklist-model.js"></script>
	<script src="/assets/js/plugins/fullcalendar/moment.min.js"></script>
	<script src="/assets/js/plugins/fullcalendar/fullcalendar.min.js"></script>
	<script src="/assets/js/angular/calendar.js"></script>
	<script src="/assets/js/angular/ui-bootstrap-tpls-0.13.4.min.js"></script>
	<script src="/assets/js/angular/angular-validation.min.js"></script>
	<script src="/assets/js/angular/angular-validation-rule.min.js"></script>
	<script src="/assets/js/angular/angular-moment.min.js"></script>
	<script src="/assets/js/angular/ng-file-upload-shim.js"></script>
	<script src="/assets/js/angular/ng-file-upload.js"></script>
	<script src="/assets/js/angular/ngBootbox.min.js"></script>
	<script src="/assets/js/angular/checklist-model.js"></script>
	<script src="/assets/js/angular/app.js?v=<?=microtime()?>"></script>

	<script src="/assets/js/jquery.chained.remote.min.js"></script>

	<script type="text/javascript"
		src="/assets/js/plugins/pnotify/pnotify.custom.min.js"></script>


	<script src="/assets/js/custom.js"></script>
	<script src="/assets/js/trouble_type.js"></script>
	<script src="/assets/js/bootstrap-confirm-delete.js"></script>
	<script src="/assets/js/jquery.confirm.js"></script>	
	<script src="/assets/js/process.js"></script>

	<script src="/assets/js/highcharts.js"></script>

	<script>
<?php
if ($this->session->flashdata ( 'growl_show' ) == 'true') {
	?>
	PNotify.prototype.options.styling = "bootstrap3";
	$( document ).ready(function() {
		var success_growl = "<?=$this->session->flashdata('growl_success')?>";
		var error_growl = "<?=$this->session->flashdata('growl_error')?>";
		//var success_growl = "Activity is created";
		if(success_growl != ''){
			new PNotify({
		        title: false,
		        text: success_growl,
		        type: "success",
		        width:'600px'
		    });
		}
		if(error_growl != ''){
			new PNotify({
		        title: false,
		        text: error_growl,
		        type: "error",
		         width:'600px'
		    });
		}
	});
<?php
}
?>
</script>

	<?php 
		$dependencies_js = get_dependencies("application","js");
		
		if(is_array($dependencies_js) &&  count($dependencies_js)>0){
			foreach ($dependencies_js as $item){
				echo"<script src='$item'></script>";
			}
		}
	?>

</body>
</html>
