
<div class="col-md-12">
	<div class="widget stacked ">
		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>Dashboard</h3>
		</div>
		<!-- /.widget-header -->

		<div class="widget-content">
			<div>

				<!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#troubles"
						aria-controls="tecniche" role="tab" data-toggle="tab">Trouble without threads</a></li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
				
					<div role="tabpanel" class="tab-pane active" id="troubles">
					    	<?php
										if (count ( $troubles ) > 0) {
											foreach ( $troubles as $item ) {
												echo "<div>";
												echo '<h5 style="font-size:120%; color:#555; font-family: "Open Sans";"><span class="fa fa-chevron-circle-right"></span><a style="font-size:120%; color:#555; font-family: "Open Sans";" href="/common/troubles/save_dashboard_session/' . $item->id . '"> <strong>' . ucfirst ( strtolower ( $item->title ) ) . '(' . $item->count . ')</strong></a></h5>';
												echo "</div>";
											}
										} else {
											echo "No open troubles";
										}
										?>

					    </div>
				</div>

			</div>
		</div>
	</div>
</div>
