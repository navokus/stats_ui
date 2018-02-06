<?php

/**
 * Created by IntelliJ IDEA.
 * User: canhtq
 * Date: 09/06/2017
 * Time: 15:22
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class test_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getDataServer($startDate, $endDate,$kpi)
    {
        $source = "ingame";
        $db = $this->load->database('ubstats', TRUE);
        $db->select("report_date,game_code,kpi_value,kpi_id");
        $db->from("server_kpi_json");
        $db->where("source", $source);
        $db->where_in('game_code', 'jxm');
        $db->where('kpi_id', $kpi);
        $db->where('report_date BETWEEN "' . $startDate . '" and "' . $endDate . '"');
        $db->order_by("report_date", 'desc');
        $query = $db->get();
        $result = $query->result_array();
        /* var_dump($result);*/
        $data = array();
        foreach ($result as $key => $value) {
            $data[$value['report_date']][$value['game_code']][$value['kpi_id']] = $this->parseJson($value['kpi_value']);
        }
        return $data;

    }

    private function parseJson($jsonStr)
    {
        //var_dump($jsonStr);
        $data = array();
        $obj = json_decode($jsonStr);
        foreach ($obj as $key => $value) {
            $data[$key] = (string)$value;
        }
        return $data;
        //var_dump($data);exit();
    }

}