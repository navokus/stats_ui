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
                <?php
                if ($btn_download != "false") { ?>
                    <div class="box-tools">
                        <a class="btn btn-box-tool" href="#" title="Copy to clipboard!"  id="copy">
                            <img src="<?php echo base_url('public/frontend/dist/img/copy.png'); ?>" width="22px" height="22px" />
                        </a>
                        <a class="btn btn-box-tool" href="#" title="Download excel file!" id="downloadExcel">
                            <img src="<?php echo base_url('public/frontend/dist/img/export.gif'); ?>" width="20px" height="20px" />
                        </a>
                    </div>
                <?php } ?>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-bordered-gray table-striped" id="<?php echo $id?>" data-export-title="<?php echo $exportTitle?>">
                    <thead>
                    <tr>
                        <?php
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
                        ?>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    //for($i=0;$i<count($data);$i++){
                    foreach($data as $i => $v){
                        $isFirstRow = true;
                        $row = $data[$i];
                        echo '<tr>';

                        if($number_format === "false"){
                            foreach ($row as $key => $value) {
                                echo '<td class="text-center">' . ($value) . '</td>' ;
                            }
                        }else{
                            foreach ($row as $key => $value) {
                                if($isFirstRow){
                                    $value = $this->util->ud_format_number($value);
                                    echo '<td class="text-center">' . ($value) . '</td>' ;
                                    $isFirstRow = false;
                                }else{
                                    $value = $this->util->ud_format_number($value);
                                    echo '<td class="text-right">' . ($value) . '</td>' ;
                                }
                            }
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
