<?php
/**
 * @author vinhdp
 * @date Dec 06, 2016
 */
class RetentionGraph extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
		//$this->output->enable_profiler(TRUE);
        $this->load->model('game_model', 'game');
        $this->load->model('game_retention_model', 'retention');
        $this->load->library('form_validation');
        $this->load->library('util');
        $this->load->helper('cookie');
    }

    public function index()
    {
        $this->retention();
    }
    
    public function retention(){
    	$gameCode = $this->session->userdata('current_game');
    	$isSessionCache = true;
    	 
    	if ($this->input->post('options')) {
    		$_SESSION[$this->class_name]['post'] = $_POST;
    	} else {
    		$isSessionCache = false;
    	}
        
        $viewData['body']['aGames'] = $this->game->listGames();
        $viewData['body']['optionsWeek'] = $this->util->listOptionsWeek();
        $viewData['body']['optionsMonth'] = $this->util->listOptionsMonth();
        $viewData['body']['gameCode'] = $gameCode;
    	 
        $viewData = $this->getRetentionData ($reportId, $viewData);
        
    	$viewData["game"] = $this->game->findGameInfo($gameCode);
    	$viewData['body']['cbb'] = "A";
        
        $this->_template['content'] = $viewData ['body'] ['selection'] = $this->load->view('body_parts/selection/game_kpi_selection', $viewData, TRUE);
        $this->_template['content'] .= $this->load->view("retention_graph/index", $viewData, TRUE);
        $this->_template['body']['title'] = "Retention";
        $this->_template['body']['aGames'] = $this->game->listGames();
        $this->load->view('master_page', $this->_template);
    }
    
    public function getRetentionData($reportId, $viewData, $isSessionCache = false){
    	
    	$this->util->parseUserInput($this->class_name, $viewData, $chartData, $tableData, $isSessionCache);
    	 
    	$tableData = array();
    	$gameCode = $viewData ['body'] ['gameCode'];
    	$timing = $viewData['body']['options'];
    	$fromDate = $viewData['body']['fromDate'];
    	$toDate = $viewData['body']['toDate'];
    	
    	$dbData = $this->retention->getDetailData($gameCode,  $timing, $fromDate, $toDate);
    	$minDate = $dbData["min_date"]; // using for prevent zero retention on date before release date
    	$days = $dbData["log_date"];
    	$newDays = array();
    	
    	foreach ($days as $index => $day){
    		
    		/* if($day < $minDate){
    			unset($days[$index]);
    		} */
    		if($day >= $minDate){
    			$newDays[] = $day;
    		}
    	}
    	
    	$viewData["timing"] = $timing;
    	$viewData["days"] = $newDays;
    	$viewData["fromDate"] = $fromDate;
    	$viewData["toDate"] = $toDate;
    	
    	$viewData["data"] = $dbData;
    	return $viewData;
    }
}