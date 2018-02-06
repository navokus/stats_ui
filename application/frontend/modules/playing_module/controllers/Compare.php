<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Compare extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('playing_model', 'playing');
		$this->load->model('playing_detail_model', 'playing_detail');
		$this->load->model('playing_detail_grade_model', 'playing_detail_grade');
		$this->load->library('api');
	}

	public function index($gameCode, $times, $preDate, $curDate)
	{
		$data = array();

		$_preDate = $preDate;
		$_curDate = $curDate;

		$data['key'] = $gameCode. '_' . $times . '_' . strtotime($preDate) . '_' . strtotime($curDate);
		$data['gameCode'] = $gameCode;

		$TimeConsecutive = 0;
		switch ($times) {
			case 'weekly':

				$data['times'] = 'Tuần';
				$data['color'] = 'primary';
				$preWeek = date('W', strtotime($preDate));
				if(date('w', strtotime($preDate)) == 0) $preWeek++;
				
				$preYear = date('Y', strtotime($preDate));
				$preDate = $preYear . '-' . $preWeek;

				// get week of year cur date
				$curWeek = date('W', strtotime($curDate));
				if(date('w', strtotime($curDate)) == 0) $curWeek++;
				
				$curYear = date('Y', strtotime($curDate));
				$curDate = $curYear . '-' . $curWeek;

				// check week Consecutive
				$tempCurDate = date('Y-W', strtotime($_preDate . "+1 week"));
				if(date('w', strtotime($_preDate . "+1 week")) == 0) 
					$tempCurDate++;

				if($tempCurDate == $curDate) {
					$TimeConsecutive = 1;
				}
								

				break;

			case 'monthly':

				$data['times'] = 'Tháng';
				$data['color'] = 'danger';
				$preDate = date('Y-m', strtotime($preDate));
				$curDate = date('Y-m', strtotime($curDate));

				$tempCurDate = date('Y-m', strtotime("first day of next month ". $_preDate));

				if($tempCurDate == $curDate) {
					$TimeConsecutive = 1;
				}

				break;

			case 'yearly':
				$data['times'] = 'Năm';
				$data['color'] = 'info';
				$preDate = date('Y', strtotime($preDate));
				$curDate = date('Y', strtotime($curDate));
				break;

			default:
				$data['times'] = 'Ngày';
				$data['color'] = 'success';

				$tempCurDate = date('Y-m-d', strtotime( $_preDate . '+1 days'));
				
				if($tempCurDate == $curDate) {
					$TimeConsecutive = 1;
				}

				break;
		}

		$aGrade = $this->api->getGradeInfo($gameCode);
		$data['aGrade'] = $aGrade['play'][$times];
		$data['preDate'] = $preDate;
		$data['curDate'] = $curDate;
		$data['aPlayCompare'] = $this->playing->compare($gameCode, $times, $_preDate, $_curDate);		
		$data['fileExportCurDate'] = 'playtime_'. $gameCode . '_' . $curDate . '_' . $times;	
		$data['fileExportPreDate'] = 'playtime_'. $gameCode . '_' . $preDate . '_' . $times;	

		if($TimeConsecutive == 1) {
			$data['content_playing_detail'] = $this->playing_detail($gameCode, $times, $_preDate, $_curDate, $data['color'], $data['times'], $data['curDate'], $data['key']);
		}		

		if ($_SESSION['playcompare'][$data['key']]) {
			unset($_SESSION['playcompare'][$data['key']]);
		}
		$_SESSION['playcompare'][$data['key']]['content'] = $this->load->view('compare', $data, TRUE);
		$_SESSION['playcompare'][$data['key']]['header'] = $data['gameCode'] . '_' . $data['times'] . '_' . $data['curDate'] . '_' . $data['preDate'];

	}

	public function delCacheCompare($key)
	{
		if (isset($_SESSION['playcompare'][$key])) {
			unset($_SESSION['playcompare'][$key]);
		}
		redirect(site_url('Playing/compare/'));
	}

	public function playing_detail($gameCode, $times, $preDate, $curDate, $color, $timeName, $dateName, $key)
	{
		$data['color'] = $color;
		$data['timeName'] = $timeName;
		$data['curDate'] = $dateName;
		$data['key'] = $key;
		$data['aPlayDetailGrade'] = $this->playing_detail_grade->getPlaytimeDetailGrade($gameCode, $times, $curDate);		
		$data['aPlayDetailCompare'] = $this->playing_detail->compare($gameCode, $times, $preDate, $curDate);

		return $this->load->view('compare_detail', $data, TRUE);
	}

	public function export_data($fileName)
	{

		list($reportType, $gameCode, $curDate, $times) = explode('_', $fileName);
		$apiDownload = $this->config->item('api_url') . 'ub/export/csv?report_type='.$reportType.'&game_code='. $gameCode . '&time_value=' . $curDate . '&timing=' . $times;

		if ($grade) {
			$apiDownload .= '&grade=' . strtolower($grade);
		}
		
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

/* End of file hello.php */
/* Location: ./application/controllers/hello.php */