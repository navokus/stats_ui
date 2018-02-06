<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_model extends CI_Model
{


    private $tableLogView = 'view_log';

    public function __construct()
    {
        parent::__construct();

    }


    public function add($domain, $content)
    {
        try {
            $data['domain'] = $domain;
            $data['content'] = $content;
            $data['created'] = date('Y-m-d H:i:s');
            $this->db->insert("logs", $data);
            return $data;
        } catch (Exception $e) {
            return false;
        }
    }
    private function getAllReportType(){
        $this->db->select("url", false);
        $this->db->from("mt_report");
        $query = $this->db->get();
        $result = $query->result_array();
        $return = array();
        for($i=0;$i<count($result);$i++){
            $return[] = $result[$i]['url'];
        }
        return $return;
    }

    public function addTrackingView($domain, $gameCode, $url)
    {
        $allReportType = $this->getAllReportType();
        $allReportType[] = "dashboard2";
        $report_uri = strtolower(uri_string());

        if(!in_array($report_uri, $allReportType)){
            return 0;
        }

        $query_string = $_SERVER['QUERY_STRING'];
        if ($query_string != "") {
            $url = $url . "?" . $query_string;
        }
        $admin = $this->util->is_admin($domain);
        if ($admin) {
            return 0;
        }
        try {
            $data['domain'] = $domain;
            $data['game_code'] = $gameCode;
            $data['url'] = $url;
            $data['log_date'] = date('Y-m-d');
            $data['report_uri'] =$report_uri;
            $data['created'] = date('Y-m-d H:i:s');
            $this->db->insert($this->tableLogView, $data);
            return $data;
        } catch (Exception $e) {
            return false;
        }
    }
}

/* End of file game_model.php */
/* Location: ./application/models/game_model.php */