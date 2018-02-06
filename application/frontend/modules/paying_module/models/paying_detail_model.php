<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paying_detail_model extends CI_Model {

	private $table = 'RC_Payment_Detail';
	private $table_statis = 'RS_Payment_Detail';

	public function __construct()
	{
		parent::__construct();
		
	}

	/*
		$gameCode : vlcm, gn ....
		$times : daily, monthly, weekly, yearly
		$preDate, $curDate : Y-m-d
	*/
	public function compare($gameCode, $times, $preDate, $curDate)
	{
		$data = array();
				
		$this->db->select('t1.*, t2.GradeName')
				->from($this->table . ' t1')
				->join('C_Grade t2','t1.IdGrade = t2.IdGrade AND t2.GradeType = 1 AND t2.GameCode="' . $gameCode . '"')
				->where('t1.GameCode', $gameCode)
//				->where('t2.GameCode', $gameCode)
				->where('t1.CalculateDate', $preDate)
				->where('t1.CalculateBy', $times);
		$this->db->order_by('t2.Order', 'asc');

		$query = $this->db->get();
		$result = $query->result_array();

		
		foreach ($result as $value) {
			if($value['IdGrade'])
				$data[$value['IdGrade']][$value['Status']]['pre_date'] = $value;
		}

		
		// get info payment current date
		$this->db->select('t1.*, t2.GradeName')
				->from($this->table . ' t1')
				->join('C_Grade t2','t1.IdGrade = t2.IdGrade AND t2.GradeType = 1 AND t2.GameCode="' . $gameCode . '"')
				->where('t1.GameCode', $gameCode)
//				->where('t2.GameCode', $gameCode)
				->where('t1.CalculateDate', $curDate)
				->where('t1.CalculateBy', $times);
		$this->db->order_by('t2.Order', 'asc');

		$query = $this->db->get();
		$result = $query->result_array();

		
		foreach ($result as $value) {
			if($value['IdGrade'])
				$data[$value['IdGrade']][$value['Status']]['cur_date'] = $value;
		}

		return $data;
	}

	public function getStatisChannelByDate($gameCode, $times, $curDate)
	{
	

		$this->db->select('*')
				->from($this->table_statis)
				->where('GameCode', $gameCode)
				->where('CalculateDate', $curDate)
				->where('CalculateBy', $times);
		$this->db->order_by('ChargedTime asc, ByChannel asc');

		$query = $this->db->get();
		$rs = $query->result_array();

		$data = array();
		foreach ($rs as $value) {
			$data[$value['ChargedTime']][$value['ByChannel']] = $value;
		}

		return $data;
	}	
	
}

/* End of file paying_model.php */
/* Location: ./application/models/paying_model.php */