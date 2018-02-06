<?php

/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 14/03/2016
 * Time: 23:29
 */

class KpiReport extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
//		 $this->output->enable_profiler(TRUE);
        $this->load->model('game_model', 'game');
        $this->load->model('kpi_report_model', 'kpi_report');
        $this->load->library('form_validation');
        $this->load->library('util');
    }

    public function index(){
        $this->active_user();
    }
    public function active_user()
    {

        $return['aGames'] = $this->game->listGames();
        $return['optionsWeek'] = $this->util->listOptionsWeek();
        $return['optionsMonth'] = $this->util->listOptionsMonth();

        if ($this->input->post('options')) {
            $_SESSION['kpi_report']['post'] = $_POST;
            redirect('KpiReport');
        }

        if ($_SESSION['kpi_report']['post']) {
            $return['post'] = $_SESSION['kpi_report']['post'];
        } else {
            $return['post']['options'] = 4;
            $return['post']['day'][1] = date('d/m/Y', strtotime("-1 days"));
            $return['post']['day'][2] = date('d/m/Y', strtotime("-15 days"));
        }


        $return['post']['gameCode'] = $this->session->userdata('default_game');
        $gameCode = $return['post']['gameCode'];


        switch ($return['post']['options']) {
            case '4':
            default:
                list($day, $month, $year) = explode('/', $return['post']['day'][1]);
                $toDay = $year . '-' . $month . '-' . $day;
                list($day, $month, $year) = explode('/', $return['post']['day'][2]);
                $fromDay = $year . '-' . $month . '-' . $day;
                $subTitle = 'Ngày ' . $return['post']['day'][2]  . ' - ' .   $return['post']['day'][1];
                $return['time'] = 'Ngày';
                $return['timing'] = 'daily';
                $data = $this->kpi_report->getDailyDataDrawChart($gameCode, $return['post']['options'], $fromDay,$toDay);
                $return['title'] = 'CHỈ SỐ A1 GAME ' . strtoupper($gameCode);
                break;
            case 5:
                $toDay = $return['post']['week'][1];
                $fromDay = $return['post']['week'][2];
                $subTitle = $return['optionsWeek'][$return['post']['week'][2]] . ' - ' .  $return['optionsWeek'][$return['post']['week'][1]];
                $return['time'] = 'Tuần';
                $return['timing'] = 'weekly';
                $return['title'] = 'CHỈ SỐ A7 GAME ' . strtoupper($gameCode);
                $data = $this->kpi_report->getWeeklyDataDrawChart($gameCode, $return['post']['options'], $fromDay,$toDay);
                break;

            case 6:
                $toDay = $return['post']['month'][1];
                $fromDay = $return['post']['month'][2];
                $subTitle = 'Tháng ' . date('m/Y', strtotime($return['post']['month']['2'])) . ' - ' . date('m/Y', strtotime($return['post']['month']['1'])) ;
                $return['time'] = 'Tháng';
                $return['timing'] = 'monthly';
                $return['title'] = 'CHỈ SỐ A30 GAME ' . strtoupper($gameCode);
                $data = $this->kpi_report->getMonthlyDataDrawChart($gameCode, $return['post']['options'], $fromDay,$toDay);
                break;
        }


        $return['subTitle'] = $subTitle;

        if ($data) {
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
                $return['dataUserActive'] .= intval($value['ActiveAccountTotal']) . ',';
                $return['dataRoleRegister'] .= intval($value['NewRoleRegister']) . ',';
            }
        }

        $return['gameCode'] = strtoupper($gameCode);
        $return['data'] = $data;
        $this->_template['content'] = $this->load->view('kpi_report/index', $return, TRUE);
        $this->load->view('master_page', $this->_template);
    }
}