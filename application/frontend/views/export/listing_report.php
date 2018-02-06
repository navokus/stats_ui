<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 20/06/2016
 * Time: 16:00
 */
if($rawdata != null){
    $collapse = "collapsed-box";
    $button = "fa-plus";
}else{
    $button = "fa-minus";
}
?>

<div class="box box-primary <?php //echo $collapse;?>">
    <!--<div class="box-header with-border">
		<a class="box-title" href="#" <?php echo $hidden?> data-toggle="collapse" data-target="#listting-report">
			<i class="fa fa-filter"></i><span class="hidden-xs"> Filter</span>
		</a>
		<div class="box-tools pull-right">
	        <button type="button" class="btn btn-box-tool" data-widget="collapse" ><i class="fa <?php echo $button;?>"></i></button>
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
                                foreach ( $list_games as $value ) {

                                    if ($this->session->userdata ( 'default_game' ) == $value ['GameCode']) {
                                        $selected = ' selected ';
                                    } else {
                                        $selected = '';
                                    }

                                    echo "<option value='{$value['GameCode']}' {$selected} >{$value['GameName']} (" . strtoupper ( $value ['GameCode'] ) . ")</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- /.col -->

                    <div class="option_day">
                        <div class="col-md-4 col-lg-4 col-xs-6">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input value="<?php echo $body['day']['default_range_date'];?>"  id="daterangepicker" name="daterangepicker" class="form-control" />
	                            <span class="input-group-btn">
			            	        <button type="submit" class="btn btn-danger">Xem</button>
			        	        </span>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="box-body text-center">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-responsive">
                            <tbody>
                            <?php
                            /*
                                                    foreach($all_reports as $key => $value){
                                                        echo '<tr>';
                                                        echo '<td><span class="checkbox"><input id="select_all_'.$key.'" type="checkbox" onchange="select_all('.$key.')">Select all</span></td>';
                                                        foreach($value as $k => $v){
                                                            echo '<td><span class="checkbox"><input class="export_checkbox_'.$key.'" id="export_checkbox_'.$k.'" name="'.$k.'" type="checkbox" value="'.$k.'">'.strtoupper($v).'</span></td>';
                                                        }
                                                        echo '</tr>';
                                                    }
                            */
                            ?>

                            </tbody>
                        </table>

                        <!-- <li class="box-tools pull-right ub-controls">-->
                        <!--<div class="box-body">
                             <button type="submit" class="btn btn-danger pull-right">Xem</button>
                        </div>-->
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<script type="text/javascript">
    function select_all(sid){
        var select_all_obj = document.getElementById("select_all_" + sid)
        var kpi_checkbox = document.getElementsByClassName("export_checkbox_" + sid)
        for(var i=0; i<kpi_checkbox.length; i++) {
            kpi_checkbox[i].checked = select_all_obj.checked
        }
    }
</script>

