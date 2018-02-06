<?php

/**
 * Created by IntelliJ IDEA.
 * User: quangctn
 * Date: 09/10/2017
 * Time: 14:50
 */
class SdkExport extends MY_Controller
{
    public function __construct()
    {
        $this->source_menu="sdk";
        parent::__construct();
        $this->load->model("sdk/sdk_export_model", "kpi");
        $this->load->library('util');
        $this->load->library('kpiconfig');

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
            if (count($viewData['rawdata']['tables']['data']) > 1) {
                $viewData['body']['tables'] = $this->load->view("sdk/body_parts/table/reverse_table", $viewData['rawdata']['tables'], TRUE);
            }
        } else {
            $viewData['body']['day']['default_range_date'] = date('d/m/Y', strtotime("-31 days")) . " - " . date('d/m/Y', strtotime("-1 days"));
        }
        $viewData['body']['kpi_selection'] = $this->load->view('export/listing_report', $viewData, TRUE);
        $this->_template['body']['title'] = "Filter";
        $this->_template['body']['aGames'] = $this->game->listGames();
        $this->_template['content'] = $this->load->view('export/wrapper', $viewData, TRUE);
        $this->load->view('master_page', $this->_template);
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
                    $viewData['body']['tables'] = $this->load->view("sdk/body_parts/table/os_export_table_reverse", $viewData['rawdata']['tables'], TRUE);
                } else {
                    if($mobile_kpi_type == "channel"){
                        $viewData['rawdata']['tables']['colspan'] = count(array_keys($all_kpi));
                        $viewData['body']['tables'] = $this->load->view("sdk/body_parts/table/channel_export_table_reverse", $viewData['rawdata']['tables'], TRUE);
                    } else if($mobile_kpi_type == "package"){
                        $viewData['rawdata']['tables']['colspan'] = count(array_keys($all_kpi));
                        $viewData['body']['tables'] = $this->load->view("sdk/body_parts/table/package_export_table_reverse", $viewData['rawdata']['tables'], TRUE);
                    }else{
                        $viewData['body']['tables'] = $this->load->view("sdk/body_parts/table/reverse_table", $viewData['rawdata']['tables'], TRUE);
                    }
                }
            }
        } else {
            $viewData['body']['day']['default_range_date'] = date('d/m/Y', strtotime("-31 days")) . " - " . date('d/m/Y', strtotime("-1 days"));
        }

        $viewData['body']['kpi_selection'] = $this->load->view('export/listing_mobile_report', $viewData, TRUE);
        $this->_template['body']['title'] = "Filter";
        $this->_template['body']['aGames'] = $this->game->listGames();
        $this->_template['content'] = $this->load->view('export/wrapper', $viewData, TRUE);
        $this->load->view('master_page', $this->_template);
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

        $all_kpi_config= $this->get_report_key($gameCode);

//        $kpi_set = array_keys($all_kpi_code);

        if ($kpi_type != "os") {
            //lamnt6
            if($kpi_type == "channel"){
                $t_2 = $this->kpi->get_channel_export_datatable($fromDate, $toDate, $gameCode, $table_config[$kpi_type], $all_kpi_config, $table_config[$kpi_type]);
                $t_2 = $this->sort_channel_export_by_kpi_id($t_2);
            }
            else if($kpi_type == "package"){
                $t_2 = $this->kpi->get_package_export_datatable($fromDate, $toDate, $gameCode, $table_config[$kpi_type], $all_kpi_config, $table_config[$kpi_type]);
                $t_2 = $this->sort_package_export_by_kpi_id($t_2);
            }
            else {
                $t_2 = $this->kpi->get_export_datatable($fromDate, $toDate, $gameCode, $table_config[$kpi_type], $all_kpi_config, $table_config[$kpi_type]);

                $t_2 = $this->util->sort_data_table($t_2, 4, true);

                $t_2 = $this->sort_export_by_kpi_id($t_2,$gameCode);
            }


        } else {
            $t_2 = $this->kpi->get_os_export_datatable($fromDate, $toDate, $gameCode, $all_kpi_config, "os_kpi");

            $t_2 = $this->sort_os_export_by_kpi_id($t_2);
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
        if (count($tableData['data']) == 0) {
            $_SESSION['export_nodata'] = true;
        }

        $viewData['rawdata']['tables'] = $tableData;


        return $viewData;
    }

    private function sort_channel_export_by_kpi_id($data,$gameCode)
    {
        $all_kpi_id = $this->get_report_key($gameCode);
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

    private function sort_package_export_by_kpi_id($data,$gameCode)
    {
        $all_kpi_id = $this->get_report_key($gameCode);
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

    private function sort_os_export_by_kpi_id($data,$gameCode)
    {
        $all_kpi_id = $this->get_report_key($gameCode);
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

    private function sort_export_by_kpi_id($data,$gameCode)
    {
        $all_kpi_id = $this->get_report_key($gameCode);
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

//    public function get_report_key()
//    {
//        $all = $this->util->get_all_kpi();
//        return $all;
//    }

    public function get_report_key($gameCode)
    {
        $lstKpiTableDaily = $this->kpiconfig->getListKpiByGameCode($gameCode, 'daily');
        $lstKpiTableWeekly = $this->kpiconfig->getListKpiByGameCode($gameCode, 'weekly');
        $lstKpiTableMonthly= $this->kpiconfig->getListKpiByGameCode($gameCode, 'monthly');
        $all = $lstKpiTableDaily+$lstKpiTableWeekly+$lstKpiTableMonthly;
        return $all;
    }
}