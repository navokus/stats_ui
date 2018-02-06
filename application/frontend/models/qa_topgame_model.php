<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 04/07/2016
 * Time: 14:35
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class qa_topgame_model extends MY_Model{

    public function __construct()
    {
        parent::__construct();
    }
    public function listGameByOs($lstGameCode, $arr_date, $timing)
    {
//        $listday = array();
//        foreach ($arr_date as $value){
//            $value = $this->util->user_date_to_db_date($value);
//
//            $listday[]=$value;
//        }

        $db_field_config = $this->util->db_field_config();

        if($timing==4){
            $kpi_field_config['a1'] = $db_field_config['user_kpi']['4']['a1'];
            $kpi_field_config['gr1'] = $db_field_config['revenue_kpi']['4']['gr1'];
        }else if ($timing==17){
            $kpi_field_config['a7'] = $db_field_config['user_kpi']['5']['a7'];
            $kpi_field_config['gr7'] = $db_field_config['revenue_kpi']['5']['gr7'];
        }else if ($timing==31){
            $kpi_field_config['a30'] = $db_field_config['user_kpi']['6']['a30'];
            $kpi_field_config['gr30'] = $db_field_config['revenue_kpi']['6']['gr30'];
        }

        $db = $this->load->database('ubstats', TRUE);

        $kpi_ids_config = $this->getKpiIDs($db, $kpi_field_config);

        $lstKpiId=array();
        foreach ($kpi_ids_config as $key => $value){
            $lstKpiId[] = $key;
        }

        $sql = "os_kpi.kpi_id,os_kpi.report_date,os_kpi.kpi_value";

        $db->select($sql, false);
        $db->from("os_kpi");
        $db->join('kpi_desc kd', 'os_kpi.kpi_id = kd.kpi_id', 'left');
        $db->join('mt_report_source mrs', 'mrs.game_code = os_kpi.game_code and mrs.group_id = kd.group_id and mrs.data_source = os_kpi.source', 'left');
        $db->join("games", "games.GameCode = os_kpi.game_code");
        $db->where_in('os_kpi.report_date', $arr_date);
        $db->where_in('os_kpi.game_code', $lstGameCode);
        $db->where_in('os_kpi.kpi_id', $lstKpiId);
        $db->order_by('os_kpi.report_date', 'asc');
        $query = $db->get();
        $resultQuery = $query->result_array();


//        var_dump($resultQuery);

        foreach ($resultQuery as $key =>$value){
            $kpiId = $value['kpi_id'];
            $kpiname=$kpi_ids_config[$kpiId];
            $logDate =$value['report_date'];
            $kpi_value = $value['kpi_value'];

            $data_json_arr = json_decode($kpi_value, true);
            $ios = 0;
            $android = 0;
            $other = 0;
            foreach ($data_json_arr as $key => $value) {
                if (strpos($key, "ios") !== FALSE) {
                    $ios += $value;
                } else if (strpos($key, "android") !== FALSE) {
                    $android += $value;
                } else {
                    $other += $value;
                }
            }

            $data_sum = array("logdate" =>$logDate, "ios" => $ios, "android" => $android, "other" => $other);

            $result[$kpiname][] = $data_sum;
            $arrlogdate[$logDate] = $logDate;

        }
        $resultnew =  array();
        foreach ($kpi_ids_config as $key => $kpiname){
            foreach ($arrlogdate as $keydate => $fvalue){
                foreach ($result[$kpiname] as $key => $vvalue){
                        if($keydate==$vvalue["logdate"]){
                            $resultnew[$kpiname][$this->util->get_xcolumn_by_timming($keydate,$timing,true)]["android"]+=$vvalue["android"];
                            $resultnew[$kpiname][$this->util->get_xcolumn_by_timming($keydate,$timing,true)]["ios"]+=$vvalue["ios"];
                            $resultnew[$kpiname][$this->util->get_xcolumn_by_timming($keydate,$timing,true)]["other"]+=$vvalue["other"];

                        }
                }
            }

        }

        return $resultnew;
    }


    public function listGamebyGameType($lstGameCode, $arr_date, $timing)
    {
        $db_field_config = $this->util->db_field_config();

        if($timing==4){
            $kpi_field_config['a1'] = $db_field_config['user_kpi']['4']['a1'];
            $kpi_field_config['gr1'] = $db_field_config['revenue_kpi']['4']['gr1'];
        }else if ($timing==17){
            $kpi_field_config['a7'] = $db_field_config['user_kpi']['5']['a7'];
            $kpi_field_config['gr7'] = $db_field_config['revenue_kpi']['5']['gr7'];
        }else if ($timing==31){
            $kpi_field_config['a30'] = $db_field_config['user_kpi']['6']['a30'];
            $kpi_field_config['gr30'] = $db_field_config['revenue_kpi']['6']['gr30'];
        }

        $db = $this->load->database('ubstats', TRUE);

        $kpi_ids_config = $this->getKpiIDs($db, $kpi_field_config);

        $lstKpiId=array();
        foreach ($kpi_ids_config as $key => $value){
            $lstKpiId[] = $key;
        }

        $sql = "sum(game_kpi.kpi_value) as kpi_value,game_kpi.report_date,game_kpi.kpi_id,games.GameType2 as gameType";

        $db->select($sql, false);
        $db->from("game_kpi");
        $db->join('kpi_desc kd', 'game_kpi.kpi_id = kd.kpi_id', 'left');
        $db->join('mt_report_source mrs', 'mrs.game_code = game_kpi.game_code and mrs.group_id = kd.group_id and mrs.data_source = game_kpi.source', 'left');
        $db->join("games", "games.GameCode = game_kpi.game_code");
        $db->where_in('game_kpi.report_date', $arr_date);
        $db->where_in('game_kpi.game_code', $lstGameCode);
        $db->where_in('game_kpi.kpi_id', $lstKpiId);
        $db->group_by("game_kpi.kpi_id,game_kpi.report_date,games.GameType2");
        $db->order_by('game_kpi.kpi_id', 'asc');
        $query = $db->get();
        $resultQuery = $query->result_array();

        foreach ($resultQuery as $key =>$value){
            $kpiId = $value['kpi_id'];
            $kpiname=$kpi_ids_config[$kpiId];
            $logDate =$value['report_date'];
            $kpi_value = $value['kpi_value'];
            $gameType = $value['gameType'];
            if(is_null($kpi_value) or empty($kpi_value)){
                $kpi_value=0;
            }
            $result[$kpiname][$this->util->get_xcolumn_by_timming($logDate,$timing,true)][$gameType] = $kpi_value;

        }

        return $result;
    }

    public function listGameByRevenue($lstGameCode, $arr_date, $timing)
    {
//        $listday = array();
//        foreach ($arr_date as $value){
//            $value = $this->util->user_date_to_db_date($value);
//
//            $listday[]=$value;
//        }

        $db_field_config = $this->util->db_field_config();

        if($timing==4){
            $kpi_field_config['gr1'] = $db_field_config['revenue_kpi']['4']['gr1'];
        }else if ($timing==17){
            $kpi_field_config['gr7'] = $db_field_config['revenue_kpi']['5']['gr7'];
        }else if ($timing==31){
            $kpi_field_config['gr30'] = $db_field_config['revenue_kpi']['6']['gr30'];
        }

        $db = $this->load->database('ubstats', TRUE);

        $kpi_ids_config = $this->getKpiIDs($db, $kpi_field_config);

        $lstKpiId=array();
        foreach ($kpi_ids_config as $key => $value){
            $lstKpiId[] = $key;
        }

        $sql = "sum(game_kpi.kpi_value) as kpi_value,game_kpi.kpi_id,game_kpi.game_code,games.GameName as gameName";
//        $sql = "game_kpi.kpi_value,game_kpi.report_date,game_kpi.kpi_id";


        $db->select($sql, false);
        $db->from("game_kpi");

        $db->join('kpi_desc kd', 'game_kpi.kpi_id = kd.kpi_id', 'left');
        $db->join('mt_report_source mrs', 'mrs.game_code = game_kpi.game_code and mrs.group_id = kd.group_id and mrs.data_source = game_kpi.source', 'left');
        $db->join("games", "games.GameCode = game_kpi.game_code");
        $db->where("mrs.kpi_type", "game_kpi");
        $db->where_in('game_kpi.report_date', $arr_date);
        $db->where_in('game_kpi.game_code', $lstGameCode);
        $db->where_in('game_kpi.kpi_id', $lstKpiId);
        $db->group_by("game_kpi.kpi_id,game_kpi.game_code");
        $db->order_by('game_kpi.kpi_value', 'desc');
        $db->limit(10);
        $query = $db->get();
        $resultQuery = $query->result_array();

        foreach ($resultQuery as $key =>$value){
            $kpiId = $value['kpi_id'];
            $kpiname=$kpi_ids_config[$kpiId];
            $game_name =$value['gameName'];
            $kpi_value = $value['kpi_value'];

            $result["game_".$kpiname][$game_name] = $kpi_value;
        }

        foreach ($kpi_ids_config as $key => $value){
            arsort($result["game_".$value]);
        }

        return $result;
    }


    public function listMobileGameByRevenue($lstGameCode, $arr_date, $timing)
    {
//        $listday = array();
//        foreach ($arr_date as $value){
//            $value = $this->util->user_date_to_db_date($value);
//
//            $listday[]=$value;
//        }

        $db_field_config = $this->util->db_field_config();

        if($timing==4){
            $kpi_field_config['gr1'] = $db_field_config['revenue_kpi']['4']['gr1'];
        }else if ($timing==17){
            $kpi_field_config['gr7'] = $db_field_config['revenue_kpi']['5']['gr7'];
        }else if ($timing==31){
            $kpi_field_config['gr30'] = $db_field_config['revenue_kpi']['6']['gr30'];
        }

        $db = $this->load->database('ubstats', TRUE);

        $kpi_ids_config = $this->getKpiIDs($db, $kpi_field_config);

        $lstKpiId=array();
        foreach ($kpi_ids_config as $key => $value){
            $lstKpiId[] = $key;
        }

        $sql = "sum(game_kpi.kpi_value) as kpi_value,game_kpi.kpi_id,games.GameName as gameName";
        $db->select($sql, false);
        $db->from("game_kpi");

        $db->join('kpi_desc kd', 'game_kpi.kpi_id = kd.kpi_id', 'left');
        $db->join('mt_report_source mrs', 'mrs.game_code = game_kpi.game_code and mrs.group_id = kd.group_id and mrs.data_source = game_kpi.source', 'left');
        $db->join("games", "games.GameCode = game_kpi.game_code");
        $db->where("mrs.kpi_type", "game_kpi");

        $db->where_in('game_kpi.report_date', $arr_date);
        $db->where_in('game_kpi.kpi_id', $lstKpiId);
        $db->where_in('games.GameCode', $lstGameCode);
        $db->where_in('games.GameType2', array(2));
        $db->group_by("game_kpi.kpi_id,game_kpi.game_code");
        $db->order_by('game_kpi.kpi_value', 'desc');
        $db->limit(10);

        $query = $db->get();
        $resultQuery = $query->result_array();
        //echo $db->last_query(); die;


        foreach ($resultQuery as $key =>$value){
            $kpiId = $value['kpi_id'];
            $kpiname=$kpi_ids_config[$kpiId];
            $game_name =$value['gameName'];
            $kpi_value = $value['kpi_value'];
            $result["mobile_".$kpiname][$game_name] = $kpi_value;
        }
        foreach ($kpi_ids_config as $key => $value){
            arsort($result["mobile_".$value]);
        }

        return $result;
    }

}