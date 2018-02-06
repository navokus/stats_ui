<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class GameGroup extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        //$this->output->enable_profiler(TRUE); // debug
        $this->load->model('kpi_model', 'kpi');
        $this->load->model('user_model', 'user');
        $this->load->library('form_validation');
        $this->load->library('util');
        $this->load->model('dashboard_model', 'dashboard');
    }
	public function test(){
		var_dump($this->game->getListByOwner("pg2"));
		die();
	}

    public function get_report_key()
    {
        $gets = array("a1");
        $all = $this->util->get_all_kpi();
        $return = array();
        foreach ($gets as $k) {
            $return[$k] = $all[$k];
        }
        return $return;

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

    public function report()
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
        $viewData ['body'] ['api_url'] = base_url('index.php/GameGroup/renderReport');
    	
    	$date = date('Y-m-d');
    	$yesterday = date ( 'Y-m-d', strtotime( $date . '-1 day' ) );
        $current_user = $this->session->userdata('user');
    	
    	//var_dump($current_user);
    	$viewData ['body'] ['groups'] = $this->user->getOwners($current_user);
    	//load master view
    	$viewData ['body'] ['dates'] = $this->util->getLastDaysOfMonths($yesterday,6,false);
    	
    	$this->_template['content'] .= $this->load->view("gamegroup_report/report", $viewData, TRUE);
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
	
    
    public function renderReport($group,$selectedDate){
    	$kpi=16031;
    	$yesterday = date ( 'Y-m-d', strtotime( date('Y-m-d') . '-1 day' ) );
    	if($selectedDate==""){
    		$date = $yesterday;
    	}else{
    		$date = $selectedDate;
    	}
        $current_user = $this->session->userdata('user');
    	$nTop =120;
    	$games=array();
    	if($group=="all"){
    		$games  = $this->user->getGameListByOwner($current_user,null);
    	}else{
    		$nTop=0;
    		$games  = $this->user->getGameListByOwner($current_user,$group);
    	}
    	$gameCodes = $this->getGameCodes($games);
    	if(count($gameCodes)<=0){
    		return;
    	}
    	$kpiData = $this->kpi->getGameKpi($gameCodes,$kpi,$date,$nTop);
    	
    	
    	//var_dump($kpiData);
    	//die();
    	
    	$total=0;
    	foreach ( $kpiData as $key => $value ) {
    		$total+=$value['kpi_value'];
    		$kpiData[$key]["mil_value"] = $value['kpi_value']/1000000;
    	}
    	$viewData['body']['data'] = $kpiData;
    	$viewData['body']['total'] = $total;
    	$viewData['body']['kpi'] = $kpi;
    	$viewData['body']['type'] = $group;
    	$viewData['body']['type_name'] = $group;
    	$viewData['body']['month'] = $this->util->formatDate("Y-m-d","M",$date) .", " . $this->util->formatDate("Y-m-d","Y",$date);
    	$viewData['body']['to_date'] = $this->util->formatDate("Y-m-d","d M,Y",$date);
    	$viewData['body']['from_date'] = $this->util->formatDate("Y-m-d","1 M,Y",$date);
    	$this->_template['content'] .= $this->load->view("gamegroup_report/renderHtml", $viewData, TRUE);
    	$this->load->view('render_html', $this->_template);
    }
    
    public function trackingRevenueTop($type,$date){
    	switch ($type){
    		case "pc":
    			break;
    	}
    }
}

