<?php

/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 14/03/2016
 * Time: 23:29
 */

class QaTopgame extends MY_Controller
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
        $this->load->model('qa_topgame_model', 'qa_topgame');

    }

    public function index()
    {
        $this->qa();
    }

    public function qa()
    {

        if ($this->input->post('options')) {
            $_SESSION[$this->class_name]['post'] = $_POST;
        }

        //var_dump($viewData['body']['aGames']);
        $viewData['body']['optionsWeek'] = $this->util->listOptionsWeek1(3);
        $viewData['body']['optionsMonth'] = $this->util->listOptionsMonth(3);

        $viewData = $this->getData($viewData);
        $listGameBylogin= $this->game_model->listGamesByLoginUser();
        $listGameCode = array();

        foreach ($listGameBylogin as $key=>$value){
            $listGameCode[] = $value['GameCode'];
        }

        $fromDate = $viewData['body']['fromDate'];
        $toDate = $viewData['body']['toDate'];
        $timing = $viewData['body']['timing'];


       $fromf = $this->util->get_xcolumn_by_timming($fromDate,$timing,true);
        $tof  = $this->util->get_xcolumn_by_timming($toDate,$timing,true);

        $fromtoDatef = $fromf." to ".$tof;

        $day_arr = $this->dashboard->getDates($fromDate, $toDate, $timing);

        $viewData['datachartOS'] = $this->qa_topgame->listGameByOs($listGameCode,$day_arr,$timing);

        $datagametype =  $this->qa_topgame->listGamebyGameType($listGameCode,$day_arr,$timing);
        $dataGames = $this->qa_topgame->listGameByRevenue($listGameCode,$day_arr,$timing);
        $dataMobile =  $this->qa_topgame->listMobileGameByRevenue($listGameCode,$day_arr,$timing);
        $viewData['datachartRev'] = array_merge($dataGames,$dataMobile);
        $viewData['datachartGameType'] = $datagametype;
        $viewData['fromtodate'] = $fromtoDatef;

        $this->_template['content'] =   $viewData['body']['selection'] = $this->load->view('qa_dashboard/timing_mygame', $viewData, TRUE);

        $this->_template['body']['title'] = "My Games - Dashboard";

        $this->_template['content'] .= $this->load->view('qa_dashboard/content_chart', $viewData, TRUE);

        $this->load->view('master_page', $this->_template);


    }

    private function getData($viewData){
        $chartData =null;
        $tableData =null;
        $this->util->parseUserInput($this->class_name, $viewData, $chartData, $tableData,false);

        $timing = $viewData['body']['options'];
        if($timing==null){
            $timing=4;//default
        }


        $viewData['body']['timing'] = $timing;


        return $viewData;

    }





}

