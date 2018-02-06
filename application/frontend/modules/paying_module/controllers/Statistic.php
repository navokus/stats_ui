<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistic extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('paying_model', 'paying');
		$this->load->model('paying_detail_model', 'paying_detail');
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

		$data['aGrades'] = $this->paying->getPayInfoByDate($gameCode, $times, $_curDate);
		$data['aStatis'] = $this->paying->getStatisByDate($gameCode, $times, $_curDate);
		$data['aStatisChannel'] = $this->paying_detail->getStatisChannelByDate($gameCode, $times, $_curDate);


		if ($_SESSION['payStatistic'][$data['key']]['content']) {
			unset($_SESSION['payStatistic'][$data['key']]);
		}
		$_SESSION['payStatistic'][$data['key']]['content'] = $this->load->view('statistic', $data, TRUE);
		$_SESSION['payStatistic'][$data['key']]['header']  = $data['gameCode'] . "_" . $data['times'] . '_' . $data['curDate'];
	}

	public function delCacheStatistic($key)
	{
		if (isset($_SESSION['payStatistic'][$key])) {
			unset($_SESSION['payStatistic'][$key]);
		}
		redirect(site_url('Paying/statistic/'));
	}
}

/* End of file Statistic.php */
/* Location: ./application/controllers/Statistic.php */