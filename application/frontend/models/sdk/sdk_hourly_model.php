<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 14/03/2016
 * Time: 23:36
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hourly_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public function get_hourly_report_synchronized($game_code, $report_date,$kpi_code_arr){
        $kpi_ids_config = $this->getKpiIDs(null, $kpi_code_arr);
//        $report_source_config = $this->get_report_source($game_code);
        $kpi_by_group_id = array();
        foreach($kpi_ids_config as $kpi_id => $kpi_code){
//            $_group_id = isset($this->kpi_config[$kpi_id]['group_id']) ? $this->kpi_config[$kpi_id]['group_id'] : 1;
            $kpi_by_group_id['sdk'] = $kpi_id;
        }

        $return = array();
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

        return $return;
    }

    /*
    public function get_hourly_report($game_code, $date_arr){
        $kpi_ids = array();
        $kpi_ids[] = 16001; //gr1

        $this->db->select("kpi_value, report_date,game_code");
        $this->db->from("game_kpi_hourly");
        $this->db->where_in("report_date", $date_arr);
        $this->db->where("game_code", $game_code);
        $this->db->where("kpi_id", "16001");
        $this->db->where("source", "sdk");
        $query=$this->db->get();
        $result = $query->result_array();

        $return = array();
        for($i=0;$i<count($result);$i++){
            $return['gr'][$result[$i]['report_date']] = $result[$i]['kpi_value'];
        }
        return $return;

    }
*/
}