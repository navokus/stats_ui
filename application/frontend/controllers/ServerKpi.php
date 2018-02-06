<?php

/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 14/03/2016
 * Time: 23:29
 */

class ServerKpi extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
		//$this->output->enable_profiler(TRUE);
        $this->load->model('game_model', 'game');
        $this->load->model('serverkpi_model', 'server');
        $this->load->library('form_validation');
        $this->load->library('util');
    }

    public function index()
    {
        $this->top();
    }

    public function top()
    {
        $game_code = $this->session->userdata('current_game');
        $current_user = $this->session->userdata('user');

        $selectData ['body'] ['aGames'] = $this->game->listGames();
        $selectData ['body'] ['gameCode'] = $game_code;
        $date = $_SESSION['server']['date'];
        if ($this->input->post('kpidatepicker') != "") {
            $date = $this->input->post('kpidatepicker');
        } else if ($date == "") {
            $date = date('d/m/Y', strtotime('-1 days'));
        }
        $selectData ['body'] ['day'] ['kpidatepicker'] = $date;
        $_SESSION['server']['date'] = $date;
		
        $viewData = $selectData;
        $viewData = $this->getViewData ( $viewData );
        $viewData["reportDate"] = $this->util->formatDate("d/m/Y", "d-M-Y", $date);
        $viewData["game"] = $this->game->findGameInfo($game_code);
        
        $this->_template['content'] = $viewData ['body'] ['selection'] = $this->load->view('body_parts/selection/kpi_date', $selectData, TRUE);
        $this->_template['body']['aGames'] = $this->game->listGames();
        $this->_template['content'] .= $this->load->view("serverkpi/top", $viewData, TRUE);
        $this->_template['body']['title'] = "Server";
        $this->load->view('master_page', $this->_template);
    }
    
    public function rank(){
    	
    	$game_code = $this->session->userdata('current_game');
    	$current_user = $this->session->userdata('user');
    	
    	$selectData ['body'] ['aGames'] = $this->game->listGames();
    	$selectData ['body'] ['gameCode'] = $game_code;
    	$date = $_SESSION['server']['date'];
    	if ($this->input->post('kpidatepicker') != "") {
    		$date = $this->input->post('kpidatepicker');
    	} else if ($date == "") {
    		$date = date('d/m/Y', strtotime('-1 days'));
    	}
    	$selectData ['body'] ['day'] ['kpidatepicker'] = $date;
    	$_SESSION['server']['date'] = $date;
    	
    	$viewData = $selectData;
    	$viewData = $this->getViewData ( $viewData );
    	$viewData["reportDate"] = $this->util->formatDate("d/m/Y", "d-M-Y", $date);
    	$viewData["game"] = $this->game->findGameInfo($game_code);
    	
    	$this->_template['content'] = $viewData ['body'] ['selection'] = $this->load->view('body_parts/selection/kpi_date', $selectData, TRUE);
    	$this->_template['body']['aGames'] = $this->game->listGames();
    	$this->_template['content'] .= $this->load->view("serverkpi/rank", $viewData, TRUE);
    	$this->_template['body']['title'] = "Server";
    	$this->load->view('master_page', $this->_template);
    }
    
    public function detail(){
    	 
    	if ($this->input->post('options')) {
            $_SESSION[$this->class_name]['post'] = $_POST;
        } else {

        }
        $selectedServer = $_POST["selectedServer"];
        
        $viewData['body']['aGames'] = $this->game->listGames();
        $viewData['body']['optionsWeek'] = $this->util->listOptionsWeek();
        $viewData['body']['optionsMonth'] = $this->util->listOptionsMonth();
        $gameCode = $this->session->userdata('current_game');
        $viewData['body']['gameCode'] = $gameCode;
    	 
    	$viewData = $this->getDetailData ($viewData, $selectedServer);
    	 
    	$viewData["game"] = $this->game->findGameInfo($gameCode);
    	$viewData['body']['cbb'] = "A";
    	$this->_template['content'] = $this->load->view("serverkpi/detail", $viewData, TRUE);
    	$this->_template['body']['title'] = "Server Kpi";
    	$this->_template['body']['aGames'] = $this->game->listGames();
    	$this->load->view('master_page', $this->_template);
    }
    
    public function getDetailData($viewData, $serverLst){
    	
    	$this->util->parseUserInput($this->class_name, $viewData, $chartData, $tableData);
    	
    	$tableData = array();
    	$gameCode = $viewData ['body'] ['gameCode'];
    	$timing = $viewData['body']['options'];
    	$fromDate = $viewData['body']['fromDate'];
    	$toDate = $viewData['body']['toDate'];
    	
    	$dbData = $this->server->getDetailData($gameCode,  $timing, $fromDate, $toDate);
    	$dbServer = $this->server->getAvailableServer($gameCode,  $timing, $fromDate, $toDate);
    	$serverNameConf = $this->server->getServerList($gameCode);
    	// contain all aivailable server
    	$serverName = $this->mapServerName($dbServer, $serverNameConf);
    	
    	$selectServer = $this->selectServers($gameCode, $serverLst, $dbData, $serverName);
    	
    	$viewData["line"] = $dbData;
    	$viewData["selectedGroup"] = $selectServer;
    	$viewData["availableGroup"] = $serverName;
    	
    	$table = $this->createTableDataDetail($dbData);
    	
    	$viewData["table"]["selectedGroup"] = $selectServer;
    	$viewData["table"]["days"] = $dbData["log_date"];
    	$viewData["table"]["timing"] = $timing;
    	
    	$viewData["table"]["data"] = $table;
    	$viewData['table']['title'] = "Detail Server Report";
    	$viewData['table']['id'] = "group";
    	$viewData['table']['exportTitle'] = $this->util->get_export_filename($gameCode, "server", $viewData['body']['fromDate'], $viewData['body']['toDate']);
    	 
    	return $viewData;
    }
    
    public function getViewData($viewData){
    	
    	$tableData = array();
    	$gameCode = $viewData ['body'] ['gameCode'];
        $reportDate = $this->util->formatDate("d/m/Y", "Y-m-d", $viewData ['body'] ['day'] ['kpidatepicker']);
    	$dbServerData = $this->server->getServerData($gameCode,  $reportDate);
    	$bar = $this->getTopServers(10, $dbServerData);
    	$viewData["bar"] = $bar;
    	
    	$table = $this->createTableData($dbServerData);
    	$newTable = $this->util->reverseData2($table, "server");
    	$viewData["table"]["data"] = $newTable;
    	$viewData['table']['title'] = "Detail Server Report";
    	$viewData['table']['id'] = "group";
    	$viewData['table']['exportTitle'] = $this->util->get_export_filename($gameCode, "server", $reportDate, $viewData['body']['toDate']);

    	$viewData['table']['header'] = $this->util->get_kpi_header_name();

    	return $viewData;
    }
    
    public function createTableData($data){
    	
    	$table = array();
    	foreach($data["server"] as $time => $value){
    		foreach($value as $kpi => $server){
    			
    			foreach($server as $name => $v){
    				$table[$name][$kpi] = number_format($v);
    			}
    		}
    	}
		
    	return $table;
    }
    
    public function createTableDataDetail($data){
    	 
    	$table = array();
    	foreach($data["group"] as $time => $value){
    		foreach($value as $kpi => $server){
    			foreach($server as $name => $v){
    				
    				$table[$kpi][$name] = $v;
    			}
    		}
    	}
    
    	return $table;
    }
    
    public function getTopServers($numOfServer, $dbData){

    	$results = array();
    	foreach($dbData["server"] as $time => $kpiArr){
    		foreach($kpiArr as $kpi => $value){

    			// ignore pu & npu
    			if(strpos ( $kpi, "pu") !== false && strpos ( $kpi, "gr") == false){
    				continue;
    			}
    			
    			$count = 0;
    			foreach($value as $server=> $v){

    				if($count >= $numOfServer){
    					break;
    				}
    			
    				// if kip contains "gr"	=> revenue kpi	=> get number user of this kpi (pu or npu)
    				if(strpos ( $kpi, "gr") !== false){
    					$kpiName = "";
    					if(strpos ( $kpi, "npu") !== false){	// firstcharge kpi

    						$kpiName = "npu" . $time;
    					} else {	// payment kpi
    						
    						$kpiName = "pu" . $time;
    					}
    					
    					$results["server"][$time][$kpiName][$server] = $dbData["server"][$time][$kpiName][$server];
    				}
    				
    				$results["server"][$time][$kpi][$server] = $v;
    				$count ++;
    			}
    		}
    	}
    	return $results;
    }
    
    public function mapServerName($lstServer, $serverName){
    	
    	$results = array();
    	foreach($lstServer as $server){
    		
    		if(isset($serverName[$server])){
    		
    			$results[$server] = $serverName[$server];
    		}else{
    			$results[$server] = $server;
    		}
    		
    	}
    	return $results;
    }
    
    public function selectServers($gameCode, $serverArr, $dbData, $serverName){
    
    	$number = 7;
    	$results = array();
    	if(count($serverArr) == 0){
    		
    		// select top 10
    		foreach($dbData["group"] as $time => $kpiArr){
    			foreach($kpiArr as $kpi => $value){
    				
    				// loop all day
    				$rankArr = array();
    				$serverArr = array();
    				
    				foreach($value as $server => $day){
    					
    					// sum total
    					$total = 0;
    					foreach($day as $log_date => $val){
    						$total += $val;
    					}
    					
    					$rankArr[$server] = $total;
    				}
    				
    				arsort($rankArr);
    				$i = 0;
    				
    				foreach ($rankArr as $s => $val){
    					if($i >= $number){
    						break;
    					}
    					
    					$serverArr[$s] = $serverName[$s];
    					$i++;
    				}
    				
    				// selected top server here
    				$results[$kpi] = $serverArr;
    			}
    		}
    	} else {
    		$arr = array();
    		foreach($serverArr as $server){
    			$arr[$server] = $serverName[$server];
    		}
    		// select server in array
    		foreach($dbData["group"] as $time => $kpiArr){
    			foreach($kpiArr as $kpi => $value){
    				$results[$kpi] = $arr;
    			}
    		}
    	}
    	return $results;
    }
}