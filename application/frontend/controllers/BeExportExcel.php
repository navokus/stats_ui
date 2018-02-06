<?php
/**
 * @date 2016-05-16
 * @author canhtq
 *
 */
class BeExportExcel extends CI_Controller
{

    private $class_name = "BeExportExcel";

    public function __construct()
    {
        parent::__construct();
        //$this->output->enable_profiler ( TRUE );
        $this->load->library('email');
        $this->load->model('game_model', 'game');
        $this->load->model('user_model', 'user');
        $this->load->model('kpi_model', 'kpi');
        $this->load->library('form_validation');
        $this->load->library('util');
        $this->load->model('dashboard_model', 'dashboard');
    }

    public function get_report_key()
    {
    	$all = $this->util->get_all_kpi();
    	return $all;
    	
    }
    private function remove_game_kpi_all_day_zero($data){
    	$db_data_by_field = $this->util->re_organize_db_data($data);
    	$key_sets = array_keys($db_data_by_field);
    	foreach ($key_sets as $k) {
    		if (array_sum($db_data_by_field[$k]) == 0) {
    			foreach ($data as $key => $value) {
    				unset($data[$key][$k]);
    			}
    		}
    	}
    	return $data;
    }
    private function remove_os_kpi_all_day_zero($data){
    	$re_organize = array();
    	$os_list = $this->util->get_os_list();
    	$count = count($data);
    	for($i=0;$i < $count;$i++){
    		foreach($os_list as $os){
    			foreach($data[$i][$os] as $kpi_code => $kpi_value){
    				$re_organize[$kpi_code][] = $kpi_value;
    			}
    		}
    	}
    	foreach($re_organize as $kpi_code => $data_sum){
    		$sum = array_sum($data_sum);
    		if($sum == 0){
    			for($i=0;$i < $count;$i++){
    				foreach($os_list as $os){
    					unset($data[$i][$os][$kpi_code]);
    				}
    			}
    		}
    	}
    	return $data;
    }
    private function sort_export_by_kpi_id($data)
    {
    	$all_kpi_code = $data[0];
    	unset($all_kpi_code['log_date']);
    	$all_kpi_code = array_keys($all_kpi_code);
    	$all_kpi_id = $this->kpi->getKpiIDs(null, $all_kpi_code);
    	ksort($all_kpi_id);
    	$new = array();
    	for ($i = 0; $i < count($data); $i++) {
    		$t = array();
    		$t['log_date'] = $data[$i]['log_date'];
    		foreach ($all_kpi_id as $kpi_id => $kpi_code) {
    			$t[$kpi_code] = $data[$i][$kpi_code];
    		}
    		$new[] = $t;
    	}
    	return $new;
    }
    
    private function sort_os_export_by_kpi_id($data)
    {
    	$all_kpi_code = $data[0]['android'];
    	$all_kpi_code = array_keys($all_kpi_code);
    	$all_kpi_id = $this->kpi->getKpiIDs(null, $all_kpi_code);
    	ksort($all_kpi_id);
    	$os_list = array("android", "ios", "other");
    	$new = array();
    	for ($i = 0; $i < count($data); $i++) {
    		$t = array();
    		$t['log_date'] = $data[$i]['log_date'];
    		for ($j = 0; $j < count($os_list); $j++) {
    			foreach ($all_kpi_id as $kpi_id => $kpi_code) {
    				$t[$os_list[$j]][$kpi_code] = $data[$i][$os_list[$j]][$kpi_code];
    			}
    		}
    		$new[] = $t;
    	}
    	return $new;
    }
    private function get_data_report($fromDate, $toDate, $gameCode, $kpi_type)
    {
    	$table_config = array(
    			"game" => "game_kpi",
    			"os" => "os_kpi",
    			"channel" => "channel_kpi",
    			"package" => "package_kpi"
    	);
    
    	 
    	$all_kpi_code = $this->get_report_key();
    	$kpi_set = array_keys($all_kpi_code);
    
    	if ($kpi_type != "os") {
    		$t_2 = $this->kpi->get_export_datatable($fromDate, $toDate, $gameCode, $table_config[$kpi_type], $kpi_set, $table_config[$kpi_type]);
    
    		$t_2 = $this->util->calculate_report_not_in_database($gameCode, $t_2, $kpi_set);
    		$t_2 = $this->util->remove_game_kpi_not_display($t_2);
    		$t_2 = $this->remove_game_kpi_all_day_zero($t_2);
    		$t_2 = $this->util->sort_data_table($t_2, 4, true);
    		$t_2 = $this->sort_export_by_kpi_id($t_2);
    		$header_key_sets = array_keys($t_2[0]);
    	} else {
    		$t_2 = $this->kpi->get_os_export_datatable($fromDate, $toDate, $gameCode, $kpi_set, "os_kpi");
    
    		$t_2 = $this->util->calculate_os_report_not_in_database($gameCode, $t_2, $kpi_set);
    		$t_2 = $this->util->remove_os_kpi_not_display($t_2);
    		$t_2 = $this->remove_os_kpi_all_day_zero($t_2);
    		$t_2 = $this->sort_os_export_by_kpi_id($t_2);
    		//$header_key_sets = array_keys($t_2[0]['android']);
    	}
    	 
    	 
    
    	return $t_2;
    }
    
    
    public function exportOsExelFile($gameCode,$fromDate,$toDate,$kpiArray){
    	
    	require_once APPPATH . "/third_party/PHPExcel.php";
    	$objPHPExcel = new PHPExcel();
    	$objPHPExcel->setActiveSheetIndex(0);
    	$activeSheet = $objPHPExcel->getActiveSheet();
    	
    	//$gameCode ="3qmobile";
    	//$fromDate= "2017-01-01";
    	//$toDate= "2017-01-30";
    	$kpiFields = explode("-", $kpiArray);
    	//$kpiFields=array("a1","gr1","pu1");
    	
    	
    	$kpi_data = $this->get_data_report($fromDate,$toDate,$gameCode, "os");
    	$tableData = array();
    	$row=2;
    	
    	$groups=array("ios","android","other");
    	
    	for ($i = 0; $i <= count($kpi_data); $i++) {
    		$logDate=$kpi_data[$i]["log_date"];
    		$col=0;
    		$activeSheet->setCellValueByColumnAndRow($col, $row, $logDate);
    		$col++;
    		foreach ($groups as $group) {
    			$groupData = $kpi_data[$i][$group];
    			
    			$cValue="0";
    			if($groupData!=null){
    			foreach ($kpiFields as $kpi) {
    				if($groupData[$kpi]!=null){
    					$cValue=$groupData[$kpi];
    				}
    				$activeSheet->setCellValueByColumnAndRow($col, $row, $cValue);
    				$col++;
    			}
    			}
    			
    		}
    		$row++;
    		
    	}
    	//header
    	
    	$row=1;
    	$col=1;
    	$activeSheet->setCellValueByColumnAndRow(0, $row, "date");
    	foreach ($groups as $group) {
    	foreach ($kpiFields as $kpi) {
    		$cValue = $group . "_". $kpi;
    		
    		$activeSheet->setCellValueByColumnAndRow($col, $row, $cValue);
    		$col++;
    	}
    	}
    	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    	$filePath ="/tmp/os-".$gameCode."-to-".$fromDate."-".$toDate .".xls";
    	$objWriter->save($filePath); // saving the excel file
    	$this->output->set_content_type('application/json');
    	$this->output->set_output(json_encode("ok==>".$filePath));
    }
    
    public function exportGameExelFile($gameArray,$fromDate,$toDate,$kpiArray,$fileName){
    	 
    	require_once APPPATH . "/third_party/PHPExcel.php";
    	$objPHPExcel = new PHPExcel();
    	$objPHPExcel->setActiveSheetIndex(0);
    	$activeSheet = $objPHPExcel->getActiveSheet();
    	 
    	//$gameCode ="3qmobile";
    	//$fromDate= "2017-01-01";
    	//$toDate= "2017-01-30";
    	$kpiFields = explode("-", $kpiArray);
    	//$kpiFields=array("a1","gr1","pu1");
    	$row=2;
    	
    	$games = explode("-", $gameArray);
        $kpiCnf = $this->util->get_all_kpi();
    	foreach ($games as $gameCode) {
    		
    		$kpi_data = $this->get_data_report($fromDate,$toDate,$gameCode, "game");
    		$tableData = array();    	
    		$len = count($kpi_data);
    		$gameInfo = $this->game->getFullGameInfo($gameCode);
    		
    		for ($i = 0; $i <$len ; $i++) {
    			$logDate=$kpi_data[$i]["log_date"];
    			$col=0;
    			
    			$activeSheet->setCellValueByColumnAndRow($col, $row, $gameCode);
    			$col++;
    			
    			$activeSheet->setCellValueByColumnAndRow($col, $row, $gameInfo["GameName"]);
    			$col++;

                $activeSheet->setCellValueByColumnAndRow($col, $row, $gameInfo["platform"]);
                $col++;

                $activeSheet->setCellValueByColumnAndRow($col, $row, $gameInfo["owner"]);
                $col++;

    			$activeSheet->setCellValueByColumnAndRow($col, $row, $logDate);
    			$col++;
    			 
    			$data = $kpi_data[$i];
    		
    			$cValue="0";
    			if($data!=null){
    		
    				foreach ($kpiFields as $kpi) {
                        $cValue="0";
    					if($data[$kpi]!=null){
    						$cValue=$data[$kpi];
    					}
    					$activeSheet->setCellValueByColumnAndRow($col, $row, $cValue);
    					$col++;
    				}
    			}
    		
    			 
    			$row++;
    		
    		}
    	}
    	
    	//header
    	 
    	$row=1;
    	$col=0;
    	$activeSheet->setCellValueByColumnAndRow($col, $row, "game");
    	$col++;
    	$activeSheet->setCellValueByColumnAndRow($col, $row, "game_name");
    	$col++;

        $activeSheet->setCellValueByColumnAndRow($col, $row, "platform");
        $col++;

        $activeSheet->setCellValueByColumnAndRow($col, $row, "dept");
        $col++;

    	$activeSheet->setCellValueByColumnAndRow($col, $row, "date");
    	$col++;
        foreach ($kpiFields as $kpi) {
            $cValue = $kpiCnf[$kpi];

            $activeSheet->setCellValueByColumnAndRow($col, $row, $cValue);
            $col++;
        }
    	
    	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if($fileName==null or $fileName==""){
            $fileName = "game-".$gameArray."-to-".$fromDate."-".$toDate ;
        }

    	$filePath ="/tmp/".$fileName .".xls";
    	$objWriter->save($filePath); // saving the excel file
    	$this->output->set_content_type('application/json');
    	$this->output->set_output(json_encode("ok==>".$filePath));


    }


    public function exportGameExelFile2($games,$fromDate,$toDate,$kpiArray,$fileName){

        require_once APPPATH . "/third_party/PHPExcel.php";
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $activeSheet = $objPHPExcel->getActiveSheet();

        $kpiFields = explode("-", $kpiArray);
        $row=2;

        $kpiCnf = $this->util->get_all_kpi();
        foreach ($games as $gameCode) {

            $kpi_data = $this->get_data_report($fromDate,$toDate,$gameCode, "game");
            $tableData = array();
            $len = count($kpi_data);
            $gameInfo = $this->game->getFullGameInfo($gameCode);

            for ($i = 0; $i <$len ; $i++) {
                $logDate=$kpi_data[$i]["log_date"];
                $col=0;

                $activeSheet->setCellValueByColumnAndRow($col, $row, $gameCode);
                $col++;

                $activeSheet->setCellValueByColumnAndRow($col, $row, $gameInfo["GameName"]);
                $col++;

                $activeSheet->setCellValueByColumnAndRow($col, $row, $gameInfo["platform"]);
                $col++;

                $activeSheet->setCellValueByColumnAndRow($col, $row, $gameInfo["owner"]);
                $col++;

                $activeSheet->setCellValueByColumnAndRow($col, $row, $logDate);
                $col++;

                $data = $kpi_data[$i];

                $cValue="0";
                if($data!=null){

                    foreach ($kpiFields as $kpi) {
                        $cValue="0";
                        if($data[$kpi]!=null){
                            $cValue=$data[$kpi];
                        }
                        $activeSheet->setCellValueByColumnAndRow($col, $row, $cValue);
                        $col++;
                    }
                }


                $row++;

            }
        }

        //header

        $row=1;
        $col=0;
        $activeSheet->setCellValueByColumnAndRow($col, $row, "game");
        $col++;
        $activeSheet->setCellValueByColumnAndRow($col, $row, "game_name");
        $col++;

        $activeSheet->setCellValueByColumnAndRow($col, $row, "platform");
        $col++;

        $activeSheet->setCellValueByColumnAndRow($col, $row, "dept");
        $col++;

        $activeSheet->setCellValueByColumnAndRow($col, $row, "date");
        $col++;
        foreach ($kpiFields as $kpi) {
            $cValue = $kpiCnf[$kpi];

            $activeSheet->setCellValueByColumnAndRow($col, $row, $cValue);
            $col++;
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if($fileName==null or $fileName==""){
            $fileName = "game-".$fromDate."-".$toDate ;
        }

        $filePath ="/tmp/".$fileName .".xls";
        $objWriter->save($filePath); // saving the excel file
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode("ok==>".$filePath));


    }

    public function exportGst($date){

        $fromDate=$date;
        $toDate=$date;
        //$gameArray="3qmobile-cfgfbs1-cgmbgfbs1-cgmfbs-dptk-ftgfbs2-gnm-htc-icamfbs2-kftl-kvtm-nikki-nlmb-pmcl-pv3d-siamplay-tfzfbs2-tlbbm";
        $gameArray="ftgfbs2-cgmfbs-icamfbs2-tfzfbs2-cgmbgfbs1-cfgfbs1-10ha7ifbs1";
        $kpiArray="aa1-pa1-apcu1-ppcu1-a30-nm-am-pum-npum-grm";
        $this->exportGameExelFile($gameArray,$fromDate,$toDate,$kpiArray,"gst-games-".$date);
        $kpiArray="pu1-gr1";
        $fromDate = date('01-m-Y', strtotime($date));
        $this->exportGameExelFile($gameArray,$fromDate,$toDate,$kpiArray,"gst-details-".$date);
    }

    public function exportMonthlyMobile($date){

        $fromDate=$date;
        $toDate=$date;
        $games = $this->game->listGamesByPlatform(2);
        $gameArray ="";
        foreach ($games as $game) {
            $gameArray= $gameArray ."-".$game["GameCode"];
        }
        $gameArray= $gameArray . "#";
        $gameArray =str_replace("-#","",$gameArray);
        //$gameArray="3qmobile-cfgfbs1-cgmbgfbs1-cgmfbs-dptk-ftgfbs2-gnm-htc-icamfbs2-kftl-kvtm-nikki-nlmb-pmcl-pv3d-siamplay-tfzfbs2-tlbbm";
        //$gameArray="ftgfbs2-cgmfbs-icamfbs2-tfzfbs2-cgmbgfbs1-cfgfbs1-10ha7ifbs1";
        $kpiArray="aa1-pa1-am-pum-grm-arppum-apcu1-ppcu1-a30-npum";
        $this->exportGameExelFile($gameArray,$fromDate,$toDate,$kpiArray,"mobiles-games-".$date);
    }


    public function exportMonthlyByUser($userName,$date){

        $fromDate=$date;
        $toDate=$date;
        $games = $this->user->getGameList($userName);
        $gameArray =array();
        foreach ($games as $game) {
            $gameArray[]= $game["game_code"];
        }
        $kpiArray="aa1-a30-am-pa1-pu30-pum-grm-gr30-n30-npum-aacu1-apcu1-ppcu1";
        $file=$userName."-games-"."-".$date;
        $this->exportGameExelFile2($gameArray,$fromDate,$toDate,$kpiArray,$file);
        $emails_test = array("canhtq@vng.com.vn");
        $mail_config = array(
            "from" => "kpi.stats@vng.com.vn",
            "fromalias" => "Export Data",
            "to" => $emails_test,
            "subject" => "Export Data" . $date,
            "message" => "Export Data",
            "attach" => array("/tmp/".$file.".xls"),
        );
        $result = $this->util->send_mail($mail_config);

    }

    public function monthlyReport($date){

        $fromDate=$date;
        $toDate=$date;
        $games = $this->user->getGameList("canhtq");
        $gameArray =array();
        foreach ($games as $game) {
            $gameArray[]= $game["game_code"];
        }
        $kpiArray="aa1-a30-am-pa1-pu30-pum-grm-gr30-n30-npum-aacu1-apcu1-ppcu1";
        $file="monthly-report-".$date;
        $this->exportGameExelFile2($gameArray,$fromDate,$toDate,$kpiArray,$file);
        $emails_test = array("canhtq@vng.com.vn");
        $mail_config = array(
            "from" => "stats.monthly.report@vng.com.vn",
            "fromalias" => "Monthly Report",
            "to" => $emails_test,
            "subject" => "monthly-report" . $date,
            "message" => "monthly-report",
            "attach" => array("/tmp/".$file.".xls"),
        );
        $result = $this->util->send_mail($mail_config);

    }
}