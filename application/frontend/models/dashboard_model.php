<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 07/06/2016
 * Time: 11:14
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public function trend_chart_1($game_code, $start_date, $log_date, $fields, $timing)
    {

        $ubstats = $this->load->database('ubstats', TRUE);
        $kpi_field_config = $this->get_db_field_config($fields);
        $kpi_ids_config = $this->getKpiIDs($ubstats, $kpi_field_config);

        $max_log_date = $this->get_max_log_date_1($ubstats, $kpi_ids_config, $game_code);
        if ($max_log_date < $log_date)
            $log_date = $max_log_date;
        $end_date = $log_date;

        $day_arr = $this->util->getDaysFromTiming($start_date, $end_date, $timing, false);

        if($timing == "17" || $timing == "31"){
            sort($day_arr);
            $max_date = $day_arr[count($day_arr)-1];
            if($max_date > $end_date){
                unset($day_arr[count($day_arr)-1]);
                $day_arr = array_values($day_arr);
            }
            if(!in_array($end_date, $day_arr)){
                $day_arr[] = $end_date;
            }
        }

        if(count($day_arr) == 0){
            return null;
        }

        $data = $this->get_kpi_data($kpi_ids_config, "game_kpi", $game_code, $day_arr, "game_kpi");

        ksort($data);
        $return_data = array();
        foreach ($data as $key => $value) {
            $t = array();
            $t['log_date'] = $key;
            foreach ($value as $k => $v) {
                $t[$k] = $v;
            }
            $return_data[] = $t;

        }
        return $return_data;
    }

    public function overview_1($game_code,$start_date, $log_date, $fields){
        $ubstats = $this->load->database('ubstats', TRUE);
        $kpi_field_config = $this->get_db_field_config($fields);
        $kpi_ids_config = $this->getKpiIDs($ubstats, $kpi_field_config);

        $max_log_date = $log_date;
        $day_arr = array($start_date,$max_log_date);

        $data = $this->get_kpi_data($kpi_ids_config, "game_kpi", $game_code, $day_arr, "game_kpi");

        krsort($data);
        $return_data = array();
        foreach($data as $key => $value){
            $t = array();
            foreach($value as $k => $v){
                $t[$k] = $v;
            }
            $t['log_date'] = $key;
            $return_data[$key] = $t;
        }
        $return_data['log_date'] = $max_log_date;
        return $return_data;
    }

    private function get_db_field_config($fields){
        $db_field_config = $this->util->db_field_config();
        $return = array();
        for($i=0;$i<count($fields);$i++){
            foreach($db_field_config['user_kpi'] as $key => $value){
                if(isset($value[$fields[$i]])){
                    $return[$fields[$i]] = $value[$fields[$i]];
                }
            }
            foreach($db_field_config['revenue_kpi'] as $key => $value){
                if(isset($value[$fields[$i]])){
                    $return[$fields[$i]] = $value[$fields[$i]];
                }
            }
        }
        return $return;
    }

    public function set_user_config($data, $type, $current_user, $current_game){
        $ubstats = $this->load->database('ubstats', TRUE);
        $now = date("Y-m-d H:i:s");
        if($data==null) return;
        //$ubstats->delete("user_config", array('user_name' => $current_user, "config_code" => $type, 'game_code'=>$current_game));
        $insert_data = array(
          "user_name" => $current_user,
            "config_code" => $type,
            "config_data" => $data,
            "game_code" => $current_game,
            "create_date" => $now
        );
        $ubstats->insert('user_config', $insert_data);
    }
    public function get_user_config($curent_user, $current_game, $type){
        $ubstats = $this->load->database('ubstats', TRUE);
        $ubstats->select("config_data", false);
        $ubstats->from("user_config");
        $ubstats->where('user_name', $curent_user);
        $ubstats->where('config_code', $type);
        $ubstats->where('game_code', $current_game);
        $ubstats->order_by('create_date', 'desc');
        $query = $ubstats->get();
        $result = $query->result_array();
        if(isset($result[0]['config_data']))
            return $result[0]['config_data'];
        return null;
    }
    
    public function getDates($start_date, $end_date, $timing){
    	$day_arr = $this->util->getDaysFromTiming($start_date, $end_date, $timing, false);
    	 
    	$now = date("Y-m-d", time());
    	if($timing == "17" || $timing == "31"){
    		$yesterday = date("Y-m-d", strtotime($now) - 24*60*60);
    		sort($day_arr);
    		$max_date = $day_arr[count($day_arr)-1];
    		if($max_date > $end_date){
    			//unset($day_arr[count($day_arr)-1]);
    			//$day_arr = array_values($day_arr);
    			if($end_date >= $now){
    				$day_arr[count($day_arr)-1] = $yesterday;
    			}else{
    				$day_arr[count($day_arr)-1] = $end_date;
    			}
    		}
    		 
    	
    	}
    	return $day_arr;
    }
    public function getData2($game_code, $start_date, $end_date, $fields, $timing)
    {
    
    	$ubstats = $this->load->database('ubstats', TRUE);
    	$kpi_field_config = $this->get_db_field_config($fields);
    	$kpi_ids_config = $this->getKpiIDs($ubstats, $kpi_field_config);
    
    
    	$day_arr = $this->getDates($start_date, $end_date, $timing);
    	
    	$now = date("Y-m-d", time());
    
    	if(count($day_arr) == 0){
    		return null;
    	}
    
    	$data = $this->get_kpi_data($kpi_ids_config, "game_kpi", $game_code, $day_arr, "game_kpi");
    	$sample =reset($data);
    	foreach ($kpi_ids_config as $kid => $kname) {
    		$sample[$kname]="0";
    	}
    	
    	if($end_date==$now){
    		$nows =array($now);
    		$realTimeData =$this->get_kpi_data($kpi_ids_config, "realtime_game_kpi", $game_code, $nows, "realtime_game_kpi");
    		if($realTimeData[$now]!=null){
    			$data[$now] = $realTimeData[$now];
    		}
    	}
    	foreach ($day_arr as $date) {
    		if($data[$date]==null){
    			$data[$date]=$sample;
    		}
    	}
    	ksort($data);
    	$return_data = array();
    	foreach ($data as $key => $value) {
    		$t = array();
    		$t['log_date'] = $key;
    		foreach ($value as $k => $v) {
    			$t[$k] = $v;
    		}
    		$return_data[] = $t;
    
    	}
    	return $return_data;
    }
}