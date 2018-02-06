<?php

/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 14/03/2016
 * Time: 23:29
 */

class Package extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
		//$this->output->enable_profiler(TRUE);
        $this->load->model('game_model', 'game');
        $this->load->model('package_model', 'package');
        $this->load->library('form_validation');
        $this->load->library('util');
    }

    public function index()
    {
        $this->installed();
    }

    public function installed()
    {
        $game_code = $this->session->userdata('current_game');
        $current_user = $this->session->userdata('user');

        $selectData ['body'] ['aGames'] = $this->game->listGames();
        $selectData ['body'] ['gameCode'] = $game_code;
        $date = $_SESSION['package']['date'];
        if ($this->input->post('kpidatepicker') != "") {
            $date = $this->input->post('kpidatepicker');
        } else if ($date == "") {
            $date = date('d/m/Y', strtotime('-1 days'));
        }
        $selectData ['body'] ['day'] ['kpidatepicker'] = $date;
        $_SESSION['package']['date'] = $date;
        
        $viewData = $selectData;
        $viewData = $this->getViewData ( $viewData );
        
        $this->_template['content'] = $viewData ['body'] ['selection'] = $this->load->view('body_parts/selection/kpi_date', $selectData, TRUE);
        $this->_template['body']['aGames'] = $this->game->listGames();
        $this->_template['content'] .= $this->load->view("package/index", $viewData, TRUE);
        $this->_template['body']['title'] = "Package";
        $this->load->view('master_page', $this->_template);
    }
    
    public function getViewData($viewData){
    	$tableData = array();
    	$gameCode = $viewData ['body'] ['gameCode'];
    	$reportDate = $this->util->formatDate("d/m/Y", "Y-m-d", $viewData ['body'] ['day'] ['kpidatepicker']);
    	$dbPackageData = $this->package->getPackageData($gameCode,  $reportDate);
    	$pie = $this->groupSmallDataTogether($dbPackageData);
    	$table = $this->createTableData($dbPackageData);
    	$viewData["pie"] = $pie;
    	$newTable = $this->util->reverseData2($table, "package");
    	$viewData["table"]["data"] = $newTable;
    	$viewData['table']['title'] = "KPI Detail";
    	$viewData['table']['id'] = "package-detail";
    	$viewData['table']['exportTitle'] = $this->util->get_export_filename($gameCode, "package", $reportDate, $viewData['body']['toDate']);
    	
    	/* $viewData['table']['header'] = array(
    			"package" => "Package",
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
    	foreach($data["package"] as $time => $value){
    		foreach($value as $kpi => $package){
    			
    			foreach($package as $name => $v){
    				$table[$name][$kpi] = number_format($v);
    			}
    		}
    	}

    	return $table;
    }
    
    public function groupSmallDataTogether($dbData){
    	
    	$lstPackage = array();
    	
    	$groupThreshold = 0.1; // 10%
    	$results = array();
    	foreach($dbData["package"] as $time => $kpiArr){
    		foreach($kpiArr as $kpi => $value){
    			
    			// calculate total kpi_value
    			$sum = 0;
    			foreach($value as $package => $v){
    				$sum += $v;
    				$lstPackage[] = $package;
    			}
    			
				// determine which package need to group together     			
    			foreach($value as $package => $v){
    				if($v / $sum < $groupThreshold){
    					$results["package"][$time][$kpi]["other"] += $v;
    					$lstPackage[] = "other";
    				}else{
    					$results["package"][$time][$kpi][$package] = $v;
    				}
    			}
    		}
    	}
    	
    	$lstPackage = array_unique($lstPackage);
    	$results["colors"] = $this->util->generateColors($lstPackage);
    	return $results;
    }
}