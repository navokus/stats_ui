<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Compare extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('behavior_model', 'behavior');
		$this->load->model('behavior_detail_model', 'behavior_detail');
		$this->load->model('behavior_detail_grade_model', 'behavior_detail_grade');
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

		$data['preDate'] = $preDate;
		$data['curDate'] = $curDate;
		$data['aBehaviorCompare'] = $this->behavior->compare($gameCode, $times, $_preDate, $_curDate);		
		
		$data['fileExportCurDate'] = 'behavior_'. $gameCode . '_' . $curDate . '_' . $times;	
		$data['fileExportPreDate'] = 'behavior_'. $gameCode . '_' . $preDate . '_' . $times;

		if($TimeConsecutive == 1) {
			$data['content_behavior_detail'] = $this->behavior_detail($gameCode, $times, $_preDate, $_curDate, $data['color'], $data['times'], $data['curDate'], $data['key']);
		}		

		if ($_SESSION['behaviorcompare'][$data['key']]) {
			unset($_SESSION['behaviorcompare'][$data['key']]);
		}

		$_SESSION['behaviorcompare'][$data['key']]['content'] = $this->load->view('compare', $data, TRUE);
		$_SESSION['behaviorcompare'][$data['key']]['header']  = $data['gameCode'] . '_' . $data['times'] . '_' . $data['curDate'] . '_' . $data['preDate'];

	}

	public function delCacheCompare($key)
	{
		if (isset($_SESSION['behaviorcompare'][$key])) {
			unset($_SESSION['behaviorcompare'][$key]);
		}
		redirect(site_url('Behavior/compare/'));
	}

	public function behavior_detail($gameCode, $times, $preDate, $curDate, $color, $timeName, $dateName, $key)
	{
		$data['color'] = $color;
		$data['timeName'] = $timeName;
		$data['curDate'] = $dateName;
		$data['key'] = $key;
		$data['aBehaviorDetailGrade'] = $this->behavior_detail_grade->getBehaviorDetailGrade($gameCode, $times, $curDate);		
		$data['aBehaviorDetailCompare'] = $this->behavior_detail->compare($gameCode, $times, $preDate, $curDate);

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