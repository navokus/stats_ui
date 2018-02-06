<div class="row">
<div class="col-md-12">

	<div class="box box-danger">
		<div class="box-header">
			<h3 class="box-title">User</h3>
			<div class="box-tools">
				<form name="user" method="get" accept="">
					<div class="input-group">
						<input type="text" name="like-username"
							value="<?php echo $_GET['like-username'] ?>"
							class="form-control input-sm pull-right" style="width: 150px;"
							placeholder="Search">
						<div class="input-group-btn">
							<button type="submit" class="btn btn-sm btn-default">
								<i class="fa fa-search"></i>
							</button>
							<button type="button" class="btn btn-primary btn-sm"
								onclick="window.location.href='<?php echo base_url('index.php/User/user_add'); ?>'">Add
								User</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<table class="table table-striped" id="users-table">
			<thead>
				<tr>
					<th>Function</th>
					<th>Domain</th>
					<th>Group</th>
					<th>Created</th>
					<th>Active</th>
				</tr>
			 </thead>
    <?php
				foreach ( $list as $key => $value ) {
					?>
      <tr>
					<td>
						<div class="btn-group">
							<!-- <button type="button" class="btn btn-success btn-sm">View</button> -->
							<button type="button" class="btn btn-primary btn-sm"
								onclick="window.location.href='<?php echo base_url('index.php/User/user_edit/' . $value['username']); ?>'">Edit</button>
							<button type="button" class="btn btn-danger btn-sm"
								onclick="if(confirm('Do you want to delete User?')) window.location.href='<?php echo base_url('index.php/User/user_del/' . $value['username']); ?>'">Delete</button>
						</div>
					</td>
					<td><?php echo $value['username'];?></td>
					<td><a href="<?php echo base_url('index.php/User/group_view/'.$value['GroupId']) ;?>"><?php echo $value['GroupName'] ?></a></td>
					<td><?php echo $value['Created'] ?></td>
					<td>
            <?php echo ($value['Active'] == 1) ? 'Yes' : 'No'; ?>
        </td>
					
				</tr>
    <?php } ?>
		</table>
		
	</div>
</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
	    $('#users-table').DataTable( {
	        "paging":   true,
	        "ordering": true,
	        "info":     false,
	        "searching": true,
	        "order": [[ 0, "asc" ]]
	    } );
	} );
 </script>