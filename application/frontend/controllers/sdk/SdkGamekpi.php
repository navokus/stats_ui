<?php

/**
 * Created by IntelliJ IDEA.
 * User: quangctn
 * Date: 09/10/2017
 * Time: 14:50
 */
class SdkGamekpi extends MY_Controller
{
    public function __construct()
    {
        $this->source_menu="sdk";
        parent::__construct();
        $this->load->model("sdk/sdk_kpi_model", "kpi");
        $this->load->library('kpiconfig');

    }

    public function daily()
    {
        if ($this->input->post('daterangepicker') != "") {

            $date=$viewData['body']['day']['default_range_date'] =  $this->input->post('daterangepicker');

        } else {
            $date=$viewData['body']['day']['default_range_date'] = date('d/m/Y', strtotime("-31 days")) . " - " . date('d/m/Y', strtotime("-1 days"));

        }
        $t1 = explode("-", $date);
        $t2 = explode("/", trim($t1[0]));
        $sDate = $t2[2] . "-" . $t2[1] . "-" . $t2[0];
        $t3 = explode("/", trim($t1[1]));
        $eDate = $t3[2] . "-" . $t3[1] . "-" . $t3[0];


//        $viewData ['body'] ['day'] ['kpidatepicker'] = date('d-m-Y', strtotime($sDate));
//        $sDate = date('Y-m-d', strtotime($sDate));
//        $eDate = date('Y-m-d', strtotime('-7 days ' . $sDate));
        $lstDate = $this->util->getDateFromRange($sDate,$eDate);
        $game_code = $this->session->userdata('current_game');
        $viewData ['body'] ['aGames'] = $this->game->listGames();
        $viewData ['body'] ['gameCode'] = $game_code;
        $this->_template['body']['title'] = "Daily Report";

        $gameInfo = $this->game->findGameInfo($game_code);


        $this->_template['content'] = $viewData ['body'] ['selection'] = $this->load->view('sdk/body_parts/selection/range_date', $viewData, TRUE);

        $viewData['section'] = array("Daily" => '16001,10001');
        foreach ($viewData['section'] as $key => $value) {
            $kpiChart = explode(',', $value);
        }

        foreach ($lstDate as $date){
            $lstDateFormat[$date]=$this->util->get_xcolumn_by_timming($date,6,true);
        }


        $viewData['dataChart'] = $this->kpi->getDataKpi($game_code, $kpiChart, $lstDate);
       /* var_dump("kinquang");
        var_dump($viewData['dataChart']);*/
        /*$lstGame = array('3qmobile', 'bklr', 'cack', 'hpt', 'nikki', 'stct');*/
        $lstKpi = $this->kpiconfig->getListKpiByGameCode($game_code, 'daily');
        //var_dump($lstKpi);exit();
        //$lstDate = array('2017-04-01','2017-03-31');
        $viewData['timming'] = 4;
        $viewData['kpiTable'] = $lstKpi;

        $viewData['lstDate'] = $lstDateFormat;
        $viewData['data'] = $this->kpi->getDataKpi($game_code, array_keys($lstKpi), $lstDate);
        $viewData['body']['game_info'] = $gameInfo;

        $this->_template['body']['aGames'] = $this->game->listGames();

        $this->_template['content'] .= $this->load->view("sdk/GameKpi/report_game_page", $viewData, TRUE);
        $this->load->view('master_page', $this->_template);

    }

    public function weekly()
    {
        if ($this->input->post('options')) {
            $_SESSION[$this->class_name]['post'] = $_POST;
        }

        $viewData['body']['optionsWeek'] = $this->util->listOptionsWeek1(3);
        $viewData = $this->getData($viewData);

        if ($this->input->post('week')) {
            $data = $this->input->post('week');
            $sDate  = $viewData['body']['week'][2]=$data[2];
            $eDate   = $viewData['body']['week'][1]=$data[1];

        }else{
            $sDate  = $viewData['body']['week'][2];
            $eDate= $viewData['body']['week'][1];
        }

//        var_dump($sDate);
//        var_dump($eDate);
//        $timing = $viewData['body']['timing'];



//        $viewData ['body'] ['day'] ['kpidatepicker'] = date('d-m-Y', strtotime($sDate));
//        $sDate = date('Y-m-d', strtotime($sDate));
//        $eDate = date('Y-m-d', strtotime('-49 days ' . $sDate));
        /*$sDate = "2017-10-09";*/
//        $lstDateTmp = $this->util->getDateFromRange($sDate, $eDate);
//        $lstDate = array();
//        foreach ($lstDateTmp as $date) {
//            if ($this->util->isWeekend($date)) {
//                $lstDate[] = $date;
//            }
//        }
//        var_dump($lstDate);
//        if (!(strcmp($lstDate[count($lstDate) - 1], $sDate) == 0)) {
//            $lstDate[count($lstDate)] = $sDate;
//        }

        $lstDate=$this->util->getDates($sDate, $eDate,17);

        $game_code = $this->session->userdata('current_game');
        $viewData ['body'] ['aGames'] = $this->game->listGames();
        $viewData ['body'] ['gameCode'] = $game_code;
        $this->_template['body']['title'] = "Weekly Report";

        $this->_template['content'] = $viewData ['body'] ['selection'] = $this->load->view('sdk/body_parts/selection/range_week', $viewData, TRUE);

        $viewData['section'] = array("Weekly" => '16017,10017');
        foreach ($viewData['section'] as $key => $value) {
            $kpiChart = explode(',', $value);
        }

        foreach ($lstDate as $date){
            $lstDateFormat[$date]=$this->util->get_xcolumn_by_timming($date,17,true);
        }


        $viewData['dataChart'] = $this->kpi->getDataKpi($game_code, $kpiChart, $lstDate);


        /*$lstGame = array('3qmobile', 'bklr', 'cack', 'hpt', 'nikki', 'stct');*/
        $lstKpi = $this->kpiconfig->getListKpiByGameCode($game_code, 'weekly');
        //$lstDate = array('2017-04-01','2017-03-31');
        $viewData['timming'] = 17;
        $viewData['kpiTable'] = $lstKpi;

        $viewData['lstDate'] = $lstDateFormat;
        $viewData['data'] = $this->kpi->getDataKpi($game_code, array_keys($lstKpi), $lstDate);

        $this->_template['body']['aGames'] = $this->game->listGames();
        $this->_template['content'] .= $this->load->view("sdk/GameKpi/report_game_page", $viewData, TRUE);
        $this->load->view('master_page', $this->_template);

    }

    public function monthly()
    {

        $viewData['body']['optionsMonth'] = $this->util->listOptionsMonth(3);
        $viewData = $this->getData($viewData);

        if ($this->input->post('month')) {
            $data = $this->input->post('month');
            $sDate = $viewData['body']['month'][2]=$data[2];
            $eDate = $viewData['body']['month'][1]=$data[1];
        }else{
            $sDate = $viewData['body']['month'][2];
            $eDate = $viewData['body']['month'][1];
        }


//        $viewData ['body'] ['day'] ['kpidatepicker'] = date('d-m-Y', strtotime($sDate));
//        $sDate = date('Y-m-d', strtotime($sDate));
//        $eDate = date('Y-m-d', strtotime('-1 year' . $sDate));
        /*$sDate = "2017-10-09";*/
//        $lstDate = $this->util->get_months($sDate, $eDate);

        $lstDate=$this->util->getDates($sDate, $eDate,31);
        $lstDate=array_unique($lstDate);
        foreach ($lstDate as $date){
            $lstDateFormat[$date]=$this->util->get_xcolumn_by_timming($date,31,true);
        }
        $game_code = $this->session->userdata('current_game');
        $viewData ['body'] ['aGames'] = $this->game->listGames();
        $viewData ['body'] ['gameCode'] = $game_code;
        $this->_template['body']['title'] = "Monthly report";

        $this->_template['content'] = $viewData ['body'] ['selection'] = $this->load->view('sdk/body_parts/selection/range_month', $viewData, TRUE);

        $viewData['section'] = array("Monthly" => '16031,10031');
        foreach ($viewData['section'] as $key => $value) {
            $kpiChart = explode(',', $value);
        }

        $viewData['dataChart'] = $this->kpi->getDataKpi($game_code, $kpiChart, $lstDate);
        /*$lstGame = array('3qmobile', 'bklr', 'cack', 'hpt', 'nikki', 'stct');*/
        $lstKpi = $this->kpiconfig->getListKpiByGameCode($game_code, 'monthly');
        //$lstDate = array('2017-04-01','2017-03-31');
        $viewData['timming'] = 31;
        $viewData['kpiTable'] = $lstKpi;

        $viewData['lstDate'] = $lstDateFormat;
        $viewData['data'] = $this->kpi->getDataKpi($game_code, array_keys($lstKpi), $lstDate);


        $this->_template['body']['aGames'] = $this->game->listGames();
        $this->_template['content'] .= $this->load->view("sdk/GameKpi/report_game_page", $viewData, TRUE);
        $this->load->view('master_page', $this->_template);

    }

    public function test()
    {
        $timming = 1;
        $data = array('ACTIVE' => '10000',
            'ACCOUNT_REGISTER' => '11000',
            'NEW_ROLE_PLAYING' => '12000',
            'RETENTION_PLAYING' => '13000',
            'CHURN_PLAYING' => '14000',
            'PAYING_USER                => 15000',
            'NET_REVENUE' => '16000',
            'RETENTION_PAYING' => '17000',
            'CHURN_PAYING' => '18000',
            'NEW_PAYING' => '19000',
            'NEW_PAYING_NET_REVENUE' => '20000',
            'NRU' => '21000',
            'NGR' => '22000',
            'RR' => '23000',
            'CR' => '24000',
            'NEW_USER_PAYING' => '25000',
            'NEW_USER_PAYING_NET_REVENUE' => '26000',
            'NEW_USER_RETENTION' => '27000',
            'NEW_USER_RETENTION_RATE' => '28000',
            'USER_RETENTION_RATE' => '29000',
            'ACU' => '30000',
            'PCU' => '31000',
            'RETENTION_PAYING_RATE' => '32000',
            'SERVER_NEW_ACCOUNT_PLAYING' => '33000',
            'PLAYING_TIME' => '34000',
            'USER_RETENTION' => '35000',
            'AVG_PLAYING_TIME' => '36000',
            'CONVERSION_RATE' => '37000',
            'ARRPU' => '38000',
            'ARRPPU' => '39000',
            'GROSS_REVENUE' => '52000',
            'NEW_PAYING_GROSS_REVENUE' => '53000',
            'NEW_USER_PAYING_GROSS_REVENUE' => '54000');
        foreach ($data as $k => $v) {
            $data[$k] = (int)$v + $timming;
        }
        var_dump(array_flip($data));
        exit();
    }

    private function getData($viewData){
        $chartData =null;
        $tableData =null;
        $this->util->parseUserInput($this->class_name, $viewData, $chartData, $tableData,false);

        $timing = $viewData['body']['options'];

        $viewData['body']['timing'] = $timing;


        return $viewData;

    }

}