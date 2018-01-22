<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>Dummy Extension Page</h3>
		</div>
		<!-- /.widget-header -->


		<div class="widget-content">
			<section id="engine">
				<?php 
					foreach($datas as $item){
						echo $item."<br>";	
					}
				?>			
			</section>
		</div>
	</div>
</div>