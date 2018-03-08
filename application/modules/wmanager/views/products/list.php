<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">			
			<span class="pull-left">
				<i class="icon-pencil"></i>
				<h3>
					<i class="fa fa-cubes"></i> Setup Products
				</h3>
			</span>
			<span class='pull-right'>
				<a href="/admin/setup_products/add" class="btn btn-default "><i class="fa fa-plus-circle"></i> Add Product</a>
			</span>	
		</div>
		<?php 
		if(!empty($message)){
						echo "<div class='col-md-12'>";
							echo '<div class="alert alert-info alert-dismissable">
									  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									  '.$message.'
									</div>';
						echo "</div>";
					}
		?>
		<div class="col-md-12">
			<table class="table ">
				<thead>
					<tr>
						<th>Title</th>
						<th>Code</th>
						<th>Type</th>
						<th>Selling Start</th>
						<th>Selling End</th>
						<th>Last Modified</th>
						<th></th>
					</tr>
			   </thead>
			   <tbody>
			   		<?php 
			   			if(is_array($products) && count($products)>0){
			   				foreach($products as $item){
			   					echo "<tr>";
			   						echo "<td>".$item['title']."</td>";
			   						echo "<td>".$item['product_code']."</td>";
			   						echo "<td>".$item['product_type']."</td>";
			   						echo "<td>".$item['selling_date']."</td>";
			   						echo "<td>".$item['selling_end']."</td>";
			   						echo "<td>".$item['modified']."</td>";
			   						echo "<td>";
				   						echo '<div class="dropdown">';
				   						echo '<a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-list"></i></a>';
				   						echo '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';
				   						echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="' . site_url ( 'admin/setup_products/edit/' . $item['id'] ) . '">Edit</a></li>';
				   						echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="' . site_url ( 'admin/setup_products/delete/' . $item['id'] ) . '" class="delete-confirm" data-message="Are you sure you want to delete the record?">Delete</a></li>';
				   						echo '</ul>';
				   						echo '</div>';
			   						echo "</td>";	
			   					echo "</tr>";
			   				}
			   			}else{
			   				echo "<tr><td colspan='6'>No products found.</td></tr>";
			   			}
			   		
			   		?>
			   </tbody>
			</table>
		</div>
	</div>
</div>		