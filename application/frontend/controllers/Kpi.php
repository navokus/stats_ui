<?php

/**
 * @date 2016-05-16
 * @author vinhdp
 *
 */
class Kpi extends MY_Controller
{

    private $class_name = "kpi";

    public function __construct()
    {
        parent::__construct();
        //$this->output->enable_profiler ( TRUE );
        $this->load->model('game_model', 'game');
        $this->load->model('kpi_model', 'kpi');
        $this->load->library('form_validation');
        $this->load->library('util');
        $this->load->model('dashboard_model', 'dashboard');
        $this->load->model('topowner_model', 'topowner');
    }

    public function index()
    {
        $this->getKpi();
    }

    public function getKpi()
    {

        if ($this->input->post('options')) {
            $_SESSION [$this->class_name] ['post'] = $_POST;
        }

        /* required params: used for all action */
        $viewData ['body'] ['aGames'] = $this->game->listGames();
        $gameCode = $this->session->userdata('current_game');
        $viewData ['body'] ['gameCode'] = $gameCode;
        /* end */

        // process date
        $date = $_SESSION['kpi']['date'];
        if ($this->input->post('kpidatepicker') != "") {
            $viewData ['body'] ['day'] ['kpidatepicker'] = $this->input->post('kpidatepicker');
        }/* else if($date != ""){
            $viewData ['body'] ['day'] ['kpidatepicker'] = $date;
        } */ else {
            $viewData ['body'] ['day'] ['kpidatepicker'] = date('d/m/Y', strtotime('-1 days'));
        }

        $_SESSION['kpi']['date'] = $viewData ['body'] ['day'] ['kpidatepicker'];

        /* convert dbdata into view data (viewable) */
        $viewData = $this->getViewData($viewData);
        /* end */

        if (count($viewData ['rawdata'] ['tables'] ['header']) != 1) {
            $viewData ['body'] ['tables'] = $this->load->view("body_parts/table/vertical_table", $viewData ['rawdata'] ['tables'], TRUE);
        }
        if (count($viewData ['rawdata'] ['charts'] ['container_1'] ['data']) != 0) {
            $viewData ['body'] ['charts'] = $this->load->view("body_parts/chart/spline", $viewData ['rawdata'] ['charts'] ['container_1'], TRUE);
        }
        // load master view
        $viewData ['body'] ['selection'] = $this->load->view('body_parts/selection/kpi_date', $viewData, TRUE);

        $this->_template ['content'] = $this->load->view('kpi/layout', $viewData, TRUE);
        $this->load->view('master_page', $this->_template);
    }

    public function getViewData($viewData)
    {

        $tableData = array();
        $gameCode = $viewData ['body'] ['gameCode'];
        $reportDate = $this->util->formatDate("d/m/Y", "d-M-Y", $viewData ['body'] ['day'] ['kpidatepicker']);
        $date = DateTime::createFromFormat('d/m/Y', $viewData ['body'] ['day'] ['kpidatepicker']);
        $timing = $viewData ['body'] ['options'];


        /*if($this->session->userdata('user') == "canhtq"){
            echo "a:".$viewData ['body'] ['day'] ['kpidatepicker']."b";
            exit();
        }*/

        $db_data = $this->kpi->getData($gameCode, $date, "game_kpi");

        foreach ($db_data as $key => $value) {

            $rotate_db_data = $this->util->rotate_db_data($value);

            $data = $this->render_data($rotate_db_data, $gameCode);
            // prepare data for table
            $tableData['table'][$key] = $data;
            $tableData['table'][$key]['exportTitle'] = $this->util->get_export_filename($gameCode, $key, $reportDate, $viewData['body']['toDate']);
        }

        $viewData ['rawdata'] ['tables'] = $tableData;
        $viewData ['body'] ['title'] = "Daily Report";
        return $viewData;
    }

    public function render_data($tableData, $gameCode)
    {
        $data = array();
        if ($tableData) {

            $data = array();
            $timing = array('1', '3', '7', '14', '30', '60', '90');

            foreach ($timing as $time) {
                for ($i = 0; $i < count($tableData ['log_date']); $i++) {
                    // retention rate
                    //if($gameCode == 'dcc'){
                    $tableData ['rr' . $time] [$i] = number_format($tableData ['rr' . $time] [$i], 2) . '%';
                    $tableData ['prr' . $time] [$i] = number_format($tableData ['prr' . $time] [$i], 2) . '%';

                    $churnRate = 100 - $tableData ['rr' . $time] [$i];
                    if ($churnRate == 100) {
                        $churnRate = 0;
                    }
                    $tableData ['cr' . $time] [$i] = number_format($churnRate, 2) . '%';
                    $tableData ['nrr' . $time] [$i] = number_format($tableData ['nrr' . $time] [$i], 2) . '%';
                    $tableData ['acu' . $time] [$i] = number_format($tableData ['acu' . $time] [$i], 2);
                    $tableData ['nr' . $time] [$i] = number_format($tableData ['nr' . $time] [$i], 2);
                    $tableData ['npu_nr' . $time] [$i] = number_format($tableData ['npu_nr' . $time] [$i], 2);
                    /* }else{
                    	$tableData ['rr' . $time] [$i] = number_format ( $tableData ['rlc' . $time] [$i] * 100 / $tableData ['a' . $time] [$i + 1], 2 ) . '%';
                    	$tableData ['nrr' . $time] [$i] = number_format ( $tableData ['nrc' . $time] [$i] * 100 / $tableData ['n' . $time] [$i + 1], 2 ) . '%';
                    } */

                    $tableData ['arppu' . $time] [$i] = number_format($tableData ['gr' . $time] [$i] / $tableData ['pu' . $time] [$i], 2);
                    $tableData ['arpu' . $time] [$i] = number_format($tableData ['gr' . $time] [$i] / $tableData ['a' . $time] [$i], 2);
                    $tableData ['cvr' . $time] [$i] = number_format($tableData ['pu' . $time] [$i] * 100 / $tableData ['a' . $time] [$i], 2) . '%';
                    $tableData ['avgtime' . $time] [$i] = number_format($tableData ['ptime' . $time] [$i] / $tableData ['a' . $time] [$i], 2);
                }
            }
            $seq = array(
                "a", "n", "pu", "gr", "nr", "nrr", "acu", "pcu",
                "npu", "npu_gr", "npu_nr"/* , "nnpu", "nnpu_gr" */, "prr", "arppu", "arpu", "cr", "cvr", "avgtime"
            );

            $tableData = $this->sort($tableData, $seq, $timing);

            // format number
            foreach ($tableData as $key => $value) {

                for ($i = 0; $i < count($value); $i++) {

                    if ($key != "log_date" && strpos($value [$i], "%") == false && strpos($value [$i], ".") == false) {

                        $tableData [$key] [$i] = number_format($value [$i]);
                    }
                }
            }

            $data = array();
            // remove unused date & remove metric without data
            foreach ($tableData as $key => $value) {

                $count = count($value);
                $sum = 0;
                $temp = array();

                for ($i = 0; $i < $count; $i++) {

                    $number = 0;

                    if ($key != "log_date") {

                        if (strpos($value[$i], '.') == false) {
                            $number = intval(str_replace(',', '', $value[$i]));
                        } else {
                            $number = doubleval(str_replace(',', '', str_replace(' %', '', $value[$i])));
                        }

                        /* if($number > 0){
                            $sum += 1;
                        } */
                    }

                    if ($i == 0 || $i % 2 != 0) {

                        $temp = array_merge($temp, array_slice($value, $i, 1));

                        if ($number > 0) {
                            $sum += 1;
                        }
                    }
                }

                if ($sum != 0 || $key == "log_date") {

                    $data[$key] = $temp;
                }
            }
            // modify date title
            $columnNameArr = array("Day", "-1", "-7", "-30");
            $logDateValue = $data["log_date"];
            for ($i = 0; $i < count($logDateValue); $i++) {

                $data ["log_date"] [$i] = $columnNameArr[$i] . ' (' . $logDateValue [$i] . ')';
            }

            // create trend columns
            $this->util->create_trend_column($data);
            $this->unset_fields_unnecessary($data, $timing);
        }

        $data = $this->changeFieldName($data);
        return $data;
    }

    private function changeFieldName($data)
    {

        $table_header_config = $this->util->get_kpi_header_name();
        $result = $data;
        foreach ($data as $key => $value) {

            $fieldName = $table_header_config[$key];

            if ($fieldName != $key && $key != "log_date") {
                $result = $this->array_insert_after($result, $key, array($fieldName => $result[$key]));
                unset($result[$key]);
            }
        }
        return $result;
    }

    private function array_insert_after(array $array, $key, array $new)
    {
        $keys = array_keys($array);
        $index = array_search($key, $keys);
        $pos = false === $index ? count($array) : $index + 1;
        return array_merge(array_slice($array, 0, $pos), $new, array_slice($array, $pos));
    }

    private function unset_fields_unnecessary(& $data, $timing)
    {

        foreach ($timing as $time) {

            unset($data['rlc' . $time]);
            unset($data['clc' . $time]);
            unset($data['rpc' . $time]);
            unset($data['cpc' . $time]);
        }
    }

    private function sort($data, $seq, $timing)
    {

        $result = array();

        $result["log_date"] = $data["log_date"];

        foreach ($seq as $key) {

            foreach ($timing as $time) {

                $result[$key . $time] = $data[$key . $time];
            }
        }

        return $result;
    }

    public function mobile_export()
    {
        $viewData['body']['aGames'] = $this->game->listGames();
        $viewData['body']['optionsWeek'] = $this->util->listOptionsWeek();
        $viewData['body']['optionsMonth'] = $this->util->listOptionsMonth();
        $gameCode = $this->session->userdata('current_game');
        $viewData['body']['gameCode'] = $gameCode;
        if (isset($_POST['daterangepicker'])) {
            $mobile_kpi_type = $_POST['mobile_kpi_type'];
            $all_kpi = $this->util->get_all_kpi();
            $viewData = $this->get_data_report($viewData, $mobile_kpi_type);

            if (count($viewData['rawdata']['tables']['data']) >= 1) {
                if ($mobile_kpi_type == "os") {
                    $viewData['rawdata']['tables']['colspan'] = count(array_keys($all_kpi));
                    $viewData['body']['tables'] = $this->load->view("body_parts/table/os_export_table_reverse", $viewData['rawdata']['tables'], TRUE);
                } else {
                    if($mobile_kpi_type == "channel"){
                        $viewData['rawdata']['tables']['colspan'] = count(array_keys($all_kpi));
                        $viewData['body']['tables'] = $this->load->view("body_parts/table/channel_export_table_reverse", $viewData['rawdata']['tables'], TRUE);
                    } else if($mobile_kpi_type == "package"){
                        $viewData['rawdata']['tables']['colspan'] = count(array_keys($all_kpi));
                        $viewData['body']['tables'] = $this->load->view("body_parts/table/package_export_table_reverse", $viewData['rawdata']['tables'], TRUE);
                    }else{
                        $viewData['body']['tables'] = $this->load->view("body_parts/table/reverse_table", $viewData['rawdata']['tables'], TRUE);
                    }
                }
            }
            $viewData['all_reports'] = $this->get_report_key();
        } else {
            $viewData['body']['day']['default_range_date'] = date('d/m/Y', strtotime("-31 days")) . " - " . date('d/m/Y', strtotime("-1 days"));
        }

        $viewData['body']['kpi_selection'] = $this->load->view('export/listing_mobile_report', $viewData, TRUE);
        $this->_template['body']['title'] = "Filter";
        $this->_template['body']['aGames'] = $this->game->listGames();
        $this->_template['content'] = $this->load->view('export/wrapper', $viewData, TRUE);
        $this->load->view('master_page', $this->_template);
    }

    public function export_kpi()
    {
        $viewData['body']['aGames'] = $this->game->listGames();
        $viewData['body']['optionsWeek'] = $this->util->listOptionsWeek();
        $viewData['body']['optionsMonth'] = $this->util->listOptionsMonth();
        $gameCode = $this->session->userdata('current_game');
        $viewData['body']['gameCode'] = $gameCode;

        if (isset($_POST['daterangepicker'])) {
            $viewData = $this->get_data_report($viewData, "game");
            if (count($viewData['rawdata']['tables']['header']) > 1) {
                $viewData['body']['tables'] = $this->load->view("body_parts/table/reverse_table", $viewData['rawdata']['tables'], TRUE);
            }
            $viewData['all_reports'] = $this->get_report_key();
        } else {
            $viewData['body']['day']['default_range_date'] = date('d/m/Y', strtotime("-31 days")) . " - " . date('d/m/Y', strtotime("-1 days"));
        }
        $viewData['body']['kpi_selection'] = $this->load->view('export/listing_report', $viewData, TRUE);
        $this->_template['body']['title'] = "Filter";
        $this->_template['body']['aGames'] = $this->game->listGames();
        $this->_template['content'] = $this->load->view('export/wrapper', $viewData, TRUE);
        $this->load->view('master_page', $this->_template);
    }

    public function export_kpi_by_source()
    {
        $viewData['body']['aGames'] = $this->game->listGames();
        $viewData['body']['optionsWeek'] = $this->util->listOptionsWeek();
        $viewData['body']['optionsMonth'] = $this->util->listOptionsMonth();
        $gameCode = $this->session->userdata('current_game');
        $viewData['body']['gameCode'] = $gameCode;
        $viewData['dataSource'] = array("ingame", "sdk", "payment", "voss");

        if (isset($_POST['daterangepicker'])) {
            $viewData['body']['source'] = $_POST['data_source'];
            $viewData = $this->get_data_report_by_source($viewData, "game");
            if (count($viewData['rawdata']['tables']['header']) > 1) {
                $viewData['body']['tables'] = $this->load->view("body_parts/table/reverse_table", $viewData['rawdata']['tables'], TRUE);
            }
            $viewData['all_reports'] = $this->get_report_key();
        } else {
            $viewData['body']['day']['default_range_date'] = date('d/m/Y', strtotime("-31 days")) . " - " . date('d/m/Y', strtotime("-1 days"));
        }
        $viewData['body']['kpi_selection'] = $this->load->view('export_source/listing_report', $viewData, TRUE);
        $this->_template['body']['title'] = "Filter";
        $this->_template['body']['aGames'] = $this->game->listGames();
        $this->_template['content'] = $this->load->view('export/wrapper', $viewData, TRUE);
        $this->load->view('master_page', $this->_template);
    }


    private function remove_os_kpi_all_day_zero($data)
    {
        $re_organize = array();
        $os_list = $this->util->get_os_list();
        $count = count($data);
        for ($i = 0; $i < $count; $i++) {
            foreach ($os_list as $os) {
                foreach ($data[$i][$os] as $kpi_code => $kpi_value) {
                    $re_organize[$kpi_code][] = $kpi_value;
                }
            }
        }
        foreach ($re_organize as $kpi_code => $data_sum) {
            $sum = array_sum($data_sum);
            if ($sum == 0) {
                for ($i = 0; $i < $count; $i++) {
                    foreach ($os_list as $os) {
                        unset($data[$i][$os][$kpi_code]);
                    }
                }
            }
        }
        return $data;
    }

    private function remove_channel_kpi_all_day_zero($data)
    {
        $re_organize = array();
        $count = count($data);
        for ($i = 0; $i < $count; $i++) {
            $channel_list = $data[$i]['channel'];

            foreach ($channel_list as $channel) {
                foreach ($data[$i][$channel] as $kpi_code => $kpi_value) {
                    $re_organize[$kpi_code][] = $kpi_value;
                }
            }
        }
        foreach ($re_organize as $kpi_code => $data_sum) {
            $sum = array_sum($data_sum);
            if ($sum == 0) {
                for ($i = 0; $i < $count; $i++) {
                    foreach ($channel_list as $channel) {
                        unset($data[$i][$channel][$kpi_code]);
                    }
                }
            }
        }
        return $data;
    }
    private function remove_package_kpi_all_day_zero($data)
    {
        $re_organize = array();
        $count = count($data);
        for ($i = 0; $i < $count; $i++) {
            $package_list = $data[$i]['package'];

            foreach ($package_list as $package) {
                foreach ($data[$i][$package] as $kpi_code => $kpi_value) {
                    $re_organize[$kpi_code][] = $kpi_value;
                }
            }
        }
        foreach ($re_organize as $kpi_code => $data_sum) {
            $sum = array_sum($data_sum);
            if ($sum == 0) {
                for ($i = 0; $i < $count; $i++) {
                    foreach ($package_list as $package) {
                        unset($data[$i][$package][$kpi_code]);
                    }
                }
            }
        }
        return $data;
    }
    private function remove_game_kpi_all_day_zero($data)
    {
        $db_data_by_field = $this->util->re_organize_db_data($data);
        $key_sets = array_keys($db_data_by_field);
        foreach ($key_sets as $k) {
            if (array_sum($db_data_by_field[$k]) == 0) {
                foreach ($data as $key => $value) {
                    unset($data[$key][$k]);
                }
            }
        }
        return $data;
    }

    private function get_data_report_by_source($viewData, $kpi_type)
    {
        $table_config = array(
            "game" => "game_kpi",
            "os" => "os_kpi",
            "channel" => "channel_kpi",
            "package" => "package_kpi"
        );

        $this->session->set_userdata('kpi_export_type', $kpi_type);

        $date = $_POST['daterangepicker'];
        $viewData['body']['day']['default_range_date'] = $date;
        $gameCode = $_POST['default_game'];
        $dataSource = $_POST['data_source'];

        unset($_POST['default_game']);
        unset($_POST['daterangepicker']);
        unset($_POST['data_source']);

        $t1 = explode("-", $date);
        $t2 = explode("/", trim($t1[0]));
        $fromDate = $t2[2] . "-" . $t2[1] . "-" . $t2[0];
        $t3 = explode("/", trim($t1[1]));
        $toDate = $t3[2] . "-" . $t3[1] . "-" . $t3[0];

        $all_kpi_code = $this->get_report_key();
        $kpi_set = array_keys($all_kpi_code);

        if ($kpi_type != "os") {
            $t_2 = $this->kpi->get_export_datatable_by_source($fromDate, $toDate, $gameCode, $table_config[$kpi_type], $kpi_set, $table_config[$kpi_type], $dataSource);

            $t_2 = $this->util->calculate_report_not_in_database($gameCode, $t_2, $kpi_set);
            $t_2 = $this->util->remove_game_kpi_not_display($t_2);
            $t_2 = $this->remove_game_kpi_all_day_zero($t_2);
            $t_2 = $this->util->sort_data_table($t_2, 4, true);
            $t_2 = $this->sort_export_by_kpi_id($t_2);
            $header_key_sets = array_keys($t_2[0]);
        } else {
            $t_2 = $this->kpi->get_os_export_datatable($fromDate, $toDate, $gameCode, $kpi_set, "os_kpi");

            $t_2 = $this->util->calculate_os_report_not_in_database($gameCode, $t_2, $kpi_set);
            $t_2 = $this->util->remove_os_kpi_not_display($t_2);
            $t_2 = $this->remove_os_kpi_all_day_zero($t_2);
            $t_2 = $this->sort_os_export_by_kpi_id($t_2);
            $header_key_sets = array_keys($t_2[0]['android']);
        }

        // vinhdp
        $tableData = array();
        if ($kpi_type != "os") {
            $newTable = $this->util->reverseData($t_2);
            $tableData['data'] = $newTable;
        } else {
            $newTable = array();
            for ($i = 0; $i <= count($t_2); $i++) {
                foreach ($t_2[$i] as $key => $value) {
                    if ($key != "log_date") {
                        // $key = android
                        foreach ($value as $k => $v) {
                            // $k = a1
                            $newTable[$k][$i][$key] = $v;
                        }
                    } else {
                        $newTable[$key][] = $value;
                    }
                }
            }

            $tableData['data'] = $newTable;
        }

        $tableData['title'] = str_replace("_", " ", strtoupper($table_config[$kpi_type] . "_" . $dataSource));
        $tableData['id'] = "kpi-report-export";
        $tableData['exportTitle'] = $this->util->get_export_filename($gameCode, $kpi_type . ".export", $fromDate, $toDate);

        //thiet lap lai header
        $table_header_config = $this->util->get_kpi_header_name();
        $table_header_config['log_date'] = "NGÀY";
        foreach ($header_key_sets as $hk) {
            $tableData['header'][$hk] = $table_header_config[$hk];
        }

        if (count($tableData['data']) == 0) {
            $_SESSION['export_nodata'] = true;
        }

        $viewData['rawdata']['tables'] = $tableData;

        return $viewData;
    }

    private function get_data_report($viewData, $kpi_type)
    {
        $table_config = array(
            "game" => "game_kpi",
            "os" => "os_kpi",
            "channel" => "channel_kpi",
            "package" => "package_kpi"
        );

        $this->session->set_userdata('kpi_export_type', $kpi_type);

        $date = $_POST['daterangepicker'];
        $viewData['body']['day']['default_range_date'] = $date;
        $gameCode = $_POST['default_game'];
        unset($_POST['default_game']);
        unset($_POST['daterangepicker']);

        $t1 = explode("-", $date);
        $t2 = explode("/", trim($t1[0]));
        $fromDate = $t2[2] . "-" . $t2[1] . "-" . $t2[0];
        $t3 = explode("/", trim($t1[1]));
        $toDate = $t3[2] . "-" . $t3[1] . "-" . $t3[0];

        $all_kpi_code = $this->get_report_key();
        $kpi_set = array_keys($all_kpi_code);

        if ($kpi_type != "os") {
            //lamnt6
            if($kpi_type == "channel"){
                $t_2 = $this->kpi->get_channel_export_datatable($fromDate, $toDate, $gameCode, $table_config[$kpi_type], $kpi_set, $table_config[$kpi_type]);
                $t_2 = $this->util->calculate_channel_report_not_in_database($gameCode, $t_2, $kpi_set);

                $t_2 = $this->util->remove_channel_game_kpi_not_display($t_2);

                $t_2 = $this->remove_channel_kpi_all_day_zero($t_2);
                $t_2 = $this->sort_channel_export_by_kpi_id($t_2);
                $header_key_sets = array_keys($t_2[0][$t_2[0]['channel'][0]]);

            }
            else if($kpi_type == "package"){
                $t_2 = $this->kpi->get_package_export_datatable($fromDate, $toDate, $gameCode, $table_config[$kpi_type], $kpi_set, $table_config[$kpi_type]);
                $t_2 = $this->util->calculate_package_report_not_in_database($gameCode, $t_2, $kpi_set);

                $t_2 = $this->util->remove_package_game_kpi_not_display($t_2);

                $t_2 = $this->remove_package_kpi_all_day_zero($t_2);
                $t_2 = $this->sort_package_export_by_kpi_id($t_2);
                $header_key_sets = array_keys($t_2[0][$t_2[0]['package'][0]]);

            }
            else {
                $t_2 = $this->kpi->get_export_datatable($fromDate, $toDate, $gameCode, $table_config[$kpi_type], $kpi_set, $table_config[$kpi_type]);
                $t_2 = $this->util->calculate_report_not_in_database($gameCode, $t_2, $kpi_set);
                $t_2 = $this->util->remove_game_kpi_not_display($t_2);
                $t_2 = $this->remove_game_kpi_all_day_zero($t_2);
                $t_2 = $this->util->sort_data_table($t_2, 4, true);
                $t_2 = $this->sort_export_by_kpi_id($t_2);
                $header_key_sets = array_keys($t_2[0]);
            }


        } else {
            $t_2 = $this->kpi->get_os_export_datatable($fromDate, $toDate, $gameCode, $kpi_set, "os_kpi");

            $t_2 = $this->util->calculate_os_report_not_in_database($gameCode, $t_2, $kpi_set);
            $t_2 = $this->util->remove_os_kpi_not_display($t_2);
            $t_2 = $this->remove_os_kpi_all_day_zero($t_2);
            $t_2 = $this->sort_os_export_by_kpi_id($t_2);
            $header_key_sets = array_keys($t_2[0]['android']);
        }

        // vinhdp
        $tableData = array();
        if ($kpi_type != "os") {
            if ($kpi_type == "channel") {
                $newTable = array();
                for ($i = 0; $i <= count($t_2); $i++) {
                    foreach ($t_2[$i] as $key => $value) {
                        if ($key != "log_date") {
                            // $key = channel
                            if($key=="channel"){
                                $newTable[$key][] = $value;
                            }else{
                                foreach ($value as $k => $v) {
                                    // $k = a1
                                    $newTable[$k][$i][$key] = $v;
                                }
                            }

                        } else {
                            $newTable[$key][] = $value;


                        }
                    }
                }
                $tableData['data'] = $newTable;
            }else if ($kpi_type == "package") {
                $newTable = array();
                for ($i = 0; $i <= count($t_2); $i++) {
                    foreach ($t_2[$i] as $key => $value) {
                        if ($key != "log_date") {
                            // $key = channel
                            if($key=="package"){
                                $newTable[$key][] = $value;
                            }else{
                                foreach ($value as $k => $v) {
                                    // $k = a1
                                    $newTable[$k][$i][$key] = $v;
                                }
                            }

                        } else {
                            $newTable[$key][] = $value;


                        }
                    }
                }
                $tableData['data'] = $newTable;
            }else{
                $newTable = $this->util->reverseData($t_2);
                $tableData['data'] = $newTable;
            }

        } else {
            $newTable = array();
            for ($i = 0; $i <= count($t_2); $i++) {
                foreach ($t_2[$i] as $key => $value) {
                    if ($key != "log_date") {
                        // $key = android
                        foreach ($value as $k => $v) {
                            // $k = a1
                            $newTable[$k][$i][$key] = $v;
                        }
                    } else {
                        $newTable[$key][] = $value;
                    }
                }
            }

            $tableData['data'] = $newTable;
        }

        $tableData['title'] = str_replace("_", " ", strtoupper($table_config[$kpi_type]));
        $tableData['id'] = "kpi-report-export";
        $tableData['exportTitle'] = $this->util->get_export_filename($gameCode, $kpi_type . ".export", $fromDate, $toDate);

//        //lamnt
//        if ($kpi_type == "channel") {
//            $tableData['id'] = "kpi-report-export-channel";
//
//        } else if($kpi_type == "package"){
//            $tableData['id'] = "kpi-report-export-package";
//
//        }

        //thiet lap lai header
        $table_header_config = $this->util->get_kpi_header_name();
        $table_header_config['log_date'] = "NGÀY";
        foreach ($header_key_sets as $hk) {
            $tableData['header'][$hk] = $table_header_config[$hk];
        }

        if (count($tableData['data']) == 0) {
            $_SESSION['export_nodata'] = true;
        }

        $viewData['rawdata']['tables'] = $tableData;


        return $viewData;
    }
    private function sort_channel_export_by_kpi_id($data)
    {
        $all_kpi_code = $data[0][$data[0]['channel'][0]];
        $all_kpi_code = array_keys($all_kpi_code);
        $all_kpi_id = $this->kpi->getKpiIDs(null, $all_kpi_code);
        ksort($all_kpi_id);
        $new = array();
        for ($i = 0; $i < count($data); $i++) {
            $channel_list = $data[$i]['channel'];

            $t = array();
            $t['channel'] = $data[$i]['channel'];
            $t['log_date'] = $data[$i]['log_date'];
            for ($j = 0; $j < count($channel_list); $j++) {
                foreach ($all_kpi_id as $kpi_id => $kpi_code) {
                    $t[$channel_list[$j]][$kpi_code] = $data[$i][$channel_list[$j]][$kpi_code];
                }
            }
            $new[] = $t;
        }
        return $new;
    }
    private function sort_package_export_by_kpi_id($data)
    {
        $all_kpi_code = $data[0][$data[0]['package'][0]];
        $all_kpi_code = array_keys($all_kpi_code);
        $all_kpi_id = $this->kpi->getKpiIDs(null, $all_kpi_code);
        ksort($all_kpi_id);
        $new = array();
        for ($i = 0; $i < count($data); $i++) {
            $package_list = $data[$i]['package'];

            $t = array();
            $t['package'] = $data[$i]['package'];
            $t['log_date'] = $data[$i]['log_date'];
            for ($j = 0; $j < count($package_list); $j++) {
                foreach ($all_kpi_id as $kpi_id => $kpi_code) {
                    $t[$package_list[$j]][$kpi_code] = $data[$i][$package_list[$j]][$kpi_code];
                }
            }
            $new[] = $t;
        }
        return $new;
    }

    private function sort_os_export_by_kpi_id($data)
    {
        $all_kpi_code = $data[0]['android'];
        $all_kpi_code = array_keys($all_kpi_code);
        $all_kpi_id = $this->kpi->getKpiIDs(null, $all_kpi_code);
        ksort($all_kpi_id);
        $os_list = array("android", "ios", "other");
        $new = array();
        for ($i = 0; $i < count($data); $i++) {
            $t = array();
            $t['log_date'] = $data[$i]['log_date'];
            for ($j = 0; $j < count($os_list); $j++) {
                foreach ($all_kpi_id as $kpi_id => $kpi_code) {
                    $t[$os_list[$j]][$kpi_code] = $data[$i][$os_list[$j]][$kpi_code];
                }
            }
            $new[] = $t;
        }
        return $new;
    }

    private function sort_export_by_kpi_id($data)
    {
        $all_kpi_code = $data[0];
        unset($all_kpi_code['log_date']);
        $all_kpi_code = array_keys($all_kpi_code);
        $all_kpi_id = $this->kpi->getKpiIDs(null, $all_kpi_code);
        ksort($all_kpi_id);
        $new = array();
        for ($i = 0; $i < count($data); $i++) {
            $t = array();
            $t['log_date'] = $data[$i]['log_date'];
            foreach ($all_kpi_id as $kpi_id => $kpi_code) {
                $t[$kpi_code] = $data[$i][$kpi_code];
            }
            $new[] = $t;
        }
        return $new;
    }

    public function get_report_key()
    {
        $all = $this->util->get_all_kpi();
        return $all;
        /*
        $ignore_list = $this->util->get_kpi_not_display();
        $extra_list = $this->util->get_kpi_must_calc();
        $timming_map = $this->util->get_timming_config();

        $db_field_config = $this->util->db_field_config(false);
        $return = array();
        foreach ($db_field_config['user_kpi'] as $timing => $value) {
            if ($timing == "7") continue;
            foreach ($value as $k => $v) {
                if (!in_array(substr($k, 0, -strlen($timming_map[$timing])), $ignore_list))
                    $return[$timing][$k] = $v;
            }
        }
        foreach ($db_field_config['revenue_kpi'] as $timing => $value) {
            if ($timing == "7") continue;
            foreach ($value as $k => $v) {
                if (!in_array(substr($k, 0, -strlen($timming_map[$timing])), $ignore_list))
                    $return[$timing][$k] = $v;
            }
            $extra_list1 = array();
            foreach ($extra_list as $k) {
                $extra_list1[] = $k . $timming_map[$timing];
            }
            $extra_list1 = $this->util->add_field_name($extra_list1);
            foreach ($extra_list1 as $k => $v) {
                $return[$timing][$k] = $v;
            }
        }
        return $return;
        */
    }

    public function compare()
    {
        if ($this->input->post('options')) {
            $_SESSION[$this->class_name]['post'] = $_POST;
        }

        $viewData['body']['aGames'] = $this->game->listGames();
        //var_dump($viewData['body']['aGames']);
        $viewData['body']['optionsWeek'] = $this->util->listOptionsWeek1(3);
        $viewData['body']['optionsMonth'] = $this->util->listOptionsMonth(3);
        $gameCode = $this->session->userdata('current_game');
        $viewData['body']['gameCode'] = $gameCode;
        $viewData = $this->getData($viewData);
        //var_dump($viewData['body']['aGames']);
        //$viewData['body']['aGames'] = $this->game->listGames();
        $viewData ['body'] ['api_url'] = base_url('index.php/Kpi/renderCompare');
        $viewData['body']['cbkpi'] = "C";
        $viewData["game"] = $this->game->findGameInfo($gameCode);
        $this->_template['body']['title'] = "Metrics Comparison";
        $viewData['body']['tables'] = $this->load->view("body_parts/table/reverse_table_wtiming", $viewData['rawdata']['tables'], TRUE);
        //$viewData['body']['aGames'] = $this->game->listGames();
        $viewData['body']['selection'] = $this->load->view('body_parts/selection/timing_kpi', $viewData, TRUE);
        //var_dump($this->session->userdata('infoUser'));
        $this->_template['body']['aGames'] = $this->game->listGames();
        $this->_template['content'] .= $this->load->view("kpi/compare", $viewData, TRUE);
        $this->load->view('master_page', $this->_template);
    }

    private function compareConfig($timing)
    {
        $kpis = array("a1vs.n1" => "Active User vs. New Users", "a1vs.pu1" => "Active User vs. Paying Users", "gr1vs.a1" => "Active User vs. Revenue");
        switch ($timing) {
            default:
            case 4:
                $kpis = array("a1vs.n1" => "Daily Active Users vs. Daily New Users"
                , "a1vs.pu1" => "Daily Active Users vs. Daily Paying Users"
                , "gr1vs.pu1" => "Daily Paying Users vs. Daily Revenue"
                , "gr1vs.npu1vs.pu1" => "Daily New Paying Users vs. Paying Users & Revenue"
                );
                break;
            case 17:
                $timing_text = "Weekly";
                $kpis = array("awvs.nw" => "Weekly Active User vs. New Users", "awvs.puw" => "Weekly Active User vs. Paying Users"
                , "grwvs.npuwvs.puw" => "Weekly New Paying Users vs. Paying Users & Revenue"
                );
                break;
            case 31:
                $timing_text = "Monthy";
                $kpis = array("amvs.nm" => "Monthy Active User vs. New Users", "amvs.pum" => "Monthy Active User vs. Paying Users", "amvs.grm" => "Monthy Active User vs. Revenue"
                , "grmvs.npumvs.pum" => "Monthy New Paying Users vs. Paying Users & Revenue"
                );
                break;
        }
        return $kpis;
    }

    public function getDates($start_date, $end_date, $timing)
    {
        $day_arr = $this->util->getDaysFromTiming($start_date, $end_date, $timing, false);

        $now = date("Y-m-d", time());
        if ($timing == "17" || $timing == "31") {
            $yesterday = date("Y-m-d", strtotime($now) - 24 * 60 * 60);
            sort($day_arr);
            $max_date = $day_arr[count($day_arr) - 1];
            if ($max_date > $end_date) {
                //unset($day_arr[count($day_arr)-1]);
                //$day_arr = array_values($day_arr);
                if ($end_date >= $now) {
                    $day_arr[count($day_arr) - 1] = $yesterday;
                } else {
                    $day_arr[count($day_arr) - 1] = $end_date;
                }
            }


        }
        return $day_arr;
    }

    private function getData(&$viewData)
    {
        $gameCode = $viewData["body"]["gameCode"];
        $f_suffix_arr = $this->util->get_timming_config();
        $chartData = null;
        $tableData = null;
        $this->util->parseUserInput($this->class_name, $viewData, $chartData, $tableData, false);

        $timing = $viewData['body']['options'];
        if ($timing == null) {
            $timing = 4;//default
        }
        $viewData['body']["comparison_charts"] = $this->compareConfig($timing);
        $f_suffix = $f_suffix_arr[$timing];
        $gameInfo = $this->game->findGameInfo($gameCode);
        $fromDate = $viewData['body']['fromDate'];
        $toDate = $viewData['body']['toDate'];
        $comparisonFilters = array("from_date" => $fromDate, "to_date" => $toDate, "timing" => $timing);
        $_SESSION['kpi_comparison']['filters'] = $comparisonFilters;

        $db_field_config = $this->util->db_field_config();

        $kpi_field_config = array();
        if ($timing == 4) {
            $kpi_field_config = array_merge($db_field_config['user_kpi']['4'], $db_field_config['revenue_kpi']['4'],
                $db_field_config['user_kpi']['5'], $db_field_config['revenue_kpi']['5'],
                $db_field_config['user_kpi']['6'], $db_field_config['revenue_kpi']['6'],
                $db_field_config['user_kpi']['3'], $db_field_config['revenue_kpi']['3'],
                $db_field_config['user_kpi']['14'], $db_field_config['revenue_kpi']['14']);
        } else {
            $kpi_field_config = array_merge($db_field_config['user_kpi'][$timing], $db_field_config['revenue_kpi'][$timing]);
        }

        $ubstats = $this->load->database('ubstats', TRUE);
        $kpi_ids_config = $this->kpi->getKpiIDs($ubstats, $kpi_field_config);
        $dates = $this->getDates($fromDate, $toDate, $timing);

        $allowKpis = array(
            "a", "n", "pu", "gr", "nrr", "acu", "pcu",
            "npu", "npu_gr"/* , "nnpu", "nnpu_gr" */, "prr", "arppu", "arpu", "cr", "cvr", "aacu", "pacu", "apcu", "ppcu"
        );

        $t_2 = $this->kpi->getKpiDatatable($dates, $gameCode, "game_kpi", $kpi_ids_config, "game_kpi", $allowKpis);

        $t_2 = $this->util->calculate_report_not_in_database($gameCode, $t_2, $kpi_ids_config);
        $db_data_by_field = $this->util->re_organize_db_data($t_2);
        $key_sets = array_keys($db_data_by_field);
        foreach ($key_sets as $k) {
            if (array_sum($db_data_by_field[$k]) == 0 || strpos($k, "nrc") !== false || strpos($k, "rlc") !== false) {
                foreach ($t_2 as $key => $value) {
                    unset($t_2[$key][$k]);
                }
            }
        }
        $t_2 = $this->util->sort_data_table($t_2, 4);
        $header_key_sets = array_keys($t_2[0]);
        $tableData = array();

        $newTable = $this->util->reverseData($t_2);
        $tableData['data'] = $newTable;
        $tableData['timing'] = $timing;
        $tableData['title'] = str_replace("_", " ", "Full KPI Data");
        $tableData['id'] = "kpi-report-export";
        $tableData['exportTitle'] = $this->util->get_export_filename($gameCode, "kpi_comparison" . ".export", $fromDate, $toDate);

        //thiet lap lai header
        $table_header_config = $this->util->get_kpi_header_name();
        $table_header_config['log_date'] = "NGÀY";

        foreach ($header_key_sets as $hk) {
            $tableData['header'][$hk] = $table_header_config[$hk];
        }
        if (count($tableData['data']) == 0) {
            $_SESSION['export_nodata'] = true;
        }

        $viewData['rawdata']['tables'] = $tableData;
        //var_dump($tableData);die;
        return $viewData;
    }

    private function getFullData($gameCode, $timing)
    {
        $viewData = array();
        $f_suffix_arr = $this->util->get_timming_config();
        $chartData = null;
        $tableData = null;
        $this->util->parseUserInput($this->class_name, $viewData, $chartData, $tableData);

        if ($timing == null) {
            $timing = 4;//default
        }
        $viewData['body']["comparison_charts"] = $this->compareConfig($timing);
        $f_suffix = $f_suffix_arr[$timing];
        $gameInfo = $this->game->findGameInfo($gameCode);
        $fromDate = $viewData['body']['fromDate'];
        $toDate = $viewData['body']['toDate'];
        $comparisonFilters = array("from_date" => $fromDate, "to_date" => $toDate, "timing" => $timing);
        $_SESSION['kpi_comparison']['filters'] = $comparisonFilters;

        $db_field_config = $this->util->db_field_config();

        $kpi_field_config = array();
        if ($timing == 4) {
            $kpi_field_config = array_merge($db_field_config['user_kpi']['4'], $db_field_config['revenue_kpi']['4'],
                $db_field_config['user_kpi']['5'], $db_field_config['revenue_kpi']['5'],
                $db_field_config['user_kpi']['6'], $db_field_config['revenue_kpi']['6'],
                $db_field_config['user_kpi']['3'], $db_field_config['revenue_kpi']['3'],
                $db_field_config['user_kpi']['14'], $db_field_config['revenue_kpi']['14']);
        } else {
            $kpi_field_config = array_merge($db_field_config['user_kpi'][$timing], $db_field_config['revenue_kpi'][$timing]);
        }

        $ubstats = $this->load->database('ubstats', TRUE);
        $kpi_ids_config = $this->kpi->getKpiIDs($ubstats, $kpi_field_config);
        $dates = $this->util->getDaysFromTiming($fromDate, $toDate, $timing, false);
        $allowKpis = array(
            "a", "n", "pu", "gr", "nrr", "acu", "pcu",
            "npu", "npu_gr"/* , "nnpu", "nnpu_gr" */, "prr", "arppu", "arpu", "cr", "cvr"
        );

        $t_2 = $this->kpi->getKpiDatatable($dates, $gameCode, "game_kpi", $kpi_ids_config, "game_kpi", $allowKpis);


        $t_2 = $this->calculate_report_not_in_database($gameCode, $t_2, $kpi_ids_config);
        $db_data_by_field = $this->util->re_organize_db_data($t_2);
        $key_sets = array_keys($db_data_by_field);
        foreach ($key_sets as $k) {
            if (array_sum($db_data_by_field[$k]) == 0 || strpos($k, "nrc") !== false || strpos($k, "rlc") !== false) {
                foreach ($t_2 as $key => $value) {
                    unset($t_2[$key][$k]);
                }
            }
        }
        $t_2 = $this->util->sort_data_table($t_2, 4);
        $header_key_sets = array_keys($t_2[0]);
        $tableData = array();

        $newTable = $this->util->reverseData($t_2);
        $tableData['data'] = $newTable;
        $tableData['timing'] = $timing;
        $tableData['title'] = str_replace("_", " ", "Full KPI Data");
        $tableData['id'] = "kpi-report-export";
        $tableData['exportTitle'] = $this->util->get_export_filename($gameCode, "kpi_comparison" . ".export", $fromDate, $toDate);

        //thiet lap lai header
        $table_header_config = $this->util->get_kpi_header_name();
        $table_header_config['log_date'] = "NGÀY";

        foreach ($header_key_sets as $hk) {
            $tableData['header'][$hk] = $table_header_config[$hk];
        }
        if (count($tableData['data']) == 0) {
            $_SESSION['export_nodata'] = true;
        }

        $viewData['rawdata']['tables'] = $tableData;
        return $viewData;
    }

    public function renderCompare($kpis, $chart)
    {
        $kpiFields = explode("vs.", $kpis);
        if ($chart == NULL) {
            $chart = $_SESSION['kpi_comparison']['chart'];
            if ($chart == NULL) {
                $chart = "line";
            }
            $_SESSION['kpi_comparison']['chart'] = $chart;
        }
        $data = $this->createViewData($kpiFields, $kpis, $chart);

        //$this->output->set_output(json_encode($data));
        //$this->output->set_content_type('application/json');
        //return;
        $this->_template['content'] .= $this->load->view("kpi/renderCompare", $data, TRUE);
        $this->load->view('render_html', $this->_template);
    }

    private function createViewData($kpiFields, $kpis, $chartType)
    {
        $game_code = $this->session->userdata('current_game');
        $current_user = $this->session->userdata('user');
        $gameInfo = $this->game->findGameInfo($game_code);
        $comparisonFilters = $_SESSION['kpi_comparison']['filters'];

        $viewData ['body'] ['gameCode'] = $game_code;
        $date = $_SESSION['dashboard']['selected_date'];
        $log_date = $date;
        $now = date("Y-m-d", time());
        $yesterday = date("Y-m-d", strtotime($now) - 24 * 60 * 60);
        $start_date = $comparisonFilters["from_date"];
        $to_date = $comparisonFilters["to_date"];
        $timing = $comparisonFilters["timing"];
        $timing_text = "Daily";
        switch ($timing) {
            default:
            case 4:
                break;
            case 17:
                $timing_text = "Weekly";
                break;
            case 31:
                $timing_text = "Monthy";
                break;
        }
        $viewData['body']['timing_text'] = $timing_text;
        $kpi_data = $this->dashboard->getData2($game_code, $start_date, $to_date, $kpiFields, $timing);
        $kpi_data_w_trend = array();
        $count = count($kpi_data);
        $viewData["body"]["kpis"] = $kpiFields;
        $cp = $this->compareConfig($timing);
        $viewData["body"]["comparison_text"] = $cp[$kpis];
        for ($i = 0; $i < $count; $i++) {
            $kpi_data_w_trend["log_date"][] = $kpi_data[$i]["log_date"];
            $color = 0;
            foreach ($kpiFields as $k => $kname) {
                $kvalue = $kpi_data[$i][$kname];
                if ($kvalue == null) {
                    $kvalue = "0";
                }

                $kpi_data_w_trend[$kname]['data'][] = $kvalue;

                if (strpos($kname, 'gr') !== false) {

                    $kpi_data_w_trend[$kname]['div'] = 1000000;
                    $yAx = 1;
                    $kpi_data_w_trend[$kname]['yAxis'] = $yAx;
                    $kpi_data_w_trend[$kname]['chart'] = "column";
                    $kpi_data_w_trend[$kname]['dim'] = "right";
                    $yConf = array("opposite" => 'true', "chart" => "column", "color" => $color, "title" => "Revenue (VND)");
                    $viewData["body"]["yAxis"]["right"] = $yConf;
                    $kpi_data_w_trend[$kname]['conf'] = $yConf;
                    $kpi_data_w_trend[$kname]['kpi_display'] = "Revenue (VND)";
                } else if (strpos($kname, 'npu') !== false) {
                    $kpi_data_w_trend[$kname]['div'] = 1;
                    $yAx = 0;

                    $kpi_data_w_trend[$kname]['yAxis'] = $yAx;
                    $kpi_data_w_trend[$kname]['chart'] = "line";
                    $kpi_data_w_trend[$kname]['dim'] = "left";
                    $yConf = array("opposite" => 'false', "chart" => "line", "color" => $color, "title" => "Number of Users");
                    $viewData["body"]["yAxis"]["left"] = $yConf;
                    $kpi_data_w_trend[$kname]['conf'] = $yConf;
                    $kpi_data_w_trend[$kname]['kpi_display'] = "New PayingUsers";
                } else if (strpos($kname, 'pu') !== false) {
                    $yAx = 0;
                    $yConf = array("opposite" => 'false', "chart" => "line", "color" => $color, "title" => "Number of Users");
                    $kpi_data_w_trend[$kname]['yAxis'] = $yAx;
                    $kpi_data_w_trend[$kname]['chart'] = "line";
                    $kpi_data_w_trend[$kname]['dim'] = "left";
                    $kpi_data_w_trend[$kname]['kpi_display'] = "PayingUsers";
                    $kpi_data_w_trend[$kname]['conf'] = $yConf;

                    $viewData["body"]["yAxis"]["left"] = $yConf;
                } else if (strpos($kname, 'nrr') !== false) {
                    $yAx = 0;

                    $kpi_data_w_trend[$kname]['yAxis'] = $yAx;
                    $kpi_data_w_trend[$kname]['chart'] = "line";
                    $kpi_data_w_trend[$kname]['kpi_display'] = "Retention";
                    $kpi_data_w_trend[$kname]['dim'] = "left";
                    $yConf = array("opposite" => 'false', "chart" => "line", "color" => $color, "title" => "Number of Users");
                    $viewData["body"]["yAxis"]["left"] = $yConf;
                    $kpi_data_w_trend[$kname]['conf'] = $yConf;
                } else if (strpos($kname, 'n') !== false) {
                    $yAx = 0;
                    $kpi_data_w_trend[$kname]['yAxis'] = $yAx;
                    $kpi_data_w_trend[$kname]['chart'] = "line";
                    $kpi_data_w_trend[$kname]['kpi_display'] = "New Users";
                    $kpi_data_w_trend[$kname]['dim'] = "left";
                    $yConf = array("opposite" => 'false', "chart" => "line", "color" => $color, "title" => "Number of Users");
                    $viewData["body"]["yAxis"]["left"] = $yConf;
                    $kpi_data_w_trend[$kname]['conf'] = $yConf;
                } else if (strpos($kname, 'a') !== false) {
                    $yAx = 0;

                    $kpi_data_w_trend[$kname]['yAxis'] = $yAx;
                    $kpi_data_w_trend[$kname]['chart'] = "line";
                    $kpi_data_w_trend[$kname]['dim'] = "left";
                    $kpi_data_w_trend[$kname]['kpi_display'] = "Active Users";
                    $yConf = array("opposite" => 'false', "chart" => "line", "color" => $color, "title" => "Number of Users");
                    $viewData["body"]["yAxis"]["left"] = $yConf;
                    $kpi_data_w_trend[$kname]['conf'] = $yConf;
                }
                $color += 1;
            }
        }

        $kpi_last_date = $log_date;


        $textDates = array();
        for ($i = 0; $i < count($kpi_data_w_trend['log_date']); $i++) {
            $textDates[] = $this->util->get_xcolumn_by_timming($kpi_data_w_trend['log_date'][$i], $timing, true);
            $kpi_last_date = $kpi_data_w_trend['log_date'][$i];
        }
        $kpi_data_w_trend['text_dates'] = $textDates;
        ksort($kpi_data_w_trend);
        $viewData['body']['kpi_data'] = $kpi_data_w_trend;

        $viewData['body']['timing'] = $timing;
        $viewData['body']['chart'] = $chartType;
        $viewData['body']['game_info'] = $gameInfo;
        return $viewData;
    }

    public function gstMonthlyReport($date)
    {
        //$now = date("Y-m-d", time());
        $yesterday = $date;
        $db_field_config = $this->util->db_field_config();
        $timing = 4;
        $kpi_field_config = array();
        $kpi_field_config = array_merge($db_field_config['user_kpi'][$timing], $db_field_config['revenue_kpi'][$timing]);

        $ubstats = $this->load->database('ubstats', TRUE);
        $kpi_ids_config = $this->kpi->getKpiIDs($ubstats, $kpi_field_config);
        $dates = $this->getDates($yesterday, $yesterday, $timing);

        $allowKpis = array(
            "a", "n", "pu", "gr", "nrr", "acu", "pcu",
            "npu", "npu_gr"/* , "nnpu", "nnpu_gr" */, "prr", "arppu", "arpu", "cr", "cvr"
        );
        $gameCode = "jxm";
        $data = $this->kpi->getKpiDatatable($dates, $gameCode, "game_kpi", $kpi_ids_config, "game_kpi", $allowKpis);
        //var_dump($data);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($data));
    }

    public function myGames()
    {
        $gameCode = "jxm";
        $log_date = "2017-01-31";
        $kpiFields = array("a1", "gr1", "pu1", "n1", "npu1", "am", "grm", "pum", "nm", "npum");
        $timing = 4;
        $start_date = date('Y-m-d', strtotime($log_date));
        $kpi_data = $this->dashboard->getData2($gameCode, $start_date, $log_date, $kpiFields, $timing);

        /*
        $tableData = array();
        foreach ($db_data as $key => $value) {

            $rotate_db_data = $this->util->rotate_db_data($value);

            $data = $this->render_data($rotate_db_data, $gameCode);
            // prepare data for table
            $tableData['table'][$key] = $data;
            $tableData['table'][$key]['exportTitle'] = $this->util->get_export_filename($gameCode, $key, $reportDate, $viewData['body']['toDate']);
        }*/
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($kpi_data));
    }

    private function get_data_reportH($fromDate, $toDate, $gameCode, $kpi_type)
    {
        $table_config = array(
            "game" => "game_kpi",
            "os" => "os_kpi",
            "channel" => "channel_kpi",
            "package" => "package_kpi"
        );


        $all_kpi_code = $this->get_report_key();
        $kpi_set = array_keys($all_kpi_code);

        if ($kpi_type != "os") {
            $t_2 = $this->kpi->get_export_datatable($fromDate, $toDate, $gameCode, $table_config[$kpi_type], $kpi_set, $table_config[$kpi_type]);

            $t_2 = $this->util->calculate_report_not_in_database($gameCode, $t_2, $kpi_set);
            $t_2 = $this->util->remove_game_kpi_not_display($t_2);
            $t_2 = $this->remove_game_kpi_all_day_zero($t_2);
            $t_2 = $this->util->sort_data_table($t_2, 4, true);
            $t_2 = $this->sort_export_by_kpi_id($t_2);
            $header_key_sets = array_keys($t_2[0]);
        } else {
            $t_2 = $this->kpi->get_os_export_datatable($fromDate, $toDate, $gameCode, $kpi_set, "os_kpi");

            $t_2 = $this->util->calculate_os_report_not_in_database($gameCode, $t_2, $kpi_set);
            $t_2 = $this->util->remove_os_kpi_not_display($t_2);
            $t_2 = $this->remove_os_kpi_all_day_zero($t_2);
            $t_2 = $this->sort_os_export_by_kpi_id($t_2);
            //$header_key_sets = array_keys($t_2[0]['android']);
        }


        return $t_2;
    }


    public function exportGroupExelFile()
    {

        require_once APPPATH . "/third_party/PHPExcel.php";
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $activeSheet = $objPHPExcel->getActiveSheet();

        $gameCode = "3qmobile";
        $fromDate = "2017-01-01";
        $toDate = "2017-01-04";
        $kpiFields = array("a1", "gr1", "pu1");


        $kpi_data = $this->get_data_reportH($fromDate, $toDate, $gameCode, "os");
        $tableData = array();
        $row = 2;

        $groups = array("ios", "android", "other");

        for ($i = 0; $i <= count($kpi_data); $i++) {
            $logDate = $kpi_data[$i]["log_date"];
            $col = 0;
            $activeSheet->setCellValueByColumnAndRow($col, $row, $logDate);
            $col++;
            foreach ($groups as $group) {
                $groupData = $kpi_data[$i][$group];

                $cValue = "0";

                foreach ($kpiFields as $kpi) {
                    if ($groupData[$kpi] != null) {
                        $cValue = $groupData[$kpi];
                    }
                    $activeSheet->setCellValueByColumnAndRow($col, $row, $cValue);
                    $col++;
                }

            }
            $row++;
            /*
            $col=1;
            foreach ($kpi_data[$i] as $key => $value) {
                if ($key != "log_date") {
                    // $key = android
                    $group=$key;
                    $groupData = $value;
                    $cValue="0";

                    foreach ($kpiFields as $kpi) {
                        if($value[$kpi]!=null){
                            $cValue=$value[$kpi];
                        }
                        $activeSheet->setCellValueByColumnAndRow($col, $row, $cValue);
                        $col++;
                    }
                } else {
                    $logDate=$value;
                }


            }
            $activeSheet->setCellValueByColumnAndRow(0, $row, $logDate);
            */

        }
        //header

        $row = 1;
        $col = 1;
        $activeSheet->setCellValueByColumnAndRow(0, $row, "date");
        foreach ($groups as $group) {
            foreach ($kpiFields as $kpi) {
                $cValue = $group . "_" . $kpi;

                $activeSheet->setCellValueByColumnAndRow($col, $row, $cValue);
                $col++;
            }
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save("/tmp/data.xls"); // saving the excel file
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode("ok"));
    }

    public function exportMonthly()
    {
        $viewData['body']['optionsMonth'] = $this->util->listOptionsMonth();
        $beforeDate =date('Y-m-d', strtotime('last day of previous month'));
        $viewData['body']['month'] = $beforeDate;
        if(isset($_POST['monthly'])){
            $month= $_POST['monthly'];
        }else{
            $month= $beforeDate;
        }
        $viewData['body']['month'] = $month;
        $listInfoGame = $this->topowner->listGamesByLoginUser();
        foreach ($listInfoGame as $key => $value) {
            $lstGameCode[] = $value['GameCode'];
        }
        /*$month = "2017-01-31";*/
        /*var_dump($month);exit();*/
        $viewData['data'] = $this->exportGameMonthly($lstGameCode, $month);

        $viewData['body']['kpi_selection'] = $this->load->view('export_source/listing_report_monthly', $viewData, TRUE);
        $this->_template['body']['title'] = "Export Monthly ".$month;
        $this->_template['content'] .= $this->load->view("export_source/export_monthly", $viewData, TRUE);
        $this->load->view('master_page', $this->_template);
    }

    private function get_data_report_export($fromDate, $toDate, $gameCode, $kpi_type)
    {
        $table_config = array(
            "game" => "game_kpi",
            "os" => "os_kpi",
            "channel" => "channel_kpi",
            "package" => "package_kpi"
        );


        $all_kpi_code = $this->get_report_key();
        $kpi_set = array_keys($all_kpi_code);
        if ($kpi_type != "os") {

            $t_2 = $this->kpi->get_export_datatable($fromDate, $toDate, $gameCode, $table_config[$kpi_type], $kpi_set, $table_config[$kpi_type]);
            $t_2 = $this->util->calculate_report_not_in_database($gameCode, $t_2, $kpi_set);
            $t_2 = $this->util->remove_game_kpi_not_display($t_2);
            $t_2 = $this->remove_game_kpi_all_day_zero($t_2);
            $t_2 = $this->util->sort_data_table($t_2, 4, true);
            $t_2 = $this->sort_export_by_kpi_id($t_2);
            $header_key_sets = array_keys($t_2[0]);
        } else {
            $t_2 = $this->kpi->get_os_export_datatable($fromDate, $toDate, $gameCode, $kpi_set, "os_kpi");
            $t_2 = $this->util->calculate_os_report_not_in_database($gameCode, $t_2, $kpi_set);
            $t_2 = $this->util->remove_os_kpi_not_display($t_2);
            $t_2 = $this->remove_os_kpi_all_day_zero($t_2);
            $t_2 = $this->sort_os_export_by_kpi_id($t_2);
            //$header_key_sets = array_keys($t_2[0]['android']);
        }


        return $t_2;
    }
    private function getRangDaysOfmonthByDate($dateInput){
        $datef = DateTime::createFromFormat("Y-m-d", $dateInput);

        $m = $datef->format("m");
        $y = $datef->format("Y");

        $number = cal_days_in_month(CAL_GREGORIAN, $m, $y);

        for ($i = 1; $i <= $number; $i++) {
            $mktime = mktime(0, 0, 0, $m, $i, $y);
            $date = date("Y-m-d", $mktime);
            $dates_month[$i] = $date;
        }

        return $dates_month;

    }
    public function exportGameMonthly($games, $date)
    {
        $array_date = $this->getRangDaysOfmonthByDate($date)  ;

        $array_date = array_unique($array_date);

        $fromDate = $date;
        $toDate = $date;

        $kpiArray = "aa1-a30-am-pa1-pu30-pum-grm-gr30-n30-npum-aacu1-apcu1-ppcu1-arpu30-arpum";
        $kpiRRArray  = array('28001' => 'arnr1', '28003' => 'arnr3', '28007' => 'arnr7', '28030' => 'arnr30');

        $kpiFields = explode("-", $kpiArray);
        $row = 0;
        $tableData = array();
        foreach ($games as $key => $gameCode) {

            $kpi_data = $this->get_data_report_export($fromDate, $toDate, $gameCode, "game");
            $avg_rr=$this->kpi->getAvgRR($gameCode,$array_date,$kpiRRArray);

            $avg_conversion=$this->kpi->getAvgConversion($gameCode,$array_date,$kpiRRArray);


            $len = count($kpi_data);
            $gameInfo = $this->game->getFullGameInfo($gameCode);
            for ($i = 0; $i < $len; $i++) {
                $logDate = $kpi_data[$i]["log_date"];
                $col = 0;
                $tableData[$row]['game'] = $gameCode;
                $tableData[$row]['game_name'] = $gameInfo["GameName"];
                $tableData[$row]['platform'] = $gameInfo["platform"];
                $tableData[$row]['dept'] = $gameInfo["owner"];
                $tableData[$row]['date'] = $logDate;
                $data = $kpi_data[$i];
                if ($data != null) {
                    foreach ($kpiFields as $kpi) {

                        $cValue = "0";
                        if ($data[$kpi] != null) {
                            $cValue = $data[$kpi];
                        }
                        $tableData[$row][$kpi][] = $cValue;
                    }
                    foreach ($kpiRRArray as $rr) {
                        $cValue = "0";
                        if ($avg_rr[$gameCode][$rr] != null) {
                            $cValue = $avg_rr[$gameCode][$rr];
                        }
                        $tableData[$row][$rr][] = $cValue;
                    }

                    $cValue = $avg_conversion[$gameCode]["payrate"];
                    $tableData[$row][payrate][] = $cValue;

                }

            }
            $row++;
        }
        return $tableData;
    }
}