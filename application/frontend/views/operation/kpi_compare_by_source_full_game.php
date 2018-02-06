<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-th"></i> <?php echo $title ?></h3>

            </div>
            <div class="box-body text-center first">
                <?php
                if(count($data) == 0 ){

                    echo "<p>Hiện không có dữ liệu của thời gian trên, bạn vui lòng chọn ngày khác,
     hoặc liên hệ <b>[canhtq@vng.com.vn or quangctn@vng.com.vn or lamnt6@vng.com.vn]</b> để được hỗ trợ. Cảm ơn.</p>";
                }else{
                    echo '<table class="table table-bordered table-bordered-gray table-striped" id="' . $id .'" width="100%" data-export-title="' . $exportTitle . '">' . "\n";

                    echo "<tr>";
                    echo "<th rowspan='2'>Game</th>";
                    echo "<th rowspan='2'>KPI</th>";
                    $source_num = count($source_arr);
                    for($i=0; $i<count($day_arr);$i++){
                        echo "<th colspan='$source_num'>" . $day_arr[$i] . "</th>";
                    }
                    echo "</tr>";

                    echo "<tr>";

                    for($i=0; $i<count($day_arr);$i++){
                        for($j=0;$j<count($source_arr);$j++){
                            echo "<th>" . strtoupper($source_arr[$j]) ."</th>";
                        }
                    }

                    echo "</tr>";


                    foreach($data as $game_code => $value){
                        echo "<tr>";
                        $kpi_num = count($value) +1;
                        echo "<th rowspan='$kpi_num'>" . strtoupper($game_code) ."</th>";
                        echo "</tr>";

                        foreach($value as $kpi_code => $kpi_detail){
                            echo "<tr>";
                            echo "<th>". strtoupper($kpi_code).  "</th>";
                            for($i=0; $i<count($day_arr);$i++){
                                for($j=0;$j<count($source_arr);$j++){
                                    $_kpi_value = isset($value[$kpi_code][$day_arr[$i]][$source_arr[$j]]) ? $value[$kpi_code][$day_arr[$i]][$source_arr[$j]] : "";
                                    $_kpi_value = number_format($_kpi_value);
                                    echo "<th> $_kpi_value </th>";
                                }
                            }
                            echo "</tr>";
                        }
                    }
                    echo '</table>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
