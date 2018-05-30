<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">			
			<span class="pull-left">
				<i class="icon-pencil"></i>
				<h3>
					<i class="fa fa-users"></i> Setup Groups
				</h3>
			</span>
			<span class='pull-right'>
				<a href="/admin/setup_groups/add" class="btn btn-default "><i class="fa fa-plus-circle"></i> Add Group</a>
			</span>	
		</div>
		
		<div class="col-md-12">
			<table class="table ">
				<thead>
					<tr>
						<th>Title</th>
						<th>Description</th>
						<th></th>
					</tr>
			   </thead>
			   <tbody>
			   		<?php 
			   			if(is_array($groups) && count($groups)>0){
			   				foreach($groups as $item){
			   					echo "<tr>";
			   						echo "<td>".$item['name']."</td>";
			   						echo "<td>".$item['description']."</td>";
			   						echo "<td>";
				   						echo '<div class="dropdown">';
				   						echo '<a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-list"></i></a>';
				   						echo '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';
				   						echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="' . site_url ( 'admin/setup_groups/edit/' . $item['id'] ) . '">Edit</a></li>';
				   						echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="' . site_url ( 'admin/setup_groups/delete/' . $item['id'] ) . '" class="delete-confirm" data-message="Are you sure you want to delete the group?">Delete</a></li>';
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