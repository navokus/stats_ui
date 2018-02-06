<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Revenue_model extends CI_Model {

	private $table = 'RC_Revenue_Users';

	public function __construct()
	{
		parent::__construct();
		
	}

	public function getDataDrawChart($gameCode, $timming, $calculateDateTo, $calculateDateFrom)
	{
		$aTiming = array('4' => 'daily', '5' => 'weekly', '6' => 'monthly', '7' => 'yearly');

		$this->db->select('AccountTotal as AccountTotalAllGrade, RevenueTotal as RevenueTotalAllGrade, CalculateDate, CalculateValue, TotalAccountFirstCharge, TotalRevenueFirstCharge, ARPPU');
		$this->db->from($this->table);
		$this->db->where('GameCode', $gameCode);
		$this->db->where('CalculateBy', $aTiming[$timming]);
		$this->db->where('CalculateDate >=', $calculateDateFrom);
		$this->db->where('CalculateDate <=', $calculateDateTo);
		$this->db->order_by('CalculateDate', 'asc');
		$query = $this->db->get();
		$result = $query->result_array();

		return $result;
	}

}

/* End of file payment_model.php */
/* Location: ./application/models/payment_model.php */