<div class="row">
<div class="col-md-12">
	<div class="box box-danger">
		<div class="box-header">
			<h3 class="box-title">Group User</h3>
			<div class="box-tools">

				<div class="input-group">
					<input type="text" name="table_search"
						class="form-control input-sm pull-right" style="width: 150px;"
						placeholder="Search">
					<div class="input-group-btn">
						<button class="btn btn-sm btn-default">
							<i class="fa fa-search"></i>
						</button>
						<div class="btn-group "> <a href="<?php echo site_url(array('User', 'group_add'));?>" type="button" class="btn btn-success btn-sm">Add Group</a> </div>
					</div>
					
				</div>
			</div>
			
		</div>
        <?php if (isset($sMessage) && !empty($sMessage)):?>
            <div
			class="<?php echo $sMessageType == 'success' ? 'alert alert-success alert-dismissable' : 'alert alert-warning alert-dismissable'?>">
			<button type="button" class="close" data-dismiss="alert"
				aria-hidden="true">×</button>
                <?php echo $sMessage;?>
            </div>
        <?php endif;?>
        <table class="table table-striped" id="groups-table">
			<thead>
				<tr>
					<th>Function</th>
					<th>Name</th>
					<th>Active</th>
				</tr>
                </thead>
                <?php $iCount = 0;?>
                <?php foreach ($aGroups as $aGroup):?>
                    <?php $iCount++;?>
                    <tr>
					<td>
						<div class="btn-group">
							<a
								href="<?php echo site_url(array('User', 'group_view', $aGroup['GroupId']));?>"
								type="button" class="btn btn-success btn-sm">View</a> <a
								href="<?php echo site_url(array('User', 'group_edit', $aGroup['GroupId']));?>"
								type="button" class="btn btn-primary btn-sm">Edit</a>
                                <?php if ($aGroup['GroupId'] != 1): ?>
                                    <a
								href="<?php echo site_url(array('User', 'group_delete', $aGroup['GroupId']));?>"
								type="button" class="btn btn-danger btn-sm"
								onclick="return confirm('Are you sure to delete this group?');">Delete</a>
                                <?php endif ?>
                                
                            </div>
					</td>
					<td><?php echo $aGroup['GroupName'];?></td>
					<td>
                            <?php echo $aGroup['Active'] ? 'Yes' : 'No';?>
                        </td>
					
				</tr>
                <?php endforeach;?>
		</table>

	</div>
</div>

</div>
<script type="text/javascript">
	$(document).ready(function() {
	    $('#groups-table').DataTable( {
	        "paging":   true,
	        "ordering": true,
	        "info":     false,
	        "searching": true,
	        "order": [[ 0, "asc" ]]
	    } );
	} );
 </script>