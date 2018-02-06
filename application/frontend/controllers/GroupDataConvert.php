<?php

/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 14/03/2016
 * Time: 23:29
 */

class GroupDataConvert extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
		//$this->output->enable_profiler(TRUE);
        $this->load->model('game_model', 'game');
        $this->load->model('groupkpi_model', 'group');
        $this->load->model('convertjson_model', 'convert');
        $this->load->library('form_validation');
        $this->load->library('util');
        $this->load->helper('cookie');
    }

    public function index()
    {
        $this->top();
    }
    
    public function convertdata($groupId, $date){
    	
    	$this->output->set_content_type('application/json');
    	$gameCode = $this->session->userdata('current_game');
    	$result = $this->convert->convertdata($groupId, $gameCode, $date);
    	echo "Converted " . $result["total"] . " into " . $result["new"] . " rows, " . $result["success"] . " affected_rows!";
    }
}