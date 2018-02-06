<?php

/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 14/03/2016
 * Time: 23:29
 */

class CompareData extends MY_Controller
{
    private $class_name = "kpi";

    public function __construct()
    {
        parent::__construct();
//		 $this->output->enable_profiler(TRUE);
        $this->load->model('game_model', 'game');
        $this->load->model('kpi_model', 'kpi');
        $this->load->library('form_validation');
        $this->load->library('util');
        $this->load->model('dashboard_model', 'dashboard');
        $this->load->model('compare_data_model', 'compare_data');

    }

    public function index()
    {
        $this->compare();
    }

    public function compare()
    {

        if ($this->input->post('options')) {
            $_SESSION[$this->class_name]['post'] = $_POST;
        }

        //var_dump($viewData['body']['aGames']);

        if ($this->input->post('daterangepicker') != "") {
            $daterange = $this->input->post('daterangepicker');
            $viewData['body']['day']['default_range_date'] = $daterange;
        } else {
            $viewData['body']['day']['default_range_date'] = date('d/m/Y', strtotime("-31 days")) . " - " . date('d/m/Y', strtotime("-1 days"));
        }

        $tmp = explode("-",$viewData['body']['day']['default_range_date']);
        $date['from'] = trim($tmp[0]);
        $date['to'] = trim($tmp[1]);

        list($day, $month, $year) = explode('/', $date['from']);
        $fromDate = $year . '-' . $month . '-' . $day;

        list($day, $month, $year) = explode('/', $date['to']);
        $toDate = $year . '-' . $month . '-' . $day;


        $game_code = $this->session->userdata('current_game');

        $viewData['body']['aGames'] = $this->game->listGames();
        $viewData['body']['gameCode'] = $game_code;

//        $viewData = $this->getData($viewData);
        $listGameBylogin= $this->game->listGamesByLoginUser();
        $listGameCode = array();

        foreach ($listGameBylogin as $key=>$value){
            $listGameCode[] = $value['GameCode'];
        }
        $date1=date_create($fromDate);
        $date2=date_create($toDate);
        $diff=date_diff($date1,$date2);

        $valuecheck=$diff->format("%a");

        $checkTime = true;
        if($valuecheck>31){
            $checkTime=false;
        }

        $day_arr = $this->dashboard->getDates($fromDate, $toDate);

        $viewData['dataIngame'] = $this->compare_data->getDataFromIngame($game_code,$day_arr);
        $viewData['dataSdk'] = $this->compare_data->getDataFromSDK($game_code,$day_arr);


        $data = $this->renderData($viewData['dataIngame'], $viewData['dataSdk'],$day_arr);

        foreach ($day_arr as $i =>$dateValue) {
            $arrdate[]="'".$dateValue."'";
        }
//        var_dump($arrdate);

        $viewData['xAxisCategories']=implode(", ",$arrdate);


        $viewData['title']="ActiveUser - ".$game_code;
        $viewData['subTitle']="From ".$fromDate." To ".$toDate;
        $viewData['yPrimaryAxisTitle']="Daily";

//        var_dump($data);

        $viewData['data']=$data;
        $viewData['datatable']=$this->renderTable($viewData['dataIngame'], $viewData['dataSdk'],$day_arr);
        $viewData['checkTime'] = $checkTime;


        $this->_template['content'] =   $viewData['body']['selection'] = $this->load->view('compare_data/timing_mygame', $viewData, TRUE);

        $this->_template['body']['title'] = "Comparedata - Dashboard";

        $this->_template['content'] .= $this->load->view('compare_data/compare2source', $viewData, TRUE);

        $this->load->view('master_page', $this->_template);

    }


    public function renderTable($ingame, $sdk,$day_arr){

        foreach ($day_arr as $i =>$dateValue) {

            $flagIn = false;

            foreach ($ingame as $i =>$dataIngame) {
                if($dateValue == $dataIngame['report_date']){
                    $result[$dateValue]['ingame']=$dataIngame['kpi_value'];
                    $flagIn=true;
                    break;
                }
            }

            if(!$flagIn){
                $result[$dateValue]['ingame']=0;

            }

            $flagSdk = false;
            foreach ($sdk as $i =>$dataSdk) {
                if($dateValue == $dataSdk['report_date']){
                    $result[$dateValue]['sdk']=$dataSdk['kpi_value'];
                    $flagSdk=true;
                    break;
                }
            }

            if(!$flagSdk){
                $result[$dateValue]['sdk']=0;

            }


        }



        return $result;

    }

    public function renderData($ingame, $sdk,$day_arr){
        $result['ingame']['name']="ingame";
        $result['sdk']['name']="sdk";


        $result['ingame']['type']="column";
        $result['sdk']['type']="column";


        foreach ($day_arr as $i =>$dateValue) {

            $flagIn = false;

            foreach ($ingame as $i =>$dataIngame) {
                if($dateValue == $dataIngame['report_date']){
                    $arrIngame[]=$dataIngame['kpi_value'];
                    $flagIn=true;
                    break;
                }
            }

            if(!$flagIn){
                $arrIngame[]=0;

            }

            $flagSdk = false;
            foreach ($sdk as $i =>$dataSdk) {
                if($dateValue == $dataSdk['report_date']){
                    $arrSdk[]=$dataSdk['kpi_value'];
                    $flagSdk=true;
                    break;
                }
            }

            if(!$flagSdk){
                $arrSdk[]=0;

            }


        }




        $result['ingame']['data']=implode(", ",$arrIngame);


        $result['sdk']['data']=implode(", ",$arrSdk);


        return $result;
    }


}

