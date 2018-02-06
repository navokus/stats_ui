<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 20/04/2016
 * Time: 09:31
 */

//var_dump($data);exit();
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-th"></i> <?php echo $title ?> </h3>
                <div class="box-tools">
                	<a class="btn btn-box-tool" href="#" title="Copy to clipboard!"  id="copy">
						<img src="<?php echo base_url('public/frontend/dist/img/copy.png'); ?>" width="22px" height="22px" />
					</a>
                	<a class="btn btn-box-tool" href="#" title="Download excel file!" id="downloadExcel">
						<img src="<?php echo base_url('public/frontend/dist/img/export.gif'); ?>" width="20px" height="20px" />
					</a>
              </div>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-bordered-gray table-striped" id="<?php echo $id?>" data-export-title="<?php echo $exportTitle?>">
                    <thead>
                    <tr>
                        <th rowspan="2">Ngày</th>
                        <th colspan="<?php echo $colspan?>" class="text-center">ANDROID</th>
                        <th colspan="<?php echo $colspan?>" class="text-center">IOS</th>
                        <th colspan="<?php echo $colspan?>" class="text-center">OTHER</th>
                    </tr>

                    <tr>
                        <?php
                        for($i=0;$i<3;$i++){
                            $isFirstRow = true;
                            foreach($header as $key => $value){
                                if($isFirstRow){
                                    echo '<th class="text-center" style="width=100px;">' . strtoupper($value) . '</th>';
                                    $isFirstRow = false;
                                }else{

                                    if(strpos ( $value, "trend") == false){
                                        echo '<th class="text-center">' . strtoupper($value) . '</th>';
                                    }else{
                                        echo '<th class="text-center no-padding-right"><i class="fa fa-caret-up text-green"></i><i class="fa fa-caret-down text-red"></i></th>';
                                    }
                                }
                            }
                        }
                        ?>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    for($i=0;$i<count($data);$i++) {
                        $isFirstRow = true;
                        $row = $data[$i];
                        echo '<tr>';
                        echo '<td class="text-center">' . ($row['log_date']) . '</td>';

                        foreach($header as $_key => $_value) {
                            $value = $row['android'][$_key];
                            if (strpos($value, "%") === false && strpos($value, ",") === false && strpos($value, "-") === false)
                                $value = number_format($value);
                            echo '<td class="text-right">' . ($value) . '</td>';
                        }

                        foreach($header as $_key => $_value) {
                            $value = $row['ios'][$_key];
                            if (strpos($value, "%") === false && strpos($value, ",") === false && strpos($value, "-") === false)
                                $value = number_format($value);
                            echo '<td class="text-right">' . ($value) . '</td>';
                        }

                        foreach($header as $_key => $_value) {
                            $value = $row['other'][$_key];
                            if (strpos($value, "%") === false && strpos($value, ",") === false && strpos($value, "-") === false)
                                $value = number_format($value);
                            echo '<td class="text-right">' . ($value) . '</td>';
                        }

                        echo '</tr>' . "\n";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
