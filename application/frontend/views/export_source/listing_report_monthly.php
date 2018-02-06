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
                    <!-- /.col -->
                    <div class="option_day">
                        <div class="col-md-4 col-lg-4 col-xs-6">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <select class="form-control" name="monthly" style="width: 100%;"
                                        onchange="this.form.submit()">
                                    <?php

                                    foreach ($body['optionsMonth'] as $key => $value) {
                                        if ($body['month'] == $key) {
                                            $selected = ' selected ';
                                        } else {
                                            $selected = '';
                                        }
                                        echo "<option value='" . $key . "' " . $selected . ">" . $value . "</option>";
                                    }
                                    ?>
                                </select>
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

