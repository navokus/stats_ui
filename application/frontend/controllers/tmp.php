<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        //$this->output->enable_profiler(TRUE); // debug
        $this->load->library('util');
        $this->load->model('game_model', 'game');
        $this->load->model('payment_model', 'payment');
        $this->load->model('retention_model', 'retention');
        $this->load->model('userkpi_model', 'userkpi');
    }

    public function index(){
        $this->dashboard();
        return;
        $currentGame = $this->session->userdata['default_game'] ? $this->session->userdata['default_game'] : '';
        if($this->util->hard_code_check_game_in_new_db($currentGame)){
            $this->load->library('../../controllers/UserKpi');
            $this->userkpi->active_user();
        }else{
            $this->dashboard();
        }
    }

    public function dashboard(){
        if ($this->input->post('options')) {
            $_SESSION[$this->class_name]['post'] = $_POST;
        }else{

        }




        $viewData['body']['aGames'] = $this->game->listGames();
        $viewData['body']['optionsWeek'] = $this->util->listOptionsWeek();
        $viewData['body']['optionsMonth'] = $this->util->listOptionsMonth();
        $gameCode = $this->session->userdata('current_game');
        $viewData['body']['gameCode'] = $gameCode;


        $this->load->library('../../controllers/RevenueKpi');
        //unset($this->revenuekpi);
        $this->load->library('../../controllers/UserKpi');

        $r = $this->revenuekpi;



        $u = $this->userkpi;


        var_dump($this->revenuekpi);exit();

        $viewData = $r->get_data_revenue($viewData);
        if (count($viewData['rawdata']['charts']['container_1']['data']) != 0) {
            $viewData['body']['charts'] .= $this->load->view("body_parts/chart/spline", $viewData['rawdata']['charts']['container_1'], TRUE);
        }


        //$viewData = $u->get_data_active_user($viewData);
        if (count($viewData['rawdata']['charts']['container_1']['data']) != 0) {
            $viewData['body']['charts'] = $this->load->view("body_parts/chart/spline", $viewData['rawdata']['charts']['container_1'], TRUE);
        }


        //load master view
        $viewData['body']['cbb'] = "A";
        $viewData['body']['selection'] = $this->load->view('body_parts/selection/kpi', $viewData, TRUE);
        $this->_template['content'] = $this->load->view('userkpi/active_user', $viewData, TRUE);
        $this->load->view('master_page', $this->_template);
    }

    public function dashboard1()
    {
        $return['aGames'] = $this->game->listGames(null);
        $return['optionsWeek'] = $this->util->listOptionsWeek();
        $return['optionsMonth'] = $this->util->listOptionsMonth();

        if ($this->input->post('options')) {
            // chec game code permission
            $check = false;
            foreach ($return['aGames'] as $value) {
                if ($value['GameCode'] == $_POST['gameCode']) {
                    $check = true;
                    break;
                }
            }

            if ($check == false) {
                show_error('You do not permission!!!');
            }echo $_GET['haha'];

            $_SESSION['dashboard']['post'] = $_POST;

            redirect('Dashboard');
        }

        if ($_SESSION['dashboard']['post']) {
            $return['post'] = $_SESSION['dashboard']['post'];
        } else {
            $return['post']['options'] = 4;
            $return['post']['day'] = date('d/m/Y', strtotime("-1 days"));

        }

        $return['post']['gameCode'] = $this->session->userdata('default_game');

        $gameCode = $return['post']['gameCode'];

        // $return['post']['options'] : 4 - day, 5 - week, 6 - month

        switch ($return['post']['options']) {
            case '5': // week

                // in this week
                $dateSaturdayInWeek = date('Y-m-d', strtotime('this week next saturday' . $return['post']['week']));

                $data = $this->payment->getDataDrawChart($gameCode, $return['post']['options'], $dateSaturdayInWeek);

                // get data retention login
                $dataRetention = $this->retention->getDataRetentionLogin($gameCode, $return['post']['options'], $return['post']['week']);
                $return['retentionLogin'] = array();
                foreach ($dataRetention as $key => $value) {

                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['retentionLogin'][$key][$v['CalculateValue']] = $v['RetentionAccountTotal'];
                        }
                    }
                }

                // get data retention paying
                $dataRetentionPaying = $this->retention->getDataRetentionPaying($gameCode, $return['post']['options'], $return['post']['week']);
                $return['retentionPaying'] = array();

                foreach ($dataRetentionPaying as $key => $value) {

                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['retentionPaying'][$key][$v['CalculateValue']] = array($v['AccountTotal'], $v['RepayingAccTotal'], $v['RevenueTotal'], $v['RepayingRevTotal']);
                        }
                    }
                }

                // get data transfer paying
                $dataTransferPaying = $this->retention->getDataPayingTransfer($gameCode, $return['post']['options'], $return['post']['week']);
                $return['transferPaying'] = array();

                foreach ($dataTransferPaying as $key => $value) {

                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['transferPaying'][$key][$v['CalculateValue']] = array($v['AccountTotal'], $v['StopPayingAccTotal'], $v['PayingAccTotal'], $v['OverLapAccTotal']);
                        }
                    }
                }

                // get data transer paying detail
                $dataTransferPayingDetail = $this->retention->getDataPayingTransferDetail($gameCode, $return['post']['options'], $return['post']['week']);
                $return['transferPayingDetail'] = array();

                foreach ($dataTransferPayingDetail as $key => $value) {

                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['transferPayingDetail'][$key][$v['CompareGameCode']] = array($v['OverLapAccTotal'], $v['PayingAccTotal'], $v['PayingAccRevenue'], $v['StopPayingAccTotal'], $v['StopPayingAccRevenue']);
                            $return['gameTransferPayingDetail'][$v['CompareGameCode']] = $v['CompareGameCode'];
                        }
                    }
                }

                // get data compare all game
                $return['compareAllGame'] = $this->retention->getDataCompareAllGame($return['aGames'], $return['post']['week']);

                // get data ARPPU Doanh Thu Trung binh
                $return['revenueARPPU'] = $this->retention->getDataARPPU($gameCode, $return['post']['options'], $return['post']['week']);

                $subTitle = $return['optionsWeek'][$return['post']['week']];
                $return['time'] = 'Tuần';
                break;

            case '6': // month

                // in this month

                $data = $this->payment->getDataDrawChart($gameCode, $return['post']['options'], $return['post']['month']);

                // get data retention login
                $dataRetention = $this->retention->getDataRetentionLogin($gameCode, $return['post']['options'], $return['post']['month']);
                $return['retentionLogin'] = array();
                foreach ($dataRetention as $key => $value) {

                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['retentionLogin'][$key][$v['CalculateValue']] = $v['RetentionAccountTotal'];
                        }
                    }
                }

                // get data retention paying
                $dataRetentionPaying = $this->retention->getDataRetentionPaying($gameCode, $return['post']['options'], $return['post']['month']);
                $return['retentionPaying'] = array();

                foreach ($dataRetentionPaying as $key => $value) {

                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['retentionPaying'][$key][$v['CalculateValue']] = array($v['AccountTotal'], $v['RepayingAccTotal'], $v['RevenueTotal'], $v['RepayingRevTotal']);
                        }
                    }
                }

                // get data transfer paying
                $dataTransferPaying = $this->retention->getDataPayingTransfer($gameCode, $return['post']['options'], $return['post']['month']);
                $return['transferPaying'] = array();

                foreach ($dataTransferPaying as $key => $value) {

                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['transferPaying'][$key][$v['CalculateValue']] = array($v['AccountTotal'], $v['StopPayingAccTotal'], $v['PayingAccTotal'], $v['OverLapAccTotal']);
                        }
                    }
                }

                // get data transer paying detail
                $dataTransferPayingDetail = $this->retention->getDataPayingTransferDetail($gameCode, $return['post']['options'], $return['post']['month']);
                $return['transferPayingDetail'] = array();

                foreach ($dataTransferPayingDetail as $key => $value) {

                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['transferPayingDetail'][$key][$v['CompareGameCode']] = array($v['OverLapAccTotal'], $v['PayingAccTotal'], $v['PayingAccRevenue'], $v['StopPayingAccTotal'], $v['StopPayingAccRevenue']);
                            $return['gameTransferPayingDetail'][$v['CompareGameCode']] = $v['CompareGameCode'];
                        }
                    }
                }

                // get data compare all game
                $return['compareAllGame'] = $this->retention->getDataCompareAllGame($return['aGames'], $return['post']['month']);

                // get data ARPPU Doanh Thu Trung binh
                $return['revenueARPPU'] = $this->retention->getDataARPPU($gameCode, $return['post']['options'], $return['post']['month']);

                $subTitle = 'Tháng ' . date('m/Y', strtotime($return['post']['month']));
                $return['time'] = 'Tháng';
                break;

            case '4': // day
            default:
                list($day, $month, $year) = explode('/', $return['post']['day']);
                $formatDay = $year . '-' . $month . '-' . $day;
                $data = $this->payment->getDataDrawChart($gameCode, $return['post']['options'], $formatDay);

                // get data retention login
                $dataRetention = $this->retention->getDataRetentionLogin($gameCode, $return['post']['options'], $formatDay);
                $return['retentionLogin'] = array();
                foreach ($dataRetention as $key => $value) {

                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['retentionLogin'][$key][$v['CalculateValue']] = $v['RetentionAccountTotal'];
                        }
                    }
                }

                // get data retention paying
                $dataRetentionPaying = $this->retention->getDataRetentionPaying($gameCode, $return['post']['options'], $formatDay);
                $return['retentionPaying'] = array();

                // var_dump($dataRetentionPaying);
                foreach ($dataRetentionPaying as $key => $value) {

                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['retentionPaying'][$key][$v['CalculateValue']] = array($v['AccountTotal'], $v['RepayingAccTotal'], $v['RevenueTotal'], $v['RepayingRevTotal']);
                        }
                    }
                }

                // get data transfer paying
                $dataTransferPaying = $this->retention->getDataPayingTransfer($gameCode, $return['post']['options'], $formatDay);
                $return['transferPaying'] = array();

                foreach ($dataTransferPaying as $key => $value) {

                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['transferPaying'][$key][$v['CalculateValue']] = array($v['AccountTotal'], $v['StopPayingAccTotal'], $v['PayingAccTotal'], $v['OverLapAccTotal']);
                        }
                    }
                }

                // get data transer paying detail
                $dataTransferPayingDetail = $this->retention->getDataPayingTransferDetail($gameCode, $return['post']['options'], $formatDay);
                $return['transferPayingDetail'] = array();
                $return['gameTransferPayingDetail'] = array();
                foreach ($dataTransferPayingDetail as $key => $value) {

                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['transferPayingDetail'][$key][$v['CompareGameCode']] = array($v['OverLapAccTotal'], $v['PayingAccTotal'], $v['PayingAccRevenue'], $v['StopPayingAccTotal'], $v['StopPayingAccRevenue']);
                            $return['gameTransferPayingDetail'][$v['CompareGameCode']] = $v['CompareGameCode'];
                        }
                    }
                }

                // get data compare all game
                $return['compareAllGame'] = $this->retention->getDataCompareAllGame($return['aGames'], $formatDay);

                // get data ARPPU Doanh Thu Trung binh
                $return['revenueARPPU'] = $this->retention->getDataARPPU($gameCode, $return['post']['options'], $formatDay);

                $subTitle = 'Ngày ' . $return['post']['day'];
                $return['time'] = 'Ngày';
                break;
        }

        $return['title'] = 'Biểu đồ Tổng Quan game ' . strtoupper($gameCode);
        $return['subTitle'] = $subTitle;

        if ($data)
            foreach ($data as $value) {
                $columnName = '';

                switch ($return['post']['options']) {
                    case '5': // week

                        $columnName = 'Tuần ' . date('W, Y', strtotime($value['CalculateDate']));
                        break;

                    case '6': // month
                        $columnName = 'Tháng ' . date('m-Y', strtotime($value['CalculateDate']));
                        break;

                    case '4': // day
                    default:
                        $columnName = $value['CalculateDate'];
                        break;
                }

                $return['columnX'] .= "'" . $columnName . "',";
                $return['dataRevenue'] .= intval($value['RevenueTotalAllGrade']) . ',';
                $return['dataUser'] .= intval($value['AccountTotalAllGrade']) . ',';
                $return['dataUserActive'] .= intval($value['ActiveAccountTotal']) . ',';
            }

        $return['gameCode'] = strtoupper($gameCode);
        $this->_template['content'] = $this->load->view('dashboard/chart', $return, TRUE);
        $this->load->view('master_page', $this->_template);
    }

    public function compareProductOneGame($gameCodeCompare)
    {
        if ($_SESSION['dashboard']['post']) {
            $return['post'] = $_SESSION['dashboard']['post'];
        } else {
            $return['post']['options'] = 4;
            $return['post']['day'] = date('d/m/Y', strtotime("-1 days"));
        }


        switch ($return['post']['options']) {
            case '5': // week
                $date = $return['post']['week'];
                break;

            case '6': // month
                $date = $return['post']['month'];
                break;

            case '4': // day
            default:
                list($day, $month, $year) = explode('/', $return['post']['day']);
                $date = $year . '-' . $month . '-' . $day;
                break;
        }

        $data['aGames'] = $this->game->listGames();
        $data['compare'] = $this->retention->getDataCompareOneGame($this->session->userdata('default_game'), $gameCodeCompare, $date);
        $data['gameCode'] = $this->session->userdata('default_game');
        $data['gameCodeCompare'] = $gameCodeCompare;
        $data['time'] = 'Tháng';

        $this->load->view('dashboard/compare_one_game', $data);
    }
}


/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */