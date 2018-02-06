<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 14/03/2016
 * Time: 23:29
 */

class HourlyReport extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('hourly_model', 'hourly_model');
        $this->load->library('form_validation');
        $this->load->library('util');
    }

    public function index()
    {
        $this->hourly();
    }

    public function hourly()
    {
        $game_code = $this->session->userdata('current_game');
        $gameList = $this->game->listGames();

        $viewData ['body'] ['aGames'] = array_values($gameList);

        $viewData ['body'] ['gameCode'] = $game_code;

        if ($this->input->post('kpidatepicker') != "") {
            $date = $this->input->post('kpidatepicker');
        } else {
            $date = date('d/m/Y');
        }
        $viewData ['body'] ['day'] ['kpidatepicker'] = $date;

        $log_date = $this->util->user_date_to_db_date($date);

        if ($game_code != "" && $log_date != "") {
            $hourly = $this->get_hourly_report($game_code, $log_date);
            if (isset($hourly['table'])) {
                $viewData['body']['charts'] = $this->load->view("body_parts/chart/synchronized", $hourly['chart']['container_1'], TRUE);
                $viewData['body']['table'] = $this->load->view("body_parts/table/v_table", $hourly['table'], TRUE);
            }
        }
        $viewData['body']['selection'] = $this->load->view('body_parts/selection/kpi_date', $viewData, TRUE);


        $this->_template['content'] = $this->load->view("hourly_report/hourly_report", $viewData, TRUE);
        $this->_template['body']['aGames'] = $this->game->listGames();
        $this->_template['body']['title'] = "Hourly Report";
        $this->load->view('master_page', $this->_template);
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


    public function get_json_file()
    {
        $game_code = $_GET['game_code'];
        $report_date = $_GET['report_date'];
        $kpi_code_arr = array("a1" => "", "n1" => "", "pu1" => "", "gr1" => "");
        $db_data = $this->hourly_model->get_hourly_report_synchronized($game_code, $report_date, $kpi_code_arr);

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

    public function get_hourly_report($game_code, $report_date)
    {
        $kpi_code_arr = array("a1" => "", "n1" => "", "pu1" => "", "gr1" => "");
        $db_data = $this->hourly_model->get_hourly_report_synchronized($game_code, $report_date, $kpi_code_arr);

        $viewData = array();
        if ($db_data) {
            $human_date = $this->util->get_human_date($report_date);

            $x_arr = $this->get_24h_xcolumn($report_date);
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

            $sub_title = "Ngày " . $user_report_date;
            $chartData['container_1']['subTitle'] = $sub_title . " (data source: " . $this->hourly_model->get_data_source($game_code) . ")";

            $viewData['chart'] = $chartData;
            $viewData['table'] = $tableData;
        }
        return $viewData;
    }


    private function get_24h_xcolumn($report_date)
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
}