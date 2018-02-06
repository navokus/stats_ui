<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 26/04/2016
 * Time: 09:04
 */

class MY_Model extends CI_Model {

    public $game_info = array();
    public $kpi_config = array();
    public $report_config = array();

    public function __construct()
    {
        parent::__construct();
        $this->game_info = $this->get_game_config();
        $this->kpi_config = $this->get_kpi_config();
        $this->report_config = $this->get_report_config();
    }

    public function get_report_source($game_code){
        $db = $this->load->database('ubstats', TRUE);
        $db->select("*",false);
        $db->from("mt_report_source");
        $db->where("game_code",$game_code);
        $query = $db->get();
        $result = $query->result_array();
        $data = array();

        for ($i = 0; $i < count($result); $i++) {
            $kpi_type = $result[$i]['kpi_type'];
            $group_id = $result[$i]['group_id'];
            $data_source = $result[$i]['data_source'];
            $data[$kpi_type][$group_id] = $data_source;
        }
        
        return $data;
    }

    public function get_server_list_from_server_kpi_hourly($kpi_id_arr, $game_code, $day_arr){
        $kpi_ids_config = $this->getKpiIDs(null, $kpi_id_arr);
        $db = $this->load->database('ubstats', TRUE);
        $db->select("distinct server_id", false);
        $db->from("server_kpi_hourly gk");
        $db->join("games g", 'gk.game_code = g.GameCode', 'left');
        $db->join('kpi_desc kd', 'gk.kpi_id = kd.kpi_id', 'left');
        $db->join('mt_report_source mrs', 'mrs.game_code = gk.game_code and mrs.group_id = kd.group_id and mrs.data_source = gk.source', 'left');
        $db->where("mrs.kpi_type", "server_kpi_hourly");
        $db->where("gk.game_code", $game_code);
        $db->where_in("gk.report_date", $day_arr);
        $db->where_in('gk.kpi_id', array_keys($kpi_ids_config));
        $db->order_by("server_id", "desc");
        $query = $db->get();
        $result = $query->result_array();
        $return = array();
        for($i=0;$i<count($result);$i++){
            $return[] = $result[$i]['server_id'];
        }
        return $return;
    }

    public function get_all_report_source(){
        $db = $this->load->database('ubstats', TRUE);
        $db->select("*",false);
        $db->from("mt_report_source");
        $query = $db->get();
        $result = $query->result_array();
        $data = array();

        for ($i = 0; $i < count($result); $i++) {
            $game_code = $result[$i]['game_code'];
            $kpi_type = $result[$i]['kpi_type'];
            $group_id = $result[$i]['group_id'];
            $data_source = $result[$i]['data_source'];
            $data[$game_code][$kpi_type][$group_id] = $data_source;
        }

        return $data;
    }

    private function get_report_config(){
        $db = $this->load->database('ubstats', TRUE);
        $db->select("*",false);
        $db->from("mt_report");
        $query = $db->get();
        $result = $query->result_array();
        $data = array();
        for ($i = 0; $i < count($result); $i++) {
            $data[$result[$i]['report_id']] = $result[$i];
        }
        return $data;
    }
    private function get_game_config(){
        $db = $this->load->database('ubstats', TRUE);
        $db->select("*",false);
        $db->from("games");
        $query = $db->get();
        $result = $query->result_array();
        $data = array();
        for ($i = 0; $i < count($result); $i++) {
            $data[$result[$i]['GameCode']] = $result[$i];
        }
        return $data;
    }
    public function get_marketing_kpi_data($kpi_ids_config,$game_code, $fromDate, $toDate, $table_name)
    {
        $ubstats = $this->load->database('ubstats', TRUE);
        $report_source_config = $this->get_report_source($game_code);
        $kpi_by_group_id = array();
        foreach ($kpi_ids_config as $kpi_id => $kpi_code) {
            $_group_id = isset($this->kpi_config[$kpi_id]['group_id']) ? $this->kpi_config[$kpi_id]['group_id'] : 1;
            $kpi_by_group_id[$report_source_config["marketing_kpi"][$_group_id]][] = $kpi_id;
        }
        $data = array();

        foreach($kpi_by_group_id as $data_source => $kpi_id_arr){

            $kpi_id_arr = $this->wrap_arr($kpi_id_arr);
            $select="date_format(report_date,'%Y-%m-%d') as log_date, media_source,os,campaign, kpi_value, kpi_id";

            $ubstats->select($select, false);
            $ubstats->from($table_name);
            $ubstats->where('game_code', $game_code);
            $ubstats->where_in('kpi_id', $kpi_id_arr);
            $ubstats->where('source', $data_source);
            $ubstats->where('report_date BETWEEN "'. date('Y-m-d', strtotime($fromDate)). '" and "'. date('Y-m-d', strtotime($toDate)).'"');
            $ubstats->order_by('report_date', 'asc');
            $query = $ubstats->get();
            $result = $query->result_array();


            for($j=0;$j<count($result);$j++){
                $_log_date = $result[$j]['log_date'];
                $_kpi_value = $result[$j]['kpi_value'];
                $_kpi_id = $result[$j]['kpi_id'];
                $media_source = $result[$j]['media_source'];
                $os = $result[$j]['os'];
                $campaign = $result[$j]['campaign'];
                $data[$j]['logdate'][] = $_log_date;
                $data[$j]['media_source'][] = $media_source;
                $data[$j]['campaign'][] = $campaign;
                $data[$j]['os'][] = $os;
                $data[$j]['kpi_id'] = $_kpi_id;
                $data[$j]['kpi_value'] = $_kpi_value;

            }

        }
        $data['numRow'] = count($result);
        return $data;


    }
    private function get_kpi_config()
    {
        $db = $this->load->database('ubstats', TRUE);
        $db->select("*", false);
        $db->from("kpi_desc");
        $query = $db->get();
        $result = $query->result_array();
        $data = array();

        for ($i = 0; $i < count($result); $i++) {
            $kpi_id = $result[$i]['kpi_id'];
            $group_id = $result[$i]['group_id'];
            if ($kpi_id != 0 && $group_id != "") {
                $data[$kpi_id] = $result[$i];
            }
        }

        return $data;
    }

    public function get_game_info($game_code){
        if(isset($this->game_info[$game_code]))
            return $this->game_info[$game_code];
        return null;
    }

    public function check_report_id($game_code, $report_id){
        $db = $this->load->database('ubstats', TRUE);
        $db->select("game_code",false);
        $db->from("mt_game_report");
        $db->where("game_code",$game_code);
        $db->where("report_id",$report_id);
        $db->where("active","1");
        $query = $db->get();
        $result = $query->result_array();
        if($result && !empty($result[0]['game_code']))
        {
            return 1;
        }
        return 0;
    }

    public function get_data_source_from_mt($game_code, $kpi_code, $kpi_type = "game_kpi"){
        $db = $this->load->database('ubstats', TRUE);
        $db->select("data_source");
        $db->from("kpi_desc kd");
        $db->join("mt_report_source mrs", "kd.group_id = mrs.group_id", "left");

        $db->where("mrs.game_code", $game_code);
        $db->where("mrs.kpi_type", $kpi_type);
        $db->where("kd.kpi_name", $kpi_code);

        $query = $db->get();
        $result = $query->result_array();
        if($result[0]['data_source']!=null)
            return $result[0]['data_source'];
        return "sdk";
    }

    public function get_data_source($game_code){
        $db = $this->load->database('ubstats', TRUE);
        $db->select("data_source",false);
        $db->from("games");
        $db->where("GameCode",$game_code);
        $query = $db->get();
        $result = $query->result_array();
        if($result[0]['data_source']!=null)
            return $result[0]['data_source'];
        return "sdk";
    }
    
    public function get_data_source_2($game_code, $kpi_id){
    	
    	$kpi = (int)($kpi_id / 1000);
    	
    	$defaultConfig["30"] = "ingame";
    	$defaultConfig["31"] = "ingame";
    	
    	if(isset($defaultConfig[$kpi])){
    		
    		if(isset($gameConfig[$kpi][$game_code])){
    			return $gameConfig[$kpi][$game_code];
    		}else{
    			return $defaultConfig[$kpi];
    		}
    	}
    	$db = $this->load->database('ubstats', TRUE);
    	$db->select("data_source",false);
    	$db->from("games");
    	$db->where("GameCode",$game_code);
    	$query = $db->get();
    	$result = $query->result_array();
    	if($result[0]['data_source']!=null){
    		return $result[0]['data_source'];
    	}
    	return "sdk";
    }

    public function get_max_log_date($db,$table,$log_date_f, $game_code, $timming, $type,$new){
        $config['userkpi']['4']['new'] = "a1";
        $config['userkpi']['5']['new'] = "a7";
        $config['userkpi']['6']['new'] = "a30";
        $config['revenuekpi']['4']['new'] = "gr1";
        $config['revenuekpi']['5']['new'] = "gr7";
        $config['revenuekpi']['6']['new'] = "gr30";

        $config['userkpi']['4']['old'] = "dau_acc_a1";
        $config['userkpi']['5']['old'] = "wau_acc_a7";
        $config['userkpi']['6']['old'] = "mau_acc_a30";
        $config['revenuekpi']['4']['old'] = "gross_revenue_gr1";
        $config['revenuekpi']['5']['old'] = "gross_revenue_gr7";
        $config['revenuekpi']['6']['old'] = "gross_revenue_gr30";

        $condition_f = isset($config[$type][$timming][$new]) ? $config[$type][$timming][$new] : "";
        $db->select("date_format(max($log_date_f),'%Y-%m-%d') as max_log_date",false);
        $db->from($table);
        $db->where("game_code",$game_code);
        if($condition_f){
            $db->where("$condition_f != ", 0);
        }
        $query = $db->get();
        $result = $query->result_array();
        $max_log_date = isset($result[0]['max_log_date']) ? $result[0]['max_log_date'] : "";
        //Đối với trường hợp chọn theo tuần (timing = 5), vì mình lấy ngày cuối tuần là thứ 7, nên nếu max_log_date = chủ nhật thì mình ko hiển thị
        // (vì lúc này cả thứ 7 và chủ nhật đều thuộc 1 tuần ==> nếu fix được việc ngày cuối tuần là chủ nhật chứ ko phải thứ 7 thì bỏ if dưới đây
        $dayofweek = date('w', strtotime($max_log_date));
        if($timming == "5" && $dayofweek == "0"){ // sunday = 0,
            $max_log_date = "";
        }
        return $max_log_date;
    }

    public function get_max_log_date_1($db1, $kpi_ids_config, $game_code){
        $t1 = array();
        $db = $this->load->database('ubstats', TRUE);
        foreach($kpi_ids_config as $key => $value){
            $db->select("date_format(max(report_date),'%Y-%m-%d') as max_log_date",false);
            $db->from("game_kpi");
            $db->where("game_code",$game_code);
            $db->where("kpi_id",$key);
            $db->where("kpi_value !=","0");
            $query = $db->get();
            $result = $query->result_array();
            if($result[0]['max_log_date']!=null)
                $t1[] = $result[0]['max_log_date'];
        }
        rsort($t1);
        return ($t1[0]);
    }
    
    public function get_max_retention_log_date($db1, $kpi_ids_config, $game_code){
    	$t1 = array();
    	$db = $this->load->database('ubstats', TRUE);
    	foreach($kpi_ids_config as $key => $value){
    		$db->select("date_format(max(report_date),'%Y-%m-%d') as max_log_date",false);
    		$db->from("game_retention");
    		$db->where("game_code",$game_code);
    		$db->where("kpi_id",$key);
    		$db->where("kpi_value !=","0");
    		$query = $db->get();
    		$result = $query->result_array();
    		if($result[0]['max_log_date']!=null)
    			$t1[] = $result[0]['max_log_date'];
    	}
    	rsort($t1);
    	return ($t1[0]);
    }

    public function getKpiIDs($db1, $kpi_config){
        $f_a = array();
        $db = $this->load->database('ubstats', TRUE);
        if(isset($kpi_config[0])){
            for($i=0;$i<count($kpi_config);$i++){
                $f_a[] = $kpi_config[$i];
            }
        }else{
            foreach($kpi_config as $key => $value){
                $f_a[] = $key;
            }
        }
        if(count($f_a) == 0 )
            return null;

        $db->select("kpi_name, kpi_id", true);
        $db->from("kpi_desc");
        $db->where_in("kpi_name", $f_a);
        $query = $db->get();
        $result = $query->result_array();
        
        $return = array();
        for($i=0;$i<count($result);$i++){
            $return[$result[$i]['kpi_id']] = $result[$i]['kpi_name'];
        }
        return $return;
    }
    public function combine_alias($kpi_ids_config,$kpi_field_config){
        for($i=0;$i<count($kpi_ids_config);$i++){
            $f = $kpi_ids_config[$i]['kpi_name'];
            $alias="unk";
            foreach($kpi_field_config as $key => $value){
                if($key == $f){
                    $alias = $value;
                    break;
                }
            }
            $kpi_ids_config[$i]['kpi_alias'] = $alias;
        }
        return $kpi_ids_config;
    }

    public function get_combine_where_string($field, $arr, $combine){
        $string = "";
        for($i=0;$i<count($arr);$i++){
            $string .= $field . " = '" . $arr[$i] . "' " . $combine . " ";
        }
        $string = substr($string, 0 , -(strlen($combine)+1));
        return $string;
    }
    public function get_kpi_data_by_source($kpi_ids_config, $kpi_type, $game_code, $day_arr, $table_name,$source){
        $ubstats = $this->load->database('ubstats', TRUE);
        $kpi_by_group_id = array();
        foreach($kpi_ids_config as $kpi_id => $kpi_code){
            $_group_id = isset($this->kpi_config[$kpi_id]['group_id']) ? $this->kpi_config[$kpi_id]['group_id'] : 1;
            $kpi_by_group_id[$source][] = $kpi_id;
        }
        $data = array();
        foreach($kpi_by_group_id as $data_source => $kpi_id_arr){

            $kpi_id_arr = $this->wrap_arr($kpi_id_arr);
            $select="date_format(report_date,'%Y-%m-%d') as log_date, kpi_value, kpi_id";
            $ubstats->select($select, false);
            $ubstats->from($table_name);
            $ubstats->where('game_code', $game_code);
            $ubstats->where_in('kpi_id', $kpi_id_arr);
            $ubstats->where('source', $data_source);

            $ubstats->where_in('report_date', $day_arr);
            $ubstats->order_by('report_date', 'asc');
            $query = $ubstats->get();
            $result = $query->result_array();
            if(isset($_GET['get-last-query'])){
                $last = $ubstats->last_query();echo $last;exit();
                file_put_contents("/tmp/tuonglv/sql.txt",$last . "\n\n",FILE_APPEND);
            }
            for($j=0;$j<count($result);$j++){
                $_log_date = $result[$j]['log_date'];
                $_kpi_value = $result[$j]['kpi_value'];
                $_kpi_id = $result[$j]['kpi_id'];
                $data[$_log_date][$this->kpi_config[$_kpi_id]['kpi_name']] = $_kpi_value;
            }

        }

        return $data;
    }

    public function get_kpi_data($kpi_ids_config, $kpi_type, $game_code, $day_arr, $table_name){
        $ubstats = $this->load->database('ubstats', TRUE);
        $report_source_config = $this->get_report_source($game_code);
        $kpi_by_group_id = array();
        foreach($kpi_ids_config as $kpi_id => $kpi_code){
            $_group_id = isset($this->kpi_config[$kpi_id]['group_id']) ? $this->kpi_config[$kpi_id]['group_id'] : 1;
            $kpi_by_group_id[$report_source_config[$kpi_type][$_group_id]][] = $kpi_id;
        }

        $data = array();
        foreach($kpi_by_group_id as $data_source => $kpi_id_arr){

            $kpi_id_arr = $this->wrap_arr($kpi_id_arr);
            $select="date_format(report_date,'%Y-%m-%d') as log_date, kpi_value, kpi_id";
            $ubstats->select($select, false);
            $ubstats->from($table_name);
            $ubstats->where('game_code', $game_code);
            $ubstats->where_in('kpi_id', $kpi_id_arr);
            $ubstats->where('source', $data_source);

            $ubstats->where_in('report_date', $day_arr);
            $ubstats->order_by('report_date', 'asc');
            $query = $ubstats->get();
            $result = $query->result_array();
            if(isset($_GET['get-last-query'])){
                $last = $ubstats->last_query();echo $last;exit();
                file_put_contents("/tmp/tuonglv/sql.txt",$last . "\n\n",FILE_APPEND);
            }
            for($j=0;$j<count($result);$j++){
                $_log_date = $result[$j]['log_date'];
                $_kpi_value = $result[$j]['kpi_value'];
                $_kpi_id = $result[$j]['kpi_id'];
                $data[$_log_date][$this->kpi_config[$_kpi_id]['kpi_name']] = $_kpi_value;
            }

        }

        return $data;
    }


    public function get_kpi_data_channel($kpi_ids_config, $kpi_type, $game_code, $day_arr, $table_name){
        $ubstats = $this->load->database('ubstats', TRUE);
        $report_source_config = $this->get_report_source($game_code);
        $kpi_by_group_id = array();
        foreach($kpi_ids_config as $kpi_id => $kpi_code){
            $_group_id = isset($this->kpi_config[$kpi_id]['group_id']) ? $this->kpi_config[$kpi_id]['group_id'] : 1;
            $kpi_by_group_id[$report_source_config[$kpi_type][$_group_id]][] = $kpi_id;
        }

        $data = array();
        foreach($kpi_by_group_id as $data_source => $kpi_id_arr){

            $kpi_id_arr = $this->wrap_arr($kpi_id_arr);
            $select="date_format(report_date,'%Y-%m-%d') as log_date, kpi_value, kpi_id, channel";
            $ubstats->select($select, false);
            $ubstats->from($table_name);
            $ubstats->where('game_code', $game_code);
            $ubstats->where_in('kpi_id', $kpi_id_arr);
            $ubstats->where('source', $data_source);

            $ubstats->where_in('report_date', $day_arr);
            $ubstats->order_by('report_date', 'asc');
            $query = $ubstats->get();
            $result = $query->result_array();
            if(isset($_GET['get-last-query'])){
                $last = $ubstats->last_query();echo $last;exit();
                file_put_contents("/tmp/tuonglv/sql.txt",$last . "\n\n",FILE_APPEND);
            }
            for($j=0;$j<count($result);$j++){
                $_log_date = $result[$j]['log_date'];
                $_kpi_value = $result[$j]['kpi_value'];
                $_kpi_id = $result[$j]['kpi_id'];
                $_channel = $result[$j]['channel'];
                if(is_null($_channel) || $_channel=="null" || $_channel==""){
                    $_channel="other";
                }
                $data[$_log_date][$_channel][$this->kpi_config[$_kpi_id]['kpi_name']] = $_kpi_value;
                $data[$_log_date]['channel'][] = $_channel;

            }


        }

        return $data;
    }


    public function get_kpi_data_package($kpi_ids_config, $kpi_type, $game_code, $day_arr, $table_name){
        $ubstats = $this->load->database('ubstats', TRUE);
        $report_source_config = $this->get_report_source($game_code);
        $kpi_by_group_id = array();
        foreach($kpi_ids_config as $kpi_id => $kpi_code){
            $_group_id = isset($this->kpi_config[$kpi_id]['group_id']) ? $this->kpi_config[$kpi_id]['group_id'] : 1;
            $kpi_by_group_id[$report_source_config[$kpi_type][$_group_id]][] = $kpi_id;
        }

        $data = array();
        foreach($kpi_by_group_id as $data_source => $kpi_id_arr){

            $kpi_id_arr = $this->wrap_arr($kpi_id_arr);
            $select="date_format(report_date,'%Y-%m-%d') as log_date, kpi_value, kpi_id, package";
            $ubstats->select($select, false);
            $ubstats->from($table_name);
            $ubstats->where('game_code', $game_code);
            $ubstats->where_in('kpi_id', $kpi_id_arr);
            $ubstats->where('source', $data_source);

            $ubstats->where_in('report_date', $day_arr);
            $ubstats->order_by('report_date', 'asc');
            $query = $ubstats->get();
            $result = $query->result_array();
            if(isset($_GET['get-last-query'])){
                $last = $ubstats->last_query();echo $last;exit();
                file_put_contents("/tmp/tuonglv/sql.txt",$last . "\n\n",FILE_APPEND);
            }
            for($j=0;$j<count($result);$j++){
                $_log_date = $result[$j]['log_date'];
                $_kpi_value = $result[$j]['kpi_value'];
                $_kpi_id = $result[$j]['kpi_id'];
                $_package = $result[$j]['package'];
                if(is_null($_package) || $_package=="null" || $_package==""){
                    $_package="other";
                }
                $data[$_log_date][$_package][$this->kpi_config[$_kpi_id]['kpi_name']] = $_kpi_value;
                $data[$_log_date]['package'][] = $_package;

            }


        }

        return $data;
    }


    public function getMetrics($kpi_ids, $kpi_type, $game_code, $day_arr, $table_name){
        $ubstats = $this->load->database('ubstats', TRUE);
        $report_source_config = $this->get_report_source($game_code);

        $kpi_by_group_id = array();
        foreach($kpi_ids as $kpi_id){
            $_group_id = isset($this->kpi_config[$kpi_id]['group_id']) ? $this->kpi_config[$kpi_id]['group_id'] : 1;
            $kpi_by_group_id[$report_source_config[$kpi_type][$_group_id]][] = $kpi_id;
        }

        $data = array();
        foreach($kpi_by_group_id as $data_source => $kpi_id_arr){

            $kpi_id_arr = $this->wrap_arr($kpi_id_arr);
            $select="date_format(report_date,'%Y-%m-%d') as log_date, kpi_value, kpi_id";
            $ubstats->select($select, false);
            $ubstats->from($table_name);
            $ubstats->where('game_code', $game_code);
            $ubstats->where_in('kpi_id', $kpi_id_arr);
            $ubstats->where('source', $data_source);

            $ubstats->where_in('report_date', $day_arr);
            $ubstats->order_by('report_date', 'asc');
            $query = $ubstats->get();
            $result = $query->result_array();
            //var_dump($result);
            for($j=0;$j<count($result);$j++){
                $_log_date = $result[$j]['log_date'];
                $_kpi_value = $result[$j]['kpi_value'];
                $_kpi_id = $result[$j]['kpi_id'];
                $data[$_log_date][$_kpi_id] = $_kpi_value;
                $data[$_log_date][$_kpi_id] = $_kpi_value;

            }

        }

        return $data;
    }

    private function wrap_arr($arr){
        $return = array();
        for($i=0;$i<count($arr);$i++){
            $return[] = "". $arr[$i];
        }
        return $return;
    }


    public function sync_to_main_src($kpi_ids, $kpi_type, $game_code, $day_arr, $table_name){

        $ubstats = $this->load->database('ubstats', TRUE);

        //$this->clean($day_arr,$game_code);
        $report_source_config = $this->get_report_source($game_code);

        $kpi_by_group_id = array();
        foreach($kpi_ids as $kpi_id){
            $_group_id = isset($this->kpi_config[$kpi_id]['group_id']) ? $this->kpi_config[$kpi_id]['group_id'] : 1;
            $kpi_by_group_id[$report_source_config[$kpi_type][$_group_id]][] = $kpi_id;
        }

        $data = array();
        foreach($kpi_by_group_id as $data_source => $kpi_id_arr){

            $kpi_id_arr = $this->wrap_arr($kpi_id_arr);
            $select="report_date,game_code,source, kpi_id,kpi_value,report_date";
            $ubstats->distinct();
            $ubstats->select($select, false);
            $ubstats->from($table_name);
            $ubstats->where('game_code', $game_code);
            $ubstats->where_in('kpi_id', $kpi_id_arr);
            $ubstats->where('source', $data_source);

            $ubstats->where_in('report_date', $day_arr);
            $ubstats->order_by('report_date', 'asc');
            $query = $ubstats->get();
            $result = $query->result_array();
            for($j=0;$j<count($result);$j++){
                $data[]=$result[$j];
            }

        }


        return $data;
    }
    public function clean($day_arr,$game_code)
    {
        $sharedb = $this->load->database('share_fa_db', TRUE);
        $sharedb -> where_in('report_date', $day_arr);
        $sharedb ->where('game_code', $game_code);
        $sharedb -> delete('share_game_kpi');
    }
    public function clean_days($day_arr)
    {
        $sharedb = $this->load->database('share_fa_db', TRUE);
        $sharedb -> where_in('report_date', $day_arr);
        $sharedb -> delete('share_game_kpi');
    }
    public function sync_to_share($result)
    {

        $sharedb = $this->load->database('share_fa_db', TRUE);

        $sharedb->insert_batch("share_game_kpi",$result);
        $sharedb->flush_cache();
    }



}

