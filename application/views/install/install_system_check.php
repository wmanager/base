<div class="row">
	<div class="col-md-12">
		<h3>System Check</h3>
		<table class="table table-hover table-striped">
			<tbody>
				<tr>
					<th>
						PHP version above 5.5 
						<a href="/install/help/php" target="_blank">
							<i class="fa fa-question-circle" aria-hidden="true"></i>
						</a>
					</th>
					<?php 
					if($checks['php'] == 'OK'){
						echo "<td class='status_green'>OK</td>";
					}else{
						echo "<td class='status_red'>Fail</td>";
						}
					?>
				</tr>
				<tr>
					<th>Postgres Enabled
						<a href="/install/help/postgres" target="_blank">
							<i class="fa fa-question-circle" aria-hidden="true"></i>
						</a>
					</th>	
					<?php 
					if($checks['pgsql'] == 'OK'){
						echo "<td class='status_green'>OK</td>";
					}else{
						echo "<td class='status_red'>Fail</td>";
						}
					?>
				</tr>
				<tr>
					<th>cURL installed
						<a href="/install/help/curl" target="_blank">
							<i class="fa fa-question-circle" aria-hidden="true"></i>
						</a>
					</th>
					<?php 
					if($checks['curl'] == 'OK'){
						echo "<td class='status_green'>OK</td>";
					}else{
						echo "<td class='status_red'>Fail</td>";
						}
					?>
				</tr>
				<tr>
					<th>PHP Session Enabled
						<a href="/install/help/session" target="_blank">
							<i class="fa fa-question-circle" aria-hidden="true"></i>
						</a>
					</th>
					<?php 
					if($checks['session'] == 'OK'){
						echo "<td class='status_green'>OK</td>";
					}else{
						echo "<td class='status_red'>Fail</td>";
						}
					?>
				</tr>
				<tr>
					<th>Apache Rewrite MOD enabled
						<a href="/install/help/rewrite" target="_blank">
							<i class="fa fa-question-circle" aria-hidden="true"></i>
						</a>
					</th>
					<?php 
					if($checks['rewrite_mod'] == 'OK'){
						echo "<td class='status_green'>OK</td>";
					}else{
						echo "<td class='status_red'>Fail</td>";
						}
					?>
				</tr>
				<tr>
					<th>.htaccess file installed
						<a href="/install/help/htaccess" target="_blank">
							<i class="fa fa-question-circle" aria-hidden="true"></i>
						</a>
					</th>
					<?php 
					if($checks['htaccess'] == 'OK'){
						echo "<td class='status_green'>OK</td>";
					}else{
						echo "<td class='status_red'>Fail</td>";
						}
					?>
				</tr>
				<tr>
					<th>Write permission for config files
						<a href="/install/help/write_config" target="_blank">
							<i class="fa fa-question-circle" aria-hidden="true"></i>
						</a>
					</th>
					<?php 
					if($checks['config_write'] == 'OK'){
						echo "<td class='status_green'>OK</td>";
					}else{
						echo "<td class='status_red'>Fail</td>";
						}
					?>
				</tr>
				<tr>
					<th>Write permission for log folder
						<a href="/install/help/log_write" target="_blank">
							<i class="fa fa-question-circle" aria-hidden="true"></i>
						</a>
					</th>
					<?php 
					if($checks['log_write'] == 'OK'){
						echo "<td class='status_green'>OK</td>";
					}else{
						echo "<td class='status_red'>Fail</td>";
						}
					?>
				</tr>
				<tr>
					<th>Write permission for upload folder
						<a href="/install/help/upload_write" target="_blank">
							<i class="fa fa-question-circle" aria-hidden="true"></i>
						</a>
					</th>
					<?php 
					if($checks['upload_write'] == 'OK'){
						echo "<td class='status_green'>OK</td>";
					}else{
						echo "<td class='status_red'>Fail</td>";
						}
					?>
				</tr>
				
			</tbody>
		</table>
		<div class="clearfix"></div>
		<a href="/install/create_host" class="pull-right btn btn-success btn-disabled <?php if(in_array("FAIL",$checks)) echo "disabled";?>"><b>Proceed to database creation <i class="fa fa-arrow-right" aria-hidden="true"></i></b></a>
	</div>
</div>
