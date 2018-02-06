<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 14/03/2016
 * Time: 23:36
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kpi_report_model extends CI_Model {

    private $table = 'kpi_daily_report';

    public function __construct()
    {
        parent::__construct();

    }

    public function getDailyDataDrawChart($gameCode, $timming, $calculateDateFrom,$calculateDateTo)
    {

        $dau_a1_field='dau_acc_a1';
        $nru_a1_field='nru_acc_a1';
        $this->db->select("date_format(log_date,'%Y-%m-%d') as CalculateDate, $dau_a1_field as ActiveAccountTotal, $nru_a1_field as NewRoleRegister",false);
        $this->db->from($this->table);
        $this->db->where('game_code', $gameCode);
        $this->db->where('log_date >=', $calculateDateFrom);
        $this->db->where('log_date <=', $calculateDateTo);
        $this->db->where('calculated_by', 'timing');
        $this->db->order_by('log_date', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        $sql = $this->db->last_query();
        file_put_contents("/tmp/tuonglv/sql.txt",$sql . "\n\n",FILE_APPEND);
        return $result;

    }

    public function getWeeklyDataDrawChart($gameCode, $timming, $calculateDateFrom,$calculateDateTo)
    {
        $wau_a7_field='wau_acc_a7';
        $nru_a7_field='nru_acc_a7';
        $this->db->select("date_format(log_date,'%Y-%m-%d') as CalculateDate, $wau_a7_field as ActiveAccountTotal, $nru_a7_field as NewRoleRegister",false);
        $this->db->from($this->table);
        $this->db->where('game_code', $gameCode);
        $weekIn = $this->getWeeksFromDate($calculateDateFrom,$calculateDateTo);
        $this->db->where_in('log_date',$weekIn);
        $this->db->where('calculated_by', 'timing'); //timing la kpi
        $this->db->order_by('log_date', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        $sql = $this->db->last_query();
        //var_dump($sql);
        //file_put_contents("/tmp/tuonglv/sql.txt",$sql . "\n\n",FILE_APPEND);
        //var_dump($result);exit();
        return $result;

    }

    public function getMonthlyDataDrawChart($gameCode, $timming, $calculateDateFrom,$calculateDateTo)
    {
        $mau_a30_field='mau_acc_a30';
        $nru_a30_field='nru_acc_a30';
        $this->db->select("date_format(log_date,'%Y-%m-%d') as CalculateDate, $mau_a30_field as ActiveAccountTotal, $nru_a30_field as NewRoleRegister",false);
        $this->db->from($this->table);
        $this->db->where('game_code', $gameCode);
        $monthIn = $this->getMonthsFromDate($calculateDateFrom,$calculateDateTo);
        $this->db->where_in('log_date',$monthIn);
        $this->db->where('calculated_by', 'timing'); //timing la kpi
        $this->db->order_by('log_date', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        $sql = $this->db->last_query();
        //var_dump($sql);
        //file_put_contents("/tmp/tuonglv/sql.txt",$sql . "\n\n",FILE_APPEND);
        //var_dump($result);exit();
        return $result;

    }

    private function getMonthsFromDate($fromDate,$toDate){
        $return = array();
        $unixFrom=strtotime($fromDate);
        $unixTo = strtotime($toDate);
        $i=1;
        $fromDate1=$fromDate;
        while ($unixFrom <= $unixTo || $i < 2){
            $return[] = $fromDate . " 00:00:00";
            $fromDate1 = date('Y-m-d', strtotime('+1 month '. $fromDate1));
            $fromDate = date('Y-m-d', strtotime('-1 day '. $fromDate1));
            $unixFrom = strtotime($fromDate);
            $i++;
        }
        return $return;

    }

    private function getWeeksFromDate($fromDate,$toDate){
        $return = array();
        $unixFrom=strtotime($fromDate);
        $unixTo = strtotime($toDate);
        $i=1;
        while ($unixFrom <= $unixTo || $i < 2){
            $return[] = $fromDate . " 00:00:00";
            $fromDate = date('Y-m-d', strtotime('+7 day '. $fromDate));
            $unixFrom = strtotime($fromDate);
            $i++;
        }
        return $return;
    }


}

