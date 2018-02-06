<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 20/06/2016
 * Time: 16:00
 */
if ($rawdata != null) {
    $collapse = "collapsed-box";
    $button = "fa-plus";
} else {
    $button = "fa-minus";
}
?>

<div class="box box-primary <?php //echo $collapse;?>">
    <!--<div class="box-header with-border">
		<a class="box-title" href="#" <?php echo $hidden ?> data-toggle="collapse" data-target="#listting-report">
			<i class="fa fa-filter"></i><span class="hidden-xs"> Filter</span>
		</a>
		<div class="box-tools pull-right">
	        <button type="button" class="btn btn-box-tool" data-widget="collapse" ><i class="fa <?php echo $button; ?>"></i></button>
	    </div>
	</div>-->
    <div class="box-body">
        <form name="form" action="" method="post">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-gamepad"></i></span>
                            <select class="form-control" name="default_game" id="slGameSelection"
                                    onchange="this.form.submit()">
                                <?php
                                $list_games = $body['aGames'];
                                foreach ($list_games as $value) {

                                    if ($this->session->userdata('default_game') == $value ['GameCode']) {
                                        $selected = ' selected ';
                                    } else {
                                        $selected = '';
                                    }

                                    echo "<option value='{$value['GameCode']}' {$selected} >{$value['GameName']} (" . strtoupper($value ['GameCode']) . ")</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- /.col -->
                    <div class="col-md-4 col-lg-3 col-xs-6">
                        <div class="form-group">
                            <select class="form-control" id="data_source" name="data_source">

                                <?php foreach ($dataSource as $key) {
                                    if ($body['source'] == $key) {
                                        $selected = ' selected ';
                                    } else {
                                        $selected = '';
                                    }
                                  echo "<option value='" . $key . "' " . $selected . ">" . $key . "</option>";
                                  /*  <option value="<?php echo $key ?>"> <?php echo $key */?><!--</option>-->
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="option_day">
                        <div class="col-md-4 col-lg-4 col-xs-6">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input value="<?php echo $body['day']['default_range_date']; ?>" id="daterangepicker"
                                       name="daterangepicker" class="form-control"/>
                                <span class="input-group-btn">
			            	        <button type="submit" class="btn btn-danger">Xem</button>
			        	        </span>
                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </form>
    </div>
</div>


<script type="text/javascript">
    function select_all(sid) {
        var select_all_obj = document.getElementById("select_all_" + sid)
        var kpi_checkbox = document.getElementsByClassName("export_checkbox_" + sid)
        for (var i = 0; i < kpi_checkbox.length; i++) {
            kpi_checkbox[i].checked = select_all_obj.checked
        }
    }
</script>

