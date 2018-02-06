<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Revenue extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		// $this->output->enable_profiler(TRUE);
		$this->load->library('util');
		$this->load->model('game_model', 'game');
		$this->load->model('revenue_model', 'revenue');
	}

	public function index()
	{
		$return['aGames'] = $this->game->listGames();
		$return['optionsWeek'] = $this->util->listOptionsWeek(3);
		$return['optionsMonth'] = $this->util->listOptionsMonth(3);

		if ($this->input->post('options')) {
			$_SESSION['active_account']['post'] = $_POST;
			redirect('Revenue');
		}

		if ($_SESSION['active_account']['post']) {
			$return['post'] = $_SESSION['active_account']['post'];
		} else {
			$return['post']['options'] = 4;
			$return['post']['day'][1] = date('d/m/Y', strtotime("-1 days"));
			$return['post']['day'][2] = date('d/m/Y', strtotime("-10 days"));
		}


		$return['post']['gameCode'] = $this->session->userdata('default_game');

		$gameCode = $return['post']['gameCode'];

		switch ($return['post']['options']) {
			case '5': // week

				// in this week
				$data = $this->revenue->getDataDrawChart($gameCode, $return['post']['options'], $return['post']['week'][1], $return['post']['week'][2]);

				$subTitle = $return['optionsWeek'][$return['post']['week'][2]] . ' - ' .  $return['optionsWeek'][$return['post']['week'][1]];
				$return['time'] = 'Tuần';
				$return['timing'] = 'weekly';
				break;

			case '6': // month

				// in this month
				$data = $this->revenue->getDataDrawChart($gameCode, $return['post']['options'], $return['post']['month'][1], $return['post']['month'][2]);

				$subTitle = 'Tháng ' . date('m/Y', strtotime($return['post']['month']['2'])) . ' - ' . date('m/Y', strtotime($return['post']['month']['1'])) ;
				$return['time'] = 'Tháng';
				$return['timing'] = 'monthly';
				break;

			case '4': // day
			default:
				list($day, $month, $year) = explode('/', $return['post']['day'][1]);
				$toDay = $year . '-' . $month . '-' . $day;
				list($day, $month, $year) = explode('/', $return['post']['day'][2]);
				$formatDay = $year . '-' . $month . '-' . $day;

				$data = $this->revenue->getDataDrawChart($gameCode, $return['post']['options'], $toDay, $formatDay);

				$subTitle = 'Ngày ' . $return['post']['day'][2]  . ' - ' .   $return['post']['day'][1];
				$return['time'] = 'Ngày';
				$return['timing'] = 'daily';
				break;
		}

		$return['title'] = 'Biểu đồ doanh thu game ' . strtoupper($gameCode);
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
				$return['dataAccountFirstCharge'] .= intval($value['TotalAccountFirstCharge']) . ',';
				$return['dataRevenueFirstCharge'] .= intval($value['TotalRevenueFirstCharge']) . ',';
				$return['dataARPPU'] .= round($value['ARPPU'], 2) . ',';
			}

		$return['gameCode'] = strtoupper($gameCode);
		$return['data'] = $data;

		$this->_template['content'] = $this->load->view('revenue/index', $return, TRUE);
		$this->load->view('master_page', $this->_template);
	}
	public function export_data($fileName)
	{

		list($reportType, $gameCode, $times, $curDate) = explode('_', $fileName);
		$apiDownload = $this->config->item('api_url') . 'ub/export/csv?report_type='.$reportType.'&f=yes&game_code='. $gameCode . '&time_value=' . $curDate . '&timing=' . $times;
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