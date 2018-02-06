<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-th"></i> <?php echo $title ?> (<a href="#" onclick="tableToExcel('<?php echo $id?>')">download</a>)  </h3>
                <div class="box-tools hidden">
                    <a download="<?php echo $exportTitle . '.xls'?>" href="" id="dlink" style="display:none;"></a>
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
                        if(count($data) == 0 ){

                            echo "<p>Hiện không có dữ liệu của thời gian trên, bạn vui lòng chọn ngày khác,
     hoặc liên hệ <b>[canhtq@vng.com.vn or quangctn@vng.com.vn or lamnt6@vng.com.vn]</b> để được hỗ trợ. Cảm ơn.</p>";
                        }else{?>
                            <table class="table table-bordered table-bordered-gray table-striped" id="<?php echo $id ?>" width="100%" data-export-title="<?php echo $exportTitle ?>">
                                <?php
                                $maxCount = 0;
                                foreach ($data as $key => $value) {

                                    $count = count($value);

                                    if($count < $maxCount){
                                        $count = $maxCount;
                                    }else{
                                        $maxCount=$count;
                                    }

                                    if($key == 'log_date'){

                                        for($i = 0; $i < $count; $i++ ) {

                                            foreach ($data['package'][$i] as $checkPac) {
                                                if (isset($checkPac)) {
                                                    $flag = true;
                                                }
                                            }
                                        }
                                        $rowspan=0;
                                        if($flag){
                                            $rowspan=2;
                                        }
                                        ?>
                                        <thead class="thead-inverse">
                                        <tr>
                                            <th class="text-left" rowspan="<?php echo $rowspan ?>"> KPI </th>
                                            <?php
                                            for($i = 0; $i < $count; $i++ ){
                                                $colspan = count($data['package'][$i]);
                                                ?>
                                                <th class="text-center" colspan="<?php echo $colspan ?>"><?php echo $value[$i] ?></th>
                                            <?php } ?>
                                        </tr>
                                        <?php
                                        if($flag){
                                        ?>
                                        <tr>
                                            <?php
                                            for($i = 0; $i < $count; $i++ ){
                                                foreach ($data['package'][$i] as $package) {
                                                    if(isset($package)){
                                                        ?>
                                                        <th class="text-center"><?php echo $package ?></th>
                                                    <?php }
                                                    else{

                                                    }
                                                }
                                            }?>
                                        </tr>
                                        <?php } ?>
                                        </thead>
                                    <?php }else{
                                        if($key != 'package'){ ?>
                                            <tr>
                                                <td class="text-left"><b><?php  echo $header[$key] ?></b></td>
                                                <?php
                                                for($i = 0; $i < $count; $i++ ){
                                                    foreach($value[$i] as $k => $v){ ?>

                                                        <td class="text-right"><?php echo $this->util->ud_format_number($v) ?></td>
                                                    <?php }
                                                } ?>

                                            </tr>
                                        <?php }

                                    }
                                }?>
                            </table>
                        <?php  }
                        ?>
            </div>
        </div>
    </div>
    <span id="exportFileName" hidden="true"><?php echo $export_file_name ?></span>
</div>
