<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 20/04/2016
 * Time: 09:31
 */
?>
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
            <div class="box-body  text-center first">
                <table class="table table-bordered table-bordered-gray table-striped" id="<?php echo $id?>" data-export-title="<?php echo $exportTitle?>">
                    <thead>
                    <tr>
                        <?php
                        $isFirstRow = true;
                        foreach($header as $key => $value) {
                            if ($isFirstRow) {
                                echo '<th class="text-center">' . strtoupper($value) . '</th>';
                                $isFirstRow = false;
                            } else {
                                echo '<th class="text-center">' . strtoupper($value) . '</th>';
                            }
                        }
                        ?>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    $header_name = $this->util->get_kpi_header_name();

                    foreach($data as $kpi_id => $kpi_value){
                        echo '<tr>';
                        echo '<td class="text-left">' . $header_name[$kpi_id] . '</td>' ;
                        $android = $this->util->ud_format_number($kpi_value['android']);
                        $ios = $this->util->ud_format_number($kpi_value['ios']);
                        $other = $this->util->ud_format_number($kpi_value['other']);

                        echo '<td class="text-right">' . ($android) . '</td>' ;
                        echo '<td class="text-right">' . ($ios) . '</td>' ;
                        echo '<td class="text-right">' . ($other) . '</td>' ;
                        echo '</tr>' . "\n";

                    }


                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <span id="exportFileName" hidden="true"><?php echo $export_file_name ?></span>
</div>
