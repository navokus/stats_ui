<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 04/07/2016
 * Time: 14:35
 */

class topowner_Model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }
    public function listOwnerGames($lstGameCode,$kpi_id, $_date,$groupBy = false)
    {
        $date = $this->util->user_date_to_db_date($_date);
        /*
        if(!$groupBy){
        $selectStm ="games.GameCode,games.GameName,games.data_source,owner,kpi_id,kpi_value,report_date";
        $this->db->order_by("owner", "asc");
        }
        else{
            $this->db->group_by($groupBy);
            $selectStm ="games.GameCode,games.GameName,games.data_source,owner,kpi_id,sum(kpi_value) as total,report_date";
        }
        $this->db->select($selectStm);
        $this->db->from("games");
        $this->db->join("game_kpi",
            'games.GameCode = game_kpi.game_code and games.data_source = game_kpi.source',
            'left');
        $this->db->where("owner is not null and owner !=''");
        $this->db->where("kpi_id = $kpi_id");
        $this->db->where_in('GameCode',$lstGameCode);
        //$this->db->where('report_date BETWEEN "'. date('Y-m-d', strtotime($fromDate)). '" and "'. date('Y-m-d', strtotime($toDate)).'"');
        $this->db->where("report_date like '%$date%'");
        //$this->db->where("report_date like '%2017-03-10%'");
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;*/
        if(!$groupBy){
            $sql = "game_kpi.game_code,game_kpi.report_date,game_kpi.kpi_value,games.GameName as game_name,games.owner";
            $this->db->order_by("games.owner", "asc");
        }
        else{
            $this->db->group_by($groupBy);
            $sql = "game_kpi.game_code,game_kpi.report_date,sum(game_kpi.kpi_value) as total,games.GameName as game_name,games.owner";
        }
        $this->db->select($sql, false);
        $this->db->from("game_kpi");
        $this->db->join('kpi_desc kd', 'game_kpi.kpi_id = kd.kpi_id', 'left');
        $this->db->join('mt_report_source mrs', 'mrs.game_code = game_kpi.game_code and mrs.group_id = kd.group_id and mrs.data_source = game_kpi.source', 'left');
        $this->db->join("games", "games.GameCode = game_kpi.game_code");
        $this->db->where("mrs.kpi_type", "game_kpi");
        $this->db->where('game_kpi.report_date', $date);
        $this->db->where('game_kpi.kpi_id', $kpi_id);
        $this->db->where("owner is not null and owner !=''");
        $this->db->where_in('games.GameCode', $lstGameCode);
        $this->db->order_by('game_kpi.kpi_value', 'desc');

        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function top10Owner($lstGameCode,$kpi_id, $_date,$groupBy = false,$limit)
    {
        $date = $this->util->user_date_to_db_date($_date);
        if(!$groupBy){
            $sql = "game_kpi.game_code,game_kpi.report_date,game_kpi.kpi_value,games.GameName as game_name,games.owner";
        }
        else{
            $this->db->group_by($groupBy);
            $sql = "game_kpi.game_code,game_kpi.report_date,sum(game_kpi.kpi_value) as total,games.GameName as game_name,games.owner";
            $this->db->order_by("total", "desc");
        }
        $this->db->select($sql, false);
        $this->db->from("game_kpi");
        $this->db->join('kpi_desc kd', 'game_kpi.kpi_id = kd.kpi_id', 'left');
        $this->db->join('mt_report_source mrs', 'mrs.game_code = game_kpi.game_code and mrs.group_id = kd.group_id and mrs.data_source = game_kpi.source', 'left');
        $this->db->join("games", "games.GameCode = game_kpi.game_code");
        $this->db->where("mrs.kpi_type", "game_kpi");
        $this->db->where('game_kpi.report_date', $date);
        $this->db->where('game_kpi.kpi_id', $kpi_id);
        $this->db->where("owner is not null and owner !=''");
        $this->db->where_in('games.GameCode', $lstGameCode);
        $this->db->order_by('game_kpi.kpi_value', 'desc');


        /*if(!$groupBy) {
            $selectStm = "games.GameCode,games.GameName,games.data_source,owner,kpi_id,kpi_value,report_date";
        }
        else{
            $this->db->group_by($groupBy);
            $selectStm ="games.GameCode,games.GameName,games.data_source,owner,kpi_id,sum(kpi_value) as total,report_date";
            $this->db->order_by("total", "desc");
        }
        $this->db->select($selectStm);
        $this->db->from("games");
        $this->db->join("game_kpi",
            'games.GameCode = game_kpi.game_code and games.data_source = game_kpi.source',
            'left');
        $this->db->where("owner is not null and owner !=''");
        $this->db->where("kpi_id = $kpi_id");
       $this->db->where("report_date like '%$date%'");
        $this->db->where_in('GameCode',$lstGameCode);*/
       //$this->db->where("report_date like '%2017-03-10%'");
        //$this->db->where('report_date BETWEEN "'. date('Y-m-d', strtotime($fromDate)). '" and "'. date('Y-m-d', strtotime($toDate)).'"');

        $this->db->limit($limit);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    public function listGamesByLoginUser()
    {
        $aUser = $this->session->userdata('infoUser');
        //TEST
        /*$aUser['GroupId']= '21';*/
        $this->db->select('*');
        $this->db->from("games");
        $this->db->where("Status", 1);
        if ($aUser['GroupId'] != 1) {
            $this->db->where('GameCode IN (SELECT GameCode FROM game_groups WHERE GroupId = ' . $aUser['GroupId'] . ')');
        }
        $this->db->order_by('GameName', 'asc');

        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }


}