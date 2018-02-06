<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 09/08/2016
 * Time: 14:08
 */


class BackendProcess extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
        $this->load->library('util');
        $this->load->model('groupkpi_model', 'group');
        $this->load->model('backendprocess_model', 'backendprocess');
        $this->load->model('operation_model', 'operation');
        $this->load->library('form_validation');
        $this->load->model('game_model', 'game');
        $this->load->model('gamekpi_model', 'gamekpi');
        $this->load->model('kpi_model', 'kpi');
        $this->load->model('rptmobileos_model', 'mobileos');
        $this->load->model('user_model', 'user');
        $this->load->model('qaissuedgame_model', 'qaissuedgame');
        $this->load->model('qcgamekpi_model', 'qcgamekpi');
        $this->load->library('kpiconfig');
        $this->load->library('user_agent');
        $this->load->helper('url');
    }

    public function check_report_hourly()
    {
        //this function run in crontab
        $game_list = array("jxm", "hpt", "3qmobile", "ddd2mp2", "nikki", "nikkisea");
        $minute_default = 150;

        foreach ($game_list as $game_code) {
            $bw_allow_config[$game_code] = $minute_default;
        }
        //$bw_allow_config['jxm'] = 130;

        $log_date = date("Y-m-d");
        $kpi_id_arr = array("gr1");

        $db_data = $this->operation->check_report_hourly($log_date, $game_list, $kpi_id_arr);

        $data_insert['level'] = "warning";
        $data_insert['log_date'] = $log_date;
        $data_insert['monitor_code'] = "hlate";
        $data_insert['message_date'] = date("Y-m-d H:i:s");

        foreach ($game_list as $game_code) {
            $data_insert['game_code'] = $game_code;
            if (!isset($db_data[$game_code])) {
                $message = "Hourly report not found, game_code = $game_code";
                $data_insert['monitor_message'] = $message;
                echo $message . "\n";
                $this->operation->insert_fw_monitor($data_insert);
                continue;
            } else {
                $data = $db_data[$game_code];
            }
            $bw_allow = $bw_allow_config[$game_code];
            $bw_allow = $bw_allow * 60;
            $now = time();
            //check a1
            if (isset($data['a1'])) {
                $data_array = json_decode($data["a1"], true);
                ksort($data_array);
                $pre = 0;
                $key = 0;
                foreach ($data_array as $time => $value) {
                    if ($pre == $value) {
                        break;
                    }
                    if ($pre != $value) {
                        $pre = $value;
                    }
                    $key = $time;
                }
                $last = strtotime($log_date . " " . $key) + 3600;
                $bw = $now - $last;
                echo date("Y-m-d H:i:s", $now) . "\n";
                echo date("Y-m-d H:i:s", $last) . "\n";
                if ($bw >= $bw_allow) {
                    $message = "hourly a1 report not update in " . intval($bw / 60)
                        . " minutes, last_time = "
                        . date("Y-m-d H:i:s", $last)
                        . ", last value = "
                        . number_format(floatval($pre));
                    $data_insert['monitor_message'] = $message;
                    echo $message . "\n";
                    $this->operation->insert_fw_monitor($data_insert);
                }
            }

            //check gr1
            if (isset($data['gr1'])) {
                if($game_code=="hpt"){
                }
                $data_array = json_decode($data["gr1"], true);
                $total = $data_array['total'];
                unset($data_array['total']);
                $keys_only = array_keys($data_array);
                sort($keys_only);
                $key = $keys_only[count($keys_only) - 1];
                $pre = $data_array[$key];

                $last = strtotime($log_date . " " . $key) + 3600;
                $bw = $now - $last;
                echo date("Y-m-d H:i:s", $now) . "\n";
                echo date("Y-m-d H:i:s", $last) . "\n";
                if ($bw >= $bw_allow) {
                    $message = "hourly gr1 report not update in " . intval($bw / 60)
                        . " minutes, last_time = "
                        . date("Y-m-d H:i:s", $last)
                        . ", last value = " . number_format(floatval($total)) . ", game_code = " . $game_code;
                    echo $message . "\n";
                    $data_insert['monitor_message'] = $message;
                    $this->operation->insert_fw_monitor($data_insert);
                }else{
                    $mes = "game_code = $game_code, bw = $bw, bw_allow = $bw_allow";
                    echo $mes . "\n";
                }
            }
        }
    }

    public function sum_revenue_by_os($report_date = "", $kpi_id)
    {
        if ($report_date == "") {
            $report_date = date("Y-m-d", time() - 24 * 60 * 60);
        }
        //$kpi_id = "16031"; //gr30
        //$kpi_id="10031";

        $dbdata = $this->backendprocess->sum_revenue_by_os($report_date, $kpi_id);

        $total_ios = 0;
        $total_android = 0;
        $total_other = 0;
        $total_game = 0;
        for ($i = 0; $i < count($dbdata); $i++) {
            $onerow = $dbdata[$i];
            $game_code = $onerow['game_code'];
            $kpi_value = $onerow['kpi_value'];
            $total_game++;
            $json_obj = json_decode($kpi_value);


            foreach ($json_obj as $os_version => $revenue) {
                // echo $os_version . ":" . $revenue . "\n";
                if ($this->util->starts_with($os_version, "android")) {
                    $total_android += floatval($revenue);
                } else if ($this->util->starts_with($os_version, "ios")) {

                    $total_ios += floatval($revenue);
                } else {
                    $total_other += floatval($revenue);
                }
            }
        }

        $current_time = date("Y-m-d H:i:s");

        $data_delete = array(
            "report_date" => $report_date,
            "kpi_id" => $kpi_id,
        );
        $this->backendprocess->delete_sum_revnue_by_os($data_delete);

        $data_insert = array(
            "report_date" => $report_date,
            "os" => "ios",
            "kpi_id" => $kpi_id,
            "kpi_value" => $total_ios,
            "total_game" => $total_game,
            "calc_date" => $current_time
        );
        $this->backendprocess->insert_sum_revnue_by_os($data_insert);

        $data_insert = array(
            "report_date" => $report_date,
            "os" => "android",
            "kpi_id" => $kpi_id,
            "kpi_value" => $total_android,
            "total_game" => $total_game,
            "calc_date" => $current_time
        );
        $this->backendprocess->insert_sum_revnue_by_os($data_insert);

        $data_insert = array(
            "report_date" => $report_date,
            "os" => "other",
            "kpi_id" => $kpi_id,
            "kpi_value" => $total_other,
            "total_game" => $total_game,
            "calc_date" => $current_time
        );
        $this->backendprocess->insert_sum_revnue_by_os($data_insert);

    }

    public function ccuCollection()
    {
        $now = date("Y-m-d", time());
        $yesterday = date("Y-m-d", strtotime($now) - 24 * 60 * 60);
        $startMonth = date('Y-m-01', time());
        $data = $this->kpi->calcCCU('2016-10-01', $yesterday);
        //var_dump($data);
        $fdata = array();
        foreach ($data as $value) {
            $tmpPeak = array();
            $tmpPeak['report_date'] = $yesterday;
            $tmpPeak['calc_date'] = date("Y-m-d h:m:s", time());
            $tmpPeak['kpi_id'] = 31031;

            $tmpPeak['kpi_value'] = $value['pcu'];
            $tmpPeak['game_code'] = $value['game_code'];
            $tmpPeak['source'] = $value['source'];

            $fdata[] = $tmpPeak;

            $tmpAvg = array();
            $tmpAvg['report_date'] = $yesterday;
            $tmpAvg['calc_date'] = date("Y-m-d h:m:s", time());
            $tmpAvg['kpi_id'] = 30031;

            $tmpAvg['kpi_value'] = $value['acu'];
            $tmpAvg['game_code'] = $value['game_code'];
            $tmpAvg['source'] = $value['source'];
            $fdata[] = $tmpAvg;
        }
        //$this->backendprocess->insertCCUGameKPI($fdata);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($fdata));
    }

    public function collectM($date)
    {
        $data = $this->backendprocess->collectMonthlyReport($date);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($data));
    }


    public function sum_monthly($start_date, $end_date)
    {
        //$start_date = "2016-12-01";
        //$end_date = "2016-12-31";
        $kpi_ids = array("a1", "a7", "nrr1", "nrr7", "n1", "n7", "pu1", "pu7", "acu1", "pcu1");
        $avg_peak_id = array(
            "a1" => array("40001", "41001"),
            "a7" => array("40007", "41007"),
            "nrr1" => array("42001", "43001"),
            "nrr7" => array("42007", "43007"),
            "n1" => array("44001", "45001"),
            "n7" => array("44007", "45007"),
            "pu1" => array("46001", "47001"),
            "pu7" => array("46007", "47007"),
            "acu1" => array("48001", "49001"),
            "pcu1" => array("50001", "51001")
        );
        $day_arr = $this->util->getDaysEveryDay($start_date, $end_date);
        sort($day_arr);
        $data = $this->backendprocess->get_data_avg_peak($day_arr, $kpi_ids);

        foreach ($data as $game_code => $detail) {
            foreach ($detail as $kpi_id => $data_detail) {
                $source = $data_detail['source'];
                unset($data_detail['source']);
                $data_detail = array_values($data_detail);
                $avg = array_sum($data_detail) / count($data_detail);
                sort($data_detail);
                $max = $data_detail[count($data_detail) - 1];

                $insert['report_date'] = $day_arr[count($day_arr) - 1];
                $insert['game_code'] = $game_code;
                $insert['source'] = $source;
                $insert['kpi_id'] = $avg_peak_id[$kpi_id][0];
                $insert['kpi_value'] = $avg;
                $this->backendprocess->insert_avg_peak($insert);
                $insert['kpi_id'] = $avg_peak_id[$kpi_id][1];
                $insert['kpi_value'] = $max;
                $this->backendprocess->insert_avg_peak($insert);

            }
        }
        
        echo "Calculating from " . $start_date . " to " . $end_date;
    }
    /*public function sum_monthly($start_date, $end_date)
    {
        $start_date = "2017-01-01";
        $end_date = "2017-01-31";
        $kpi_ids = array("a1", "a7", "nrr1", "nrr7","nrr3","nrr30" , "n1", "n7", "pu1", "pu7", "acu1", "pcu1");
        $avg_peak_id = array(
            "a1" => array("40001", "41001"),
            "a7" => array("40007", "41007"),
            "nrr1" => array("42001", "43001"),
            "nrr7" => array("42007", "43007"),
            "nrr3" => array("42003", "43003"),
            "nrr30" => array("42030", "43030"),
            "n1" => array("44001", "45001"),
            "n7" => array("44007", "45007"),
            "pu1" => array("46001", "47001"),
            "pu7" => array("46007", "47007"),
            "acu1" => array("48001", "49001"),
            "pcu1" => array("50001", "51001")
        );
        $day_arr = $this->util->getDaysEveryDay($start_date, $end_date);
        sort($day_arr);
        $data = $this->backendprocess->get_data_avg_peak($day_arr, $kpi_ids);

        foreach ($data as $game_code => $detail) {
            foreach ($detail as $kpi_id => $data_detail) {
                $source = $data_detail['source'];
                unset($data_detail['source']);
                $data_detail = array_values($data_detail);
                $avg = array_sum($data_detail) / count($data_detail);
                sort($data_detail);
                $max = $data_detail[count($data_detail) - 1];

                $insert['report_date'] = $day_arr[count($day_arr) - 1];
                $insert['game_code'] = $game_code;
                $insert['source'] = $source;
                $insert['kpi_id'] = $avg_peak_id[$kpi_id][0];
                $insert['kpi_value'] = $avg;
                $this->backendprocess->insert_avg_peak($insert);
                $insert['kpi_id'] = $avg_peak_id[$kpi_id][1];
                $insert['kpi_value'] = $max;
                $this->backendprocess->insert_avg_peak($insert);

            }
        }

        echo "Calculating from " . $start_date . " to " . $end_date;
    }*/
    public function mobileOs($date)
    {
    	$mobiles = $this->game->listGamesByPlatform(2);
    	$total_data = array();
    	
    	foreach ($mobiles as $mobile) {
    		$gameCode= $mobile["GameCode"];
    		$data = $this->group->getDetailData("os",$gameCode,"31","2016-08-01","2017-03-01");
    		$kpis= $data["group"]["31"];   	    		
    		foreach ($kpis as $kpi_name => $kpi) {
    			foreach ($kpi as $os => $kpios) {
    				foreach ($kpios as $date => $value) {
    					$tmp = array();
    					$tmp["game_code"] = $gameCode;
    					$tmp["kpi"] = $kpi_name;
    					$tmp["value"] = floatval($value);;
    					$tmp["os"] = $os;
    					$tmp["report_date"] = $date;
    					$this->mobileos->insert($tmp);
    					$total_data[]=$tmp;
    				}
    			}    		
    		}
    	}
    	
    	$this->output->set_content_type('application/json');
    	$this->output->set_output(json_encode($mobiles));
    	return;
    }


    public function get_report_key()
    {
        $all = $this->util->get_all_kpi();
        return $all;

    }
    private function remove_game_kpi_all_day_zero($data){
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
    private function remove_os_kpi_all_day_zero($data){
        $re_organize = array();
        $os_list = $this->util->get_os_list();
        $count = count($data);
        for($i=0;$i < $count;$i++){
            foreach($os_list as $os){
                foreach($data[$i][$os] as $kpi_code => $kpi_value){
                    $re_organize[$kpi_code][] = $kpi_value;
                }
            }
        }
        foreach($re_organize as $kpi_code => $data_sum){
            $sum = array_sum($data_sum);
            if($sum == 0){
                for($i=0;$i < $count;$i++){
                    foreach($os_list as $os){
                        unset($data[$i][$os][$kpi_code]);
                    }
                }
            }
        }
        return $data;
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
    private function get_data_report($fromDate, $toDate, $gameCode, $kpi_type)
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
    public function gameIssue($gameArray,$fromDate,$toDate){

        $kpiFields=array("a1","gr1","pu1");
        $games = explode("-", $gameArray);
        $results = array();
        foreach ($games as $gameCode) {

            $kpi_data = $this->get_data_report($fromDate,$toDate,$gameCode, "game");
            $len = count($kpi_data);
            $gameInfo = $this->game->getFullGameInfo($gameCode);
            if($gameInfo["Status"]!=1){
                continue;
            }

            if($gameInfo["KpiStatus"]==1){
                //full kpi;
                $kpiFields=array("a1","gr1","pu1");
            }elseif ($gameInfo["KpiStatus"]==0)
            {
                //payment kpi
                $kpiFields=array("gr1","pu1");
            }else{
                continue;
            }
            $issued = array();
            for ($i = 0; $i <$len ; $i++) {
                $data = $kpi_data[$i];
                if($data!=null){
                    $isIssued = false;
                    //issue:value=0, a1=n1, |%incr| > 80
                    foreach ($kpiFields as $kpi) {
                        $cValue="0";
                        if($data[$kpi]!=null){
                            $cValue=$data[$kpi];
                        }
                        if($cValue<=1){
                            $isIssued = true;
                        }
                    }
                    if($isIssued){
                        $issued["game_code"] = $gameCode;
                        $issued["report_date"] = $toDate;
                        $issued["update_time"] = date("Y-m-d H:i:s", time());
                        $results[]= $issued;
                    }
                }
            }
        }
        $this->qaissuedgame->clean($toDate);
        if(count($results)>0){
            $this->qaissuedgame->addIssues($results);
        }
    }

    public function delectIssues(){
        $now = date("Y-m-d", time());
        $nowHH = date("H", time());
        $yesterday = date("Y-m-d", strtotime($now) - 24*60*60);
        $fromDate=$yesterday;
        $toDate=$yesterday;
        $games = $this->user->getGameList("canhtq");
        $gameArray ="";
        foreach ($games as $game) {
            $gameArray= $gameArray ."-".$game["game_code"];
        }
        $this->gameIssue($gameArray,$fromDate,$toDate);

        $issues = $this->qaissuedgame->getIssues($yesterday);
        $data= json_decode($issues["issues"],true);
        if(count($data["games"])>0){
            //co issues
            if($nowHH>"08"){
                $this->alertIssues($data["games"],$data["hdfs"],$data["report-date"],$data["check-time"]);
            }
        }
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($data["games"]));
    }


    public function alertIssues($games,$hdfs,$reportDate,$checkTime)
    {
        $now = date("Y-m-d", time());
        $yesterday = date("Y-m-d", strtotime($now) - 24 * 60 * 60);

        $html = "";
        $html .= "<!DOCTYPE html>
        <html>
        <head>
        <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            width: auto;
        }
        </style>
        </head>
        <body>";
        $html .= "<p>Hi!,";
        $html .= "</br>";
        $html .= "</br>";
        $html .= "Pls check your systems. ";
        $html .= "</br>";
        $html .= "Detection information:</br>";
        $html .= "</br>";
        $html .= '</p>';
        $html .= "<p>Data Date:" .$reportDate;
        $html .= "</br>";
        $html .= '</p>';
        $html .= "<p>Check Time:" .$checkTime;
        $html .= "</br>";
        $html .= '</p>';

        $html .= "<table style=\"border: 1px solid #cccccc;\" align=\"left\"  cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
        $htmlGames = "ISSUED GAMES: ";
        foreach ($games as $game) {
            $htmlGames .= $game .",";
        }
        $waitingLog=false;
        $html .= "<tr bgcolor=\"#70bbd9\"><td colspan=\"8\">$htmlGames</td></tr>";
        $html .= "<tr bgcolor=\"#70bbd9\"><td colspan=\"8\">LOG INFO:</td></tr>";
        $html .= "<tr bgcolor=\"#70bbd9\"><td>GameCode</td><td>Path</td><td>Size</td><td>Is Exists</td><td>ModificationTime</td><td>DirCount</td><td>FileCount</td><td>Status</td></tr>";
        foreach ($hdfs as $game => $files) {
            $color = "#669999";
            foreach ($files as $file) {
                $html .= "<tr bgcolor=\"";
                $html .= $color;
                $html .= "\">";
                $html .= "<td>" . $game . "</td>";
                $html .= "<td>";
                $html .= str_replace("u003d","=",$file["filePath"]);
                $html .= "</td><td align=\"right\">";
                $html .= number_format($file["fileSize"]);
                $html .= "</td><td align=\"right\">";
                $html .= $file["exists"]==true ? "true":"false";
                $html .= "</td><td align=\"right\">";
                $html .= $file["modificationTime"]>0 ? date("Y-m-d H:i:s", ($file["modificationTime"]/1000)) : "";
                $html .= "</td><td align=\"right\" bgcolor=\"";
                $html .= $color;
                $html .= "\">";
                $html .=$file["dirCount"];
                $html .= "</td><td align=\"right\" bgcolor=\"";
                $html .= $color;
                $html .= "\">";
                $html .= $file["fileCount"];;
                $html .= "</td>";
                $html .= "<td align=\"right\" bgcolor=\"";
                $html .= $color;
                $html .= "\">";
                $html .= $file["exists"]==true?"running report...":"waiting log";
                $html .= "</td>";

                $html .= "</tr>";
                if(!$waitingLog && !$file["exists"]){
                    $waitingLog=true;
                }
            }
        }
        $html .= "</table>";
        $html .= "</body></html>";

        $emails_test = array("canhtq@vng.com.vn");
        $emails = array("canhtq@vng.com.vn", "quangctn@vng.com.vn","lamnt6@vng.com.vn");
        if($waitingLog){
            $emails[]= "duclt@vng.com.vn";
            $emails[]= "vunv@vng.com.vn";
        }
        $mail_config = array(
            "from" => "kpi-detection@vng.com.vn",
            "fromalias" => "Game Issued Detection",
            "to" => $emails,
            "subject" => "Game KPI Issued Detection for " . $yesterday . ". Check time:" . date("Y-m-d H:i:s", time()),
            "message" => $html
        );
        $result = $this->util->send_mail($mail_config);

        $this->output->set_output(json_encode($result));
        return;
    }

    public function test(){
        $now = date("Y-m-d", time());
        $yesterday = date("Y-m-d", strtotime($now) - 24*60*60);
        $xdata = $this->qcgamekpi->getData($yesterday,"projectc");

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($xdata));
    }
    public function sync(){
        $now = date("Y-m-d", time());
        $yesterday = date("Y-m-d", strtotime($now) - 24*60*60);
        $games = $this->game->getAtiveAlls();
        $data = array();
        $this->gamekpi->clean_days(array($yesterday));
        $kpi_array = array("10001","10030","10031","11001","11030","11031","15001","15030","15031","19001","19030","19031","16001","160031","28001","28007","28030","28003","28014","30001","31001","48001","51001","39031","39001");

        foreach ($games as $game) {
            $result=$this->gamekpi->sync_to_main_src($kpi_array,"game_kpi",$game["GameCode"],array($yesterday),"game_kpi");
            for($j=0;$j<count($result);$j++){
                $data[]=$result[$j];
            }
        }
        $this->gamekpi->sync_to_share($data);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode("ok",$data));
    }
    public function sync_30date(){
        $now = date("Y-m-d", time());
        $yesterday = date("Y-m-d", strtotime($now) - 24*60*60);
        $day30 = date("Y-m-d", strtotime($now) - 30*24*60*60);
        $dates = $this->util->getDaysFromTiming($day30, $yesterday, "daily", false);
        $games = $this->game->getAtiveAlls();

        $this->gamekpi->clean_days($dates);
        $kpi_array = array("10001","10030","10031","11001","11030","11031","15001","15030","15031","19001","19030","19031","16001","160031","28001","28007","28030","28003","28014","30001","31001","48001","51001","39031","39001","29001","29007","29030","29003","29014");
        //var_dump($dates);exit();
        $data = array();
        foreach ($games as $game) {

            $result=$this->gamekpi->sync_to_main_src($kpi_array,"game_kpi",$game["GameCode"],$dates,"game_kpi");
            for($j=0;$j<count($result);$j++){
                $data[]=$result[$j];
            }
            //var_dump($data);exit();


        }
        $this->gamekpi->sync_to_share($data);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode("ok"));
    }

    public function syncGS(){
        $now = date("Y-m-d", time());
        $yesterday = date("Y-m-d", strtotime($now) - 24*60*60);
        $day7 = date("Y-m-d", strtotime($now) - 30*24*60*60);
        $dates = $this->util->getDaysFromTiming($day7, $yesterday, "daily", false);
    }

}