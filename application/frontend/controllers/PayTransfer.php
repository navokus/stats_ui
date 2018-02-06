<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class PayTransfer extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // $this->output->enable_profiler(TRUE);
        $this->load->library('util');
        $this->load->model('game_model', 'game');
        $this->load->model('payment_model', 'payment');
        $this->load->model('retention_model', 'retention');
    }

    public function index() {
        $return['aGames'] = $this->game->listGames();
        $return['optionsWeek'] = $this->util->listOptionsWeek();
        $return['optionsMonth'] = $this->util->listOptionsMonth();

        if ($this->input->post('options')) {
            $_SESSION['pay_transfer']['post'] = $_POST;
            redirect('PayTransfer');
        }

        if ($_SESSION['pay_transfer']['post']) {
            $return['post'] = $_SESSION['pay_transfer']['post'];
        } else {
            $return['post']['options'] = 4;
            $return['post']['day'] = date('d/m/Y', strtotime("-1 days"));
        }

        $return['post']['gameCode'] = $this->session->userdata('default_game');

        $gameCode = $return['post']['gameCode'];

        switch ($return['post']['options']) {
            case '5': // week

                // in this week
                $calculateValue = date('Y-w');
                if ($calculateValue == date('Y-w', strtotime($return['post']['week']))) {
                    $newestCalculateDate = $this->payment->getNewestCalculateDateByTime($gameCode, 'weekly', $calculateValue);

                    if ($newestCalculateDate) {
                        $return['post']['week'] = $newestCalculateDate;
                    }
                }

                // get data transfer paying
                $dataTransferPaying = $this->retention->getDataPayingTransfer($gameCode, $return['post']['options'], $return['post']['week']);
                $return['transferPaying'] = array();

                foreach ($dataTransferPaying as $key => $value) {

                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['transferPaying'][$key][$v['CalculateValue']] = array(
                                $v['AccountTotal'],
                                $v['StopPayingAccTotal'],
                                $v['PayingAccTotal'],
                                $v['OverLapAccTotal'],
                                $v['NewPaying'],
                                $v['NewPayingRevenue'],
                                $v['ReturnPaying'],
                                $v['ReturnPayingRevenue']
                            );
                        }
                    }
                }

                // get data transer paying detail
                $dataTransferPayingDetail = $this->retention->getDataPayingTransferDetail($gameCode, $return['post']['options'], $return['post']['week']);
                $return['transferPayingDetail'] = array();

                foreach ($dataTransferPayingDetail as $key => $value) {

                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['transferPayingDetail'][$key][$v['CompareGameCode']] = array(
                                $v['OverLapAccTotal'],
                                $v['PayingAccTotal'],
                                $v['PayingAccRevenue'],
                                $v['StopPayingAccTotal'],
                                $v['StopPayingAccRevenue'],
                                $v['NewPaying'],
                                $v['NewPayingRevenue'],
                                $v['ReturnPaying'],
                                $v['ReturnPayingRevenue']
                            );
                            $return['gameTransferPayingDetail'][$v['CompareGameCode']] = $v['CompareGameCode'];
                        }
                    }
                }


                $return['time'] = 'Tuần';
                $return['timing'] = 'weekly';
                break;

            case '6': // month

                // in this month
                $calculateValue = date('Y-m');
                if ($calculateValue == date('Y-m', strtotime($return['post']['month']))) {
                    $newestCalculateDate = $this->payment->getNewestCalculateDateByTime($gameCode, 'monthly', $calculateValue);

                    if ($newestCalculateDate) {
                        $return['post']['month'] = $newestCalculateDate;
                    }
                }

                // get data transfer paying
                $dataTransferPaying = $this->retention->getDataPayingTransfer($gameCode, $return['post']['options'], $return['post']['month']);
                $return['transferPaying'] = array();

                foreach ($dataTransferPaying as $key => $value) {

                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['transferPaying'][$key][$v['CalculateValue']] = array(
                                $v['AccountTotal'],
                                $v['StopPayingAccTotal'],
                                $v['PayingAccTotal'],
                                $v['OverLapAccTotal'],
                                $v['NewPaying'],
                                $v['NewPayingRevenue'],
                                $v['ReturnPaying'],
                                $v['ReturnPayingRevenue']
                            );
                        }
                    }
                }

                // get data transfer paying detail
                $dataTransferPayingDetail = $this->retention->getDataPayingTransferDetail($gameCode, $return['post']['options'], $return['post']['month']);
                $return['transferPayingDetail'] = array();

                foreach ($dataTransferPayingDetail as $key => $value) {

                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['transferPayingDetail'][$key][$v['CompareGameCode']] = array(
                                $v['OverLapAccTotal'],
                                $v['PayingAccTotal'],
                                $v['PayingAccRevenue'],
                                $v['StopPayingAccTotal'],
                                $v['StopPayingAccRevenue']
                            );
                            $return['gameTransferPayingDetail'][$v['CompareGameCode']] = $v['CompareGameCode'];
                        }
                    }
                }

                $return['time'] = 'Tháng';
                $return['timing'] = 'monthly';
                break;

            case '4': // day
            default:
                list($day, $month, $year) = explode('/', $return['post']['day']);
                $formatDay = $year . '-' . $month . '-' . $day;

                // get data transfer paying
                $dataTransferPaying = $this->retention->getDataPayingTransfer($gameCode, $return['post']['options'], $formatDay);
                $return['transferPaying'] = array();

                foreach ($dataTransferPaying as $key => $value) {

                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['transferPaying'][$key][$v['CalculateValue']] = array(
                                $v['AccountTotal'],
                                $v['StopPayingAccTotal'],
                                $v['PayingAccTotal'],
                                $v['OverLapAccTotal'],
                                $v['NewPaying'],
                                $v['NewPayingRevenue'],
                                $v['ReturnPaying'],
                                $v['ReturnPayingRevenue']
                            );
                        }
                    }
                }

                // get data transfer paying detail
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


                $return['time'] = 'Ngày';
                $return['timing'] = 'daily';
                break;
        }

        $return['gameCode'] = strtoupper($gameCode);
        $this->_template['content'] = $this->load->view('pay_transfer/index', $return, TRUE);
        $this->load->view('master_page', $this->_template);
    }
    public function export_data($fileName)
    {
        list($reportType, $gameCode, $times, $curDate, $prev_Date) = explode('_', $fileName);
        $apiDownload = $this->config->item('api_url')
            . 'ub/export_retention/csv?report_type='.$reportType
            . '&game_code='. $gameCode
            . '&time_value=' . $curDate
            . '&pre_time_value='.$prev_Date
            . '&f=yes&timing=' . $times
           ;
//        var_dump($apiDownload);
//        exit;
        $this->load->helper('download');
        $fileName = strtolower($fileName);

        try {
            $data = file_get_contents($apiDownload);
            $name = $fileName . '.csv';
            force_download($name, $data);
        } catch (Exception $e) {
            show_error($e->getMessage());
        }

    }
    public function export_data_detail($fileName)
    {
        list($reportType, $gameCode, $compareGameCode, $times, $curDate, $reportDetailType) = explode('_', $fileName);
        $apiDownload = $this->config->item('api_url')
            . 'ub/export_retention/csv?report_type='.$reportType
            .'&game_code='. $gameCode
            .'&compare_game_code='. $compareGameCode
            . '&time_value=' . $curDate
            . '&timing=' . $times
            . '&f=yes&detail_type=' . $reportDetailType
        ;
//        var_dump($apiDownload);
//        exit;
        $this->load->helper('download');
        $fileName = strtolower($fileName);

        try {
            $data = file_get_contents($apiDownload);
            $name = $fileName . '.csv';
            force_download($name, $data);
        } catch (Exception $e) {
            show_error($e->getMessage());
        }
//        echo $apiDownload;
//        exit();
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/Retention.php */