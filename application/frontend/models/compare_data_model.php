<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 04/07/2016
 * Time: 14:35
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class compare_data_model extends MY_Model{

    public function __construct()
    {
        parent::__construct();
    }
    public function getDataFromIngame($gameCode, $arr_date)
    {

//        $db = $this->load->database('ubstats', TRUE);

        $this->db->select('game_code,kpi_id,kpi_value,report_date');
        $this->db->from("game_kpi");
        $this->db->where_in('report_date', $arr_date);
        $this->db->where('game_code', $gameCode);
        $this->db->where('kpi_id', "10001");
        $this->db->where('source', "ingame");

        $query = $this->db->get();

        $resultQuery = $query->result_array();

        return $resultQuery;

    }


    public function getDataFromSDK($gameCode, $arr_date)
    {

        $this->db->select('game_code,kpi_id,kpi_value,report_date');
        $this->db->from("qc_game_kpi");
        $this->db->where_in('report_date', $arr_date);
        $this->db->where('game_code', $gameCode);
        $this->db->where('kpi_id', "10001");
        $this->db->where('source', "sdk");

        $query = $this->db->get();

        $resultQuery = $query->result_array();

        return $resultQuery;
    }
}