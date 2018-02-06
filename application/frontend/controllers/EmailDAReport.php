
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 01/11/2016
 * Time: 09:14
 */
class EmailDAReport extends CI_Controller
{
    private $private_key = "edsa*#12nr32!Mf93";
    public function __construct()
    {
        parent::__construct();
        define("HOME_DIR", "/home/tuonglv/da_mail");
        $this->load->library('email');
        $this->load->model('game_model', 'game');
        $this->load->library('util');
        $this->load->model('emailreport_model', 'emailreport');
        $this->load->library('form_validation');
        $this->load->library('util');
    }

    public function convert($inpupt, $output)
    {
        $this->load->library('excel');
        $this->excel->html_to_excel($inpupt, $output);
    }

    public function index($type, $force = 0)
    {

        $pre = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

</head>
<body>';

        $suf = '</body>
</html>';
        $today = date("Y-m-d");
        $one_day_ago = date("Y-m-d", strtotime($today) - 24 * 60 * 60);
        ini_set('memory_limit', '-1');
        $log_date = $one_day_ago;
        $numday_report = 41;
        $force_time = 1529; //15h29p
        $miss_game_number_allow = 5;
        if (!file_exists(HOME_DIR . "/content/" . $one_day_ago)) {
            mkdir(HOME_DIR . "/content/" . $one_day_ago);
        }
        if (!file_exists(HOME_DIR . "/sent/" . $one_day_ago)) {
            mkdir(HOME_DIR . "/sent/" . $one_day_ago);
        }

        $done_flag = $this->get_done_path($one_day_ago, $type);
        if (file_exists($done_flag)) {
            $time = file_get_contents($done_flag);
            echo "The email was sent at $time\n";
            echo "Path: $done_flag\n";
            return;
        }

        $mode = "real";
        //$mode = "test";
        $system_email_address = "kpi.stats@vng.com.vn";
        if($mode == "real"){
            $operator_email_address = array("canhtq@vng.com.vn", "quangctn@vng.com.vn");
            $operator_crossteam_email_address = array("canhtq@vng.com.vn", "thanhdx@vng.com.vn","huynq2@vng.com.vn",
                "hangnv@vng.com.vn", "nguyettt2@vng.com.vn", "xuyenlt@vng.com.vn", "quangctn@vng.com.vn");
            //remove: "hungnp@vng.com.vn","trongnvd@vng.com.vn","phuongbm@vng.com.vn",

            $boss_email = array("minhlh@vng.com.vn", "chrisliu@vng.com.vn",
                "hungnp@vng.com.vn", "hungnt@vng.com.vn", "minhnh@vng.com.vn", "soant@vng.com.vn", "vandb@vng.com.vn","canhpt@vng.com.vn",
                "trungnk@vng.com.vn", "tuyennn@vng.com.vn", "vietph@vng.com.vn",  "canhtq@vng.com.vn");
            //$boss_email = array("thanhnb@vng.com.vn", "nhambt@vng.com.vn", "tuonglv@vng.com.vn");
            if ($type == "monthly") {
                //$boss_email[] = "thanhnb@vng.com.vn";
                //$boss_email[] = "nhambt@vng.com.vn";
            }
        }else{
            $test_mail = "canhtq@vng.com.vn";
            //$test_mail = "lyvinhtuong@gmail.com";
            $operator_email_address = array($test_mail);
            $operator_crossteam_email_address = array($test_mail);
            $boss_email = array($test_mail);
        }
        $kpi_report_alias = "KPI Report Tool";

        //dung de xac dinh thu tu xuat hien trong table, cac game ko co trong array nay se nam cuoi table.
        $game_in_order = array("jxm", "myplay", "zingplay_card", "myplay_board", "myplayinternational", "3qmobile", "cack", "gnm", "icamfbs2", "tlbbm",
            "siamplay", "cgmfbs", "stct", "nikki", "dptk", "ctpgsn", "wefight", "tfzfbs2", "cgmbgfbs1", "dttk",
            "coccmgsn", "bklr", "sfmgsn", "coccgsn", "stonysea", "pv3d", "dcc",
            "pmcl", "cfgfbs1", "ftgfbs2", "10ha7ifbs1", "htc", "adsads", "stonyvn");

        $game_not_require = array("10ha7ifbs1","cfgfbs1","cgmbgfbs1","ftgfbs2");

        if ($type == "daily") {
            $data = $this->daily($one_day_ago, $numday_report,$game_not_require);
            $human_date = date("d-M-Y", strtotime($one_day_ago));
            $header = "Mobile Games Daily Report - " . $human_date;
            $subject = $header;
        } else {
            $data = $this->monthly($one_day_ago, $numday_report, $game_in_order, $game_not_require);
            $last_month = date("Y-m-t", strtotime("last month"));
            $human_date = date("M-Y", strtotime($last_month));
            $header = "Mobile Games Monthly Report - " . $human_date;
            $subject = $header;
        }

        //$report_date_html = "<strong>Report ngày " . $human_date . "</strong>";
        $report_date_html = "<h2><strong>" . strtoupper($header) . "</strong></h2>";
        $myplay_note_html = "";
        if (in_array("myplay", $game_in_order)) {
            $myplay_note_html = $this->get_note();
        }

        $email_source = $this->get_email_source();

        $html = $pre
            . "\n\n"
            . $report_date_html
            . "\n\n"
            . $data['html']
            .  "\n\n"
            . $myplay_note_html
            . "\n\n"
            . $email_source
            .= "</br></br></br>"
            . $suf;

        $check_data = $data['check_data'];
        $closed_game = isset($data['closed_game']) ? $data['closed_game'] : "";

        $rev_string = "";
        foreach ($boss_email as $email) {
            $rev_string .= $email . ", ";
        }
        $rev_string = substr($rev_string, 0, -2);

        $alert = "<h2><strong> For operation users only: </strong></h4>";
        $mess1 = "";
        $mess2 = "";

        $now_hi = date("Hi");
        $miss_game_number = $check_data['num_miss'];
        if(intval($now_hi) >= $force_time && $miss_game_number < $miss_game_number_allow){
            $force = 1;
        }

        if ($check_data['miss'] === false || $force == 1) {
            $mes = " - The email has sent to " . $rev_string;
            $alert .= "<h4><strong> " . $mes . "</strong></h4>";
        } else {
            $subject = "Still waiting data to send email: " . $subject;
            $url = $this->generate_force_key($log_date);
            $mess1 = " - The email will not be sent. You can force send email immediately by click <a href='$url'>HERE</a>";
            $mess2 = " - The email will not be sent.";
            $alert .= "<h4><strong>AAAAAAAAAAAAAAAA</strong></h4>";
        }

        if ($check_data['kpi_not_found'] != "") {
            $alert .= "<h4><strong>  - Waiting list:</strong></h4>";
            $alert .= $check_data['kpi_not_found'];
        }

        if ($closed_game != "") {
            $alert .= "<h4><strong>  - The following games have closed in last month:</strong></h4>";
            $alert .= $closed_game;
        }

        $alert .= "<br><strong>---------------------------------------------------------------------</strong>";

        $alert_1 = $alert;
        $alert_2 = $alert;
        if ($mess1 != "" && $mess2 != "") {
            $alert_1 = str_replace("AAAAAAAAAAAAAAAA", $mess1, $alert);
            $alert_2 = str_replace("AAAAAAAAAAAAAAAA", $mess2, $alert);
        }
        $html_operation = $pre
            . "\n\n"
            . $alert_1
            . "\n\n"
            . $report_date_html
            . "\n"
            . $data['html']
            . "\n\n"
            . $myplay_note_html
            . $email_source
            . "\n"
            . $suf;

        $html_operation_cross_team = $pre
            . "\n\n"
            . $alert_2
            . "\n\n"
            . $report_date_html
            . "\n"
            . $data['html']
            . "\n\n"
            . $myplay_note_html
            . $email_source
            . "\n"
            . $suf;


        if ($check_data["miss"] === false || $force == 1) {
            //check again
            $done_flag = $this->get_done_path($one_day_ago, $type);
            if (file_exists($done_flag)) {
                $time = file_get_contents($done_flag);
                echo "The email was sent at $time\n";
                return;
            }
            $mail_config = array(
                "from" => $system_email_address,
                "fromalias" => $kpi_report_alias,
                "to" => $boss_email,
                "subject" => $subject,
                "message" => $html,
                "attach" => array($data['attach']),
            );
            $this->util->send_mail($mail_config);
            $now = date("Y-m-d H:i:s");
            file_put_contents($this->get_done_path($one_day_ago, $type), $now);
            echo "Sent \n";
        } else {
            unlink($this->get_done_path($one_day_ago, $type));
            unlink($data['attach']);
            rmdir(HOME_DIR . "/content/" . $one_day_ago);
            rmdir(HOME_DIR . "/sent/" . $one_day_ago);
        }

        $mail_config = array(
            "from" => $system_email_address,
            "fromalias" => $kpi_report_alias,
            "to" => $operator_email_address,
            "subject" => $subject,
            "message" => $html_operation,
            "attach" => array($data['attach']),
        );
        $this->util->send_mail($mail_config);

        //cross team operation
        $mail_config = array(
            "from" => $system_email_address,
            "fromalias" => $kpi_report_alias,
            "to" => $operator_crossteam_email_address,
            "subject" => $subject,
            "message" => $html_operation_cross_team,
            "attach" => array($data['attach']),
        );
        $this->util->send_mail($mail_config);

    }

    private function add_miss_game_and_remove_close_game($game_list, $data)
    {
        for ($i = 0; $i < count($game_list); $i++) {
            if (!isset($data[$game_list[$i]])) {
                //echo "remove " . $game_list[$i] . "\n";
                unset($game_list[$i]);
            }
        }
        $game_list = array_values($game_list);
        foreach ($data as $game_code => $detail) {
            if (!in_array($game_code, $game_list)) {
                $game_list[] = $game_code;
                //echo "add " . $game_code . "\n";
            }
        }
        return $game_list;
    }
    private function generate_force_key($log_date){
        $hash = md5($log_date . $this->private_key);
        $url = "https://kpi.stats.vng.com.vn/index.php/email-report/force-send?log_date=" . $log_date . "&key=" . $hash;
        return $url;
    }

    public function force_send()
    {
        $has_request = isset($_GET['key']) ? $_GET['key'] : "";
        $log_date = isset($_GET['log_date']) ? $_GET['log_date'] : "";
        $yesterday = date("Y-m-d", strtotime("1 day ago"));

        $hash = md5($log_date . $this->private_key);

        if ($yesterday != $log_date) {
            echo "Request not valid<br>";
        } else {
            if ($has_request == $hash) {
                echo "The email will be sent now<br>";
                exec("nohup sh /home/tuonglv/da_mail/script/send.sh > /tmp/force_send 2>&1 &", $output);
                file_put_contents("/tmp/force_send", $output);
            } else {
                echo "key not valid<br>";
            }
        }
    }

    private function remove_game_included($data){
        $games_remove = array("ctpgsn", "sfmgsn", "coccmgsn","360live");
        foreach($games_remove as $game_code){
            if(isset($data[$game_code])){
                unset($data[$game_code]);
            }
        }
        return $data;
    }
    private function array_sort_by_column(&$arr, $col, $dir = SORT_DESC) {
        $sort_col = array();
        foreach ($arr as $key=> $row) {
            $sort_col[$key] = $row[$col];
        }
        return array_multisort($sort_col, $dir, $arr);
    }
    public function daily($one_day_ago, $numday_report, $game_in_order, $game_not_require, $is_mail = true)
    {
        $log_date = $one_day_ago;

        $kpi_id_display = array("n1" => "New Users", "a1" => "A1", "a30" => "A30", "pu1" => "Paying Users", "gr1" => "Revenue");
        $kpi_id_attach = array("n1" => "New Users", "nrr1" => "RR1(%)", "nrr7" => "RR7(%)", "a1" => "A1", "a7" => "A7", "a30" => "A30",
            "pu1" => "Paying Users",
            "npu1" => "New Paying Users",
            "gr1" => "Revenue");

        $all_kpi = array_merge($kpi_id_attach, $kpi_id_display);

        $start_date = date("Y-m-d", strtotime($log_date) - $numday_report * 24 * 60 * 60);
        $day_arr = $this->util->getDaysEveryDay($start_date, $log_date);
        $db_data = $this->emailreport->get_mobile_game_report($day_arr, array_keys($all_kpi));


        $db_data = $this->remove_game_included($db_data);

        $data = array_merge($db_data);

        $display_data = $this->get_display_data($data, $log_date);
        $this->array_sort_by_column($display_data, 'gr1');
        $game_in_order = array_keys($display_data);
        $summary_data = $this->calc_summary($kpi_id_display, $display_data);
        $summary_html = $this->generate_summary_html_daily($summary_data, $kpi_id_display);

        $game_in_order_html = $this->generate_top_key_games($display_data, $kpi_id_display, $game_in_order);

        $html = ""
            . $summary_html
            . "\n\n"
            . $game_in_order_html
            . "\n";
        // unset myplay from sent condition
        /*
        if (($key = array_search("myplay", $game_must_have_reports)) !== false) {
            unset($game_must_have_reports[$key]);
            $game_must_have_reports = array_values($game_must_have_reports);
        }*/
        //end
        $check_data = null;
        $attach_path_daily = "";
        if($is_mail === true){
            $check_data = $this->check_data_ready($game_not_require, $display_data, $kpi_id_display);
            $attach_path_daily = $this->get_daily_attach_path($log_date);
            $this->generate_attach_file_daily($data, $day_arr, $kpi_id_attach, $attach_path_daily, $game_in_order);
        }
        return array("check_data" => $check_data, "html" => $html, "attach" => $attach_path_daily);
    }

    public function monthly($one_day_ago, $numday_report,$game_in_order, $game_not_require)
    {
        $log_date = $one_day_ago;

        $kpi_monthly = array("am" => "am", "nm" => "nm", "pum" => "pum", "grm" => "grm",
            "a30" => "a30", "npum" => "npum");
        $kpi_monthly_display = array("nm" => "New Users", "avg_a1" => "Average A1", "a30" => "A30", "pum" => "Paying Users", "grm" => "Revenue");
        $kpi_id_attach = array("nm" => "New Users", "avg_nrr1" => "Average RR1(%)", "avg_nrr7" => "Average RR7(%)", "avg_a1" => "Average A1",
            "avg_a7" => "Average A7", "a30" => "A30",
            "pum" => "Paying Users",
            "npum" => "New Paying Users",
            "grm" => "Revenue");

        $all_kpi = $kpi_monthly;

        $end_date = date('Y-m-d', strtotime('last day of previous month'));
        $start_date = date('Y-m-d', strtotime('12 months ago'));

       /* $end_date = '2016-09-30';
        $start_date = '2016-02-28';*/
        $day_arr = $this->util->getDaysFromTiming($start_date, $end_date, '31', "");
        $db_data_arr = $this->emailreport->get_mobile_game_report_monthly($day_arr, array_keys($all_kpi));
        $data_t1 = $this->remove_game_included($db_data_arr['data']);
        $db_data_arr['data'] = $data_t1;

        $db_data = $db_data_arr['data'];
        $close_games = $db_data_arr['closed_games'];
        $data = $db_data;
        $display_data_monthly = $this->get_display_data_monthly($data);
        $this->array_sort_by_column($display_data_monthly['detail']['data'],'grm');
        //var_dump($display_data_monthly['detail']['data']);exit();
        $game_in_order = (array_keys($display_data_monthly['detail']['data']));
        unset($game_in_order[array_search("ftgfbs2", $game_in_order)]);
        /*$game_in_order = $this->add_miss_game_and_remove_close_game($game_in_order, $display_data_monthly['detail']['data'])*/;

        $summary_monthly_html = $this->generate_summary_html_monthly($display_data_monthly['summary'], $kpi_monthly_display);
        $game_in_order_monthly_html = $this->generate_top_key_games_monthly($display_data_monthly['detail'], $kpi_monthly_display, $game_in_order);

        $html = ""
            . $summary_monthly_html
            . "\n\n"
            . $game_in_order_monthly_html
            . "\n";

        // unset myplay from sent condition
        /*
        if (($key = array_search("myplay", $game_must_have_reports)) !== false) {
            unset($game_must_have_reports[$key]);
            $game_must_have_reports = array_values($game_must_have_reports);
        }
        */
        //end
        $data_check = $display_data_monthly['detail']['data'];
        foreach($display_data_monthly['detail']['game_info'] as $game_code => $_t){
            if(isset($data_check[$game_code]))
                $data_check[$game_code]['game_name'] = $_t['game_name'];
        }
        $check_data = $this->check_data_ready($game_not_require, $data_check, $kpi_monthly_display);
        $attach_path_monthly = $this->get_monthly_attach_path($log_date);

        $closed_games_html = $this->close_game_alert($close_games);

        $data_attach = $this->get_data_attach($data);
        $this->generate_attach_file_monthly($data_attach, $day_arr, $kpi_id_attach, $attach_path_monthly, $game_in_order);

        return array("check_data" => $check_data, "html" => $html, "attach" => $attach_path_monthly, "closed_game" => $closed_games_html);

    }

    private function get_done_path($to_date, $type)
    {
        return HOME_DIR . "/sent/" . $to_date . "/done_" . $type . ".txt";
    }
    private function get_force_send_dir()
    {
        return HOME_DIR . "/wait";
    }
    private function get_force_send_path($to_date)
    {
        return HOME_DIR . "/wait/" . $to_date . "/force.txt";
    }

    private function generate_attach_file_daily($data, $day_arr, $kpi_id_arr, $output_path, $game_list)
    {
        require_once APPPATH . "/third_party/PHPExcel.php";
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $activeSheet = $objPHPExcel->getActiveSheet();

        $activeSheet->getColumnDimensionByColumn(0)->setWidth(8);
        $activeSheet->getColumnDimensionByColumn(1)->setWidth(15);

        $default_border = array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '1006A3'));
        $header_style = array(
            'borders' => array('bottom' => $default_border, 'left' => $default_border, 'top' => $default_border, 'right' => $default_border,),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'E1E0F7'),),
            'font' => array('bold' => true,));

        $game_code_style = array(
            'borders' => array('bottom' => $default_border, 'left' => $default_border, 'top' => $default_border, 'right' => $default_border,),
            'font' => array(
                'bold' => true,
            ));

        $cell_style = array(
            'borders' => array('bottom' => $default_border, 'left' => $default_border, 'top' => $default_border, 'right' => $default_border,),
            //   'fill' => array( 'type' => PHPExcel_Style_Fill::FILL_SOLID, ),
            //   'font' => array( 'bold' => true, )
        );

        $rowspans = count(array_keys($kpi_id_arr));

        $activeSheet->getStyleByColumnAndRow(0, 1)
            ->applyFromArray($header_style)
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $activeSheet->setCellValueByColumnAndRow(0, 1, "Game Name");


        $activeSheet->getStyleByColumnAndRow(1, 1)
            ->applyFromArray($header_style)
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $activeSheet->setCellValueByColumnAndRow(1, 1, "KPIs");


        $num_day = count($day_arr);
        for ($i = $num_day - 1; $i > 0; $i--) {
            $t_day = date("d-M-Y", strtotime($day_arr[$i]));
            $activeSheet->getStyleByColumnAndRow(2 + ($num_day - $i) - 1, 1, $t_day)
                ->applyFromArray($header_style)
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $activeSheet->setCellValueByColumnAndRow(2 + ($num_day - $i) - 1, 1, $t_day);

            $activeSheet->getColumnDimensionByColumn($num_day - $i + 1)->setWidth(12);
        }
        $next_row = 2;
        foreach ($game_list as $game_code) {
            $data_detail = $data[$game_code];
            $activeSheet->getStyleByColumnAndRow(0, $next_row)
                ->applyFromArray($game_code_style)
                ->getAlignment()
                ->setWrapText(true)
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $activeSheet->setCellValueByColumnAndRow(0, $next_row, $data[$game_code]['game_name']);
            $activeSheet->mergeCellsByColumnAndRow(0, $next_row, 0, $next_row + $rowspans);

            $_t1 = 0;
            foreach ($kpi_id_arr as $kpi_id => $kpi_name) {
                $activeSheet->getStyleByColumnAndRow(1, $next_row + $_t1)->applyFromArray($cell_style);
                $activeSheet->setCellValueByColumnAndRow(1, $next_row + $_t1, $kpi_name);
                $_t2 = 1;
                for ($i = count($day_arr) - 1; $i > 0; $i--) {
                    $value = isset($data[$game_code][$day_arr[$i]][$kpi_id]) ? $data[$game_code][$day_arr[$i]][$kpi_id] : 0;
                    $value = number_format($value);

                    $activeSheet->getStyleByColumnAndRow(1 + $_t2, $next_row + $_t1)
                        ->applyFromArray($cell_style)
                        ->getAlignment()
                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $activeSheet->setCellValueByColumnAndRow(1 + $_t2, $next_row + $_t1, $value);
                    $_t2++;
                }
                $_t1++;
            }

            $activeSheet->getStyleByColumnAndRow(1, $next_row + $_t1)->applyFromArray($cell_style);
            $activeSheet->setCellValueByColumnAndRow(1, $next_row + $_t1, "ARPPU");

            $_t2 = 1;
            for ($i = count($day_arr) - 1; $i > 0; $i--) {
                $need1 = isset($data[$game_code][$day_arr[$i]]['pu1']) ? $data[$game_code][$day_arr[$i]]['pu1'] : 0;
                $need2 = isset($data[$game_code][$day_arr[$i]]['gr1']) ? $data[$game_code][$day_arr[$i]]['gr1'] : 0;
                $arppu = 0;
                if ($need1 != 0 and $need2 != 0) {
                    $arppu = round($need2 / $need1, 2);
                }
                $arppu = number_format($arppu);

                $activeSheet->getStyleByColumnAndRow(1 + $_t2, $next_row + $_t1)
                    ->applyFromArray($cell_style)
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $activeSheet->setCellValueByColumnAndRow(1 + $_t2, $next_row + $_t1, $arppu);
                $_t2++;

            }

            $next_row = $next_row + $rowspans + 1;

        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($output_path); // saving the excel file

    }

    private function generate_attach_file_monthly($data, $day_arr, $kpi_id_arr, $output_path, $game_list)
    {
        require_once APPPATH . "/third_party/PHPExcel.php";
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $activeSheet = $objPHPExcel->getActiveSheet();

        $activeSheet->getColumnDimensionByColumn(0)->setWidth(8);
        $activeSheet->getColumnDimensionByColumn(1)->setWidth(15);

        $default_border = array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '1006A3'));
        $header_style = array(
            'borders' => array('bottom' => $default_border, 'left' => $default_border, 'top' => $default_border, 'right' => $default_border,),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'E1E0F7'),),
            'font' => array('bold' => true,));

        $game_code_style = array(
            'borders' => array('bottom' => $default_border, 'left' => $default_border, 'top' => $default_border, 'right' => $default_border,),
            'font' => array(
                'bold' => true,
            ));

        $cell_style = array(
            'borders' => array('bottom' => $default_border, 'left' => $default_border, 'top' => $default_border, 'right' => $default_border,),
            //   'fill' => array( 'type' => PHPExcel_Style_Fill::FILL_SOLID, ),
            //   'font' => array( 'bold' => true, )
        );

        $rowspans = count(array_keys($kpi_id_arr));

        $activeSheet->getStyleByColumnAndRow(0, 1)
            ->applyFromArray($header_style)
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $activeSheet->setCellValueByColumnAndRow(0, 1, "Game Name");


        $activeSheet->getStyleByColumnAndRow(1, 1)
            ->applyFromArray($header_style)
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $activeSheet->setCellValueByColumnAndRow(1, 1, "KPIs");


        $num_day = count($day_arr);
        for ($i = $num_day - 1; $i > 0; $i--) {
            $t_day = date("M-Y", strtotime($day_arr[$i]));
            $activeSheet->getStyleByColumnAndRow(2 + ($num_day - $i) - 1, 1, $t_day)
                ->applyFromArray($header_style)
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $activeSheet->setCellValueByColumnAndRow(2 + ($num_day - $i) - 1, 1, $t_day);
            $activeSheet->getColumnDimensionByColumn($num_day - $i + 1)->setWidth(12);
        }
        $next_row = 2;
        foreach ($game_list as $game_code) {
            $data_detail = $data[$game_code];
            $activeSheet->getStyleByColumnAndRow(0, $next_row)
                ->applyFromArray($game_code_style)
                ->getAlignment()
                ->setWrapText(true)
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $activeSheet->setCellValueByColumnAndRow(0, $next_row, $data[$game_code]['game_name']);
            $activeSheet->mergeCellsByColumnAndRow(0, $next_row, 0, $next_row + $rowspans);

            $_t1 = 0;
            foreach ($kpi_id_arr as $kpi_id => $kpi_name) {
                $activeSheet->getStyleByColumnAndRow(1, $next_row + $_t1)->applyFromArray($cell_style);
                $activeSheet->setCellValueByColumnAndRow(1, $next_row + $_t1, $kpi_name);
                $_t2 = 1;
                for ($i = count($day_arr) - 1; $i > 0; $i--) {
                    $value = isset($data[$game_code][$day_arr[$i]][$kpi_id]) ? $data[$game_code][$day_arr[$i]][$kpi_id] : 0;
                    $value = number_format($value);

                    $activeSheet->getStyleByColumnAndRow(1 + $_t2, $next_row + $_t1)
                        ->applyFromArray($cell_style)
                        ->getAlignment()
                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $activeSheet->setCellValueByColumnAndRow(1 + $_t2, $next_row + $_t1, $value);
                    $_t2++;
                }
                $_t1++;
            }

            $activeSheet->getStyleByColumnAndRow(1, $next_row + $_t1)->applyFromArray($cell_style);
            $activeSheet->setCellValueByColumnAndRow(1, $next_row + $_t1, "ARPPU");

            $_t2 = 1;
            for ($i = count($day_arr) - 1; $i > 0; $i--) {
                $need1 = isset($data[$game_code][$day_arr[$i]]['pum']) ? $data[$game_code][$day_arr[$i]]['pum'] : 0;
                $need2 = isset($data[$game_code][$day_arr[$i]]['grm']) ? $data[$game_code][$day_arr[$i]]['grm'] : 0;
                $arppu = 0;
                if ($need1 != 0 and $need2 != 0) {
                    $arppu = round($need2 / $need1, 2);
                }
                $arppu = number_format($arppu);

                $activeSheet->getStyleByColumnAndRow(1 + $_t2, $next_row + $_t1)
                    ->applyFromArray($cell_style)
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $activeSheet->setCellValueByColumnAndRow(1 + $_t2, $next_row + $_t1, $arppu);
                $_t2++;

            }

            $next_row = $next_row + $rowspans + 1;

        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($output_path); // saving the excel file

    }

    private function get_daily_attach_path($log_date)
    {
        return HOME_DIR . "/content/" . $log_date . "/kpi.stats.vng.com.vn-daily-kpi-report." . $log_date . ".xlsx";
    }

    private function get_monthly_attach_path($log_date)
    {
        $end_of_month_date = date('Y-m-d', strtotime('last day of previous month'));
        return HOME_DIR . "/content/" . $log_date . "/kpi.stats.vng.com.vn-monthly-kpi-report." . $end_of_month_date . ".xlsx";
    }

    private function get_attach_content($data, $day_arr, $kpi_id_arr)
    {
        $html = '';
        $html .= '<table border="1" cellspacing="0" cellpadding="0">
				<thead>
					<tr align="center" style="background-color:#ddf2f2">

						<th>Game</th>
						<th>KPIs</th>';
        for ($i = count($day_arr) - 1; $i > 0; $i--) {
            $t_day = date("d-M-Y", strtotime($day_arr[$i]));
            $html .= '<th>' . $t_day . '</th>';
        }
        $html .= '</tr></thead>';
        $html .= '<tbody>';
        $rowspans = count(array_keys($kpi_id_arr)) + 1;
        foreach ($data as $game_code => $data_detail) {
            $html .= '<tr>';
            $html .= '<th rowspan="' . $rowspans . '">' . $data[$game_code]['game_name'] . '</th>';
            foreach ($kpi_id_arr as $kpi_id => $kpi_name) {

                $html .= '<th>' . $kpi_name . '</th>';
                for ($i = count($day_arr) - 1; $i > 0; $i--) {
                    $value = isset($data[$game_code][$day_arr[$i]][$kpi_id]) ? $data[$game_code][$day_arr[$i]][$kpi_id] : 0;
                    $html .= '<th>' . number_format($value) . '</th>';
                }

                $html .= '</tr>';
                $html .= '<tr>';
            }

            $html .= '<th>' . 'ARPPU' . '</th>';
            for ($i = count($day_arr) - 1; $i > 0; $i--) {
                $need1 = isset($data[$game_code][$day_arr[$i]]['pu1']) ? $data[$game_code][$day_arr[$i]]['pu1'] : 0;
                $need2 = isset($data[$game_code][$day_arr[$i]]['gr1']) ? $data[$game_code][$day_arr[$i]]['gr1'] : 0;
                $arppu = 0;
                if ($need1 != 0 and $need2 != 0) {
                    $arppu = round($need2 / $need1, 2);
                }
                $html .= '<th>' . number_format($arppu) . '</th>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>' . "\n";
        return $html;
    }

    public function get_display_data($data, $log_date)
    {
        $display_data = array();
        foreach ($data as $game_code => $data_detail) {
            $display_data[$game_code] = $data_detail[$log_date];
            $display_data[$game_code]['game_name'] = $data_detail['game_name'];
            $display_data[$game_code]['region'] = $data_detail['region'];
        }
        return $display_data;
    }

    private function close_game_alert($close_games)
    {
        $html_alert = '<table border="1" cellspacing="0" cellpadding="0">';
        $html_alert .= '<thead>
							<tr style="background-color:#c3e3e3">
							<th align="center" style="text-align:center;padding:5px">GameCode</th>
							<th align="center" style="text-align:center;padding:5px">GameName</th>
								<th align="center" style="text-align:center;padding:5px">CloseDate</th>';

        foreach ($close_games as $game_code => $detail) {
            $html_alert .= "<tr>";
            $html_alert .= '<th align="center" style="text-align:center;padding:5px;width:100px">' . strtoupper($game_code) . "</td>";
            $html_alert .= '<th align="center" style="text-align:center;padding:5px;width:100px">' . $detail['game_name'] . "</td>";
            $html_alert .= '<th align="center" style="text-align:center;padding:5px;width:100px">' . $detail['max_date'] . "</td>";
            $html_alert .= "</tr>";
        }

        $html_alert .= '</thead>';
        $html_alert .= '<tbody>';
        $html_alert .= "</table>";

        return $html_alert;
    }

    private function check_data_ready($game_not_require, $data, $kpi_id_arr)
    {
        $html_alert = '<table border="1" cellspacing="0" cellpadding="0">';
        $html_alert .= '<thead>
							<tr style="background-color:#c3e3e3">
							<th align="center" style="text-align:center;padding:5px">GameCode</th>
							<th align="center" style="text-align:center;padding:5px">GameName</th>
								<th align="center" style="text-align:center;padding:5px">KPI</th>
								<th align="center" style="text-align:center;padding:5px">Require</th>';
        $html_alert .= '</thead>';
        $html_alert .= '<tbody>';

        $not_found = false;
        $r_t = 1;
        $num_miss_game = 0;
        foreach ($data as $game_code => $detail) {
            $miss = false;
            $not_found_html = "";
            $require_text = (in_array($game_code, $game_not_require)) ? "NO" : "YES";
            $game_name = $data[$game_code]['game_name'];
            foreach ($kpi_id_arr as $kpi_id => $kpi_name) {
                if (!isset($data[$game_code][$kpi_id]) || $data[$game_code][$kpi_id] == 0) {
                    //echo "game_code = $game_code, kpi not found = $kpi_id \n";
                    $not_found = true;
                    $not_found_html .= strtoupper($kpi_id) . ", ";
                    if (!in_array($game_code, $game_not_require)) {
                        //echo 'set miss = true' . "\n";
                        $miss = true;
                    }
                    //don't break, to get all information about miss log
                }
            }
            if($miss == true && $require_text == "YES"){
                $num_miss_game++;
            }
            if ($not_found_html != "") {
                $not_found_html = substr($not_found_html, 0, -2);
                $html_alert .= "<tr>";
                $html_alert .= '<th align="center" style="text-align:center;padding:5px;width:100px">' . strtoupper($game_code) . "</td>";
                $html_alert .= '<th align="center" style="text-align:center;padding:5px;width:100px">' . ($game_name) . "</td>";
                $html_alert .= '<th align="center" style="text-align:center;padding:5px;width:100px">' . $not_found_html . "</td>";
                $html_alert .= '<th align="center" style="text-align:center;padding:5px;width:100px">' . $require_text . "</td>";
                $html_alert .= "</tr>";
            }
            $r_t++;
        }

        $html_alert .= "</tbody>";
        $html_alert .= "</table>";
        if ($not_found === false) {
            $html_alert = "";
        }
        $miss = ($num_miss_game==0) ? false : true;
        $return = array("miss" => $miss, "kpi_not_found" => $html_alert, "num_miss" => $num_miss_game);
        return $return;
    }

    private function get_note()
    {
        $note = '<strong>--------------------------------------</strong><p>
            <span style="color:#f21619"><h3><strong>***Note: </strong></h3></span>
             - MyPlay Card contains the following games: Ba Cây, Binh, Poker HK (Xì Tố), Poker US, Sâm, Tá Lả, Tiến Lên, Xìdzach.
             <br>
             - MyPlay Non-Card contains the following games: Cờ Tỷ Phú, Bida, Bida Card, Bida Mobile, Caro, Cờ Cá Ngựa, Cờ Tướng, Cờ Úp, Thời Loạn, Farmery.
             <br>
             - MyPlay International contains the following games: Big2Indo, Binh Indo, Poker-US Indo, Cờ Tỷ Phú Sea, Liêng Thái, Poker Thái, Bida mobile International, Farmery Mobile Sea, Thời Loạn Sea.
             <br>
             - This report does not include offline games.
             </p>';
        return $note;
    }

    private function get_email_source()
    {
        $more_info = "<i>
            Source: <a href='https://kpi.stats.vng.com.vn/index.php/dashboard2?from=mobile-email'>STATS/TEG</a><br>
            </i>
            ";
        return $more_info;
    }

    private function generate_top_key_games($data, $kpi_id_arr, $game_in_order)
    {
        $html = '';
        $html .= '<h3><strong>DETAIL MOBILE GAMES</strong></h3>';
        $html .= '<table border="1" cellspacing="0" cellpadding="0">';
        $html .= '<thead>
							<tr style="background-color:#c3e3e3">
							<th align="center" style="text-align:center;padding:5px">STT</th>
								<th align="center" style="text-align:center;padding:5px">Game Name</th>';

        foreach ($kpi_id_arr as $kpi_id => $kpi_name) {
            $html .= '<th align="center" style="text-align:center;padding:5px;width:100px">' . $kpi_name . '</th>';
        }

        $html .= '<th align="center" style="text-align:center;padding:5px">Global/Local Game</th>';
        $html .= '</tr></thead>';

        $html .= '<tbody>';
        $stt = 1;
        foreach ($game_in_order as $game_code) {
            if(!isset($data[$game_code]))
                continue;
            $row  = "";
            $game_name = $data[$game_code]['game_name'];
            $region = $data[$game_code]['region'];
            $row .= '<tr align="right">';
            $row .= '<td align="left" rowspan="" style="text-align:left;padding:5px">' . $stt . '</td>';

            $row .= '<td align="left" rowspan="" style="text-align:left;padding:5px">' . $game_name . '</td>';

            $sum_all_kpi = 0;

            foreach ($kpi_id_arr as $kpi_id => $kpi_name) {
                $value = isset($data[$game_code][$kpi_id]) ? $data[$game_code][$kpi_id] : 0;
                $sum_all_kpi += $value;
                $row .= '<td align="left" rowspan="" style="text-align:right;padding:5px">  ' . number_format($value) . ' </td>';
            }
            $row .= '<td align="left" rowspan="" style="text-align:center;padding:5px">' . ($region) . '</td>';
            if($sum_all_kpi != 0){
                $stt++;
                $html .= $row;
            }

        }

        $html .= '</tbody>';
        $html .= '</table>';
        return $html;
    }

    private function generate_top_key_games_monthly($data, $kpi_id_arr, $game_in_order)
    {
        $html = '';
        $html .= '<h3><strong>DETAIL MOBILE GAMES</strong></h3>';
        $html .= '<table border="1" cellspacing="0" cellpadding="0">';
        $html .= '<thead>
							<tr style="background-color:#c3e3e3">
							<th align="center" style="text-align:center;padding:5px">STT</th>
								<th align="center" style="text-align:center;padding:5px">Game Name</th>';


        foreach ($kpi_id_arr as $key => $value) {
            $html .= '<th align="center" style="text-align:center;padding:5px;width:100px">' . $value . '</th>';
        }
        $html .= '<th align="center" style="text-align:center;padding:5px">Global/Local Game</th>';
        $html .= '</tr></thead>';

        $html .= '<tbody>';
        $stt = 1;
        foreach ($game_in_order as $game_code) {
            if(!isset($data['data'][$game_code]))
                continue;
            $game_name = $data['game_info'][$game_code]['game_name'];
            $region = $data['game_info'][$game_code]['region'];
            $html .= '<tr align="right">';
            $html .= '<td align="left" rowspan="" style="text-align:left;padding:5px">' . $stt . '</td>';
            $stt++;
            $html .= '<td align="left" rowspan="" style="text-align:left;padding:5px">' . $game_name . '</td>';

            foreach ($kpi_id_arr as $kpi_id => $kpi_name) {
                $value = isset($data['data'][$game_code][$kpi_id]) ? $data['data'][$game_code][$kpi_id] : 0;
                $html .= '<td align="left" rowspan="" style="text-align:right;padding:5px">  ' . number_format($value) . ' </td>';
            }
            $html .= '<td align="left" rowspan="" style="text-align:left;padding:5px">' . ($region) . '</td>';

        }
        $html .= '</tbody>';
        $html .= '</table>';
        return $html;
    }

    private function generate_summary_html_daily($summary_data, $kpi_id_arr)
    {
        $html = '';
        $html .= '<h3><strong>SUMMARY MOBILE GAMES</strong></h3>';
        $html .= '<table border="1" cellspacing="0" cellpadding="0">
				<thead>
					<tr style="background-color:#c3e3e3">
						<th align="center" style="text-align:center;padding:5px">KPIs</th>
						<th align="center" style="text-align:center;padding:5px">SUM ALL MOBILE GAMES </th>
						<th align="center" style="text-align:center;padding:5px">SUM LOCAL MOBILE GAMES </th>
						<th align="center" style="text-align:center;padding:5px">SUM GLOBAL MOBILE GAMES </th>
					</tr>
				</thead>';
        $html .= '<tbody>';

        foreach ($kpi_id_arr as $kpi_id => $kpi_name) {
            $local_percent = round(($summary_data[$kpi_id]['local'] / $summary_data[$kpi_id]['total']) * 100, 2);
            $global_percent = round(($summary_data[$kpi_id]['global'] / $summary_data[$kpi_id]['total']) * 100, 2);
            $html .= '<tr align="right">
						<td align="left" rowspan="" style="text-align:left;padding:5px">' . $kpi_name . '</td>
						<td align="left" style="text-align:right;padding:5px">   ' . number_format($summary_data[$kpi_id]['total']) . ' </td>
						<td align="left" style="text-align:right;padding:5px">   ' . number_format($summary_data[$kpi_id]['local']) . '(' . $local_percent . '%)' . ' </td>
						<td align="left" style="text-align:right;padding:5px">   ' . number_format($summary_data[$kpi_id]['global']) . '(' . $global_percent . '%)' . ' </td>
					</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';

        return $html;

    }

    private function generate_summary_html_monthly($summary_data, $kpi_monthly_display)
    {
        $html = '';
        $html .= '<h3><strong>SUMMARY MOBILE GAMES</strong></h3>';
        $html .= '<table border="1" cellspacing="0" cellpadding="0">
				<thead>
					<tr style="background-color:#c3e3e3">
						<th align="center" style="text-align:center;padding:5px">KPIs</th>
						<th align="center" style="text-align:center;padding:5px">SUM ALL MOBILE GAMES </th>
						<th align="center" style="text-align:center;padding:5px">SUM LOCAL MOBILE GAMES </th>
						<th align="center" style="text-align:center;padding:5px">SUM GLOBAL MOBILE GAMES </th>
					</tr>
				</thead>';
        $html .= '<tbody>';

        foreach ($kpi_monthly_display as $key => $value) {
            $local_percent = round(($summary_data[$key]['local'] / $summary_data[$key]['total']) * 100, 2);
            $global_percent = round(($summary_data[$key]['global'] / $summary_data[$key]['total']) * 100, 2);

            $html .= '<tr align="right">
		 	<td align="left" rowspan="" style="text-align:left;padding:5px">' . $value . '</td>
			<td align="left" style="text-align:right;padding:5px">   ' . number_format($summary_data[$key]['total']) . ' </td>
						<td align="left" style="text-align:right;padding:5px">   ' . number_format($summary_data[$key]['local']) . '(' . $local_percent . '%)' . ' </td>
						<td align="left" style="text-align:right;padding:5px">   ' . number_format($summary_data[$key]['global']) . '(' . $global_percent . '%)' . ' </td>
    		</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';

        return $html;

    }

    private function get_data_attach($data)
    {
        $t1 = array();
        foreach ($data as $game_code => $detail) {
            foreach ($detail as $day => $kpi_detail) {
                $t_date = date("Y-m-t", strtotime($day));
                if (isset($kpi_detail['a1'])) {
                    $t1[$game_code][$t_date]['a1'][] = $kpi_detail['a1'];
                }
                if (isset($kpi_detail['a7'])) {
                    $t1[$game_code][$t_date]['a7'][] = $kpi_detail['a7'];
                }
                if (isset($kpi_detail['nrr1'])) {
                    $t1[$game_code][$t_date]['nrr1'][] = $kpi_detail['nrr1'];
                }
                if (isset($kpi_detail['nrr7'])) {
                    $t1[$game_code][$t_date]['nrr7'][] = $kpi_detail['nrr7'];
                }
            }
        }
        foreach ($t1 as $game_code => $detail) {
            foreach ($detail as $day => $kpi_detail) {

                $data[$game_code][$day]['avg_a1'] = 0;
                if (count($t1[$game_code][$day]['a1']) != 0) {
                    $data[$game_code][$day]['avg_a1'] = round(array_sum($t1[$game_code][$day]['a1']) / count($t1[$game_code][$day]['a1']));
                }

                $data[$game_code][$day]['avg_a7'] = 0;
                if (count($t1[$game_code][$day]['a7']) != 0) {
                    $data[$game_code][$day]['avg_a7'] = round(array_sum($t1[$game_code][$day]['a7']) / count($t1[$game_code][$day]['a7']));
                }

                $data[$game_code][$day]['avg_nrr1'] = 0;
                if (count($t1[$game_code][$day]['nrr1']) != 0) {
                    $_v = round(array_sum($t1[$game_code][$day]['nrr1']) / count($t1[$game_code][$day]['nrr1']));
                    if($_v <= 1) $_v = 0;
                    $data[$game_code][$day]['avg_nrr1'] = $_v;
                }

                $data[$game_code][$day]['avg_nrr7'] = 0;
                if (count($t1[$game_code][$day]['nrr7']) != 0) {
                    $_v = round(array_sum($t1[$game_code][$day]['nrr7']) / count($t1[$game_code][$day]['nrr7']));
                    if($_v <=1) $_v =0;
                    $data[$game_code][$day]['avg_nrr7'] = $_v;
                }
            }
        }

        return $data;
    }

    /*
     * monthly:
     *  am
     *  nm
     *  sum rr1 / count rr1 of one month
     *  sum rr7 / count rr7 of one month
     *  pum
     *  grm
     */
    private function get_display_data_monthly($data)
    {
        $end_of_month_date = date('Y-m-d', strtotime('last day of previous month'));
        $begin_of_month_date = date('Y-m-01', strtotime($end_of_month_date));
        $log_month_pre = date('Y-m-t', strtotime('last day of previous month'));
        /*$end_of_month_date = '2016-09-30';
        $begin_of_month_date= '2016-09-30';
        $log_month_pre = '2016-09-30';*/


        $am_total['local'] = 0;
        $am_total['global'] = 0;

        $nm_total['local'] = 0;
        $nm_total['global'] = 0;

        $pum_total['local'] = 0;
        $pum_total['global'] = 0;

        $grm_total['local'] = 0;
        $grm_total['global'] = 0;

        $a30['local'] = 0;
        $a30['global'] = 0;

        foreach ($data as $game_code => $detail) {
            $region = $data[$game_code]['region'];
            if (isset($detail[$end_of_month_date]['am'])) {
                $am_total[$region] += $detail[$end_of_month_date]['am'];
            }
            if (isset($detail[$end_of_month_date]['nm'])) {
                $nm_total[$region] += $detail[$end_of_month_date]['nm'];
            }
            if (isset($detail[$end_of_month_date]['pum'])) {
                $pum_total[$region] += $detail[$end_of_month_date]['pum'];
            }
            if (isset($detail[$end_of_month_date]['grm'])) {
                $grm_total[$region] += $detail[$end_of_month_date]['grm'];
            }
            if (isset($detail[$end_of_month_date]['a30'])) {
                $a30[$region] += $detail[$end_of_month_date]['a30'];
            }
        }

        $am_total['total'] = $am_total['local'] + $am_total['global'];
        $nm_total['total'] = $nm_total['local'] + $nm_total['global'];
        $pum_total['total'] = $pum_total['local'] + $pum_total['global'];
        $grm_total['total'] = $grm_total['local'] + $grm_total['global'];
        $a30['total'] = $a30['local'] + $a30['global'];

        $a1 = array();
        $n1 = array();
        foreach ($data as $game_code => $detail) {
            foreach ($detail as $day => $kpi_detail) {
                if ($day >= $begin_of_month_date and $day <= $end_of_month_date) {
                    $log_month = date("Y-m-t", strtotime($day));
                    if (isset($kpi_detail['a1'])) {
                        $a1[$game_code][$log_month][] = $kpi_detail['a1'];
                    }
                    if (isset($kpi_detail['n1'])) {
                        $n1[$game_code][$log_month][] = $kpi_detail['n1'];
                    }
                }
            }
        }

        $avg_a1['local'] = 0;
        $avg_a1['global'] = 0;
        $avg_a1['total'] = 0;
        foreach ($a1 as $game_code => $detail) {
            $region = $data[$game_code]['region'];
            $avg = array_sum($a1[$game_code][$log_month_pre]) / count($a1[$game_code][$log_month_pre]);
            $avg_a1[$region] += $avg;
            $avg_a1['total'] += $avg;
        }
        $summary['am'] = $am_total;
        $summary['nm'] = $nm_total;
        $summary['pum'] = $pum_total;
        $summary['grm'] = $grm_total;
        $summary['a30'] = $a30;
        $summary['avg_a1'] = $avg_a1;

        //top
        $detail_games = array();
        foreach ($data as $game_code => $detail) {
            if (isset($detail[$end_of_month_date]['am'])) {
                $detail_games[$game_code]['am'] = $detail[$end_of_month_date]['am'];
            }
            if (isset($detail[$end_of_month_date]['nm'])) {
                $detail_games[$game_code]['nm'] = $detail[$end_of_month_date]['nm'];
            }
            if (isset($detail[$end_of_month_date]['pum'])) {
                $detail_games[$game_code]['pum'] = $detail[$end_of_month_date]['pum'];
            }
            if (isset($detail[$end_of_month_date]['grm'])) {
                $detail_games[$game_code]['grm'] = $detail[$end_of_month_date]['grm'];
            }
            if (isset($detail[$end_of_month_date]['a30'])) {
                $detail_games[$game_code]['a30'] = $detail[$end_of_month_date]['a30'];
            }
        }

        foreach ($a1 as $game_code => $detail) {
            $detail_games[$game_code]['avg_a1'] = round(array_sum($a1[$game_code][$log_month_pre]) / count($a1[$game_code][$log_month_pre]));
        }
        $return['summary'] = $summary;
        $return['detail']['data'] = $detail_games;
        $return['detail']['game_info'] = $data;
        return $return;
    }

    public function calc_summary($kpi_id_arr, $data)
    {
        $summary_data = array();
        foreach ($kpi_id_arr as $kpi_id => $kpi_name) {
            $summary_data[$kpi_id]['total'] = 0;
            $summary_data[$kpi_id]['local'] = 0;
            $summary_data[$kpi_id]['global'] = 0;
            foreach ($data as $game_code => $data_detail) {
                if (isset($data_detail[$kpi_id])) {
                    $region = $data[$game_code]['region'];
                    $summary_data[$kpi_id][$region] += $data_detail[$kpi_id];
                    $summary_data[$kpi_id]['total'] += $data_detail[$kpi_id];
                }
            }

        }
        return $summary_data;
    }
    /*public function test(){
        $one_day_ago = '2016-10-15';
        $numday_report = 41;
        $game_not_require = array("10ha7ifbs1","cfgfbs1","cgmbgfbs1","ftgfbs2");
        $game_in_order = array("jxm", "myplay", "zingplay_card", "myplay_board", "myplayinternational", "3qmobile", "cack", "gnm", "icamfbs2", "tlbbm",
            "siamplay", "cgmfbs", "stct", "nikki", "dptk", "ctpgsn", "wefight", "tfzfbs2", "cgmbgfbs1", "dttk",
            "coccmgsn", "bklr", "sfmgsn", "coccgsn", "stonysea", "pv3d", "dcc",
            "pmcl", "cfgfbs1", "ftgfbs2", "10ha7ifbs1", "htc", "adsads", "stonyvn");

        $this->monthly($one_day_ago,$numday_report,$game_in_order,$game_not_require);
    }*/

}