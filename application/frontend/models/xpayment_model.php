<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class xpayment_model extends MY_Model {


    public function __construct()
    {
        parent::__construct();

    }

    public function getZdb($date)
    {

        $db = $this->load->database('zstatsDb', TRUE);

        $result = array();
        $sql="FLOOR(sum(amount_pp)) as revenue, app_id";
        $db->select($sql, false);
        $db->from("summary_rev_daily_per_app");
        $db->where('yyyymmdd', $date);
        $db->group_by('app_id');
        $query = $db->get();
        $result = $query->result_array();
        //echo $db->last_query(); die;
        return $result;
    }
    
    
    public function getApps()
    {
    
    	$db = $this->load->database('ubstats', TRUE);
    
    	$result = array();
    	$sql="app_id,game_code";
    	$db->select($sql, false);
    	$db->from("directbilling_apps");
    	$query = $db->get();
    	$result = $query->result_array();
    	//echo $db->last_query(); die;
    	return $result;
    }
    
    
    public function getUbdb($date)
    {
    
    	$db = $this->load->database('ubstats', TRUE);
    
    	$result = array();
    	$sql="game_kpi.game_code,game_kpi.kpi_value,game_kpi.source";
    	$db->select($sql, false);
    	$db->from("game_kpi");

        $db->join('kpi_desc kd', 'game_kpi.kpi_id = kd.kpi_id', 'left');
        $db->join('mt_report_source mrs', 'mrs.game_code = game_kpi.game_code and mrs.group_id = kd.group_id and mrs.data_source = game_kpi.source', 'left');
        $db->where("mrs.kpi_type", "game_kpi");

    	$db->where('game_kpi.report_date', $date);
    	$db->where('game_kpi.kpi_id', 16001);
    	$query = $db->get();
    	$result = $query->result_array();
    
    	return $result;
    }
}

