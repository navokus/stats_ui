<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 20/04/2016
 * Time: 09:31
 */

?>
<section>
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
            <div class="box-body">
                <table class="table table-bordered table-bordered-gray table-striped" id="<?php echo $id?>" data-export-title="<?php echo $exportTitle?>">
                    <thead>
                    <tr>
                        <?php
                        $isFirstRow = true;
                        echo '<th class="text-center">KPI Name</th>';
                        foreach($header as $key => $value){
                            if($isFirstRow){
                                echo '<th class="text-center" style="width=100px;">' . $value . '</th>';
                                $isFirstRow = false;
                            }else{
                                echo '<th class="text-left">' . $value . '</th>';
                            }
                        }
                        ?>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    foreach($data as $key => $value_arr) {
                        $kpi_name = $value_arr[0];
                        echo '<tr>';
                        echo '<td class="text-left">' . $kpi_name . '</td>';
                        for ($i = 1; $i < count($value_arr); $i++) {
                            $value = $value_arr[$i];
                            if (strpos($value, "%") === false && strpos($value, ",") === false && strpos($value, "-") === false) $value = number_format($value);
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
    <span id="exportFileName" hidden="true"><?php echo $export_file_name ?></span>
</section>