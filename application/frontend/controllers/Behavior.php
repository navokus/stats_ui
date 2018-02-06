<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Behavior extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        // $this->output->enable_profiler(TRUE);
        //$this->load->model('game_model', 'game');
        $this->load->model('behavior_model', 'behavior');
        $this->load->library('form_validation');
        $this->load->library('util');
    }

    public function index()
    {

    }

    public function oneDayDetail()
    {
        $game_code = $this->session->userdata('current_game');
        $gameList = $this->game->listGames();

        $viewData ['body'] ['aGames'] = array_values($gameList);
        $viewData ['body'] ['gameCode'] = $game_code;
        $date_string = $_SESSION['behavior']['date_string'];
        if ($this->input->post('daterangepicker') != "") {
            $date_string = $this->input->post('daterangepicker');
        } else if ($date_string == "") {
            $date_string = date('d/m/Y', strtotime("-1 days")) . " - " . date('d/m/Y');
        }
        $viewData['body']['day']['default_range_date'] = $date_string;
        $_SESSION['behavior']['date_string'] = $date_string;

        $date_arr = $this->util->get_db_date_from_range_date($date_string);

        $date_1 = $date_arr[0];
        $date_2 = $date_arr[1];

        $server_id="";
        $server_list = $this->behavior->get_server_list_from_server_kpi_hourly(array("a1"), $game_code, array($date_1, $date_2));
        if(count($server_list) > 0){
            if($this->input->post("server_id") && $this->input->post("server_id") != "not_select" && $game_code == $this->input->post("game_before") ){
                $server_id = $this->input->post("server_id");
                $this->session->set_userdata("server_id", $server_id);
            }else{
                $this->session->set_userdata("server_id", "");
            }
        }
        $kpi_ids_config = array(
            "16001" => array("kpi_code" => "gr1", "kpi_id" => "16001", "description" => "Revenue", "tab_id" => "type1"),
            "15001" => array("kpi_code" => "pu1", "kpi_id" => "15001", "description" => "Paying user", "tab_id" => "type1"),
            "11001" => array("kpi_code" => "n1", "kpi_id" => "11001", "description" => "Register user", "tab_id" => "type1"),
            "10001" => array("kpi_code" => "a1", "kpi_id" => "10001", "description" => "Active user", "tab_id" => "type1"),
        );

        $tab_config = array("type1" => "type1", "type2" => "type2");

        $viewData['body']['charts'] = "";
        $no_data = true;
        if ($game_code != "" && $date_1 != "" && $date_2 != "") {
            foreach ($kpi_ids_config as $kpi_id => $kpi_detail) {
                $tab_id = $kpi_detail['tab_id'];
                if ($kpi_id == "16001" || $kpi_id == "11001") {
                    $_t1 = $this->get_od_data_increment($game_code, $date_1, $date_2, $kpi_detail, $server_id);
                    if (count($_t1) > 0) $no_data = false;
                    $odd[$tab_id][$kpi_detail['kpi_code']] = $_t1;
                } else {
                    $_t2 = $this->get_od_data_total($game_code, $date_1, $date_2, $kpi_detail, $server_id);
                    if (count($_t2) > 0) $no_data = false;
                    $odd[$tab_id][$kpi_detail['kpi_code']] = $_t2;
                }
            }
        }

        if ($game_code != "" && $date_2 != "") {
            $hourly = $this->get_hourly_report($game_code,$server_id, $date_2);
            if (isset($hourly['table'])) {
                if($server_id == ""){
                    $synchronized_title =  strtoupper($game_code) . " - " . date("d-M-Y", strtotime($date_2));
                }else{
                    $synchronized_title =  strtoupper($game_code) . " - " . $server_id . " - " . date("d-M-Y", strtotime($date_2));
                }
                $viewData['syncData']['synchronized_title'] = $synchronized_title;
                $viewData['syncData']['charts'] = $this->load->view("body_parts/chart/synchronized", $hourly['chart']['container_1'], TRUE);
                //$viewData['syncData']['table'] = $this->load->view("body_parts/table/v_table", $hourly['table'], TRUE);
            }
        }


        $viewData['game_code'] = $game_code;
        $viewData['tab_config'] = $tab_config;
        $viewData['server_list'] = $server_list;
        $viewData ['body'] ['selection'] = $this->load->view('body_parts/selection/hourly_report', $viewData, TRUE);
        $viewData['odd_data'] = $odd;
        $viewData['nodata'] = $no_data;
        $this->_template['content'] = $this->load->view("behavior/odrevenue", $viewData, TRUE);
        $this->_template['body']['aGames'] = $this->game->listGames();
        $this->_template['body']['title'] = "Behavior";
        $this->_template['body']['aGames'] = $this->game->listGames();
        $this->load->view('master_page', $this->_template);
    }

    public function get_json_file()
    {
        $game_code = $_GET['game_code'];
        $report_date = $_GET['report_date'];
        $server_id = $_GET['server_id'];
        $kpi_code_arr = array("a1" => "", "n1" => "", "pu1" => "", "gr1" => "");
        $db_data = $this->behavior->get_hourly_report_synchronized($game_code,$server_id, $report_date, $kpi_code_arr);

        $x_arr = $this->get_24h_xcolumn($report_date);
        $hour_count = count($x_arr);

        $a_data = $this->set_24h_data($hour_count, "a1", $db_data);
        $n_data = $this->set_24h_data($hour_count, "n1", $db_data);
        $gr_data = $this->set_24h_data($hour_count, "gr1", $db_data);
        $pu_data = $this->set_24h_data($hour_count, "pu1", $db_data);

        $t = $a_data;
        rsort($t);
        $max_a = $t[0];

        $tp = $pu_data;
        rsort($tp);
        $max_pu = $tp[0];

        $json_obj = array(
            "xData" => $x_arr,
            "datasets" => array(
                array(
                    "id" => "gr",
                    "name" => "Revenue",
                    "data" => $gr_data,
                    "unit" => "VND",
                    "type" => "area",
                    //"total_value" => "Total revenue: "  . number_format(array_sum($gr_data)),
                    "total_value" => number_format(array_sum($gr_data)),
                    "valueDecimals" => 0
                ),
                array(
                    "id" => "pu",
                    "name" => "Paying Users",
                    "data" => $pu_data,
                    "unit" => "Users",
                    "type" => "area",
                    //"total_value" => "Total paying user: ". number_format(array_sum($pu_data)),
                    "total_value" => number_format($max_pu),
                    "valueDecimals" => 0
                ),
                array(
                    "id" => "a",
                    "name" => "Active Users",
                    "data" => $a_data,
                    "unit" => "Users",
                    "type" => "area",
                    //"total_value" => "Total active user: " . number_format($max_a),
                    "total_value" => number_format($max_a),
                    "valueDecimals" => 0
                ),
                array(
                    "name" => "New Users",
                    "data" => $n_data,
                    "unit" => "Users",
                    "type" => "area",
                    //"total_value" => "Total new register user: ". number_format(array_sum($n_data)),
                    "total_value" => number_format(array_sum($n_data)),
                    "valueDecimals" => 0
                ),

            )
        );
        $json_string = json_encode($json_obj);
        echo $json_string;
    }

    public function get_hourly_report($game_code,$server_id, $report_date)
    {
        $kpi_code_arr = array("a1" => "", "n1" => "", "pu1" => "", "gr1" => "");
        $db_data = $this->behavior->get_hourly_report_synchronized($game_code, $server_id, $report_date, $kpi_code_arr);

        $viewData = array();
        if ($db_data) {
            $human_date = $this->util->get_human_date($report_date);

            $x_arr = $this->get_24h_xcolumn_s($report_date);
            $hour_count = count($x_arr);
            $a_data = $this->set_24h_data($hour_count, "a1", $db_data);
            $n_data = $this->set_24h_data($hour_count, "n1", $db_data);
            $gr_data = $this->set_24h_data($hour_count, "gr1", $db_data);
            $pu_data = $this->set_24h_data($hour_count, "pu1", $db_data);

            $tableData = null;

            $table_header_config = $this->util->get_kpi_header_name();
            //hard code only with hourly, 'Revenue in 1 day' -> 'Revenue'
            $table_header_config['gr1'] = "Revenue";
            $table_header_config['pu1'] = "Paying users";
            $table_header_config['a1'] = "Active users";
            $table_header_config['n1'] = "New register";

            $header = $x_arr;
            $t_data['gr1'] = array_merge(array($table_header_config['gr1']), $gr_data);
            $t_data['pu1'] = array_merge(array($table_header_config['pu1']), $pu_data);
            $t_data['a1'] = array_merge(array($table_header_config['a1']), $a_data);
            $t_data['n1'] = array_merge(array($table_header_config['n1']), $n_data);



            $tableData['header'] = $header;
            $tableData['data'] = $t_data;
            $tableData['id'] = "kpi-hourly-report";
            $tableData['title'] = "Hourly report for " . $human_date;
            $tableData['exportTitle'] = $this->util->get_export_filename($game_code, "hourly", $report_date, "", "4");

            $user_report_date = $this->util->db_date_to_user_date($report_date);

            $chartData['container_1']['xAxisCategories'] = $this->util->get_data_string($this->get_24h_xcolumn($report_date), "'", true);
            $chartData['container_1']['id'] = "container";
            $chartData['container_1']['yPrimaryAxisTitle'] = "Doanh Thu";
            $chartData['container_1']['gameCode'] = $game_code;
            $chartData['container_1']['reportDate'] = $report_date;


            $chartData['container_1']['section_name'] = "Hourly report for " . $human_date;
            $chartData['container_1']['server_id'] = $server_id;

            $sub_title = "Ngày " . $user_report_date;
            $chartData['container_1']['subTitle'] = $sub_title . " (data source: " . $this->behavior->get_data_source($game_code) . ")";

            $viewData['chart'] = $chartData;
            $viewData['table'] = $tableData;
        }
        return $viewData;
    }

    private function set_24h_data($count, $kpi_code, $data)
    {
        $return = array();
        if (!isset($data[$kpi_code])) {
            for ($i = 0; $i < $count; $i++) {
                $return[] = 0;
            }
        } else {
            $json_string = $data[$kpi_code];
            $json_obj = json_decode($json_string);
            $pre = -1;
            for ($i = 0; $i < $count; $i++) {
                if ($i < 10) {
                    $key = "0" . $i . ":00:00";
                } else {
                    $key = $i . ":00:00";
                }
                if (isset($json_obj->$key)) {
                    $need = floatval($json_obj->$key);
                } else {
                    $need = 0;
                }
                if (($kpi_code == "a1" || $kpi_code == "pu1") && $need == $pre
                    && ($i > 8)
                ) { //ko chinh xac lam
                    $return[$i] = 0;
                } else {
                    $return[$i] = $need;
                    $pre = $need;
                }
            }
        }
        if (($kpi_code == "a1" || $kpi_code == "pu1")) {
            $t = $return;
            rsort($t);
            $max_a = $t[0];

            for ($i = 0; $i < count($return); $i++) {
                if ($return[$i] == 0 and isset($return[$i - 1]) and $return[$i - 1] < $max_a) {
                    $return[$i] = $return[$i - 1];
                }
            }
        }
        return $return;
    }
    private function get_24h_xcolumn_s($report_date)
    {
        $yesterday = date("Y-m-d", time());
        $current_h = date("H", time());
        if ($yesterday == $report_date)
            $n = intval($current_h);
        else
            $n = 23;
        $n = 24;
        $return = array();
        for ($i = 1; $i <= $n; $i++) {
            $return[] = $i . "H";
        }
        return $return;
    }

    private function get_24h_xcolumn()
    {
        $return = array();
        for ($i = 1; $i <= 24; $i++) {
            $return[] = $i;
        }
        return $return;
    }

    private function json_data_to_array($json_data)
    {
        $obj = json_decode($json_data);
        $return = array();
        for ($i = 0; $i < 24; $i++) {
            if ($i < 10) {
                $key = "0" . $i . ":00:00";
            } else {
                $key = $i . ":00:00";
            }
            if (isset($obj->$key)) {
                $return[] = floatval($obj->$key);
            } else {
                $return[] = 0;
            }
        }
        return $return;
    }


    private function get_increment_total($data)
    {
        $return = array();
        $total = 0;
        for ($i = 0; $i < 24; $i++) {
            $total = $total + $data[$i];
            $return[] = $total;
        }
        return $return;
    }

    public function get_od_data_increment($game_code, $date_1, $date_2, $kpi_detail, $server_id)
    {
        $kpi_id = $kpi_detail['kpi_id'];
        $kpi_code = $kpi_detail['kpi_code'];
        $pa[$kpi_id] = $kpi_code;
        if($server_id == ""){
            $db_data = $this->behavior->get_od_data($game_code, $date_1, $date_2, $pa);
        }else{
            $db_data = $this->behavior->get_od_data_by_server($game_code, $date_1, $date_2, $pa, $server_id);
        }


        $chartData = array();
        if (count($db_data) > 0) {
            $display_data = isset($db_data[$date_1]) ? $db_data[$date_1] : null;
            $compare_data = isset($db_data[$date_2]) ? $db_data[$date_2] : null;

            $display_data = $this->json_data_to_array($display_data);
            $compare_data = $this->json_data_to_array($compare_data);

            $display_data_total = $this->get_increment_total($display_data);
            $compare_data_total = $this->get_increment_total($compare_data);

            $chartData['xAxisCategories'] = $this->util->get_data_string($this->get_24h_xcolumn(), "'", true);

            $chartData['id'] = $kpi_id;

            $t = array("n1" => "new users ", "gr1" => "revenue ");

            $chartData['data']['main'] = array(
                "name" => "Hourly " . $t[$kpi_code] . date("d-M", strtotime($date_1)),
                "type" => "column",
                "yAxis" => "0",
                "data" => $this->util->get_data_string($display_data)
            );
            $chartData['data']['main_total'] = array(
                "name" => "Total " . $t[$kpi_code] . date("d-M", strtotime($date_1)),
                "type" => "spline",
                "yAxis" => "1",
                "data" => $this->util->get_data_string($display_data_total)
            );

            if ($compare_data != null) {
                $chartData['data']['compare'] = array(
                    "name" => "Hourly " . $t[$kpi_code] . date("d-M", strtotime($date_2)),
                    "type" => "column",
                    "yAxis" => "0",
                    "data" => $this->util->get_data_string($compare_data)
                );
                $chartData['data']['compare_total'] = array(
                    "name" => "Total " . $t[$kpi_code] . date("d-M", strtotime($date_2)),
                    "type" => "spline",
                    "yAxis" => "1",
                    "data" => $this->util->get_data_string($compare_data_total)
                );
            }

            $source = $this->behavior->get_data_source_from_mt($game_code, $kpi_code, "hourly_kpi");

            $sub_title = $this->util->get_human_date($date_1) . " vs " . $this->util->get_human_date($date_2) . " (source: " .
                $source . ")";
            $chartData['subTitle'] = $sub_title;
            if($server_id!=""){
                $kpi_detail['description'] .= " - ServerID: " . $server_id;
            }
            $chartData['title'] = $this->util->get_main_chart_title(array("feature" => $kpi_detail['description'], "game_info" => $this->_gameInfo), 0);
            $chartData['yPrimaryAxisTitle'] = "Hourly";
            $chartData['ySecondaryAxisTitle'] = "Total";

        }
        return $chartData;
    }

    public function get_od_data_total($game_code, $date_1, $date_2, $kpi_detail, $server_id)
    {
        $kpi_id = $kpi_detail['kpi_id'];
        $kpi_code = $kpi_detail['kpi_code'];
        $pa[$kpi_id] = $kpi_detail['kpi_code'];
        if($server_id==""){
            $db_data = $this->behavior->get_od_data($game_code, $date_1, $date_2, $pa);
        }else{
            $db_data = $this->behavior->get_od_data_by_server($game_code, $date_1, $date_2, $pa, $server_id);
        }

        $chartData = array();
        if (count($db_data) > 0) {
            $display_data = isset($db_data[$date_1]) ? $db_data[$date_1] : null;
            $compare_data = isset($db_data[$date_2]) ? $db_data[$date_2] : null;

            $display_data = $this->json_data_to_array($display_data);
            $compare_data = $this->json_data_to_array($compare_data);

            $chartData['xAxisCategories'] = $this->util->get_data_string($this->get_24h_xcolumn(), "'", true);

            $chartData['id'] = $kpi_id;

            $t = array("pu1" => "paying users ", "a1" => "active users ");

            $chartData['data']['main'] = array(
                "name" => "Total " . $t[$kpi_code] . date("d-M", strtotime($date_1)),
                "type" => "spline",
                "yAxis" => "1",
                "data" => $this->util->get_data_string($display_data)
            );


            if ($compare_data != null) {
                $chartData['data']['compare'] = array(
                    "name" => "Total " . $t[$kpi_code] . date("d-M", strtotime($date_2)),
                    "type" => "spline",
                    "yAxis" => "1",
                    "data" => $this->util->get_data_string($compare_data)
                );

            }

            $source = $this->behavior->get_data_source_from_mt($game_code, $kpi_code, "hourly_kpi");

            $sub_title = $this->util->get_human_date($date_1) . " vs " . $this->util->get_human_date($date_2) . " (source: " .
                $source . ")";
            $chartData['subTitle'] = $sub_title;
            if($server_id!=""){
                $kpi_detail['description'] .= " - ServerID: " . $server_id;
            }
            $chartData['title'] = $this->util->get_main_chart_title(array("feature" => $kpi_detail['description'], "game_info" => $this->_gameInfo), 0);
            $chartData['yPrimaryAxisTitle'] = "";
            $chartData['ySecondaryAxisTitle'] = "Total";//$kpi_detail['description'];

        }
        return $chartData;
    }


    public function add_spent_by_level()
    {
        $report_code = "add_spent_by_level";
        $game_code = $this->session->userdata('current_game');
        $gameList = $this->game->listGames();

        $viewData ['body'] ['aGames'] = array_values($gameList);
        $viewData ['body'] ['gameCode'] = $game_code;

        if ($this->input->post('kpidatepicker') != "") {
            $date = $this->input->post('kpidatepicker');
        } else {
            $date = date('d/m/Y', strtotime('-1 days'));
        }
        $viewData ['body'] ['day'] ['kpidatepicker'] = $date;

        $log_date = $this->util->user_date_to_db_date($date);

        $this->_template['content'] = $viewData ['body'] ['selection'] = $this->load->view('body_parts/selection/kpi_date', $viewData, TRUE);
        $this->_template['body']['aGames'] = $this->game->listGames();


        $db_data = $this->behavior->get_add_spent_by_level($game_code, $log_date, $report_code);

        $data = array();
        for($i=0;$i<count($db_data);$i++){
            $row = $db_data[$i];
            $t1 = $row['report_value'];
            $t2 = json_decode($t1, true);
            $data[$row['timing']]['add'] = $t2['add'];
            $data[$row['timing']]['spent'] = $t2['spent'];
        }
        $category = $this->get_category($data['all']['spent']);
        $x_category = $this->util->get_data_string($category,  "'", true);
        $chartData = array();
        foreach($data as $timing => $value){
            $add = $value['add'];
            $spent = $value['spent'];

            $chartData[$timing]['title'] = "Level Analytics";
            $chartData[$timing]['xAxisCategories'] = $x_category;
            $chartData[$timing]['id'] = $timing;
            $chartData[$timing]['yPrimaryAxisTitle'] = "";
            $chartData[$timing]['ySecondaryAxisTitle'] = "";

            $chartData[$timing]['data'] = array(
                "add" => array (
                    "name" => "Total add",
                    "type" => "column",
                    "yAxis" => "0",
                    "data" => $this->util->get_data_string($this->sort_by_category($add, $category))
                ),
                "spent" => array (
                    "name" => "Total spend",
                    "type" => "column",
                    "yAxis" => "0",
                    "data" => $this->util->get_data_string($this->sort_by_category($spent, $category))
                )
            );
        }
        $viewData['chart_data'] = $chartData;
        $this->_template['content'] .= $this->load->view("game_user/add_spent_by_level", $viewData, TRUE);
        $this->_template['body']['title'] = "Top User";
        $this->_template['body']['aGames'] = $this->game->listGames();
        $this->load->view('master_page', $this->_template);
    }

    public function sort_by_category($arr,$category){
        $return = array();
        for($i=0;$i<count($category);$i++){
            if(isset($arr[$category[$i]])){
                $return[] = $arr[$category[$i]];
            }else{
                $return[] = 0;
            }
        }
        return $return;
    }

    public function get_category($arr){
        $key_arr = array_keys($arr);
        $sort_key = array();
        for($i=0;$i<count($key_arr);$i++){
            $t1 = $key_arr[$i];
            $t2 = explode("-", $t1);

            $k = intval($t2[0]);
            $sort_key[$k] = $t1;

        }
        asort($sort_key);
        $return = array_values($sort_key);
        return $return;

    }
    public function top_user()
    {

        $game_code = $this->session->userdata('current_game');
        $gameList = $this->game->listGames();

        $this->session->set_userdata('top_user', 'true');

        $viewData ['body'] ['aGames'] = array_values($gameList);
        $viewData ['body'] ['gameCode'] = $game_code;

        if ($this->input->post('kpidatepicker') != "") {
            $date = $this->input->post('kpidatepicker');
        } else {
            //$date = date('d/m/Y');
            $date = date('d/m/Y', strtotime('-1 days'));
        }
        $viewData ['body'] ['day'] ['kpidatepicker'] = $date;

        $log_date = $this->util->user_date_to_db_date($date);

        $this->_template['content'] = $viewData ['body'] ['selection'] = $this->load->view('body_parts/selection/kpi_date', $viewData, TRUE);
        $this->_template['body']['aGames'] = $this->game->listGames();

        $order_by = "total_charge";
        $db_data = $this->behavior->get_top_user($game_code, $log_date, $order_by);


        $data = array();
        $header = array(
            "id" => "Id",
            "rid" => "Role_ID",
            "sid" => "SId",
            "level" => "Level",
            "online_time" => "Online_Time(m)",
            "total_charge" => "Total_Charge",
            "today_charge" => "Selected_Day_Charge",
            "first_charge_date" => "First_Charge",
            "last_charge_date" => "Last_Charge",
            "last_login_date" => "Last_Login",
            "register_date" => "Register_Date"

        );
        $title_arr = array(
            "1" => "Top paying users (user register from " . $this->util->get_human_date($log_date) . ")",
            "3" => "Top paying users (user register from " . $this->util->get_human_date(date('Y-m-d', strtotime($log_date . " - 3 days"))) . ")",
            "7" => "Top paying users (user register from " . $this->util->get_human_date(date('Y-m-d', strtotime($log_date . " - 7 days"))) . ")",
            "30" => "Top paying users (user register from " . $this->util->get_human_date(date('Y-m-d', strtotime($log_date . " - 30 days"))) . ")",
            "all" => "Top paying users (user register from beginning" . ")"
        );

        $data_table = array();
        $stt = array();
        for ($i = 0; $i < count($db_data); $i++) {
            $row = $db_data[$i];
            $timing = $row['timing'];
            $data = array();
            if(!isset($stt[$timing])){
                $stt[$timing] = 1;
            }
            $data['stt'] = $stt[$timing];
            $stt[$timing] ++;

            $data['id'] = $row['id'];
            $data['rid'] = $row['rid'];
            $data['sid'] = $row['sid'];
            $data['level'] = $row['level'];
            $data['online_time'] = number_format($row['online_time'] / 60);
            $data['total_charge'] = number_format($row['total_charge']);
            $data['today_charge'] = number_format($row['today_charge']);
            $data['first_charge_date'] = $row['first_charge_date'];
            $data['last_charge_date'] = $row['last_charge_date'];
            $data['last_login_date'] = $row['last_login_date'];
            $data['register_date'] = $row['register_date'];

            $data_table[$timing][] = $data;
        }
        array_unshift($header, "STT");
        $viewData['data_table'] = $data_table;
        $viewData['header_table'] = $header;
        $viewData['title_arr'] = $title_arr;


        $this->_template['content'] .= $this->load->view("game_user/top_user", $viewData, TRUE);
        $this->_template['body']['title'] = "Top User";
        $this->_template['body']['aGames'] = $this->game->listGames();
        $this->load->view('master_page', $this->_template);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */