<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 12/04/2016
 * Time: 10:38
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Package_model extends MY_Model {

    private $table = 'package_kpi';

    public function __construct()
    {
        parent::__construct();
    }

    public function getPackageData($gameCode, $date)
    {

        $timing = array("1", "7", "30");
        $kpiName = array("a", "n", "gr", "pu", "npu", "npu_gr");
        $data = array();

        foreach ($timing as $time){
        
        	$kpi_config = array();
        	
        	foreach($kpiName as $name){
        		$kpi_config[$name . $time] = "";
        	}
	        
	        $kpi_ids_config = $this->getKpiIDs(null, $kpi_config);

            $report_source_config = $this->get_report_source($gameCode);
            $kpi_by_group_id = array();
            foreach($kpi_ids_config as $kpi_id => $kpi_code){
                $_group_id = isset($this->kpi_config[$kpi_id]['group_id']) ? $this->kpi_config[$kpi_id]['group_id'] : 1;
                $kpi_by_group_id[$report_source_config['package_kpi'][$_group_id]][] = $kpi_id;
            }

            foreach($kpi_by_group_id as $data_source => $kpi_id_arr){
                $sql = "package, kpi_value, kpi_id";
                $this->db->select($sql, false);
                $this->db->from("package_kpi");
                $this->db->where('game_code', $gameCode);
                $this->db->where_in('kpi_id', $kpi_id_arr);
                $this->db->where('source', $data_source);
                $this->db->where_in('report_date', array($date));
                $this->db->order_by('kpi_value', 'asc');
                $query = $this->db->get();
                $result = $query->result_array();

                for($i=0;$i<count($result);$i++){
                    if($result[$i]['package'] == "null"){
                        $package = "other";
                    }else{
                        $package = $result[$i]['package'];
                    }
                    $f_alias = $this->kpi_config[$result[$i]['kpi_id']]['kpi_name'];
                    $data['package'][$time][$f_alias][$package] = $result[$i]['kpi_value'];
                }
            }
        }
        return $data;
    }
/*
    public function getFullPackageData($gameCode, $from)
    {
    
    	$yesterday = date("Y-m-d", time() - 24 * 60 * 60);
    	if ($yesterday < $from){
    		$from = $yesterday;
    	}
    	
    	$timing = array("1", "7", "30");
    	$kpiName = array("a", "n", "gr", "pu", "npu", "npu_gr");
    
    	$kpi_config = array();
    	foreach ($timing as $time){
    	
    		foreach($kpiName as $name){
    			$kpi_config[$name . $time] = "";
    		}
    	}

    	$kpi_ids_config = $this->getKpiIDs(null, $kpi_config);

        $report_source_config = $this->get_report_source($gameCode);
        $kpi_by_group_id = array();
        foreach($kpi_ids_config as $kpi_id => $kpi_code){
            $_group_id = isset($this->kpi_config[$kpi_id]['group_id']) ? $this->kpi_config[$kpi_id]['group_id'] : 1;
            $kpi_by_group_id[$report_source_config['package_kpi'][$_group_id]][] = $kpi_id;
        }

    	$data = array();

        foreach($kpi_by_group_id as $data_source => $kpi_id_arr){
            $sql = "package, kpi_value, kpi_id";
            $this->db->select($sql, false);
            $this->db->from("package_kpi");
            $this->db->where('game_code', $gameCode);
            $this->db->where_in('kpi_id', $kpi_id_arr);
            $this->db->where('source', $data_source);
            $this->db->where_in('report_date', array($from));
            $this->db->order_by('kpi_value', 'asc');
            $query = $this->db->get();
            $result = $query->result_array();

            for($i=0;$i<count($result);$i++){
                if($result[$i]['package'] == "null"){
                    $package = "other";
                }else{
                    $package = $result[$i]['package'];
                }
                $f_alias = $this->kpi_config[$result[$i]['kpi_id']]['kpi_name'];
                $data['package'][$package][$f_alias] = $result[$i]['kpi_value'];
            }
        }
    	return $data;
    }
*/
    public function getDailyDataDrawChart($gameCode, $timming, $calculateDateFrom,$calculateDateTo)
    {
        $this->db->select("date_format(log_date,'%Y-%m-%d') as CalculateDate, dau_acc_a1 as ActiveAccountTotal, nru_acc_a1 as NewAccountRegister, (dau_acc_a1-nru_acc_a1) as OldAccountActive",false);
        $this->db->from($this->table);
        $this->db->where('game_code', $gameCode);
        $this->db->where('log_date >=', $calculateDateFrom);
        $this->db->where('log_date <=', $calculateDateTo);
        $this->db->where('calculated_by', 'kpi');
        $this->db->order_by('log_date', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        //$sql = $this->db->last_query();
        //file_put_contents("/tmp/tuonglv/sql.txt",$sql . "\n\n",FILE_APPEND);
        return $result;

    }

    public function getWeeklyDataDrawChart($gameCode, $timming, $calculateDateFrom,$calculateDateTo)
    {
        $this->db->select("date_format(log_date,'%Y-%m-%d') as CalculateDate, wau_acc_a7 as ActiveAccountTotal,  nru_acc_a7 as NewAccountRegister, (wau_acc_a7-nru_acc_a7) as OldAccountActive",false);
        $this->db->from($this->table);
        $this->db->where('game_code', $gameCode);
        $weekIn = $this->util->getWeeksFromDate($calculateDateFrom,$calculateDateTo);
        $this->db->where_in('log_date',$weekIn);
        $this->db->where('calculated_by', 'kpi');
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

        $this->db->select("date_format(log_date,'%Y-%m-%d') as CalculateDate, mau_acc_a30 as ActiveAccountTotal, nru_acc_a30 as NewAccountRegister, (mau_acc_a30-nru_acc_a30) as OldAccountActive",false);
        $this->db->from($this->table);
        $this->db->where('game_code', $gameCode);
        $monthIn = $this->util->getMonthsFromDate($calculateDateFrom,$calculateDateTo);
        $this->db->where_in('log_date',$monthIn);
        $this->db->where('calculated_by', 'kpi');
        $this->db->order_by('log_date', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        $sql = $this->db->last_query();
        //var_dump($sql);
        //file_put_contents("/tmp/tuonglv/sql.txt",$sql . "\n\n",FILE_APPEND);
        //var_dump($result);exit();
        return $result;

    }



}


