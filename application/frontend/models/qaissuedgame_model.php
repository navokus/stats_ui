<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by IntelliJ IDEA.
 * User: canhtq
 * Date: 12/06/2017
 * Time: 17:05
 */
class Qaissuedgame_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

    }
    public function addIssues($data)
    {
        $this->db->insert_batch("qa_issued_games", $data);
    }

    public function addNewIssues($data)
    {
        $this->db->insert_batch("qa_issued_games", $data);
    }

    public function clean($date,$last)
    {
        $this -> db -> where('report_date', $date);
        $this -> db -> where('update_time <', $last);
        $this -> db -> delete('qa_issued_games');
    }
    public function clean_games($date,$games)
    {
        $this -> db -> where('report_date', $date);
        $this -> db -> where_in('game_code', $games);
        $this -> db -> delete('qa_issued_games');
    }

    public function getIssues($date)
    {
        $this->db->select('issues');
        $this->db->from("qa_issued_data");
        $this->db->where('report_date', $date);
        $this->db->order_by("id", "desc");
        $query = $this->db->get();
        $arr = $query->row_array();
        return $arr;
    }

    public function getGames($date)
    {
        $this->db->select('game_code');
        $this->db->from("qa_issued_games");
        $this->db->where('report_date', $date);
        $query = $this->db->get();
        $arr = $query->result_array();
        return $arr;
    }

    public function getIssuedGames($date)
    {
        $this->db->select('game_code');
        $this->db->from("qa_issued_games");
        $this->db->where('report_date', $date);
        $query = $this->db->get();
        $arr = $query->result_array();
        return $arr;
    }

    public function solved($game,$qaUser,$reportDate,$message){
        $data = array();
        $data['game_code'] = $game;
        $data['qa_user'] = $qaUser;
        $data['report_date'] = $reportDate;
        $data['message'] = $message;
        $data['solved_date'] = date('Y-m-d H:i:s');

        $this->db->insert("qa_solved_game", $data);

    }
    public function solved_alls($batch){
        $this->db->insert_batch("qa_solved_game", $batch);
    }
    public function getSolvedGames($date)
    {
        $this->db->select('game_code');
        $this->db->from("qa_solved_game");
        $this->db->where('report_date', $date);
        $query = $this->db->get();
        $arr = $query->result_array();
        return $arr;
    }
}