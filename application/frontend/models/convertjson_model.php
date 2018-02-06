<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ConvertJson_model extends MY_Model {
	
	public $tableInfo = array();
	
    public function __construct()
    {
        parent::__construct();
        /*
         * name: Dispaly Name
         * tbl_gid: Map id
         * tbl_gname: Map name
         * tbl_name: Table config
         * dta_table: Data table
         * dta_group_field: Group field name in data table
         * dta_group: kpi_type in mt_report_source
         * 
         */
        $this->tableInfo["server"] = array("name" => "Server", "tbl_gid" => "id", "tbl_gname" => "name", "tbl_name" => "group_name_conf", "dta_table" => "server_kpi", "dta_group_field" => "server_id", "dta_group" => "server_kpi");
        $this->tableInfo["channel"] = array("name" => "Channel", "tbl_gid" => "id", "tbl_gname" => "name", "tbl_name" => "group_name_conf", "dta_table" => "channel_kpi", "dta_group_field" => "channel", "dta_group" => "channel_kpi");
        $this->tableInfo["package_name"] = array("name" => "Package", "tbl_gid" => "id", "tbl_gname" => "name", "tbl_name" => "group_name_conf", "dta_table" => "package_kpi", "dta_group_field" => "package", "dta_group" => "package_kpi");
        $this->tableInfo["country_code"] = array("name" => "Country", "tbl_gid" => "id", "tbl_gname" => "name", "tbl_name" => "group_name_conf", "dta_table" => "country_kpi", "dta_group_field" => "country", "dta_group" => "country_kpi");
    }
    
    public function convertdata($groupId, $gameCode, $date){
    	
    	$groupConf = $this->tableInfo[$groupId];
    	
    	$ubstats = $this->load->database('ubstats', TRUE);
    	$sql = "source, " . $groupConf["dta_group_field"] . ", kpi_id, kpi_value";
    	
    	$this->db->select($sql, false);
    	$this->db->from($groupConf["dta_table"]);
    	$this->db->where('game_code', $gameCode);
    	$this->db->where('report_date', $date);
    	$this->db->order_by("kpi_id");
    	
    	$query = $this->db->get();
    	$result = $query->result_array();
    	
    	$data = array();
    	$count = 0;
    	for($i = 0; $i < count($result); $i++){
    		
    		$source = $result[$i]["source"];
    		$group = $result[$i][$groupConf["dta_group_field"]];
    		$kpiId = $result[$i]["kpi_id"];
    		$kpiValue = $result[$i]["kpi_value"];
    		
    		$data[$source][$kpiId][$group] = $kpiValue;
    		$count++;
    	}
    	
    	$entries = array();
    	$newCount = 0;
    	
    	foreach($data as $source => $kpi){
    		
    		foreach($kpi as $kpiId => $value){
    			
    			$json = json_encode($value, JSON_NUMERIC_CHECK);
    			$entries[] = array(
    				"report_date" => $date,
    				"game_code"	=> $gameCode,
    				"source" => $source,
    				"group_id" => $groupId,
    				"kpi_id" => $kpiId,
    				"kpi_value" => $json,
    				"calc_date"	=> date("Y-m-d H:i:s", time())
    			);
    			$newCount++;
    		}
    	}
    	$numberAffect = 0;
    	
    	if($entries != null){
	    	
    		$this->db->where('report_date', $date);
    		$this->db->where('game_code', $gameCode);
    		$this->db->where('group_id', $groupId);
    		$this->db->delete("group_kpi_json");
    		
    		$this->db->insert_batch("group_kpi_json", $entries);
	    	$numberAffect = $this->db->affected_rows();
    	}
    	
    	if($numberAffect > 0){
    		return array("total" => $count, "new" => $newCount, "success" => $numberAffect);
    	} else {

    		return array("total" => $count, "new" => $newCount, "success" => 0);
    	}
    }
    
    public function getDetailData($groupId, $gameCode, $timing, $from, $to, $flag = false)
    {
    	$groupConf = $this->tableInfo[$groupId];
    
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
    		$kpi_field_config = array_merge($db_field_config['user_kpi']['4'], $db_field_config['revenue_kpi']['4'],
    				$db_field_config['user_kpi']['5'], $db_field_config['revenue_kpi']['5'],
    				$db_field_config['user_kpi']['6'], $db_field_config['revenue_kpi']['6'],
    				$db_field_config['user_kpi']['3'], $db_field_config['revenue_kpi']['3'],
    				$db_field_config['user_kpi']['14'], $db_field_config['revenue_kpi']['14']);
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
    		$kpi_by_group_id[$report_source_config[$groupConf["dta_group"]][$_group_id]][] = $kpi_id;
    	}
    	
    	foreach($kpi_by_group_id as $data_source => $kpi_id_arr) {
    		//$sql = "date_format(report_date,'%Y-%m-%d') as log_date, LOWER(" . $groupConf["dta_group_field"] . ") as " . $groupConf["dta_group_field"] . ", kpi_value, kpi_id";
    		
    		$sql = "date_format(report_date,'%Y-%m-%d') as log_date, kpi_value, kpi_id";
    		$this->db->select($sql, false);
    		$this->db->from($groupConf["dta_table"]);
    		$this->db->where('game_code', $gameCode);
    		$this->db->where_in('kpi_id', $kpi_id_arr);
    		$this->db->where('source', $data_source);
    		$this->db->where_in('report_date', $day_arr);
    		$this->db->order_by("kpi_id");
    		
    	
    		$query = $this->db->get();
    		
    		$result = $query->result_array();
    		// change kpi_id to kpi_name
    		for ($i = 0; $i < count($result); $i++) {
    	
    			$f_alias = $this->kpi_config[$result[$i]['kpi_id']]['kpi_name'];
    			//$dbData['group'][$timing][$f_alias][$result[$i][$groupConf["dta_group_field"]]][$result[$i]['log_date']] = $result[$i]['kpi_value'];
    			
    			$jsonData = $result[$i]['kpi_value'];
    			$jsonArray = json_decode($jsonData, true);
    			
    			foreach($jsonArray as $key => $value){
    			
    				$dbData['group'][$timing][$f_alias][$key][$result[$i]['log_date']] = $value;
    			}
    		}
    		
    		//var_dump($dbData); exit();
    		
    	}
    	
    	$dbData["log_date"] = $day_arr;
    	return $dbData;
    }
}


