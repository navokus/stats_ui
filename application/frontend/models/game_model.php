<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game_model extends MY_Model {
    public function __construct()
    {
        parent::__construct();
        $this->load->library ( 'util' );
    }

    public function listGames($all = null)
    {
        $dept_head = array(
            "chrisliu",
            "hungnp",
            "nhanpnc",
            "trongnvd",
            "phuongbm",
            "hungnt",
            "minhnh",
            "soant",
            "trungnk",
            "tuonglv",
            "canhtq"
        );
        $aUser = $this->session->userdata('infoUser');
        //if(in_array(strtolower($aUser["username"]), $dept_head)){
        //    $all = true;
        //}

        /** vinhdp - 2016-06-26: game type selection (ALL, DESKTOP, MOBILE) */
        $gameType = $this->session->userdata('game_type');
        $gameType2 = 0;
        if($gameType == "mobile"){
        	$gameType2 = 2;
        }else if($gameType == "pc"){
        	$gameType2 = 1;
        }else if($gameType == "web"){
        	$gameType2 = 3;
        }
        /** end */
        
        $this->db->select('*');
        $this->db->from("games");
        
        if(!$this->util->is_admin($aUser["username"])){
        	$this->db->where("Status != ", 0);
        }
        
        if($gameType2 != 0){
        	$this->db->where("GameType2", $gameType2);
        }
        
        if ($aUser['GroupId'] != 1 && $all != TRUE) {
            $this->db->where('GameCode IN (SELECT GameCode FROM game_groups WHERE GroupId = ' . $aUser['GroupId'] . ')');
        }

        $this->db->order_by('GameName', 'asc');

        $query = $this->db->get();


        //$t = $this->db->last_query();
        //echo $t . "<br>";

        $result = $query->result_array();

        $this->setDefaultGame($result);
        return $result;
    }
    
    public function listGamesByLoginUser()
    {
        $all = false;
    	$aUser = $this->session->userdata('infoUser');
    	$this->db->select('*');
    	$this->db->from("games");
    	$this->db->where("Status", 1);
    	if ($aUser['GroupId'] != 1 && $all != TRUE) {
    		$this->db->where('GameCode IN (SELECT GameCode FROM game_groups WHERE GroupId = ' . $aUser['GroupId'] . ')');
    	}
    
    	$this->db->order_by('GameName', 'asc');
    
    	$query = $this->db->get();
    	$result = $query->result_array();
    
    	$this->setDefaultGame($result);
    	return $result;
    }


    public function findGameInfo($gameCode){
    	
    	$this->db->select('*');
    	$this->db->from("games");
    	$this->db->where("Status", 1);
    	$this->db->where("GameCode", $gameCode);
    	
    	$query = $this->db->get();
    	$result = $query->result_array();
    	return $result[0];
    }
    
    private function setDefaultGame($data){
        $defaultGame=$this->session->userdata('default_game');
        $check=0;
        $count=count($data);
        for($i=0;$i<$count;$i++){
            if($data[$i]['GameCode']==$defaultGame){
                $check=1;
                break;
            }
        }
        if($check==0 && $count!=0){
            $this->session->set_userdata('default_game', $data[0]['GameCode']);
        }else{
            //$this->session->set_userdata('default_game', null);
        }
    }

    public function getGame($gameCode)
    {
        $this->db->select('games.*');
        $this->db->from("games");
        $this->db->where('GameCode', $gameCode);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function addGame($data)
    {
        try {
            $data['data_source'] = '';
            $data['CreatedDate'] = date('Y-m-d H:i:s');
            $this->db->insert("games", $data);
            return $data;
        } catch (Exception $e) {
            return false;
        }

    }

    public function editGame($data, $gameCode)
    {
        try {
            $this->db->update("games", $data, array('GameCode' => $gameCode));
            return true;
        } catch (Exception $e) {
            return false;
        }

    }

    public function delGame($gameCode)
    {
        try {
            $this->db->delete("games", array('GameCode' => $gameCode));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }


    public function get_report_list_from_gamecode($game_code){
        $this->db->select("gar.report_id,rp.report_name,rp.url,rp.html_class_1 as rp_class_1,
             rp.html_class_2 as rp_class_2,
             rp.group_id,gr.group_name,gr.html_class_1 as gr_class_1,
             gr.html_class_2 as gr_class_2");
        $this->db->from('mt_game_report gar');
        $this->db->join('mt_report rp', 'gar.report_id = rp.report_id', 'left');
        $this->db->join('mt_group_report gr', 'rp.group_id = gr.group_id', 'left');
        $this->db->where("gar.game_code",$game_code);
        $this->db->where("gar.active",1);
        $this->db->where("rp.active",1);
        $this->db->where("gr.active",1);
        $this->db->order_by('gr.position', 'asc');
        $this->db->order_by('rp.position', 'asc');

        $query = $this->db->get();
        $result = $query->result_array();

        if($result == null || count($result) == 0){
            $result = $this->get_full_report();
        }
        return $result;
    }


    public function get_full_report(){
        $this->db->select("rp.report_id,rp.report_name,rp.url,rp.html_class_1 as rp_class_1,rp.html_class_2 as rp_class_2, rp.group_id,gr.group_name,gr.html_class_1 as gr_class_1,gr.html_class_2 as gr_class_2");
        $this->db->from('mt_report rp');
        $this->db->join('mt_group_report gr', 'rp.group_id = gr.group_id', 'left');
        $this->db->where("rp.active",1);
        $this->db->where("gr.active",1);

        $this->db->order_by('gr.position', 'asc');
        $this->db->order_by('rp.position', 'asc');

        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    public function get_menu_report(){
        $this->db->select("group_id,group_name,html_class_1,html_class_2");
        $this->db->from('mt_group_report');
        $this->db->order_by('position', 'asc');
        $this->db->order_by('create_date', 'desc');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function add_report($data)
    {
        $data_insert = array();
        $data_insert['report_id'] = $data['reportId'];
        $data_insert['report_name'] = $data['reportName'];
        $data_insert['group_id'] = $data['reportGroup'];
        $data_insert['url'] = $data['url'];
        $data_insert['create_date'] = date('Y-m-d H:i:s');
        $data_insert['position'] = $data['position'];
        $data_insert['active'] = 1;
        $data_insert['html_class_1'] = $data['class_1'];
        $data_insert['html_class_2'] = $data['class_2'];

        if($data_insert['report_id'] == "" || $data_insert['report_name'] == "" ||
            $data_insert['group_id'] == "" || $data_insert['url'] == ""){
            var_dump($data_insert);exit();
            return;
        }

        try {
            $this->db->insert("mt_report", $data_insert);
        } catch (Exception $e) {

        }
    }

    public function add_report_group($data)
    {
        $data_insert = array();
        $data_insert['group_id'] = $data['groupId'];
        $data_insert['group_name'] = $data['groupName'];
        $data_insert['html_class_1'] = $data['class_1'];
        $data_insert['html_class_2'] = $data['class_1'];
        $data_insert['create_date'] = date('Y-m-d H:i:s');
        $data_insert['position'] = $data['position'];
        $data_insert['active'] = 1;
        if($data_insert['group_id'] == "" || $data_insert['group_name'] == ""){
            return;
        }
        try {
            $this->db->insert("mt_group_report", $data_insert);
        } catch (Exception $e) {

        }
    }

    public function update_report_menu($game_code,$data){
        try {
            $this->db->delete("mt_game_report", array('game_code' => $game_code));
        } catch (Exception $e) {

        }

        foreach($data as $key => $value){
            try {
                $data_insert = array();
                $data_insert['create_date'] = date('Y-m-d H:i:s');
                $data_insert['active'] = 1;
                $data_insert['game_code'] = $game_code;
                $data_insert['report_id'] = $key;

                $this->db->insert("mt_game_report", $data_insert);
            } catch (Exception $e) {

            }
        }
    }

    public function getListByOwner($owner)
    {
    	
    	$this->db->select('*');
    	$this->db->from("games");
    	$this->db->where("Status", 1);
    	if($owner!=null){
    		$this->db->where("owner", $owner);
    	}
    	$this->db->order_by('GameName', 'asc');
    	$query = $this->db->get();
    	$result = $query->result_array();
    	return $result;
    }
    
    public function getOwners()
    {
    	$this->db->distinct();
    	$this->db->select('owner, COUNT(GameCode) as total');
    	$this->db->from("games");
    	$this->db->where("owner is not null and owner !=''");
    	$this->db->group_by('owner');
    	$this->db->order_by('total', 'desc');
    	$query = $this->db->get();
    	$result = $query->result_array();
    	return $result;
    }
    
    public function listGamesByPlatform($gameType2)
    {
    	
    	
    	$this->db->select('*');
    	$this->db->from("games");
    	//$this->db->where("Status", 1);
   		$this->db->where("GameType2", $gameType2);
    	$this->db->order_by('GameName', 'asc');
    
    	$query = $this->db->get();
    	$result = $query->result_array();
    
    	
    	return $result;
    }

    public function getGstGames()
    {
        $this->db->select('game_code');
        $this->db->from("tmp_gst_games");
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    public function getFullGameInfo($gameCode)
    {
        $this->db->select('games.*,mt_game_type.name as platform');
        $this->db->from("games");
        $this->db->join('mt_game_type', 'mt_game_type.id = games.GameType2');
        $this->db->where('games.GameCode', $gameCode);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getAtiveAlls(){

        $this->db->select('*');
        $this->db->from("games");
        $this->db->where_in('Status', array(1));
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function getQaAlls(){

        $this->db->select('*');
        $this->db->from("games");
        $this->db->where_in('Status', array(1));
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
}

/* End of file game_model.php */
/* Location: ./application/models/game_model.php */