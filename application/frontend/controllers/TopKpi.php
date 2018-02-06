<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TopKpi extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        //$this->output->enable_profiler(TRUE); // debug
        $this->load->model('kpi_model', 'kpi');

    }
	public function test(){
		var_dump($this->game->getListByOwner("pg2"));
		die();
	}
    public function revenue()
    {
    	if ($this->input->post('options')) {
    		$_SESSION[$this->class_name]['post'] = $_POST;
    	}
    	//var_dump($this->game->listGames());
    	$viewData['body']['aGames'] = $this->game->listGames();
    	//$viewData['body']['optionsWeek'] = $this->util->listOptionsWeek();
    	//$viewData['body']['optionsMonth'] = $this->util->listOptionsMonth();
    	$gameCode = $this->session->userdata('current_game');
    	$viewData['body']['gameCode'] = $gameCode;
    	$viewData['body']['breadcrumb']="All Games";
    	$viewData ['body'] ['title']="Top Games Report";
    	
    	$date = $_SESSION['kpi']['date'];
    	if($this->input->post ('kpidatepicker') != ""){
    		$viewData ['body'] ['day'] ['kpidatepicker'] = $this->input->post ('kpidatepicker');
    	}else if($date != ""){
    		$viewData ['body'] ['day'] ['kpidatepicker'] = $date;
    	}else{
    		$viewData ['body'] ['day'] ['kpidatepicker'] = date ( 'd/m/Y', strtotime( '-1 days' ) );
    	}
    	$_SESSION['kpi']['date'] = $viewData ['body'] ['day'] ['kpidatepicker'];
    	$date = $_SESSION['kpi']['date'];
    	//var_dump($log_date);
    	$viewData ['body'] ['selection'] = $this->load->view ('body_parts/selection/only_date', $viewData, TRUE );
    	//load master view
    	$this->_template['content'] .= $this->load->view("tops/revenue", $viewData, TRUE);
    	$this->load->view('master_page', $this->_template);
    }
	private function getGameCodesByUserLogin(){
		$games = $this->game->listGamesByLoginUser();
		return $this->getGameCodes($games);
	}
	private function getGameCodes($fullGames){
		$gameCodes = array();
			
		foreach ( $fullGames as $key => $game ) {
			array_push($gameCodes,$game['GameCode']);
		}
		return $gameCodes;
	}
	
    public function renderMobileTop()
    {
    	$kpi=16031;
    	$sdate = $_SESSION['kpi']['date'];
    	$games  = $this->getGameCodesByUserLogin();
    	$date = $this->util->user_date_to_db_date($sdate);
    	$viewData['body']['data'] = $this->kpi->getMobileGameKpi($games,$kpi,$date,10);
    	$viewData['body']['kpi'] = $kpi;
    	$data = $viewData['body']['data'];
    	$total=0;
    	foreach ( $data as $key => $value ) {
    		$total+=$value['kpi_value'];
    	}
    	$viewData['body']['total'] = $total;
    	$viewData['body']['type'] = "mobile";
    	$viewData['body']['type_name'] = "Mobile Games";
    	$viewData['body']['month'] = $this->util->formatDate("Y-m-d","M",$date) .", " . $this->util->formatDate("Y-m-d","Y",$date);
    	$viewData['body']['to_date'] = $this->util->formatDate("Y-m-d","d M,Y",$date);
    	$viewData['body']['from_date'] = $this->util->formatDate("Y-m-d","1 M,Y",$date);
    	$this->_template['content'] .= $this->load->view("tops/renderRevenueTop", $viewData, TRUE);
    	$this->load->view('render_html', $this->_template);
    }
    
    public function renderPcTop()
    {
    	$kpi=16031;
    	$sdate = $_SESSION['kpi']['date'];
    	$date = $this->util->user_date_to_db_date($sdate);
    	$games  = $this->getGameCodesByUserLogin();
    	$viewData['body']['data'] = $this->kpi->getPcGameKpi($games,$kpi,$date,10);
    	$data = $viewData['body']['data'];
    	$total=0;
    	foreach ( $data as $key => $value ) {
    		$total+=$value['kpi_value'];
    	}
    	$viewData['body']['total'] = $total;
    	$viewData['body']['kpi'] = $kpi;
    	$viewData['body']['type'] = "pc";
    	$viewData['body']['type_name'] = "PC Games";
    	$viewData['body']['month'] = $this->util->formatDate("Y-m-d","M",$date) .", " . $this->util->formatDate("Y-m-d","Y",$date);
    	$viewData['body']['to_date'] = $this->util->formatDate("Y-m-d","d M,Y",$date);
    	$viewData['body']['from_date'] = $this->util->formatDate("Y-m-d","1 M,Y",$date);
    	$this->_template['content'] .= $this->load->view("tops/renderRevenueTop", $viewData, TRUE);
    	$this->load->view('render_html', $this->_template);
    }
   
    public function renderAllTop()
    {
    	$kpi=16031;
    	$sdate = $_SESSION['kpi']['date'];
    	$date = $this->util->user_date_to_db_date($sdate);
    	$games  = $this->getGameCodesByUserLogin();
    	$viewData['body']['data'] = $this->kpi->getGameKpi($games,$kpi,$date,30);
    	$data = $viewData['body']['data'];
    	$total=0;
    	foreach ( $data as $key => $value ) {
    		$total+=$value['kpi_value'];
    	}
    	$viewData['body']['total'] = $total;
    	$viewData['body']['kpi'] = $kpi;
    	$viewData['body']['type'] = "all";
    	$viewData['body']['type_name'] = "All Games";
    	$viewData['body']['month'] = $this->util->formatDate("Y-m-d","M",$date) .", " . $this->util->formatDate("Y-m-d","Y",$date);
    	$viewData['body']['to_date'] = $this->util->formatDate("Y-m-d","d M,Y",$date);
    	$viewData['body']['from_date'] = $this->util->formatDate("Y-m-d","1 M,Y",$date);
    	$this->_template['content'] .= $this->load->view("tops/renderRevenueTop", $viewData, TRUE);
    	$this->load->view('render_html', $this->_template);
    }
    
    public function renderTop($selection){
    	$kpi=16031;
    	$sdate = $_SESSION['kpi']['date'];
    	$date = $this->util->getLastDayOfMonth($sdate,true);
    	$games  = $this->game->getListByOwner("pg2");
    	$viewData['body']['data'] = $this->kpi->getGameKpi($games,$kpi,$date,100);
    	$data = $viewData['body']['data'];
    	$total=0;
    	foreach ( $data as $key => $value ) {
    		$total+=$value['kpi_value'];
    	}
    	$viewData['body']['total'] = $total;
    	$viewData['body']['kpi'] = $kpi;
    	$viewData['body']['type'] = "all";
    	$viewData['body']['type_name'] = "All Games";
    	$viewData['body']['month'] = $this->util->formatDate("Y-m-d","M",$date) .", " . $this->util->formatDate("Y-m-d","Y",$date);
    	$viewData['body']['to_date'] = $this->util->formatDate("Y-m-d","d M,Y",$date);
    	$viewData['body']['from_date'] = $this->util->formatDate("Y-m-d","1 M,Y",$date);
    	$this->_template['content'] .= $this->load->view("tops/renderRevenueTop", $viewData, TRUE);
    	$this->load->view('render_html', $this->_template);
    }
    public function trackingRevenueTop($type,$date){
    	switch ($type){
    		case "pc":
    			break;
    	}
    }
}

