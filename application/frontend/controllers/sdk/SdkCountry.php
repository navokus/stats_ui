<?php

/**
 * Created by IntelliJ IDEA.
 * User: quangctn
 * Date: 10/10/2017
 * Time: 14:47
 */
class SdkCountry extends MY_Controller
{
    public function __construct()
    {
        $this->source_menu="sdk";
        parent::__construct();
        $this->load->model("sdk/sdk_country_model", "country");
        $this->load->model('game_model', 'game');
        $this->load->library('kpiconfig');
    }

    public function index()
    {
        if ($this->input->post('options')) {
            $_SESSION[$this->class_name]['post'] = $_POST;
        }

        $gameCode = $this->session->userdata('current_game');
        $viewData['body']['gameCode'] = $gameCode;
        $viewData['body']['optionsWeek'] = $this->util->listOptionsWeek1(3);
        $viewData['body']['optionsMonth'] = $this->util->listOptionsMonth(3);
        $viewData = $this->getData($viewData);

        if ($this->input->post('options')) {
            $viewData['body']['options'] = $this->input->post('options');
        }else {
            $viewData['body']['options'] = 4;
        }

        $fromDate = $viewData['body']['fromDate'];
        $toDate = $viewData['body']['toDate'];
        $timing = $viewData['body']['timing'];

        // get selected group




        $_SESSION["Country"]['game_code'] = $gameCode;

        $array_date=$this->util->getDates($fromDate,$toDate,$timing);
//        $date = array('2017-02-26');




        $game_code = $this->session->userdata('current_game');
        $viewData['body']['aGames'] = $this->game->listGames();
        switch ($timing){
            default:
                $lstKpiConfig  = array('10001' => 'active', '15001' => 'pu', '16001' => 'rev');
                break;
            case 17:
                $lstKpiConfig  = array('10017' => 'active', '15017' => 'pu', '16017' => 'rev');
                break;
            case 31:
                $lstKpiConfig  = array('10031' => 'active', '15031' => 'pu', '16031' => 'rev');
                break;

        }

        $lstKpi = array_keys($lstKpiConfig);
        $data = $this->country->getDataKpi($game_code, $lstKpi, $array_date);
        $viewData['countrygroup']=$this->getGroupCountry($data);


        if($_POST["selectedCountry"]){
            $selectedCountry = $_POST["selectedCountry"];
        }else{
            $selectedCountry = $viewData['countrygroup'];
        }


        // reset all selected id when gamecode selected change
        if($_SESSION["Country"]['game_code'] != $gameCode){
            $selectedCountry = $viewData['countrygroup'];
            $_SESSION["group-selected"] = null;
        }

        $colors = array(
            "#DAA520", "#2F4F4F", "#B0C4DE", "#800000", "#808000", "#CD853F", "#708090",
            "#5F9EA0", "#008B8B", "#FF8C00", "#2F4F4F", "#DAA520", "#CD5C5C",
            "#F08080", "#20B2AA", "#778899", "#9370DB", "#3CB371", "#191970", "#FF4500",
            "#DB7093", "#663399", "#4169E1", "#8B4513", "#4682B4", "#008080", "#40E0D0"
        );

        $datareport = $this->getDataReport($data,$lstKpiConfig,$array_date,$gameCode);
        $viewData['colors']=$colors;
        $viewData['timing']=$timing;
        $viewData['array_date']=$array_date;

        $viewData['dataActive']=$datareport["active"];
        $viewData['dataPu']=$datareport["pu"];
        $viewData['dataRev']=$datareport["rev"];

        if($timing==4){
            $lstKpiTableConfig = $this->kpiconfig->getListKpiByGameCode($game_code, 'daily');
        }else if($timing==17){
            $lstKpiTableConfig = $this->kpiconfig->getListKpiByGameCode($game_code, 'weekly');
        }else if($timing==31){
            $lstKpiTableConfig = $this->kpiconfig->getListKpiByGameCode($game_code, 'monthly');

        }
        $lstKpiTable = array_keys($lstKpiTableConfig);
        $dataTable = $this->country->getDataKpi($game_code, $lstKpiTable, $array_date);



        $this->_template['body']['title'] = "Country Report";
        $this->_template['body']['aGames'] = $this->game->listGames();

        $viewData['datareport']=$datareport;
        $viewData['selectedCountry']=$selectedCountry;

        $tableData['exportTitle'] = $this->util->get_export_filename($gameCode, "Country_Report", $fromDate, $toDate);
        $tableData['datatable'] = $dataTable;
        $tableData['game_code'] = $game_code;
        $tableData['lstKpiTableConfig'] = $lstKpiTableConfig;

        $viewData['table']=$tableData;

        $this->_template['content'] =   $viewData['body']['selection'] = $this->load->view('sdk/report_country/report_country', $viewData, TRUE);

        $this->load->view('master_page', $this->_template);


    }
    public function getDataReport($data,$lstKpiConfig,$array_date,$gameCode){
        foreach ($lstKpiConfig as $kpiId =>$name){
            foreach ($array_date as $date){
                $result[$name][$date]=$data[$date][$gameCode][$kpiId];
            }
        }
        return $result;

    }
    public function getGroupCountry($data){
        foreach ($data as $date => $value) {
            foreach ($value as $gameCode => $value_1) {
                foreach ($value_1 as $kpi_id => $value_2) {
                    foreach ($value_2 as $country => $value_3) {
                        $data['country'][]=$country;
                    }
                }
            }
        }
        $result =array_unique($data['country']);
        asort($result);
       return $result;

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