<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 16/09/2016
 * Time: 09:40
 */
?>
<div class="nav-tabs-custom">
    <!--code  -->
    <div class="tab-content">
        <?php

        //Lay so luong channel nhieu nhat
        $i=0;
        foreach ($lstdate as $date){
            $countChannel[$i]=count(array_keys($data[$date][$body['gameCode']]));
            if($i>0){
                if($countChannel[$i] > $countChannel[$i--]){
                    $channels = array_keys($data[$date][$body['gameCode']]);
                }
            }else{
                $channels= array_keys($data[$date][$body['gameCode']]);
            }

            $i++;
        }

        $dataFilter = $data[$lstdate][$body['gameCode']];

        $colors = array(
            "#F08080", "#20B2AA", "#778899", "#9370DB", "#3CB371", "#191970", "#FF4500",
            "#DAA520", "#2F4F4F", "#B0C4DE", "#800000", "#808000", "#CD853F", "#708090",
            "#5F9EA0", "#008B8B", "#FF8C00", "#2F4F4F", "#DAA520", "#CD5C5C",
            "#DB7093", "#663399", "#4169E1", "#8B4513", "#4682B4", "#008080", "#40E0D0"
        );

        ?>
        <section>
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12">
                                    <?php
                                    foreach ($section as $key => $value) {
                                        ?>
                                        <?php
                                        foreach ($lstdate as $date){
                                            foreach ($channels as $channel)
                                            $datas[$date][$channel]=$dataChart[$date][$body['gameCode']][$channel][$key];
                                        }
                                        $chart = array();
                                        $chart['colors']=$colors;
                                        $chart['datas'] = $datas;
                                        $chart['id'] = 'Chart_' . $key;
                                        $chart['days']=$lstdate;
                                        $chart['names']=$channels;

                                        if (strcmp($key, "10001") == 0) {
                                            $chart['title'] = 'Active User by '.$titleTable;
                                            $chart['subTitle'] = $this->util->get_sub_chart_title(array("feature"=>"Active Users", "to"=>$body["toDate"], "from"=>$body["fromDate"], "game_info"=>$this->_gameInfo), 6);

                                        }
                                        if (strcmp($key, "16001") == 0) {
                                            $chart['title'] = 'Revenue User by '.$titleTable;
                                            $chart['subTitle'] = $this->util->get_sub_chart_title(array("feature"=>"Revenue User", "to"=>$body["toDate"], "from"=>$body["fromDate"], "game_info"=>$this->_gameInfo), 6);

                                        }
                                        $html = $this->load->view("sdk/body_parts/chart/stack_bar", $chart, TRUE);
                                        echo $html;
                                        ?>
                                        <div class="col-md-6 col-lg-6 col-sm-6">
                                            <div id="Chart_<?php echo $key; ?>"></div>
                                        </div>
                                    <?php }
                                    ?>


                                </div>
                            </div><!-- /.box -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-th"></i> <?php echo $titleTable?> Detail Table</h3>
                                    <div class="box-tools">
                                        <a class="btn btn-box-tool" href="#" title="Copy to clipboard!"  id="copy">
                                            <img src="<?php echo base_url('public/frontend/dist/img/copy.png'); ?>" width="22px" height="22px" />
                                        </a>
                                        <a class="btn btn-box-tool" href="#" title="Download excel file!" id="downloadExcel">
                                            <img src="<?php echo base_url('public/frontend/dist/img/export.gif'); ?>" width="20px" height="20px" />
                                        </a>
                                    </div>
                                </div>
                                <div class="box-body text-center first">
                                    <div class="table-responsive kpi-table">
                                        <div id="all_kpi_wrapper"
                                             class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                            <?php if (count($channels)==0){
                                                echo "<p>Hiện không có dữ liệu của thời gian trên, bạn vui lòng chọn ngày khác,
     hoặc liên hệ <b>[canhtq@vng.com.vn or quangctn@vng.com.vn or lamnt6@vng.com.vn]</b> để được hỗ trợ. Cảm ơn.</p>";
                                            } else {?>
                                                <table class="table table-striped table-bordered" id="kpi_table"
                                                       data-export-title="<?php echo $titleTable ?>_report_<?php echo $lstdate ?>" width="100%">
                                                    <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-left" style="width: auto;">KPI Name</th>
                                                        <?php
                                                        foreach ($channels as $channel) {
                                                            ?>
                                                            <th class="text-left" style="width: auto;"><?php echo $channel ?> </th>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tr>
                                                    </thead>
                                                    <?php

                                                    foreach ($kpiTable as $kpi => $desc) { ?>
                                                        <tr>

                                                            <td> <?php echo $desc ?> </td>
                                                            <?php foreach ($channels as $channel) { ?>
                                                                <td class="text-left"><?php echo $dataFilter[$channel][$kpi]; ?></td>
                                                            <?php } ?>
                                                        </tr>
                                                    <?php }
                                                    ?>
                                                </table>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-content -->
                        </div>
                    </div>
        </section>
    </div>


</div>
<!-- /.tab-content -->

</div>



