<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 04/07/2016
 * Time: 14:29
 */

class EmailReport extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //$this->output->enable_profiler(TRUE);
        $this->load->library('email');
        $this->load->library('util');
        $this->load->model ('emailreport_model', 'emailreport');
        $this->load->model('qaissuedgame_model', 'qaissuedgame');
        $this->load->library('form_validation');
        $this->load->library('util');
    }

    private function get_require_kpi_daily($game_code){
        $default_list = array("a1", "n1", "gr1", "pu1");
        /*
        if($game_code=="360live"){
        	$default_list = array("a1","n1");
        	return $default_list;
        }*/
        return $default_list;
    }
    private function get_optional_kpi_daily($game_code){
        $default_list = array("npu1", "nrr1", "cr1", "arpu1", "arppu1", "cvr1","acu1","pcu1");

        return $default_list;
    }
    private function get_attach_kpi_daily($game_code){
        $default_list = array("a7", "n7", "n30", "pu7",
            "pu30","npu7","npu30",
            "grw","grm");

        return $default_list;
    }
    private function get_calc_kpi_daily($game_code){
        $arr = array("cvr1", "arppu1");
        return $arr;
    }
    private function get_extra_kpi_daily($game_code){
        $arr = array("rr1");
        return $arr;
    }

    private function get_require_kpi_monthly($game_code){
        $arr = array("am", "nm", "grm", "pum");
        return $arr;
    }
    private function get_optional_kpi_monthly($game_code){
        $arr = array("npum");
        return $arr;
    }
    private function get_attach_kpi_monthly($game_code)
    {
        $arr = array("a7", "a30",  "nrr7", "nrr30", "cr30", "arpu7", "arpu30", "arppu7", "arppu30");
        return $arr;
    }
    private function get_calc_kpi_monthly($game_code){
        $arr = array("cr30", "arpu30", "arppu30");
        return $arr;
    }
    private function get_extra_kpi_monthly($game_code){
        $arr = array("a7", "a30");
        return $arr;
    }

    private function get_monitor_list_daily($game_code){
        $arr['require'] = $this->get_require_kpi_daily($game_code);
        $arr['optional'] = $this->get_optional_kpi_daily($game_code);
        $arr['attach'] = $this->get_attach_kpi_daily($game_code);
        $arr['calc'] = $this->get_calc_kpi_daily($game_code);
        $arr['extra'] = $this->get_extra_kpi_daily($game_code);
        return $arr;
    }

    private function get_monitor_list_monthly($game_code){
        $arr['require'] = $this->get_require_kpi_monthly($game_code);
        $arr['optional'] = $this->get_optional_kpi_monthly($game_code);
        $arr['attach'] = $this->get_attach_kpi_monthly($game_code);
        $arr['calc'] = $this->get_calc_kpi_monthly($game_code);
        $arr['extra'] = $this->get_extra_kpi_monthly($game_code);
        return $arr;
    }

    public function index($timing)
    {

        $pre='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

</head>
<body>';

        $suf = '</body>
</html>';
        if($timing == ""){
            $timing = 1;
        }
        define("TIMING", $timing);
        define("HOME_DIR", "/home/tuonglv/mail");
        $today = date("Y-m-d");
        $one_day_ago = date("Y-m-d", strtotime($today) - 24*60*60);
        if (!file_exists(HOME_DIR . "/content/" . $one_day_ago)) {
            mkdir(HOME_DIR . "/content/" . $one_day_ago);
        }
            if (!file_exists(HOME_DIR . "/sent/" . $one_day_ago)) {
                mkdir(HOME_DIR . "/sent/" . $one_day_ago);
            }
            if (!file_exists(HOME_DIR . "/wait/" . $one_day_ago)) {
                mkdir(HOME_DIR . "/wait/" . $one_day_ago);
            }

        $system_email_address = "kpi.stats@vng.com.vn";
        $operator_email_address = array("canhtq@vng.com.vn", "quangctn@vng.com.vn");
        //$operator_email_address = array("lamnt6@vng.com.vn");
        $kpi_report_alias = "KPI Report Tool";

        $kpi_lists = array(
            "a1" => array("kpi_id" => "10001", "kpi_description" => "Active User"),
            "a7" => array("kpi_id" => "10007", "kpi_description" => "Active User"),
            "a30" => array("kpi_id" => "10030", "kpi_description" => "Active User"),
            "am" => array("kpi_id" => "10031", "kpi_description" => "Active User"),
            "n1" => array("kpi_id" => "11001", "kpi_description" => "New Login (Register) User"),
            "n7" => array("kpi_id" => "11007", "kpi_description" => "New Login (Register) User"),
            "n30" => array("kpi_id" => "11030", "kpi_description" => "New Login (Register) User"),
            "nm" => array("kpi_id" => "11031", "kpi_description" => "New Login (Register) User"),
            "pu1" => array("kpi_id" => "15001", "kpi_description" => "Paying User"),
            "pu7" => array("kpi_id" => "15007", "kpi_description" => "Paying User"),
            "pu30" => array("kpi_id" => "15030", "kpi_description" => "Paying User"),
            "pum" => array("kpi_id" => "15031", "kpi_description" => "Paying User"),
            "gr1" => array("kpi_id" => "16001", "kpi_description" => "Revenue"),
            "gr7" => array("kpi_id" => "16007", "kpi_description" => "Revenue"),
            "grw" => array("kpi_id" => "16017", "kpi_description" => "Revenue"),
            "gr30" => array("kpi_id" => "16030", "kpi_description" => "Revenue"),
            "grm" => array("kpi_id" => "16031", "kpi_description" => "Revenue"),
            "npu1" => array("kpi_id" => "19001", "kpi_description" => "First charge User"),
            "npu7" => array("kpi_id" => "19007", "kpi_description" => "First charge User"),
            "npu30" => array("kpi_id" => "19030", "kpi_description" => "First charge User"),
            "npum" => array("kpi_id" => "19031", "kpi_description" => "First charge User"),
            "npu_gr1" => array("kpi_id" => "20001", "kpi_description" => "Revenue of First Charge User"),
            "npu_gr7" => array("kpi_id" => "20007", "kpi_description" => "Revenue of First Charge User"),
            "npu_gr30" => array("kpi_id" => "20030", "kpi_description" => "Revenue of First Charge User"),
            "npu_grm" => array("kpi_id" => "20031", "kpi_description" => "Revenue of First Charge User"),
            "nnpu1" => array("kpi_id" => "25001", "kpi_description" => "New Login & First Charge User"),
            "nnpu7" => array("kpi_id" => "25007", "kpi_description" => "new and paid user"),
            "nnpu30" => array("kpi_id" => "25030", "kpi_description" => "new and paid user"),
            "nnpum" => array("kpi_id" => "25031", "kpi_description" => "new and paid user"),
            "nnpu_gr1" => array("kpi_id" => "26001", "kpi_description" => "Revenue of NNPU1"),
            "nnpu_gr7" => array("kpi_id" => "26007", "kpi_description" => "gross revenue of nnpu"),
            "nnpu_gr30" => array("kpi_id" => "26030", "kpi_description" => "gross revenue of nnpu"),
            "nnpu_grm" => array("kpi_id" => "26031", "kpi_description" => "gross revenue of nnpu"),
            "cr1" => array("kpi_id", "kpi_description" => "Churn Rate"),
            "cr7" => array("kpi_id", "kpi_description" => "Churn Rate"),
            "cr30" => array("kpi_id", "kpi_description" => "Churn Rate"),
            "nrr1" => array("kpi_id" => "28001", "kpi_description" => "New User Retention Rate"),
            "nrr7" => array("kpi_id" => "28007", "kpi_description" => "New User Retention Rate"),
            "nrr30" => array("kpi_id" => "28030", "kpi_description" => "New User Retention Rate"),
            "nrrm" => array("kpi_id" => "28031", "kpi_description" => "New User Retention Rate"),
            "arppu1" => array("kpi_id", "kpi_description" => "Average Revenue Per Paying User"),
            "arppu7" => array("kpi_id", "kpi_description" => "Average Revenue Per Paying User"),
            "arppu30" => array("kpi_id", "kpi_description" => "Average Revenue Per Paying User"),
            "arpu1" => array("kpi_id", "kpi_description" => "Average Revenue Per User"),
            "arpu7" => array("kpi_id", "kpi_description" => "Average Revenue Per User"),
            "arpu30" => array("kpi_id", "kpi_description" => "Average Revenue Per User"),
            "cvr1" => array("kpi_id", "kpi_description" => "Convertion Rate"),
            "cvr7" => array("kpi_id", "kpi_description" => "Convertion Rate"),
            "cvr30" => array("kpi_id", "kpi_description" => "Convertion Rate"),
            "acu1" => array("kpi_id" => "30001", "kpi_description" => " Average of concurrent users"),
            "pcu1" => array("kpi_id" => "31001", "kpi_description" => "Peak concurrent users"),
        );

        //reserve list
        $kpi_id_code=array();
        foreach($kpi_lists as $_kpi_code => $_value){
            if($_value['kpi_id']!="")
                $kpi_id_code[$_value['kpi_id']] = $_kpi_code;
        }

        $data = array();
        $miss_games = array();
        $all_games = array();
        $game_monitor_list=array();
        $game_ready = array();
        $game_not_ready = array();
        $all_user = array();
        $done_user = array();
        $to_date = $one_day_ago;
        $issuedGames = $this->qaissuedgame->getGames($one_day_ago);

        $all_done = $this->get_done_path("all", $to_date);
        if(file_exists($all_done)){
            echo "nothing to do\n";
            return;
        }

        if(TIMING=="31"){
            $day_arr = $this->get_need_month($to_date);
        }else{
            $day_arr = $this->get_need_day($to_date);
        }

        $email_send = array();
        $recipients_arr = array();


        $user_list = $this->emailreport->get_user_list();

        for($p=0;$p<count($user_list);$p++){
            $row = $user_list[$p];
            $user_name = strtolower($row['username']);
            $game_code = $row['GameCode'];
            $game_name = $row['GameName'];

            if(!isset($all_games[$user_name]) || !in_array($game_code, $all_games[$user_name])) $all_games[$user_name][$game_code] = $game_name;
            if(!isset($all_user) || !in_array($user_name, $all_user)) $all_user[] = $user_name;
            if(!isset($game_not_ready[$user_name])) $game_not_ready[$user_name] = array();
            if(!isset($game_ready[$user_name])) $game_ready[$user_name] = array();

            $is_sent = $this->check_sent($user_name, $to_date);
            if($is_sent==1){ // done, do nothing
                if(!in_array($user_name, $done_user)){
                    echo $user_name . " have received all games belong to her/him\n";
                    $done_user[] = $user_name;
                }
                continue;
            }

            if($is_sent != ""){
                $miss_games[$user_name] = explode(",", $is_sent);
            }

            if(TIMING == "31"){
                $_monitor_list = $this->get_monitor_list_monthly($game_code);
            }else {
                $_monitor_list = $this->get_monitor_list_daily($game_code);
            }

            //transform to two dimision array
            $new_list =array();
            foreach($_monitor_list as $t => $v){
                for($i=0;$i<count($v);$i++){
                    $new_list[$t][$v[$i]] = "";
                }
            }
            $_monitor_list = $new_list;
            //end transform

            //$ready = $this->emailreport->check_data_ready($game_code, $_monitor_list['require'], $to_date, $kpi_lists);
            //canhtq fix
            $ready=true;
            foreach($issuedGames as $t => $v){
                if($v["game_code"]==$game_code){
                    $ready=false;
                    break;
                }
            }
            //end canhtq fix
            //check data ready
            if($ready){
                $game_monitor_list[$game_code] = $_monitor_list;
                $_monitor_list = array_merge($_monitor_list['require'], $_monitor_list['attach'], $_monitor_list['optional'], $_monitor_list['extra']);
                $kpi_value_arr = $this->emailreport->get_kpi_value($game_code, $_monitor_list, $day_arr, $kpi_lists, $kpi_id_code);
                $data[$user_name][$game_code] = $kpi_value_arr;
                $game_ready[$user_name][] = $game_code;
            }else{
                $game_not_ready[$user_name][] = $game_code;
            }
        }

        if(count($done_user) == count($all_user)){
            //do nothing
            echo "nothing need todo\n";
           // return;
        }
        $data = $this->calculate_another_kpi($game_code, $data, $this->get_calc_kpi_daily(""), $day_arr);
        $html_alert_nothing_change = "";
        $html_alert_not_enough_report = "";

        foreach($all_user as $user_name){
            if(count($done_user) != 0 && in_array($user_name, $done_user)){
                continue;
            }
            $send = true;
            $value = isset($data[$user_name]) ? $data[$user_name] : null;
            $update_info = "";
            if(isset($miss_games[$user_name]) && count($miss_games[$user_name]) != 0){
                $update_info = "- <b>Updated games:</b> ";
                $_flag=false;
                for($i=0;$i<count($miss_games[$user_name]); $i++){
                    $_miss_game = $miss_games[$user_name][$i];
                    if(in_array($_miss_game, $game_ready[$user_name])){
                        $_flag = true;
                        $update_info .= $all_games[$user_name][$_miss_game] . ", ";
                    }
                }
                $update_info = substr($update_info,0,-2) . "<br>";
                if($_flag == false){
                    //sau khi chay lai, ko co game nao dc update, thi ko can gui mail
                    $update_info = '';
                    if($html_alert_nothing_change != "")
                        $html_alert_nothing_change .= "<br>-----------------------<br>";

                    $html_alert_nothing_change .= $this->nothing_change_when_rerun($user_name, $to_date, $miss_games[$user_name]);

                    if(!file_exists($this->get_wait_path($user_name, $to_date))){
                        continue;
                    }
                }
            }

            $intro = "";

            if(!isset($game_not_ready[$user_name]) || count($game_not_ready[$user_name]) == 0){
                $intro .= "- <b>Game list:</b> ";
                foreach($all_games[$user_name] as $_game_code => $_game_name){
                    $intro .= $_game_name . ", ";
                }
                $intro = substr($intro, 0, -2) . "<br>";
                $this->set_done_user($user_name, $to_date);
            }else {
                $intro = "- Missing games: ";
                for ($i = 0; $i < count($game_not_ready[$user_name]); $i++) {
                    $intro .= $all_games[$user_name][$game_not_ready[$user_name][$i]] . ", ";
                }
                $intro = substr($intro, 0, -2);
                $intro .= "<br>";
                $this->set_miss_game($user_name, $game_not_ready[$user_name], $to_date);
                if ($html_alert_not_enough_report != "")
                    $html_alert_not_enough_report .= "<br>---------------------<br>";
                $html_alert_not_enough_report .= $this->game_have_not_enough_report($user_name, $game_not_ready[$user_name], $game_ready[$user_name], $to_date);

                $w_path = $this->get_wait_path($user_name, $to_date);
                if (file_exists($w_path)) {
                    $send = true;
                    unlink($w_path);
                } else {
                    if (count($game_ready[$user_name]) != 0) {
                        touch($w_path);
                    }
                    $send = false;
                }
            }

            if($send === true) {
                $data_table_arr = $this->report_type_1($value, $game_monitor_list, $day_arr, $all_games[$user_name], $kpi_lists);
                $data_table = $data_table_arr[0];
                $data_table_in_mail = $data_table_arr[1];

                $more_info = "To view all kpi report, please visit <a href='https://kpi.stats.vng.com.vn/index.php/dashboard?from=email'>KPI Tool</a><br>";

                $html = $pre .
                    "Dear Sir/Madam, <br><br>" .
                    "We send you daily report for <b>" . $to_date .  "</b>. <br>" .
                    $intro .
                    $update_info .
                    $data_table .
                    $more_info . "<br>" .
                    "Regards, <br> KPI Report Team - STATS/TEG/GE.<br>" .
                    $suf;
                $content_path = $this->get_content_path($user_name, $to_date);
                $attach_path = $this->get_attach_path($user_name, $to_date);
                file_put_contents($content_path, $html);
                file_put_contents($attach_path, $data_table_in_mail);

                $mail_config = array(
                    "from" => $system_email_address,
                    "fromalias" => $kpi_report_alias,
                    "to" => $user_name . '@vng.com.vn',
                    //"to" => 'tuonglv' . '@vng.com.vn',
                    "subject" => "KPI Daily report - $to_date",
                    "message" => file_get_contents($content_path),
                    "attach" => array($attach_path)
                );
                if($recipients_arr == null || !in_array($recipients_arr, $user_name))
                    $recipients_arr[] = $user_name;
                $email_send[] = $mail_config;
            }

        }

        $sent_mail_status = $this->sent_report_status($to_date, $all_user, $all_games);
        $sent_html = $sent_mail_status['html'];
        $miss = $sent_mail_status['miss'];
        if($miss == false){
            $this->set_done_user("all", $to_date);
        }
        $sent_status = false;
        if($html_alert_not_enough_report){
            $mail_config = array(
                "from" => $system_email_address,
                "fromalias" => $kpi_report_alias,
                "to" => $operator_email_address,
                "subject" => "[KPI-Alert]Games have not enough report",
                "message" => $html_alert_not_enough_report . "<br>----------------------<br>" .  $sent_html,
                "attach" => null,
            );
            $email_send[] = $mail_config;
            $sent_status = true;
        }else if($html_alert_nothing_change!=""){
            $mail_config = array(
                "from" => $system_email_address,
                "fromalias" => $kpi_report_alias,
                "to" => $operator_email_address,
                "subject" => "[KPI-Alert]Still not enough report",
                "message" => $html_alert_nothing_change . "<br>----------------------<br>" .  $sent_html,
                "attach" => null,
            );
            $email_send[] = $mail_config;
            $sent_status = true;
        }

        if(!$sent_status){
            $mail_config = array(
                "from" => $system_email_address,
                "fromalias" => $kpi_report_alias,
                "to" => $operator_email_address,
                "subject" => "[KPI-Alert]Sent mail report status",
                "message" => $sent_html,
                "attach" => null,
            );
            $email_send[] = $mail_config;
        }

        for($ii=0;$ii<count($email_send);$ii++){
            $_mail_config = $email_send[$ii];
            $this->util->send_mail($_mail_config);
        }

        $this->send_to_opearator($recipients_arr, $to_date, $system_email_address, $kpi_report_alias, $operator_email_address);

    }

    private function send_to_opearator($recipients_arr, $to_date, $system_email_address, $kpi_report_alias, $operator_email_address){
        $html_arr = array();
        for($i=0;$i<count($recipients_arr);$i++){
            $user_name = $recipients_arr[$i];
            $f = $this->get_content_path($user_name, $to_date);
            $md5 = md5_file($f);
            $html_arr[$md5][] = $user_name;
        }

        foreach($html_arr as $md5 => $user_names){
            $html = "This is the email has sent to: ";
            $random = "";
            for($i=0;$i<count($user_names);$i++){
                $html .= $user_names[$i] . ", ";
                $random = $user_names[$i];
            }
            $html = substr($html,0,-2);
            $html .= "<br>----------------------------<br>";

            $_t = $this->get_content_path($random, $to_date);
            $html .= file_get_contents($_t);

            $mail_config = array(
                "from" => $system_email_address,
                "fromalias" => $kpi_report_alias,
                "to" => $operator_email_address,
                "subject" => "Sample KPI Daily report - $to_date",
                "message" => $html
            );
            $this->util->send_mail($mail_config);
        }
    }

    private function nothing_change_when_rerun($user_name, $to_date, $miss_game){
        $html = "";
        $html .= "Report date: " . $to_date . ".<br>";
        $html .= "Recipient: " . $user_name . ".<br>";
        $html .= "Games need update: ";
        for($i=0;$i<count($miss_game);$i++){
            $html .= $miss_game[$i] . ", ";
        }
        $html = substr($html,0,-2);
        return $html;
    }

    private function game_have_not_enough_report($user_name, $game_not_ready, $game_ready, $to_date)
    {
        $html = "";
        $html .= "Report date: " . $to_date . ".<br>";
        $html .= "Recipient: " . $user_name . ".<br>";
        $html .= "Game have not enough kpi report: ";
        for ($i = 0; $i < count($game_not_ready); $i++) {
            $html .= $game_not_ready[$i] . ", ";
        }
        $html = substr($html, 0, -2) . ".<br>";

        if (isset($game_ready) && count($game_ready) != 0) {
            $html .= "Game have enough kpi report: ";
            for ($i = 0; $i < count($game_ready); $i++) {
                $html .= $game_ready[$i] . ", ";
            }
            $html = substr($html, 0, -2) . ".<br>";
            $html .= "<br>";

            $w_path = $this->get_wait_path($user_name, $to_date);
            if(file_exists($w_path)){
                $html .= "<b>The report email has sent to product owner.</b><br>";
            }else{
                $html .= "<b>The report email will be sent to product owner after 30 minutes.</b> <br>";
                $api_url = "https://kpi.stats.vng.com.vn/application/mail_handle.php";
                $now = time();
                $key = $this->generate_key($now);
                $param1 = "?username=$user_name&report_date=$to_date&type=sendnow&time=$now&key=$key";
                $param2 = "?username=$user_name&report_date=$to_date&type=pause&time=$now&key=$key";
                $send_now_url = $api_url . $param1;
                $pause_url = $api_url . $param2;
                //$html .= "You can send that email immediately by click link: $send_now_url<br>";
                //$html .= "You can stop temporary by click link: $pause_url<br>";
                //$html .= "Please check log and run email report by manual: <i>php /var/www/kpiweb/application/afirst/mail_report.php </i> <br>";
            }
        }

        return $html;

    }
    private function generate_key($time){
        $_key = "1dsa923ducnqwue#23";
        $key = md5($_key . $time);
        return  $key;
    }

    private function get_wait_path($user_name, $to_date)
    {
        return HOME_DIR . "/wait/" . $to_date . "/" . $user_name . ".txt";
    }

    private function get_done_path($user_name, $to_date){
        return HOME_DIR . "/sent/" . $to_date . "/" . $user_name . "_done.txt";
    }

    private function get_miss_path($user_name, $to_date){
        return HOME_DIR . "/sent/" . $to_date . "/" . $user_name . "_miss.txt";
    }

    private function get_content_path($user_name, $to_date){
        return HOME_DIR . "/content/" . $to_date . "/" . $user_name . "." . $to_date . ".html";
    }

    private function get_attach_path($user_name, $to_date){
        return HOME_DIR . "/content/" . $to_date . "/kpi.stats.com.vng.vn-daily-kpi-report." . $user_name . "." . $to_date . ".xls";
    }

    private function sent_report_status($to_date, $alluser, $all_games)
    {
        $html = "Sent mail report status: <br><br>";
        $html .= '<table border="1" cellpadding="1" cellspacing="0" style="table-layout: fixed;">';
        $html .= '<tr align="center" style="background-color:#ddf2f2">';
        $html .= '<th>User</th>';
        $html .= '<th>Report Date</th>';
        $html .= '<th>Status</th>';
        $html .= '<th>Total game</th>';
        $html .= "<th>Last Update</th>";
        $html .= '</tr>';

        $miss = false;
        for ($i = 0; $i < count($alluser); $i++) {
            $html .= '<tr>';
            $user_name = $alluser[$i];
            $html .= '<td>' . $user_name . '</td>';
            $html .= '<td>' . $to_date . '</td>';

            $_done = $this->get_done_path($user_name, $to_date);
            $_miss = $this->get_miss_path($user_name, $to_date);
            $game_number = count($all_games[$user_name]);
            if (file_exists($_done)) {
                $html .= "<td>Done</td>";
                $last_update = date("Y-m-d H:i:s", filemtime($_done));
            } else if (file_exists($_miss)) {
                $miss = true;
                $_miss_content = file_get_contents($_miss);
                $html .= "<td>Miss games: " . $_miss_content . "</td>";
                $last_update = date("Y-m-d H:i:s", filemtime($_miss));
            } else {
                $miss = true;
                $html .= "<td>N/A</td>";
                $last_update = "N/A";
            }
            $html .= "<td> $game_number </td>";
            $html .= "<td> $last_update </td>";
            $html .= '</tr>';
        }
        $html .= '</table>';
        return array("miss" => $miss, "html" => $html);
    }

    private function get_header_table_daily($date_1, $date_2, $date_3, $date_4)
    {
        $header = "<tr align='center' style='background-color:#ddf2f2'>
        <th rowspan='2' width='30' style='font-size: small'>Game</th>
        <th rowspan='2'>KPI</th>
        <th rowspan='2'> Yesterday <br>(" . date("d-M-Y", strtotime($date_1)) . ")</th>
        <th rowspan='2'> Y-1Day <br>(" . date("d-M-Y", strtotime($date_2)) . ")</th>
        <th rowspan='2'> Y-7Days <br> (" . date("d-M-Y", strtotime($date_3)) . ")</th>
        <th rowspan='2'> Y-30Days <br>(" . date("d-M-Y", strtotime($date_4)) . ")</th>
        <th colspan='3'> Compare</th>
        <th rowspan='2'>KPI Description</th>
    </tr>
    <tr align='center' style='background-color:#ddf2f2'>
        <th>Yesterday /<br>Y-1Day</th>
        <th>Yesterday /<br>Y-7Days</th>
        <th>Yesterday /<br>Y-30Days</th>
    </tr>";

        return $header;
    }
    private function get_header_table_monthly($date_1, $date_2, $date_3, $date_4)
    {

        $m1 = date("M", strtotime($date_1));
        $m2 = date("M", strtotime($date_2));
        $m3 = date("M", strtotime($date_3));
        $m4 = date("M", strtotime($date_4));
        $header = "<tr align='center' style='background-color:#ddf2f2'>
        <th rowspan='2' width='30' style='font-size: small'>Game</th>
        <th rowspan='2'>KPI</th>
        <th rowspan='2'> " . $m1 . " <br>(" . date("d-M-Y", strtotime($date_1)) . ")</th>
        <th rowspan='2'> " . $m2 . " <br>(" . date("d-M-Y", strtotime($date_2)) . ")</th>
        <th rowspan='2'> " . $m3 . " <br> (" . date("d-M-Y", strtotime($date_3)) . ")</th>
        <th rowspan='2'> " . $m4 . " <br>(" . date("d-M-Y", strtotime($date_4)) . ")</th>
        <th colspan='3'> Compare</th>
        <th rowspan='2'>KPI Description</th>
    </tr>
    <tr align='center' style='background-color:#ddf2f2'>
        <th>" . $m1 . " /<br>" . $m2 . "</th>
        <th>" . $m1 . " /<br>" . $m3 . "</th>
        <th>" . $m1 . " /<br>" . $m4 . "</th>
    </tr>";

        return $header;
    }

    private function report_type_1($data, $game_monitor_list, $day_arr, $all_games, $kpi_lists){
        $new_line = "\n";
        $html = "";
        $html .= '<table border="1" cellpadding="1" cellspacing="0">'.$new_line;
        $html .= "<tr style='text-align: center; font-weight: bold; background: #ccccff;'>$new_line";
        $date_1 = $day_arr[0];
        $date_2 = $day_arr[1];
        $date_3 = $day_arr[3];
        $date_4 = $day_arr[5];
        $table_header_config = $this->util->get_kpi_header_name();

        if(TIMING == "1"){
            $html .= $this->get_header_table_daily($date_1, $date_2, $date_3, $date_4);
        }else if (TIMING == "31"){
            $html .= $this->get_header_table_monthly($date_1, $date_2, $date_3, $date_4);
        }

        $html_in_mail = $html;

        $_all_games = array_keys($all_games);
        sort($_all_games);
        for($k=0;$k<count($_all_games);$k++){
            $game_code = $_all_games[$k];
            if(isset($data[$game_code])){
                $value = $data[$game_code];

                $t = array("require", "optional", "attach");
                $child_require = "";
                $child_in_mail = "";
                $rows_span_require = 0;
                $rows_span_in_mail = 0;
                for($i_t = 0 ; $i_t < count($t); $i_t ++) {
                    $tt = $t[$i_t];
                    $monitor_list = $game_monitor_list[$game_code][$tt];
                    $monitor_list = array_keys($monitor_list);

                    for ($i = 0; $i < count($monitor_list); $i++) {
                        $kpi_code = $monitor_list[$i];

                        $compare_1 = $this->calc_compare_number($value[$kpi_code][$date_1], $value[$kpi_code][$date_2]);
                        $compare_2 = $this->calc_compare_number($value[$kpi_code][$date_1], $value[$kpi_code][$date_3]);
                        $compare_3 = $this->calc_compare_number($value[$kpi_code][$date_1], $value[$kpi_code][$date_4]);

                        $color_1 = $this->get_color_tren($compare_1);
                        $color_2 = $this->get_color_tren($compare_2);
                        $color_3 = $this->get_color_tren($compare_3);

                        $kpi_code_alias = $kpi_code;
                        if (isset($table_header_config[$kpi_code])) {
                            $kpi_code_alias = $table_header_config[$kpi_code];
                        }

                        $child =
                            "<tr align='right'>
                <td align='left'> " . ($kpi_code_alias) . "</td>
                <td> " . $this->ub_format_number($value[$kpi_code][$date_1],$kpi_code) . "</td>
                <td> " . $this->ub_format_number($value[$kpi_code][$date_2],$kpi_code) . "</td>
                <td> " . $this->ub_format_number($value[$kpi_code][$date_3],$kpi_code) . "</td>
                <td> " . $this->ub_format_number($value[$kpi_code][$date_4],$kpi_code) . "</td>
                <td style = 'color: " . $color_1 . "'> " . $compare_1 . "% </td>
                <td style = 'color: " . $color_2 . "'> " . $compare_2 . "% </td>
                <td style = 'color: " . $color_3 . "'> " . $compare_3 . "% </td>
                <td align='left'> " . $kpi_lists[$kpi_code]['kpi_description'] . " </td>
            </tr>";

                        if ($tt == "require") {
                            $child_in_mail .= $child;
                            $child_require .= $child;
                            $rows_span_in_mail++;
                            $rows_span_require++;
                        } else if ($tt == "optional" && array_sum($value[$kpi_code]) != 0) {
                            $child_require .= $child;
                            $child_in_mail .= $child;
                            $rows_span_in_mail++;
                            $rows_span_require++;
                        } else if ($tt == "attach") {
                            $child_in_mail .= $child;
                            $rows_span_in_mail++;
                        }
                    }
                }
                //add here
                $html .= "<tr align='left' style='font-weight:bold;font-size:13pt'>
                      <td rowspan='" . ($rows_span_require + 1) . "' align='left' style='background-color:#fff'>" . $all_games[$game_code] . "<br>(" . strtoupper($game_code) . ")</td>
                  </tr>";
                $html .= $child_require;

                $html_in_mail .= "<tr align='left' style='font-weight:bold;font-size:13pt'>
                      <td rowspan='" . ($rows_span_in_mail + 1) . "' align='left' style='background-color:#fff'>" . $all_games[$game_code] . "<br>(" . strtoupper($game_code) . ")</td>
                  </tr>";
                $html_in_mail .= $child_in_mail;

            }else{
                $update_later = "<tr align='left'>
                      <td rowspan='1' align='left' style='font-weight:bold;font-size:13pt'>" . $all_games[$game_code] . "<br>(" . strtoupper($game_code) . ")</td>
                      <td colspan='8' align='center'>Update later</td>
                  </tr>";

                $html .=$update_later;
                $html_in_mail .= $update_later;
            }
        }

        $html .= "</table>";
        return array($html, $html_in_mail);
    }

    /**
     * cat /tmp/mail_content.txt | mutt -e 'set smtp_url=smtp://10.30.76.11' -e 'set content_type=text/html' -e 'set from="lamnt6@vng.com.vn"' -e 'set realname="UB Zabbix"' -s "$subject" $recipients
     */
    /*
    private function send_email($message,$from, $to, $subject, $attach = null){
        $time = time();
        $tmp_path = "/tmp/mail." . $time;
        file_put_contents($tmp_path, $message);
        $cmd = "cat $tmp_path | /usr/bin/mutt -e 'set smtp_url=smtp://10.30.76.11' -e 'set content_type=text/html'";
        $cmd .= " -e 'set from=\"$from\"'";
        $cmd .= " -e 'set realname=\"KPI Report tool\"'";
        $cmd .= " -s \"$subject\" $to";
        if($attach != null && count($attach)!=0){
            for($i=0;$i<count($attach);$i++){
                if(file_exists($attach[$i])){
                    $cmd .= " -a \"" . $attach[$i] . "\"";
                }
            }
        }
        shell_exec($cmd);
        unlink($tmp_path);
    }
    */

    private function ub_format_number($number,$kpi_code)
    {
        if(strpos($number, ".") !== false && $number < 100){
            $number = round($number,2);
        }else{
            if(strpos($number, ",") === false && strpos($number, "%") === false){
                $number = number_format($number);
            }
        }
        return $number;
    }

    private function calc_compare_number($a,$b)
    {
        if ($b == 0) {
            return 0;
        }
        $c = round((($a - $b) / $b) * 100, 2);
        return $c;
    }

    private function get_color_tren($number)
    {
        if($number > 0)
            $color = "blue";
        else if ($number < 0)
            $color = "red";
        else
            $color = "black";
        return $color;
    }

    private function db_date_to_user_date($date)
    {
        $list = explode("-", $date);
        return $list[2] . "/" . $list[1] . "/" . $list[0];
    }
    private function calculate_another_kpi($game_code, $data, $calc_list, $day_arr)
    {
        foreach ($data as $user_name => $value_1) {
            foreach ($value_1 as $game_code => $kpi_arr) {
                for ($i = 0; $i < count($calc_list); $i++) {
                    $kpi_calc = $calc_list[$i];
                    for ($j = 0; $j < count($day_arr); $j++) {
                        $d1 = $day_arr[$j];
                        $f_t = $this->util->get_field_and_timming($kpi_calc);
                        $_kpi_code = $f_t[0];
                        $timming = $f_t[1];

                        switch ($_kpi_code) {
                            case "arppu" :
                                $need_1 = "pu" . $timming;
                                $need_2 = "gr" . $timming;
                                if ($kpi_arr[$need_1][$d1] != 0 && $kpi_arr[$need_2][$d1] != 0) {
                                    $arppu = round($kpi_arr[$need_2][$d1] / $kpi_arr[$need_1][$d1], 2);
                                } else {
                                    $arppu = 0;
                                }
                                $data[$user_name][$game_code][$kpi_calc][$d1] = $arppu;
                                break;
                            case "arpu" :
                                $need_1 = "a" . $timming;
                                $need_2 = "gr" . $timming;
                                if ($kpi_arr[$need_1][$d1] != 0 && $kpi_arr[$need_2][$d1] != 0) {
                                    $arpu = round($kpi_arr[$need_2][$d1] / $kpi_arr[$need_1][$d1], 2);
                                } else {
                                    $arpu = 0;
                                }
                                $data[$user_name][$game_code][$kpi_calc][$d1] = $arpu;
                                break;
                            case "cvr" :
                                $need_1 = "a" . $timming;
                                $need_2 = "pu" . $timming;
                                if ($kpi_arr[$need_1][$d1] != 0 && $kpi_arr[$need_2][$d1] != 0) {
                                    $cvr = round(($kpi_arr[$need_2][$d1] / $kpi_arr[$need_1][$d1]) * 100, 2) ;
                                } else {
                                    $cvr = 0;
                                }
                                $data[$user_name][$game_code][$kpi_calc][$d1] = $cvr;
                                break;
                        }
                        if (!isset($day_arr[$j + 1])) continue;
                        $d2 = $day_arr[$j + 1];
                        if ($d2 == date('Y-m-d', strtotime('-1 day ' . $d1))) {
                            switch ($_kpi_code) {
                                case "cr" :
                                    $need = "nrr" . $timming;
                                    if (isset($kpi_arr[$need])) {
                                        $nrr = $kpi_arr[$need][$d1];
                                        $cr = 100 - $nrr;
                                        $data[$user_name][$game_code][$kpi_calc][$d1] = round($cr, 2);
                                    } else {
                                        $data[$user_name][$game_code][$kpi_calc][$d1] = "0";
                                    }
                                    break;
                            }
                        } else {
                            $data[$user_name][$game_code][$kpi_calc][$d1] = "0";
                        }
                    }
                }
            }
        }
        return $data;
    }

    private function re_organize($allgame, $priority_game){
        $return = $priority_game;
        for($i=0;$i < count($allgame); $i++){
            $game = $allgame[$i];
            if(in_array($game, $return) === false)
                $return[] = $game;
        }
        $return = array_reverse($return);
        return $return;
    }

    private function set_done_user($user, $to_date){
        $done = $this->get_done_path($user, $to_date);
        touch($done);
    }

    private function set_miss_game($user, $games, $to_date){
        $string = implode(",", $games);
        $game_cache = $this->get_miss_path($user, $to_date);
        file_put_contents($game_cache, $string);
    }


    private function check_sent($user_name, $to_date){
        $done = $this->get_done_path($user_name, $to_date);
        $game_miss = $this->get_miss_path($user_name, $to_date);
        if(file_exists($done))
            return 1;// all game is done
        if(file_exists($game_miss)){
            $game_string = file_get_contents($game_miss);
            return $game_string;
        }
        return "";
    }

    private function get_need_day($to_date){
        $return = array();

        $return[] = $to_date;
        $return[] = date('Y-m-d', strtotime('-1 day '. $to_date));
        $return[] = date('Y-m-d', strtotime('-2 day '. $to_date));

        $return[] = date('Y-m-d', strtotime('-7 day '. $to_date));
        $return[] = date('Y-m-d', strtotime('-8 day '. $to_date));

        $return[] = date('Y-m-d', strtotime('-30 day '. $to_date));
        $return[] = date('Y-m-d', strtotime('-31 day '. $to_date));

        return $return;
    }
    private function get_need_month($to_date){
        $return = $this->util->getLastDaysOfMonths($to_date, 3, false);
        return $return;
    }

    public function tt(){
        $emails_test = array("canhtq@vng.com.vn");
        $mail_config = array(
            "from" => "kpi.stats@vng.com.vn",
            "fromalias" => "GameKPI Alert",
            "to" => $emails_test,
            "subject" => "Issues  Check time:" . date("Y-m-d H:i:s", time()),
            "message" => "a",
            "attach" => array("/tmp/current.csv"),
        );
        $result = $this->util->send_mail($mail_config);
    }
}