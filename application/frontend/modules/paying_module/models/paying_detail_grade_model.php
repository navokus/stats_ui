<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paying_detail_grade_model extends CI_Model {

	public $table = 'RC_Payment_Detail_Grade';

	public function __construct()
	{
		parent::__construct();
		
	}

	public function getPaymentDetailGrade($gameCode, $times, $curDate)
	{
		
		
		$this->db->select('t1.*, t2.GradeName')
				->from($this->table . ' t1')
				->join('C_Grade t2','t1.IdGradePrevious = t2.IdGrade AND t2.GradeType = 1 AND t2.GameCode="' . $gameCode . '"', 'left')
				->where('t1.GameCode', $gameCode)
//				->where('t2.GameCode', $gameCode)
				->where('t1.CalculateDate', $curDate)
				->where('t1.CalculateBy', $times);				
		$this->db->order_by('t2.Order asc, t1.IdGradePrevious asc');

		$query = $this->db->get();
		$rs = $query->result_array();

		$data = array();
		foreach ($rs as $key => $value) {
			$data[$value['IdGrade']][$value['Status']][$value['GradeName']] = $value['AccountTotal'];
		}

		return $data;
	}
}

/* End of file paying_detail_grade_model.php */
/* Location: ./application/models/paying_detail_grade_model.php */