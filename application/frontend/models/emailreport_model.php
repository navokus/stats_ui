<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 04/07/2016
 * Time: 14:35
 */

class Emailreport_Model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_myplay_report($day_arr, $game_code, $game_name){
        /*
        $return = array();
        $db = $this->load->database('stats_myplay', TRUE);

        $db->select("num_active_a1 as a1, num_active_a30 as a30, log_date", false);
        $db->from("log_active_user");
        $db->where("app", "myplay");
        $db->where_in("log_date", $day_arr);
        $query = $db->get();
        $result = $query->result_array();
        if(!empty($result)){
            for($i=0;$i < count($result); $i++){
                $return[$game_code][$result[$i]['log_date']]["a1"] = $result[$i]['a1'];
                $return[$game_code][$result[$i]['log_date']]["a30"] =  $result[$i]['a30'];
            }
        }

        $db->select("total_money as gr1, total_user as pu1, log_date", false);
        $db->from("log_payment");
        $db->where("app", "myplay");
        $db->where("type", "1");
        $db->where_in("log_date", $day_arr);
        $query = $db->get();
        $result = $query->result_array();
        if(!empty($result)){
            for($i=0;$i < count($result); $i++){
                $return[$game_code][$result[$i]['log_date']]["gr1"] = $result[$i]['gr1'];
                $return[$game_code][$result[$i]['log_date']]["pu1"] =  $result[$i]['pu1'];
            }
        }

        $db->select("total_money as gr30, log_date", false);
        $db->from("log_payment");
        $db->where("app", "myplay");
        $db->where("type", "30");
        $db->where_in("log_date", $day_arr);
        $query = $db->get();
        $result = $query->result_array();
        if(!empty($result)){
            for($i=0;$i < count($result); $i++){
                $return[$game_code][$result[$i]['log_date']]["gr30"] = $result[$i]['gr30'];
            }
        }

        asort($day_arr);
        $from = $day_arr[0];
        $to = $day_arr[count($day_arr)-1];
        $db->select("num_nru as n1, log_date", false);
        $db->from("log_statistic_register");
        $db->where("app", "myplay");
        $db->where("type", "1");
        $db->where("log_date >=", $from);
        $db->where("log_date <=", $to);
        $query = $db->get();
        $result = $query->result_array();
        if(!empty($result)){
            for($i=0;$i < count($result); $i++){
                $return[$game_code][$result[$i]['log_date']]["n1"] = $result[$i]['n1'];
            }
        }
        $log_month = date('Y-m', strtotime('last month'));
        $log_date = date('Y-m-d', strtotime('last day of previous month'));
        $db->select("total_user as pum, total_money as grm", false);
        $db->from("log_payment_platform_monthly");
        $db->where("app", "myplay");
        $db->where("platform", "mobile");
        $db->where("log_month", $log_month);
        $query = $db->get();
        $result = $query->result_array();
        if(!empty($result)){
            for($i=0;$i < count($result); $i++){
                $return[$game_code][$log_date]["pum"] = $result[$i]['pum'];
                $return[$game_code][$log_date]["grm"] = $result[$i]['grm'];
            }
        }

        $return[$game_code]['game_name'] = $game_name;
        $return[$game_code]['region'] = "local";
        return $return;
        */
    }

    public function get_mobile_game_report_monthly($day_arr, $kpi_id_arr){
        sort($day_arr);
        $kpi_ids_config = $this->getKpiIDs(null, $kpi_id_arr);
        $sql="g.GameCode,g.region,g.GameName,gk.report_date, gk.kpi_id,kd.kpi_name,gk.source,gk.report_date, kd.group_id, gk.kpi_value, mrs.kpi_type";
        $this->db->select($sql, false);
        $this->db->from("game_kpi gk");
        $this->db->join("games g", 'gk.game_code = g.GameCode', 'left');
        $this->db->join('kpi_desc kd', 'gk.kpi_id = kd.kpi_id', 'left');
        $this->db->join('mt_report_source mrs', 'mrs.game_code = gk.game_code and mrs.group_id = kd.group_id and mrs.data_source = gk.source', 'left');
        $this->db->where("mrs.kpi_type", "game_kpi");
        $this->db->where("gk.game_code is not null");
        $this->db->where("g.GameType2", 2);
        $this->db->where("g.Status", 1);
        $this->db->where_in("gk.report_date", $day_arr);
        $this->db->where_in('gk.kpi_id', array_keys($kpi_ids_config));

        $query = $this->db->get();
        $result = $query->result_array();

        $return = array();
        for($i=0;$i<count($result);$i++){
            $_log_date = $result[$i]['report_date'];
            $_kpi_id = $result[$i]['kpi_name'];
            $_kpi_value = $result[$i]['kpi_value'];
            $_game_code = $result[$i]['GameCode'];
            $_region = $result[$i]['region'];
            $_game_name = $result[$i]['GameName'];
            $return[$_game_code][$_log_date][$_kpi_id] = $_kpi_value;
            $return[$_game_code]['game_name'] = $_game_name;
            $return[$_game_code]['region'] = $_region;
        }

        $lastest_month = $day_arr[count($day_arr)-1];
        $one_month_ago = $day_arr[count($day_arr)-2];


        $sql="g.GameCode,g.region,g.GameName,gk.report_date, gk.kpi_id,kd.kpi_name,gk.source,gk.report_date, kd.group_id, gk.kpi_value, mrs.kpi_type";
        $this->db->select($sql, false);
        $this->db->from("game_kpi gk");
        $this->db->join("games g", 'gk.game_code = g.GameCode', 'left');
        $this->db->join('kpi_desc kd', 'gk.kpi_id = kd.kpi_id', 'left');
        $this->db->join('mt_report_source mrs', 'mrs.game_code = gk.game_code and mrs.group_id = kd.group_id and mrs.data_source = gk.source', 'left');
        $this->db->where("mrs.kpi_type", "game_kpi");
        $this->db->where("gk.game_code is not null");
        $this->db->where("g.GameType2", 2);
        $this->db->where("g.Status", 0);
        $this->db->where("gk.report_date >= ", $one_month_ago);
        $this->db->where("gk.report_date <= ", $lastest_month);
        $this->db->where_in('gk.kpi_id', array_keys($kpi_ids_config));
        $this->db->order_by("gk.report_date", "desc");
        $this->db->order_by("gk.game_code", "desc");

        $query = $this->db->get();
        $result = $query->result_array();

        $tmp = array();
        for($i=0;$i<count($result);$i++){
            $_log_date = $result[$i]['report_date'];
            $_game_code = $result[$i]['GameCode'];
            $_kpi_id = $result[$i]['kpi_name'];
            $_kpi_value = $result[$i]['kpi_value'];
            $_region = $result[$i]['region'];
            $_game_name = $result[$i]['GameName'];
            $tmp[$_game_code][$_log_date][$_kpi_id] = $_kpi_value;
            $tmp[$_game_code]['game_name'] = $_game_name;
            $tmp[$_game_code]['region'] = $_region;
        }
        $close_games = array();
        foreach($tmp as $_game_code => $_data){
            $date_arr = array_keys($tmp[$_game_code]);
            sort($date_arr);
            $max_date = $date_arr[count($date_arr)-3];
            //cheat
            $return[$_game_code][$lastest_month] = $tmp[$_game_code][$max_date];
            $return[$_game_code]['game_name'] = $tmp[$_game_code]['game_name'];
            $return[$_game_code]['region'] = $tmp[$_game_code]['region'];
            $close_games[$_game_code]['max_date'] = $max_date;
            $close_games[$_game_code]['game_name'] = $tmp[$_game_code]['game_name'];
        }

        $from = $day_arr[0];
        $to = $day_arr[count($day_arr)-1];

        $kpi_ids_config = $this->getKpiIDs(null, array("n1", "a1", "a7", "nrr1", "nrr7"));
        $sql="g.GameCode,g.region,g.GameName,gk.report_date, gk.kpi_id,kd.kpi_name,gk.source,gk.report_date, kd.group_id, gk.kpi_value, mrs.kpi_type";
        $this->db->select($sql, false);
        $this->db->from("game_kpi gk");
        $this->db->join("games g", 'gk.game_code = g.GameCode', 'left');
        $this->db->join('kpi_desc kd', 'gk.kpi_id = kd.kpi_id', 'left');
        $this->db->join('mt_report_source mrs', 'mrs.game_code = gk.game_code and mrs.group_id = kd.group_id and mrs.data_source = gk.source', 'left');
        $this->db->where("mrs.kpi_type", "game_kpi");
        $this->db->where("gk.game_code is not null");
        $this->db->where("g.GameType2", 2);
        $this->db->where_in("g.Status", array(0,1));
        $this->db->where("gk.report_date >=", $from);
        $this->db->where("gk.report_date <=", $to);
        $this->db->where_in('gk.kpi_id', array_keys($kpi_ids_config));

        $query = $this->db->get();
        $result = $query->result_array();

        for($i=0;$i<count($result);$i++){
            $_log_date = $result[$i]['report_date'];
            $_kpi_id = $result[$i]['kpi_name'];
            $_kpi_value = $result[$i]['kpi_value'];
            $_game_code = $result[$i]['GameCode'];
            $_game_name = $result[$i]['GameName'];
            $_region = $result[$i]['region'];
            $return[$_game_code][$_log_date][$_kpi_id] = $_kpi_value;
            $return[$_game_code]['game_name'] = $_game_name;
            $return[$_game_code]['region'] = $_region;
        }
        return array("data" => $return, "closed_games" => $close_games);
    }
    public function get_mobile_game_report($day_arr, $kpi_id_arr){
        $kpi_ids_config = $this->getKpiIDs(null, $kpi_id_arr);
        $sql="g.GameCode,g.region,g.GameName,gk.report_date, gk.kpi_id,kd.kpi_name,gk.source,gk.report_date, kd.group_id, gk.kpi_value, mrs.kpi_type";
        $this->db->select($sql, false);
        $this->db->from("game_kpi gk");
        $this->db->join("games g", 'gk.game_code = g.GameCode', 'left');
        $this->db->join('kpi_desc kd', 'gk.kpi_id = kd.kpi_id', 'left');
        $this->db->join('mt_report_source mrs', 'mrs.game_code = gk.game_code and mrs.group_id = kd.group_id and mrs.data_source = gk.source', 'left');
        $this->db->where("mrs.kpi_type", "game_kpi");
        $this->db->where("gk.game_code is not null");
        $this->db->where("g.GameType2", 2);
        $this->db->where("g.Status", 1);
        $this->db->where_in("gk.report_date", $day_arr);
        $this->db->where_in('gk.kpi_id', array_keys($kpi_ids_config));

        $query = $this->db->get();
        $result = $query->result_array();

        $return = array();
        for($i=0;$i<count($result);$i++){
            $_log_date = $result[$i]['report_date'];
            $_kpi_id = $result[$i]['kpi_name'];
            $_kpi_value = $result[$i]['kpi_value'];
            $_game_code = $result[$i]['GameCode'];
            $_game_name = $result[$i]['GameName'];
            $_region = $result[$i]['region'];
            $return[$_game_code][$_log_date][$_kpi_id] = $_kpi_value;
            $return[$_game_code]['game_name'] = $_game_name;
            $return[$_game_code]['region'] = $_region;
        }
        return $return;
    }

    public function get_all_game_report($day_arr, $kpi_id_arr){
        $kpi_ids_config = $this->getKpiIDs(null, $kpi_id_arr);
        $sql="g.GameCode,g.region,g.GameName,g.GameType2,gk.report_date, gk.kpi_id,kd.kpi_name,gk.source,gk.report_date, kd.group_id, gk.kpi_value, mrs.kpi_type";
        $this->db->select($sql, false);
        $this->db->from("game_kpi gk");
        $this->db->join("games g", 'gk.game_code = g.GameCode', 'left');
        $this->db->join('kpi_desc kd', 'gk.kpi_id = kd.kpi_id', 'left');
        $this->db->join('mt_report_source mrs', 'mrs.game_code = gk.game_code and mrs.group_id = kd.group_id and mrs.data_source = gk.source', 'left');
        $this->db->where("mrs.kpi_type", "game_kpi");
        $this->db->where("gk.game_code is not null");
        //$this->db->where("g.GameType2 !=", 2);
        $this->db->where("g.Status", 1);
        $this->db->where_in("gk.report_date", $day_arr);
        $this->db->where_in('gk.kpi_id', array_keys($kpi_ids_config));

        $query = $this->db->get();
        $result = $query->result_array();

        $return = array();
        for($i=0;$i<count($result);$i++){
            $_log_date = $result[$i]['report_date'];
            $_kpi_id = $result[$i]['kpi_name'];
            $_kpi_value = $result[$i]['kpi_value'];
            $_game_code = $result[$i]['GameCode'];
            $_game_name = $result[$i]['GameName'];
            $_game_type = $result[$i]['GameType2'];
            $_region = $result[$i]['region'];
            $return[$_game_type][$_game_code][$_log_date][$_kpi_id] = $_kpi_value;
            $return[$_game_type][$_game_code]['game_name'] = $_game_name;
            $return[$_game_type][$_game_code]['region'] = $_region;
        }
        return $return;
    }

    public function get_user_list()
    {
        $this->db->select("t1.username, t2.GameCode, t3.data_source, t3.GameName");
        $this->db->from('users t1');
        $this->db->join('game_groups t2', 't1.GroupId = t2.GroupId', 'left');
        $this->db->join('games t3', 't2.GameCode = t3.GameCode', 'left');
        $this->db->where("t1.Active", 1);
        $this->db->where("t3.SendMail", 1);
        $this->db->where("t3.Status", 1);
        $this->db->where("t1.send_mail", 1);



        $query = $this->db->get();
        $result = $query->result_array();

        return $result;

    }

    public function check_data_ready($game_code, $_monitor_list, $report_date, $kpi_lists)
    {
        $kpi_ids_config = $this->getKpiIDs(null, $_monitor_list);
        $report_source_config = $this->get_report_source($game_code);
        $kpi_by_group_id = array();
        foreach($kpi_ids_config as $kpi_id => $kpi_code){
            $_group_id = isset($this->kpi_config[$kpi_id]['group_id']) ? $this->kpi_config[$kpi_id]['group_id'] : 1;
            $kpi_by_group_id[$report_source_config['game_kpi'][$_group_id]][] = $kpi_id;
        }
        if (count($kpi_by_group_id) > 0) {
            $total = 0;
            $total_kpi = 0;
            foreach($kpi_by_group_id as $data_source => $kpi_id_arr){
                $this->db->select("count(*) as total");
                $this->db->from("game_kpi");
                $this->db->where("game_code", $game_code);
                $this->db->where("source", $data_source);
                //canhtq fix
                $this->db->where("kpi_value > 0");
                //end fix
                $this->db->where("report_date", $report_date);

                //tuonglv, 2016-10-24, fix loi~ report daily tre~
                $this->db->where("DATE_FORMAT(calc_date,'%T') >= ", '02:00:00');
                $this->db->where("DATE_FORMAT(calc_date,'%T') <= ", '23:01:00');
                //end

                $kpi_or = $this->get_combine_where_string("kpi_id", $kpi_id_arr, "||");

                $this->db->where("($kpi_or)");

                $query = $this->db->get();
                $row = $query->result_array();
                $_total = isset($row[0]['total']) ? $row[0]['total'] : 0;
                $total += $_total;
                $total_kpi += count($kpi_id_arr);
            }
            if ($total == $total_kpi) {
                return true;
            }
        }
        return false;
    }
    public function get_kpi_value($game_code, $monitor_list, $day_arr, $kpi_lists, $kpi_id_code)
    {
        $kpi_ids_config = $this->getKpiIDs(null, $monitor_list);
        $report_source_config = $this->get_report_source($game_code);
        $kpi_by_group_id = array();
        foreach($kpi_ids_config as $kpi_id => $kpi_code){
            $_group_id = isset($this->kpi_config[$kpi_id]['group_id']) ? $this->kpi_config[$kpi_id]['group_id'] : 1;
            $kpi_by_group_id[$report_source_config['game_kpi'][$_group_id]][] = $kpi_id;
        }

        $data = array();
        if (count($kpi_by_group_id) > 0) {
            foreach($kpi_by_group_id as $data_source => $kpi_id_arr){
                $this->db->select("kpi_value, kpi_id, report_date");
                $this->db->from("game_kpi");
                $this->db->where("game_code", $game_code);
                $this->db->where_in("report_date", $day_arr);
                $this->db->where("source", $data_source);

                $kpi_or = $this->get_combine_where_string("kpi_id", $kpi_id_arr, "||");

                $this->db->where("($kpi_or)");
                $query = $this->db->get();
                $rows = $query->result_array();

                for ($i = 0; $i < count($rows); $i++) {
                    $row = $rows[$i];
                    $data[$row['kpi_id']][$row['report_date']] = $row['kpi_value'];
                }
            }
        }
        $return = array();
        if (count($data) > 0) {
            foreach ($data as $_kpi_id => $_value) {
                for ($i = 0; $i < count($day_arr); $i++) {
                    $day = $day_arr[$i];
                    $return[$kpi_id_code[$_kpi_id]][$day] = isset($_value[$day]) ? $_value[$day] : 0;
                }
            }
        }

        return $return;
    }

    public function get_operation_user_list()
    {
        $this->db->select("t1.username, t2.GameCode, t3.data_source, t3.GameName");
        $this->db->from('users t1');
        $this->db->join('game_groups t2', 't1.GroupId = t2.GroupId', 'left');
        $this->db->join('games t3', 't2.GameCode = t3.GameCode', 'left');
        $this->db->where("t1.Active", 1);
        $this->db->where("t3.SendMail", 1);
        $this->db->where("t3.Status", 1);
        $this->db->where("t1.send_mail", 1);
        $this->db->where("t1.user_type", 1);


        $query = $this->db->get();
        $result = $query->result_array();

        return $result;

    }
}