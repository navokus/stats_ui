<?php

/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 04/07/2016
 * Time: 14:35
 */
class Mail_Model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function getSumKpi($kpi, $lstDate)
    {
        $ownerNotIn = array('gsn', 'fbs1', 'b2s', 'fbs2', 'kmf', 'myplay', 'gst', 'fbs');
        $db = $this->load->database('ubstats', TRUE);
        $sql = "market, sum(game_kpi.kpi_value) as total, game_kpi.report_date";
        $db->select($sql, false);
        $db->from("game_kpi");
        $db->join('kpi_desc kd', 'game_kpi.kpi_id = kd.kpi_id', 'left');
        $db->join('mt_report_source mrs', 'mrs.game_code = game_kpi.game_code and mrs.group_id = kd.group_id and mrs.data_source = game_kpi.source', 'left');
        $db->join("games", "games.GameCode = game_kpi.game_code");
        $db->where("mrs.kpi_type", "game_kpi");
        $db->where_in('game_kpi.report_date', $lstDate);
        $db->where('game_kpi.kpi_id', $kpi);
        $db->where_in('games.GameType2', array(2));
        $db->where('games.status', 1);
        /*$db->where('games.SendMail', 1);*/
        $db->where_not_in('owner', $ownerNotIn);
        $db->group_by('market,game_kpi.report_date');
        $db->order_by('games.OpenDate', 'desc');
        $db->order_by('games.market', 'asc');
        $db->order_by('game_kpi.report_date', 'desc');
        $query = $db->get();
        $result = $query->result_array();
        /*  var_dump($db->last_query());
          exit();*/
        return $result;
    }


    public function getKpiFromMobileGame($lstKpiId, $lstDate)
    {
        $ownerNotIn = array('gsn', 'fbs1', 'b2s', 'fbs2', 'kmf', 'myplay', 'gst', 'fbs');
        $db = $this->load->database('ubstats', TRUE);
        $sql = "games.market,game_kpi.game_code,game_kpi.report_date,game_kpi.kpi_id,game_kpi.kpi_value,games.GameName as game_name";
        $db->select($sql, false);
        $db->from("game_kpi");
        $db->join('kpi_desc kd', 'game_kpi.kpi_id = kd.kpi_id', 'left');
        $db->join('mt_report_source mrs', 'mrs.game_code = game_kpi.game_code and mrs.group_id = kd.group_id and mrs.data_source = game_kpi.source', 'left');
        $db->join("games", "games.GameCode = game_kpi.game_code");
        $db->where("mrs.kpi_type", "game_kpi");
        $db->where_in('game_kpi.report_date', $lstDate);
        $db->where_in('game_kpi.kpi_id', $lstKpiId);
        $db->where_in('games.GameType2', array(2));
        $db->where_not_in('owner', $ownerNotIn);
        $db->where('games.status', 1);
        /*$db->where('games.SendMail', 1);*/
        $db->order_by('games.OpenDate', 'desc');
        $db->order_by('games.market', 'asc');
        $db->order_by('game_kpi.report_date', 'desc');
        $db->order_by('game_kpi.kpi_id', 'asc');
        $query = $db->get();
        $result = $query->result_array();
        $data = array();
        foreach ($result as $key => $value) {
            $data[$value['game_code']]['report_date'][$value['report_date']]['kpi_id'][$value['kpi_id']] = $value['kpi_value'];
            $data[$value['game_code']]['game_name'] = $value['game_name'];
            $data[$value['game_code']]['market'] = $value['market'];
        }
        $data = $this->setZeroForMissKPI($data, $lstKpiId, $lstDate);
        /*var_dump($data);
        exit();*/
        return $data;

    }

    public function getKpiFromMobileGameNotGE($lstKpiId, $lstDate)
    {
        $owner = array('gsn', 'fbs1', 'b2s', 'fbs2', 'kmf', 'myplay', 'gst', 'fbs');
        $db = $this->load->database('ubstats', TRUE);
        $sql = "games.market,game_kpi.game_code,game_kpi.report_date,game_kpi.kpi_id,game_kpi.kpi_value,games.GameName as game_name";
        $db->select($sql, false);
        $db->from("game_kpi");
        $db->join('kpi_desc kd', 'game_kpi.kpi_id = kd.kpi_id', 'left');
        $db->join('mt_report_source mrs', 'mrs.game_code = game_kpi.game_code and mrs.group_id = kd.group_id and mrs.data_source = game_kpi.source', 'left');
        $db->join("games", "games.GameCode = game_kpi.game_code");
        $db->where("mrs.kpi_type", "game_kpi");
        $db->where_in('game_kpi.report_date', $lstDate);
        $db->where_in('game_kpi.kpi_id', $lstKpiId);
        $db->where_in('games.GameType2', array(2));
        $db->where_in('owner', $owner);
        $db->where('games.status', 1);
        /*$db->where('games.SendMail', 1);*/
        $db->order_by('games.OpenDate', 'desc');
        $db->order_by('games.market', 'asc');
        $db->order_by('game_kpi.report_date', 'desc');
        $db->order_by('game_kpi.kpi_id', 'asc');
        $query = $db->get();
        $result = $query->result_array();
        $data = array();
        foreach ($result as $key => $value) {
            $data[$value['game_code']]['report_date'][$value['report_date']]['kpi_id'][$value['kpi_id']] = $value['kpi_value'];
            $data[$value['game_code']]['game_name'] = $value['game_name'];
            $data[$value['game_code']]['market'] = $value['market'];
        }
        $data = $this->setZeroForMissKPI($data, $lstKpiId, $lstDate);
        /*var_dump($data);
        exit();*/
        return $data;

    }
    public function sortMobileGame($kpiId, $date, $market, $limit)
    {
        $ownerNotIn = array('gsn', 'fbs1', 'b2s', 'fbs2', 'kmf', 'myplay', 'gst', 'fbs');
        $db = $this->load->database('ubstats', TRUE);
        $sql = "game_kpi.game_code,game_kpi.kpi_value";
        $db->select($sql, false);
        $db->from("game_kpi");
        $db->join('kpi_desc kd', 'game_kpi.kpi_id = kd.kpi_id', 'left');
        $db->join('mt_report_source mrs', 'mrs.game_code = game_kpi.game_code and mrs.group_id = kd.group_id and mrs.data_source = game_kpi.source', 'left');
        $db->join("games", "games.GameCode = game_kpi.game_code");
        $db->where("mrs.kpi_type", "game_kpi");
        $db->where('game_kpi.report_date', $date);
        $db->where('game_kpi.kpi_id', $kpiId);
        if (strcmp($market, "vn") == 0) {
            $db->where('games.market', $market);
        } else {
            $db->where('games.market !=', 'vn');
        }
        $db->where_in('games.GameType2', array(2));
        $db->where_not_in('owner', $ownerNotIn);
        $db->where('games.status', 1);
        /*$db->where('games.SendMail', 1);*/
        $db->order_by('game_kpi.kpi_value', 'desc');
        $db->limit($limit);

        $query = $db->get();
        $result = $query->result_array();
        return $result;

    }

    private function setZeroForMissKPI($data, $lstKpi, $lstDate)
    {
        foreach ($data as $gameCode => $value) {
            $lstKpiDb = array();
            foreach ($value['report_date'] as $report_date => $data2) {
                $lstKpiDb = array_keys($data2['kpi_id']);
                //Compare between kpi that configured and kpi get from query
                // If not found, set zero for it
                if (!(count($lstKpi) == count($lstKpiDb))) {
                    $missingKpi = array_diff($lstKpi, $lstKpiDb);
                    if (count($missingKpi) != 0) {
                        foreach ($missingKpi as $key) {
                            $data[$gameCode]['report_date'][$report_date]['kpi_id'][$key] = "0";
                        }

                    }
                }
            }

        }
        return $data;
    }


    public function filter_mail($id)
    {
        $db = $this->load->database('ubstats', TRUE);
        $db->select("content_html");
        $db->from("log_mail");
        $db->where("id", $id);
        $query = $db->get();
        $result = $query->result_array();
        return $result;

    }

    public function insert_log($to, $content_html, $logDate, $type_mail, $done_flag)
    {
        $lstUser = explode(",", $to);

        if (in_array("quynhnt2@vng.com.vn", $lstUser)) {
            $status = 1;
        } else {
            $status = 0;
        }
        $data['to_user'] = $to;
        $data['done_flag'] = $done_flag;
        $data['created'] = date('Y-m-d H:i:s');
        $data['content_html'] = $content_html;
        $data['log_date'] = $logDate;
        $data['status'] = $status;
        $data['type_mail'] = $type_mail;
        $db = $this->load->database('ubstats', TRUE);
        try {
            return $db->insert("log_mail", $data);
        } catch (Exception $e) {
            return false;
        }

    }

    /*
     * Status: default 0 -> mail sent to operator: not check
     *                 1 -> send to account
     * result true : not send
     *        false: sent
     */

    public function check_send_mail($log_date, $type_mail)
    {

        $db = $this->load->database('ubstats', TRUE);
        $db->select("content_html");
        $db->from("log_mail");
        $db->where("log_date", $log_date);
        $db->where("status", 1);
        $db->where("type_mail", $type_mail);
        $query = $db->get();
        //var_dump($db->last_query());exit();
        $result = $query->result_array();
        if (empty($result)) {
            return true;
        }
        return false;
    }

    public function getMarketGame()
    {
        $db = $this->load->database('ubstats', TRUE);
        $db->select('DISTINCT market', FALSE);
        $db->from("games");
        $db->where("GameType2", 2);
        $db->where("status", 1);
        $query = $db->get();
        $result = $query->result_array();
        return $result;

    }

    public function getGameNameByMarket($market)
    {
        $db = $this->load->database('ubstats', TRUE);
        $db->select('GameName');
        $db->from("games");
        $db->where("GameType2", 2);
        $db->where("status", 1);
        if (strcmp($market, "") == 0) {
            $db->where("market", null);
        } else {
            $db->where("market", $market);
        }
        $query = $db->get();
        $result = $query->result_array();

        return $result;
    }

    public function getIssueGame($reportDate)
    {
        $db = $this->load->database('ubstats', true);
        $db->select("game_code,message");
        $db->from("qa_issued_games");
        $db->where("report_date", $reportDate);
        $query = $db->get();
        $result = $query->result_array();
        return $result;

    }
    public function getLstUserSendMail($groupId){
        $db = $this->load->database('ubstats', true);
        $db->select("username");
        $db->from("users");
        $db->join("groups", "groups.GroupId = users.GroupId");
        $db->where("groups.GroupId",$groupId);
        $db->where("groups.Active",1);
        $query = $db->get();
        $result = $query->result_array();
        $array = array();
        for ($i=0; $i< count($result);$i++){
            $array[$i] = $result[$i]['username'];
        }
        return $array;
    }


}