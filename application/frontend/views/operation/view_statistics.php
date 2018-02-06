<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 16/09/2016
 * Time: 09:40
 */

?>
<div class="box box-primary">
    <div class="box-footer text-black text-left">
        <form name="form" action="" method="post" class="form-horizontal">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="input-group" id="inputDate">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input value="<?php echo $body['day']['kpidatepicker'];?>" id="kpidatepicker" name="kpidatepicker" class="form-control" />
				    	<span class="input-group-btn">
			            	<button type="submit" class="btn btn-danger">Xem</button>
			        	</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_selected" data-toggle="tab">Selected day</a>
        </li>
        <li>
            <a href="#tab_day7ago" data-toggle="tab">Last 7 day</a>
        </li>
        <li>
            <a href="#tab_day30ago" data-toggle="tab">Last 30 day</a>
        </li>
    </ul>

    <div class="tab-content">
        <?php

        $first=true;
        foreach($data_chart as $date_key => $value){
            $active = "";
            if($first==true){
                $active = "active";
                $first=false;
            }
            ?>

            <div class="tab-pane <?php echo $active?>" id="tab_<?php echo $date_key?>">
                <section>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-footer">
                                    <div class="row">
                                    <?php
                                    foreach($data_chart[$date_key] as $metric_key => $metric_value) {
                                        ?>

                                            <div class="col-md-4">
                                                <div id="container_<?php echo $date_key . "_" . $metric_key?>"></div>
                                            </div>

                                        <?php
                                        $metric_value['id'] = "container_" . $date_key . "_" . $metric_key;
                                        $metric_value['chart_width'] = "500";
                                        $html = $this->load->view("body_parts/chart/bar", $metric_value, TRUE);
                                        echo $html;
                                        //echo $date_key . "_" . $metric_key;
                                    }
                                    ?>
                                    </div>
                                </div>
                            </div><!-- /.box -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </section>
            </div>

        <?php
        }
        ?>
    </div>
    <!-- /.tab-content -->
</div>

<div class="row">
    <div class="col-md-12">
        <div id="container_line_chart"></div>
        <?php
        $html_line = $this->load->view("body_parts/chart/spline", $line_chart, TRUE);
echo $html_line;
?>
    </div>
</div>