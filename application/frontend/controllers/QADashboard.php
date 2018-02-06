<?php

/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 14/03/2016
 * Time: 23:29
 */

class QADashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
//		 $this->output->enable_profiler(TRUE);
        $this->load->model('game_model', 'game');
        $this->load->model('device_model', 'device');
        $this->load->model('qa_report_model', 'qareport');

        $this->load->library('form_validation');
        $this->load->library('util');
    }

    public function index()
    {
        $this->qa();
    }

    public function qa()
    {
        if ($this->input->post('kpidatepicker') != "") {
            $date = $this->input->post('kpidatepicker');
        } else {
            $date = date('d/m/Y');
        }
        $viewData ['body'] ['day'] ['kpidatepicker'] = $date;

//        $_SESSION['device']['date'] = $date;
        $listGameBylogin= $this->game_model->listGamesByLoginUser();
        $listGameCode = array();

        foreach ($listGameBylogin as $key=>$value){
            $gameNames[$value['GameCode']] = $value['GameName'];
        }


        foreach ($listGameBylogin as $key=>$value){
            $listGameCode[] = $value['GameCode'];
        }

//        var_dump($listGameCode);
//        $date = "11/10/2016";

        $listgameReport= $this->qareport->listGameReport($listGameCode,$date);
        $listgameReport=$this -> sortDataGame($listgameReport);
        $viewData['gameNames']=$gameNames;
        $viewData['datagames']=$listgameReport;
        $viewData['datachart']=$this->renderData($listgameReport,$date);
//        exit;

        $this->_template['content'] = $viewData ['body'] ['selection'] = $this->load->view('body_parts/selection/kpi_date2', $viewData, TRUE);
        $html['html'] = $this->load->view("body_parts/chart/sparkline", $viewData, TRUE);
        $this->_template['content'] .= $this->load->view("device/index",$html, TRUE);
        $this->_template['body']['title'] = "QA DATA";
        $this->load->view('master_page', $this->_template);
    }


    private function renderData($data,$date){
        foreach ($data as $gameCode => $kpiname){
            foreach($kpiname as $key => $value){
                if(isset($value['ratioYesterday'])){
                    unset($value['ratioYesterday']);
                }
                if(isset($value['currentValue'])){
                    unset($value['currentValue']);
                }
                if(isset($value['yesterday'])){
                    unset($value['yesterday']);
                }
                $result[$gameCode.$key]=$value;
            }
        }
        return $result;
    }

    private function sortDataGame($listgameReport){


        foreach ($listgameReport as $gameCode => $kpiValue){

            $kpiValue=array_merge(array_flip(array('a1', 'pu1', 'gr1','n1','npu1')), $kpiValue);
            $listgameReport[$gameCode]=$kpiValue;
        }

        return $listgameReport;
    }





}