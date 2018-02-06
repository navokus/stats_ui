<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 16/03/2016
 * Time: 18:00
 */
class RevenueKpi extends MY_Controller
{
    private $class_name="revenuekpi";
    private $_kpi_type = "game_kpi";

    public function __construct()
    {
        parent::__construct();
        //$this->output->enable_profiler(TRUE);
        $this->load->model('game_model', 'game');
        $this->load->model('revenuekpi_model', 'revenuekpi');
        $this->load->library('form_validation');
        $this->load->library('util');
    }

    public function index(){
        $this->revenue();
    }

    public function revenue()
    {
        if ($this->input->post('options')) {
            $_SESSION[$this->class_name]['post'] = $_POST;
        }

        $viewData['body']['aGames'] = $this->game->listGames();
        $viewData['body']['optionsWeek'] = $this->util->listOptionsWeek(3);
        $viewData['body']['optionsMonth'] = $this->util->listOptionsMonth(3);
        $gameCode = $this->session->userdata('current_game');
        $viewData['body']['gameCode'] = $gameCode;
        $viewData = $this->get_data_revenue($viewData);
        if (count($viewData['rawdata']['tables']['header']) > 1) {
            $viewData['body']['tables'] = $this->load->view("body_parts/table/reverse_table", $viewData['rawdata']['tables'], TRUE);
        }
        if (count($viewData['rawdata']['charts']['container_1']['data']) != 0) {
            $viewData['body']['charts'] = $this->load->view("body_parts/chart/spline", $viewData['rawdata']['charts']['container_1'], TRUE);
        }

        //load master view
        $viewData['body']['cbb'] = "RV";
        $viewData['body']['selection'] = $this->load->view('body_parts/selection/kpi', $viewData, TRUE);
        $this->_template['content'] = $this->load->view('revenuekpi/revenue', $viewData, TRUE);
        $this->load->view('master_page', $this->_template);
    }

    public function get_data_revenue($viewData)
    {
        $gameCode = $viewData["body"]["gameCode"];
        $chartData = array();
        $tableData = array();
        $f_suffix_arr = $this->util->get_timming_config();
        $this->util->parseUserInput($this->class_name, $viewData, $chartData, $tableData);

        $timming = $viewData['body']['options'];
        $f_suffix = $f_suffix_arr[$timming];
        $gameInfo = $this->game->findGameInfo($gameCode);
        $chartData['container_1']['subTitle'] .= " (source: " . $this->revenuekpi->get_data_source_from_mt($gameCode, "gr1", "game_kpi") . ")";
        $db_data = $this->revenuekpi->getRevenueKpiData($gameCode, $this->_kpi_type, $viewData['body']['options'], $viewData['body']['fromDate'], $viewData['body']['toDate']);

        $max_log_date = "";
        if(isset($db_data['max_log_date'])){
            $max_log_date = $db_data['max_log_date'];
            unset($db_data['max_log_date']);
        }
        $db_data_by_field = $this->util->re_organize_db_data($db_data);
        if ($db_data) {
            $t_1 = array();
            $t_2 = array();
            for ($i=0;$i<count($db_data);$i++) {
                $row = $db_data[$i];
                $t_1['columnX'][] = $this->util->get_xcolumn_by_timming($row['log_date'], $timming, true);
                $t_1['gr'.$f_suffix][] = intval($row['gr'.$f_suffix]);
                $t_1['pu'.$f_suffix][] = intval($row['pu'.$f_suffix]);

                //co bao nhieu hien thi len het
                $tt_2 = array();
                $tt_2["log_date"] = $this->util->get_xcolumn_by_timming($row['log_date'], $timming, false);
                if($max_log_date == $row['log_date'] && $timming != "4"){
                    $tt_2['log_date'] .= " ($max_log_date)";
                }
                unset($row['log_date']);
                foreach ($row as $f => $v) {
                    if($f=="cv")
                        $tt_2[$f] = round($v,2) . "%";
                    else
                        $tt_2[$f] = number_format($v);
                }

                // tinh phan tram user retention
                $row_before = isset($db_data[$i-1]) ? $db_data[$i-1] : 0;
                $paying_user_before = isset($row_before['pu'.$f_suffix]) ? $row_before['pu'.$f_suffix] : 0;
                $m_paying_user_before = isset($row_before['pum']) ? $row_before['pum'] : 0;
                if(isset($tt_2['rpc'.$f_suffix])){
                    $retention_current = isset($row['rpc'.$f_suffix]) ? $row['rpc'.$f_suffix] : 0;
                    if($paying_user_before != 0 && $retention_current !=0){
                        $retention_percent =  round(($retention_current / $paying_user_before)*100,2) . "%";
                    }else{
                        $retention_percent = "";
                    }
                    $tt_2['prr'.$f_suffix] = $retention_percent;
                    $db_data_by_field['prr'.$f_suffix][] = $retention_percent;
                }

                if(isset($tt_2['rpcm'])){
                    $retention_current = isset($row['rpcm']) ? $row['rpcm'] : 0;
                    if($m_paying_user_before != 0 && $retention_current !=0){
                        $retention_percent =  round(($retention_current / $m_paying_user_before)*100,2) . "%";
                    }else{
                        $retention_percent = "";
                    }
                    $tt_2['prrm'] = $retention_percent;
                    $db_data_by_field['prrm'][] = $retention_percent;
                }
                //tinh phan tram churn
                if(isset($tt_2['cpc'.$f_suffix])){
                    $retention_current = isset($row['cpc'.$f_suffix]) ? $row['cpc'.$f_suffix] : 0;
                    if($paying_user_before != 0 && $retention_current !=0){
                        $retention_percent =  round(($retention_current / $paying_user_before)*100,2) . "%";
                    }else{
                        $retention_percent = "";
                    }
                    $tt_2['cpr'.$f_suffix] = $retention_percent;
                    $db_data_by_field['cpr'.$f_suffix][] = $retention_percent;
                }

                if(isset($tt_2['cpcm'])){
                    $retention_current = isset($row['cpcm']) ? $row['cpcm'] : 0;
                    if($m_paying_user_before != 0 && $retention_current !=0){
                        $retention_percent =  round(($retention_current / $m_paying_user_before)*100,2) . "%";
                    }else{
                        $retention_percent = "";
                    }
                    $tt_2['cprm'] = $retention_percent;
                    $db_data_by_field['cprm'][] = $retention_percent;
                }
                $this->unset_fields_unnecessary($tt_2, $f_suffix);
                $arppu = number_format(intval($row['gr'.$f_suffix] / $row['pu'.$f_suffix]));
                $tt_2['arppu'. $f_suffix] = $arppu;
                $db_data_by_field['arppu'.$f_suffix][] = $arppu;
                $t_2[] = $tt_2;
            }
            $t_2 = array_reverse($t_2);
            //prepare data for chart
            $table_header_config = $this->util->get_kpi_header_name();
            $chartData['container_1']['title'] = $this->util->get_main_chart_title(array("feature"=>"Revenue","game_info"=>$this->_gameInfo),$timming);
            $chartData['container_1']['xAxisCategories'] = $this->util->get_data_string($t_1['columnX'], "'", true);
            $chartData['container_1']['id'] = "container_1";
            $chartData['container_1']['yPrimaryAxisTitle'] = "Revenue (VND)";
            $chartData['container_1']['ySecondaryAxisTitle'] = "Paying User (users)";
            $chartData['container_1']['data'] = array(
                "gr".$f_suffix => array(
                    "name" => strtoupper($table_header_config['gr'.$f_suffix]),
                    "type" => "column",
                    "yAxis" => "0",
                    "data" => $this->util->get_data_string($t_1['gr'.$f_suffix])
                ),
                "pu".$f_suffix => array(
                    "name" => strtoupper($table_header_config['pu'.$f_suffix]),
                    "type" => "spline",
                    "yAxis" => "1",
                    "data" => $this->util->get_data_string($t_1['pu'.$f_suffix])
                )
            );
            $table_header_config['log_date'] = $tableData['reportType'];

            //kiem tra neu ko co du lieu thi remove khoi chart, remove khoi table, tranh tinh trang co column nhung ko co du lieu
            $key_sets = array_keys($db_data_by_field);
            foreach ($key_sets as $k) {
                if (array_sum($db_data_by_field[$k]) == 0) {
                    //echo $k;exit();
                    unset($chartData['container_1']['data'][$k]);
                    foreach ($t_2 as $key => $value) {
                        unset($t_2[$key][$k]);
                    }
                }
            }

            //prepare data for table
            //$t_2 = $this->util->sort_data_table($t_2,$timming);
            //$this->util->add_trend_icon($t_2, $timming);
            //$tableData['data'] = $t_2;
            $newTable = $this->util->reverseData($t_2);
            $tableData['data'] = $newTable;
            $tableData['title'] = "KPI Detail";
            $tableData['id'] = "kpi-report-revenue";
            $tableData['exportTitle'] = $this->util->get_export_filename($gameCode,"revenue",$viewData['body']['fromDate'],$viewData['body']['toDate'], $timming);

            //thiet lap lai header
            $header_key_sets = array_keys($t_2[0]);
            foreach ($header_key_sets as $hk) {
                $tableData['header'][$hk] = $table_header_config[$hk];
            }

            $viewData['body']['listContainer'] = array_keys($chartData);
            $viewData['body']['gameCode'] = strtoupper($gameCode);
            $viewData['body']['title'] = "Revenue";

            $viewData['rawdata']['tables'] = $tableData;
            $viewData['rawdata']['charts'] = $chartData;
        }
        return $viewData;
    }
    private function unset_fields_unnecessary(& $tt_2, $f_suffix){
        unset($tt_2['rpc'.$f_suffix]);
        unset($tt_2['cpc'.$f_suffix]);
        unset($tt_2['cpr'.$f_suffix]);
        unset($tt_2['prr'.$f_suffix]);
        unset($tt_2['rpcm']);
        unset($tt_2['cpcm']);
    }

}