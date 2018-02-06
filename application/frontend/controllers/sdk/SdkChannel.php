<?php

/**
 * Created by IntelliJ IDEA.
 * User: quangctn
 * Date: 10/10/2017
 * Time: 13:48
 */
class SdkChannel extends MY_Controller
{
    public function __construct()
    {
        $this->source_menu="sdk";
        parent::__construct();
        $this->load->model("sdk/sdk_channel_model", "channel");
        $this->load->library('kpiconfig');
    }

    public function index()
    {
//        if ($this->input->post('kpidatepicker') != "") {
//
//            $sDate = str_replace('/', '-', $this->input->post('kpidatepicker'));
//            $sDate = date('Y-m-d', strtotime($sDate));
//        } else {
//            $sDate = date('Y-m-d', strtotime('-1 days'));
//
//
//        }
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

        $lstDate = $this->util->getDateFromRange($sDate,$eDate);
        $viewData['body']['fromdate']=$sDate;
        $viewData['body']['todate']=$eDate;

//        $viewData ['body'] ['day'] ['kpidatepicker'] = date('d-m-Y', strtotime($sDate));
        $game_code = $this->session->userdata('current_game');
        $viewData ['body'] ['aGames'] = $this->game->listGames();
        $viewData ['body'] ['gameCode'] = $game_code;
        $this->_template['body']['title'] = "Channel Report";

        $this->_template['content'] = $viewData ['body'] ['selection'] = $this->load->view('sdk/body_parts/selection/range_date', $viewData, TRUE);
        $viewData['section'] = array("10001" => 'A1', '16001' => 'Rev1');
        $viewData['dataChart'] = $this->channel->getDataKpi($game_code, array_keys($viewData['section']), $lstDate);

        $viewData['titleTable'] = 'Channel';
        /*$lstGame = array('tlbbm', 'siamplay', 'nikki', 'hpt', 'gnm', 'dptk');
        $lstKpi = array('10001', '16001', '52001', '11001', '15001', '17001', '18001');
        $date = array('2017-02-27');*/
        //$game_code = '3qmobile';
        $lstKpi = $this->kpiconfig->getListKpiByGameCode($game_code, 'daily');
        //$logDate = '2017-02-27';
        $viewData['kpiTable'] = $lstKpi;
        $viewData['data'] = $this->channel->getDataKpi($game_code, array_keys($lstKpi), $lstDate);
        $viewData['lstdate'] = $lstDate;
        /* $viewData['dataTable'] = $this->renderTable($viewData['data'], $logDate, $game_code);*/

        $this->_template['body']['aGames'] = $this->game->listGames();
        $this->_template['content'] .= $this->load->view("sdk/channel/report_channel_page", $viewData, TRUE);
        $this->load->view('master_page', $this->_template);
    }

    /*private function renderTable($data, $date, $gameCode)
    {
        $newArr = array();
        foreach ($data[$date][$gameCode] as $channel => $row1) {
            foreach ($row1 as $kpi => $kpiValue) {
                $newArr[$date][$gameCode][$kpi][$channel] = $kpiValue;
            }
        }
        return $newArr;
    }

    public function test()
    {

        $this->load->view('master_page', $this->_template);
        /*$this->load->view('body_parts/chart/pie', $this->_template);
}*/

}