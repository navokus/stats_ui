<?php

/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 14/03/2016
 * Time: 23:29
 */

class Geo extends MY_Controller
{

    private $class_name = "geo";

    public function __construct()
    {
        parent::__construct();
//		 $this->output->enable_profiler(TRUE);
        $this->load->model('game_model', 'game');
        $this->load->model('geo_model', 'geo');
        $this->load->library('form_validation');
        $this->load->library('util');
    }

    public function index()
    {
        $this->location();
    }

    public function location()
    {
        if ($this->input->post('daterangepicker')) {
            $_SESSION[$this->class_name]['post'] = $_POST;
        }

        $viewData['body']['aGames'] = $this->game->listGames();
        $viewData['body']['optionsWeek'] = $this->util->listOptionsWeek();
        $viewData['body']['optionsMonth'] = $this->util->listOptionsMonth();
        $gameCode = $this->session->userdata('current_game');
        $viewData['body']['gameCode'] = $gameCode;

        $viewData = $this->get_data_location($viewData);

        if (count($viewData['rawdata']['tables']['header']) != 1) {
            $viewData['body']['tables'] = $this->load->view("body_parts/table/common_table", $viewData['rawdata']['tables'], TRUE);
        }
        if (count($viewData['rawdata']['charts']['container_1']['data']) != 0) {
            $viewData['body']['charts'] = $this->load->view("body_parts/chart/pie", $viewData['rawdata']['charts']['container_1'], TRUE);
        }
        //load master view
        $viewData['body']['selection'] = $this->load->view('body_parts/selection/range_date', $viewData, TRUE);
        $this->_template['content'] = $this->load->view('geo/location', $viewData, TRUE);
        $this->load->view('master_page', $this->_template);
    }

    public function get_data_location($viewData)
    {
        $chartData = array();
        $tableData = array();
        $this->util->parseUserInput($this->class_name, $viewData, $chartData, $tableData);
        $gameCode = $viewData['body']['gameCode'];
        $data = $this->geo->getDataDrawChart($gameCode, $viewData['body']['options'], $viewData['body']['fromDate'], $viewData['body']['toDate']);
        $viewData['title'] = 'BIỀU ĐỒ PHÂN BỐ TỶ LỆ ĐĂNG NHẬP CÁC VÙNG MIỀN GAME ' . strtoupper($gameCode);

        $data_chart = array();
        $data_chart['Other'] = 0;
        $OTHER_LIMIT = 0;
        if ($data) {
            foreach ($data as $key => $value) {
                if ($value['ltotal'] < $OTHER_LIMIT) {
                    $data_chart['Other'] += $value['ltotal'];
                } else {
                    $data_chart[$value['country_code'] . ' - ' . $value['city_name']] = $value['ltotal'];
                }
            }
        }

        if ($data_chart['Other'] == 0)
            unset($data_chart['Other']);
        $data_string = "";
        arsort($data_chart);
        $total_login = array_sum(array_values($data_chart));
        $data_table = array();
        $stt=0;
        foreach ($data_chart as $key => $value) {
            $key = str_replace("'", "", $key);
            $data_string .= "{ name : '" . $key . "',y: " . $value . "},";
            $data_table[$stt]['fromDate'] = $viewData['body']['fromDate'];
            $data_table[$stt]['toDate'] = $viewData['body']['toDate'];
            $data_table[$stt]['location'] = $key;
            $data_table[$stt]['times'] = number_format($value);
            $data_table[$stt]['percent'] = round(($value / $total_login) * 100, 2) . "%";
            $stt++;
        }
        $data_string = substr($data_string, 0, -1);

        //prepare data for table
        $tableData['data'] = $data_table;
        $tableData['title'] = "Dữ liệu chi tiết";
        $tableData['id'] = "geo-location"; //change later
        //table header config
        $tableData['header'] = array(
            "fromDate" => "TỪ NGÀY",
            "toDate" => "TỚI NGÀY",
            "location" => "TỈNH THÀNH",
            "login_time" => "SỐ LẦN ĐĂNG NHẬP",
            "percent" => "PHẦN TRĂM"
        );

        //prepare data for chart
        $chartData['container_1']['title'] = "BIỂU ĐỒ PHÂN BỐ TỶ LỆ ĐĂNG NHẬP CÁC VÙNG MIỀN GAME " . strtoupper($gameCode);
        $chartData['container_1']['id'] = "container_1";
        $chartData['container_1']['data'] = array(
            "login" => array(
                "name" => "Tỷ lệ đăng nhập",
                "data" => $data_string
            )
        );

        $viewData['body']['title'] = "IP LOCATION";
        $viewData['body']['listContainer'] = array_keys($chartData);
        $viewData['rawdata']['tables'] = $tableData;
        $viewData['rawdata']['charts'] = $chartData;

        $viewData['data']['dateFrom'] = $viewData['body']['fromDate'];
        $viewData['data']['dateTo'] = $viewData['body']['toDate'];

        return $viewData;
    }
}