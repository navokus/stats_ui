<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 01/09/2016
 * Time: 11:18
 */

class Operation extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('game_model', 'game');
        $this->load->model('operation_model', 'operation');
        $this->load->library('form_validation');
        $this->load->library('util');
    }

    public function index()
    {
        $this->overview();
    }

    public function monitor_by_server(){

    }

    public function overview()
    {
        $gamefilter="issuegame";
        if ($this->input->post('kpidatepicker') != "") {
            $date = $this->input->post('kpidatepicker');
            $gamefilter = $this->input->post("gamefilter");
        } else  {
            $date = date('d/m/Y', strtotime('-1 days'));
        }
        $viewData ['body'] ['day'] ['kpidatepicker'] = $date;
        $date = $this->util->user_date_to_db_date($date);

        $key_kpi_ids = array("gr1" => "Revenue", "a1" => "Active user", "n1" => "New user", "npu1" => "First charge", "acu1" => "ACU");
        $day_number = 7;
        $percent_config = array(
            "gr1" => array(
                '100000000' => "30",
                '50000000' => "50",
                '20000000' => "60",
                '10000000' => "70",
                '5000000' => "80",
                '1' => "1000"
            ),
            "a1" => array(
                '100000' => "10",
                '50000' => "12",
                '20000' => "14",
                '10000' => "16",
                '5000' => "20",
                '1' => "1000"
            ),
            "n1" => array(
                '10000' => "10",
                '5000' => "12",
                '2000' => "14",
                '1000' => "16",
                '500' => "20",
                '1' => "1000"
            ),
            "npu1" => array(
                '400' => "10",
                '300' => "12",
                '200' => "14",
                '100' => "16",
                '50' => "20",
                '1' => "1000"
            ),"acu1" => array(
                '10000' => "10",
                '5000' => "12",
                '2000' => "14",
                '1000' => "16",
                '500' => "20",
                '1' => "1000"
            )
        );

        $day_arr = array($date);
        for ($i = 1; $i <= $day_number; $i++) {
            $t = date("Y-m-d", strtotime($date) - $i * 24 * 60 * 60);
            $day_arr[] = $t;
        }

        $all_game = $this->operation->game_info;
        $active_game = array();
        foreach ($all_game as $game_code => $game_info) {
            if ($game_info['Status'] == 1) {
                $active_game[$game_code] = $game_info;
            }
        }

        $db_data = $this->operation->compare_key_kpi($day_arr, $key_kpi_ids, array_keys($active_game));

        $viewData['overview']['db_data'] = $db_data;
        $viewData['overview']['config'] = array("day_number" => $day_number,
            "kpi_config" => $key_kpi_ids,
            "day_arr" => $day_arr,
            "percent_config" => $percent_config);
        $viewData['overview']['game_filter'] = $gamefilter;
        $this->_template['content'] = $this->load->view("operation/overview", $viewData, TRUE);
        $this->_template['body']['title'] = "Operation";
        $this->_template['body']['gameCode'] = "Operation";
        $this->load->view('master_page', $this->_template);

        mkdir("/tmp/kpitool/");
        $today = date("Y-m-d");
        file_put_contents("/tmp/kpitool/" . $today . "_overview", "seen");
    }


    public function compare_kpi_by_source()
    {
        /*
        if ($this->input->post('daterangepicker') != "") {
            $date_string = $this->input->post('daterangepicker');
        } else {
            $date_string = date('d/m/Y', strtotime("-2 days")) . " - " . date('d/m/Y', strtotime("-1 days"));
        }
        $viewData['body']['day']['default_range_date'] = $date_string;

        $date_arr = $this->util->get_db_date_from_range_date($date_string);
        $date_1 = $date_arr[0];
        $date_2 = $date_arr[1];

        $day_arr = $this->util->getDaysEveryDay($date_1, $date_2);
*/


        $viewData['body']['aGames'] = $this->game->listGames();
        $gameCode = $this->session->userdata('current_game');


        if ($this->input->post('daterangepicker') != "") {
            $date_string = $this->input->post('daterangepicker');
        } else  {
            $date_string = date('d/m/Y', strtotime("-30 days")) . " - " . date('d/m/Y', strtotime("-1 days"));
        }
        $viewData['body']['day']['default_range_date'] = $date_string;

        $date_arr = $this->util->get_db_date_from_range_date($date_string);
        $date_1 = $date_arr[0];
        $date_2 = $date_arr[1];

        $day_arr = $this->util->getDaysEveryDay($date_1, $date_2);

        $full_source_arr = array("ingame", "sdk", "payment");
        $source_arr = array();
        for($i=0;$i<count($full_source_arr);$i++){

            if($this->input->post($full_source_arr[$i]) == $full_source_arr[$i]){
                $source_arr[] = $full_source_arr[$i];
            }
        }

        $full_kpi_id_arr = array("a1", "n1","gr1","pu1");
        $default_kpi_arr = array("a1", "n1","gr1","pu1");
        $kpi_id_arr = array();
        for($i=0;$i<count($full_kpi_id_arr);$i++){
            if($this->input->post($full_kpi_id_arr[$i]) == $full_kpi_id_arr[$i]){
                $kpi_id_arr[] = $full_kpi_id_arr[$i];
            }
        }

        $game_arr = array($gameCode);
        if(count($kpi_id_arr)==0){
            $kpi_id_arr = $default_kpi_arr;
        }

        if(count($source_arr) != 0 && count($kpi_id_arr) != 0){
            $db_data = $this->operation->compare_kpi_by_source($day_arr, $kpi_id_arr,$source_arr, $game_arr);
            $data = array();
            for($i=0;$i<count($db_data);$i++){
                $_kpi_code = $db_data[$i]['kpi_name'];
                $_source = $db_data[$i]['source'];
                $_kpi_value = $db_data[$i]['kpi_value'];
                $_report_date = $db_data[$i]['report_date'];

                if($_source=="voss" && strpos($_kpi_code, "nrr") !== false){
                    $_kpi_value = $_kpi_value * 100;
                }
                $data[$_report_date][$_kpi_code][$_source] = $_kpi_value;
            }

            krsort($data);
            $viewData['data'] = $data;
            $viewData['action'] = true;
        }
        $viewData['source_arr'] = $source_arr;
        $viewData['day_arr'] = $day_arr;
        $viewData['kpi_arr'] = $kpi_id_arr;

        $viewData['title'] = "KPI compare by source";
        $viewData['full_source_arr'] = $full_source_arr;
        $viewData['full_kpi_arr'] = $full_kpi_id_arr;

        $viewData['id'] = "kpi-report-export";
        $viewData['exportTitle'] = $this->util->get_export_filename($gameCode, "Compare.export", $date_1, $date_2);

        $this->_template['content'] = $this->load->view("operation/kpi_compare_by_source", $viewData, TRUE);
        $this->_template['body']['title'] = "Operation";
        $this->_template['body']['gameCode'] = "Operation";
        $this->load->view('master_page', $this->_template);

    }

    public function kpi_migration_status(){
        if ($this->input->post('daterangepicker') != "") {
            $date_string = $this->input->post('daterangepicker');
        } else  {
            $date_string = date('d/m/Y', strtotime("-2 days")) . " - " . date('d/m/Y', strtotime("-1 days"));
        }
        $viewData['body']['day']['default_range_date'] = $date_string;

        $date_arr = $this->util->get_db_date_from_range_date($date_string);
        $date_1 = $date_arr[0];
        $date_2 = $date_arr[1];

        $day_arr = $this->util->getDaysEveryDay($date_1, $date_2);

        $kpi_id_arr = array("a1","n1","pu1","gr1",
            "npu1","npu_gr1","nnpu1","nnpu_gr1","acu1","pcu1",
            "prr1","nrr1");

        $kpi_type_config = array(
            "game_kpi" => "Game KPI",
            "server_kpi" => "Server KPI",
            "channel_kpi" => "Channel KPI",
            "os_kpi" => "OS KPI",
            "package_kpi" => "Package KPI"
        );

        $header[] = "GameCode";
        $header[] = "GameName";
        for($i=0;$i<count($kpi_id_arr);$i++) {
            $_kpi_code = $kpi_id_arr[$i];
            $header[] = strtoupper($_kpi_code);
        }
        foreach($kpi_type_config as $kpi_type => $description){
            $db_data = $this->operation->kpi_migration_status($day_arr, $kpi_id_arr, $kpi_type);
            $data = array();
            $data_table = array();
            for($i=0;$i<count($db_data);$i++){
                $_game_code = $db_data[$i]['GameCode'];
                $_kpi_code = $db_data[$i]['kpi_name'];
                $_game_name = $db_data[$i]['GameName'];

                $_source = $db_data[$i]['source'];
                if($_game_code == "") continue;
                $data[$_game_code]['game_name'] = $_game_name;
                $data[$_game_code]['data'][$_kpi_code] = $_source;
            }

            foreach($data as $_game_code => $detail){
                $t2['game_code'] = strtoupper($_game_code);
                $t2['game_name']  = $detail['game_name'];

                for($i=0;$i<count($kpi_id_arr);$i++){
                    $_kpi_code = $kpi_id_arr[$i];
                    $t1="";
                    if(isset($detail['data'][$_kpi_code])){
                        $t1 = strtoupper($detail['data'][$_kpi_code]);
                    }
                    $t2[$_kpi_code] = $t1;
                }
                $data_table[] = $t2;
            }

            $viewData['data_table'][$kpi_type]['data'] = $data_table;
            $viewData['data_table'][$kpi_type]['header'] = $header;
            $viewData['data_table'][$kpi_type]['btn_download'] = "false";
            $viewData['data_table'][$kpi_type]['id'] = "migration_status_" . $kpi_type;
            $viewData['data_table'][$kpi_type]['exportTitle'] = "exportTitle";
            $viewData['data_table'][$kpi_type]['title'] = "Migration status";
        }

        $viewData['tab_content'] = $kpi_type_config;

        $this->_template['content'] = $this->load->view("operation/migration_status", $viewData, TRUE);
        $this->_template['body']['title'] = "Operation";
        $this->_template['body']['gameCode'] = "Operation";
        $this->load->view('master_page', $this->_template);


    }
    public function view_statistics(){
        if ($this->input->post('kpidatepicker') != "") {
            $date = $this->input->post('kpidatepicker');
        } else  {
            $date = date('d/m/Y');
        }
        $view_data ['body'] ['day'] ['kpidatepicker'] = $date;
        $date = $this->util->user_date_to_db_date($date);

        $metric_arr = array("domain" => "User Access","report_uri" => "Report View","game_code" => "Game");
        $metric = array_keys($metric_arr);

        $day_ago_7 = date("Y-m-d", strtotime($date) - 7 * 24 * 3600);
        $day_ago_30 = date("Y-m-d", strtotime($date) - 30 * 24 * 3600);
        $day_ago_90 = date("Y-m-d", strtotime($date) - 90 * 24 * 3600);

        $day_arr = array(
            "selected" => array($date,$date),
            "day7ago" => array($day_ago_7,$date),
            "day30ago" => array($day_ago_30,$date)
        );

        $default_limit = 10;
        $limit_arr = array(
            "report_uri" => 100,
            "game_code" => $default_limit,
            "domain" => $default_limit,
        );

        $title_arr = array(
            "domain" => "Top " .$limit_arr['domain'] . " " . $metric_arr['domain'],
            "report_uri" => "Top " . $metric_arr['report_uri'],
            "game_code" => "Top " . $limit_arr['game_code']  . " " . $metric_arr['game_code'],

        );

        $statistic = array('behavior/odd',
            'dashboard',
            'dashboard2',
            'hourlyreport',
            'kpi/daily',
            'kpi/export',
            'kpi/hourly',
            'kpi/revenue',
            'kpi/user',
            'mobile/device-os',
            'mobile/export',
            'mobile/login-channel',
            'mobile/login-channel-detail',
            'mobile/package-install',
            'server/detail',
            'server/top',
            'behavior/top-user'
        );
        $db_data = $this->operation->view_statistics($day_arr,$metric);
        $report_config = $this->operation->report_config;

        $sort_data = array();
        foreach($day_arr as $date_key => $date_value){
            for($i=0;$i<count($metric);$i++){
                $t1 = $db_data[$date_key][$metric[$i]];
                arsort($t1);
                $l=0;
                $limit =  $limit_arr[$metric[$i]];

                if($metric[$i] == "report_uri"){
                    foreach($t1 as $_k => $_v){
                        unset($t1[$_k]);
                        //if(in_array(($_k), $statistic)){
                            $report_name = $this->get_report_name($report_config, $_k);
                            $t1[$report_name] = $_v;
                        //}
                    }
                }else{
                    foreach($t1 as $_k => $_v){
                        unset($t1[$_k]);
                        $t1[strtoupper($_k)] = $_v;
                    }
                }

                foreach($t1 as $_k => $_v){
                    if($l>=$limit){
                        break;
                    }
                    $sort_data[$date_key][$metric[$i]][$_k] = $_v;
                    $l++;
                }
            }
        }

        $data_chart = array();
        foreach($sort_data as $t => $value){
            foreach($value as $metric_key => $metric_value){
                $data_chart[$t][$metric_key]['title'] = $title_arr[$metric_key];
                $data_chart[$t][$metric_key]['categories'] = $this->util->get_data_string(array_keys($metric_value), "'", true);
                $data_chart[$t][$metric_key]['yPrimaryAxisTitle'] = $metric_key;
                $data_chart[$t][$metric_key]['data'] = array(
                    $metric_key => array(
                        "name" => $metric_arr[$metric_key],
                        "type" => "bar",
                        "yAxis" => "0",
                        "data" => $this->util->get_data_string(array_values($metric_value))
                    )
                );
            }
        }
        $view_data['data_chart'] = $data_chart;

        //// view by day
        $db_data_trend = $this->operation->view_trend($day_ago_90, $date);
        $day_arr_trend = $this->util->getDaysEveryDay($day_ago_90,$date);

        $data_trend=array();
        for($i=0;$i<count($day_arr_trend);$i++){
            $ld = $day_arr_trend[$i];
            $data_trend[$ld] = isset($db_data_trend[$ld]) ? $db_data_trend[$ld] : 0;
        }


        $line_chart['title'] = "Total view last 90 days";
        $line_chart['xAxisCategories'] = $this->util->get_data_string(array_keys($data_trend), "'", true);
        $line_chart['id'] = "container_line_chart";
        $line_chart['yPrimaryAxisTitle'] = "Times";
        $line_chart['ySecondaryAxisTitle'] = "";
        $line_chart['data'] = array(
            "gr" => array(
                "name" => 'Total view last 90 day',
                "type" => "spline",
                "yAxis" => "1",
                "data" => $this->util->get_data_string(array_values($data_trend))
            )
        );

        $view_data['line_chart'] = $line_chart;
        $this->_template['content'] .= $this->load->view("operation/view_statistics", $view_data, TRUE);
        $this->_template['body']['title'] = "Operation";
        $this->_template['body']['gameCode'] = "Operation";
        $this->load->view('master_page', $this->_template);

    }

    private function get_report_name($report_config, $report_uri){
        foreach($report_config as $report_id => $report_detail){
            if($report_uri == "mobile/export"){
                return "Mobile Export";
            }
            if($report_uri == "kpi/export"){
                return "Kpi Export";
            }

            if($report_detail['url'] == $report_uri){
                return  $report_detail['report_name'];
            }
        }
        return ucfirst($report_uri);
    }
}