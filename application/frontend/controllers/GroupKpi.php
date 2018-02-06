<?php

/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 14/03/2016
 * Time: 23:29
 */

class GroupKpi extends MY_Controller
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
        
        define("HOME_DIR", "/home/vinhdp/files/");
    }

    public function index()
    {
        $this->top();
    }
    
    public function convertdata($groupId, $date){
    	
    	$this->output->set_content_type('application/json');
    	$gameCode = $this->session->userdata('current_game');
    	$result = $this->convert->convertdata($groupId, $gameCode, $date);
    	echo "Converted " . $result["total"] . " into " . $result["success"] . " success!";
    }

    public function detail(){
    	 
    	$gameCode = $this->session->userdata('current_game');
    	
    	if ($this->input->post('options')) {
            $_SESSION[$this->class_name]['post'] = $_POST;
        } else {

        }
        
        // get group id
        $groupId = $_GET["group_id"];
        if($groupId == "" && $_SESSION["group_kpi"]['group_id'] == ""){
        	
        	$groupId = "server";
        	$_SESSION["group_kpi"]['group_id'] = $groupId;
        }else if($groupId == "" && $_SESSION["group_kpi"]['group_id'] != ""){
        	
        	$groupId = $_SESSION["group_kpi"]['group_id'];
        }
        
        $selectedGroup = $_POST["selectedGroup"];
        
        // remove all selected id when gamecode selected change
        if($_SESSION["group_kpi"]['game_code'] != $gameCode){
        	$selectedGroup = array();
        }
        
        $_SESSION["group_kpi"]['game_code'] = $gameCode;
        
        $viewData['body']['aGames'] = $this->game->listGames();
        $viewData['body']['optionsWeek'] = $this->util->listOptionsWeek();
        $viewData['body']['optionsMonth'] = $this->util->listOptionsMonth();
        $viewData['body']['gameCode'] = $gameCode;
    	 
    	$viewData = $this->getDetailData ($groupId, $viewData, $selectedGroup);
    	 
    	$viewData["groupName"] = $this->group->tableInfo[$groupId]["name"];
    	$viewData["game"] = $this->game->findGameInfo($gameCode);
    	$viewData['body']['cbb'] = "A";
    	$viewData['body']['groupId'] = $groupId;
    	$this->_template['content'] = $this->load->view("groupkpi/detail", $viewData, TRUE);
    	$this->_template['body']['title'] = $this->group->tableInfo[$groupId]["name"] . " Kpi";
    	$this->_template['body']['a$cookieGroupGames'] = $this->game->listGames();
    	$this->load->view('master_page', $this->_template);
    }
    
    public function stack($groupId){
    
    	ini_set('memory_limit', '-1');
    	$gameCode = $this->session->userdata('current_game');
    	$isSessionCache = true;
    	 
    	if ($this->input->post('options')) {
    		$_SESSION[$this->class_name]['post'] = $_POST;
    	} else {
    		$isSessionCache = false;
    	}
    	
    	// get group id
    	//$groupId = $_GET["group_id"];
    	if($groupId == "" && $_SESSION["group_kpi"]['group_id'] == ""){
    		 
    		$groupId = "server";
    		$_SESSION["group_kpi"]['group_id'] = $groupId;
    	}else if($groupId == "" && $_SESSION["group_kpi"]['group_id'] != ""){
    		 
    		$groupId = $_SESSION["group_kpi"]['group_id'];
    	}
    
    	// get selected group
    	$selectedGroup = $_POST["selectedGroup"];

    	// remove all selected id when gamecode selected change
    	if($_SESSION["group_kpi"]['game_code'] != $gameCode || count($selectedGroup) == 0){
    		$selectedGroup = array();
    		$_SESSION["group-selected"] = null;
    	}
    
    	$_SESSION["group_kpi"]['game_code'] = $gameCode;
    
    	$viewData['body']['aGames'] = $this->game->listGames();
    	$viewData['body']['optionsWeek'] = $this->util->listOptionsWeek();
    	$viewData['body']['optionsMonth'] = $this->util->listOptionsMonth();
    	$viewData['body']['gameCode'] = $gameCode;
    
    	$viewData = $this->getDetailData ($groupId, $viewData, $selectedGroup, $isSessionCache);

    	$isDownload = $_POST["isDownload"];
    	if($isDownload == true){
    	
    		$filename = $this->generateExcelFile($viewData);
    		header('Content-Type: application/csv');
    		header('Content-Disposition: attachment; filename=' . $filename);
    		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    		header('Content-Transfer-Encoding: binary');
    		header('Cache-Control: must-revalidate');
    		header('Pragma: public');
    		header('Pragma: no-cache');
    		readfile("/tmp/" . $filename);
    		return;
    	}
    	
    	/* vinhdp - edit at 2017-02-28 */
    	$groupInfo = $this->group->getGroupInfo($groupId);
    	$viewData["groupInfo"] = $groupInfo;
    	/* end */
    	
    	$viewData["groupName"] = $this->group->tableInfo[$groupId]["name"];
    	$viewData["game"] = $this->game->findGameInfo($gameCode);
    	$viewData['body']['cbb'] = "A";
    	$viewData['body']['groupId'] = $groupId;
    	$this->_template['content'] = $this->load->view("groupkpi/stack", $viewData, TRUE);
    	
    	/* vinhdp - edit at 2017-02-28 */
    	if(count($groupInfo) > 0){
    		$this->_template['body']['title'] = $groupInfo["name"] . " Kpi";
    	} else {
    		$this->_template['body']['title'] = $this->group->tableInfo[$groupId]["name"] . " Kpi";
    	}
    	/* end */
    	
    	$this->_template['body']['aGames'] = $this->game->listGames();
    	$this->load->view('master_page', $this->_template);
    }

    private function generateExcelFile($data){
    	
    	require_once APPPATH . "/third_party/PHPExcel.php";

    	$objPHPExcel = new PHPExcel();
    	$objPHPExcel->setActiveSheetIndex(0);
    	$activeSheet = $objPHPExcel->getActiveSheet();
    	
    	$days = $data["table"]["days"];
    	$tableData = $data["table"]["data"];
    	$group = array_values($data["selectedGroup"]);
    	$selectedGroup = array();
    	$gameCode = $data['body']['gameCode'];
    	$from = $data['body']['fromDate'];
    	$to = $data['body']['toDate'];
    	$fileName = $this->util->get_export_filename($gameCode, "group", $from, $to) . ".xlsx";

    	// only get key
    	foreach($group as $g){
    		foreach ($g as $key => $value){
    			if(!in_array($key, $selectedGroup)){
    				$selectedGroup[] = $key;
    			}
    		}
    	}
    	rsort($days);
    	rsort($selectedGroup);
    	
    	$row = 0;
    	$numGroup = count($selectedGroup);
    	$numDay = count($days);
    	$totalColumns = $numGroup * $numDay;
    	
    	// process header
    	$default_border = array( 'style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb'=>'1006A3') );
        $header_style = array(
            'borders' => array( 'bottom' => $default_border, 'left' => $default_border, 'top' => $default_border, 'right' => $default_border, ),
            'fill' => array( 'type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb'=>'E1E0F7'), ),
            'font' => array( 'bold' => true, ) );
        $cell_style = array(
            'borders' => array( 'bottom' => $default_border, 'left' => $default_border, 'top' => $default_border, 'right' => $default_border, ),
        );
        
        $activeSheet->mergeCellsByColumnAndRow(0, 1, 0, 2);
        $activeSheet->setCellValueByColumnAndRow(0, 1, "Kpi Name");
        $activeSheet->getStyleByColumnAndRow(0, 1)
        ->applyFromArray($header_style)
        ->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
    	$row = 1;
    	$column = 0;
    	for($column = 0; $column < $numDay; $column++){
    		
    		// set day header
    		$activeSheet->setCellValueByColumnAndRow($column * $numGroup + 1, $row, $days[$column]);
    		$activeSheet->mergeCellsByColumnAndRow($column * $numGroup + 1, $row, ($column + 1) * $numGroup, $row);
    		
    		$activeSheet->getStyleByColumnAndRow($column * $numGroup + 1, $row)
    		->applyFromArray($header_style)
    		->getAlignment()
    		->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		//set group header
    		$i = 0;
    		for($i = 0; $i < $numGroup; $i++){
    			$activeSheet->setCellValueByColumnAndRow(($column * $numGroup) + $i + 1, $row + 1, $selectedGroup[$i]);
    			
    			$activeSheet->getStyleByColumnAndRow(($column * $numGroup) + $i + 1, $row + 1)
    			->applyFromArray($header_style)
    			->getAlignment()
    			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		}
    	}
    	
    	// num of row
    	$row = 3;
    	foreach($tableData as $key => $value){
    		$groupIndex = 0;
    		$dayIndex = 0;
    		
    		for($dayIndex = 0; $dayIndex < $numDay; $dayIndex++){
    			for($groupIndex = 0; $groupIndex < $numGroup; $groupIndex++){
    				
    				$day = $days[$dayIndex];
    				$group = $selectedGroup[$groupIndex];
    				
    				$formattedValue = 0;
    				if(fmod($value[$group][$day], 1.0) != 0.0){
    					$formattedValue = number_format($value[$group][$day], 2);
    				}else{
    					$formattedValue = number_format($value[$group][$day]);
    				}
    				
    				$activeSheet->getStyleByColumnAndRow(($dayIndex * $numGroup) + $groupIndex + 1, $row)
                        ->applyFromArray($cell_style)
                        ->getAlignment()
                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    				$activeSheet->setCellValueByColumnAndRow(($dayIndex * $numGroup) + $groupIndex + 1, $row, $formattedValue);
    				
    				$activeSheet->getStyleByColumnAndRow(0, $row)
    				->applyFromArray($header_style)
    				->getAlignment()
    				->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    				$activeSheet->setCellValueByColumnAndRow(0, $row, $key);
    			}
    		}
    		
    		$row++;
    	}
    	
    	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    	$objWriter->save("/tmp/" . $fileName);

    	return $fileName;
    }
    
    
    
    public function getDetailData($groupId, $viewData, $groupList, $isSessionCache = false){
    	
    	$this->util->parseUserInput($this->class_name, $viewData, $chartData, $tableData, $isSessionCache, 30);
    	
    	$tableData = array();
    	$gameCode = $viewData ['body'] ['gameCode'];
    	$timing = $viewData['body']['options'];
    	$fromDate = $viewData['body']['fromDate'];
    	$toDate = $viewData['body']['toDate'];
    	
    	$dbData = $this->group->getDetailData($groupId, $gameCode,  $timing, $fromDate, $toDate);
    	// select all available group in db from date to date
    	$dbGroup = $this->group->getAvailableGroup($groupId, $gameCode,  $timing, $fromDate, $toDate);
    	// select all group name config in db
    	$groupNameConf = $this->group->getGroupList($groupId, $gameCode);
    	// contain all aivailable group
    	$groupName = $this->mapGroupName($dbGroup, $groupNameConf);
    	
    	// read selected group from session
    	//$selectGroup = $this->selectGroup($gameCode, $groupList, $dbData, $groupName);
    	$selectGroup = array();
    	$sessionGroup = $_SESSION["group-selected"];
    	
    	if(count($groupList) == 0 && $sessionGroup == null){
    		//var_dump("ALL NULL => AUTO SELECT");
    		$selectGroup = $this->selectGroup($gameCode, $groupList, $dbData, $groupName);
    	} else if (count($groupList) != 0){
    		//var_dump("USE SELECTION");
    		$selectGroup = $this->selectGroup($gameCode, $groupList, $dbData, $groupName);
    	} else if(count($groupList) == 0 && $sessionGroup != null && $isSessionCache == true){
    		//var_dump("USE SESSION");
    		$selectGroup = $sessionGroup;
    	} else {
    		$selectGroup = $this->selectGroup($gameCode, $groupList, $dbData, $groupName);
    	}
    	
    	$_SESSION["group-selected"] = $selectGroup;
    	// end
    	
    	$viewData["line"] = $dbData;
    	$viewData["line"]["colors"] = $this->util->generateColors2($selectGroup);
    	$viewData["selectedGroup"] = $selectGroup;
    	$viewData["availableGroup"] = $groupName;
    	$viewData["timing"] = $timing;
    	
    	$table = $this->createTableDataDetail($dbData);
    	$viewData["table"]["selectedGroup"] = $selectGroup;
    	$viewData["table"]["days"] = $dbData["log_date"];
    	$viewData["table"]["timing"] = $timing;
    	
    	$viewData["table"]["data"] = $table;
    	$viewData['table']['title'] = "Detail " . $this->group->tableInfo[$groupId]["name"] . " Report";
    	$viewData['table']['id'] = "group";
    	$viewData['table']['exportTitle'] = $this->util->get_export_filename($gameCode, "group", $viewData['body']['fromDate'], $viewData['body']['toDate']);
    	 
    	return $viewData;
    }
    
    public function ajaxChart($groupId, $timing, $kpiName){
    
    	ini_set('memory_limit', '-1');
    	$this->output->set_content_type('application/json');
    	$gameCode = $this->session->userdata('current_game');
    	 
    	if ($this->input->post('options')) {
    		$_SESSION[$this->class_name]['post'] = $_POST;
    	} else {
    		 
    	}
    	 
    	// get group id
    	if($groupId == "" && $_SESSION["group_kpi"]['group_id'] == ""){
    		 
    		$groupId = "server";
    		$_SESSION["group_kpi"]['group_id'] = $groupId;
    	}else if($groupId == "" && $_SESSION["group_kpi"]['group_id'] != ""){
    		 
    		$groupId = $_SESSION["group_kpi"]['group_id'];
    	}
    	 
    	$selectedGroup = $_POST["selectedGroup"];
    	 
    	// remove all selected id when gamecode selected change
    	if($_SESSION["group_kpi"]['game_code'] != $gameCode){
    		$selectedGroup = array();
    	}
    	 
    	$_SESSION["group_kpi"]['game_code'] = $gameCode;
    	 
    	$viewData['body']['aGames'] = $this->game->listGames();
    	$viewData['body']['optionsWeek'] = $this->util->listOptionsWeek();
    	$viewData['body']['optionsMonth'] = $this->util->listOptionsMonth();
    	$viewData['body']['gameCode'] = $gameCode;
    	 
    	$viewData = $this->getDetailData ($groupId, $viewData, $selectedGroup, true);

    	$data = $viewData["line"]["group"][$timing][$kpiName];
    	$dayArr = $viewData["line"]["log_date"];
    	$chartInfo = $this->getChartInfo($kpiName);
    	
    	/* vinhdp - edit at 2017-02-28 */
    	$groupInfo = $this->group->getGroupInfo($groupId);
    	$viewdata["groupInfo"] = $groupInfo;
    	/* end */
    	
    	$viewdata['timing'] = $timing;
    	$viewdata["game"] = $game;
    	$viewdata['id'] = $kpiName;
    	$viewdata['title'] = $this->util->get_main_chart_title(array("feature"=>$chartInfo["name"],"game_info"=>$this->_gameInfo), $timing);
    	$viewdata['subTitle'] = $this->util->get_sub_chart_title(array("feature"=>$chartInfo["name"], "to"=>$viewData["body"]["toDate"], "from"=>$viewData["body"]["fromDate"], "game_info"=>$this->_gameInfo), $timing);
    	$viewdata['kpi'] = $chartInfo["kpi"];
    	$viewdata['metric'] = $chartInfo["metric"];
    	$viewdata['unit'] = $chartInfo["unit"];
    	$viewdata['data'] = $data;
    	$viewdata['colors'] = $viewData["line"]["colors"];
    	$viewdata['days'] = $dayArr;
    	$viewdata['selectedGroup'] = $viewData["selectedGroup"][$kpiName];
    	// end
    	 
    	$html = $this->load->view("body_parts/chart/gstack_bar", $viewdata, TRUE);
    	echo $html;
    }
    
    public function ajaxTable($groupId, $timing){
    
    	ini_set('memory_limit', '-1');
    	$this->output->set_content_type('application/json');
    	$gameCode = $this->session->userdata('current_game');
    
    	if ($this->input->post('options')) {
    		$_SESSION[$this->class_name]['post'] = $_POST;
    	} else {
    		 
    	}
    
    	// get group id
    	if($groupId == "" && $_SESSION["group_kpi"]['group_id'] == ""){
    		 
    		$groupId = "server";
    		$_SESSION["group_kpi"]['group_id'] = $groupId;
    	}else if($groupId == "" && $_SESSION["group_kpi"]['group_id'] != ""){
    		 
    		$groupId = $_SESSION["group_kpi"]['group_id'];
    	}
    
    	$selectedGroup = $_POST["selectedGroup"];
    
    	// remove all selected id when gamecode selected change
    	if($_SESSION["group_kpi"]['game_code'] != $gameCode){
    		$selectedGroup = array();
    	}
    
    	$_SESSION["group_kpi"]['game_code'] = $gameCode;
    
    	$viewData['body']['aGames'] = $this->game->listGames();
    	$viewData['body']['optionsWeek'] = $this->util->listOptionsWeek();
    	$viewData['body']['optionsMonth'] = $this->util->listOptionsMonth();
    	$viewData['body']['gameCode'] = $gameCode;
    	
    	$viewData = $this->getDetailData ($groupId, $viewData, $selectedGroup, true);
    	
    	$html = $this->load->view("body_parts/table/group_table", $viewData["table"], TRUE);
    	echo $html;
    }
    
    public function getChartInfo($kpiName){
    	
    	$chartInfo = array();
    	$chartInfo["a"] = array("name" => "Active User", "kpi" => "Active User", "metric" => "Active User", "unit" => "user");
    	$chartInfo["acu"] = array("name" => "ACU", "kpi" => "ACU", "metric" => "ACU", "unit" => "user");
    	$chartInfo["pcu"] = array("name" => "PCU", "kpi" => "PCU", "metric" => "PCU", "unit" => "user");
    	$chartInfo["n"] = array("name" => "Account Register", "kpi" => "Account Register", "metric" => "Account Register", "unit" => "user");
    	$chartInfo["pu"] = array("name" => "Paying User", "kpi" => "Paying User", "metric" => "Paying User", "unit" => "user");
    	$chartInfo["gr"] = array("name" => "Revenue", "kpi" => "Revenue", "metric" => "Revenue", "unit" => "VND");
    	$chartInfo["npu"] = array("name" => "Firstcharge User", "kpi" => "Firstcharge User", "metric" => "Firstcharge User", "unit" => "user");
    	$chartInfo["npu_gr"] = array("name" => "Firstcharge Revenue", "kpi" => "Firstcharge Revenue", "metric" => "Firstcharge Revenue", "unit" => "VND");
    	
    	$timing = array("1", "17", "31");
    	
    	foreach($chartInfo as $key => $value){
    		if(strpos ( $kpiName, $key) == 0){
    			foreach($timing as $v){
    				if($key . $v == $kpiName){
    					return $chartInfo[$key];
    				}
    			}
    		}
    	}
    	
    	return null;
    }
    
	public function pie($groupId){
    	 
		$gameCode = $this->session->userdata('current_game');
		
    	if ($this->input->post('options')) {
            $_SESSION[$this->class_name]['post'] = $_POST;
        } else {

        }
        
        if($groupId == "" && $_SESSION["group_kpi"]['group_id'] == ""){
        	
        	$groupId = "server";
        	$_SESSION["group_kpi"]['group_id'] = $groupId;
        }else if($groupId == "" && $_SESSION["group_kpi"]['group_id'] != ""){
        	
        	$groupId = $_SESSION["group_kpi"]['group_id'];
        }
        
        $selectedGroup = $_POST["selectedGroup"];
        
        // remove all selected id when gamecode selected change
        if($_SESSION["group_kpi"]['game_code'] != $gameCode){
        	$selectedGroup = array();
        }
        
        $_SESSION["group_kpi"]['game_code'] = $gameCode;
        
        $viewData['body']['aGames'] = $this->game->listGames();
        $viewData['body']['optionsWeek'] = $this->util->listOptionsWeek();
        $viewData['body']['optionsMonth'] = $this->util->listOptionsMonth();
        $viewData['body']['gameCode'] = $gameCode;
        
    	$viewData = $this->getPieData ($groupId, $viewData, $selectedGroup);
    	 
    	$viewData["groupName"] = $this->group->tableInfo[$groupId]["name"];
    	$viewData["game"] = $this->game->findGameInfo($gameCode);
    	$viewData['body']['cbb'] = "A";
    	$viewData['body']['groupId'] = $groupId;
    	$this->_template['content'] = $this->load->view("groupkpi/pie", $viewData, TRUE);
    	$this->_template['body']['title'] = $this->group->tableInfo[$groupId]["name"] . " Kpi";
    	$this->_template['body']['aGames'] = $this->game->listGames();
    	$this->load->view('master_page', $this->_template);
    }
    
    public function getPieData($groupId, $viewData, $groupList){
    	 
    	$this->util->parseUserInput($this->class_name, $viewData, $chartData, $tableData, 30);
    	$reportDate = $this->util->formatDate("d/m/Y", "Y-m-d", $viewData ['body'] ['day'] ['kpidatepicker']);
    	$tableData = array();
    	$gameCode = $viewData ['body'] ['gameCode'];
    	$timing = $viewData['body']['options'];
    	$fromDate = $viewData['body']['fromDate'];
    	$toDate = $viewData['body']['toDate'];
    	 
    	$dbData = $this->group->getDetailData($groupId, $gameCode,  $timing, $fromDate, $toDate, true);
    	// select all available group in db from date to date
    	$dbGroup = $this->group->getAvailableGroup($groupId, $gameCode,  $timing, $fromDate, $toDate, true);
    	// select all group name config in db
    	$groupNameConf = $this->group->getGroupList($groupId, $gameCode);
    	// contain all aivailable group
    	$groupName = $this->mapGroupName($dbGroup, $groupNameConf);
    	 
    	// contain all user select group
    	$selectGroup = $this->selectGroup($gameCode, $groupList, $dbData, $groupName);
    	$viewData["pie"] = $dbData;
    	$viewData["pie"]["colors"] = $this->util->generateColors2($selectGroup);
    	$viewData["selectedGroup"] = $selectGroup;
    	$viewData["availableGroup"] = $groupName;
    	$table = $this->createTableDataDetail($dbData);
    	 
    	$viewData["table"]["selectedGroup"] = $selectGroup;
    	$viewData["table"]["days"] = $dbData["log_date"];
    	$viewData["table"]["timing"] = $timing;
    	 
    	$viewData["table"]["data"] = $table;
    	$viewData['table']['title'] = "Detail " . $this->group->tableInfo[$groupId]["name"] . " Report";
    	$viewData['table']['id'] = "group";
    	$viewData['table']['exportTitle'] = $this->util->get_export_filename($gameCode, "group", $viewData['body']['fromDate'], $viewData['body']['toDate']);
    
    	return $viewData;
    }
    
    public function createTableDataDetail($data){
    	 
    	$removeKpi = array();
    	$removeArr = array();
		$timingArr = array("1", "3", "7", "14", "30", "60", "90", "w", "m");
    	foreach($timingArr as $timing){
    		foreach($removeKpi as $kpi){
    			$removeArr[] = $kpi . $timing;
    		}
    	}
    	
    	$table = array();
    	foreach($data["group"] as $time => $value){
    		foreach($value as $kpiName => $group){
    			
    			if(in_array($kpiName, $removeArr)){
    				continue;
    			}
    			foreach($group as $groupName => $v){
    				$table[$kpiName][$groupName] = $v;
    			}
    		}
    	}
    	$newTable = array();
    	
    	foreach($table as $kpiName => $groupData){
    		foreach($groupData as $groupName => $value){
	    		foreach($value as $logDate => $v){
	    			// add churn rate
	    			if(strpos($kpiName, "rr") === 0){
	    				$time = str_replace("rr", "", $kpiName);
	    				$churnRate = 100 - number_format($v, 2);
	    				if ($churnRate == 100) {
	    					$churnRate = 0;
	    				}
	    				$newTable['cr' . $time][$groupName][$logDate] = number_format($churnRate, 2);
	    			}else{
	    				$newTable[$kpiName][$groupName][$logDate] = $v;
	    			}
	    		}
    		}
    	}

    	return $newTable;
    }
    
    /**
     * Map group id to group name, name = id if config not found
     * @param unknown $lstGroup
     * @param unknown $groupName
     */
    public function mapGroupName($lstGroup, $groupName){
    	
    	$results = array();
    	foreach($lstGroup as $group){
    		
    		if(isset($groupName[$group])){
    		
    			$results[$group] = $groupName[$group];
    		}else{
    			$results[$group] = $group;
    		}
    		
    	}
    	
    	return $results;
    }
    
    public function selectGroup($gameCode, $groupArr, $dbData, $groupName){
    	
    	$number = 7;
    	$results = array();
    	if(count($groupArr) == 0){
    		// select top 7
    		foreach($dbData["group"] as $time => $kpiArr){
    			foreach($kpiArr as $kpi => $value){
    				
    				// loop all day
    				$rankArr = array();
    				$groupArr = array();
    				foreach($value as $group => $day){
    					
    					// sum total
    					$total = 0;
    					foreach($day as $log_date => $val){
    						$total += $val;
    					}
    					
    					$rankArr[$group] = $total;
    				}
    				
    				arsort($rankArr);
    				
    				$i = 0;
    				
    				foreach ($rankArr as $s => $val){
    					if($i >= $number){
    						break;
    					}
    					
    					$groupArr[$s] = $groupName[$s];
    					$i++;
    				}
    				
    				// selected top server here
    				$results[$kpi] = $groupArr;
    			}
    		}
    	} else {
    		$arr = array();
    		foreach($groupArr as $group){
    			$arr[$group] = $groupName[$group];
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