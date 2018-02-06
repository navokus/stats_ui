<?php

/**
 * Created by IntelliJ IDEA.
 * User: lamnt6
 * Date: 09/10/2017
 * Time: 15:06
 */
class sdk_export_model extends MY_Model
{
    private function wrap_arr($arr){
        $return = array();
        for($i=0;$i<count($arr);$i++){
            $return[] = "". $arr[$i];
        }
        return $return;
    }
    public function get_kpi_sdk_data($kpi_ids_config, $kpi_type, $game_code, $day_arr, $table_name){
        $ubstats = $this->load->database('ubstats', TRUE);

        $lstKpi=array_keys($kpi_ids_config);
        foreach ($lstKpi as $kpi) {
            if (strpos($kpi, '/') == true) {
                $calcKpi = explode('/', $kpi);
                foreach ($calcKpi as $calc) {
                    $lstKpi[] = $calc;
                }
            }
        }


        $data = array();
        $select="date_format(report_date,'%Y-%m-%d') as log_date, kpi_value, kpi_id";
        $ubstats->select($select, false);
        $ubstats->from($table_name);
        $ubstats->where('game_code', $game_code);
        $ubstats->where_in('kpi_id', $lstKpi);
        $ubstats->where('source', "sdk");
        $ubstats->where_in('report_date', $day_arr);
        $ubstats->order_by('report_date', 'asc');
        $query = $ubstats->get();
        $result = $query->result_array();

        for($j=0;$j<count($result);$j++){
            $_log_date = $result[$j]['log_date'];
            $_kpi_value = $result[$j]['kpi_value'];
            $_kpi_id = $result[$j]['kpi_id'];
            $data[$_log_date][$_kpi_id] = $_kpi_value;
        }
        $data2 = $this->calcKpi($data, $day_arr);

        return $data2;
    }


    private function calcKpi($data, $lstDate)
    {
        foreach ($lstDate as $date) {

            //find special kpi
            $lstKpi = array_keys($data[$date]);
            foreach ($lstKpi as $kpi) {
                if (strpos($kpi, '/') == true) {
                    $calcKpi = explode('/', $kpi);
                    $data[$date][$kpi] = $data[$date][$calcKpi[0]] / $data[$date][$calcKpi[1]];
                }
            }
        }
        return $data;

    }

    public function get_kpi_sdk_data_channel($kpi_ids_config, $kpi_type, $game_code, $day_arr, $table_name){
        $ubstats = $this->load->database('ubstats', TRUE);
        $lstKpi=array_keys($kpi_ids_config);
        foreach ($lstKpi as $kpi) {
            if (strpos($kpi, '/') == true) {
                $calcKpi = explode('/', $kpi);
                foreach ($calcKpi as $calc) {
                    $lstKpi[] = $calc;
                }
            }
        }


        $data = array();
        $select="date_format(report_date,'%Y-%m-%d') as log_date, kpi_value, kpi_id, channel";
        $ubstats->select($select, false);
        $ubstats->from($table_name);
        $ubstats->where('game_code', $game_code);
        $ubstats->where_in('kpi_id', $lstKpi);
        $ubstats->where('source', "sdk");

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
            $data[$_log_date][$_channel][$_kpi_id] = $_kpi_value;
            $data[$_log_date]['channel'][] = $_channel;

        }
        $data2 = $this->calcKpiChannel($data, $day_arr);

        return $data2;
    }

    private function calcKpiChannel($data, $lstDate)
    {
        foreach ($lstDate as $date) {

            //find special kpi
            $lstKpi = array_keys($data[$date]);
            foreach ($lstKpi as $kpi) {
                if (strpos($kpi, '/') == true) {
                    $calcKpi = explode('/', $kpi);
                    foreach ( $data[$date]['channel']as $channel) {
                        $data[$date][$channel][$kpi] = $data[$date][$channel][$calcKpi[0]] / $data[$date][$channel][$calcKpi[1]];
                    }
                }
            }
        }
        return $data;

    }


    public function get_kpi_sdk_data_package($kpi_ids_config, $kpi_type, $game_code, $day_arr, $table_name){
        $ubstats = $this->load->database('ubstats', TRUE);
        $lstKpi=array_keys($kpi_ids_config);
        foreach ($lstKpi as $kpi) {
            if (strpos($kpi, '/') == true) {
                $calcKpi = explode('/', $kpi);
                foreach ($calcKpi as $calc) {
                    $lstKpi[] = $calc;
                }
            }
        }

        $data = array();

        $select="date_format(report_date,'%Y-%m-%d') as log_date, kpi_value, kpi_id, package";
        $ubstats->select($select, false);
        $ubstats->from($table_name);
        $ubstats->where('game_code', $game_code);
        $ubstats->where_in('kpi_id', $lstKpi);
        $ubstats->where('source', "sdk");
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
            $data[$_log_date][$_package][$_kpi_id] = $_kpi_value;
            $data[$_log_date]['package'][] = $_package;
        }
        $data2 = $this->calcKpiPackage($data, $day_arr);

        return $data2;
    }

    private function calcKpiPackage($data, $lstDate)
    {
        foreach ($lstDate as $date) {

            //find special kpi
            $lstKpi = array_keys($data[$date]);
            foreach ($lstKpi as $kpi) {
                if (strpos($kpi, '/') == true) {
                    $calcKpi = explode('/', $kpi);
                    foreach ( $data[$date]['package']as $package) {
                        $data[$date][$package][$kpi] = $data[$date][$package][$calcKpi[0]] / $data[$date][$package][$calcKpi[1]];
                    }
                }
            }
        }
        return $data;

    }


    public function get_channel_export_datatable($fromDate, $toDate, $game_code, $table_name, $kpi_ids_config, $kpi_type)
    {

        $yesterday = date("Y-m-d", time() - 24 * 60 * 60);
        if ($yesterday < $toDate)
            $toDate = $yesterday;

        $timming_map = $this->util->get_timming_config();
        foreach ($timming_map as $k => $v) {
            $kpi_ids[] = 'rlc' . $v;
            $kpi_ids[] = 'nrc' . $v;
        }
        $day_arr = $this->util->getDaysFromTiming($fromDate, $toDate, 4, false);
        $data = $this->get_kpi_sdk_data_channel($kpi_ids_config, $kpi_type, $game_code, $day_arr, $table_name);

        $return_data = array();
        //for($i=0;$i<count($day_arr);$i++){
        for ($i = 0; $i < count($day_arr); $i++) {
            $t = array();
            foreach ($kpi_ids_config as $key => $value) {
                $channel_list = $data[$day_arr[$i]]['channel'];
                foreach ($channel_list as $channel) {
                    $f_alias = $value;
                    $t[$channel][$f_alias] = (isset($data[$day_arr[$i]][$channel][$key])) ? $data[$day_arr[$i]][$channel][$key] : 0;
                }


            }
            $return_data[$i] = $t;
            $return_data[$i]['log_date'] = $day_arr[$i];
            foreach (array_unique($data[$day_arr[$i]]['channel']) as $channel){
                $return_data[$i]['channel'][]=$channel;
            }
        }

        $return_data = array_values($return_data);
        return $return_data;
    }
    public function get_package_export_datatable($fromDate, $toDate, $game_code, $table_name, $kpi_ids_config, $kpi_type)
    {

        $yesterday = date("Y-m-d", time() - 24 * 60 * 60);
        if ($yesterday < $toDate)
            $toDate = $yesterday;

        $timming_map = $this->util->get_timming_config();
        foreach ($timming_map as $k => $v) {
            $kpi_ids[] = 'rlc' . $v;
            $kpi_ids[] = 'nrc' . $v;
        }
        $day_arr = $this->util->getDaysFromTiming($fromDate, $toDate, 4, false);
        $data = $this->get_kpi_sdk_data_package($kpi_ids_config, $kpi_type, $game_code, $day_arr, $table_name);

        $return_data = array();
        //for($i=0;$i<count($day_arr);$i++){
        for ($i = 0; $i < count($day_arr); $i++) {
            $t = array();
            foreach ($kpi_ids_config as $key => $value) {
                $package_list = $data[$day_arr[$i]]['package'];
                foreach ($package_list as $package) {
                    $f_alias = $value;
                    $t[$package][$f_alias] = (isset($data[$day_arr[$i]][$package][$key])) ? $data[$day_arr[$i]][$package][$key] : 0;
                }


            }
            $return_data[$i] = $t;
            $return_data[$i]['log_date'] = $day_arr[$i];
            foreach (array_unique($data[$day_arr[$i]]['package']) as $package){
                $return_data[$i]['package'][]=$package;
            }
        }

        $return_data = array_values($return_data);
        return $return_data;
    }

    public function get_export_datatable($fromDate, $toDate, $game_code, $table_name, $kpi_ids_config, $kpi_type)
    {
        $yesterday = date("Y-m-d", time() - 24 * 60 * 60);
        if ($yesterday < $toDate)
            $toDate = $yesterday;

        $day_arr = $this->util->getDaysFromTiming($fromDate, $toDate, 4, false);
        $data = $this->get_kpi_sdk_data($kpi_ids_config, $kpi_type, $game_code, $day_arr, $table_name);

        $return_data = array();
        //for($i=0;$i<count($day_arr);$i++){
        for ($i = count($day_arr) - 1; $i >= 0; $i--) {
            $t = array();
            foreach ($kpi_ids_config as $key => $value) {
                $f_alias = $value;
                $t[$f_alias] = (isset($data[$day_arr[$i]][$key])) ? $data[$day_arr[$i]][$key] : 0;
            }

            $return_data[$i] = array_reverse($t);
            $return_data[$i]['log_date'] = $day_arr[$i];
            $return_data[$i] = array_reverse($return_data[$i]);
        }

        $return_data = array_values($return_data);
        return $return_data;
    }


    private function setZeroForMissKPI($data, $lstKpi)
    {
        foreach ($data as $report_date => $value) {
            $lstKpiDb = array();
            foreach ($value as $gameCode => $data2) {
                $lstKpiDb = array_keys($data2);
                //var_dump(!(count($lstKpi) == count($lstKpiDb)));exit();
                if (!(count($lstKpi) == count($lstKpiDb))) {
                    $missingKpi = array_diff($lstKpi, $lstKpiDb);
                    if (count($missingKpi) != 0) {
                        foreach ($missingKpi as $key) {
                            $data[$report_date][$gameCode][$key] = "0";
                        }
                    }
                }
            }
        }
        return $data;
    }

    public function get_os_export_datatable($fromDate, $toDate, $game_code, $kpi_ids_config, $kpi_type)
    {
        $yesterday = date("Y-m-d", time() - 24 * 60 * 60);
        if ($yesterday < $toDate)
            $toDate = $yesterday;

        $day_arr = $this->util->getDaysFromTiming($fromDate, $toDate, 4, false);
        rsort($day_arr);	//vinhdp added 2016-12-26 show asc
        $table_name = $kpi_type;

        $data = $this->get_kpi_sdk_data_os($kpi_ids_config, $kpi_type, $game_code, $day_arr, $table_name);

        $return_data = array();
        for ($i = count($day_arr) - 1; $i >= 0; $i--) {
            $t = array();
            foreach ($kpi_ids_config as $key => $value) {
                $f_alias = $value;
                $t_t = (isset($data[$day_arr[$i]][$key])) ? $data[$day_arr[$i]][$key] : "";
                $ios = 0;
                $android = 0;
                $other = 0;
                if ($t_t != "") {
                    $t_obj = json_decode($t_t);
                    foreach ($t_obj as $t_key => $value) {
                        if (strpos($t_key, "ios") !== FALSE) {
                            $ios += $value;
                        } else if (strpos($t_key, "android") !== FALSE) {
                            $android += $value;
                        } else {
                            $other += $value;
                        }
                    }
                }
                $t['android'][$f_alias] = $android;
                $t['ios'][$f_alias] = $ios;
                $t['other'][$f_alias] = $other;
            }

            $return_data[$i] = array_reverse($t);
            $return_data[$i]['log_date'] = $day_arr[$i];
            $return_data[$i] = array_reverse($return_data[$i]);
        }
        $return_data = array_values($return_data);

        for ($i = count($day_arr) - 1; $i >= 0; $i--) {
            //find special kpi
            $lstKpi = array_keys($kpi_ids_config);
            foreach ($lstKpi as $kpi) {
                if (strpos($kpi, '/') == true) {
                    $calcKpi = explode('/', $kpi);
                    $return_data[$i][$day_arr[$i]]['android'][$kpi] = $return_data[$i][$day_arr[$i]]['android'][$calcKpi[0]] / $return_data[$i][$day_arr[$i]]['android'][$calcKpi[1]];
                    $return_data[$i][$day_arr[$i]]['ios'][$kpi] = $return_data[$i][$day_arr[$i]]['ios'][$calcKpi[0]] / $return_data[$i][$day_arr[$i]]['ios'][$calcKpi[1]];
                    $return_data[$i][$day_arr[$i]]['other'][$kpi] = $return_data[$i][$day_arr[$i]]['other'][$calcKpi[0]] / $return_data[$i][$day_arr[$i]]['other'][$calcKpi[1]];

                }
            }
        }

        return $return_data;

    }

    public function get_kpi_sdk_data_os($kpi_ids_config, $kpi_type, $game_code, $day_arr, $table_name){
        $ubstats = $this->load->database('ubstats', TRUE);

        $lstKpi=array_keys($kpi_ids_config);
        foreach ($lstKpi as $kpi) {
            if (strpos($kpi, '/') == true) {
                $calcKpi = explode('/', $kpi);
                foreach ($calcKpi as $calc) {
                    $lstKpi[] = $calc;
                }
            }
        }


        $data = array();
        $select="date_format(report_date,'%Y-%m-%d') as log_date, kpi_value, kpi_id";
        $ubstats->select($select, false);
        $ubstats->from($table_name);
        $ubstats->where('game_code', $game_code);
        $ubstats->where_in('kpi_id', $lstKpi);
        $ubstats->where('source', "sdk");
        $ubstats->where_in('report_date', $day_arr);
        $ubstats->order_by('report_date', 'asc');
        $query = $ubstats->get();
        $result = $query->result_array();

        for($j=0;$j<count($result);$j++){
            $_log_date = $result[$j]['log_date'];
            $_kpi_value = $result[$j]['kpi_value'];
            $_kpi_id = $result[$j]['kpi_id'];
            $data[$_log_date][$_kpi_id] = $_kpi_value;
        }
        return $data;
    }


}