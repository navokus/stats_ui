<?php

$data = $db_data['data'];
$data_source = $db_data['data_source'];

$day_number = $config['day_number'];
$kpi_config = $config['kpi_config'];
$day_arr = $config['day_arr'];
$game_list = (array_keys($data));

$percent_config = $config['percent_config'];

$header['key'] = "Key";
$header[$day_arr[0]] = $day_arr[0];
$header[$day_arr[1]] = $day_arr[1];
$header['1daycompare_nag'] = "-1 day compare";
for($i=2;$i<count($day_arr);$i++){
    $header[$day_arr[$i]] = $day_arr[$i];
}
$header['source'] = "Source";

$return = array();
foreach($kpi_config as $_kpi_code => $kpi_description){
    $html_first = array();
    $html_after = array();
    $html_priority = array();
    for($i=0;$i<count($game_list);$i++){
        $_game_code = $game_list[$i];
        //foreach($data[$_game_code] as $_kpi_code => $_kpi_values){
            $value_check = isset($data[$_game_code][$_kpi_code][$day_arr[0]]) ? $data[$_game_code][$_kpi_code][$day_arr[0]] : 0;
            $total=0;
            $html_t1 = array();
            for($j=1;$j<count($day_arr);$j++){
                $_report_date = $day_arr[$j];
                $value_by_day = isset($data[$_game_code][$_kpi_code][$_report_date]) ? $data[$_game_code][$_kpi_code][$_report_date] : 0;

                $total += $value_by_day;
                if($j!=1){
                    $html_t1[] = ud_format_number($value_by_day);
                }
            }

            $value_1_day_ago = isset($data[$_game_code][$_kpi_code][$day_arr[1]]) ? $data[$_game_code][$_kpi_code][$day_arr[1]] : 0;

            if($value_1_day_ago==0){
                $percent_1=0;

            }else {
                $percent_1 = (($value_check - $value_1_day_ago) / $value_1_day_ago) * 100;
                $percent_1 = number_format($percent_1, 2);
                if ($percent_1 > 0) $percent_1 = "+" . $percent_1;
            }
            $cl = $value_check - $value_1_day_ago;
            $alert = false;
            $priority = false;

            if((abs($percent_1) >= 99 and abs($percent_1) <= 101) || ($percent_1==0) || (abs($percent_1) > 1 and abs($percent_1)%50==0) ){
                $alert = true;
                $priority = true;
            }else{

                $percent_alert = get_percent_from_total($percent_config,$_kpi_code,$value_check);
                if(abs($percent_1) >= $percent_alert){
                    $alert = true;
                }
            }

            $html_t2 = array();
            if($alert==true){
                $html_t2['gc'] = strtoupper($_game_code);
                $html_t2['vc'] = ud_format_number($value_check);
                $html_t2['v1dg'] = ud_format_number($value_1_day_ago);
                $html_t2['cp'] = $percent_1 . "% (" . ud_format_number($cl) . ")" ;
                for($p = 0;$p <count($html_t1); $p++){
                    $html_t2['d'.$p] = $html_t1[$p];
                }
                $html_t2['dts'] = $data_source[$_game_code][$_kpi_code];

                if($priority==true){
                    $html_priority[] = $html_t2;
                }else{
                    if($percent_1>=0){
                        $html_first[] = $html_t2;
                    }else{
                        $html_after[] = $html_t2;
                    }
                }
            }
            //break;
        //}

    }

    $data_table = array_merge($html_priority, $html_first, $html_after);

    $view_data['data'] = $data_table;
    $view_data['header'] = $header;
    $view_data['title'] = "Overview last " . $day_number . " days for " . $kpi_description;
    $view_data['id'] = "container_" . $_kpi_code;
    $view_data['exportTitle'] = "exportTitle";
    //$html = $this->load->view("body_parts/table/common_table", $view_data, TRUE);

    $return[$_kpi_code] = $view_data;
}
var_dump($return);
return $return;

function ud_format_number($number){
    $number = number_format($number);
    return $number;
}

function get_percent_from_total($percent_config,$_kpi_code,$total){
    foreach($percent_config[$_kpi_code] as $key => $value){
        $key_number = intval($key);
        if($total > $key_number){
            return $value;
        }
    }
    return 2000;
}
