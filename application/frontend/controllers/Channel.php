<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Channel extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		// $this->output->enable_profiler(TRUE);
		$this->load->library('util');
		$this->load->model('game_model', 'game');
		$this->load->model('payment_model', 'payment');
		$this->load->model('retention_model', 'retention');
	}

	public function index()
	{
		$return['aGames'] = $this->game->listGames();
		$return['optionsWeek'] = $this->util->listOptionsWeek();
		$return['optionsMonth'] = $this->util->listOptionsMonth();

		if ($this->input->post('options')) {
			$_SESSION['channel']['post'] = $_POST;
			redirect('Channel');
		}

		if ($_SESSION['channel']['post']) {
			$return['post'] = $_SESSION['channel']['post'];
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

				// get data pay frequency
				$return['payMethod']    = $this->retention->getDataPayChannel($gameCode, $return['post']['options'], $return['post']['week']);

				$return['time'] = 'Tuần';
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

				// get data pay frequency
				$return['payMethod']    = $this->retention->getDataPayChannel($gameCode, $return['post']['options'], $return['post']['month']);

				$return['time'] = 'Tháng';
				break;

			case '4': // day
			default:
				list($day, $month, $year) = explode('/', $return['post']['day']);
				$formatDay = $year . '-' . $month . '-' . $day;

				// get data pay frequency
				$return['payMethod']    = $this->retention->getDataPayChannel($gameCode, $return['post']['options'], $formatDay);

				$return['time'] = 'Ngày';
				break;
		}

		$return['gameCode'] = strtoupper($gameCode);
		$this->_template['content'] = $this->load->view('channel/index', $return, TRUE);
		$this->load->view('master_page', $this->_template);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/Retention.php */