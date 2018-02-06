<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by IntelliJ IDEA.
 * User: canhtq
 * Date: 30/06/2017
 * Time: 11:07
 */
class Kpiconfig
{
    function get_daily_payment($gameCode)
    {
        $ids = array("16001");
        if (isset($gameCode)) {
            switch ($gameCode) {
                case "3qmobile":
                    $ids = array("10001", "16001");
                    break;
                default:
                    $ids = array("16001");
            }
        }

        return $ids;
    }

    function get_qa_kpi_daily($gameCode)
    {
        $ids = array("10001", "16001", "52001");
        if (isset($gameCode)) {
            switch ($gameCode) {
                case "3qmobile":
                    $ids = array("10001", "16001");
                    break;
                default:
                    $ids = array("10001", "16001", "52001");
            }
        }

        return $ids;
    }

    function get_daily_kpi_ids($gameCode)
    {
        $ids = array("10001", "16001");
        if (isset($gameCode)) {
            switch ($gameCode) {
                case "3qmobile":
                    $ids = array("10001", "16001");
                    break;
                default:
                    $ids = array("10001", "16001");
            }
        }

        return $ids;
    }

    function get_name($kpiId, $gameCode)
    {
        $tId = substr($kpiId, 0, 2);
        $name = "";
        switch ($tId) {
            case "10":
                $name = "Active";
                break;
            case "blue":
                $name = "Active";
                break;
            case "green":
                $name = "Active";
                break;
            default:
                $name = "Active";
        }
        return $name;
    }

    function get_Aclass()
    {
        $ids = array("16001" => 100000000, "10001" => 20000);
        return $ids;
    }

    private function getBasicKpi($timing)
    {
        switch ($timing) {
            case "daily":
                return array('10001' => 'A1', '10003' => 'A3', '10007' => 'A7', '10014' => 'A14', '10030' => 'A30',
                    '11001' => 'N1', '11003' => 'N3', '11007' => 'N7', '21014' => 'N14', '11030' => 'N30',
                    '16001' => 'Net in 1 day', '16003' => 'Net in 3 day', '16007' => 'Net in 7 day', '16014' => 'Net in 14 day', '16030' => 'Net in 30 day',
                    '15001' => 'PU1', '15003' => 'PU3', '15007' => 'PU7', '15014' => 'PU14', '15030' => 'PU30',
                    '28001' => 'RR1', '28003' => 'RR3', '28007' => 'RR7', '28014' => 'RR14', '28030' => 'RR30',
                    '16001/15001' => 'ARPPU1', '16003/15003' => 'ARPPU3', '16007/15007' => 'ARPPU7', '16014/15014' => 'ARPPU14', '16030/15030' => 'ARPPU30',
                    '16001/10001' => 'ARPU1', '16003/10003' => 'ARPU3', '16007/10007' => 'ARPU7', '16014/10014' => 'ARPU14', '16030/10030' => 'ARPU30'
                );
            case "weekly":
                return array('10017' => 'Weekly Active User', '11017' => 'Weekly New User', '16017' => 'Weekly Revenue',
                    '28017' => 'Weekly RR(%)', '19017' => 'Weekly New Paying Users', '20017' => 'Weekly Revenue of New Paying Users',
                    '16017/15017' => 'Weekly ARPPU', '16017/10017' => 'Weekly ARPU');
            case "monthly":
                return array('10031' => 'Monthly Active Users', '11031' => 'Monthly New Users', '15031' => 'Monthly Paying User',
                    '16031' => 'Monthly Revenue', '28031' => 'Monthly RR(%)', '19031' => 'Monthly New Paying Users', '53031' => 'Monthly Revenue of New Paying Users',
                    '16031/15031' => 'Monthly ARPPU', '16031/10031' => 'Monthly ARPU');
        }
    }

    private function kpisGameCode($gameCode, $timing)
    {
        $config['stct']['daily'] = array('10001' => 'A1', '10003' => 'A3', '15001' => 'PU1', '15003' => 'PU3', '16001' => 'Net in 1 day', '16003' => 'Net in 3 days');
        $config['stct']['weekly'] = array('10007' => 'A7', '11007' => 'N7', '15007' => 'PU7', '16007' => 'Net in 7 days');
        $config['stct']['monthly'] = array('10030' => 'A30', '11030' => 'N30', '15030' => 'PU30', '16030' => 'Net in 30 days');
        /*        $config['3qmobile']['daily'] = array('10001' => 'A1', '10003' => 'A3','16001' => 'Net in 1 day');
                $config['3qmobile']['weekly'] = array('10001' => 'A1', '10003' => 'A3');
                $config['3qmobile']['monthly'] = array('10001' => 'A1', '10003' => 'A3');*/

        return $config[$gameCode][$timing];
    }

    public function getListKpiByGameCode($gameCode, $timing)
    {
        $kpis = $this->getBasicKpi($timing);
        $kpiGameCode = $this->kpisGameCode($gameCode, $timing);

        if (isset($kpiGameCode)) {
            return $kpiGameCode;
        }
        return $kpis;
        /*$config['stct'] = array('10031' => 'Active User',
            '16031' => 'Revenue Net ',
            '52031' => 'Revenue Gross',
            '11031' => 'Register User',
            '15031' => 'Paying User',
           '21031' => 'New Register User');*/

    }

}