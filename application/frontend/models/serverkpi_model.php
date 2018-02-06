<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Serverkpi_model extends MY_Model {
	
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getServerList($gameCode){
    	
    	$server = array();
    	
    	$sql = "server_id, server_name";
    	$this->db->select($sql, false);
    	$this->db->from("server_list");
    	$this->db->where('game_code', $gameCode);
    	$this->db->order_by('open_date', 'desc');
    	
    	$query = $this->db->get();
    	$result = $query->result_array();
    	
    	for ($i = 0; $i < count($result); $i++) {
    	
    		$server[$result[$i]['server_id']] = $result[$i]['server_name'];
    	}
    	
    	return $server;
    }

	public function getServerData($gameCode, $date)
    {

        $timing = array("1", "3", "7", "14", "30", "60", "90");
        $kpiName = array("a", "n", "gr", "pu", "npu", "npu_gr", "nrr");
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
                $kpi_by_group_id[$report_source_config['server_kpi'][$_group_id]][] = $kpi_id;
            }
            foreach($kpi_by_group_id as $data_source => $kpi_id_arr) {
                $sql = "server_id, kpi_value, kpi_id";
                $this->db->select($sql, false);
                $this->db->from("server_kpi");
                $this->db->where('game_code', $gameCode);
                $this->db->where_in('kpi_id', $kpi_id_arr);
                $this->db->where('source', $data_source);
                $this->db->where_in('report_date', array($date));
                $this->db->order_by('kpi_value', 'desc');

                $query = $this->db->get();
                $result = $query->result_array();

                for ($i = 0; $i < count($result); $i++) {

                    $f_alias = $this->kpi_config[$result[$i]['kpi_id']]['kpi_name'];
                    $data['server'][$time][$f_alias][$result[$i]['server_id']] = $result[$i]['kpi_value'];
                }
            }
        }
        return $data;
    }
    
    public function getDetailData($gameCode, $timing, $from, $to)
    {
    
    	$yesterday = date("Y-m-d", time() - 24 * 60 * 60);
    	 
    	if($yesterday < $to){
    		$to = $yesterday;
    	}
    	 
    	$day_arr = $this->util->getDaysFromTiming($from, $to, intval($timing), false);
    	$ubstats = $this->load->database('ubstats', TRUE);
    	$db_field_config = $this->util->db_field_config();
    	$kpi_field_config = array_merge($db_field_config['user_kpi'][$timing], $db_field_config['revenue_kpi'][$timing]);
    	$kpi_ids_config = $this->getKpiIDs($ubstats, $kpi_field_config);
    	 
    	/**
    	 *  process for weekly and monthly
    	 *  if last selected week or month is not end, select the last day of this time in database
    	 */
    	$count = count($day_arr);
    	$to = $day_arr[$count - 1];
    	 
    	if($timming == "17" || $timming == "31"){
    		$max_log_date = $this->get_max_log_date_1($ubstats, $kpi_ids_config, $gameCode);
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
    	 
    	$dbData = array();
    	$report_source_config = $this->get_report_source($gameCode);
    	$kpi_by_group_id = array();
    	
    	foreach($kpi_ids_config as $kpi_id => $kpi_code){
    		$_group_id = isset($this->kpi_config[$kpi_id]['group_id']) ? $this->kpi_config[$kpi_id]['group_id'] : 1;
    		$kpi_by_group_id[$report_source_config['server_kpi'][$_group_id]][] = $kpi_id;
    	}

    	foreach($kpi_by_group_id as $data_source => $kpi_id_arr) {
    		$sql = "date_format(report_date,'%Y-%m-%d') as log_date, server_id, kpi_value, kpi_id";
    		$this->db->select($sql, false);
    		$this->db->from("server_kpi");
    		$this->db->where('game_code', $gameCode);
    		$this->db->where_in('kpi_id', $kpi_id_arr);
    		$this->db->where('source', $data_source);
    		$this->db->where_in('report_date', $day_arr);
    		$this->db->order_by('kpi_value', 'desc');
    	
    		$query = $this->db->get();
    		$result = $query->result_array();
			
    		// change kpi_id to kpi_name
    		for ($i = 0; $i < count($result); $i++) {
    	
    			$f_alias = $this->kpi_config[$result[$i]['kpi_id']]['kpi_name'];
    			$dbData['group'][$timing][$f_alias][$result[$i]['server_id']][$result[$i]['log_date']] = $result[$i]['kpi_value'];
    		}
    	}
    	
    	$dbData["log_date"] = $day_arr;

    	return $dbData;
    }
    
    public function getAvailableServer($gameCode, $timing, $from, $to){
    	$yesterday = date("Y-m-d", time() - 24 * 60 * 60);
    	
    	if($yesterday < $to){
    		$to = $yesterday;
    	}
    	
    	$day_arr = $this->util->getDaysFromTiming($from, $to, intval($timing), false);
    	$ubstats = $this->load->database('ubstats', TRUE);
    	
    	$db_field_config = $this->util->db_field_config();
    	$kpi_field_config = array_merge($db_field_config['user_kpi'][$timing], $db_field_config['revenue_kpi'][$timing]);
    	$kpi_ids_config = $this->getKpiIDs($ubstats, $kpi_field_config);
    	
    	$dbData = array();
    	$report_source_config = $this->get_report_source($gameCode);
    	$kpi_by_group_id = array();
    	
    	foreach($kpi_ids_config as $kpi_id => $kpi_code){
    		$_group_id = isset($this->kpi_config[$kpi_id]['group_id']) ? $this->kpi_config[$kpi_id]['group_id'] : 1;
    		$kpi_by_group_id[$report_source_config['server_kpi'][$_group_id]][] = $kpi_id;
    	}
    	
    	foreach($kpi_by_group_id as $data_source => $kpi_id_arr) {
    		$sql = "distinct(server_id) as server_id";
    		$this->db->select($sql, false);
    		$this->db->from("server_kpi");
    		$this->db->where('game_code', $gameCode);
    		$this->db->where('kpi_value !=', 0);
    		$this->db->where_in('kpi_id', $kpi_id_arr);
    		$this->db->where('source', $data_source);
    		$this->db->where_in('report_date', $day_arr);
    		 
    		$query = $this->db->get();
    		$result = $query->result_array();
    		for ($i = 0; $i < count($result); $i++) {
    			 
    			if (!in_array($result[$i]['server_id'], $dbData)){
    				$dbData[] = $result[$i]['server_id'];
    			}
    		}
    	}
    	
    	return $dbData;
    }
}


