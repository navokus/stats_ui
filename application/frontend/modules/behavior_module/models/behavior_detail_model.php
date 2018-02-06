<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Behavior_detail_model extends CI_Model {

	private $table = 'RC_Behavior_Detail';

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
				
		$this->db->select('t1.*, t2.ClassificationName')
				->from($this->table .' t1')
				->join('C_Classification t2','t1.IdClassification = t2.IdClassification AND t2.GameCode="' . $gameCode . '"')
				->where('t1.GameCode', $gameCode)
//				->where('t2.GameCode', $gameCode)
				->where('t1.CalculateDate', $preDate)
				->where('t1.CalculateBy', $times);
		$this->db->order_by('t2.Order', 'asc');

		$query = $this->db->get();
		$result = $query->result_array();

		
		foreach ($result as $value) {
			if($value['IdClassification'])
				$data[$value['IdClassification']][$value['Status']]['pre_date'] = $value;
		}

		
		// get info payment current date
		$this->db->select('t1.*, t2.ClassificationName')
				->from($this->table .' t1')
				->join('C_Classification t2','t1.IdClassification = t2.IdClassification AND t2.GameCode="' . $gameCode . '"')
				->where('t1.GameCode', $gameCode)
//				->where('t2.GameCode', $gameCode)
				->where('t1.CalculateDate', $curDate)
				->where('t1.CalculateBy', $times);
		$this->db->order_by('t2.Order', 'asc');

		$query = $this->db->get();
		$result = $query->result_array();

		
		foreach ($result as $value) {
			if($value['IdClassification'])
				$data[$value['IdClassification']][$value['Status']]['cur_date'] = $value;
		}

		return $data;
	}
	
}

/* End of file paying_model.php */
/* Location: ./application/models/paying_model.php */