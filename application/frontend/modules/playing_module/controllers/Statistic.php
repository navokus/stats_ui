<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistic extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('playing_model', 'playing');
	}

	public function index($gameCode, $times, $curDate)
	{
		$data = array();

		$_curDate = $curDate;

		$data['key'] = $gameCode. '_' . $times . '_' . strtotime($curDate);
		$data['gameCode'] = $gameCode;

		switch ($times) {
			case 'weekly':
				$data['times'] = 'Tuần';
				$data['color'] = 'primary';
				
				// get week of year cur date
				$week = date('W', strtotime($curDate));
				if(date('w', strtotime($curDate)) == 0) $week++;

				$year = date('Y', strtotime($curDate));
				$curDate = $year . '-' . $week;
				
				break;

			case 'monthly':
				$data['times'] = 'Tháng';
				$data['color'] = 'danger';
				$curDate = date('Y-m', strtotime($curDate));

				break;

			case 'yearly':
				$data['times'] = 'Năm';
				$data['color'] = 'info';
				$curDate = date('Y', strtotime($curDate));
				break;

			default:
				$data['times'] = 'Ngày';
				$data['color'] = 'success';
				break;
		}

		$data['curDate'] = $curDate;

		$data['aGrades'] = $this->playing->getPlayInfoByDate($gameCode, $times, $_curDate);
				
		if ($_SESSION['playStatistic'][$data['key']]) {
			unset($_SESSION['playStatistic'][$data['key']]);
		}
		$_SESSION['playStatistic'][$data['key']]['content'] = $this->load->view('statistic', $data, TRUE);
		$_SESSION['playStatistic'][$data['key']]['header']  = $data['gameCode'] . "_" . $data['times'] . '_' . $data['curDate'];
	}

	public function delCacheStatistic($key)
	{
		if (isset($_SESSION['playStatistic'][$key])) {
			unset($_SESSION['playStatistic'][$key]);
		}
		redirect(site_url('Playing/statistic/'));
	}
}

/* End of file Statistic.php */
/* Location: ./application/controllers/Statistic.php */