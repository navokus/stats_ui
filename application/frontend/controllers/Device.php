<?php

/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 14/03/2016
 * Time: 23:29
 */

class Device extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
//		 $this->output->enable_profiler(TRUE);
        $this->load->model('game_model', 'game');
        $this->load->model('device_model', 'device');
        $this->load->library('form_validation');
        $this->load->library('util');
    }

    public function index()
    {
        $this->os();
    }

    public function brand()
    {
        $game_code = $this->session->userdata('current_game');

        $viewData ['body'] ['aGames'] = $this->game->listGames();
        $viewData ['body'] ['gameCode'] = $game_code;
        $date = $_SESSION['device']['date'];
        if ($this->input->post('kpidatepicker') != "") {
            $date = $this->input->post('kpidatepicker');
        } else if ($date == "") {
            $date = date('d/m/Y', strtotime('-1 days'));
        }
        $viewData ['body'] ['day'] ['kpidatepicker'] = $date;
        $_SESSION['device']['date'] = $date;

        $log_date = $this->util->user_date_to_db_date($date);

        $viewData = $this->get_data_brand($viewData, $log_date);

        $this->_template['content'] = $viewData ['body'] ['selection'] = $this->load->view('body_parts/selection/kpi_date', $viewData, TRUE);
        $this->_template['body']['aGames'] = $this->game->listGames();
        $html['html'] = $this->load->view("device/brand", $viewData, TRUE);
        $this->_template['content'] .= $this->load->view("device/index",$html, TRUE);
        $this->_template['body']['title'] = "Device Brand";
        $this->_template['body']['aGames'] = $this->game->listGames();
        $this->load->view('master_page', $this->_template);
    }

    public function get_data_brand($viewData, $log_date)
    {
        $chartData = array();
        $tableData = array();
        $gameCode = $viewData['body']['gameCode'];
        $this->util->parseUserInput($this->class_name, $viewData, $chartData, $tableData);

        $kpi_config = $this->util->get_os_kpi_id();

        $db_data = $this->device->get_brand_dbdata($gameCode, $kpi_config, $log_date);

        $timing_config = $this->util->get_timming_config();

        if ($db_data) {
            $os_data = $db_data['os'];
            $timing_arr = array("4", "5", "6");
            $type_arr = array("gr", "pu", "a","n","npu", "npu_gr");

            $title_arr = array(
                "a" => "Active user",
                "pu" => "Paying user",
                "gr" => "Revenue",
                "n" => "New user"
            );

            $data_sum = array();
            $data_detail = array();
            for ($i = 0; $i < count($timing_arr); $i++) {
                $timing = $timing_arr[$i];
                for ($j = 0; $j < count($type_arr); $j++) {
                    $type = $type_arr[$j];
                    $need_data = $os_data[$type . $timing_config[$timing]]; // a1

                    $data_json_arr = json_decode($need_data, true);
                    if ($type == "gr") {
                        // var_dump($data_json_arr);exit();
                    }
                    if (!isset($data_json_arr['other'])) {
                        $data_json_arr['other'] = '0';
                    }

                    $ios = 0;
                    $android = 0;
                    $other = 0;
                    foreach ($data_json_arr as $key => $value) {
                        if (strpos($key, "ios") !== FALSE) {
                            $ios += $value;
                        } else if (strpos($key, "android") !== FALSE) {
                            $android += $value;
                        } else {
                            $other += $value;
                        }
                    }
                    if ($type == "gr")
                        $data_detail[$timing] = $data_json_arr;
                    $data_sum[$timing][$type] = array("ios" => $ios, "android" => $android, "other" => $other);
                }
            }
            //echo "\n\n\n";
            //var_dump($data_detail);exit();
            $data_chart = array();
            $data_table = array();
            foreach ($data_sum as $_timming => $value) {
                foreach ($value as $_type => $v) {
                    $data_chart[$_timming][$_type]['data'] = array(
                        "Ios" => $v['ios'],
                        "Android" => $v['android']

                    );
                    //if($v['other']!=0){
                    //    $data_chart[$_timming][$_type]['data']['Other'] = $v['other'];
                    //}
                    $data_chart[$_timming][$_type]['total'] = $v['android'] + $v['ios'] + $v['other'];

                    $title = $title_arr[$_type];
                    $data_chart[$_timming][$_type]['title'] = $title;
                    $data_chart[$_timming][$_type]['categories'] = "'Android','IOS'";
                    //$data_chart[$_timming][$_type]['categories'] = "'Android', 'IOS', 'Other'";

                    $index = $timing_config[$_timming];

                    $data_table[$_type . $index]['android'] = $v['android'];
                    $data_table[$_type . $index]['ios'] = $v['ios'];
                    $data_table[$_type . $index]['other'] = $v['other'];

                }
            }

            //detail revenue by os
            foreach ($data_detail as $_timming => $value) {

                if ($value['other'] == 0)
                    unset($value['other']);
                arsort($value);

                $data_chart[$_timming]['gr_detail']['title'] = "Revenue by os version";
                $data_chart[$_timming]['gr_detail']['xAxisCategories'] = $this->util->get_data_string(array_keys($value), "'", true);
                //$data_chart[$_timming]['gr_detail']['id'] = "container_1";
                $data_chart[$_timming]['gr_detail']['yPrimaryAxisTitle'] = "Revenue";
                $data_chart[$_timming]['gr_detail']['data'] = array(
                    "gr" => array(
                        "name" => "Revenue",
                        "type" => "column",
                        "yAxis" => "0",
                        "data" => $this->util->get_data_string(array_values($value))
                    )
                );
            }

            $viewData['body']['gameCode'] = strtoupper($gameCode);
            $viewData['body']['title'] = "Device";
            $viewData['rawdata']['charts'] = $data_chart;
            $viewData['rawdata']['table']['data'] = $data_table;
            $viewData['rawdata']['table']['id'] = "kpi-device-report";
            $viewData['rawdata']['table']['title'] = "KPI Detail";
            $viewData['rawdata']['table']['header'] = array("kpi" => "KPI", "android" => "Android",
                "ios" => "Ios", "other" => "Other");

            $viewData['rawdata']['table']['exportTitle'] = $this->util->get_export_filename($gameCode,"device.os",$log_date,"",$timing);

            $tableData['export_file_name'] = $this->util->get_export_filename($gameCode,"device",$log_date);

        } else {
            $return['body']['nodata'] = 1;
            $_SESSION['device_nodata'] = 'true';
        }

        return $viewData;
    }

    public function os()
    {
        $game_code = $this->session->userdata('current_game');

        $viewData ['body'] ['aGames'] = $this->game->listGames();
        $viewData ['body'] ['gameCode'] = $game_code;
        $date = $_SESSION['device']['date'];
        if ($this->input->post('kpidatepicker') != "") {
            $date = $this->input->post('kpidatepicker');
        } else if ($date == "") {
            $date = date('d/m/Y', strtotime('-1 days'));
        }
        $viewData ['body'] ['day'] ['kpidatepicker'] = $date;
        $_SESSION['device']['date'] = $date;

        $log_date = $this->util->user_date_to_db_date($date);

        $viewData = $this->get_data_os($viewData, $log_date);

        $this->_template['content'] = $viewData ['body'] ['selection'] = $this->load->view('body_parts/selection/kpi_date', $viewData, TRUE);
        $this->_template['body']['aGames'] = $this->game->listGames();
        $html['html'] = $this->load->view("device/device", $viewData, TRUE);
        $this->_template['content'] .= $this->load->view("device/index",$html, TRUE);
        $this->_template['body']['title'] = "Device OS";
        $this->_template['body']['aGames'] = $this->game->listGames();
        $this->load->view('master_page', $this->_template);
    }



    public function get_data_os($viewData, $log_date)
    {
        $chartData = array();
        $tableData = array();
        $gameCode = $viewData['body']['gameCode'];
        $this->util->parseUserInput($this->class_name, $viewData, $chartData, $tableData);
        $all_kpi = $this->util->get_all_kpi();
        $t1 = $this->device->get_os_dbdata($gameCode, $all_kpi, $log_date);
        $t2 = $this->util->calculate_os_json_report_not_in_database($t1['os']);
        $t2 = $this->remove_kpi_not_dislay($t2);
        $t2 = $this->remove_kpi_zero($t2);
        $db_data['os'] = $t2;
        $timing_config = $this->util->get_timming_config();

        if (count($db_data['os']) > 0) {
            $os_data = $db_data['os'];
            $timing_arr = array("4", "5", "6");
            $type_arr = array("gr", "pu", "a","n","npu", "npu_gr");

            $title_arr = array(
                "a" => "Active user",
                "pu" => "Paying user",
                "gr" => "Revenue",
                "n" => "New user"
            );

            $data_sum = array();
            $data_detail = array();
            for ($i = 0; $i < count($timing_arr); $i++) {
                $timing = $timing_arr[$i];
                for ($j = 0; $j < count($type_arr); $j++) {
                    $type = $type_arr[$j];
                    $need_data = $os_data[$type . $timing_config[$timing]]; // a1

                    $data_json_arr = json_decode($need_data, true);
                    if ($type == "gr") {
                        // var_dump($data_json_arr);exit();
                    }
                    if (!isset($data_json_arr['other'])) {
                        $data_json_arr['other'] = '0';
                    }

                    $ios = 0;
                    $android = 0;
                    $other = 0;
                    foreach ($data_json_arr as $key => $value) {
                        if (strpos($key, "ios") !== FALSE) {
                            $ios += $value;
                        } else if (strpos($key, "android") !== FALSE) {
                            $android += $value;
                        } else {
                            $other += $value;
                        }
                    }
                    if ($type == "gr")
                        $data_detail[$timing] = $data_json_arr;
                    $data_sum[$timing][$type] = array("ios" => $ios, "android" => $android, "other" => $other);
                }
            }
            $data_chart = array();
            foreach ($data_sum as $_timming => $value) {
                foreach ($value as $_type => $v) {
                    $data_chart[$_timming][$_type]['data'] = array(
                        "Ios" => $v['ios'],
                        "Android" => $v['android']

                    );
                    $data_chart[$_timming][$_type]['total'] = $v['android'] + $v['ios'] + $v['other'];
                    $title = $title_arr[$_type];
                    $data_chart[$_timming][$_type]['title'] = $title;
                    $data_chart[$_timming][$_type]['categories'] = "'Android','IOS'";
                    //$data_chart[$_timming][$_type]['categories'] = "'Android', 'IOS', 'Other'";

                    $index = $timing_config[$_timming];

                    //$data_table[$_type . $index]['android'] = $v['android'];
                    //$data_table[$_type . $index]['ios'] = $v['ios'];
                    //$data_table[$_type . $index]['other'] = $v['other'];

                }
            }
            $data_table = array();
            foreach ($db_data['os'] as $kpi_code => $json_data ) {
                $v= json_decode($json_data,true);
                $data_table[$kpi_code]['android'] = $v['android'];
                $data_table[$kpi_code]['ios'] = $v['ios'];
                $data_table[$kpi_code]['other'] = $v['other'];
            }
            $data_table = $this->sort_data_by_kpi_id($data_table);
            //detail revenue by os
            foreach ($data_detail as $_timming => $value) {

                if ($value['other'] == 0)
                    unset($value['other']);
                arsort($value);

                $data_chart[$_timming]['gr_detail']['title'] = "Revenue by os version";
                $data_chart[$_timming]['gr_detail']['xAxisCategories'] = $this->util->get_data_string(array_keys($value), "'", true);
                //$data_chart[$_timming]['gr_detail']['id'] = "container_1";
                $data_chart[$_timming]['gr_detail']['yPrimaryAxisTitle'] = "Revenue";
                $data_chart[$_timming]['gr_detail']['data'] = array(
                    "gr" => array(
                        "name" => "Revenue",
                        "type" => "column",
                        "yAxis" => "0",
                        "data" => $this->util->get_data_string(array_values($value))
                    )
                );
            }

            $viewData['body']['gameCode'] = strtoupper($gameCode);
            $viewData['body']['title'] = "Device";
            $viewData['rawdata']['charts'] = $data_chart;
            $viewData['rawdata']['table']['data'] = $data_table;
            $viewData['rawdata']['table']['id'] = "kpi-device-report";
            $viewData['rawdata']['table']['title'] = "KPI Detail";
            $viewData['rawdata']['table']['header'] = array("kpi" => "KPI", "android" => "Android",
                "ios" => "Ios", "other" => "Other");

            $viewData['rawdata']['table']['exportTitle'] = $this->util->get_export_filename($gameCode,"device.os",$log_date,"",$timing);

            $tableData['export_file_name'] = $this->util->get_export_filename($gameCode,"device",$log_date);

        } else {
            $return['body']['nodata'] = 1;
            $_SESSION['device_nodata'] = 'true';
        }

        return $viewData;
    }

    private function remove_kpi_not_dislay($db_data){
        $kpi_remove = $this->util->get_kpi_not_display();
        $timing_list = $this->util->get_timming_config();
        foreach($kpi_remove as $kpi_code){
            foreach ($timing_list as $timing) {
                if(isset($db_data[$kpi_code . $timing])){
                    unset($db_data[$kpi_code . $timing]);
                }
            }
        }
        return $db_data;
    }

    private function remove_kpi_zero($db_data){
        foreach($db_data as $kpi_code => $json_data){
            $data = array_values(json_decode($json_data,true));
            if(array_sum($data) == 0){
                unset($db_data[$kpi_code]);
            }
        }
        return $db_data;
    }
    private function sort_data_by_kpi_id($data)
    {
        $all_kpi_code = array_keys($data);
        $all_kpi_id = $this->device->getKpiIDs(null, $all_kpi_code);
        ksort($all_kpi_id);
        $new = array();
        foreach ($all_kpi_id as $kpi_id => $kpi_code) {
            $new[$kpi_code] = $data[$kpi_code];
        }
        return $new;
    }

    public function index1()
    {
        $return['body']['aGames'] = $this->game->listGames();
        $return['body']['optionsWeek'] = $this->util->listOptionsWeek();
        $return['body']['optionsMonth'] = $this->util->listOptionsMonth();

        if ($this->input->post('options')) {
            $_SESSION['device']['post'] = $_POST;
            redirect('Device');
        }

        if ($_SESSION['device']['post']) {
            $return['post'] = $_SESSION['device']['post'];
        } else {
            $return['post']['options'] = 4;
            $return['post']['day'][1] = date('d/m/Y', strtotime("-1 days"));
            $return['post']['day'][2] = date('d/m/Y', strtotime("-10 days"));
        }

        if ($_SESSION['device']) {
            $userInput = $_SESSION['device']['post'];
            $return['body'] = array_merge($return['body'], $userInput);
            if (isset($userInput["daterangepicker"]) && $userInput["daterangepicker"] != "") {
                $tmp = explode("-", $userInput["daterangepicker"]);
                $return['body']['day'][1] = trim($tmp[1]);
                $return['body']['day'][2] = trim($tmp[0]);
            } else {
                $return['body']['day']['2'] = $userInput["datesinglepicker"];
            }
        } else {
            $return['body']['options'] = 4;
            //set default day
            $return['body']['day'][1] = date('d/m/Y', strtotime("-1 days"));
            $return['body']['day'][2] = date('d/m/Y', strtotime("-31 days"));
            //set default week
            $t_list_week = array_keys($return['body']['optionsWeek']);
            $return['body']['week'][1] = $t_list_week[0];
            $return['body']['week'][2] = $t_list_week[12];
            //set default month
            $t_list_month = array_keys($return['body']['optionsMonth']);
            $return['body']['month'][1] = $t_list_month[0];
            $return['body']['month'][2] = $t_list_month[12];
        }
        $return['body']['day']['default_single_date'] = $return['body']['day'][2];
        $return['post']['gameCode'] = $this->session->userdata('default_game');
        $gameCode = $return['post']['gameCode'];


        switch ($return['post']['options']) {
            case '5': // week
                list($day, $month, $year) = explode('/', $return['body']['week'][2]);
                $fromDate = $year . '-' . $month . '-' . $day;
                $subTitle = 'Tuần ' . date('W/Y', strtotime($return['body']['week']['2']));
                $return['time'] = 'Tuần';
                break;
            case '6': // month
                list($day, $month, $year) = explode('/', $return['body']['month'][2]);
                $fromDate = $year . '-' . $month . '-' . $day;
                $subTitle = 'Tháng ' . date('m/Y', strtotime($return['body']['month']['2']));
                $return['time'] = 'Tháng';
                break;
            case '4': // day
            default:
                list($day, $month, $year) = explode('/', $return['body']['day'][2]);
                $fromDate = $year . '-' . $month . '-' . $day;
                $subTitle = 'Ngày ' . $return['body']['day'][2];
                $return['time'] = 'Ngày';
                break;
        }
        $db_data = $this->device->getDataDrawChart($gameCode, $return['post']['options'], $fromDate);

        $return['subTitle'] = $subTitle;
        $title_array = array(
            "nwk" => "network type",
            "tel" => "carrier",
            "os" => "os",
            "dty" => "device",
            "sc" => "resolution",
            "android_ios" => "TỔNG QUAN ANDROID VS IOS"
        );
        $data_chart = array();
        $OTHER_LIMIT = 50;
        $is_small_array = array();
        if ($db_data) {
            $db_data = $this->re_organize($db_data); //active len dau` array, de dk if(data_type_2=="active) xay ra dau tien
            foreach ($db_data as $value) {
                $data_type_1 = $value['data_type_1'];   //nwk, tel...
                $data_type_2 = $value['data_type_2'];   // paying, active, new
                $data_json_string = $value['data'];

                $title = "BIỂU ĐỒ " . (isset($title_array[$data_type_1]) ? $title_array[$data_type_1] : $data_type_1) . " GAME " . $gameCode;
                $title = strtoupper($title);
                $data_chart[$data_type_1][$data_type_2]['title'] = $title;
                $data_json_job = json_decode($data_json_string, true);
                if (!isset($data_json_job['other'])) {
                    $data_json_job['other'] = '0';
                }

                $tmp = array();
                if ($data_type_2 == "active") {
                    $key_set = array_keys($data_json_job);
                    for ($i = 0; $i < count($key_set); $i++) {
                        $metric_key = $key_set[$i];   // android4.5, android6.1 .....
                        if (isset($data_json_job[$metric_key])) {
                            $tmp[$metric_key] = $data_json_job[$metric_key];
                            if ($data_json_job[$metric_key] < $OTHER_LIMIT && $metric_key != "other") {
                                $is_small_array[$data_type_1][] = $metric_key;
                            }
                        } else {
                            $tmp[$metric_key] = 0;
                        }
                    }
                    arsort($tmp);
                    $active_key_set_1 = array_keys($tmp); // ketset when not remove metric has value less than $OTHER_LIMIT
                } else {
                    // paying va new register dua vao active
                    $key_set = $data_chart[$data_type_1]['active']['keyset'];

                    for ($i = 0; $i < count($key_set); $i++) {
                        $metric_key = $key_set[$i];   // android4.5, android6.1 .....
                        if (isset($data_json_job[$metric_key])) {
                            $tmp[$metric_key] = $data_json_job[$metric_key];
                        } else {
                            $tmp[$metric_key] = 0;
                        }
                    }
                }

                //remove if value less than $OTHER_LIMIT
                foreach ($tmp as $tmp_key => $tmp_value) { // android5.4 => 54, android 5.6 => 12
                    if ($tmp_key == "other") continue;
                    if (in_array($tmp_key, $is_small_array[$data_type_1])) {
                        $tmp['other'] += $tmp_value;
                        unset($tmp[$tmp_key]);
                    }
                }

                // get keysets for paying, newregister..
                if ($data_type_2 == "active") {
                    arsort($tmp);
                    $active_key_set_2 = array_keys($tmp); // ketset when remove metric has value less than $OTHER_LIMIT
                    $other_position_1 = array_search("other", $active_key_set_1, true);
                    $other_position_2 = array_search("other", $active_key_set_2, true);
                    unset($active_key_set_1[$other_position_1]);
                    array_splice($active_key_set_1, $other_position_2, 0, 'other'); // lay vi tri cua other trong keyset2 = x, sau do push other vao vi tri x cua keyset 1
                    $data_chart[$data_type_1][$data_type_2]['keyset'] = $active_key_set_1;
                }

                $data_chart[$data_type_1][$data_type_2]['chart_width'] = count($tmp) * 50;
                $data_chart[$data_type_1][$data_type_2]['data'] = implode(",", array_values($tmp));
                $data_chart[$data_type_1][$data_type_2]['total'] = array_sum($tmp);
                $data_chart[$data_type_1][$data_type_2]['categories'] = $this->util->get_categories(array_keys($tmp));

            }
            // more chart for summary android vs ios
            $__os_active = explode(",", $data_chart['os']['active']['data']);
            $__os_paying = explode(",", $data_chart['os']['paying']['data']);
            $__os_categories = explode(",", $data_chart['os']['active']['categories']);

            $total_android_device = 0;
            $total_android_paying = 0;
            $total_ios_device = 0;
            $total_ios_paying = 0;
            $total_other_device = 0;
            $total_other_paying = 0;
            for ($k = 0; $k < count($__os_categories); $k++) {
                $__metric_key = strtolower(str_replace("'", "", $__os_categories[$k]));
                if ($__metric_key != "") {
                    //echo $__metric_key . "\n";
                    if (strpos($__metric_key, 'android') !== false) {
                        $total_android_device += $__os_active[$k];
                        $total_android_paying += $__os_paying[$k];
                    } else if (strpos($__metric_key, 'ios') !== false) {
                        $total_ios_device += $__os_active[$k];
                        $total_ios_paying += $__os_paying[$k];
                    } else {
                        $total_other_device += $__os_active[$k];
                        $total_other_paying += $__os_paying[$k];
                    }
                }
            }
            /*
             *
             *
             *
             * 001 - device
             * 002 - abc
             * 003 - xyz
             *
             * contra 001, 003
             * tlbb 001, 002
             */
            $data_chart['android_ios']['active']['chart_width'] = 5 * 50;
            $data_chart['android_ios']['active']['data'] = $total_android_device . "," . $total_ios_device . "," . $total_other_device;
            $data_chart['android_ios']['active']['total'] = $total_android_device + $total_ios_device + $total_other_device;
            $data_chart['android_ios']['active']['categories'] = "'Android', 'IOS', 'Other'";

            $title = "SO SÁNH TỶ LỆ HỆ ĐIỀU HÀNH GAME " . $gameCode;
            $title = strtoupper($title);
            $data_chart['android_ios']['active']['title'] = $title;

            $data_chart['android_ios']['paying']['data'] = $total_android_paying . "," . $total_ios_paying . "," . $total_other_paying;
            $data_chart['android_ios']['paying']['total'] = $total_android_paying + $total_ios_paying + $total_other_paying;
        } else {
            $return['body']['nodata'] = 1;
        }

        $return['gameCode'] = strtoupper($gameCode);
        $return['data'] = $data_chart;

        $this->_template['content'] = $this->load->view('device/index', $return, TRUE);
        $this->load->view('master_page', $this->_template);
    }

    private function re_organize($db_data)
    {
        $first_arr = array();
        foreach ($db_data as $key => $value) {
            $data_type_2 = $value['data_type_2'];   // paying, active, new
            if ($data_type_2 == "active") {
                $first_arr[] = $value;
                unset($db_data[$key]);
            }
        }
        foreach ($first_arr as $first) {
            array_unshift($db_data, $first);
        }
        return $db_data;
    }

    private function get_metric($db_data)
    {
        $return = array();
        foreach ($db_data as $value) {
            $data_json_string = $value['data'];
            $data_type_1 = $value['data_type_1'];
            $data_type_2 = $value['data_type_2'];
            if ($data_type_2 == "active") {
                $data_json_obj = json_decode($data_json_string, true);
                $tmp = array();
                foreach ($data_json_obj as $key => $value) {
                    $tmp[] = $key;
                }
                $return[$data_type_1] = $tmp;
            }
        }
        return $return;
    }
}