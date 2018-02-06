
<div class="box box-primary">
    <div class="box-footer text-black text-left">
        <form name="form" action="" method="post" class="form-horizontal">
            <div class="row">

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-gamepad"></i></span>
                        <select class="form-control" name="default_game" id="slGameSelection"
                                onchange="this.form.submit()">
                            <?php
                            $list_games = $body['aGames'];
                            foreach ( $list_games as $value ) {

                                if ($this->session->userdata ( 'default_game' ) == $value ['GameCode']) {
                                    $selected = ' selected ';
                                } else {
                                    $selected = '';
                                }

                                echo "<option value='{$value['GameCode']}' {$selected} >{$value['GameName']} (" . strtoupper ( $value ['GameCode'] ) . ")</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <?php
                    for($i=0;$i<count($full_source_arr);$i++){
                        $t_1 = $full_source_arr[$i];
                        ?>
                        <input type="checkbox" name="<?php echo $t_1?>" value="<?php echo $t_1?>" <?php echo (in_array($t_1,$source_arr)) ? "checked" : "";?>> <?php echo strtoupper($t_1)?> &nbsp;
                        <?php
                    }
                    ?>
                </div>

                <div class="col-md-4 col-lg-4 col-xs-6">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input value="<?php echo $body['day']['default_range_date'];?>"  id="daterangepicker" name="daterangepicker" class="form-control" />
                        <span class="input-group-btn">
			            	        <button type="submit" class="btn btn-danger">Xem</button>
			        	        </span>
                    </div>

                    <?php
                    for($i=0;$i<count($full_kpi_arr);$i++){
                        $t_1 = $full_kpi_arr[$i];
                        ?>
                        <input type="checkbox" name="<?php echo $t_1?>" value="<?php echo $t_1?>" <?php echo (in_array($t_1,$kpi_arr)) ? "checked" : "";?>> <?php echo strtoupper($t_1)?> &nbsp;
                        <?php
                    }
                    ?>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-th"></i> <?php echo $exportTitle ?></h3>
                <div class="box-tools">
                    <a class="btn btn-box-tool" href="#" title="Copy to clipboard!" id="copy">
                        <img src="<?php echo base_url('public/frontend/dist/img/copy.png'); ?>" width="22px"
                             height="22px"/>
                    </a>
                    <a class="btn btn-box-tool" href="#" title="Download excel file!" id="downloadExcel">
                        <img src="<?php echo base_url('public/frontend/dist/img/export.gif'); ?>" width="20px"
                             height="20px"/>
                    </a>
                </div>
            </div>
            <div class="box-body text-center first">
                <?php
                if(count($data) == 0 && $action == true){

                    echo "<p>Hiện không có dữ liệu của thời gian trên, bạn vui lòng chọn ngày khác,
     hoặc liên hệ <b>[canhtq@vng.com.vn or quangctn@vng.com.vn or lamnt6@vng.com.vn]</b> để được hỗ trợ. Cảm ơn.</p>";
                }else if (count($data) != 0){

                    foreach($data as $report_date => $value) {
                        for($i=0;$i<count($kpi_arr);$i++){
                            for($j=0;$j<count($source_arr);$j++){
                                if(!isset($total[$kpi_arr[$i]][$source_arr[$j]])){
                                    $total[$kpi_arr[$i]][$source_arr[$j]]=0;
                                }
                                if(!isset($value[$kpi_arr[$i]][$source_arr[$j]])){
                                    $value[$kpi_arr[$i]][$source_arr[$j]]=0;
                                }
                                $total[$kpi_arr[$i]][$source_arr[$j]]=$total[$kpi_arr[$i]][$source_arr[$j]]+$value[$kpi_arr[$i]][$source_arr[$j]];
                            }
                        }

                    }


                    echo '<table class="table table-bordered table-bordered-gray table-striped" id="' . $id .'" width="100%" data-export-title="' . $exportTitle . '">';

                    echo "<thead><tr>";
                    echo "<th rowspan='2' class='text-center'>Day</th>";

                    $kpi_num = count($kpi_arr) ;
                    $source_num = count($source_arr);
                    for($i=0; $i<count($kpi_arr);$i++){
                        echo "<th class='text-center' colspan='$source_num'>" . strtoupper($kpi_arr[$i]) . "</th>";
                    }
                    echo "</tr>";
                    echo "<tr>";

                    for($i=0; $i<count($kpi_arr);$i++){
                        for($j=0; $j<count($source_arr);$j++){
                            echo "<th class='text-center'>" . strtoupper($source_arr[$j]) . "</th>";
                        }
                    }
                    echo "</tr></thead>";


                    foreach($data as $report_date => $value){
                        echo "<tr>";
                        echo "<th class='text-center' style='width=100px;'>" . $report_date ."</th>";
                        for($i=0;$i<count($kpi_arr);$i++){
                            for($j=0;$j<count($source_arr);$j++){
                                $_kpi_value = isset($value[$kpi_arr[$i]][$source_arr[$j]]) ? $value[$kpi_arr[$i]][$source_arr[$j]] : "";

                                $percent = "";


                                if($j>0 && isset($value[$kpi_arr[$i]][$source_arr[$j-1]])){
                                    $before = $value[$kpi_arr[$i]][$source_arr[$j-1]];

                                    if($before != 0 && $_kpi_value != 0){
                                        $p_1 = (($_kpi_value - $before) / $before)*100;
                                        if($kpi_arr[$i] == "gr1"){
                                            //echo $p_1;exit();
                                        }
                                        $percent = round($p_1,2);
                                    }
                                }

                                $_kpi_value = number_format($_kpi_value);
                                if($percent === ""){
                                    echo "<th class='text-right'> $_kpi_value </th>";
                                }else{
                                    if(abs($percent) >=5){
                                        echo "<th class='text-right' style='color: #ff0000'>" . $_kpi_value . " (" . $percent . "%)" .  "</th>";
                                    }else{
                                        echo "<th class='text-right'>" . $_kpi_value . " (" . $percent . "%)" .  "</th>";
                                    }

                                }

                            }
                        }
                        echo "</tr>";
                    }
                    echo "<tr>";

                    echo "<th class='text-center' style='width=100px;'>" . "Total" ."</th>";
                    for($i=0;$i<count($kpi_arr);$i++){
                        for($j=0;$j<count($source_arr);$j++){
                            $_value = isset($total[$kpi_arr[$i]][$source_arr[$j]]) ? $total[$kpi_arr[$i]][$source_arr[$j]] : "";

                            $percent = "";


                            if($j>0 && isset($total[$kpi_arr[$i]][$source_arr[$j-1]])){
                                $before = $total[$kpi_arr[$i]][$source_arr[$j-1]];

                                if($before != 0 && $_value != 0){
                                    $p_1 = (($_value - $before) / $before)*100;
                                    if($kpi_arr[$i] == "gr1"){
                                        //echo $p_1;exit();
                                    }
                                    $percent = round($p_1,2);
                                }
                            }

                            $_value = number_format($_value);
                            if($percent === ""){
                                echo "<th class='text-right'> $_value </th>";
                            }else{
                                if(abs($percent) >=5){
                                    echo "<th class='text-right' style='color: #ff0000'>" . $_value . " (" . $percent . "%)" .  "</th>";
                                }else{
                                    echo "<th class='text-right'>" . $_value . " (" . $percent . "%)" .  "</th>";
                                }

                            }

                        }
                    }
                    echo "</tr>";



                    echo '</table>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
