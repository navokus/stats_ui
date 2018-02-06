<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 25/04/2016
 * Time: 13:57
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Behavior_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();

    }

    public function get_hourly_report_synchronized($game_code, $server_id,$report_date,$kpi_code_arr){
        $kpi_ids_config = $this->getKpiIDs(null, $kpi_code_arr);
        $report_source_config = $this->get_report_source($game_code);
        $kpi_by_group_id = array();
        foreach($kpi_ids_config as $kpi_id => $kpi_code){
            $_group_id = isset($this->kpi_config[$kpi_id]['group_id']) ? $this->kpi_config[$kpi_id]['group_id'] : 1;
            $kpi_by_group_id[$report_source_config["hourly_kpi"][$_group_id]][] = $kpi_id;
        }

        $return = array();
        if($server_id == ""){
            foreach($kpi_by_group_id as $data_source => $kpi_id_arr){
                $this->db->select("kpi_value, report_date,game_code, kpi_id");
                $this->db->from("game_kpi_hourly");
                $this->db->where("report_date", $report_date);
                $this->db->where("game_code", $game_code);
                $this->db->where_in("kpi_id", $kpi_id_arr);
                $this->db->where("source", $data_source);
                $query=$this->db->get();
                $result = $query->result_array();

                $return = array();
                for($i=0;$i<count($result);$i++){
                    $return[$kpi_ids_config[$result[$i]['kpi_id']]] = $result[$i]['kpi_value'];
                }
            }
        }else{
            foreach($kpi_by_group_id as $data_source => $kpi_id_arr){
                $this->db->select("kpi_value, report_date,game_code, kpi_id");
                $this->db->from("server_kpi_hourly");
                $this->db->where("report_date", $report_date);
                $this->db->where("game_code", $game_code);
                $this->db->where_in("kpi_id", $kpi_id_arr);
                $this->db->where("source", $data_source);
                $this->db->where("server_id", $server_id);
                $query=$this->db->get();
                $result = $query->result_array();

                $return = array();
                for($i=0;$i<count($result);$i++){
                    $return[$kpi_ids_config[$result[$i]['kpi_id']]] = $result[$i]['kpi_value'];
                }
            }
        }


        return $return;
    }


    public function get_od_data($game_code, $date_1,$date_2, $kpi_ids_config){
        $report_source_config = $this->get_report_source($game_code);
        $kpi_by_group_id = array();
        foreach($kpi_ids_config as $kpi_id => $kpi_code){
            $_group_id = isset($this->kpi_config[$kpi_id]['group_id']) ? $this->kpi_config[$kpi_id]['group_id'] : 1;
            $kpi_by_group_id[$report_source_config["hourly_kpi"][$_group_id]][] = $kpi_id;
        }

        foreach($kpi_by_group_id as $data_source => $kpi_id_arr) {

            $sql = "kpi_value, report_date, kpi_id";
            $this->db->select($sql, false);
            $this->db->from("game_kpi_hourly");
            $this->db->where('game_code', $game_code);
            $this->db->where_in('kpi_id', $kpi_id_arr);
            $this->db->where('source', $data_source);
            $this->db->where_in('report_date', array($date_1,$date_2));
            $query = $this->db->get();
            $result = $query->result_array();
            for ($i = 0; $i < count($result); $i++) {
                $return[$result[$i]['report_date']] = $result[$i]['kpi_value'];
            }
        }
        return $return;
    }

    public function get_od_data_by_server($game_code, $date_1,$date_2, $kpi_ids_config, $server_id){
        $report_source_config = $this->get_report_source($game_code);
        $kpi_by_group_id = array();
        foreach($kpi_ids_config as $kpi_id => $kpi_code){
            $_group_id = isset($this->kpi_config[$kpi_id]['group_id']) ? $this->kpi_config[$kpi_id]['group_id'] : 1;
            $kpi_by_group_id[$report_source_config["server_kpi_hourly"][$_group_id]][] = $kpi_id;
        }
        foreach($kpi_by_group_id as $data_source => $kpi_id_arr) {

            $sql = "kpi_value, report_date, kpi_id";
            $this->db->select($sql, false);
            $this->db->from("server_kpi_hourly");
            $this->db->where('game_code', $game_code);
            $this->db->where('server_id', $server_id);
            $this->db->where_in('kpi_id', $kpi_id_arr);
            $this->db->where('source', $data_source);
            $this->db->where_in('report_date', array($date_1,$date_2));

            $query = $this->db->get();
            $result = $query->result_array();

            for ($i = 0; $i < count($result); $i++) {
                $return[$result[$i]['report_date']] = $result[$i]['kpi_value'];
            }
        }
        return $return;
    }

    public function get_add_spent_by_level($game_code, $log_date, $report_code){
        $report_source_config = $this->get_report_source($game_code);
        $source = $report_source_config['game_kpi'][1];

        $this->db->select("*", false);
        $this->db->from("custom_report");
        $this->db->where('game_code', $game_code);
        $this->db->where('source', $source);
        $this->db->where('log_date', $log_date);
        $this->db->where('report_code', $report_code);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    public function get_top_user($game_code, $log_date, $order_by){
        $report_source_config = $this->get_report_source($game_code);
        $source = $report_source_config['game_behavior'][1];

        $this->db->select("*", false);
        $this->db->from("top_user");
        $this->db->where('game_code', $game_code);
        $this->db->where('source', $source);
        $this->db->where('log_date', $log_date);
        $this->db->order_by($order_by, "desc");
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;

    }

    public function getGameplayFrequencyData($gameCode, $timming, $fre_type, $from)
    {
        $timming_arr = array('4'=>'daily','5'=>'weekly','6'=>'monthly');
        $current_m = date('m');
        $from_m = date('m',strtotime($from));
        $current_date = date('y-m-d');
        $from_date = date('y-m-d',$from);

        if($timming_arr[$timming] == "monthly" && $current_m == $from_m){
            $max_log_date = $this->_get_max_log_date($gameCode,"monthly",$fre_type);
            if($max_log_date != ""){
                $from = $max_log_date;
            }
        }

        $ubstats = $this->load->database('ubstats', TRUE);
        $ubstats->select("date_format(report_date,'%Y-%m-%d') as log_date, frequency, total", false);
        $ubstats->from("playfrequency");
        $ubstats->where('game_code', $gameCode);
        $ubstats->where('report_date', $from);
        $ubstats->where('fre_type', $fre_type);
        $ubstats->where('timing', $timming_arr[$timming]);
        $ubstats->order_by('frequency', 'asc');
        $ubstats->group_by('frequency');
        $query = $ubstats->get();
        $result = $query->result_array();
        return $result;

    }
    private function _get_max_log_date($game_code,$timing,$fre_type){
        $ubstats = $this->load->database('ubstats', TRUE);
        $ubstats->select("max(report_date) as max_log_date", false);
        $ubstats->from("playfrequency");
        $ubstats->where('game_code', $game_code);
        $ubstats->where('fre_type', $fre_type);
        $ubstats->where('timing', $timing);
        $query = $ubstats->get();
        $result = $query->result_array();
        $max_log_date = isset($result[0]['max_log_date']) ? $result[0]['max_log_date'] : "";
        return $max_log_date;

    }
}