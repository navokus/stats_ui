<?php

/**
 * Created by IntelliJ IDEA.
 * User: quangctn
 * Date: 19/10/2017
 * Time: 15:09
 */
class sdk_package_model extends MY_Model
{
    public function getDataKpi($lstGameCode, $lstKpi, $lstDate)
    {
        $source = "sdk";

        $db = $this->load->database('ubstats', TRUE);
        $db->select("a.*", "");
        $db->from("package_kpi a");
        $db->join('games b', 'a.game_code = b.GameCode', 'left');
        $db->where("source", $source);
        $db->where_in('game_code', $lstGameCode);
        $db->where_in('kpi_id', $lstKpi);
        $db->where_in("report_date", $lstDate);
        $db->where("b.Status != 0");
        $db->order_by("report_date", 'asc');
        $query = $db->get();
        /*var_dump($db->last_query());*/
        $result = $query->result_array();
        //var_dump($result);
        //exit();
        $data = array();
        foreach ($result as $key => $value) {
            $data[$value['report_date']]
            [$value['game_code']]
            [$value['package']]
            [$value['kpi_id']] = $value['kpi_value'];
        }
        //var_dump($data);exit();
        $data2 = $this->setZeroForMissKPI($data, $lstKpi);
        return $data2;


    }

    private function setZeroForMissKPI($data, $lstKpi)
    {
        foreach ($data as $report_date => $value) {
            foreach ($value as $gameCode => $data2) {
                $lstKpiDb = array();
                foreach ($data2 as $channel => $data3) {
                    $lstKpiDb = array_keys($data3);
                    //var_dump(!(count($lstKpi) == count($lstKpiDb)));exit();
                    if (!(count($lstKpi) == count($lstKpiDb))) {
                        $missingKpi = array_diff($lstKpi, $lstKpiDb);
                        // var_dump($missingKpi);exit();
                        if (count($missingKpi) != 0) {
                            foreach ($missingKpi as $key) {
                                $data[$report_date][$gameCode][$channel][$key] = "0";
                            }
                        }
                    }
                }
            }
        }
        return $data;
    }
}