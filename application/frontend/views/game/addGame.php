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
                                <label>Name</label>
                                <input type="text" name="GameName" class="form-control" value="<?php echo $_POST['GameName'] ?>" placeholder="Enter ...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="GameCode" class="form-control" value="<?php echo $_POST['GameCode'] ?>" placeholder="Enter ...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Send Email</label>
                                <select class="form-control" id="SendMail" name="SendMail">
                                    <?php foreach($sendMail as $key=>$value) {  ?>
                                        <option value="<?php echo $key?>" > <?php echo $value?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Region</label>
                                <select class="form-control" id="region" name="region">
                                    <?php foreach($region as $key=>$value) {  ?>
                                        <option value="<?php echo $key?>" > <?php echo $value?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" id="Status" name="Status">
                                    <?php foreach($Status as $key=>$value) {  ?>
                                        <option value="<?php echo $key?>" > <?php echo $value?></option>
                                    <?php  } ?>
                                </select>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Market</label>
                                <input type="text" name="market" class="form-control" value="<?php echo $_POST['market'] ?>" placeholder="Enter ...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Owner (Dept.)</label>
                                <input type="text" name="owner" class="form-control" value="<?php echo $_POST['owner'] ?>" placeholder="Enter ...">
                            </div>
                        </div>

                        <!-- <div class="col-md-2">
                <div class="form-group">
                    <label>Data source</label>
                    <input type="text" name="data_source" class="form-control" value="<?php /*echo $_POST['data_source'] */?>" placeholder="Enter ...">
                </div>
            </div>-->

                        <!--
        <div class="col-md-3">
	        <div class="form-group">
				<label>Type</label>
				<select class="form-control" name="IdGameType" hidden="true">
					<?php
                        //foreach ($gameType as $value) {
                        //	echo '<option value="'. $value['IdGameType'] .'" '. (($value['IdGameType'] == $_POST['IdGameType']) ? 'selected' : '') .' >'. $value['GameTypeName'] .'</option>';
                        //}
                        ?>
				</select>
	        </div>
	    </div>
-->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Type 2</label>
                                <select class="form-control" name="GameType2">
                                    <?php
                                    foreach ($gameType2 as $value) {
                                        echo '<option value="'. $value['Id'] .'" '. (($value['Id'] == $_POST['GameType2']) ? 'selected' : '') .' >'. $value['Type'] .'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-2">
                            <div class="form-group">
                                <label>% Paying</label>
                                <input type="text" name="PercentPaying" class="form-control" value="<?php echo $_POST['PercentPaying'] ?>" placeholder="Enter ...">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>% Playing</label>
                                <input type="text" name="PercentPlaying" class="form-control" value="<?php echo $_POST['PercentPlaying'] ?>" placeholder="Enter ...">
                            </div>
                        </div>


                    </div>

                </div><!-- /.box-body -->

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

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Contact Techom</label>
                                <input type="text" name="ContactTechOm" class="form-control" value="<?php echo $_POST['ContactTechOm'] ?>" placeholder="Enter ...">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label>Alpha test</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input value="<?php echo $_POST['AlphaTestDate'];?>"  id="alphatest" name="AlphaTestDate" class="form-control" placeholder="DD/MM/YYYY" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
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


                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Add Game</button>
                </div>
            </div><!-- /.box -->
        </div>
    </div>

</form>