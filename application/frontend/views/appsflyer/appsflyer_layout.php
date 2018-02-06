<?php echo $body['selection'];?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-th"></i> <?php echo $title ?></h3>
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

                <?php
                if(count($rawdata['tables']) == 0){
                echo "<p>Hiện không có dữ liệu của thời gian trên, bạn vui lòng chọn ngày khác,
                    hoặc liên hệ <b>[canhtq@vng.com.vn or quangctn@vng.com.vn or lamnt6@vng.com.vn]</b> để được hỗ trợ. Cảm ơn.</p>";
                }else{
                ?>
                <div class="table-responsive kpi-table">
                    <div id="all_kpi_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                        <table class ="table table-striped table-bordered" id = "marketing_report"width="100%">
                            <thead class="thead-inverse">
                            <tr>
                                <th class="text-left" style="width: auto;">Date</th>
                                <th class="text-left" style="width: auto;">Media Source</th>
                                <th class="text-left" style="width: auto;">Campaign name</th>
                                <th class="text-left" style="width: auto;">Device (OS)</th>
                                <th class="text-left" style="width: auto;">Install (GMT +7)</th>
                                <th class="text-left" style="width: auto;">NRU0</th>
                                <th class="text-left" style="width: auto;">NRU</th>
                                <th class="text-left" style="width: auto;">REV1</th>
                                <th class="text-left" style="width: auto;">REV7</th>
                            </tr>
                            </thead>
                            <?php
                            for ($j = 0; $j < count($rawdata['tables']); $j++) {
                                ?>
                                <tr>
                                    <td class="text-left"><?php echo $rawdata['tables'][$j]['logdate'][0] ?></td>
                                    <td class="text-left"><?php echo $rawdata['tables'][$j]['media_source'][0] ?></td>
                                    <td class="text-left"><?php echo $rawdata['tables'][$j]['campaign'][0] ?></td>
                                    <td class="text-leftt"><?php echo $rawdata['tables'][$j]['os'][0] ?></td>
                                    <td class="text-right"><?php echo $rawdata['tables'][$j]['install'] ?></td>
                                    <td class="text-right"><?php echo $rawdata['tables'][$j]['nru0'] ?></td>
                                    <td class="text-right"><?php echo $rawdata['tables'][$j]['nru1'] ?></td>
                                    <td class="text-right"><?php echo $rawdata['tables'][$j]['rev1'] ?></td>
                                    <td class="text-right"><?php echo $rawdata['tables'][$j]['rev7'] ?></td>
                                </tr>
                            <?php }
                            }?>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
			</div>
        </div>
			<!-- /.tab-content -->
    </div>
</div>
