<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Playing extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
//		 $this->output->enable_profiler(TRUE);
		$this->load->model('game_model', 'game');
		$this->load->library('form_validation');
		$this->load->library('util');
	}

	public function index()
	{
		$this->compare();
	}

	public function compare()
	{

		$return['content'] = '';
		$return['aGames'] = $this->game->listGames();

		if($this->input->post('game')){
			// check game code permission
			$check = false;
			foreach ($return['aGames'] as $value) {
				if ($value['GameCode'] == $this->input->post('game')) {
					$check = true;
					break;
				}
			}

			if ($check == false) {
				show_error('You do not permission!!!');
			}

			// save post to session
			$_SESSION['post'] = $_POST;

			$gameCode = $this->input->post('game');
			$options = $this->input->post('options', '3');

			switch ($options) {
				case '1': // compare Today vs Yesterday
					$times = 'daily';
					$_curDate = $curDate = date('Y-m-d', strtotime('-1 day '. date('Y-m-d')));
					$_preDate = $preDate = date('Y-m-d', strtotime('-1 day '. $curDate));

					break;
				case '2': // compare Cur Week vs Pre Week
					$times = 'weekly';
					$_curDate = $curDate = date('Y-m-d', strtotime('last Saturday'));
					$_preDate = $preDate = date('Y-m-d', strtotime('-1 week '. $curDate));

					if(date('w', strtotime($preDate)) != 6)
						$preDate = date('Y-m-d', strtotime('this week next saturday'. $preDate));

					break;

				case '4':  // compare 2 days
					$times = 'daily';
					$dateRange = $this->input->post('date_range');

					$day = $this->input->post('day');

					list($iDay, $iMonth, $iYear) = explode('/', $day[2]);
					$preDate = $_preDate = $iYear . '-'. $iMonth . '-' . $iDay;

					list($iDay, $iMonth, $iYear) = explode('/', $day[1]);
					$curDate = $_curDate = $iYear . '-'. $iMonth . '-' . $iDay;

					break;
				case '5': // compare 2 weeks
					$times = 'weekly';

					$day = $this->input->post('week');

					$preDate = $_preDate = $day[2];
					$curDate = $_curDate = $day[1];

					if(date('w', strtotime($preDate)) != 6)
						$preDate = date('Y-m-d', strtotime('this week next saturday'. $preDate));  

					if(date('w', strtotime($curDate)) != 6)
						$curDate = date('Y-m-d', strtotime('this week next saturday'. $curDate));


					$week = date('W', strtotime($preDate));
					$year = date('Y', strtotime($preDate));
					$_preDate = $year . '-' . $week;

					// get week of year cur date
					$week = date('W', strtotime($curDate));
					$year = date('Y', strtotime($curDate));
					$_curDate = $year . '-' . $week;
					

					break;
				case '6': // compare two months
					$times = 'monthly';

					$day = $this->input->post('month');

					$preDate = $_preDate = $day[2];
					$curDate = $_curDate = $day[1];

					$_preDate = date('Y-m', strtotime($preDate));
					$_curDate = date('Y-m', strtotime($curDate));

					break;
				case '3': // compare Cur Month vs Pre Month
				default:
					$times = 'monthly';
					$_curDate = $curDate = date('Y-m-d', strtotime('last Saturday'));
					$_preDate = $preDate = date('Y-m-t', strtotime("first day of last month" . $curDate));
					break;
			}

			if ($_preDate == $_curDate) {
				$_SESSION['message'] = 'Vui lòng chọn thời gian khác nhau để so sánh';
			}
			
			if(isset($gameCode) &&  isset($times) &&  isset($preDate) &&  isset($curDate) && $_preDate != $_curDate ) {
				$this->load->module('playing_module/Compare/index', array($gameCode, $times, $preDate, $curDate), TRUE);
			}

			// redirect('Playing/compare', 'refresh');
		}

		if (isset($_SESSION['playcompare'])) {

			$aPlayCompare = array_reverse($_SESSION['playcompare']);

			$i = 1;
			foreach ($aPlayCompare as $key => $value) {
				if ($this->uri->segment(3) == $key || (!$this->uri->segment(3) && $i == 1)) {
					$return['content'] = $value['content'];
				}
				$i++;
			}
		}

		if(isset($_SESSION['post'])) {
			$return['post'] = $_SESSION['post'];
		}
		
		
		$return['optionsWeek'] = $this->util->listOptionsWeek();
		$return['optionsMonth'] = $this->util->listOptionsMonth();
		
		$this->_template['content'] = $this->load->view('playing/compare', $return, TRUE);

		$this->load->view('master_page', $this->_template);
	}

	public function statistic()
	{
		
		$return['content'] = '';
		$return['aGames'] = $this->game->listGames();

		if($this->input->post('game')){

			$check = false;
			foreach ($return['aGames'] as $value) {
				if ($value['GameCode'] == $this->input->post('game')) {
					$check = true;
					break;
				}
			}

			if ($check == false) {
				show_error('You do not permission!!!');
			}
			
			$_SESSION['post_statis'] = $_POST;

			$gameCode = $this->input->post('game');
			$options = $this->input->post('options');

			switch ($options) {
				case '1': // today
					$times = 'daily';
					$curDate = date('Y-m-d', strtotime('-1 day '. date('Y-m-d')));
					
					break;
				case '2': // this week
					$times = 'weekly';
					$curDate = date('Y-m-d', strtotime('last Saturday'));
					
					break;
				case '4':  // chose date
					$times = 'daily';
					// if($this->input->post('date'))
					// 	$curDate = $this->input->post('date');

					if($this->input->post('day')) {
						$day = $this->input->post('day');					
						list($iDay, $iMonth, $iYear) = explode('/', $day[1]);
						$curDate = $_curDate = $iYear . '-'. $iMonth . '-' . $iDay;
					}
					
					
					break;
				case '5': // chose weeks
					$times = 'weekly';
					if($this->input->post('week')) {
						$day = $this->input->post('week');
						$curDate = $_curDate = $day[1];

						if(date('w', strtotime($curDate)) != 6)
							$curDate = date('Y-m-d', strtotime('this week next saturday'. $curDate));
					}
					

					break;
				case '6': // chose month
					$times = 'monthly';

					$day = $this->input->post('month');

					if($this->input->post('month')) {
						$curDate = $_curDate = $day[1];
						$curDate = date('Y-m-t', strtotime($curDate));
					}
					break;
				case '3': // this month
				default:
					$times = 'monthly';
					$curDate = date('Y-m-d', strtotime('last Saturday'));

					break;
			}
			
			if(isset($gameCode) &&  isset($times) &&  isset($curDate)) {
				$this->load->module('playing_module/Statistic/index', array($gameCode, $times, $curDate), TRUE);
			}

			if (!$curDate) {
				$_SESSION['message'] = 'Vui lòng chọn thời gian';
			}
			

			// redirect('Playing/statistic', 'refresh');
		}

		if (isset($_SESSION['playStatistic'])) {

			$aPlayStatistic = array_reverse($_SESSION['playStatistic']);

			$i = 1;
			foreach ($aPlayStatistic as $key => $value) {
				if ($this->uri->segment(3) == $key || (!$this->uri->segment(3) && $i == 1)) {
					$return['content'] = $value['content'];
				}
				$i++;
			}
		}

		if(isset($_SESSION['post_statis'])) {
			$return['post'] = $_SESSION['post_statis'];
		}

				
		$return['optionsWeek'] = $this->util->listOptionsWeek();
		$return['optionsMonth'] = $this->util->listOptionsMonth();

		$this->_template['content'] = $this->load->view('playing/statistic', $return, TRUE);

		$this->load->view('master_page', $this->_template);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */