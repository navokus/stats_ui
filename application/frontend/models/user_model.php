<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		
	}

	public function listUsers($where, $order, $page)
    {
        if (!$page) {
            $page = 1;
        }
        
        $page = ($page - 1) * $this->config->item('per_page');
        
        $this->db->select('users.username, users.Active, users.Created, groups.GroupName,users.GroupId');
        $this->db->from("users");
        $this->db->join('groups', 'users.GroupId = groups.GroupId AND groups.Active = 1', 'left');

        if ($where) {
        	$this->db->where($where);
        }
        
        if ($order) $this->db->order_by($order);
        
        $this->db->limit($this->config->item('per_page'), $page);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function totalListUsers($where) {
        $this->db->select('count(*) as total');
        $this->db->from("users");
        $this->db->join('groups', 'users.GroupId = groups.GroupId AND groups.Active = 1', 'left');
        
        if ($where) $this->db->where($where);
        
        $query = $this->db->get();
        $arr = $query->row_array();
        return $arr['total'];
    }

    public function updateUserById($data, $username) {
        $this->db->where('username', $username);
        $this->db->update("users", $data);
    }

    public function addUser(&$data)
    {
        $data['Created'] = date('Y-m-d H:i:s');
        $this->db->insert("users", $data);
    }

    public function getUser($username)
    {
        //$this->db->select('*');
        $this->db->select('LOWER(username) as username,GroupId,Created,Active,send_mail');
        $this->db->from("users");
        $this->db->where('username', $username);
        $query = $this->db->get();

        $arr = $query->row_array();
        return $arr;
    }


    public function delUser($username)
    {
    	$this->db->delete("users", array('username' => $username));
    }

    public function listGroup()
    {
        
        $this->db->select('*');
        $this->db->from('groups');
        $this->db->order_by('GroupName', 'asc');

        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    public function checkUserLogin($username)
    {
        $this->db->select('*');
        $this->db->from("users");
        $this->db->where('username', $username);
        $this->db->where('Active', 1);
        $query = $this->db->get();

        $arr = $query->row_array();
        return $arr;
    }

    public function checkSmtUser($username)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username', $username);
        $this->db->where('Active', 1);
        $query = $this->db->get();

        $arr = $query->row_array();
        return $arr;
    }

    public function getGameList($username)
    {
    	$db = $this->load->database('ubstats', TRUE);
    	$user = $this->getUser($username);
    	if($user['GroupId']==1){
    		return $this->getAdminGameList();
    	}else{
	    	$result = array();
	    	$sql="GameCode as game_code";
	    	$db->select($sql, false);
	    	$db->from("game_groups");
	    	//$db->join("groups","groups.GroupId = game_groups.GroupId");
	    	$db->join("users","users.GroupId = game_groups.GroupId");
            $db->join("games", "games.GameCode = game_groups.GameCode");
            $db->distinct();
	    	$query = $db->get();
	    	$result = $query->result_array();
	    	return $result;
    	}
    }

    public function getGameListByOwner($username,$owner)
    {
        $db = $this->load->database('ubstats', TRUE);
        $user = $this->getUser($username);
        if($user['GroupId']==1){
            return $this->getAdminGameList();
        }else {
            $result = array();
            $sql = "games.*";
            $db->select($sql, false);
            $db->from("game_groups");
            //$db->join("groups","groups.GroupId = game_groups.GroupId");
            $db->join("users", "users.GroupId = game_groups.GroupId");
            $db->join("games", "games.GameCode = game_groups.GameCode");
            //$db->where('users.username', $username);
            $db->where('users.GroupId', $user['GroupId']);
            if ($owner != null) {
                $db->where('games.onwer', $owner);
            }
            $db->distinct();
            $query = $db->get();
            $result = $query->result_array();
            return $result;
        }
    }

    public function getOwners($username)
    {
        $result = array();
        $db = $this->load->database('ubstats', TRUE);
        $user = $this->getUser($username);
        if($user['GroupId']==1){
            $result= $this->getAllOnwers();
        }else {

            $sql = "games.owner";
            $db->select($sql, false);
            $db->from("game_groups");
            $db->join("users", "users.GroupId = game_groups.GroupId");
            $db->join("games", "games.GameCode = game_groups.GameCode");
            $db->where('users.GroupId', $user['GroupId']);
            $db->distinct();
            $query = $db->get();
            $result = $query->result_array();

        }
        return $result;
    }

    public function getAdminGameList()
    {
    	$db = $this->load->database('ubstats', TRUE);
    	$result = array();
    	$sql="GameCode as game_code";
    	$db->select($sql, false);
    	$db->from("games");
    	$db->where('Status', 1);
    	$query = $db->get();
    	$result = $query->result_array();
    	return $result;
    }

    public function getAllOnwers()
    {
        $db = $this->load->database('ubstats', TRUE);
        $result = array();
        $sql="owner";
        $db->select($sql, false);
        $db->from("games");
        $db->where('Status', 1);
        $db->distinct();
        $query = $db->get();
        $result = $query->result_array();
        return $result;
    }
    public function listUserInGroup($groupId)
    {
    	$db = $this->load->database('ubstats', TRUE);
    	$this->db->select('*');
    	$this->db->from('users');
    	$this->db->where('GroupId', $groupId);
    	$query = $this->db->get();
    	$result = $query->result_array();
    
    	return $result;
    }
    public function getAll()
    {
    	$db = $this->load->database('ubstats', TRUE);
    	$this->db->select('users.username, users.Active, users.Created, groups.GroupName,users.GroupId');
    	$this->db->from("users");
    	$this->db->join('groups', 'users.GroupId = groups.GroupId AND groups.Active = 1', 'left');
    	$query = $this->db->get();
    	$result = $query->result_array();
    
    	return $result;
    }



}

/* End of file User_model.php */
/* Location: ./application/models/User_model.php */