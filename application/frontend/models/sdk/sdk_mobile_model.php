<?php

/**
 * Created by IntelliJ IDEA.
 * User: quangctn
 * Date: 20/10/2017
 * Time: 14:40
 */
class sdk_mobile_model extends MY_Model
{
    public function getDataKpi($lstGameCode, $lstKpi, $lstDate)
    {

        $source = "sdk";
        $db = $this->load->database('ubstats', TRUE);
        $db->select("a.*", "");
        $db->from("os_kpi a");
        $db->join('games b', 'a.game_code = b.GameCode', 'left');
        $db->where("source", $source);
        $db->where_in('game_code', $lstGameCode);
        $db->where_in('kpi_id', $lstKpi);
        $db->where_in("report_date", $lstDate);
        $db->where("b.Status != 0");
        $db->order_by("report_date", 'desc');
        $query = $db->get();
        //var_dump($db->last_query());exit();
        $result = $query->result_array();
        //var_dump($result);exit();
        $data = array();
        foreach ($result as $key => $value) {
            $data[$value['report_date']][$value['game_code']][$value['kpi_id']] = $this->parseJson($value['kpi_value']);;
        }

        $data2 = $this->setZeroForMissKPI($data, $lstKpi);
      /*  var_dump($data2);exit();*/
        return $data2;


    }

    private function setZeroForMissKPI($data, $lstKpi)
    {
        foreach ($data as $report_date => $value) {
            $lstKpiDb = array();
            foreach ($value as $gameCode => $data2) {
                $lstKpiDb = array_keys($data2);
                //var_dump(!(count($lstKpi) == count($lstKpiDb)));exit();
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

    private function parseJson($jsonStr)
    {
        //var_dump($jsonStr);
        $data = array();
        $obj = json_decode($jsonStr);
        foreach ($obj as $key => $value) {
            $data[$key]=(string)$value;
        }
        return $data;
        //var_dump($data);exit();
    }
}