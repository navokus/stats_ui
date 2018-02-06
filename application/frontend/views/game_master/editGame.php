<ol class="breadcrumb">
    <li><a href="<?php echo base_url('index.php/Game') ?>"><i
                    class="fa fa-dashboard"></i> Game</a></li>
    <li class="active">Edit Game</li>
</ol>

<div class="row">
    <div class="col-md-6">
        <!-- general form elements disabled -->
        <div class="box box-warning">
            <div class="box-header">
                <h3 class="box-title">Update Game Information</h3>
            </div>
            <!-- /.box-header -->
            <form role="form" name="add" method="post" action="">
                <div class="box-body">


                    <?php
                    $message = validation_errors ();
                    if ($message) {
                        echo "<blockquote>" . $message . "</blockquote>";
                    }
                    ?>

                    <div class="form-group">
                        <label>Name</label> <input type="text" name="Game Code" disabled
                                                   class="form-control" value="<?php echo $_POST['game_code'] ?>"
                                                   placeholder="Enter ...">
                    </div>
                    <div class="form-group">
                        <label for="sel1">Kpi Type</label>
                        <select class="form-control" id="kpi_type" name="kpi_type" disabled>
                            <?php foreach($kpiType as $key=>$value) {  ?>
                                <option value="<?php echo $value?>"
                                    <?php echo(($value == $_POST['kpi_type']) ? 'selected' : '') ?>>
                                    <?php echo $key?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sel1">Group Id</label>
                        <select class="form-control" id="group_id" name="group_id" disabled>
                            <?php foreach($groupId as $key=>$value) {  ?>
                                <option value="<?php echo $value?>"
                                    <?php echo(($value == $_POST['group_id']) ? 'selected' : '') ?>>
                                    <?php echo $key?>
                                </option>
                            <?php  } ?>
                        </select>

                        <div class="form-group">
                            <label for="sel1">Data Source</label>
                            <select class="form-control" id="data_source" name="data_source">
                                <?php foreach($dataSource as $key) {  ?>
                                    <option value="<?php echo $key?>"
                                        <?php echo(($key == $_POST['data_source']) ? 'selected' : '') ?>>
                                        <?php echo $key?>
                                    </option>
                                <?php  } ?>
                            </select>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" name="up_game" value="1"
                            class="btn btn-primary">Update Game</button>
                </div>
            </form>
        </div>
        <!-- /.box -->
    </div>
</div>
