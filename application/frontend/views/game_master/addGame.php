<form role="form" name="add" method="post" action="">
<div class="row">
<div class="col-md-12">
  <!-- general form elements disabled -->
  <div class="box box-warning">
    <div class="box-header">
      <h3 class="box-title">Add Game</h3>
    </div><!-- /.box-header -->

    <div class="box-body">
		<div class="row">

		<?php
			$message = validation_errors();
			if ($message) {
				echo "<blockquote>" . $message . "</blockquote>";
			}
		?>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="sel1">Game Code</label>
                    <select class="form-control" id="game_code" name="game_code">
                        <?php foreach($lstGameCode as $key=>$value) {  ?>
                            <option value="<?php echo $value['GameCode']?>"> <?php echo $value['GameName']?></option>
                        <?php  } ?>
                    </select>
                </div>
            </div>
        <div class="col-md-3">
                <div class="form-group">
                    <label for="sel1">Kpi Type</label>
                    <select class="form-control" id="kpi_type" name="kpi_type">
                        <?php foreach($kpiType as $key=>$value) {  ?>
                            <option value="<?php echo $value?>" > <?php echo $key?></option>
                        <?php  } ?>
                    </select>
                </div>
        </div>
        <div class="col-md-3">
                <div class="form-group">
                    <label for="sel1">Group Id </label>
                    <select class="form-control" id="group_id" name="group_id">
                        <?php foreach($groupId as $key=>$value) {  ?>
                            <option value="<?php echo $value?>" > <?php echo $key?></option>
                        <?php  } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="sel1">Data Source</label>
                    <select class="form-control" id="data_source" name="data_source">
                        <?php foreach($dataSource as $key) {  ?>
                            <option value="<?php echo $key?>" > <?php echo $key?></option>
                        <?php  } ?>
                    </select>
                </div>
            </div>
		</div>

    </div><!-- /.box-body -->
    <div class="box-footer">
		<button type="submit" class="btn btn-primary">Add</button>
	</div>
  </div><!-- /.box -->
</div>
</div>

</form>