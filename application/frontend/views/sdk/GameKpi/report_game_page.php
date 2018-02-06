<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 16/09/2016
 * Time: 09:40
 */
FUNCTION format_cash($cash)
{
    // strip any commas
    $cash = (0 + STR_REPLACE(',', '', $cash));

    // make sure it's a number...
    IF (!IS_NUMERIC($cash)) {
        RETURN FALSE;
    }

    // filter and format it
    IF ($cash > 1000000000000) {
        RETURN ROUND(($cash / 1000000000000), 2) . ' trillion';
    } ELSEIF ($cash > 1000000000) {
        RETURN ROUND(($cash / 1000000000), 2) . ' B';
    } ELSEIF ($cash > 1000000) {
        RETURN ROUND(($cash / 1000000), 2) . ' M';
    } ELSEIF ($cash > 1000) {
        RETURN ROUND(($cash / 1000), 2) . ' K';
    }

    RETURN NUMBER_FORMAT($cash);
}

?>

<div class="nav-tabs-custom">
    <!--code  -->
    <div class="tab-content">
        <!-- --><?php
        //$body['gameCode'] = 'stct';
        foreach ($section as $key => $value)
            $lstKpi = explode(',', $value);
        ?>
        <section>
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12">
                                    <div id="combo-dual-axes_<?php echo $timming ?>"></div>
                                </div>
                                <?php foreach ($lstDate as $date => $dateformat) {
                                    $chart['date'][] = $dateformat;
                                } ?>

                                <!--                                --><?php
                                //                                if (strcmp($timming, "4") == 0) {
                                //                                    //Get list date to present categories in chart
                                //                                    foreach ($dataChart as $report_date => $v) {
                                //                                        $chart['date'][] = date('F j, Y', strtotime($report_date));
                                //                                    }
                                //                                } else if (strcmp($timming, "17") == 0) {
                                //                                    foreach ($dataChart as $report_date => $v) {
                                //                                        $chart['date'][] = "Week " . date('W', strtotime($report_date));
                                //                                    }
                                //                                } else if (strcmp($timming, "31") == 0) {
                                //                                    foreach ($dataChart as $report_date => $v) {
                                //                                        $chart['date'][] = date('F / Y', strtotime($report_date));
                                //                                    }
                                //                                }
                                //                                ?>
                                <!--script -->
                                <?php
                                foreach ($lstDate as $date => $dateformat) {
                                    foreach ($lstKpi as $kpi) {
                                        if (isset($dataChart[$date][$body['gameCode']][$kpi])) {
                                            $chart['data_chart'][$kpi][] = $dataChart[$date][$body['gameCode']][$kpi];
                                        } else {
                                            $chart['data_chart'][$kpi][] = 0;
                                        }
                                    }

                                }

                                $chart['lstKpi'] = $lstKpi;
                                $chart['title'] = $key . " Report";
                                $chart['id'] = 'combo-dual-axes_' . $timming;
                                $chart['chart_width'] = "500";
                                $chart['substitle'] = $body ['game_info']["GameName"] . " - " . strtoupper($body ['game_info']["owner"]);
                                $html = $this->load->view("sdk/body_parts/chart/combo-dual-axes", $chart, TRUE);
                                echo $html;
                                ?>


                            </div>
                        </div><!-- /.box -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
               <!-- <div class="row">-->
                    <div class="col-md-12">
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-th"></i> <?php echo $key; ?></h3>
                                <div class="box-tools">
                                    <a class="btn btn-box-tool" href="#" title="Copy to clipboard!" id="copy">
                                        <img src="<?php echo base_url('public/frontend/dist/img/copy.png'); ?>"
                                             width="22px" height="22px"/>
                                    </a>
                                    <a class="btn btn-box-tool" href="#" title="Download excel file!"
                                       id="downloadExcel">
                                        <img src="<?php echo base_url('public/frontend/dist/img/export.gif'); ?>"
                                             width="20px" height="20px"/>
                                    </a>
                                </div>
                            </div>
                            <div class="box-body text-center first">
                                <div class="table-responsive kpi-table">
                                    <div id="all_kpi_wrapper"
                                         class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <table class="table table-striped table-bordered"
                                            <?php foreach (array_keys($section) as $k => $v) {
                                                $report_name = $v;
                                                break;
                                            } ?>
                                               id="kpi_table"
                                               data-export-title="<?php echo $report_name ?>_report" width="100%">
                                            <thead class="thead-inverse">
                                            <tr>
                                                <th class="text-left" style="width: auto;">KPI</th>
                                                <?php
                                                foreach ($lstDate as $date => $dateformat) { ?>
                                                    <th class="text-left"
                                                        style="width: auto;">
                                                        <?php echo $dateformat;
                                                        ?>
                                                    </th>
                                                <?php } ?>
                                            </tr>
                                            </thead>
                                            <?php
                                            foreach ($kpiTable as $kpi => $value) { ?>
                                                <tr>
                                                    <td><?php echo $value; ?> </td>
                                                    <?php foreach ($lstDate as $date => $dateformat) { ?>
                                                        <td class="text-left"><?php
                                                            if (isset($data[$date][$body['gameCode']][$kpi])) {
                                                                echo round($data[$date][$body['gameCode']][$kpi],2);
                                                            } else {
                                                                echo 0;
                                                            }
                                                            ?></td>
                                                    <?php } ?>
                                                </tr>
                                            <?php }
                                            ?>
                                        </table>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                        </div>
                        <!-- /.tab-content -->
                    </div>
                <!--</div>-->
        </section>
    </div>


</div>
<!-- /.tab-content -->

</div>



