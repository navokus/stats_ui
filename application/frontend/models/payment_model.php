<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment_model extends CI_Model {

	private $table = 'RC_Payment';

	public function __construct()
	{
		parent::__construct();
		
	}

	public function getDataDrawChart($gameCode, $timming, $calculateDate, $limit = 10)
	{
		$this->table = 'RC_Revenue_Users';
		$aTiming = array('4' => 'daily', '5' => 'weekly', '6' => 'monthly', '7' => 'yearly');

		//add by tuonglv, 2016-04-15
		$limit_arr = $this->userconstants->get_default_day_number();
		// chi thay doi gia tri limit khi no la default
		$limit = ($limit == 10) ?  $limit_arr[$timming] : $limit;
		//end

		$aCalculateDate = array();
		switch ($timming) {
			case '4': // day

				for ($i=1; $i <= $limit; $i++) { 

					$this->db->select('AccountTotal as AccountTotalAllGrade, RevenueTotal as RevenueTotalAllGrade, CalculateDate, CalculateValue, TotalAccountFirstCharge, TotalRevenueFirstCharge, ARPPU');
					$this->db->from($this->table);
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('CalculateValue', $calculateDate);
					$this->db->order_by('Id', 'desc');
					$this->db->limit('1', 0);
					$query = $this->db->get();
					$rs = $query->row_array();
					if($rs)
						$aCalculateDate[$i] = $rs;
					else
						$aCalculateDate[$i] = array(
							'CalculateDate' => $calculateDate,
							'RevenueTotalAllGrade' => 0,
							'AccountTotalAllGrade' => 0,
							'CalculateValue' => $calculateDate
						);

					// get data playing time
					$this->db->select('AccountTotal as ActiveAccountTotal, RoleTotal as ActiveRoleTotal');
					$this->db->from('RC_Active_Users');
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('CalculateValue', $calculateDate);
					$this->db->order_by('Id', 'desc');
					$this->db->limit('1', 0);
					$query = $this->db->get();
					$rs = $query->row_array();

					if($rs) {
						$aCalculateDate[$i] = array_merge($aCalculateDate[$i], $rs);
					}

					$calculateDate = date('Y-m-d', strtotime('-1 day '. $calculateDate));					
				}

				break;
			
			case '5': // week

				for ($i=1; $i <= $limit; $i++) {

					$this->db->select('AccountTotal as AccountTotalAllGrade, RevenueTotal as RevenueTotalAllGrade, CalculateDate, CalculateValue, TotalAccountFirstCharge, TotalRevenueFirstCharge, ARPPU');
					$this->db->from($this->table);
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('CalculateValue', date('Y-W', strtotime($calculateDate)));
					$this->db->order_by('Id', 'desc');
					$this->db->limit('1', 0);
					$query = $this->db->get();
					$rs = $query->row_array();
					if($rs)
						$aCalculateDate[$i] = $rs;
					else
						$aCalculateDate[$i] = array(
							'CalculateDate' => $calculateDate,
							'RevenueTotalAllGrade' => 0,
							'AccountTotalAllGrade' => 0,
							'CalculateValue' => date('W, Y', strtotime($calculateDate))
						);

					// get data playing time
					$this->db->select('AccountTotal as ActiveAccountTotal, RoleTotal as ActiveRoleTotal');
					$this->db->from('RC_Active_Users');
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('CalculateValue', date('Y-W', strtotime($calculateDate)));
					$this->db->order_by('Id', 'desc');
					$this->db->limit('1', 0);
					$query = $this->db->get();
					$rs = $query->row_array();

					if($rs) {
						$aCalculateDate[$i] = array_merge($aCalculateDate[$i], $rs);
					}

					$calculateDate = date('Y-m-d', strtotime('-7 days '. $calculateDate));	

					$isSaturday = date('w', strtotime($calculateDate));
					if ($isSaturday != 6) {						
						$calculateDate = date('Y-m-d', strtotime('this week next saturday'. $calculateDate));
					}
				}

				break;

			case '6': // month

				for ($i=1; $i <= $limit; $i++) {
					
					$this->db->select('AccountTotal as AccountTotalAllGrade, RevenueTotal as RevenueTotalAllGrade, CalculateDate, CalculateValue, TotalAccountFirstCharge, TotalRevenueFirstCharge, ARPPU');
					$this->db->from($this->table);
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('CalculateValue', date('Y-m', strtotime($calculateDate)));
					$this->db->order_by('Id', 'desc');
					$this->db->limit('1', 0);
					$query = $this->db->get();
					$rs = $query->row_array();
					if($rs)
						$aCalculateDate[$i] = $rs;
					else
						$aCalculateDate[$i] = array(
							'CalculateDate' => $calculateDate,
							'RevenueTotalAllGrade' => 0,
							'AccountTotalAllGrade' => 0,
							'CalculateValue' => date('Y-m', strtotime($calculateDate))
						);

					// get data playing time`
					$this->db->select('AccountTotal as ActiveAccountTotal, RoleTotal as ActiveRoleTotal');
					$this->db->from('RC_Active_Users');
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('CalculateValue', date('Y-m', strtotime($calculateDate)));
					$this->db->order_by('Id', 'desc');
					$this->db->limit('1', 0);
					$query = $this->db->get();
					$rs = $query->row_array();

					if($rs) {
						$aCalculateDate[$i] = array_merge($aCalculateDate[$i], $rs);
					}

					$calculateDate = date('Y-m-d', strtotime('-31 days '. $calculateDate));
					$calculateDate = date('Y-m-t', strtotime($calculateDate));
					
				}
				
				break;
			
		}
		//$sql = $this->db->last_query();
		//file_put_contents("/tmp/tuonglv/sql.txt",$sql . "\n\n",FILE_APPEND);

		$result = array_reverse($aCalculateDate, true);

		return $result;
	}

	public function getPayInfoByDate($gameCode, $times, $curDate)
	{
		
		$this->db->select('t1.*, t2.GradeName')
				->from('RC_Payment t1')
				->join('C_Grade t2','t1.IdGrade = t2.IdGrade AND t2.GradeType = 1')
				->where('t1.GameCode', $gameCode)
				->where('t2.GameCode', $gameCode)
				->where('t1.CalculateDate', $curDate)
				->where('t1.CalculateBy', $times);
		$this->db->order_by('t2.Order', 'asc');

		$query = $this->db->get();
		return $query->result_array();
	}

	public function getStatisByDate($gameCode, $times, $curDate)
	{

		$this->db->select('*')
				->from('RS_Payment')
				->where('GameCode', $gameCode)
				->where('CalculateDate', $curDate)
				->where('CalculateBy', $times);
		$this->db->order_by('ChargedTime', 'asc');

		$query = $this->db->get();
		return $query->result_array();

	}

	public function getStatisChannelByDate($gameCode, $times, $curDate)
	{
	
		$this->db->select('*')
				->from('RS_Payment_Detail')
				->where('GameCode', $gameCode)
				->where('CalculateDate', $curDate)
				->where('CalculateBy', $times);
		$this->db->order_by('ChargedTime', 'asc');

		$query = $this->db->get();
		$rs = $query->result_array();

		$data = array();
		foreach ($rs as $value) {
			$data[$value['ChargedTime']][$value['ByChannel']] = $value;
		}

		return $data;
	}	

	public function getNewestCalculateDateByTime($gameCode, $calculateBy, $calculateValue)
	{
		
		$this->db->select('CalculateDate')
				->from('RC_Payment')
				->where('GameCode', $gameCode)
				->where('CalculateValue', $calculateValue)
				->where('CalculateBy', $calculateBy);
		$this->db->order_by('CalculateDate', 'desc');

		$query = $this->db->get();
		$row = $query->row_array();

		return $row['CalculateDate'];
	}

}

/* End of file payment_model.php */
/* Location: ./application/models/payment_model.php */