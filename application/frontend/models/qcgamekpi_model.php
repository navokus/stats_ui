<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by IntelliJ IDEA.
 * User: canhtq
 * Date: 12/06/2017
 * Time: 17:05
 */
class Qcgamekpi_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public function getData($date,$gameCode)
    {
        $this->db->select('game_code,kpi_id,kpi_value');
        $this->db->from("qc_game_kpi");
        $this->db->where('report_date', $date);
        $this->db->where('game_code', $gameCode);
        $query = $this->db->get();
        $arr = $query->row_array();
        return $arr;
    }

    public function getGamesData($date)
    {
        $this->db->select('game_code,kpi_id,kpi_value');
        $this->db->from("qc_game_kpi");
        $this->db->where('report_date', $date);
        $query = $this->db->get();
        $arr = $query->result_array();
        $rs = array();
        foreach ($arr as $row) {
            //$tmp=array();
            //$tmp[$row["kpi_id"]]=$row["kpi_value"];
            $rs[$row["game_code"]][$row["kpi_id"]] =floatval($row["kpi_value"]);

        }
        return $rs;
    }
}