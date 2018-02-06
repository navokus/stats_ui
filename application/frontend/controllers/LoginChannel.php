<?php

/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 14/03/2016
 * Time: 23:29
 */

class LoginChannel extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
		//$this->output->enable_profiler(TRUE);
        $this->load->model('game_model', 'game');
        $this->load->model('login_channel_model', 'channel');
        $this->load->library('form_validation');
        $this->load->library('util');
    }

    public function index()
    {
        $this->channel();
    }

    public function channel()
    {
        $game_code = $this->session->userdata('current_game');
        $current_user = $this->session->userdata('user');

        $selectData ['body'] ['aGames'] = $this->game->listGames();
        $selectData ['body'] ['gameCode'] = $game_code;
        $date = $_SESSION['channel']['date'];
        if ($this->input->post('kpidatepicker') != "") {
            $date = $this->input->post('kpidatepicker');
        } else if ($date == "") {
            $date = date('d/m/Y', strtotime('-1 days'));
        }
        $selectData ['body'] ['day'] ['kpidatepicker'] = $date;
        $_SESSION['channel']['date'] = $date;
        
        $viewData = $selectData;
        $viewData = $this->getViewData ( $viewData );
        
        $this->_template['content'] = $viewData ['body'] ['selection'] = $this->load->view('body_parts/selection/kpi_date', $selectData, TRUE);
        $this->_template['body']['aGames'] = $this->game->listGames();
        $this->_template['content'] .= $this->load->view("login_channel/index", $viewData, TRUE);
        $this->_template['body']['title'] = "Channel";
        $this->load->view('master_page', $this->_template);
    }
    
    public function getViewData($viewData){
    	$tableData = array();
    	$gameCode = $viewData ['body'] ['gameCode'];
    	$reportDate = $this->util->formatDate("d/m/Y", "Y-m-d", $viewData ['body'] ['day'] ['kpidatepicker']);
    	$dbChannelData = $this->channel->getChannelData($gameCode,  $reportDate);
    	$pie = $this->groupSmallDataTogether($dbChannelData);
    	$table = $this->createTableData($dbChannelData);
    	$viewData["pie"] = $pie;
    	
    	$newTable = $this->util->reverseData2($table, "channel");
    	$viewData["table"]["data"] = $newTable;
    	$viewData['table']['title'] = "KPI Detail";
    	/**
    	 * vinhdp
    	 * 2017-02-16 change id from group to channel-detail
    	 */
    	$viewData['table']['id'] = "channel-detail";
    	$viewData['table']['exportTitle'] = $this->util->get_export_filename($gameCode, "login_channel", $reportDate, $viewData['body']['toDate']);
    	
    	/* $viewData['table']['header'] = array(
    			"channel" => "Channel",
    			"a1" => "A1",
    			"n1" => "N1",
    			"pu1" => "PU1",
    			"gr1" => "RV1",
    			"npu1" => "NPU1",
    			"npu_gr1" => "NPU_RV1",
    			"a7" => "A7",
    			"n7" => "N7",
    			"pu7" => "PU7",
    			"gr7" => "RV7",
    			"npu7" => "NPU7",
    			"npu_gr7" => "NPU_RV7",
    			"a30" => "A30",
    			"n30" => "N30",
    			"pu30" => "PU30",
    			"gr30" => "RV30",
    			"npu30" => "NPU30",
    			"npu_gr30" => "NPU_RV30",
    	); */
    	
    	$viewData['table']['header'] = $this->util->get_kpi_header_name();
    	
    	return $viewData;
    }
    
    public function createTableData($data){
    	
    	$table = array();
    	foreach($data["channel"] as $time => $value){
    		foreach($value as $kpi => $channel){
    			
    			foreach($channel as $name => $v){
    				$table[$name][$kpi] = number_format($v);
    			}
    		}
    	}

    	return $table;
    }
    
    public function groupSmallDataTogether($dbData){
    	
    	$lstChannel = array();
    	
    	$groupThreshold = 0.1; // 10%
    	$results = array();
    	foreach($dbData["channel"] as $time => $kpiArr){
    		foreach($kpiArr as $kpi => $value){
    			
    			// calculate total kpi_value
    			$sum = 0;
    			foreach($value as $channel => $v){
    				$sum += $v;
    				$lstChannel[] = $channel;
    			}
    			
				// determine which channel need to group together     			
    			foreach($value as $channel => $v){
    				if($v / $sum < $groupThreshold){
    					$results["channel"][$time][$kpi]["other"] += $v;
    					$lstChannel[] = "other";
    				}else{
    					$results["channel"][$time][$kpi][$channel] = $v;
    				}
    			}
    		}
    	}
    	$lstChannel = array_unique($lstChannel);
    	$results["colors"] = $this->util->generateColors($lstChannel);
    	return $results;
    }
    
    public function detail(){
    
    	if ($this->input->post('options')) {
    		$_SESSION[$this->class_name]['post'] = $_POST;
    	} else {
    
    	}
    	$selectedChannel = $_POST["selectedChannel"];
    
    	$viewData['body']['aGames'] = $this->game->listGames();
    	$viewData['body']['optionsWeek'] = $this->util->listOptionsWeek();
    	$viewData['body']['optionsMonth'] = $this->util->listOptionsMonth();
    	$gameCode = $this->session->userdata('current_game');
    	$viewData['body']['gameCode'] = $gameCode;
    
    	$viewData = $this->getDetailData ($viewData, $selectedChannel);
    
    	$viewData["game"] = $this->game->findGameInfo($gameCode);
    	$viewData['body']['cbb'] = "A";
    	$this->_template['content'] = $this->load->view("login_channel/detail", $viewData, TRUE);
    	$this->_template['body']['title'] = "Channel Kpi";
    	$this->_template['body']['aGames'] = $this->game->listGames();
    	$this->load->view('master_page', $this->_template);
    }
    
    public function getDetailData($viewData, $lstChannel){
    	 
    	$this->util->parseUserInput($this->class_name, $viewData, $chartData, $tableData);
    	 
    	$tableData = array();
    	$gameCode = $viewData ['body'] ['gameCode'];
    	$timing = $viewData['body']['options'];
    	$fromDate = $viewData['body']['fromDate'];
    	$toDate = $viewData['body']['toDate'];
    	 
    	$dbData = $this->channel->getDetailData($gameCode,  $timing, $fromDate, $toDate);
    	$dbChannel = $this->channel->getAvailableChannel($gameCode,  $timing, $fromDate, $toDate);
    	$selectChannel = $this->selectChannel($lstChannel, $dbData);
    	$viewData["line"] = $dbData;
    	$viewData["selectedGroup"] = $selectChannel;
    	$viewData["availableGroup"] = $dbChannel;
    	 
    	$table = $this->createTableDataDetail($dbData);
    	 
    	$viewData["table"]["selectedGroup"] = $selectChannel;
    	$viewData["table"]["days"] = $dbData["log_date"];
    	$viewData["table"]["timing"] = $timing;
    	 
    	$viewData["table"]["data"] = $table;
    	$viewData['table']['title'] = "Detail Channel Report";
    	$viewData['table']['id'] = "group";
    	$viewData['table']['exportTitle'] = $this->util->get_export_filename($gameCode, "channel", $viewData['body']['fromDate'], $viewData['body']['toDate']);
    
    	return $viewData;
    }
    
    public function selectChannel($channelArr, $dbData){
    
    	$number = 7;
    	$results = array();
    	if(count($channelArr) == 0){
    
    		// select top 10
    		foreach($dbData["group"] as $time => $kpiArr){
    			foreach($kpiArr as $kpi => $value){
    
    				// loop all day
    				$rankArr = array();
    				$channelArr = array();
    
    				foreach($value as $channel => $day){
    						
    					// sum total
    					$total = 0;
    					foreach($day as $log_date => $val){
    						$total += $val;
    					}
    						
    					$rankArr[$channel] = $total;
    				}
    
    				arsort($rankArr);
    				$i = 0;
    
    				foreach ($rankArr as $s => $val){
    					if($i >= $number){
    						break;
    					}
    						
    					$channelArr[] = $s;
    					$i++;
    				}
    
    				// selected top server here
    				$results[$kpi] = $channelArr;
    			}
    			 
    		}
    	} else {
    		// select server in array
    		foreach($dbData["group"] as $time => $kpiArr){
    			foreach($kpiArr as $kpi => $value){
    				$results[$kpi] = $channelArr;
    			}
    		}
    	}
    
    	return $results;
    }
    
    public function createTableDataDetail($data){
    
    	$table = array();
    	foreach($data["group"] as $time => $value){
    		foreach($value as $kpi => $channel){
    			foreach($channel as $name => $v){
    
    				$table[$kpi][$name] = $v;
    			}
    		}
    	}
    
    	return $table;
    }
}