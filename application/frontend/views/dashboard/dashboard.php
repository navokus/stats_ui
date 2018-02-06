<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 22/06/2016
 * Time: 11:19
 */
?>


<?php

if($nodata == true){
    echo "<p>Hiện không có dữ liệu của thời gian trên, bạn vui lòng chọn ngày khác,
     hoặc liên hệ <b>[canhtq@vng.com.vn or quangctn@vng.com.vn or lamnt6@vng.com.vn]</b> để được hỗ trợ. Cảm ơn.</p>";
}else{
?>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_t4" data-toggle="tab">Daily</a>
        </li>
        <li>
            <a href="#tab_t17" data-toggle="tab">Weekly</a>
        </li>
        <li>
            <a href="#tab_t31" data-toggle="tab">Monthly</a>
        </li>
    </ul>

    <div class="tab-content">
        <?php

        $timming_map = $this->util->get_timming_config();
        ?>
        <?php foreach($overview_data as $key => $v){
            $active = ($key == "4") ? " active" : "";
            $key_index = $timming_map[$key];
            $t_display = array("4" => "day", "17" => "week", "31" => "month");
            $t_title_display = array("4" => "Report for:",
                "17" => "Report for week:",
                "31" => "Report for month:");
			$fTitle="";
            $overview_logdate = date("d-M-Y", strtotime($v['log_date']));

			if($key=="17" || $key =="31"){

				$fTitle = " (from ". $this->util->getStartDateIn($overview_logdate,$key) . " to " . $overview_logdate .")"; 
			}
            ?>
            <div class="tab-pane<?php echo $active?>" id="tab_t<?php echo $key ?>">

                <section>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-header with-border dashboard-keykpis">
                                    <h3 class="box-title"><i class="fa fa-dashboard" name="overview"></i> <?php echo  $t_title_display[$key] . " " . $this->util->getDateByTimming($overview_logdate,$key) .$fTitle ?></h3>
                                </div>
                                <div class="box-footer dashboard-keykpis">
                                    <div class="row">
                                        <div class="col-">
                                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                                <div class="info-box bg-yellow">
                                                    <span class="info-box-icon revenue-chart"><i class="fa fa-dollar"></i></span>

                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Revenue (VND)</span>
                                                        <span class="info-box-number"><?php echo number_format($v['data']['gr' . $key_index]['data'] * 1.0 / 1000000, 2)?> mil</span>

                                                        <div class="progress">
                                                            <div class="progress-bar" style="width: 70%"></div>
                                                        </div>
									                <span class="progress-description">
									                	<?php
                                                        /*
                                                        $value = $v['data']['gr' . $key_index]['percent'];
                                                        if($value > 0) {
                                                            echo '<i class="fa fa-arrow-up"></i>';
                                                        } else if($value == 0) {
                                                            echo '<i class="fa fa-ellipsis-h"></i>';
                                                        } else {
                                                            echo '<i class="fa fa-arrow-down"></i>';
                                                        }
                                                        */
                                                        ?>
                                                        <?php
                                                        $percent = round($v['data']['gr' . $key_index]['percent'],2);
                                                        $p_d = $percent . "% vs. previous " . $t_display[$key] ;
                                                            //number_format($v['data']['gr' . $key_index]['before'] * 1.0 / 1000000, 2) . "mil";
                                                        echo $p_d;
                                                        ?>
									                </span>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                                <div class="info-box bg-blue">
                                                    <span class="info-box-icon revenue-chart"><i class="fa fa-users"></i></span>

                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Paying users</span>
                                                        <span class="info-box-number"><?php echo number_format($v['data']['pu' . $key_index]['data'])?></span>

                                                        <div class="progress">
                                                            <div class="progress-bar" style="width: 70%"></div>
                                                        </div>
									                <span class="progress-description">
									                	<?php
                                                        /*
                                                        $value = $v['data']['pu' . $key_index]['percent'];
                                                        if($value > 0) {
                                                            echo '<i class="fa fa-arrow-up"></i>';

                                                        } else if($value == 0) {
                                                            echo '<i class="fa fa-ellipsis-h"></i>';
                                                        } else {
                                                            echo '<i class="fa fa-arrow-down"></i>';
                                                        }
                                                        */

                                                        $percent = round($v['data']['pu' . $key_index]['percent'],2);
                                                        $p_d = $percent . "% vs. previous " . $t_display[$key];
                                                            //number_format($v['data']['pu' . $key_index]['before']);
                                                        echo $p_d;
                                                        ?>
									                </span>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                                <div class="info-box bg-green">
                                                    <span class="info-box-icon user-chart"><i class="fa fa-users"></i></span>

                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Active users</span>
                                                        <span class="info-box-number"><?php echo number_format($v['data']['a' . $key_index]['data'])?></span>

                                                        <div class="progress">
                                                            <div class="progress-bar" style="width: 70%"></div>
                                                        </div>
									                <span class="progress-description">
									                	<?php
                                                        /*
                                                        $value = $v['data']['a' . $key_index]['percent'];
                                                        if($value > 0) {
                                                            echo '<i class="fa fa-arrow-up"></i>';

                                                        } else if($value == 0) {
                                                            echo '<i class="fa fa-ellipsis-h"></i>';
                                                        } else {
                                                            echo '<i class="fa fa-arrow-down"></i>';
                                                        }*/

                                                        $percent = round($v['data']['a' . $key_index]['percent'],2);
                                                        $p_d = $percent . "% vs. previous " . $t_display[$key] ;
                                                            //number_format($v['data']['a' . $key_index]['before']);
                                                        echo $p_d;
                                                        ?>
									                </span>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                                <div class="info-box bg-red">
                                                    <span class="info-box-icon user-chart"><i class="fa fa-user-plus"></i></span>

                                                    <div class="info-box-content">
                                                        <span class="info-box-text">New users</span>
                                                        <span class="info-box-number"><?php echo number_format($v['data']['n' . $key_index]['data'])?></span>

                                                        <div class="progress">
                                                            <div class="progress-bar" style="width: 70%"></div>
                                                        </div>
									                <span class="progress-description">
									                	<?php
                                                        /*
                                                        $value = $v['data']['n' . $key_index]['percent'];
                                                        if($value > 0) {
                                                            echo '<i class="fa fa-arrow-up"></i>';

                                                        } else if($value == 0) {
                                                            echo '<i class="fa fa-ellipsis-h"></i>';
                                                        } else {
                                                            echo '<i class="fa fa-arrow-down"></i>';
                                                        }*/

                                                        $percent = round($v['data']['n' . $key_index]['percent'],2);
                                                        $p_d = $percent . "% vs. previous " . $t_display[$key];
                                                            //number_format($v['data']['n' . $key_index]['before']);
                                                        echo $p_d;
                                                        ?>
									                </span>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <?php
                                            /*
                                            $config = array("");
                                            foreach($v['data'] as $k => $value){
                                            	//var_dump($v['data']);
                                            	//exit();
                                                echo '<div class="col-sm-3 col-xs-6">';
                                                echo '<div class="description-block border-right">';

                                                if($value['percent'] > 0) {
                                                    $h1 = "text-green";
                                                    $h2 = "fa fa-caret-up";
                                                }else if ($value['percent'] < 0){
                                                    $h1 = "text-red";
                                                    $h2 = "fa fa-caret-down";
                                                }else{
                                                    $h1 = "text-yellow";
                                                    $h2 = "fa fa-caret-left";
                                                }
                                                $value['percent'] = round($value['percent'],2) . "%";
                                                echo $value['kpi_name'].' <span class="description-percentage '.$h1.'"><i class="'.$h2.'"></i> '.$value['percent'].'</span>';
                                                echo '<h5 class="description-header">'.number_format($value['data']).'</h5>';
                                                echo '<span class="description-text">'.$value['description'].'</span>';
                                                echo '</div>';
                                                echo '</div>';
                                                //$i++;
                                            }
                                            */
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>



                <?php
                if(isset($trendchart_data[$key]['trend_chart_1']['charts']['container_1_'.$key]['data'])){
                ?>
                <section id="section-revenue">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-warning">
                                <!--<div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-money" name="revenue"></i> Revenue</h3>
                                </div>-->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id="container_1_<?php echo $key?>"></div>
                                        </div>
                                    </div>
                                    <?php
                                    $viewdata = $trendchart_data[$key]['trend_chart_1']['charts']['container_1_'.$key];
                                    $html = $this->load->view("body_parts/chart/spline", $viewdata, TRUE);
                                    echo $html;
                                    ?>
                                    <div class="col-md-12">
                                        <a class="btn btn-primary btn-sm pull-right" href="<?php echo site_url('kpi/revenue'); ?>">Details</a>
                                    </div>
                                </div>
                            </div><!-- /.box -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </section>
                <?php } ?>

                <?php
                if(isset($trendchart_data[$key]['trend_chart_2']['charts']['container_2_'.$key]['data'])){
                ?>
                <section id="section-user">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <!--<div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-user" name="user"></i> User</h3>
                                </div>-->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id="container_2_<?php echo $key?>" class="chart"></div>
                                        </div>
                                    </div>
                                    <?php
                                    $viewdata = $trendchart_data[$key]['trend_chart_2']['charts']['container_2_'.$key];
                                    $html = $this->load->view("body_parts/chart/spline", $viewdata, TRUE);
                                    echo $html;
                                    ?>
                                    <div class="col-md-12">
                                        <a class="btn btn-primary btn-sm pull-right" href="<?php echo site_url('kpi/user'); ?>">Details</a>
                                    </div>
                                </div>
                            </div><!-- /.box -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </section>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
    <!-- /.tab-content -->
</div>


<?php } ?>