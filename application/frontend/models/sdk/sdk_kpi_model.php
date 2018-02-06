<?php

/**
 * Created by IntelliJ IDEA.
 * User: quangctn
 * Date: 09/10/2017
 * Time: 15:06
 */
class sdk_kpi_model extends MY_Model
{
    public function getDataKpi($lstGameCode, $lstKpi, $lstDate)
    {
        foreach ($lstKpi as $kpi) {
            if (strpos($kpi, '/') == true) {
                $calcKpi = explode('/', $kpi);
                foreach ($calcKpi as $calc) {
                    $lstKpi[] = $calc;
                }
            }
        }
        $source = "sdk";
        $db = $this->load->database('ubstats', TRUE);
        $db->select("a.*", "");
        $db->from("game_kpi a");
        $db->join('games b', 'a.game_code = b.GameCode', 'left');
        $db->where("source", $source);
        $db->where_in('game_code', $lstGameCode);
        $db->where_in('kpi_id', $lstKpi);
        $db->where_in("report_date", $lstDate);
        $db->where("b.Status != 0");
        $db->order_by("report_date", 'desc');
        $query = $db->get();
        /*var_dump($db->last_query());*/
        $result = $query->result_array();
        /* var_dump($result);*/
        $data = array();
        foreach ($result as $key => $value) {
            $data[$value['report_date']][$value['game_code']][$value['kpi_id']] = $value['kpi_value'];
        }

        $data2 = $this->setZeroForMissKPI($data, $lstKpi);
        $data2 = $this->calcKpi($data2, $lstGameCode, $lstDate);

        return $data2;


    }

    private function calcKpi($data, $gameCode, $lstDate)
    {
        foreach ($lstDate as $date) {

            //find special kpi
            $lstKpi = array_keys($data[$date][$gameCode]);
            foreach ($lstKpi as $kpi) {
                if (strpos($kpi, '/') == true) {
                    $calcKpi = explode('/', $kpi);
                    $data[$date][$gameCode][$kpi] = $data[$date][$gameCode][$calcKpi[0]] / $data[$date][$gameCode][$calcKpi[1]];
                }
            }
        }
        return $data;

    }

    private function setZeroForMissKPI($data, $lstKpi)
    {
        foreach ($data as $report_date => $value) {
            $lstKpiDb = array();
            foreach ($value as $gameCode => $data2) {
                $lstKpiDb = array_keys($data2);
                //Compare between kpi that configured and kpi get from query
                // If not found, set zero for it
                if (!(count($lstKpi) == count($lstKpiDb))) {
                    $missingKpi = array_diff($lstKpi, $lstKpiDb);
                    if (count($missingKpi) != 0) {
                        foreach ($missingKpi as $key) {
                            $data[$report_date][$gameCode][$key] = "0";
                        }
                    }
                }
            }
        }

        return $data;
    }
    
}