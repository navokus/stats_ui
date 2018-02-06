<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by IntelliJ IDEA.
 * User: canhtq
 * Date: 30/06/2017
 * Time: 11:07
 */
class Dateutils
{
    public function get_dates_for_daily_email($select_date){
        $return = array();

        $return[] = $select_date;
        $return[] = date('Y-m-d', strtotime('-1 day '. $select_date));
        $return[] = date('Y-m-d', strtotime('-2 day '. $select_date));

        $return[] = date('Y-m-d', strtotime('-7 day '. $select_date));
        $return[] = date('Y-m-d', strtotime('-8 day '. $select_date));

        $return[] = date('Y-m-d', strtotime('-30 day '. $select_date));
        $return[] = date('Y-m-d', strtotime('-31 day '. $select_date));

        return $return;
    }

    public function get_range_date_from_picker($range){
        $dates = explode("-", $range);
        $rs=array();
        $date = trim($dates[0]);
        $parts = explode("/", $date);
        $rs['from_date']=$parts[2]."-".$parts[1]."-".$parts[0];
        $date = trim($dates[1]);
        $parts = explode("/", $date);
        $rs['to_date']=$parts[2]."-".$parts[1]."-".$parts[0];
        return $rs;
    }

    public function get_picker_range_date($from_date,$to_date){
        $rs=array();
        $parts = explode("-", $from_date);
        $rs['from_date']=$parts[2]."/".$parts[1]."/".$parts[0];
        $parts = explode("-", $to_date);
        $rs['to_date']=$parts[2]."/".$parts[1]."/".$parts[0];
        return $rs["from_date"]." - ".$rs["to_date"];
    }

    public function add_date($date,$add){

        return date('Y-m-d', strtotime($add.' day '. $date));
    }

}