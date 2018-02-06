<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 14/03/2016
 * Time: 23:36
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Appsflyer_model extends MY_Model
{
    private $_table_name = "marketing_kpi";

    public function __construct()
    {
        parent::__construct();
    }


    public function getMarketingReport($gameCode,$fromDate,$toDate){

        $db_field_config = $this->util->db_field_marketing();
        $kpi_field_config = array_merge($db_field_config['user_kpi']["4"],

        $db_field_config['revenue_kpi']["4"], $db_field_config['revenue_kpi']["5"], $db_field_config['revenue_kpi']["6"],
        $db_field_config['revenue_kpi']["3"]);
        $ubstats = $this->load->database('ubstats', TRUE);
        $kpi_ids_config = $this->getKpiIDs($ubstats, $kpi_field_config);


        $data = $this->get_marketing_kpi_data($kpi_ids_config, $gameCode, $fromDate, $toDate, $this->_table_name);
        return $data;
    }
    
}

