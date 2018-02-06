<?php
/**
 * Created by IntelliJ IDEA.
 * User: canhtq
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class kpiaggregation_model extends MY_Model
{

    private $table = 'game_kpi';

    public function __construct()
    {
        parent::__construct();

    }

    public function aggregateByPlatform($kpiId, $date,$reportType)
    {
    	$ok=false;
    	$db = $this->load->database('ubstats', TRUE);
    	$resultAgg = array();
    	$sql="game_kpi.report_date, games.GameType2 as game_type, sum(game_kpi.kpi_value) as kpi_value, count(game_kpi.game_code) as total_games";
    	$db->select($sql, false);
    	$db->from("game_kpi");

        $db->join('kpi_desc kd', 'game_kpi.kpi_id = kd.kpi_id', 'left');
        $db->join('mt_report_source mrs', 'mrs.game_code = game_kpi.game_code and mrs.group_id = kd.group_id and mrs.data_source = game_kpi.source', 'left');
        $db->join("games","games.GameCode = game_kpi.game_code");
        $db->where("mrs.kpi_type", "game_kpi");

    	$db->where('game_kpi.report_date', $date);
    	$db->where('game_kpi.kpi_id', $kpiId);
    	$db->group_by('games.GameType2,game_kpi.report_date');
    	$query = $db->get();
    	$resultAgg = $query->result_array();
    	//echo $db->last_query(); die;

    	$sql="id";
    	$db->select($sql, false);
    	$db->from("data_sum");
    	$db->where('report_date', $date);
    	$db->where('kpi_id', $kpiId);
    	$db->where('report_type', $reportType);
    	$query = $db->get();
    	$resultCheck = $query->result_array();
    	
    	$data = array(
    			'kpi_id' => $kpiId,
    			'report_date'  => $date,
    			'report_type'  => $reportType,
    			'data'=>json_encode($resultAgg)
    	);
    	if(count($resultCheck) >0){
    		$id = $resultCheck[0]["id"];
    		$data['id']=$id;
    		$this->db->replace('data_sum', $data);
    		$ok=true;
    	}else{
    		$this->db->insert('data_sum', $data);
    		$ok=true;
    	}
    	return array($ok,$reportType);
    }
    
    
    public function aggregateRanking($kpiId, $date)
    {
    	$ok=false;
    	$reportType="all-ranking";
    	$db = $this->load->database('ubstats', TRUE);
    	$resultAgg = array();
    	$sql="game_kpi.report_date,game_kpi.game_code, games.GameName as game_name, game_kpi.kpi_value, game_kpi.kpi_id";
    	$db->select($sql, false);
    	$db->from("game_kpi");

        $db->join('kpi_desc kd', 'game_kpi.kpi_id = kd.kpi_id', 'left');
        $db->join('mt_report_source mrs', 'mrs.game_code = game_kpi.game_code and mrs.group_id = kd.group_id and mrs.data_source = game_kpi.source', 'left');
        $db->join("games","games.GameCode = game_kpi.game_code");
        $db->where("mrs.kpi_type", "game_kpi");

    	$db->where('game_kpi.report_date', $date);
    	$db->where('game_kpi.kpi_id', $kpiId);
    	$this->db->order_by("game_kpi.kpi_value", "desc");
    	$query = $db->get();
    	$resultAgg = $query->result_array();
    	//echo $db->last_query(); die;

    	$idx =0;
    	for ($i = 0; $i < count($resultAgg); $i++) {
    		$resultAgg[$i]["rank"]=$i+1;
    	}
    	$sql="id";
    	$db->select($sql, false);
    	$db->from("data_sum");
    	$db->where('report_date', $date);
    	$db->where('report_type', $reportType);
    	$db->where('kpi_id', $kpiId);
    	$query = $db->get();
    	$resultCheck = $query->result_array();
    	 
    	$data = array(
    			'kpi_id' => $kpiId,
    			'report_date'  => $date,
    			'report_type'  => $reportType,
    			'data'=>json_encode($resultAgg)
    	);
    	if(count($resultCheck) >0){
    		$id = $resultCheck[0]["id"];
    		$data['id']=$id;
    		$this->db->replace('data_sum', $data);
    		$ok=true;
    	}else{
    		$this->db->insert('data_sum', $data);
    		$ok=true;
    	}
    	return array($ok,$reportType);
    }
    
    public function aggregateRankingPC($kpiId, $date)
    {
    	$ok=false;
    	$reportType="pc-ranking";
    	$db = $this->load->database('ubstats', TRUE);
    	$resultAgg = array();
    	$sql="game_kpi.report_date,game_kpi.game_code, games.GameName as game_name, game_kpi.kpi_value, game_kpi.kpi_id";
    	$db->select($sql, false);
    	$db->from("game_kpi");

        $db->join('kpi_desc kd', 'game_kpi.kpi_id = kd.kpi_id', 'left');
        $db->join('mt_report_source mrs', 'mrs.game_code = game_kpi.game_code and mrs.group_id = kd.group_id and mrs.data_source = game_kpi.source', 'left');
        $db->join("games","games.GameCode = game_kpi.game_code");
        $db->where("mrs.kpi_type", "game_kpi");

    	$db->where('game_kpi.report_date', $date);
    	$db->where('game_kpi.kpi_id', $kpiId);
    	$db->where_in('games.GameType2', array(1,3));
    	$this->db->order_by("game_kpi.kpi_value", "desc");
    	$query = $db->get();
    	$resultAgg = $query->result_array();
    	//echo $db->last_query(); die;
    	$idx =0;
    	for ($i = 0; $i < count($resultAgg); $i++) {
    		$resultAgg[$i]["rank"]=$i+1;
    	}
    	$sql="id";
    	$db->select($sql, false);
    	$db->from("data_sum");
    	$db->where('report_date', $date);
    	$db->where('report_type', $reportType);
    	$db->where('kpi_id', $kpiId);
    	$query = $db->get();
    	$resultCheck = $query->result_array();
    
    	$data = array(
    			'kpi_id' => $kpiId,
    			'report_date'  => $date,
    			'report_type'  => $reportType,
    			'data'=>json_encode($resultAgg)
    	);
    	if(count($resultCheck) >0){
    		$id = $resultCheck[0]["id"];
    		$data['id']=$id;
    		$this->db->replace('data_sum', $data);
    		$ok=true;
    	}else{
    		$this->db->insert('data_sum', $data);
    		$ok=true;
    	}
    	return array($ok,$reportType);
    }
    
    public function aggregateRankingMobile($kpiId, $date)
    {
    	$ok=false;
    	$reportType="mobile-ranking";
    	$db = $this->load->database('ubstats', TRUE);
    	$resultAgg = array();
    	$sql="game_kpi.report_date,game_kpi.game_code, games.GameName as game_name, game_kpi.kpi_value, game_kpi.kpi_id";
    	$db->select($sql, false);
    	$db->from("game_kpi");

        $db->join('kpi_desc kd', 'game_kpi.kpi_id = kd.kpi_id', 'left');
        $db->join('mt_report_source mrs', 'mrs.game_code = game_kpi.game_code and mrs.group_id = kd.group_id and mrs.data_source = game_kpi.source', 'left');
        $db->join("games","games.GameCode = game_kpi.game_code");
        $db->where("mrs.kpi_type", "game_kpi");

    	$db->where('game_kpi.report_date', $date);
    	$db->where('game_kpi.kpi_id', $kpiId);
    	$db->where('games.GameType2', 2);
    	$this->db->order_by("game_kpi.kpi_value", "desc");
    	$query = $db->get();
    	$resultAgg = $query->result_array();
    	//echo $db->last_query(); die;
    	$idx =0;
    	for ($i = 0; $i < count($resultAgg); $i++) {
    		$resultAgg[$i]["rank"]=$i+1;
    	}
    	$sql="id";
    	$db->select($sql, false);
    	$db->from("data_sum");
    	$db->where('report_date', $date);
    	$db->where('report_type', $reportType);
    	$db->where('kpi_id', $kpiId);
    	$query = $db->get();
    	$resultCheck = $query->result_array();
    
    	$data = array(
    			'kpi_id' => $kpiId,
    			'report_date'  => $date,
    			'report_type'  => $reportType,
    			'data'=>json_encode($resultAgg)
    	);
    	if(count($resultCheck) >0){
    		$id = $resultCheck[0]["id"];
    		$data['id']=$id;
    		$this->db->replace('data_sum', $data);
    		$ok=true;
    	}else{
    		$this->db->insert('data_sum', $data);
    		$ok=true;
    	}
    	return array($ok,$reportType);
    }
}

