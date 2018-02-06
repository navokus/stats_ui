<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Group_model extends CI_Model {

    private $_sTable = 'groups';

    public function __construct() {
        parent::__construct();
    }
    
    public function deleteGroup($iGroupId){

        if ($iGroupId == 1) return false;

        $this->db->delete('game_groups', array('GroupId' => $iGroupId));
        $this->db->delete($this->_sTable, array('GroupId' => $iGroupId));
        
        return true;
    }
    
    /*
     * Check the group has user or not
     */
    public function isHasUsers($iGroupId) {
        return $this->db->from('users')
                        ->where('GroupId', $iGroupId)
                        ->count_all_results();
    }
    
    /*
     * Get game info of group (GameCode, GameName)
     * Return: array of game
     */
    public function getGroupGames($iGroupId) {
        $aGameCodes = $this->getGroupGameCodes($iGroupId);
        $aGameCodes = array_merge($aGameCodes, array('0'));
        
        $aResult = $this->db->select('*')
                            ->from('games')
                            ->where_in('GameCode', $aGameCodes)
                            ->get()
                            ->result_array();
        
        return $aResult;
    }
    
    /*
     * Get game code of group (GameCode, GameName)
     * Return: array of game code
     */
    public function getGroupGameCodes($iGroupId) {
        $aDatas = $this->db->select('*')
                        ->from('game_groups')
                        ->where('GroupId', $iGroupId)
                        ->get()
                        ->result_array();
        
        $aResult = array();
        if (count($aDatas)){
            foreach ($aDatas as $aData) {
                $aResult[] = $aData['GameCode'];
            }
        }
        
        return $aResult;
    }
    
    public function getGroup($iGroupId) {
        $aResults = $this->db->select('*')
                        ->from("groups")
                        ->where('GroupId', $iGroupId)
                        ->get()
                        ->row_array()
                        ;
        
        return $aResults;
    }
    
    public function updateGroup($iGroupId) {
        $aData = array(
            'GroupName' => $this->input->post('group_name'),
            'Active' => $this->input->post('is_active'),
        );
        
        $this->db->trans_start(); // Begin transaction
        
        $this->db->update($this->_sTable, $aData, 'GroupId = ' . (int) $iGroupId);
        
        // Set permission by game code
        $this->db->delete('game_groups', array('GroupId' => $iGroupId));
        $aGameCodes = $this->input->post('game_codes');
        if (is_array($aGameCodes) && count($aGameCodes)) {
            $aData = array();
            foreach($aGameCodes as $iGameCode){
                $aTmp = array(
                    'GroupId' => $iGroupId,
                    'GameCode' => $iGameCode
                );

                $aData[] = $aTmp;
            }

            $this->db->insert_batch('game_groups', $aData);
        }
        
        $this->db->trans_complete(); // End transaction
        
        return true;
    }
    
    public function addGroup(){
        $aData = array(
            'GroupName' => $this->input->post('group_name'),
            'Active' => $this->input->post('is_active'),
        );                
        
        $this->db->trans_start(); // Begin transaction
        
        if ($this->db->insert($this->_sTable, $aData)) {
            $iGroupId = $this->db->insert_id();
            
            // Set permission by game code
            $aGameCodes = $this->input->post('game_codes');
            if (is_array($aGameCodes) && count($aGameCodes)) {
                $aData = array();
                foreach($aGameCodes as $iGameCode){
                    $aTmp = array(
                        'GroupId' => $iGroupId,
                        'GameCode' => $iGameCode
                    );
                    
                    $aData[] = $aTmp;
                }
                
                $this->db->insert_batch('game_groups', $aData);
            }
        }
        
        $this->db->trans_complete(); // End transaction
        
        return true;
    }
    
    public function getAll(){
        $aResults = $this->db->select('*')
                        ->from("groups")
                        ->order_by('GroupId', 'asc')
                        ->get()
                        ->result_array()
                        ;
        
        return $aResults;
    }
    
    public function cloneGroup($iGroupId) {
    	$aData = array(
    			'GroupName' => $this->input->post('group_name'),
    			'Active' => '1',
    	);
    	$oldGroup = $this->getGroup($iGroupId);
    	if($oldGroup['GroupName'] == $this->input->post('group_name')){
    		return false;
    	}
    	$this->db->trans_start(); // Begin transaction
    	if ($this->db->insert($this->_sTable, $aData)) {
    		$newGroupId = $this->db->insert_id();
    	}
    	$select = $this->db->select('GameCode')->from("game_groups")->where('GroupId', $iGroupId)->get()->result_array();
    	$clone = array();
    	foreach($select as $code=>$vcode){
    		$gr = array(
    				'GroupId' => $newGroupId,
    				'GameCode' => $vcode['GameCode']
    		);
    		
    		$clone[] = $gr;
    	}
    	if(count($clone)>0)
    	{
    		$insert = $this->db->insert_batch('game_groups', $clone);
    	}
    	
    
    	$this->db->trans_complete(); // End transaction
    
    	return true;
    }
    
}