<script type="text/javascript">
    $(document).ready(function() {
        $('#alphatest').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy'
        }).on('changeDate', function (ev) {
            $('#alphatest').datepicker('hide');
            $('#alphatest').val(this.val());
        });

        $('#opendate').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy'
        }).on('changeDate', function (ev) {
            $('#opendate').datepicker('hide');
            $('#opendate').val(this.val());
        });

    } );
</script>

<ol class="breadcrumb">
    <li><a href="<?php echo base_url('index.php/Game') ?>"><i
                    class="fa fa-dashboard"></i> Game</a></li>
    <li class="active">Edit Game & Grade Configuration</li>
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
                        <label>Name</label> <input type="text" name="GameName"
                                                   class="form-control" value="<?php echo $_POST['GameName'] ?>"
                                                   placeholder="Enter ...">
                    </div>

                    <div class="form-group">
                        <label>Code</label> <input type="text" name="GameCode" disabled
                                                   class="form-control" value="<?php echo $_POST['GameCode'] ?>"
                                                   placeholder="Enter ...">
                    </div>

                    <div class="form-group">
                        <label>Game Type</label> <select class="form-control" name="GameType2">
                            <option value="1"
                                <?php echo ($_POST['GameType2'] == 1) ? ' selected ' : ''; ?>>ClientGame</option>
                            <option value="2"
                                <?php echo ($_POST['GameType2'] == 2) ? ' selected ' : ''; ?>>MobileGame </option>
                            <option value="3"
                                <?php echo ($_POST['GameType2'] == 3) ? ' selected ' : ''; ?>>WebGame </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>SendEmail ON/OFF</label> <select class="form-control" name="SendMail">
                            <option value="1"
                                <?php echo ($_POST['SendMail'] == 1) ? ' selected ' : ''; ?>>ON</option>
                            <option value="0"
                                <?php echo ($_POST['SendMail'] == 0) ? ' selected ' : ''; ?>>OFF</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Region</label> <select class="form-control" name="region">
                            <option value="global"
                                <?php echo ($_POST['region'] == 'global') ? ' selected ' : ''; ?>>global</option>
                            <option value="local"
                                <?php echo ($_POST['region'] == 'local') ? ' selected ' : ''; ?>>local</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label> <select class="form-control" name="Status">
                            <option value="0"
                                <?php echo ($_POST['Status'] == 0) ? ' selected ' : ''; ?>>Closed </option>
                            <option value="1"
                                <?php echo ($_POST['Status'] == 1) ? ' selected ' : ''; ?>>Launching </option>
                            <option value="2"
                                <?php echo ($_POST['Status'] == 2) ? ' selected ' : ''; ?>>Integrating </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Market </label>
                        <input type="text" name="market" class="form-control" value="<?php echo $_POST['market'] ?>" placeholder="Enter ...">
                    </div>
                    <div class="form-group">
                        <label>Owner (Dept.)</label>
                        <input type="text" name="owner" class="form-control" value="<?php echo $_POST['owner'] ?>" placeholder="Enter ...">
                    </div>
                </div>


                <div class="box-header">
                    <h3 class="box-title">Contact Point</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Contact Product</label>
                                <input type="text" name="ContactProduct" class="form-control" value="<?php echo $_POST['ContactProduct'] ?>" placeholder="Enter ...">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Contact Techom</label>
                                <input type="text" name="ContactTechOm" class="form-control" value="<?php echo $_POST['ContactTechOm'] ?>" placeholder="Enter ...">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label>Alpha test</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input value="<?php echo $_POST['AlphaTestDate'];?>"  id="alphatest" name="AlphaTestDate" class="form-control" placeholder="DD/MM/YYYY" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label>Open Date</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input value="<?php echo $_POST['OpenDate'];?>"  id="opendate" name="OpenDate" class="form-control" placeholder="DD/MM/YYYY"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.box-body -->

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
