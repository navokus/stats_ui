<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 04/07/2016
 * Time: 14:35
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class qa_report_model extends MY_Model{

    public function __construct()
    {
        parent::__construct();
    }
    public function listGameReport($lstGameCode, $_date)
    {
        $date = $this->util->user_date_to_db_date($_date);

        $db_field_config = $this->util->db_field_config();

        $kpi_field_config['a1'] = $db_field_config['user_kpi']['4']['a1'];
        $kpi_field_config['pu1'] = $db_field_config['revenue_kpi']['4']['pu1'];
        $kpi_field_config['gr1'] = $db_field_config['revenue_kpi']['4']['gr1'];
        $kpi_field_config['npu1'] ='New PU1';
        $kpi_field_config['n1'] ='N1';

        $db = $this->load->database('ubstats', TRUE);

        $kpi_ids_config = $this->getKpiIDs($db, $kpi_field_config);


        $lstKpiId=array();
        foreach ($kpi_ids_config as $key => $value){
            $lstKpiId[] = $key;
        }

        //select * from game_kpi where source = 'ingame' and  kpi_id=10001 and game_code ="bklr" and report_date between date_sub('2016-11-27', INTERVAL 7 DAY) and '2016-11-27';

        $sql = "game_kpi.report_date,game_kpi.kpi_value,game_kpi.game_code, game_kpi.kpi_id";

        $db->select($sql, false);
        $db->from("game_kpi");

        $db->join('kpi_desc kd', 'game_kpi.kpi_id = kd.kpi_id', 'left');
        $db->join('mt_report_source mrs', 'mrs.game_code = game_kpi.game_code and mrs.group_id = kd.group_id and mrs.data_source = game_kpi.source', 'left');
        $db->join("games", "games.GameCode = game_kpi.game_code");

        $db->where('report_date BETWEEN date_sub("'. date('Y-m-d', strtotime($date)). '",INTERVAL 7 DAY) and "'. date('Y-m-d', strtotime($date)).'"');
        $db->where('game_kpi.source',"ingame");
        $db->where_in('game_kpi.game_code', $lstGameCode);
        $db->where_in('game_kpi.kpi_id', $lstKpiId);
        $db->order_by('game_kpi.kpi_id', 'asc');
        $query =$db->get();
        $resultQuery = $query->result_array();



        $rotate_db_data = $this->util->rotate_db_data($resultQuery);



        foreach ($resultQuery as $key =>$value){
            $gameCode = $value['game_code'];
            $kpiId = $value['kpi_id'];
            $kpiname=$kpi_ids_config[$kpiId];
            $logDate =$value['report_date'];
            $kpi_value = $value['kpi_value'];
            if(is_null($kpi_value)||empty($kpi_value)){
                $kpi_value=0;
            }

            $result[$gameCode][$kpiname][$logDate] = $kpi_value;
        }
        $yesterday = date('Y-m-d',strtotime($date . "-1 days"));


        foreach ($result as $gameCode => $v){
            foreach ($kpi_ids_config as $key => $value){
                $currentValue = $result[$gameCode][$value][$date];
                $yesterdayValue = $result[$gameCode][$value][$yesterday];

                $cal= round(($currentValue/$yesterdayValue)*100);
                $cal= $cal."%";
                $result[$gameCode][$value]['ratioYesterday']=$cal;
                $result[$gameCode][$value]['currentValue']=$currentValue;
                $result[$gameCode][$value]['yesterday']=$yesterdayValue;
            }
        }

        return $result;
    }



}