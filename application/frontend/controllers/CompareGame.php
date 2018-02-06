<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CompareGame extends MY_Controller {

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
			$_SESSION['compare_game']['post'] = $_POST;
			redirect('CompareGame');
		}

		if ($_SESSION['compare_game']['post']) {
			$return['post'] = $_SESSION['compare_game']['post'];
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

				// get data compare all game
				$return['compareAllGame'] = $this->retention->getDataCompareAllGame($return['aGames'], $return['post']['week']);


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

				// get data compare all game
				$return['compareAllGame'] = $this->retention->getDataCompareAllGame($return['aGames'], $return['post']['month']);

				$return['time'] = 'Tháng';
				break;

			case '4': // day
			default:
				list($day, $month, $year) = explode('/', $return['post']['day']);
				$formatDay = $year . '-' . $month . '-' . $day;

				// get data compare all game
				$return['compareAllGame'] = $this->retention->getDataCompareAllGame($return['aGames'], $formatDay);


			$return['time'] = 'Ngày';
				break;
		}

		$return['gameCode'] = strtoupper($gameCode);
		$this->_template['content'] = $this->load->view('compare_game/index', $return, TRUE);
		$this->load->view('master_page', $this->_template);
	}

	public function compareProductOneGame($gameCodeCompare)
	{
		if ($_SESSION['compare_game']['post']) {
			$return['post'] = $_SESSION['compare_game']['post'];
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

		$this->load->view('compare_game/compare_one_game', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/Retention.php */