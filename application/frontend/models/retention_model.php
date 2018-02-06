<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Retention_model extends CI_Model {

	private $table;

	public function __construct()
	{
		parent::__construct();
		
	}

	public function getDataRetentionLogin($gameCode, $timming, $FirstLoginValue, $limit = 10)
	{
		$this->table = 'RC_Retention_Login';
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
					$this->db->select('CalculateValue, FirstLoginValue, RetentionAccountTotal, NewAccount, ReturnAccount');
					$this->db->from($this->table);	
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('FirstLoginValue', $FirstLoginValue);

					$query = $this->db->get();
					$rs = $query->result_array();

					$aCalculateDate[$FirstLoginValue] = $rs;

					$FirstLoginValue = date('Y-m-d', strtotime('-1 day '. $FirstLoginValue));
				}
			break;

			case '5': // week

				for ($i=1; $i <= $limit; $i++) {
					$this->db->select('CalculateValue, FirstLoginValue, RetentionAccountTotal, NewAccount, ReturnAccount');
					$this->db->from($this->table);	
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('FirstLoginValue',  date('Y-W', strtotime($FirstLoginValue)) );

					$query = $this->db->get();
					$rs = $query->result_array();

					$aCalculateDate[date('Y-W', strtotime($FirstLoginValue))] = $rs;

					$FirstLoginValue = date('Y-m-d', strtotime('-7 days '. $FirstLoginValue));	

					$isSaturday = date('w', strtotime($FirstLoginValue));
					if ($isSaturday != 6) {		
						$FirstLoginValue = date('Y-m-d', strtotime('this week next saturday'. $FirstLoginValue));
					}
				}

			break;

			case '6': // month

				for ($i=1; $i <= $limit; $i++) {
					$this->db->select('CalculateValue, FirstLoginValue, RetentionAccountTotal, NewAccount, ReturnAccount');
					$this->db->from($this->table);	
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('FirstLoginValue',  date('Y-m', strtotime($FirstLoginValue)) );

					$query = $this->db->get();
					$rs = $query->result_array();

					$aCalculateDate[date('Y-m', strtotime($FirstLoginValue))] = $rs;

					$FirstLoginValue = date('Y-m-d', strtotime('-31 days '. $FirstLoginValue));
					$FirstLoginValue = date('Y-m-t', strtotime($FirstLoginValue));
				}

			break;
		}
		

		$result = array_reverse($aCalculateDate, true);

		return $result;
	}


	public function getDataRetentionPaying($gameCode, $timming, $FirstPayingValue, $limit = 10)
	{
		$this->table = 'RC_Retention_Paying';
		$aTiming = array('4' => 'daily', '5' => 'weekly', '6' => 'monthly', '7' => 'yearly');

		$aCalculateDate = array();

		//add by tuonglv, 2016-04-15
		$limit_arr = $this->userconstants->get_default_day_number();
		// chi thay doi gia tri limit khi no la default
		$limit = ($limit == 10) ?  $limit_arr[$timming] : $limit;
		//end


		switch ($timming) {
			case '4': // day
				for ($i=1; $i <= $limit; $i++) {
					$this->db->select('CalculateValue, FirstPayingValue, AccountTotal, RepayingAccTotal, RevenueTotal, RepayingRevTotal, NewPaying, NewPayingRevenue, ReturnPaying, ReturnPayingRevenue');
					$this->db->from($this->table);	
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('FirstPayingValue', $FirstPayingValue);

					$query = $this->db->get();
					$rs = $query->result_array();

					$aCalculateDate[$FirstPayingValue] = $rs;

					$FirstPayingValue = date('Y-m-d', strtotime('-1 day '. $FirstPayingValue));
				}
			break;

			case '5': // week

				for ($i=1; $i <= $limit; $i++) {
					$this->db->select('CalculateValue, FirstPayingValue, AccountTotal, RepayingAccTotal, RevenueTotal, RepayingRevTotal, NewPaying, NewPayingRevenue, ReturnPaying, ReturnPayingRevenue');
					$this->db->from($this->table);	
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('FirstPayingValue',  date('Y-W', strtotime($FirstPayingValue)) );

					$query = $this->db->get();
					$rs = $query->result_array();

					$aCalculateDate[date('Y-W', strtotime($FirstPayingValue))] = $rs;

					$FirstPayingValue = date('Y-m-d', strtotime('-7 days '. $FirstPayingValue));	

					$isSaturday = date('w', strtotime($FirstPayingValue));
					if ($isSaturday != 6) {		
						$FirstPayingValue = date('Y-m-d', strtotime('this week next saturday'. $FirstPayingValue));
					}
				}

			break;

			case '6': // month

				for ($i=1; $i <= $limit; $i++) {
					$this->db->select('CalculateValue, FirstPayingValue, AccountTotal, RepayingAccTotal, RevenueTotal, RepayingRevTotal, NewPaying, NewPayingRevenue, ReturnPaying, ReturnPayingRevenue');
					$this->db->from($this->table);	
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('FirstPayingValue',  date('Y-m', strtotime($FirstPayingValue)) );

					$query = $this->db->get();
					$rs = $query->result_array();

					$aCalculateDate[date('Y-m', strtotime($FirstPayingValue))] = $rs;

					$FirstPayingValue = date('Y-m-d', strtotime('-31 days '. $FirstPayingValue));
					$FirstPayingValue = date('Y-m-t', strtotime($FirstPayingValue));
				}

			break;
		}
		

		$result = array_reverse($aCalculateDate, true);

		return $result;
	}

	// Paying transfer
	public function getDataPayingTransfer($gameCode, $timming, $FirstPayingValue, $limit = 10)
	{
		
		$this->table = 'RC_Transfer_Product_Paying';
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
					$this->db->select('CalculateValue, FirstPayingValue, AccountTotal, PayingAccTotal, StopPayingAccTotal, OverLapAccTotal, NewPaying, NewPayingRevenue, ReturnPaying, ReturnPayingRevenue');
					$this->db->from($this->table);	
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('FirstPayingValue', $FirstPayingValue);

					$query = $this->db->get();
					$rs = $query->result_array();

					$aCalculateDate[$FirstPayingValue] = $rs;

					$FirstPayingValue = date('Y-m-d', strtotime('-1 day '. $FirstPayingValue));
				}
			break;

			case '5': // week

				for ($i=1; $i <= $limit; $i++) {
					$this->db->select('CalculateValue, FirstPayingValue, AccountTotal, PayingAccTotal, StopPayingAccTotal, OverLapAccTotal, NewPaying, NewPayingRevenue, ReturnPaying, ReturnPayingRevenue');
					$this->db->from($this->table);	
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('FirstPayingValue',  date('Y-W', strtotime($FirstPayingValue)) );

					$query = $this->db->get();
					$rs = $query->result_array();

					$aCalculateDate[date('Y-W', strtotime($FirstPayingValue))] = $rs;

					$FirstPayingValue = date('Y-m-d', strtotime('-7 days '. $FirstPayingValue));	

					$isSaturday = date('w', strtotime($FirstPayingValue));
					if ($isSaturday != 6) {		
						$FirstPayingValue = date('Y-m-d', strtotime('this week next saturday'. $FirstPayingValue));
					}
				}

			break;

			case '6': // month

				for ($i=1; $i <= $limit; $i++) {
					$this->db->select('CalculateValue, FirstPayingValue, AccountTotal, PayingAccTotal, StopPayingAccTotal, OverLapAccTotal, NewPaying, NewPayingRevenue, ReturnPaying, ReturnPayingRevenue');
					$this->db->from($this->table);	
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('FirstPayingValue',  date('Y-m', strtotime($FirstPayingValue)) );

					$query = $this->db->get();
					$rs = $query->result_array();

					$aCalculateDate[date('Y-m', strtotime($FirstPayingValue))] = $rs;

					$FirstPayingValue = date('Y-m-d', strtotime('-31 days '. $FirstPayingValue));
					$FirstPayingValue = date('Y-m-t', strtotime($FirstPayingValue));
				}

			break;
		}
		

		$result = array_reverse($aCalculateDate, true);

		return $result;
		
	}

	public function getDataPayingTransferDetail($gameCode, $timming, $CalculateValue, $limit = 10) {

		$this->table = 'RC_Transfer_Product_Paying_Detail';
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
					$this->db->select('CompareGameCode, CalculateValue, OverLapAccTotal, PayingAccTotal, PayingAccRevenue, StopPayingAccTotal, StopPayingAccRevenue');
					$this->db->from($this->table);
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('CalculateValue', $CalculateValue);

					$query = $this->db->get();
					$rs = $query->result_array();

					$aCalculateDate[$CalculateValue] = $rs;

					$CalculateValue = date('Y-m-d', strtotime('-1 day '. $CalculateValue));
				}
			break;

			case '5': // week

				for ($i=1; $i <= $limit; $i++) {
					$this->db->select('CompareGameCode, CalculateValue, OverLapAccTotal, PayingAccTotal, PayingAccRevenue, StopPayingAccTotal, StopPayingAccRevenue');
					$this->db->from($this->table);	
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('CalculateValue',  date('Y-W', strtotime($CalculateValue)));

					$query = $this->db->get();
					$rs = $query->result_array();

					$aCalculateDate[date('Y-W', strtotime($CalculateValue))] = $rs;

					$CalculateValue = date('Y-m-d', strtotime('-7 days '. $CalculateValue));	

					$isSaturday = date('w', strtotime($CalculateValue));
					if ($isSaturday != 6) {
						$CalculateValue = date('Y-m-d', strtotime('this week next saturday'. $CalculateValue));
					}
				}

			break;

			case '6': // month

				for ($i=1; $i <= $limit; $i++) {
					$this->db->select('CompareGameCode, CalculateValue, OverLapAccTotal, PayingAccTotal, PayingAccRevenue, StopPayingAccTotal, StopPayingAccRevenue');
					$this->db->from($this->table);	
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('CalculateValue',  date('Y-m', strtotime($CalculateValue)) );

					$query = $this->db->get();
					$rs = $query->result_array();

					$aCalculateDate[date('Y-m', strtotime($CalculateValue))] = $rs;

					$CalculateValue = date('Y-m-d', strtotime('-31 days '. $CalculateValue));
					$CalculateValue = date('Y-m-t', strtotime($CalculateValue));
				}

			break;
		}
		

		$result = array_reverse($aCalculateDate, true);

		return $result;

	}

	// compare product one game
	public function getDataCompareOneGame($gameCode, $gameCodeCompare, $date, $limit = 10)
	{
		// data gameCode
		$CalculateValue = $date;

		//add by tuonglv, 2016-04-15
		$limit = ($limit == 10) ?  31 : $limit;
		//end

		for ($i=1; $i <= $limit; $i++) {
			$this->db->select('*');
			$this->db->from('RS_Product');	
			$this->db->where('GameCode', $gameCode);
			$this->db->where('CalculateValue',  date('Y-m', strtotime($CalculateValue)) );
			$this->db->order_by('CalculateDate', 'desc');

			$query = $this->db->get();
			$rs = $query->row_array();

			$aCalculateDate[date('Y-m', strtotime($CalculateValue))] = $rs;

			$CalculateValue = date('Y-m-d', strtotime('-31 days '. $CalculateValue));
			$CalculateValue = date('Y-m-t', strtotime($CalculateValue));
		}

		$result[$gameCode] = array_reverse($aCalculateDate, true);

		// data gameCodeCompare
		$aCalculateDate = array();
		$gameCode = $gameCodeCompare;
		$CalculateValue = $date;

		for ($i=1; $i <= $limit; $i++) {
			$this->db->select('*');
			$this->db->from('RS_Product');
			$this->db->where('GameCode', $gameCode);
			$this->db->where('CalculateValue',  date('Y-m', strtotime($CalculateValue)) );
			$this->db->order_by('CalculateDate', 'desc');

			$query = $this->db->get();
			$rs = $query->row_array();

			$aCalculateDate[date('Y-m', strtotime($CalculateValue))] = $rs;

			$CalculateValue = date('Y-m-d', strtotime('-31 days '. $CalculateValue));
			$CalculateValue = date('Y-m-t', strtotime($CalculateValue));
		}

		$result[$gameCode] = array_reverse($aCalculateDate, true);

		return $result;
	}

	// compare all game
	public function getDataCompareAllGame($aGameCode, $calculateDate)
	{

		foreach ($aGameCode as $value) {
			$this->db->select('*');
			$this->db->from('RS_Product');
			$this->db->where('GameCode', $value['GameCode']);
			$this->db->where('CalculateValue',  date('Y-m', strtotime($calculateDate)) );
			$this->db->order_by('CalculateDate', 'desc');

			$query = $this->db->get();
			$rs = $query->row_array();

			$result[$value['GameCode']] = $rs;

		}

		return $result;
	}

	// ARPPU Doanh thu trung binh
	public function getDataARPPU($gameCode, $timming, $CalculateValue, $limit = 10)
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
				for ($i=1; $i <= 20; $i++) {
					$this->db->select('*');
					$this->db->from($this->table);
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('CalculateValue', $CalculateValue);

					$query = $this->db->get();
					$rs = $query->row_array();

					$aCalculateDate[$CalculateValue] = $rs;

					$CalculateValue = date('Y-m-d', strtotime('-1 day '. $CalculateValue));
				}
			break;

			case '5': // week

				for ($i=1; $i <= $limit; $i++) {
					$this->db->select('*');
					$this->db->from($this->table);
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('CalculateValue',  date('Y-W', strtotime($CalculateValue)));
					$this->db->order_by('CalculateDate', 'desc');

					$query = $this->db->get();
					$rs = $query->row_array();

					$aCalculateDate[date('Y-W', strtotime($CalculateValue))] = $rs;

					$CalculateValue = date('Y-m-d', strtotime('-7 days '. $CalculateValue));	

					$isSaturday = date('w', strtotime($CalculateValue));
					if ($isSaturday != 6) {
						$CalculateValue = date('Y-m-d', strtotime('this week next saturday'. $CalculateValue));
					}
				}
			break;

			case '6': // month

				for ($i=1; $i <= $limit; $i++) {
					$this->db->select('*');
					$this->db->from($this->table);	
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('CalculateValue',  date('Y-m', strtotime($CalculateValue)) );
					$this->db->order_by('CalculateDate', 'desc');

					$query = $this->db->get();
					$rs = $query->row_array();

					$aCalculateDate[date('Y-m', strtotime($CalculateValue))] = $rs;

					$CalculateValue = date('Y-m-d', strtotime('-31 days '. $CalculateValue));
					$CalculateValue = date('Y-m-t', strtotime($CalculateValue));
				}
			break;
		}

		$result = array_reverse($aCalculateDate, true);

		return $result;
	}

	public function getDataPayFrequency($gameCode, $timming, $CalculateValue, $limit = 10)
	{
		$this->table = 'RC_Cash_Frequency';
		$aTiming = array('4' => 'daily', '5' => 'weekly', '6' => 'monthly', '7' => 'yearly');

		//add by tuonglv, 2016-04-15
		$limit_arr = $this->userconstants->get_default_day_number();
		// chi thay doi gia tri limit khi no la default
		$limit = ($limit == 10) ?  $limit_arr[$timming] : $limit;
		//end


		$aCalculateDate = array();

		switch ($timming) {
			case '4': // day
				for ($i=1; $i <= 20; $i++) {
					$this->db->select('*');
					$this->db->from($this->table);
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('CalculateValue', $CalculateValue);
					$this->db->where('Type', 'transtime');

					$query = $this->db->get();
					$rs = $query->result_array();

					foreach ($rs as $value) {
						$aCalculateDate[$CalculateValue][$value['Times']] = $value;
					}
					$CalculateValue = date('Y-m-d', strtotime('-1 day '. $CalculateValue));
				}
				break;

			case '5': // week

				for ($i=1; $i <= $limit; $i++) {
					$this->db->select('*');
					$this->db->from($this->table);
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('CalculateValue',  date('Y-W', strtotime($CalculateValue)));
					$this->db->where('Type', 'transtime');
					$this->db->order_by('CalculateDate', 'desc');

					$query = $this->db->get();
					$rs = $query->result_array();

					foreach ($rs as $value) {
						$aCalculateDate[date('Y-W', strtotime($CalculateValue))][$value['Times']] = $value;
					}

					$CalculateValue = date('Y-m-d', strtotime('-7 days '. $CalculateValue));

					$isSaturday = date('w', strtotime($CalculateValue));
					if ($isSaturday != 6) {
						$CalculateValue = date('Y-m-d', strtotime('this week next saturday'. $CalculateValue));
					}
				}
				break;

			case '6': // month

				for ($i=1; $i <= $limit; $i++) {
					$this->db->select('*');
					$this->db->from($this->table);
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('CalculateValue',  date('Y-m', strtotime($CalculateValue)) );
					$this->db->where('Type', 'transtime');
					$this->db->order_by('CalculateDate', 'desc');

					$query = $this->db->get();
					$rs = $query->result_array();

					foreach ($rs as $value) {
						$aCalculateDate[date('Y-m', strtotime($CalculateValue))][$value['Times']] = $value;
					}
					$CalculateValue = date('Y-m-d', strtotime('-31 days '. $CalculateValue));
					$CalculateValue = date('Y-m-t', strtotime($CalculateValue));
				}
				break;
		}

		$result = array_reverse($aCalculateDate, true);

		return $result;
	}

	public function getDataPayMethod($gameCode, $timming, $CalculateValue, $limit = 10)
	{
		$this->table = 'RC_Cash_Method';
		$aTiming = array('4' => 'daily', '5' => 'weekly', '6' => 'monthly', '7' => 'yearly');

		//add by tuonglv, 2016-04-15
		$limit_arr = $this->userconstants->get_default_day_number();
		// chi thay doi gia tri limit khi no la default
		$limit = ($limit == 10) ?  $limit_arr[$timming] : $limit;
		//end


		$aCalculateDate = array();
		$aChannel = array();

		switch ($timming) {
			case '4': // day
				for ($i=1; $i <= 20; $i++) {
					$this->db->select('*');
					$this->db->from($this->table);
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('CalculateValue', $CalculateValue);

					$query = $this->db->get();
					$rs = $query->result_array();

					foreach ($rs as $value) {
						$aChannel[$value['Channel']] = $value['Channel'];
						$aCalculateDate[$CalculateValue][$value['Channel']] = $value;
					}
					$CalculateValue = date('Y-m-d', strtotime('-1 day '. $CalculateValue));
				}
				break;

			case '5': // week

				for ($i=1; $i <= $limit; $i++) {
					$this->db->select('*');
					$this->db->from($this->table);
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('CalculateValue',  date('Y-W', strtotime($CalculateValue)));
					$this->db->order_by('CalculateDate', 'desc');

					$query = $this->db->get();
					$rs = $query->result_array();

					foreach ($rs as $value) {
						$aChannel[$value['Channel']] = $value['Channel'];
						$aCalculateDate[date('Y-W', strtotime($CalculateValue))][$value['Channel']] = $value;
					}

					$CalculateValue = date('Y-m-d', strtotime('-7 days '. $CalculateValue));

					$isSaturday = date('w', strtotime($CalculateValue));
					if ($isSaturday != 6) {
						$CalculateValue = date('Y-m-d', strtotime('this week next saturday'. $CalculateValue));
					}
				}
				break;

			case '6': // month

				for ($i=1; $i <= $limit; $i++) {
					$this->db->select('*');
					$this->db->from($this->table);
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('CalculateValue',  date('Y-m', strtotime($CalculateValue)) );
					$this->db->order_by('CalculateDate', 'desc');

					$query = $this->db->get();
					$rs = $query->result_array();

					foreach ($rs as $value) {
						$aChannel[$value['Channel']] = $value['Channel'];
						$aCalculateDate[date('Y-m', strtotime($CalculateValue))][$value['Channel']] = $value;
					}
					$CalculateValue = date('Y-m-d', strtotime('-31 days '. $CalculateValue));
					$CalculateValue = date('Y-m-t', strtotime($CalculateValue));
				}
				break;
		}

		$result['data'] = array_reverse($aCalculateDate, true);
		$result['channel'] = $aChannel;
		return $result;
	}

	public function getDataPayChannel($gameCode, $timming, $CalculateValue, $limit = 10)
	{
		$this->table = 'RC_Channel';
		$aTiming = array('4' => 'daily', '5' => 'weekly', '6' => 'monthly', '7' => 'yearly');

		//add by tuonglv, 2016-04-15
		$limit_arr = $this->userconstants->get_default_day_number();
		// chi thay doi gia tri limit khi no la default
		$limit = ($limit == 10) ?  $limit_arr[$timming] : $limit;
		//end


		$aCalculateDate = array();
		$aChannel = array();

		switch ($timming) {
			case '4': // day
				for ($i=1; $i <= 10; $i++) {
					$this->db->select('*');
					$this->db->from($this->table);
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('CalculateValue', $CalculateValue);

					$query = $this->db->get();
					$rs = $query->result_array();

					foreach ($rs as $value) {
						$aChannel[$value['Channel']] = $value['Channel'];
						$aCalculateDate[$CalculateValue][$value['Channel']] = $value;
					}
					$CalculateValue = date('Y-m-d', strtotime('-1 day '. $CalculateValue));
				}
				break;

			case '5': // week

				for ($i=1; $i <= $limit; $i++) {
					$this->db->select('*');
					$this->db->from($this->table);
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('CalculateValue',  date('Y-W', strtotime($CalculateValue)));
					$this->db->order_by('CalculateDate', 'desc');

					$query = $this->db->get();
					$rs = $query->result_array();

					foreach ($rs as $value) {
						$aChannel[$value['Channel']] = $value['Channel'];
						$aCalculateDate[date('Y-W', strtotime($CalculateValue))][$value['Channel']] = $value;
					}

					$CalculateValue = date('Y-m-d', strtotime('-7 days '. $CalculateValue));

					$isSaturday = date('w', strtotime($CalculateValue));
					if ($isSaturday != 6) {
						$CalculateValue = date('Y-m-d', strtotime('this week next saturday'. $CalculateValue));
					}
				}
				break;

			case '6': // month

				for ($i=1; $i <= $limit; $i++) {
					$this->db->select('*');
					$this->db->from($this->table);
					$this->db->where('GameCode', $gameCode);
					$this->db->where('CalculateBy', $aTiming[$timming]);
					$this->db->where('CalculateValue',  date('Y-m', strtotime($CalculateValue)) );
					$this->db->order_by('CalculateDate', 'desc');

					$query = $this->db->get();
					$rs = $query->result_array();

					foreach ($rs as $value) {
						$aChannel[$value['Channel']] = $value['Channel'];
						$aCalculateDate[date('Y-m', strtotime($CalculateValue))][$value['Channel']] = $value;
					}
					$CalculateValue = date('Y-m-d', strtotime('-31 days '. $CalculateValue));
					$CalculateValue = date('Y-m-t', strtotime($CalculateValue));
				}
				break;
		}

		$result['data'] = array_reverse($aCalculateDate, true);
		$result['channel'] = $aChannel;
		return $result;
	}
}

/* End of file retention_model.php */
/* Location: ./application/models/retention_model.php */