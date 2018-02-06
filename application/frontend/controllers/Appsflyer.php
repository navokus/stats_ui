<?php
/**
 * @date 2016-05-16
 * @author vinhdp
 *
 */
class Appsflyer extends MY_Controller
{

    private $class_name = "appsflyer";
    public function __construct()
    {
        parent::__construct();
        $this->load->model('game_model', 'game');
        $this->load->model('kpi_model', 'kpi');
        $this->load->model('appsflyer_model', 'appsflyer');
        $this->load->library('form_validation');
        $this->load->library('util');
        $this->load->model('dashboard_model', 'dashboard');
    }

    public function index()
    {
        $this->getMarketingReport();
    }

    public function getMarketingReport()
    {

        if ($this->input->post('options')) {
            $_SESSION [$this->class_name] ['post'] = $_POST;
        }

        /* required params: used for all action */
        $viewData ['body'] ['aGames'] = $this->game->listGames();
        $gameCode = $this->session->userdata('current_game');
        $viewData ['body'] ['gameCode'] = $gameCode;
        /* end */
        // process date
        if (isset($_POST['daterangepicker'])) {
            $date = $_POST['daterangepicker'];
            $viewData['body']['day']['default_range_date'] = $date;

            $viewData = $this->getViewData($viewData);
        } else {
            $viewData['body']['day']['default_range_date'] = date('d/m/Y', strtotime("-31 days")) . " - " . date('d/m/Y', strtotime("-1 days"));
        }


        /*//test
        $date = "01/07/2016 - 03/07/2016";
        $viewData['body']['day']['default_range_date'] = $date;*/


        $viewData = $this->getViewData($viewData);
        /* end */


        // load master view

        $viewData['body']['selection'] = $this->load->view('export/listing_report', $viewData, TRUE);

        $this->_template ['content'] = $this->load->view('appsflyer/appsflyer_layout', $viewData, TRUE);

        $this->load->view('master_page', $this->_template);

    }

    public function getViewData($viewData)
    {
        $t1 = explode("-", $viewData['body']['day']['default_range_date']);
        $t2 = explode("/", trim($t1[0]));
        $fromDate = $t2[2] . "-" . $t2[1] . "-" . $t2[0];
        $t3 = explode("/", trim($t1[1]));
        $toDate = $t3[2] . "-" . $t3[1] . "-" . $t3[0];
        $gameCode = $viewData ['body'] ['gameCode'];

        $db_data = $this->appsflyer->getMarketingReport($gameCode,$fromDate, $toDate);
        $data = $this->render_data2($db_data);
        $data = $this->fieldsZeroDataForTable($data);
        $viewData ['rawdata'] ['tables'] = $data;
        $viewData ['body'] ['title'] = "Marketing Report";
        return $viewData;
    }
    public function findIndex($tableData, $rowData){
        for ($t = 0 ; $t<count($tableData); $t++){
            if (($tableData[$t]['logdate'] == $rowData['logdate'])
            &&($tableData[$t]['media_source'] == $rowData['media_source'])
            &&($tableData[$t]['campaign'] == $rowData['campaign'])
            &&($tableData[$t]['os'] == $rowData['os'])){
                return $t;
            }
        }
        return false;
    }
    public function addNewRowForTableData($abc,$rowData){
        $tableData = array();
        $tableData['logdate']  = $rowData['logdate'];
        $tableData['media_source']  = $rowData['media_source'];
        $tableData['campaign']  = $rowData['campaign'];
        $tableData['os']  = $rowData['os'];
        $tableData = $this->addfieldForTableData($tableData,$rowData);
        return $tableData;
    }

    public function addfieldForTableData($tableData,$rowData){
        /*$prefix = "mkt";
        $kpi_name = $this->appsflyer->kpi_config[$rowData['kpi_id']]['kpi_name'] ;
        var_dump($kpi_name);exit();*/

        if($rowData['kpi_id'] == "58001"){
             $tableData['install']  = $rowData['kpi_value'];
        }
        if($rowData['kpi_id'] == "59001"){
            $tableData['nru0']  = $rowData['kpi_value'];
        }
        if($rowData['kpi_id'] == "60001"){
            $tableData['nru1']  = $rowData['kpi_value'];
        }
        if($rowData['kpi_id'] == "57001"){
            $tableData['rev1']  = $rowData['kpi_value'];
        }
        if($rowData['kpi_id'] == "57007"){
            $tableData['rev7']  = $rowData['kpi_value'];
        }
        return $tableData;
    }
    public function fieldsZeroDataForTable($tableData){
        for($i=0; $i<count($tableData); $i++){
            if(!isset($tableData[$i]['install'])){
                $tableData[$i]['install'] = 0;
            }
            if(!isset($tableData[$i]['nru1'])){
                $tableData[$i]['nru1'] = 0;
            }
            if(!isset($tableData[$i]['rev1'])){
                $tableData[$i]['rev1'] = 0;
            }
            if(!isset($tableData[$i]['rev7'])){
                $tableData[$i]['rev7'] = 0;
            }
            if(!isset($tableData[$i]['nru0'])){
                $tableData[$i]['nru0'] = 0;
            }
        }
        return $tableData;
    }
    public function render_data2($db_data)
    {
        $tableData = array();
        $x = 0 ;

        for($i = 0 ; $i<$db_data['numRow'];$i++){
            if(count($tableData) == 0){
                $tableData[0] = $this->addNewRowForTableData($i,$db_data[$i]);
                $x++;
            }else {
                $t = $this->findIndex($tableData,$db_data[$i]);
                if(is_int($t)){
                    $tableData[$t] =$this->addfieldForTableData($tableData[$t],$db_data[$i]);
                }
                else{
                        $tableData[$x] =($this->addNewRowForTableData($i,$db_data[$i]));
                        $x++;
                    }
                }
        }
        return $tableData;
    }


}