<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 16/03/2016
 * Time: 18:01
 */

class RevenueKpi_model extends MY_Model
{
    private $_table_name="game_kpi";
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Modify date: 2016-08-04
     * By : vinhdp
     * Add: process for timing = 17, 31
     */
    public function getRevenueKpiData($gameCode, $kpi_type, $timming, $from, $to)
    {
        $yesterday = date("Y-m-d", time() - 24*60*60);
        if($yesterday < $to)
            $to = $yesterday;
        $day_arr = $this->util->getDaysFromTiming($from, $to, $timming, false);
        $ubstats = $this->load->database('ubstats', TRUE);
        $db_field_config = $this->util->db_field_config();
        $kpi_field_config = $db_field_config['revenue_kpi'][$timming];
        $kpi_ids_config = $this->getKpiIDs($ubstats, $kpi_field_config);

        /**
         *  process for weekly and monthly
         *  if last selected week or month is not end, select the last day of this time in database
         */
        $count = count($day_arr);
        $to = $day_arr[$count - 1];
        
        if($timming == "17" || $timming == "31"){
            $max_log_date = $this->get_max_log_date_1($ubstats,$kpi_ids_config,$gameCode);
            if ($max_log_date != "" && !in_array($max_log_date, $day_arr)) {
                sort($day_arr);
                for($i=0;$i<count($day_arr);$i++){
                    if(strtotime($day_arr[$i]) >= strtotime($max_log_date)){
                        unset($day_arr[$i]);
                    }
                }
                // max log_data > last selected day
                if(strtotime($max_log_date)	<= strtotime($to)){
                	
                	$day_arr[] = $max_log_date;
                }
                $day_arr = array_values($day_arr);
            }
        }

        $data = $this->get_kpi_data($kpi_ids_config, $kpi_type, $gameCode, $day_arr,$this->_table_name);

        $return_data=array();
        $have_data=false;
        for($i=0;$i<count($day_arr);$i++){
            $t=array();
            foreach($kpi_ids_config as $key => $value){
                $f_alias = $value;
                $t[$f_alias] = (isset($data[$day_arr[$i]][$f_alias])) ? $data[$day_arr[$i]][$f_alias] : 0;
            }
            if($have_data == false){
                if(array_sum($t)!=0){
                    $return_data[$i] = $t;
                    $return_data[$i]['log_date'] = $day_arr[$i];
                    $have_data=true;
                }
            }else{
                $return_data[$i] = $t;
                $return_data[$i]['log_date'] = $day_arr[$i];
            }
        }
        $return_data = array_values($return_data);
        if ($max_log_date != "") {
            $return_data['max_log_date'] = $max_log_date;
        }
        
        return $return_data;
    }
}
