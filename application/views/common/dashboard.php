
<div class="col-md-12" ng-controller="Dashboard">
	<div class="widget stacked ">
		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>Dashboard</h3>
		</div>
		<!-- /.widget-header -->

		<div class="widget-content">
			<div>
				<div class="col-lg-3 col-md-6">
				<a style="color: black" href="<?php echo ($troubles) ? '/common/home/set_session/trouble' : '#'?>">
				<div class="panel block-shadow">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-bug fa-5x fa-color"></i>
							</div>
							<div class="col-xs-9 text-right">
							<h2><?php echo $troubles; ?></h2>
							<div>Total Open Trouble</div>
							</div>
						</div>
					</div>
				</div>
				</a>
				</div>
				<a style="color: black" href="<?php echo ($threads) ? '/common/home/set_session/thread' : '#' ?>">
				<div class="col-lg-3 col-md-6">				
				<div class="panel block-shadow">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-shopping-cart fa-5x fa-color"></i>
							</div>
							<div class="col-xs-9 text-right">
							<h2><?php echo $threads; ?></h2>
							<div>Total Open Threads</div>
							</div>
						</div>
					</div>
				</div>
				</div>
				</a>
				<a style="color: black" href="<?php echo ($activity) ? '/common/home/set_session/activity' : '#' ?>">
				<div class="col-lg-3 col-md-6">				
				<div class="panel block-shadow">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-shield fa-5x fa-color"></i>
							</div>
							<div class="col-xs-9 text-right">
							<h2><?php echo $activity; ?></h2>
							<div>Total Open Activity</div>
							</div>
						</div>
					</div>
				</div>
				</div>
				</a>
				<a style="color: black" href="<?php echo ($contract) ? '/common/home/set_session/contract' : '#' ?>">
				<div class="col-lg-3 col-md-6">				
				<div class="panel block-shadow">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-users fa-5x fa-color"></i>
							</div>
							<div class="col-xs-9 text-right">
							<h2><?php echo $contract; ?></h2>
							<div>Total Contract</div>
							</div>
						</div>
					</div>
				</div>
				</div>
				</a>
				<div class="clearfix"></div>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<div class="col-md-6 col-sm-12 dash-block">
							<div id="trouble_container"></div>
						</div>
						<div class="col-md-6" >
							 
		                        <div class="panel activity-panel" style='margin: 0 auto; box-shadow: 0 1px 15px 1px rgba(39,39,39,.1);'>
		                            <div class="panel-heading">
		                                <h6 class="panel-title">Activities</h6>
		                            </div>
		                            <div class="panel-body">
		                                <div class="activity-box">
		                                    <ul class="activity-list">
		                                    	<?php if(count($activities) > 0) { 
		                                    		foreach($activities as $row) { 
		                                    	?>
		                                        <li>
 													<div class="activity-user">
		                                                <a href="#" class="avatar" title="<?= $row['user_name'] ?>" data-toggle="tooltip"><?= $row['user_name'][0] ?></a>
		                                            </div>
		                                            <div class="activity-content">
		                                                <div class="timeline-content">
		                                                    <a href="profile.html" class="name"><?= $row['user_name'] ?></a> added the reminder in
		                                                    	<?php if(!empty($row['activity_id'])) { ?>
		                                                    	<a target="_blank" href="/common/activities/detail/<?= $row['activity_id'] ?>"> <?= $row['trouble_title'] ?> activity_type</a>
		                                                    	<?php } else if(!empty($row['trouble_id'])) { ?>
		                                                    	<a target="_blank" href="/common/troubles/edit/<?= $row['trouble_id'] ?>"> <?= $row['trouble_title'] ?></a>
		                                                    	<?php } ?>
		                                                    	as <b><?= $row['description'] ?></b>
															<?php if($row['notification_date']) { ?>
																 <span class="time">Notification date <?= $row['notification_date']; ?></span>
															<?php } ?>
		                                                    <span class="time">Created on <?= $row['created']; ?></span>
		                                                </div>
		                                            </div>
		                                        </li>
		                                        <?php }
		                                    		} ?>
		                                    </ul>
		                                </div>
		                            </div>
		                            <!-- <div class="panel-footer text-center bg-white">
		                                <a href="activities.html" class="text-primary">View all activities</a>
		                            </div> -->
		                    </div>
						</div>
					</div>
				</div>			
			</div>
		</div>
	</div>
</div>


