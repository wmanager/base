<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header">			
			<span class="pull-left">
				<i class="icon-pencil"></i>
				<h3>
					<i class="fa fa-user"></i> Setup Roles
				</h3>
			</span>
			<span class='pull-right'>
				<a href="/admin/setup_roles/add" class="btn btn-default "><i class="fa fa-plus-circle"></i> Add Role</a>
			</span>	
		</div>
		<div class="col-md-12">
			<table class="table ">
				<thead>
					<tr>
						<th>Title</th>
						<th>Parent Role</th>
						<th>Disabled</th>
						<th>Last Modified</th>
						<th></th>
					</tr>
			   </thead>
			   <tbody>
			   		<?php 
			   			if(is_array($roles) && count($roles)>0){
			   				foreach($roles as $item){
			   					echo "<tr>";
			   						echo "<td>".$item['key']."</td>";
			   						echo "<td>".$item['parent_role']."</td>";
			   						
			   						if($item['disabled'] == 't'){
			   							$disabled = "Yes";	
			   						}else{
			   							$disabled = "No";
			   						}
			   						
			   						echo "<td>".$disabled."</td>";
			   						echo "<td>".$item['modified']."<br><small>".$item['user_name']."</small></td>";
			   						echo "<td>";
				   						echo '<div class="dropdown">';
				   						echo '<a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-list"></i></a>';
				   						echo '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';
				   						echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="' . site_url ( 'admin/setup_roles/edit/' . $item['id'] ) . '">Edit</a></li>';
				   						echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="' . site_url ( 'admin/setup_roles/delete/' . $item['id'] ) . '" class="delete-confirm" data-message="Are you sure you want to delete the role?">Delete</a></li>';
				   						echo '</ul>';
				   						echo '</div>';
			   						echo "</td>";	
			   					echo "</tr>";
			   				}
			   			}else{
			   				echo "<tr><td colspan='4'>No roles found.</td></tr>";
			   			}
			   		
			   		?>
			   </tbody>
			</table>
		</div>
	</div>
</div>		