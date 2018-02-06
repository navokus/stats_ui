<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 22/06/2016
 * Time: 11:19
 */
//var_dump($rawdata['charts']);exit();
?>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_t4" data-toggle="tab">Daily</a>
        </li>
        <li>
            <a href="#tab_t5" data-toggle="tab">Last 7 Days</a>
        </li>
        <li>
            <a href="#tab_t6" data-toggle="tab">Last 30 Days</a>
        </li>
    </ul>

    <div class="tab-content">
        <?php
        $overview_logdate = $overview_data['log_date'];
        unset($overview_data['log_date']);
        $timming_map = $this->util->get_timming_config();
        ?>
        <?php foreach($rawdata['charts'] as $key => $v){
            $active = ($key == "4") ? " active" : "";
            $key_index = $timming_map[$key];
            ?>
            <div class="tab-pane<?php echo $active?>" id="tab_t<?php echo $key ?>">
                <section id="section-os">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="box box-warning">
                                <!--<div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-money" name="revenue"></i> Revenue</h3>
                                </div>-->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id="container_gr_<?php echo $key?>"></div>
                                        </div>
                                    </div>
                                    <?php
                                    $viewdata = $rawdata['charts'][$key]['gr'];
                                    $viewdata['id'] = "container_gr_" . $key;
                                    $html = $this->load->view("body_parts/chart/pie", $viewdata, TRUE);
                                    echo $html;
                                    ?>

                                </div>
                            </div><!-- /.box -->
                        </div><!-- /.col -->
                        <div class="col-md-3">
                            <div class="box box-warning">
                                <!--<div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-money" name="revenue"></i> Revenue</h3>
                                </div>-->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id="container_pu_<?php echo $key?>"></div>
                                        </div>
                                    </div>
                                    <?php
                                    $viewdata = $rawdata['charts'][$key]['pu'];
                                    $viewdata['id'] = "container_pu_" . $key;

                                    $html = $this->load->view("body_parts/chart/pie", $viewdata, TRUE);
                                    echo $html;
                                    ?>

                                </div>
                            </div><!-- /.box -->
                        </div><!-- /.col -->
                        <div class="col-md-3">
                            <div class="box box-warning">
                                <!--<div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-money" name="revenue"></i> Revenue</h3>
                                </div>-->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id="container_a_<?php echo $key?>"></div>
                                        </div>
                                    </div>
                                    <?php
                                    $viewdata = $rawdata['charts'][$key]['a'];
                                    $viewdata['id'] = "container_a_" . $key;
                                    $html = $this->load->view("body_parts/chart/pie", $viewdata, TRUE);
                                    echo $html;
                                    ?>

                                </div>
                            </div><!-- /.box -->
                        </div><!-- /.col -->
                        <div class="col-md-3">
                            <div class="box box-warning">
                                <!--<div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-money" name="revenue"></i> Revenue</h3>
                                </div>-->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id="container_n_<?php echo $key?>"></div>
                                        </div>
                                    </div>
                                    <?php
                                    $viewdata = $rawdata['charts'][$key]['n'];
                                    $viewdata['id'] = "container_n_" . $key;
                                    $html = $this->load->view("body_parts/chart/pie", $viewdata, TRUE);
                                    echo $html;
                                    ?>

                                </div>
                            </div><!-- /.box -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                    <?php
                    $viewdata = $rawdata['charts'][$key]['gr_detail'];

                    if($viewdata['data']['gr']['data'] != false){
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box box-warning">
                                    <!--<div class="box-header with-border">
                                        <h3 class="box-title"><i class="fa fa-money" name="revenue"></i> Revenue</h3>
                                    </div>-->
                                    <div class="box-footer">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="container_gr_detail_<?php echo $key?>"></div>
                                            </div>
                                        </div>
                                        <?php


                                        $viewdata['id'] = "container_gr_detail_" . $key;
                                        $html = $this->load->view("body_parts/chart/spline", $viewdata, TRUE);
                                        echo $html;

                                        ?>

                                    </div>
                                </div><!-- /.box -->
                            </div>
                        </div>
                    <?php } ?>
                </section>

            </div>
        <?php } ?>
    </div>
    <!--  data table-->
    <?php
    $html = $this->load->view("body_parts/table/device_table", $rawdata['table'], TRUE);
    echo $html;

    ?>
    <!-- /.tab-content -->

</div>