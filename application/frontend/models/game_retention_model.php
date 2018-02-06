<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game_retention_model extends MY_Model {
	
	public $tableInfo = array();
	
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getDetailData($gameCode, $timing, $from, $to, $flag = false)
    {
    	$yesterday = date("Y-m-d", time() - 24 * 60 * 60);
    	 
    	if($yesterday < $to){
    		$to = $yesterday;
    	}
    	 
    	$day_arr = $this->util->getDaysFromTiming($from, $to, intval($timing), false);
    	
    	if($flag == true){
    		$day_arr = $this->util->getDaysFromRanges($day_arr);
    	}
    	
    	$ubstats = $this->load->database('ubstats', TRUE);
    	$db_field_config = $this->util->db_field_config();
    	$kpi_field_config = array();
    	if($timing == '4'){
    		$kpi_field_config = array_merge($db_field_config['user_kpi']['4'], $db_field_config['revenue_kpi']['4']);
    	}else{
    		$kpi_field_config = array_merge($db_field_config['user_kpi'][$timing], $db_field_config['revenue_kpi'][$timing]);
    	}
    	$kpi_ids_config = $this->getKpiIDs($ubstats, $kpi_field_config);
    	/**
    	 *  process for weekly and monthly
    	 *  if last selected week or month is not end, select the last day of this time in database
    	 */
    	$count = count($day_arr);
    	$to = $day_arr[$count - 1];
    	$lastDbReportDate = "";		// latest date having report in database
    	$lastReportDate = "";		// report date need to be shown.
    	 
    	if($timing == "17" || $timing == "31"){
    		$max_log_date = $this->get_max_retention_log_date($ubstats, $kpi_ids_config, $gameCode);
    		    		
    		if ($max_log_date != "" && !in_array($max_log_date, $day_arr)) {
    			sort($day_arr);
    			for($i=0;$i<count($day_arr);$i++){
    				if(strtotime($day_arr[$i]) >= strtotime($max_log_date)){
    					
    					$lastReportDate = $day_arr[$i]; 
    					unset($day_arr[$i]);
    				}
    			}
    			// max log_data > last selected day
    			if(strtotime($max_log_date)	<= strtotime($to)){
    				
    				$lastDbReportDate = $max_log_date;
    				$day_arr[] = $max_log_date;
    			}
    			$day_arr = array_values($day_arr);
    		}
    	}
    	
    	$dbData = array();
    	$report_source_config = $this->get_report_source($gameCode);
    	$kpi_by_group_id = array();
    	
    	foreach($kpi_ids_config as $kpi_id => $kpi_code){
    		$_group_id = isset($this->kpi_config[$kpi_id]['group_id']) ? $this->kpi_config[$kpi_id]['group_id'] : 1;
    		$kpi_by_group_id[$report_source_config['game_retention'][$_group_id]][] = $kpi_id;
    	}
    	
    	$dbMinDate = "9999-99-99";
    	
    	foreach($kpi_by_group_id as $data_source => $kpi_id_arr) {
    		$sql = "date_format(report_date,'%Y-%m-%d') as log_date, kpi_value, kpi_id";
    		$this->db->select($sql, false);
    		$this->db->from("game_retention");
    		$this->db->where('game_code', $gameCode);
    		$this->db->where_in('kpi_id', $kpi_id_arr);
    		$this->db->where('source', $data_source);
    		$this->db->where_in('report_date', $day_arr);
    		$this->db->order_by("kpi_id");
    		
    		$query = $this->db->get();
    		$result = $query->result_array();
			$numOfResult = count($result);
			
    		// change kpi_id to kpi_name
    		for ($i = 0; $i < $numOfResult; $i++) {
    	
    			//$f_alias = $this->kpi_config[$result[$i]['kpi_id']]['kpi_name'];
    			$jsonData = $result[$i]['kpi_value'];
    			$logDate = $result[$i]['log_date'];
    			if($dbMinDate >= $logDate){
    				$dbMinDate = $logDate;
    			}
    			
    			$jsonArray = json_decode($jsonData, true);
    			
    			foreach($jsonArray as $firstDate => $retention){
    			
    				if($retention > 0 && $from <= $firstDate && $$firstDate <= $to){
    				
    					if($lastDbReportDate == $logDate && $firstDate == $lastReportDate){
    						$dbData[$timing][$lastDbReportDate][$lastDbReportDate] = $retention;
    					}
    					else{ 
    						$dbData[$timing][$firstDate][$logDate] = $retention;
    					}
    				}
    			}
    		}
    	}
    	$dbData["log_date"] = $day_arr;
    	$dbData["min_date"] = $dbMinDate;
    	return $dbData;
    }
}


