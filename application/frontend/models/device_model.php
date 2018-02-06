<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 12/04/2016
 * Time: 10:38
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Device_model extends MY_Model {

    public function __construct()
    {
        parent::__construct();

    }

    public function get_os_dbdata($gameCode,$_kpi_config, $from)
    {
        $kpi_ids_config = $this->getKpiIDs(null, $_kpi_config);
        $data = $this->get_kpi_data($kpi_ids_config, "os_kpi", $gameCode, array($from), "os_kpi");
        
        $return['os'] = $data[$from];
        return $return;
    }
    public function get_totalOs($gameCode,$_kpi_config, $dates)
    {
    	
    	$kpi_ids_config = $this->getKpiIDs(null, $_kpi_config);
    	
    	$data = $this->get_kpi_data($kpi_ids_config, "os_kpi", $gameCode, $dates, "os_kpi");
    			
    	$len =count($data);
    	$return= array();
    	$ios_data =array();
    	$android_data =array();
    	$other_data =array();
    	
    	foreach ($data as $key => $value) {
    		$return['dates'][] = $key;
    		foreach ($kpi_ids_config as $kpi_id => $kpi_name) {
    			
    			$os_data = json_decode($value[$kpi_name]);
    			$ios=0;
    			$and=0;
    			$other=0;
    			
    			foreach ($os_data as $os_name => $os_val) {
    				$val = floatval($os_val);
    				if (strpos($os_name, 'ios') !== false) {
    					$ios+=$val;
    				}else if (strpos($os_name, 'android') !== false) {
    					$and+=$val;
    				}else{
    					$other+=$val;
    				}
    			}
    			
    			$return[$kpi_name]["ios"][] = $ios;
    			$return[$kpi_name]["android"][] = $and;
    			$return[$kpi_name]["other"][] = $other;
    			
    		}
    		
    		//$return[] = $t;
    		//
    	}
    	
    	return $return;
    }
/*
    public function get_brand_dbdata($gameCode,$_kpi_config, $from)
    {

        $kpi_ids_config = $this->getKpiIDs(null, $_kpi_config);
        $data = array();
        foreach ($kpi_ids_config as $key => $value) {
            $f_id = $key;
            $f_alias = $value;
            $sql = "kpi_value as $f_alias";
            $this->db->select($sql, false);
            $this->db->from("device_kpi");
            $this->db->where('game_code', $gameCode);
            $this->db->where('kpi_id', $f_id);
            $this->db->where('source', $this->get_data_source($gameCode));
            $this->db->where_in('report_date', $from);
            $this->db->order_by('report_date', 'asc');
            $this->db->group_by('report_date');
            $query = $this->db->get();
            $result = $query->result_array();
            if (count($result) != 0) {
                $data['brand'][$f_alias] = $result[0][$f_alias];
            }
        }
        return $data;
    }
*/
    
    
}


