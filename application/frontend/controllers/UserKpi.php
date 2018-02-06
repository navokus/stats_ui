<?php

/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 14/03/2016
 * Time: 23:29
 */

class UserKpi extends MY_Controller
{
    private $class_name = "userkpi";
    private $_kpi_type = "game_kpi";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('game_model', 'game');
        $this->load->model('userkpi_model', 'userkpi');
        $this->load->library('form_validation');
        $this->load->library('util');
    }

    public function index()
    {
        $this->active_user();
    }

    public function active_user()
    {
        if ($this->input->post('options')) {
            $_SESSION[$this->class_name]['post'] = $_POST;
        } else {

        }
        $viewData['body']['aGames'] = $this->game->listGames();
        $viewData['body']['optionsWeek'] = $this->util->listOptionsWeek(3);
        $viewData['body']['optionsMonth'] = $this->util->listOptionsMonth(3);
        $gameCode = $this->session->userdata('current_game');
        $viewData['body']['gameCode'] = $gameCode;

        $viewData = $this->get_data_active_user($viewData);
        if (count($viewData['rawdata']['tables']['header']) > 1) {
            $viewData['body']['tables'] = $this->load->view("body_parts/table/reverse_table", $viewData['rawdata']['tables'], TRUE);
        }
        if (count($viewData['rawdata']['charts']['container_1']['data']) != 0) {
            $viewData['body']['charts'] = $this->load->view("body_parts/chart/spline", $viewData['rawdata']['charts']['container_1'], TRUE);
        }
        $current_user = $this->session->userdata('user');
        if ($current_user == "tuonglv") {

        }
        //load master view
        $viewData['body']['cbb'] = "A";
        $viewData['body']['selection'] = $this->load->view('body_parts/selection/kpi', $viewData, TRUE);
        $this->_template['content'] = $this->load->view('userkpi/active_user', $viewData, TRUE);
        $this->load->view('master_page', $this->_template);
    }

    public function get_data_active_user($viewData)
    {
        $chartData = array();
        $tableData = array();
        $f_suffix_arr = $this->util->get_timming_config();
        $gameCode = $viewData['body']['gameCode'];
        $gameInfo = $this->game->findGameInfo($gameCode);
        $this->util->parseUserInput($this->class_name, $viewData, $chartData, $tableData);
        $chartData['container_1']['subTitle'] .= " (data source: " . $this->userkpi->get_data_source_from_mt($gameCode, "a1", "game_kpi") . ")";

        $timming = $viewData['body']['options'];
        $f_suffix = $f_suffix_arr[$timming];
        $db_data = $this->userkpi->getUserKpiData($gameCode, $this->_kpi_type, $viewData['body']['options'], $viewData['body']['fromDate'], $viewData['body']['toDate']);

        $max_log_date = "";
        if (isset($db_data['max_log_date'])) {
            $max_log_date = $db_data['max_log_date'];
            unset($db_data['max_log_date']);
        }

        $db_data_by_field = $this->util->re_organize_db_data($db_data);
        if ($db_data) {
            $t_1 = array();
            $t_2 = array();
            for ($i = 0; $i < count($db_data); $i++) {
                $row = $db_data[$i];

                $t_1['columnX'][] = $this->util->get_xcolumn_by_timming($row['log_date'], $timming, true);
                $t_1['a' . $f_suffix][] = intval($row['a' . $f_suffix]);
                $t_1['n' . $f_suffix][] = intval($row['n' . $f_suffix]);

                //co bao nhieu hien thi len het
                $tt_2 = array();
                $tt_2["log_date"] = $this->util->get_xcolumn_by_timming($row['log_date'], $timming, false);
                if ($max_log_date == $row['log_date'] && $timming != "4") {
                    $tt_2['log_date'] .= " ($max_log_date)";
                }
                unset($row['log_date']);
                foreach ($row as $f => $v) {
                    if("rr".$f_suffix == $f){
                        $tt_2['rr' . $f_suffix] = round($v,2) . "%";
                    }else if("nrr".$f_suffix == $f){
                        $tt_2['nrr' . $f_suffix] = round($v,2) . "%";
                    }else{
                        $tt_2[$f] = number_format($v);
                    }

                }
                // tinh phan tram user retention

                $row_before = isset($db_data[$i - 1]) ? $db_data[$i - 1] : 0;
                $active_user_before = isset($row_before['a' . $f_suffix]) ? $row_before['a' . $f_suffix] : 0;
                $m_active_user_before = isset($row_before['am']) ? $row_before['am'] : 0;
                /*
                if (isset($tt_2['rlc' . $f_suffix])) {
                    $retention_current = isset($row['rlc' . $f_suffix]) ? $row['rlc' . $f_suffix] : 0;
                    if ($active_user_before != 0 && $retention_current != 0) {
                        $retention_percent = round(($retention_current / $active_user_before) * 100, 2) . "%";
                    } else {
                        $retention_percent = "";
                    }
                    $tt_2['rr' . $f_suffix] = $retention_percent;
                    $db_data_by_field['rr' . $f_suffix][] = $retention_percent;
                }


                if (isset($tt_2['rlcm'])) {
                    $retention_current = isset($row['rlcm']) ? $row['rlcm'] : 0;
                    if ($m_active_user_before != 0 && $retention_current != 0) {
                        $retention_percent = round(($retention_current / $m_active_user_before) * 100, 2) . "%";
                    } else {
                        $retention_percent = "";
                    }
                    $tt_2['rrm'] = $retention_percent;
                    $db_data_by_field['rrm' . $f_suffix][] = $retention_percent;
                }
                */
                //tinh phan tram churn
                if (isset($tt_2['clc' . $f_suffix])) {
                    $retention_current = isset($row['clc' . $f_suffix]) ? $row['clc' . $f_suffix] : 0;
                    if ($active_user_before != 0 && $retention_current != 0) {
                        $retention_percent = round(($retention_current / $active_user_before) * 100, 2) . "%";
                    } else {
                        $retention_percent = "";
                    }
                    $tt_2['cr' . $f_suffix] = $retention_percent;
                    $db_data_by_field['cr' . $f_suffix][] = $retention_percent;
                }

                if (isset($tt_2['clcm'])) {
                    $retention_current = isset($row['clcm']) ? $row['clcm'] : 0;
                    if ($m_active_user_before != 0 && $retention_current != 0) {
                        $retention_percent = round(($retention_current / $m_active_user_before) * 100, 2) . "%";
                    } else {
                        $retention_percent = "";
                    }
                    $tt_2['crm'] = $retention_percent;
                    $db_data_by_field['crm'][] = $retention_percent;
                }

                $this->unset_fields_unnecessary($tt_2, $f_suffix);

                $t_2[] = $tt_2;
            }

            $t_2 = array_reverse($t_2);
            $table_header_config = $this->util->get_kpi_header_name();
            $chartData['container_1']['title'] = $this->util->get_main_chart_title(array("feature"=>"Active Users","game_info"=>$this->_gameInfo),$timming);
            $chartData['container_1']['xAxisCategories'] = $this->util->get_data_string($t_1['columnX'], "'", true);
            $chartData['container_1']['id'] = "container_1";
            $chartData['container_1']['yPrimaryAxisTitle'] = "Active Users (users)";
            $chartData['container_1']['ySecondaryAxisTitle'] = "Account Register (users)";
            $chartData['container_1']['data'] = array(
                "a" . $f_suffix => array(
                    "name" => strtoupper($table_header_config['a' . $f_suffix]),
                    "type" => "column",
                    "yAxis" => "0",
                    "data" => $this->util->get_data_string($t_1['a' . $f_suffix])
                ),
                "n" . $f_suffix => array(
                    "name" => strtoupper($table_header_config['n' . $f_suffix]),
                    "type" => "spline",
                    "yAxis" => "1",
                    "data" => $this->util->get_data_string($t_1['n' . $f_suffix])
                )

            );

            //kiem tra neu ko co du lieu thi remove khoi chart, remove khoi table, tranh tinh trang co column nhung ko co du lieu
            $key_sets = array_keys($db_data_by_field);
            foreach ($key_sets as $k) {
                if (array_sum($db_data_by_field[$k]) == 0) {
                    unset($chartData['container_1']['data'][$k]);
                    foreach ($t_2 as $key => $value) {
                        unset($t_2[$key][$k]);
                    }
                }
            }
            //prepare data for table
            //$t_2 = $this->util->sort_data_table($t_2, $timming);
            //$this->util->add_trend_icon($t_2, $timming);
            $newTable = $this->util->reverseData($t_2);
            $tableData['data'] = $newTable;
            $tableData['title'] = "KPI Detail";
            $tableData['id'] = "kpi-report-active-user";
            $tableData['exportTitle'] = $this->util->get_export_filename($gameCode, "user", $viewData['body']['fromDate'], $viewData['body']['toDate'], $timming);

            $tableData['export_file_name'] = $this->util->get_export_filename($gameCode,"user",$viewData['body']['fromDate'],$viewData['body']['toDate']);

            //thiet lap lai header
            $header_key_sets = array_keys($t_2[0]);

            $table_header_config['log_date'] = $tableData['reportType'];
            foreach ($header_key_sets as $hk) {
                $tableData['header'][$hk] = $table_header_config[$hk];
            }
            $viewData['body']['listContainer'] = array_keys($chartData);
            $viewData['body']['gameCode'] = strtoupper($gameCode);
            $viewData['body']['title'] = "Active user";

            $viewData['rawdata']['tables'] = $tableData;
            $viewData['rawdata']['charts'] = $chartData;
        }
        return $viewData;
    }

    private function unset_fields_unnecessary(& $tt_2, $f_suffix){
        unset($tt_2['rlc'.$f_suffix]);
        unset($tt_2['rr'.$f_suffix]);
        unset($tt_2['clc'.$f_suffix]);
        unset($tt_2['cr'.$f_suffix]);
        unset($tt_2['nrc'.$f_suffix]);
        unset($tt_2['rlcm']);
        unset($tt_2['clcm']);
    }
}