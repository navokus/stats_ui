<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		// $this->output->enable_profiler(TRUE);
		$this->load->model('user_model', 'user');
        $this->load->model('group_model', 'group_model');
        $this->load->model('game_model', 'game_model');

        $userInfo = $this->session->userdata('infoUser');

        if ($userInfo['GroupId'] != 1) {
            show_error('You do not permission!');
        }
	}

	public function index()
	{
		$this->load->library('form_validation');
        $this->load->helper('form');
        $aData['sMessage'] = $this->session->flashdata('message');
        $aData['sMessageType'] = $this->session->flashdata('message_type') ? $this->session->flashdata('message_type') : 'success';

        if ($_POST) {            
            $this->form_validation->set_rules('group_name', 'Group Name', 'required');
            $this->form_validation->set_rules('game_codes', 'Game', 'required');

            if ($this->form_validation->run() == TRUE)
            {                                
                // Add group
                if($this->group_model->addGroup()) {
                    $aData['sMessage'] = 'Created group successfully!';
                }
            }
        }
        
        
        $aData['aGroups'] = $this->group_model->getAll();
        $aData['aGames'] = $this->game_model->listGames(TRUE);
        
        $this->_template['content'] = $this->load->view('user/groups', $aData, TRUE);
        $this->load->view('master_page', $this->_template);
	}

    public function group_view($iGroupId)
    {   
    	$this->load->library('form_validation');
    	$this->load->helper('form');
    	$aData['aGroup'] = $this->group_model->getGroup($iGroupId);
    	if ($_POST) {
    		$this->form_validation->set_rules('group_name', 'Group Name', 'required');
    	
    		if ($this->form_validation->run() == TRUE)
    		{
    			//clone
    			if($this->group_model->cloneGroup($iGroupId)) {
    				$this->session->set_flashdata('message', "Update group <b>{$aGroup['GroupName']}</b> successfully!");
    				redirect('/User');
    			}
    		}
    	}
        
        $aData['aGroupGames'] = $this->group_model->getGroupGames($iGroupId);
        $aData['aGroups'] = $this->group_model->getAll();
        $aData['users'] = $this->user->listUserInGroup($iGroupId);
        $this->_template['content'] = $this->load->view('user/group_view', $aData, TRUE);
        $this->load->view('master_page', $this->_template);
    }

	public function group_edit($iGroupId)
    {
        $this->load->library('form_validation');
        $this->load->helper('form');
        $aData['aGroup'] = $aGroup = $this->group_model->getGroup($iGroupId);
        
        if ($_POST) {            
            $this->form_validation->set_rules('group_name', 'Group Name', 'required');

            if ($this->form_validation->run() == TRUE)
            {                                
                // Add group
                if($this->group_model->updateGroup($iGroupId)) {
                    $this->session->set_flashdata('message', "Update group <b>{$aGroup['GroupName']}</b> successfully!");
                    redirect('/User');
                }
            }
        }        
        
        $aData['aGameCodes'] = $this->group_model->getGroupGameCodes($iGroupId);
        $aData['aGames'] = $this->game_model->listGames(TRUE);
        
        $this->_template['content'] = $this->load->view('user/group_edit', $aData, TRUE);
        $this->load->view('master_page', $this->_template);
    }

    public function group_add()
    {
    	$this->load->library('form_validation');
    	$this->load->helper('form');
    	$aData['sMessage'] = $this->session->flashdata('message');
    	$aData['sMessageType'] = $this->session->flashdata('message_type') ? $this->session->flashdata('message_type') : 'success';
    	
    	if ($_POST) {
    		$this->form_validation->set_rules('group_name', 'Group Name', 'required');
    		$this->form_validation->set_rules('game_codes', 'Game', 'required');
    	
    		if ($this->form_validation->run() == TRUE)
    		{
    			// Add group
    			if($this->group_model->addGroup()) {
    				$aData['sMessage'] = 'Created group successfully!';
    			}
    		}
    	}
    	
    	
    	$aData['aGroups'] = $this->group_model->getAll();
    	$aData['aGames'] = $this->game_model->listGames(TRUE);
    	
    	$this->_template['content'] = $this->load->view('user/group_add', $aData, TRUE);
    	$this->load->view('master_page', $this->_template);
    }
    
    public function group_delete($iGroupId)
    {
        $aGroup = $this->group_model->getGroup($iGroupId);
        
        if ($this->group_model->isHasUsers($iGroupId)) {
            $this->session->set_flashdata('message', "Can not delete group: <b>{$aGroup['GroupName']}</b>, please delete all users belong to it first.");
            $this->session->set_flashdata('message_type', 'fail');
            return redirect('/User');
        }
        
        if($this->group_model->deleteGroup($iGroupId)) {
            $this->session->set_flashdata('message', "Delete group <b>{$aGroup['GroupName']}</b> successfully!");            
        }
        
        redirect('/User');
    }

	public function users()
	{
		
                
        $return['list'] = $this->user->getAll();

		$this->_template['content'] = $this->load->view('user/users', $return, TRUE);
		$this->load->view('master_page', $this->_template);
	}

	public function user_add()
	{

        $this->load->library('form_validation');

        if ($_POST) {
            
            $this->form_validation->set_rules('username', 'username', 'required|is_unique[users.username]');
            $this->form_validation->set_rules('GroupId', 'GroupId', 'required');
            $this->form_validation->set_rules('Active', 'Active', 'required');            

            if ($this->form_validation->run() == TRUE)
            {                                
                
                $this->user->addUser($_POST);


                redirect('User/users');
            }            
        }
		
        $return['aGroup'] = $this->user->listGroup();
        $this->_template['content'] = $this->load->view('user/user_add', $return, TRUE);
        $this->load->view('master_page', $this->_template);
	}

	public function user_edit($username)
	{
		
        $this->load->library('form_validation');

        $aUser = $this->user->getUser($username);
        if (!$aUser) {
            show_error('User not exist');
        }


        if ($_POST) {
            
            $this->form_validation->set_rules('GroupId', 'GroupId', 'required');
            $this->form_validation->set_rules('Active', 'Active', 'required');            

            if ($this->form_validation->run() == TRUE)
            {                                
                
                $this->user->updateUserById($_POST, $username);

                redirect('User/users');
            }            
        } else {

            
            $_POST = $aUser;

        }
        
        $return['aGroup'] = $this->user->listGroup();
        $this->_template['content'] = $this->load->view('user/user_edit', $return, TRUE);
        $this->load->view('master_page', $this->_template);
	}

    public function user_del($username)
    {
        
        $aUser = $this->user->getUser($username);
        if (!$aUser) {
            show_error('User not exist');
        }

        $this->user->delUser($username);
        redirect('User/users');

    }

}

/* End of file User.php */
/* Location: ./application/controllers/User.php */