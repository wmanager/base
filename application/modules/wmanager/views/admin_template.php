<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="author" content="Wmanager">
    <link rel="icon" href="/../../favicon.ico">

    <title>Wmanager</title>
	<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="/assets/js/libs/jquery-migrate-1.2.1.min.js"></script>
	<script src="/assets/js/libs/bootstrap.min.js"></script>
	<script src="/assets/js/libs/bootstrap/bootbox.min.js"></script>
	<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="/assets/css/datepicker.css" rel="stylesheet">
	<link href="/assets/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link href="/assets/css/admin-section.css" rel="stylesheet">
	<link href="/assets/css/custom.css" rel="stylesheet">
	<link href="//fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
	<link href="/assets/css/font-awesome.min.css" rel="stylesheet">

	<?php 
		$dependencies_css = get_dependencies("admin","css");
		
		if(is_array($dependencies_css) &&  count($dependencies_css)>0){
			foreach ($dependencies_css as $item){
				echo"<link href='$item' rel='stylesheet'>";
			}
		}
	?>
	<style type="text/css">
	.card{		
    		margin-top:15px;   
		    position: relative;
		    padding:25px;
		    -webkit-box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
		  -moz-box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
		  box-shadow: 4 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
		}
	</style>
  </head>

  <body>
	<?php $current_url = current_url();?>
    <!-- Static navbar -->
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
         <a class="navbar-brand" href="/dashboard/" style="padding-top: 5px;"><img style="height: 40px" title=""
					alt="WManager" src="/assets/img/logo.png" ></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="<?php if(strpos($current_url,'dashboard') > 0) echo "active";?>"><a href="/dashboard">Dashboard</a></li>
            <li class="<?php if(strpos($current_url,'trouble_type') > 0) echo "active";?>"><a href="/trouble_type/">Troubles</a></li>
            <li class="<?php if(strpos($current_url,'extension') > 0) echo "active";?>"><a href="/core/extension">Extension</a></li>
            <li class="<?php if(strpos($current_url,'companies') > 0) echo "active";?>"><a href="/admin/companies">Companies</a></li>
            <li class="dropdown <?php if((strpos($current_url,'setup_process') > 0) || (strpos($current_url,'setup_form') > 0) || (strpos($current_url,'setup_activities')>0)) echo "active";?>">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Process <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="/admin/setup_processes">Setup Process</a></li>
                <li><a href="/admin/setup_form">Setup Forms</a></li>                
              </ul>
            </li>
            <li class="dropdown <?php if(strpos($current_url,'setup_attach') > 0 || strpos($current_url,'setup_collection') > 0) echo "active";?>">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Misc <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="/admin/setup_attach/">Setup Attachment</a></li>
                <li><a href="/admin/setup_collection/">Setup Collection</a></li>
<!--                 <li><a href="/admin/setup_reports/">Setup Reports</a></li> -->
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
           	<li class="dropdown">
           		<a href="javscript:void(0);" class="dropdown-toggle" data-toggle="dropdown"> 
           			<i class="fa fa-user"></i> 
					<?=$this->ion_auth->user()->row()->first_name?> <?=$this->ion_auth->user()->row()->last_name?>
					<b class="caret"></b>
				</a>
					<ul class="dropdown-menu">
						<li><a href="/auth/change_password">Change password</a></li>
						<li class="divider"></li>
						<li><a href="/auth/logout">Logout</a></li>
					</ul></li>
				</ul>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>


    <div class="container">
      <!-- Main component for a primary marketing message or call to action -->
      <div class="card" ng-app="WmanagerApp">
		<div class="row">
			<div class="col-md-12"><?=$content?></div>
		</div>	
	  </div>
    </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/assets/js/libs/jquery-ui-1.10.0.custom.min.js"></script>
	<script src="/assets/js/libs/jquery-sortable.js"></script>

	<script src="/assets/js/plugins/validate/jquery.validate.min.js"></script>
	<script src="/assets/js/plugins/validate/messages_it.js"></script>


	<script src="/assets/js/plugins/datepicker/bootstrap-datepicker.js"></script>
	<script type="text/javascript" src="/assets/js/plugins/datepicker/locales/bootstrap-datepicker.IT.js" charset="UTF-8"></script>
	<script src="/assets/js/plugins/handlebars/handlebars-v1.3.0.js"></script>
	<script src="/assets/js/plugins/typeahead/typeahead.bundle.min.js"></script>

	<script src="/assets/js/plugins/fancytree/jquery.fancytree.js" type="text/javascript"></script>
	<script src="/assets/js/plugins/fancytree/jquery.fancytree.glyph.js" type="text/javascript"></script>

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
	
	<script src="/assets/js/admin.js"></script>
	
	<?php 
		$dependencies_js = get_dependencies("admin","js");
		
		if(is_array($dependencies_js) &&  count($dependencies_js)>0){
			foreach ($dependencies_js as $item){				
				echo"<script src='$item'></script>";
			}
		}
	?>
  </body>
</html>
