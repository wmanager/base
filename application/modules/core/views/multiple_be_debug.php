<div class="col-md-12">
	<div class="widget stacked ">
		<div class="widget-content">
			<?php
			if (isset ( $message ) && $message != '') {
				
				?>
				<div class="alert alert-success alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Success!</strong> <?php echo $message;?>
				</div>
			<?php
			}
			?>
			<h4>Debug for Billing Wizard</h4>
			<br />
			<form id="filters" name="filter" method="post" action=""
				class="form-inline">
				<div class="form-group">
					<div class="input-group">
						<div class="col-md-4">
							<b>BE:</b>
						</div>
						<div class="col-md-8">
							<textarea name="be_ids" class="form-control" rows="5" cols=""></textarea>
						</div>
					</div>
					<div class="clearfix"></div>
					<br />
					<div class="input-group">
						<div class="col-md-4">
							<b>D_decorenza:</b>
						</div>
						<div data-date-viewmode="days" data-date-format="dd/mm/yyyy"
							data-date="" class="input-group date col-md-6" new-calendar>
							<input type="text" class="form-control"
								column="col-sm-6 col-md-6 col-lg-6" placeholder="dd/mm/yyyy"
								name="date"> <span class="input-group-addon"><i
								class="fa fa-calendar"></i></span>
						</div>

					</div>
					<div class="input-group">
						<button type="submit" class="btn btn-primary">Submit</button>
					</div>
				</div>
			</form>
			<hr />
		</div>
	</div>
</div>