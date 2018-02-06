<?php

/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 04/05/2016
 * Time: 12:27
 */
class Common_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();

    }

    public function is_mobile_game($game_code){
        $this->db->select("GameType2");
        $this->db->where("GameCode",$game_code);
        $this->db->from("games");
        $query = $this->db->get();
        $result = $query->result_array();
        if(isset($result[0]['GameType2']) && $result[0]['GameType2'] == "2"){
            return true;
        }
        return false;
    }

}
