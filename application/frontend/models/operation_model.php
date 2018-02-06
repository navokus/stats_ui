<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 04/07/2016
 * Time: 14:35
 */

class Operation_Model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function check_report_hourly($day_arr, $game_list, $kpi_id_arr){
        $kpi_ids_config = $this->getKpiIDs(null, $kpi_id_arr);
        $db = $this->load->database('ubstats', TRUE);
        $sql="gk.kpi_id, gk.kpi_value, kd.kpi_name as kpi_code, gk.game_code";
        $db->select($sql, false);
        $db->from("game_kpi_hourly gk");
        $db->join("games g", 'gk.game_code = g.GameCode', 'left');
        $db->join('kpi_desc kd', 'gk.kpi_id = kd.kpi_id', 'left');
        $db->join('mt_report_source mrs', 'mrs.game_code = gk.game_code and mrs.group_id = kd.group_id and mrs.data_source = gk.source', 'left');
        $db->where("mrs.kpi_type", "hourly_kpi");
        $db->where_in("gk.game_code", $game_list);
        $db->where_in("gk.report_date", $day_arr);
        $db->where_in('gk.kpi_id', array_keys($kpi_ids_config));
        $query = $db->get();
        $result = $query->result_array();
        $return = array();
        for($i=0;$i<count($result);$i++){
            $return[$result[$i]['game_code']][$result[$i]['kpi_code']] = $result[$i]['kpi_value'];
        }
        return $return;
    }
    public function get_server_data() {
        /*
        $key_kpi_ids = array("gr1" => "gr1");

        $kpi_ids_config = $this->getKpiIDs(null, $key_kpi_ids);

        $this->db->select("gk.report_date,gk.game_code,gk.source,mrs.data_source,kd.kpi_name,gk.kpi_id,kd.group_id,gk.kpi_value");
        $this->db->from("server_kpi_json gk");
        //$this->db->join('games g', 'gk.game_code = g.GameCode', 'left');
        $this->db->join('kpi_desc kd', 'gk.kpi_id = kd.kpi_id', 'left');
        $this->db->join('mt_report_source mrs', 'mrs.game_code = gk.game_code and mrs.group_id = kd.group_id and mrs.data_source = gk.source', 'left');
        $this->db->where("mrs.kpi_type", "game_kpi");
        //$this->db->where("g.Status", "1");
        $this->db->where_in("report_date", $day_arr);
        $this->db->where_in("gk.kpi_id", array_keys($kpi_ids_config));
        $this->db->where_in("gk.game_code", $active_game);

        $query = $this->db->get();
        $rows = $query->result_array();
        */
    }

    public function insert_fw_monitor($data){
        $this->db->insert("fw_monitor", $data);
    }
    public function compare_kpi_by_source($day_arr, $kpi_id_arr,$source_arr, $game_arr){
        $kpi_ids_config = $this->getKpiIDs(null, $kpi_id_arr);
        $db = $this->load->database('ubstats', TRUE);
        $sql="g.GameCode,g.GameName,gk.report_date, gk.kpi_id,kd.kpi_name,gk.source, kd.group_id, gk.kpi_value";
        $db->select($sql, false);
        $db->from("game_kpi gk");
        $db->join("games g", 'gk.game_code = g.GameCode', 'left');
        $db->join('kpi_desc kd', 'gk.kpi_id = kd.kpi_id', 'left');
        $db->where("g.GameCode is not null");
        $db->where_in("gk.report_date", $day_arr);
        $db->where_in("gk.source", $source_arr);
        $db->where_in("gk.game_code", $game_arr);
        $db->where_in('gk.kpi_id', array_keys($kpi_ids_config));
        $db->order_by("gk.kpi_id");
        $query = $db->get();
        $result = $query->result_array();
        return $result;
    }

    public function compare_key_kpi($day_arr, $key_kpi_ids, $active_game)
    {
        $kpi_ids_config = $this->getKpiIDs(null, $key_kpi_ids);

        $this->db->select("gk.report_date,gk.game_code,g.GameName, gk.source,mrs.data_source,kd.kpi_name,gk.kpi_id,kd.group_id,gk.kpi_value");
        $this->db->from("game_kpi gk");
        $this->db->join('games g', 'gk.game_code = g.GameCode', 'left');
        $this->db->join('kpi_desc kd', 'gk.kpi_id = kd.kpi_id', 'left');
        $this->db->join('mt_report_source mrs', 'mrs.game_code = gk.game_code and mrs.group_id = kd.group_id and mrs.data_source = gk.source', 'left');
        $this->db->where("mrs.kpi_type", "game_kpi");
        $this->db->where("g.Status != ", "0");
        $this->db->where_in("report_date", $day_arr);
        $this->db->where_in("gk.kpi_id", array_keys($kpi_ids_config));
        $this->db->where_in("gk.game_code", $active_game);

        $query = $this->db->get();
        $rows = $query->result_array();

        $count = count($rows);
        for ($i = 0; $i < $count; $i++) {
            $row = $rows[$i];
            $_game_code = $row['game_code'];
            $_game_name = $row['GameName'];
            $_kpi_value = $row['kpi_value'];
            $_kpi_code = $row['kpi_name'];
            $_report_date = $row['report_date'];
            $_source = $row['data_source'];

            $data[$_game_code][$_kpi_code][$_report_date] = $_kpi_value;
            $data_source[$_game_code][$_kpi_code] = $_source;
            $data_source[$_game_code]['game_name'] = $_game_name;
        }

        return array("data" => $data, "data_source" => $data_source);

    }

    public function kpi_migration_status($day_arr, $kpi_id_arr, $kpi_type){
        $kpi_ids_config = $this->getKpiIDs(null, $kpi_id_arr);
        $db = $this->load->database('ubstats', TRUE);
        $sql="g.GameCode,g.GameName,gk.report_date, gk.kpi_id,kd.kpi_name,gk.source, kd.group_id, gk.kpi_value, mrs.kpi_type";
        $db->select($sql, false);
        $db->from($kpi_type . " gk");
        $db->join("games g", 'gk.game_code = g.GameCode', 'left');
        $db->join('kpi_desc kd', 'gk.kpi_id = kd.kpi_id', 'left');
        $db->join('mt_report_source mrs', 'mrs.game_code = gk.game_code and mrs.group_id = kd.group_id and mrs.data_source = gk.source', 'left');
        //$db->join('mt_report_source mrs', 'mrs.game_code = gk.game_code and mrs.group_id = kd.group_id and mrs.data_source = gk.source and mrs.kpi_type="' . $kpi_type . '"', 'left');
        $db->where("mrs.kpi_type", $kpi_type);
        $db->where("gk.game_code is not null");
        $db->where_in("gk.report_date", $day_arr);
        $db->where_in('gk.kpi_id', array_keys($kpi_ids_config));
        $db->group_by('gk.kpi_id');
        $db->group_by('gk.game_code');
        $query = $db->get();
        $result = $query->result_array();
        return $result;
    }

    public function view_trend($from,$to){
        $data = array();
        $this->db->select("count(*) as total, log_date");
        $this->db->from("view_log");
        $this->db->where("log_date >= ", $from);
        $this->db->where("log_date <= ", $to);
        $this->db->group_by("log_date");
        $query = $this->db->get();
        
        $rows = $query->result_array();
        for ($i = 0; $i < count($rows); $i++) {
            $data[$rows[$i]['log_date']] = $rows[$i]['total'];
        }
        return $data;
    }
    public function view_statistics($day_arr, $metric_arr)
    {
        $data = array();
        foreach ($day_arr as $key => $value) {
            $from = $value[0];
            $to = $value[1];
            for ($k = 0; $k < count($metric_arr); $k++) {
                $metric = $metric_arr[$k];
                $this->db->select($metric . " as gb, count(*) as total");
                $this->db->from("view_log");
                $this->db->where("log_date >= ", $from);
                $this->db->where("log_date <= ", $to);
                $this->db->group_by("gb");
                $query = $this->db->get();
                $rows = $query->result_array();
                for ($i = 0; $i < count($rows); $i++) {
                    $gb = $rows[$i]['gb'];
                    $total = $rows[$i]['total'];
                    $data[$key][$metric][$gb] = $total;
                }
            }
        }
        return $data;
    }
}