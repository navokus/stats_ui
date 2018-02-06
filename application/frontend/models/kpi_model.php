<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 14/03/2016
 * Time: 23:36
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kpi_model extends MY_Model
{
    private $_table_name = "game_kpi";

    public function __construct()
    {
        parent::__construct();
    }

    public function getData($gameCode, $date, $kpi_type)
    {
        $lstDate = $this->util->getKpiListDate($date);
        $ubstats = $this->load->database('ubstats', TRUE);
        $db_field_config = $this->util->db_field_config();

        $lstMetric = array("all_kpi", "user_kpi", "revenue_kpi");
        $resultData = array();

        foreach ($lstMetric as $metric) {
            if ($metric == "all_kpi") {
                $kpi_field_config = array_merge($db_field_config['user_kpi']["4"], $db_field_config['user_kpi']["5"], $db_field_config['user_kpi']["6"],
                    $db_field_config['user_kpi']["8"], $db_field_config['user_kpi']["9"],
                    $db_field_config['user_kpi']["3"], $db_field_config['user_kpi']["14"],

                    $db_field_config['revenue_kpi']["4"], $db_field_config['revenue_kpi']["5"], $db_field_config['revenue_kpi']["6"],
                    $db_field_config['revenue_kpi']["8"], $db_field_config['revenue_kpi']["9"],
                    $db_field_config['revenue_kpi']["3"], $db_field_config['revenue_kpi']["14"]);
                $kpi_ids_config = $this->getKpiIDs($ubstats, $kpi_field_config);
            } else {
                $kpi_field_config = array_merge($db_field_config[$metric]["4"], $db_field_config[$metric]["5"], $db_field_config[$metric]["6"],
                    $db_field_config[$metric]["8"], $db_field_config[$metric]["9"],
                    $db_field_config[$metric]["3"], $db_field_config[$metric]["14"]);
                $kpi_ids_config = $this->getKpiIDs($ubstats, $kpi_field_config);
            }
            $data = $this->get_kpi_data($kpi_ids_config, $kpi_type, $gameCode, $lstDate, $this->_table_name);
			
            $return_data = array();
            for ($i = 0; $i < count($lstDate); $i++) {
                $return_data[$i]['log_date'] = $lstDate[$i];
                foreach ($kpi_ids_config as $key => $value) {
                    $f_alias = $value;
                    $return_data[$i][$f_alias] = (isset($data[$lstDate[$i]][$f_alias])) ? $data[$lstDate[$i]][$f_alias] : 0;
                }
            }

            if ($return_data[0]["log_date"] != $lstDate[0]) {
                $return_data = array();
            }

            $resultData[$metric] = $return_data;
        }
        return $resultData;
    }

    public function get_os_export_datatable($fromDate, $toDate, $game_code, $kpi_ids, $kpi_type)
    {
        $yesterday = date("Y-m-d", time() - 24 * 60 * 60);
        if ($yesterday < $toDate)
            $toDate = $yesterday;

        $kpi_ids = $this->util->add_field_name($kpi_ids);
        $kpi_ids_config = $this->getKpiIDs($this->db, $kpi_ids);
        $day_arr = $this->util->getDaysFromTiming($fromDate, $toDate, 4, false);
        rsort($day_arr);	//vinhdp added 2016-12-26 show asc
        $table_name = $kpi_type;
		
        $data = $this->get_kpi_data($kpi_ids_config, $kpi_type, $game_code, $day_arr, $table_name);

        $return_data = array();
        for ($i = count($day_arr) - 1; $i >= 0; $i--) {
            $t = array();
            foreach ($kpi_ids_config as $key => $value) {
                $f_alias = $value;

                $t_t = (isset($data[$day_arr[$i]][$f_alias])) ? $data[$day_arr[$i]][$f_alias] : "";
                $ios = 0;
                $android = 0;
                $other = 0;
                if ($t_t != "") {
                    $t_obj = json_decode($t_t);
                    foreach ($t_obj as $t_key => $value) {
                        if (strpos($t_key, "ios") !== FALSE) {
                            $ios += $value;
                        } else if (strpos($t_key, "android") !== FALSE) {
                            $android += $value;
                        } else {
                            $other += $value;
                        }
                    }
                }
                $t['android'][$f_alias] = $android;
                $t['ios'][$f_alias] = $ios;
                $t['other'][$f_alias] = $other;
            }

            $return_data[$i] = array_reverse($t);
            $return_data[$i]['log_date'] = $day_arr[$i];
            $return_data[$i] = array_reverse($return_data[$i]);
        }
        $return_data = array_values($return_data);

        return $return_data;
    }
    private function add_calc_report($data, &$kpi_ids_config){
        $timing_map = $this->util->get_timming_config();
        foreach($data as $log_date => $data_detail){
            foreach($timing_map as $timing){
                //cr
                $need = $data[$log_date]['rr' . $timing];
                $t = json_decode($need, true);
                $android = 0;
                if(isset($t['android'])){
                    $android = 100 - $t['android'];
                }
                $ios = 0;
                if(isset($t['ios'])){
                    $ios = 100 - $t['ios'];
                }
                $other = 0;
                if(isset($t['other'])){
                    $other = 100 - $t['other'];
                }
                $tt = array("android" => $android, "ios" => $ios, "other" => $other);
                $data[$log_date]['cr' . $timing] = json_encode($tt);
                $kpi_ids_config['cr' . $timing] = 'cr' . $timing;
            }
        }
        return $data;
    }
    public function get_export_datatable_by_source($fromDate, $toDate, $game_code, $table_name, $kpi_ids, $kpi_type,$source)
    {
        $yesterday = date("Y-m-d", time() - 24 * 60 * 60);
        if ($yesterday < $toDate)
            $toDate = $yesterday;

        $timming_map = $this->util->get_timming_config();
        foreach ($timming_map as $k => $v) {
            $kpi_ids[] = 'rlc' . $v;
            $kpi_ids[] = 'nrc' . $v;
        }
        $kpi_ids = $this->util->add_field_name($kpi_ids);
        $kpi_ids_config = $this->getKpiIDs($this->db, $kpi_ids);
        $day_arr = $this->util->getDaysFromTiming($fromDate, $toDate, 4, false);
        $data = $this->get_kpi_data_by_source($kpi_ids_config, $kpi_type, $game_code, $day_arr, $table_name,$source);

        $return_data = array();
        //for($i=0;$i<count($day_arr);$i++){
        for ($i = count($day_arr) - 1; $i >= 0; $i--) {
            $t = array();
            foreach ($kpi_ids_config as $key => $value) {
                $f_alias = $value;
                $t[$f_alias] = (isset($data[$day_arr[$i]][$f_alias])) ? $data[$day_arr[$i]][$f_alias] : 0;
            }

            $return_data[$i] = array_reverse($t);
            $return_data[$i]['log_date'] = $day_arr[$i];
            $return_data[$i] = array_reverse($return_data[$i]);
        }

        $return_data = array_values($return_data);
        return $return_data;
    }

    public function get_export_datatable($fromDate, $toDate, $game_code, $table_name, $kpi_ids, $kpi_type)
    {
        $yesterday = date("Y-m-d", time() - 24 * 60 * 60);
        if ($yesterday < $toDate)
            $toDate = $yesterday;

        $timming_map = $this->util->get_timming_config();
        foreach ($timming_map as $k => $v) {
            $kpi_ids[] = 'rlc' . $v;
            $kpi_ids[] = 'nrc' . $v;
        }
        $kpi_ids = $this->util->add_field_name($kpi_ids);
        $kpi_ids_config = $this->getKpiIDs($this->db, $kpi_ids);
        $day_arr = $this->util->getDaysFromTiming($fromDate, $toDate, 4, false);
        $data = $this->get_kpi_data($kpi_ids_config, $kpi_type, $game_code, $day_arr, $table_name);

        $return_data = array();
        //for($i=0;$i<count($day_arr);$i++){
        for ($i = count($day_arr) - 1; $i >= 0; $i--) {
            $t = array();
            foreach ($kpi_ids_config as $key => $value) {
                $f_alias = $value;
                $t[$f_alias] = (isset($data[$day_arr[$i]][$f_alias])) ? $data[$day_arr[$i]][$f_alias] : 0;
            }

            $return_data[$i] = array_reverse($t);
            $return_data[$i]['log_date'] = $day_arr[$i];
            $return_data[$i] = array_reverse($return_data[$i]);
        }

        $return_data = array_values($return_data);
        return $return_data;
    }

    public function getAvgConversion($game,$array_dates)
    {
        $kpiConversion  = array('10001' => 'a1', '15001' => 'pu1');
        $listKpiId=array_keys($kpiConversion);

        $db = $this->load->database('ubstats', TRUE);
        $sql = "kpi_value,kpi_id,game_code,report_date";
        $db->select($sql, false);
        $db->from("game_kpi");
        $db->where_in('report_date', $array_dates);
        $db->where_in('kpi_id', $listKpiId);
        $db->where('game_code', $game);

        $db->order_by('game_kpi.kpi_value', 'desc');

        $query = $db->get();

//        echo $db->last_query();

        $result = $query->result_array();

        foreach ($result as $key => $value) {
            if(isset($value)){
                $kpiName = $kpiConversion[$value['kpi_id']];
                if(!isset($value['kpi_value'])){
                    $value['kpi_value']=0;
                }
                $log_date = $value[report_date];
                $data[$value['game_code']][$log_date][$kpiName] = $value['kpi_value'];
            }
        }

        $resultData[$game]["payrate"]=0;
        $payrateTotal = 0;
        $numberDay = count($array_dates);

        foreach ($array_dates as $date) {
            if($data[$game][$date]['a1']>0){
                $payrateTotal=$payrateTotal+ $data[$game][$date]['pu1']/$data[$game][$date]['a1'];
            }else{
                $payrateTotal= $payrateTotal+0;
            }

        }
        $resultData[$game]["payrate"]=$payrateTotal/$numberDay;

        return $resultData;
    }

    /*
     * getAvgRr1,3,7,30($kpiId, $dates)
     * create by lamnt6
     */

    public function getAvgRR($game,$array_dates,$kpiRRArray)
    {

        $listKpiId=array_keys($kpiRRArray);

        $db = $this->load->database('ubstats', TRUE);
        $sql = "avg(kpi_value) as kpi_value,kpi_id,game_code";
        $db->select($sql, false);
        $db->from("game_kpi");
        $db->where_in('report_date', $array_dates);
        $db->where_in('kpi_id', $listKpiId);
        $db->where('game_code', $game);
        $db->group_by('kpi_id','game_code');

        $db->order_by('game_kpi.kpi_value', 'desc');

        $query = $db->get();

//        echo $db->last_query();

        $result = $query->result_array();

        if(isset($result) && !empty($result)){
            foreach ($result as $key => $value) {
                if(isset($value)){
                    $kpiName = $kpiRRArray[$value['kpi_id']];
                    if(!isset($value['kpi_value'])){
                        $value['kpi_value']=0;
                    }
                    $data[$value['game_code']][$kpiName] = $value['kpi_value'];
                }
            }
        }else{
            $data[$game]['arnr1'] = 0;
            $data[$game]['arnr3'] = 0;
            $data[$game]['arnr7'] = 0;
            $data[$game]['arnr30'] = 0;
        }
        return $data;
    }

    public function get_channel_export_datatable($fromDate, $toDate, $game_code, $table_name, $kpi_ids, $kpi_type)
    {

        $yesterday = date("Y-m-d", time() - 24 * 60 * 60);
        if ($yesterday < $toDate)
            $toDate = $yesterday;

        $timming_map = $this->util->get_timming_config();
        foreach ($timming_map as $k => $v) {
            $kpi_ids[] = 'rlc' . $v;
            $kpi_ids[] = 'nrc' . $v;
        }
        $kpi_ids = $this->util->add_field_name($kpi_ids);
        $kpi_ids_config = $this->getKpiIDs($this->db, $kpi_ids);
        $day_arr = $this->util->getDaysFromTiming($fromDate, $toDate, 4, false);
        $data = $this->get_kpi_data_channel($kpi_ids_config, $kpi_type, $game_code, $day_arr, $table_name);

        $return_data = array();
        //for($i=0;$i<count($day_arr);$i++){
        for ($i = 0; $i < count($day_arr); $i++) {
            $t = array();
            foreach ($kpi_ids_config as $key => $value) {
                $channel_list = $data[$day_arr[$i]]['channel'];
                foreach ($channel_list as $channel) {
                    $f_alias = $value;
                    $t[$channel][$f_alias] = (isset($data[$day_arr[$i]][$channel][$f_alias])) ? $data[$day_arr[$i]][$channel][$f_alias] : 0;
                }


            }
            $return_data[$i] = $t;
            $return_data[$i]['log_date'] = $day_arr[$i];
            foreach (array_unique($data[$day_arr[$i]]['channel']) as $channel){
                $return_data[$i]['channel'][]=$channel;
            }
        }

        $return_data = array_values($return_data);
        return $return_data;
    }


    public function get_package_export_datatable($fromDate, $toDate, $game_code, $table_name, $kpi_ids, $kpi_type)
    {

        $yesterday = date("Y-m-d", time() - 24 * 60 * 60);
        if ($yesterday < $toDate)
            $toDate = $yesterday;

        $timming_map = $this->util->get_timming_config();
        foreach ($timming_map as $k => $v) {
            $kpi_ids[] = 'rlc' . $v;
            $kpi_ids[] = 'nrc' . $v;
        }
        $kpi_ids = $this->util->add_field_name($kpi_ids);
        $kpi_ids_config = $this->getKpiIDs($this->db, $kpi_ids);
        $day_arr = $this->util->getDaysFromTiming($fromDate, $toDate, 4, false);
        $data = $this->get_kpi_data_package($kpi_ids_config, $kpi_type, $game_code, $day_arr, $table_name);

        $return_data = array();
        //for($i=0;$i<count($day_arr);$i++){
        for ($i = 0; $i < count($day_arr); $i++) {
            $t = array();
            foreach ($kpi_ids_config as $key => $value) {
                $package_list = $data[$day_arr[$i]]['package'];
                foreach ($package_list as $package) {
                    $f_alias = $value;
                    $t[$package][$f_alias] = (isset($data[$day_arr[$i]][$package][$f_alias])) ? $data[$day_arr[$i]][$package][$f_alias] : 0;
                }


            }
            $return_data[$i] = $t;
            $return_data[$i]['log_date'] = $day_arr[$i];
            foreach (array_unique($data[$day_arr[$i]]['package']) as $package){
                $return_data[$i]['package'][]=$package;
            }
        }

        $return_data = array_values($return_data);
        return $return_data;
    }

    /*
     * getGameKpi($kpiId, $dates)
     * create by canhtq
     */

    public function getGameKpi($games, $kpiId, $date, $limit)
    {
        $db = $this->load->database('ubstats', TRUE);
        $sql = "game_kpi.game_code,game_kpi.report_date,game_kpi.kpi_value,games.GameName as game_name,games.release_date,games.owner,games.GameType2 as game_type";
        $db->select($sql, false);
        $db->from("game_kpi");
        $db->join('kpi_desc kd', 'game_kpi.kpi_id = kd.kpi_id', 'left');
        $db->join('mt_report_source mrs', 'mrs.game_code = game_kpi.game_code and mrs.group_id = kd.group_id and mrs.data_source = game_kpi.source', 'left');
        $db->join("games", "games.GameCode = game_kpi.game_code");
        $db->where("mrs.kpi_type", "game_kpi");
        $db->where('game_kpi.report_date', $date);
        $db->where('game_kpi.kpi_id', $kpiId);
        $db->where_in('games.GameCode', $games);
        $db->order_by('game_kpi.kpi_value', 'desc');
        if ($limit > 0) {
            $db->limit($limit);
        }
        $query = $db->get();
        $result = $query->result_array();
        //echo $db->last_query(); die;
        return $result;
    }


    public function getPcGameKpi($games, $kpiId, $date, $limit)
    {

        $db = $this->load->database('ubstats', TRUE);
        $sql = "game_kpi.game_code,game_kpi.report_date,game_kpi.kpi_value,games.GameName as game_name,games.release_date,games.owner,games.GameType2 as game_type";
        $db->select($sql, false);
        $db->from("game_kpi");
        $db->join('kpi_desc kd', 'game_kpi.kpi_id = kd.kpi_id', 'left');
        $db->join('mt_report_source mrs', 'mrs.game_code = game_kpi.game_code and mrs.group_id = kd.group_id and mrs.data_source = game_kpi.source', 'left');
        $db->join("games", "games.GameCode = game_kpi.game_code");
        $db->where("mrs.kpi_type", "game_kpi");
        $db->where('game_kpi.report_date', $date);
        $db->where('game_kpi.kpi_id', $kpiId);
        $db->where_in('games.GameType2', array(1, 3));
        $db->where_in('games.GameCode', $games);
        $db->order_by('game_kpi.kpi_value', 'desc');
        $db->limit($limit);
        $query = $db->get();
        $result = $query->result_array();
        //echo $db->last_query(); die;
        return $result;
    }

    public function getMobileGameKpi($games, $kpiId, $date, $limit)
    {

        $db = $this->load->database('ubstats', TRUE);
        $sql = "game_kpi.game_code,game_kpi.report_date,game_kpi.kpi_value,games.GameName as game_name,games.release_date,games.owner,games.GameType2 as game_type";
        $db->select($sql, false);
        $db->from("game_kpi");

        $db->join('kpi_desc kd', 'game_kpi.kpi_id = kd.kpi_id', 'left');
        $db->join('mt_report_source mrs', 'mrs.game_code = game_kpi.game_code and mrs.group_id = kd.group_id and mrs.data_source = game_kpi.source', 'left');
        $db->join("games", "games.GameCode = game_kpi.game_code");
        $db->where("mrs.kpi_type", "game_kpi");

        $db->where('game_kpi.report_date', $date);
        $db->where('game_kpi.kpi_id', $kpiId);
        $db->where_in('games.GameCode', $games);
        $db->where_in('games.GameType2', array(2));
        $db->order_by('game_kpi.kpi_value', 'desc');
        $db->limit($limit);
        $query = $db->get();
        $result = $query->result_array();
        //echo $db->last_query(); die;
        return $result;
    }

    private function get_db_field_config($fields)
    {
        $db_field_config = $this->util->db_field_config();
        $return = array();
        for ($i = 0; $i < count($fields); $i++) {
            foreach ($db_field_config['user_kpi'] as $key => $value) {
                if (isset($value[$fields[$i]])) {
                    $return[$fields[$i]] = $value[$fields[$i]];
                }
            }
            foreach ($db_field_config['revenue_kpi'] as $key => $value) {
                if (isset($value[$fields[$i]])) {
                    $return[$fields[$i]] = $value[$fields[$i]];
                }
            }
        }
        return $return;
    }

    public function getKpiDatatable($dates, $game_code, $table_name, $kpi_ids, $kpi_type, $allowKpis)
    {
        $yesterday = date("Y-m-d", time() - 24 * 60 * 60);
        /*
        if ($yesterday < $toDate)
            $toDate = $yesterday;
*/
        $timming_map = $this->util->get_timming_config();
        foreach ($timming_map as $k => $v) {
            $kpi_ids[] = 'rlc' . $v;
            $kpi_ids[] = 'nrc' . $v;
        }
        $kpi_ids = $this->util->add_field_name($kpi_ids);
        $kpi_ids_config = $this->getKpiIDs($this->db, $kpi_ids);


        $data = $this->get_kpi_data($kpi_ids_config, $kpi_type, $game_code, $dates, $table_name);
        $return_data = array();
        //for($i=0;$i<count($day_arr);$i++){

        for ($i = count($dates) - 1; $i >= 0; $i--) {
            $t = array();
            foreach ($kpi_ids_config as $key => $value) {
                $f_alias = $value;
                $t[$f_alias] = (isset($data[$dates[$i]][$f_alias])) ? $data[$dates[$i]][$f_alias] : 0;


            }
            $return_data[$i] = array_reverse($t);

            foreach ($timming_map as $ktime => $time) {

                $return_data[$i] ['rr' . $time] = number_format($return_data[$i] ['rr' . $time], 2) . '%';
                $return_data[$i] ['prr' . $time] = number_format($return_data[$i] ['prr' . $time], 2) . '%';

                $churnRate = 100 - $return_data[$i] ['rr' . $time];
                if ($churnRate == 100) {
                    $churnRate = 0;
                }
                $return_data[$i] ['cr' . $time] = number_format($churnRate, 2) . '%';
                $return_data[$i] ['nrr' . $time] = number_format($return_data[$i] ['nrr' . $time], 2) . '%';
                $return_data[$i] ['acu' . $time] = number_format($return_data[$i] ['acu' . $time], 2);

                $return_data[$i] ['arppu' . $time] = number_format($return_data[$i] ['gr' . $time] / $return_data[$i] ['pu' . $time], 2);
                $return_data[$i] ['arpu' . $time] = number_format($return_data[$i] ['gr' . $time] / $return_data[$i] ['a' . $time], 2);
                $return_data[$i] ['cvr' . $time] = number_format($return_data[$i] ['pu' . $time] * 100 / $return_data[$i] ['a' . $time], 2) . '%';
            }
            $return_data[$i]['log_date'] = $dates[$i];
            $return_data[$i] = array_reverse($return_data[$i]);

            $result = array();

            $result["log_date"] = $return_data[$i]["log_date"];

            foreach ($allowKpis as $key) {
                foreach ($timming_map as $ktime => $time) {
                    $result[$key . $time] = $return_data[$i][$key . $time];
                }
            }
            $return_data[$i] = $result;
        }


        $return_data = array_values($return_data);
        return $return_data;
    }
    
    public function calcCCU($dateFrom, $dateTo)
    {
    
    	$db = $this->load->database('ubstats', TRUE);
    	$sql = "max(kpi_value) as pcu, avg(kpi_value) as acu, game_code,source";
    	$db->select($sql, false);
    	$db->from("game_kpi");
    
    	$db->where('report_date >=', $dateFrom);
    	$db->where('report_date <=', $dateTo);
    	$db->where('kpi_id', '30001');
    	$db->group_by('game_code');
    	$db->group_by('source');
    	$query = $db->get();
    	$result = $query->result_array();
    	
    	return $result;
    }

}

