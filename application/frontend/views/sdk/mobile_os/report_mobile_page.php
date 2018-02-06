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
        //Lay so luong os nhieu nhat
        $i=0;
        foreach ($days as $date){
            $countChannel[$i]=count(array_keys($data[$date][$body['gameCode']]));
            if($i>0){
                if($countChannel[$i] > $countChannel[$i--]){
                    $os_names = array_keys($data[$date][$body['gameCode']]);
                }
            }else{
                $os_names= array_keys($data[$date][$body['gameCode']]);
            }

            $i++;

        }
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
                                        foreach ($days as $date){
                                            foreach ($os_names as $os){
                                                $datas[$date][$os]=$dataChart[$date][$body['gameCode']][$key][$os];
                                            }
                                        }
                                        $chart = array();
                                        $chart['datas'] = $datas;
                                        $chart['id'] = 'Chart_' . $key;
                                        $chart['days']=$days;
                                        $chart['names']=$os_names;

                                        if (strcmp($key, "10001") == 0) {
                                            $chart['title'] = 'Active User by '.$titleTable;
                                            $chart['subTitle'] = $this->util->get_sub_chart_title(array("feature"=>"Active Users", "to"=>$body["toDate"], "from"=>$body["fromDate"], "game_info"=>$this->_gameInfo), 6);
                                            $chart['metric'] = "Active User";
                                            $chart['unit'] = "user";
                                        }
                                        if (strcmp($key, "16001") == 0) {
                                            $chart['title'] = 'Revenue User by '.$titleTable;
                                            $chart['subTitle'] = $this->util->get_sub_chart_title(array("feature"=>"Revenue User", "to"=>$body["toDate"], "from"=>$body["fromDate"], "game_info"=>$this->_gameInfo), 6);
                                            $chart['metric'] = "Revenue";
                                            $chart['unit'] = "VND";
                                        }
                                        $html = $this->load->view("sdk/body_parts/chart/stack_bar_gColumn", $chart, TRUE);
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
                                            <?php if (count($os_names)==0){
                                                echo "<p>Hiện không có dữ liệu của thời gian trên, bạn vui lòng chọn ngày khác,
     hoặc liên hệ <b>[canhtq@vng.com.vn or quangctn@vng.com.vn or lamnt6@vng.com.vn]</b> để được hỗ trợ. Cảm ơn.</p>";
                                            } else {?>
                                                <table class="table table-striped table-bordered" id="kpi_table"
                                                       data-export-title="<?php echo $titleTable ?>_report_<?php echo $body['fromdate']."-".$body['todate'] ?>" width="100%">
                                                    <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-left" rowspan="2" style="width: auto;">KPI Name</th>
                                                        <?php
                                                        $count=count($os_names);
                                                        foreach ($days as $date) {
                                                            ?>
                                                            <th class="text-center" style="width: auto;" colspan="<?php echo $count; ?>"><?php echo $date ?> </th>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <?php
                                                        foreach ($days as $date) {
                                                            foreach ($os_names as $os) {
                                                                ?>
                                                                <th class="text-left"
                                                                    style="width: auto;"><?php echo $os ?> </th>
                                                            <?php }
                                                        }?>
                                                    </tr>

                                                    </thead>
                                                    <?php
                                                    foreach ($kpiTable as $kpi => $desc) { ?>
                                                        <tr>
                                                            <td> <?php echo $desc ?> </td>
                                                            <?php foreach ($days as $date) { ?>
                                                                <?php foreach ($os_names as $os) { ?>
                                                                    <td class="text-left"><?php
                                                                        if(isset($data[$date][$body['gameCode']][$os][$kpi])){
                                                                            echo  number_format($data[$date][$body['gameCode']][$os][$kpi]);
                                                                        }else{
                                                                            echo 0;
                                                                        }

                                                                    ?></td>
                                                                <?php } ?>
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



