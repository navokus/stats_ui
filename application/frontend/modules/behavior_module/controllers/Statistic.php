<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistic extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('behavior_model', 'behavior');
		$this->load->model('behavior_detail_model', 'behavior_detail');
	}

	public function index($gameCode, $times, $curDate)
	{
		$data = array();

		$_curDate = $curDate;

		$data['key'] = $gameCode. $times . strtotime($curDate);
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

		$data['preDate'] = $preDate;
		$data['curDate'] = $curDate;

		$data['aGrades'] = $this->behavior->getBehaviorInfoByDate($gameCode, $times, $_curDate);
		if ($_SESSION['behaviorStatistic'][$data['key']]) {
			unset($_SESSION['behaviorStatistic'][$data['key']]);
		}

		$_SESSION['behaviorStatistic'][$data['key']]['content'] = $this->load->view('statistic', $data, TRUE);
		$_SESSION['behaviorStatistic'][$data['key']]['header']  = $data['gameCode'] . "_" . $data['times'] . '_' . $data['curDate'];
	}

	public function delCacheStatistic($key)
	{
		if (isset($_SESSION['behaviorStatistic'][$key])) {
			unset($_SESSION['behaviorStatistic'][$key]);
		}
		redirect(site_url('Behavior/statistic/'));
	}
}

/* End of file Statistic.php */
/* Location: ./application/controllers/Statistic.php */