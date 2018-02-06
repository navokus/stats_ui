<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Retention extends MY_Controller {

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
            $_SESSION['retention']['post'] = $_POST;
            redirect('Retention');
        }

        if ($_SESSION['retention']['post']) {
            $return['post'] = $_SESSION['retention']['post'];
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

                // get data retention login
                $dataRetention = $this->retention->getDataRetentionLogin($gameCode, $return['post']['options'], $return['post']['week']);
                $return['retentionLogin'] = array();
                foreach ($dataRetention as $key => $value) {
                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['retentionLogin'][$key][$v['CalculateValue']] = array(
                                $v['RetentionAccountTotal'],
                                $v['NewAccount'],
                                $v['ReturnAccount']
                            );
                        }
                    }
                }

                // get data retention paying
                $dataRetentionPaying = $this->retention->getDataRetentionPaying($gameCode, $return['post']['options'], $return['post']['week']);
                $return['retentionPaying'] = array();

                foreach ($dataRetentionPaying as $key => $value) {
                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['retentionPaying'][$key][$v['CalculateValue']] = array(
                                $v['AccountTotal'],
                                $v['RepayingAccTotal'],
                                $v['RevenueTotal'],
                                $v['RepayingRevTotal'],
                                $v['NewPaying'],
                                $v['NewPayingRevenue'],
                                $v['ReturnPaying'],
                                $v['ReturnPayingRevenue'],
                            );
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

                // get data retention login
                $dataRetention = $this->retention->getDataRetentionLogin($gameCode, $return['post']['options'], $return['post']['month']);
                $return['retentionLogin'] = array();
                foreach ($dataRetention as $key => $value) {
                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['retentionLogin'][$key][$v['CalculateValue']] = array(
                                $v['RetentionAccountTotal'],
                                $v['NewAccount'],
                                $v['ReturnAccount']
                            );
                        }
                    }
                }

                // get data retention paying
                $dataRetentionPaying = $this->retention->getDataRetentionPaying($gameCode, $return['post']['options'], $return['post']['month']);
                $return['retentionPaying'] = array();

                foreach ($dataRetentionPaying as $key => $value) {
                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['retentionPaying'][$key][$v['CalculateValue']] = array(
                                $v['AccountTotal'],
                                $v['RepayingAccTotal'],
                                $v['RevenueTotal'],
                                $v['RepayingRevTotal'],
                                $v['NewPaying'],
                                $v['NewPayingRevenue'],
                                $v['ReturnPaying'],
                                $v['ReturnPayingRevenue'],
                            );
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

                // get data retention login
                $dataRetention = $this->retention->getDataRetentionLogin($gameCode, $return['post']['options'], $formatDay);
                $return['retentionLogin'] = array();
                foreach ($dataRetention as $key => $value) {
                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['retentionLogin'][$key][$v['CalculateValue']] = array(
                                $v['RetentionAccountTotal'],
                                $v['NewAccount'],
                                $v['ReturnAccount']
                            );
                        }
                    }
                }

                // get data retention paying
                $dataRetentionPaying = $this->retention->getDataRetentionPaying($gameCode, $return['post']['options'], $formatDay);
//         echo "<pre>";
//            var_dump($dataRetentionPaying);
//            exit();
                $return['retentionPaying'] = array();

                foreach ($dataRetentionPaying as $key => $value) {
                    foreach ($value as $k => $v) {
                        if ($v) {
                            $return['retentionPaying'][$key][$v['CalculateValue']] = array(
                                $v['AccountTotal'],
                                $v['RepayingAccTotal'],
                                $v['RevenueTotal'],
                                $v['RepayingRevTotal'],
                                $v['NewPaying'],
                                $v['NewPayingRevenue'],
                                $v['ReturnPaying'],
                                $v['ReturnPayingRevenue'],
                            );
                        }
                    }
                }

                $return['time'] = 'Ngày';
            $return['timing'] = 'daily';
                break;
        }

        $return['gameCode'] = strtoupper($gameCode);
        $this->_template['content'] = $this->load->view('retention/index', $return, TRUE);
        $this->load->view('master_page', $this->_template);
    }
    public function export_data($fileName)
    {

        list($reportType, $gameCode,  $times, $curDate, $preDate) = explode('_', $fileName);
        $apiDownload = $this->config->item('api_url')
            . 'ub/export_retention/csv?report_type='.$reportType
            .'&f=yes&game_code='. $gameCode
            . '&time_value=' . $curDate
            . '&pre_time_value=' . $preDate
            . '&timing=' . $times;

//        var_dump($apiDownload , $fileName);
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/Retention.php */