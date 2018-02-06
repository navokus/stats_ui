<div class="row">
	<div class="col-md-6">
		<!-- general form elements disabled -->
		<div class="box box-warning">
			<div class="box-header">
				<h3 class="box-title">Edit User</h3>
			</div>
			<!-- /.box-header -->
			<form role="form" name="adduser" method="post" action="">
				<div class="box-body">
      	<?php
							$message = validation_errors ();
							if ($message) {
								echo "<blockquote>" . $message . "</blockquote>";
							}
							?>
        <!-- text input -->
					<div class="form-group">
						<label>Username</label>
						<p><?php echo $_POST['username'] ?></p>
					</div>

					<div class="form-group">
						<label>Group</label> <select class="form-control" name="GroupId"
							id="groupid">
				<?php
				foreach ( $aGroup as $value ) {
					echo '<option value="' . $value ['GroupId'] . '" ' . (($value ['GroupId'] == $_POST ['GroupId']) ? 'selected' : '') . ' >' . $value ['GroupName'] . '</option>';
				}
				?>					
			</select>
					</div>

					<div class="form-group">
						<label>Active</label> <select class="form-control" name="Active">
							<option value="1"
								<?php echo ($_POST['Active'] == 1) ? ' selected ' : ''; ?>>Yes</option>
							<option value="0"
								<?php echo ($_POST['Active'] == 0) ? ' selected ' : ''; ?>>No</option>
						</select>
					</div>
					<div class="form-group">
						<label>Send Email</label> <select class="form-control"
							name="send_mail">
							<option value="1"
								<?php echo ($_POST['send_mail'] == 1) ? ' selected ' : ''; ?>>Yes</option>
							<option value="0"
								<?php echo ($_POST['send_mail'] == 0) ? ' selected ' : ''; ?>>No</option>
						</select>
					</div>


				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
		<!-- /.box -->
	</div>
	<script type="text/javascript">
	$(document).ready(function() {
	  $("#groupid").select2();
	});
</script>