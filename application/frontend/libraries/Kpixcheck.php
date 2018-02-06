<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by IntelliJ IDEA.
 * User: canhtq
 * Date: 30/06/2017
 * Time: 11:07
 */
class Kpixcheck
{
    function get_daily_kpi_ids($gameCode)
    {
        $ids = array("10001","11001","11007");
        if(isset($gameCode)){
            switch ($gameCode) {
                case "3qmobile":
                    $ids = array("15001","11001");
                    break;
                default:
                    $ids=array("10001","11001","11007");
            }
        }

        return $ids;
    }
    function get_name($kpiId,$gameCode)
    {
        $tId=substr($kpiId, 0,2);
        $name="";
        switch ($tId) {
            case "10":
                $name="Active";
                break;
            case "blue":
                $name="Active";
                break;
            case "green":
                $name="Active";
                break;
            default:
                $name="Active";
        }
        return $name;
    }
}