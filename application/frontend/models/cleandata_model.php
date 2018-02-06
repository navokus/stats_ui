<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cleandata_model extends MY_Model {
    public function __construct()
    {
        parent::__construct();
        $this->load->library ( 'util' );
    }
    public function clean($data)
    {
        $db1 = $this->load->database('ubstats',TRUE);
        $tableName = $data['table_name'];
        //$tableName = "game_kpi";

        $gameCode = $data['default_game'];
        //$gameCode = "tlbbw";
        $date = $this->util->user_date_to_db_date($data['kpidatepicker']);
        //$date = "2016-01-04";
        $aUser = $this->session->userdata('infoUser');
        if($this->util->is_admin($aUser["username"])){
            $db1->select("*");
            $db1->from($tableName);
            $db1->where("game_code",$gameCode);
            $db1->where("report_date <='$date'");
            $query = $db1->get();
            $result = $query->result_array();
        }
        if(count($result)!=0){
            foreach ($result as $key => $value){
                $result[$key]['game_code']= $result[$key]['game_code'].'_bk';
            }
            $db1->update_batch($tableName,$result,'id');
            return $this->insert_log($tableName,$gameCode,$date,count($result),"clean");

        }
        return false;
    }

    public function insert_log($tableName, $gameCode, $date,$numRow,$action)
    {
        $data['game_code'] = $gameCode;
        $data['to_date'] = $date;
        $data['table_name'] = $tableName;
        $data['created'] = date('Y-m-d H:i:s');
        $data['num_row_affect'] = $numRow;
        $data['action'] = $action;
        $db1 = $this->load->database('ubstats', TRUE);
        try {
            return $db1->insert("log_clean_restore", $data);
        } catch (Exception $e) {
            return false;
        }

    }
    public function restore($data){
        $db1 = $this->load->database('ubstats', TRUE);
        $tableName = $data['table_name'];
        $gameCode = $data['default_game'].'_bk';
        $date = $this->util->user_date_to_db_date($data['kpidatepicker']);
        $aUser = $this->session->userdata('infoUser');
        if($this->util->is_admin($aUser["username"])){
            $db1->select("*");
            $db1->from($tableName);
            $db1->where("game_code",$gameCode);
            $db1->where("report_date <='$date'");
            $db1->order_by('report_date', 'desc');
            $query = $db1->get();
            $result = $query->result_array();
        }
        if(count($result)!=0){
            foreach ($result as $key => $value){
                $result[$key]['game_code']= str_ireplace('_bk','',$result[$key]['game_code']);
            }
            $db1->update_batch($tableName,$result,'id');
            return $this->insert_log($tableName,$gameCode,$date,count($result),"restore");

        }
        return false;
    }


    /*public function deleteData($dbDelete,$tableName,$resultReceive){
        $arrId = array();
        foreach ($resultReceive as $key => $value){
            $arrId[] = $value['id'];
        }
        $dbDelete->where_in('id', $arrId);
        $dbDelete->delete($tableName);
        //var_dump($dbDelete->last_query());

    }
    public function restore($data){
        $db1 = $this->load->database('ubstats_bk',TRUE);
        $tableName = $data['table_name'];
        $gameCode = $data['default_game'];
        $date = $this->util->user_date_to_db_date($data['kpidatepicker']);
        $aUser = $this->session->userdata('infoUser');
        if($this->util->is_admin($aUser["username"])){
            $db1->select("*");
            $db1->from($tableName);
            $db1->where("game_code",$gameCode);
            $db1->where("report_date <='$date'");
            $db1->order_by('report_date', 'desc');
            $query = $db1->get();
            $result = $query->result_array();
        }
        if(count($result)!=0){
            $db_bk = $this->load->database('ubstats',TRUE);
            if ($this->insert_bk($db_bk,$tableName,$result)){
                $db1 = $this->load->database('ubstats_bk',TRUE);
                return $this->deleteData($db1,$tableName,$result);
            }
        }
        return false;
    }*/

}
