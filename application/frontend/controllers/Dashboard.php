<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        //$this->output->enable_profiler(TRUE); // debug
        $this->load->library('util');

        $this->load->model('game_model', 'game');
        $this->load->model('dashboard_model', 'dashboard');
        $this->load->model('device_model', 'device');
        $this->load->model('kpi_model', 'kpi');
    }

    public function index()
    {
        $this->dashboard2();
    }
    public function dashboard_op(){
        $this->dashboard_bk();
    }
    public function dashboard(){
        $this->dashboard2();
    }

    public function dashboard_bk($game_code_p="")
    {
        if($game_code_p != ''){
            $this->set_everything_about_gamecode($game_code_p);
            $game_code = $game_code_p;
        }else{
            $game_code = $this->get_user_input_game_code();
        }

        $pre_game_code = $this->session->userdata('pre_game');
        $current_user = $this->session->userdata('user');
        $this->session->set_userdata('pre_game',$game_code);

        $viewData ['body'] ['aGames'] = $this->game->listGames();
        $viewData ['body'] ['gameCode'] = $game_code;
        if ($this->input->post('kpidatepicker') != "" && $game_code == $pre_game_code) {
            $date = $this->input->post('kpidatepicker');
        } else {
            if ($this->dashboard->check_report_id($game_code, "bhodd") == 1) {
                $date = date('d/m/Y');
            } else {
                $date = date('d/m/Y', strtotime('-1 days'));
            }
        }
        $viewData ['body'] ['day'] ['kpidatepicker'] = $date;
        // $_SESSION['dashboard']['date'] = $date;

        $log_date = $this->util->user_date_to_db_date($date);

        $this->_template['content'] = $viewData ['body'] ['selection'] = $this->load->view('body_parts/selection/kpi_date', $viewData, TRUE);
        $this->_template['body']['aGames'] = $this->game->listGames();
        $html['overview_data'] = $this->get_overview_data($current_user, $game_code, $log_date);
        $html['trendchart_data'] = $this->get_trendchart_data($current_user, $game_code, $log_date);

        $this->_template['content'] .= $this->load->view("dashboard/dashboard",$html, TRUE);
        $this->_template['body']['title'] = "Dashboard";
        $this->_template['body']['aGames'] = $this->game->listGames();
        $this->load->view('master_page', $this->_template);
    }

    private function get_overview_data($current_user,$game_code,$log_date)
    {
        $default_config = array("a" => "Active user", "n" => "new user", "pu" => "paying user", "gr" => "revenue");
        $timming_map = $this->util->get_timming_config();
        $timing_tab = array("4", "17", "31");

        $overview_1_data = array();
        for ($ii = 0; $ii < count($timing_tab); $ii++) {
            $timming = $timing_tab[$ii];
            $config = array();
            $kpi_keys  = array_keys($default_config);
            for ($j = 0; $j < count($kpi_keys); $j++) {
                $config[$kpi_keys[$j] . $timming_map[$timming]] = $default_config[$kpi_keys[$j]];
            }

            $fields = array_keys($config);
            $header_name = $this->util->get_header_name($fields);
            $description = $config;

            $start_date = $this->util->get_nearest_date_by_timming($log_date,$timming);

            //cheat
            if($timming!="4" and $log_date == date("Y-m-d")){
                $log_date = date("Y-m-d", strtotime($log_date) - 24*60*60);
                $db_data = $this->dashboard->overview_1($game_code,$start_date, $log_date , $fields);
            }else {
                $db_data = $this->dashboard->overview_1($game_code, $start_date, $log_date, $fields);
            }

            $display = $db_data[$log_date];

            $get_percent = $db_data[$start_date];

            $percent_arr = array();
            foreach ($display as $key => $value) {
                if ($display[$key] == 0 || $get_percent[$key] == 0) {
                    $p = 0;
                } else {
                    $p = ($display[$key] / $get_percent[$key]);
                    $p = $p * 100;
                }
                $percent_arr[$key] = $p;
            }
            $view_data = array();
            $view_data['log_date'] = $log_date;
            for ($i = 0; $i < count($fields); $i++) {
                $t_k = $fields[$i];
                $t_d['data'] = $display[$t_k];
                $t_d['kpi_id'] = $t_k;
                $t_d['kpi_name'] = strtoupper($header_name[$t_k]);
                $t_d['description'] = isset($description[$t_k]) ? $description[$t_k] : "";
                $t_d['percent'] = $percent_arr[$t_k];
                $t_d['before'] = $get_percent[$t_k];

                if ($t_d['data'] != 0)
                    $view_data['data'][$t_k] = $t_d;
            }
            $overview_1_data[$timming] = $view_data;
        }
        return $overview_1_data;
    }

    private function get_trendchart_data($current_user,$game_code,$log_date){
        $config_1 = array(
            "config" => array("times" => array("4" => "31", "17" => "12", "31" => "12"), "chart_title" => "", "left" => "revenue", "right"=>"paying user"),
            "pu" => array( "chart_type" => "spline", "y_axis" => "1"),
            "gr" => array( "chart_type" => "column", "y_axis" => "0"),
        );
        $timing_map = $this->util->get_timming_config();
        $timing_tab = array("4", "17", "31");

        $common_config = $config_1['config'];
        unset($config_1['config']);
        for ($ii = 0; $ii < count($timing_tab); $ii++) {
            $config=array();
            $config['config'] = $common_config;
            $timing = $timing_tab[$ii];
            foreach($config_1 as $key => $value){
                $config[$key.$timing_map[$timing]] = $value;
            }
            $data[$timing]['trend_chart_1'] = $this->trend_chart_1($game_code, $log_date, $config,$timing);
        }

        $config_2 = array(
            "config" => array("times" => array("4" => "31", "17" => "12", "31" => "12"), "chart_title" => "", "left" => "active user", "right" => "new user"),
            "n" => array( "chart_type" => "spline", "y_axis" => "1"),
            "a" => array( "chart_type" => "column", "y_axis" => "0")
        );
        $common_config = $config_2['config'];
        unset($config_2['config']);
        for ($ii = 0; $ii < count($timing_tab); $ii++) {
            $config=array();
            $config['config'] = $common_config;
            $timing = $timing_tab[$ii];
            foreach($config_2 as $key => $value){
                $config[$key.$timing_map[$timing]] = $value;
            }
            $data[$timing]['trend_chart_2'] = $this->trend_chart_2($game_code, $log_date, $config,$timing);
        }
        return $data;
    }




    private function trend_chart_2($game_code, $log_date, $config,$timing){
        $t_data = $this->trend_chart_1($game_code, $log_date, $config,$timing);

        $container_2_id = "container_2_" . $timing;
        $container_1_id = "container_1_" . $timing;
        $t_data['charts'][$container_2_id] = $t_data['charts'][$container_1_id];
        $t_data['charts'][$container_2_id]['id'] = $container_2_id;
        $t_data['charts'][$container_2_id]['title'] = $this->util->get_main_chart_title(array("feature"=>"Active Users","game_info"=>$this->_gameInfo),$timing);


        unset($t_data['charts'][$container_1_id]);

        return $t_data;
    }

    private function trend_chart_1($game_code, $log_date, $config,$timing)
    {
        $container_id = "container_1_" . $timing;
        $times = $config['config']['times'][$timing];
        $left_chart = $config['config']['left'];
        $right_chart = $config['config']['right'];
        unset($config['config']);
        $fields = array_keys($config);

        $stringtotime_config = $this->util->get_stringtotime_config();
        $start_date = date('Y-m-d', strtotime($log_date . " - " . $times . " " . $stringtotime_config[$timing]));

        $db_data = $this->dashboard->trend_chart_1($game_code, $start_date, $log_date, $fields, $timing);
        if (count($db_data) == 0) {
            $_SESSION['dashboard_nodata'] = 'true';
        }

        $db_data_by_field = $this->util->re_organize_db_data($db_data);
        if ($db_data) {
            $t_1 = array();
            for ($i = 0; $i < count($db_data); $i++) {
                $row = $db_data[$i];
                $t_1['columnX'][] = $this->util->get_xcolumn_by_timming($row['log_date'], $timing, true);
                for ($ii = 0; $ii < count($fields); $ii++) {
                    $t_1[$fields[$ii]][] = intval($row[$fields[$ii]]);
                }
            }
            $data_source = $this->dashboard->get_data_source_from_mt($game_code, "gr1", "game_kpi");
            $table_header_config = $this->util->get_kpi_header_name();
            $chartData[$container_id]['title'] = $this->util->get_main_chart_title(array("feature" => "Revenue", "game_info"=>$this->_gameInfo), $timing);

            $chartData[$container_id]['subTitle'] = $this->util->get_sub_chart_title(
                array("from" => $start_date, "to" => $log_date, "source" => $data_source),
                $timing);

            $chartData[$container_id]['xAxisCategories'] = $this->util->get_data_string($t_1['columnX'], "'", true);
            $chartData[$container_id]['id'] = $container_id;
            $chartData[$container_id]['yPrimaryAxisTitle'] = strtoupper($left_chart);
            $chartData[$container_id]['ySecondaryAxisTitle'] = strtoupper($right_chart);


            for ($j = 0; $j < count($fields); $j++) {
                if (isset ($db_data_by_field[$fields[$j]]) && array_sum($db_data_by_field[$fields[$j]]) != 0) {
                    $tt = array(
                        "name" => strtoupper($table_header_config[$fields[$j]]),
                        "type" => $config[$fields[$j]]['chart_type'],
                        "yAxis" => $config[$fields[$j]]['y_axis'],
                        "data" => $this->util->get_data_string($t_1[$fields[$j]])
                    );
                    $chartData[$container_id]['data'][$fields[$j]] = $tt;
                }
            }
        }

        $viewData['charts'] = $chartData;
        return $viewData;
    }

    public function choose_revenue(){
        $this->_template['content'] = $this->load->view('dashboard/parts/choose_dashboard_revenue', null, TRUE);
        $this->load->view('master_page', $this->_template);
    }

    private function check_kpi_type($type){
        $db_field_config = $this->util->db_field_config();
        foreach($db_field_config as $key => $value){
            foreach($value as $timming => $v){
                $t_k = array_keys($v);
                if(in_array($type, $t_k))
                    return $key;
            }
        }
        return "";
    }
	
    public function dashboard2()
    {
    	$pre_game_code = $this->session->userdata('pre_game');
    	$game_code = $this->session->userdata('current_game');
    	$current_user = $this->session->userdata('user');
    	$tab=$this->session->userdata('dashboard_tab');
    	if($tab==null){
    		$tab="Daily";
    	}
    	$viewData ['body'] ['selected_tab'] = $tab;
    	$viewData ['body'] ['aGames'] = $this->game->listGames();
    	$viewData ['body'] ['gameCode'] = $game_code;
    	$now = date("Y-m-d", time());
    	if ($this->input->post('kpidatepicker') != "" && $game_code == $pre_game_code) {
    		$date = $this->input->post('kpidatepicker');
            if(isset($_GET['tuonglv'])){
                var_dump($date);
            }
    	} else {
    		if ($this->dashboard->check_report_id($game_code, "bhodd") == 1) {
    			$date = date('d/m/Y');
    		} else {
    			$date = date('d/m/Y', strtotime('-1 days'));
    		}
    	}

    	$this->session->set_userdata('pre_game',$game_code);
    	//var_dump($date);die;
    	$_SESSION['dashboard']['selected_date'] = $date;
    	$viewData ['body'] ['day'] ['kpidatepicker'] = $date;
    	$viewData ['body'] ['tabs'] = array("Daily", "Weekly", "Monthly");
    	$viewData ['body'] ['api_url'] = base_url('index.php/Dashboard/renderDashboard/');
    	$log_date = $this->util->user_date_to_db_date($date);
    	
    	$this->_template['content'] = $viewData ['body'] ['selection'] = $this->load->view('body_parts/selection/kpi_date', $viewData, TRUE);
    	
    	$this->_template['body']['title'] = "Dashboard";
    	$this->_template['body']['aGames'] = $this->game->listGames();
    	$this->_template['content'] .= $this->load->view("dashboard/dashboard2",null, TRUE);
    	$this->load->view('master_page', $this->_template);
    }
    
    public function test()
    {
    	//$this->load->helper('cookie');
    	//var_dump($this->input->cookie('ci_session', false)); die;
    	//return;
    	//$overview_logdate="2016-10-02";
    	//var_dump($this->util->getStartDateIn ( $overview_logdate, "17" ));die();
    	$this->output->set_content_type('application/json');
    	$game_code = $this->session->userdata('current_game');
    	$current_user = $this->session->userdata('user');
    	$start_date="2016-10-12";
    	$fields=array("a1","gr1","n1","pu1","nnpu1","nrr1");
    	$timing =4;
    	$db_data = $this->getRealtimeData($game_code, $start_date,$fields);
    	$db_data_by_field = $this->util->re_organize_db_data($db_data);
    	$data2 = $db_data_by_field;
    	foreach ($db_data_by_field as $key => $value )
    	{
    		if($key!="log_date"){
    			
    			$val2=$value[count($value)-1];
    			$d2 = $db_data_by_field['log_date'][count($value)-1];
    			$val1=$val2;
    			$d1 =$d2;
    			if(count($value)>2){
    				$val1=$value[count($value)-2];
    				$d1 = $db_data_by_field['log_date'][count($value)-2];
    			}
    			
    			$trend = 100*($val2 - $val1) / $val1;
    			$trend_result = array();
    			$trend_result['selected_date'] = $d2;
    			$trend_result['prev_date'] = $d1;
    			$trend_result['selected_value'] = $val2;
    			$trend_result['prev_value'] = $val1;
    			$trend_result['trend'] = $trend;
    			
    			$data2["trend"][$key] = $trend_result;
    			
    		}
    	}
    	$table_header_config = $this->util->get_kpi_header_name();
    	
    	$this->output->set_output(json_encode($db_data));
    }
    
    private function createViewData($t){
    	$game_code = $this->session->userdata('current_game');
    	$current_user = $this->session->userdata('user');
    	$this->session->set_userdata('dashboard_tab',$t);
    	$fields=array("a1","gr1");
    	$kpiFields=array("am","grm","pum","nm","npum");
    	$gameInfo = $this->game->findGameInfo($game_code);
    	//var_dump($gameInfo);
    	$viewData ['body'] ['aGames'] = $this->game->listGames();
    	$viewData ['body'] ['gameCode'] = $game_code;
    	$viewData ['body'] ['gameName'] = $gameInfo['GameName'];
    	$date = $_SESSION['dashboard']['selected_date'];
    	$log_date = $this->util->user_date_to_db_date($date);
    	$now = date("Y-m-d", time());
    	$yesterday = date("Y-m-d", strtotime($now) - 24*60*60);
    	$timing_text="day";
    	switch($t){
    		default:
    		case "Daily":	// 3: weekly
    			$fields=array("a1","gr1");
    			$timing=4;
    			$start_date = date('Y-m-d', strtotime('-14 day '. $log_date));
    			$kpiFields=array("a1","gr1","pu1","n1","npu1");
    			$viewData ['body'] ['suffix']="1";
    			
    			break;
    		case "Weekly":	// 3: weekly
    			$fields=array("aw","grw");
    			$timing=17;
    			$start_date = date('Y-m-d', strtotime('-98 day '. $log_date));
    			$kpiFields=array("aw","grw","puw","nw","npuw");
    			$viewData ['body'] ['suffix']="w";
    			$timing_text="week";
    			break;
    		case "Monthly":	// 4: monthly
    			$fields=array("am","grm");
    			$timing=31;
    			$kpiFields=array("am","grm","pum","nm","npum");
    			$start_date = date('Y-m-d', strtotime('-360 day '. $log_date));
    			$viewData ['body'] ['suffix']="m";
    			$timing_text="month";
    			break;
    	}
    	$kpi_data = $this->dashboard->getData2($game_code, $start_date, $log_date, $kpiFields, $timing);
    	
    	$kpi_data_w_trend = array();
    	$count = count($kpi_data);
    	for($i=0;$i < $count;$i++){
    		$kpi_data_w_trend["log_date"][] = $kpi_data[$i]["log_date"];
    			
    		foreach($kpiFields as $k => $kname){
    			$kvalue=$kpi_data[$i][$kname];
    			if($kvalue==null){
    				$kvalue="0";
    			}
    			$kpi_data_w_trend[$kname][]=$kvalue;
    		}
    	}
    	
    	
    	$kpi_last_date =$log_date;
    	
    	foreach ($kpi_data_w_trend as $key => $value )
    	{
    		if($key!="log_date"){
    			 
    			$val2=$value[count($value)-1];
    			$d2 = $kpi_data_w_trend['log_date'][count($value)-1];
    			//$last_date =$d2;
    			$val1=0;
    			//$d1 =$d2;
    			if(count($value)>=2){
    				$val1=$value[count($value)-2];
    				$d1 = $kpi_data_w_trend['log_date'][count($value)-2];
    			}
    			if($val1!=0){
    				$trend = 100*($val2) / $val1;
    			}else{
    				$trend=0;
    				$d1="";
    			}
    			$trend_result = array();
    			$trend_result['selected_date'] = $d2;
    			$trend_result['prev_date'] = $d1;
    			$trend_result['selected_value'] = $val2;
    			
    			$trend_result['prev_value'] = $val1;
    			$trend_result['trend'] = number_format ( $trend);
    			if (strpos($key, 'gr') !== false) {
    				$trend_result['kpi_name'] ="Revenue (VND)";
    			}else if (strpos($key, 'npu') !== false) {
    				$trend_result['kpi_name'] ="New PayingUsers";
    			}else if (strpos($key, 'pu') !== false) {
    				$trend_result['kpi_name'] ="PayingUsers";
    			}else if (strpos($key, 'nrr') !== false) {
    				$trend_result['kpi_name'] ="Retention (%)";
    			}else if (strpos($key, 'n') !== false) {
    				$trend_result['kpi_name'] ="New Users";
    			}else if (strpos($key, 'a') !== false) {
    				$trend_result['kpi_name'] ="Active Users";
    			}
    			
    			if($now == $d2){
    				$trend_result['prev_date_text'] = "Yesterday";
    				$trend_result['selected_date_text'] = "Today";
    			}else{
    				$trend_result['prev_date_text'] = "Previous " . $timing_text;
    				$trend_result['selected_date_text'] = $d2;
    			}
    			$kpi_data_w_trend["trend"][$key] = $trend_result;
    		}
    	}
    	
    	$textDates = array();
    	for ($i = 0; $i < count($kpi_data_w_trend['log_date']); $i++) {
    		$textDates[] = $this->util->get_xcolumn_by_timming($kpi_data_w_trend['log_date'][$i], $timing, true);
    		$kpi_last_date= $kpi_data_w_trend['log_date'][$i];
    	}
    	$kpi_data_w_trend['text_dates'] = $textDates;
    	ksort($kpi_data_w_trend);
    	$viewData['body']['kpi_data'] = $kpi_data_w_trend;
    	
    	$gamePlatform = $this->dashboard->game_info[$game_code]["GameType2"];
    	
    	$dates = $this->dashboard->getDates($start_date, $log_date, $timing);
    	if($gamePlatform == 2){
    		
	    	$os_data = $this->device->get_totalOs($game_code,$fields,$dates);
	    	$textOsDates = array();
	    	
	    	for ($i = 0; $i < count($os_data['dates']); $i++) {
	    		$textOsDates[] = $this->util->get_xcolumn_by_timming($os_data['dates'][$i], $timing, true);
	    		$viewData['body']['has_os_data'] = $textOsDates;
	    	}
	    	$os_data['text_dates']=$textOsDates;
	    	$viewData['body']['os_data'] = $os_data;
	    	
	    	if(count($textOsDates)>=1){
	    		$viewData['body']['os_start_time_text'] = $textOsDates[0];
	    	}else{
	    		$viewData['body']['os_start_time_text'] = $this->util->get_xcolumn_by_timming( $log_date, $timing, true);
	    	}
	    	$viewData['body']['os_end_time_text'] =$textOsDates[count($textOsDates)-1];
    	}
    	$viewData['body']['timing'] = $timing;
    	
    	
    	switch($t){
    		default:
    		case "Daily":	// 3: weekly
    			$viewData['body']['selected_date']=$log_date;
    			
    			if($now==$kpi_last_date){
    				$viewData['body']['selected_date_text'] = "today " ."(" . $this->util->get_xcolumn_by_timming($kpi_last_date, $timing, true) .")";
    			}else{
    				$viewData['body']['selected_date_text'] =$this->util->get_xcolumn_by_timming($kpi_last_date, $timing, true);
    			}
    			break;
    		case "Weekly":	// 3: weekly
    			$viewData['body']['selected_date_text'] = $this->util->get_xcolumn_by_timming($kpi_last_date, $timing, true);;
    			$timing_text="week";
    			$viewData['body']['selected_date']=$kpi_last_date;
    			break;
    		case "Monthly":	// 4: monthly
    			$viewData['body']['selected_date_text'] = $this->util->get_xcolumn_by_timming($kpi_last_date, $timing, true);
    			$viewData['body']['selected_date']=$kpi_last_date;
    			$timing_text="month";
    			break;
    	}
    	if(count($textDates)>1){
    		$viewData['body']['kpi_start_time_text'] = $textDates[0];
    	}else{
    		$viewData['body']['kpi_start_time_text'] = $this->util->get_xcolumn_by_timming( $kpi_last_date, $timing, true);
    	}
    	$viewData['body']['kpi_end_time_text'] =$textDates[count($textDates)-1];
    	
    	
    	return $viewData;
    }
    
    public function renderDashboard($t){
    	
    	$viewData = $this->createViewData($t);
    	//$this->output->set_output(json_encode($viewData['body']['kpi_data']));
    	//$this->output->set_content_type('application/json');
    	//return;
    	$this->_template['content'] .= $this->load->view("dashboard/renderDashboard", $viewData, TRUE);
    	$this->load->view('render_html', $this->_template);
    }
    
}


/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */