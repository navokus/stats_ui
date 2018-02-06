<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Util {

	private $CI;

	public function __construct()
	{
		$this->CI =& get_instance();

        $config['mailtype'] = 'html';
        $config['protocol'] = 'smtp';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = TRUE;
        $config['smtp_host'] = '10.30.76.11';
        $config['smtp_port'] = '25';
        $this->CI->email->initialize($config);

	}

	public function listOptionsWeek($preYear = 1)
	{
		$return = array();	

		$year = date('Y');
		$week = date('W');

		for ($i = $week; $i >= 1; $i--) {

			$date = date('Y-m-d', strtotime($year . 'W' . str_pad($i, 2, '0', STR_PAD_LEFT)));
			//$date = date('Y-m-d', strtotime('-1 day '. $date)); // back to Sunday
			$formatDate = date('d/m/Y', strtotime($date));
			$return[$date] = 'Week '. str_pad($i, 2, '0', STR_PAD_LEFT) .', '. $year .' ('.$formatDate.')';

		}
		// lay 1 ngay truoc do ke tu ngay dau tien cua nam
		$date = date('Y-m-d', strtotime('-1 day '. $date));
		$week  = date('W',strtotime($date));
		$year--;

		for ($i = $week; $i >= 1; $i--) {

			$date = date('Y-m-d', strtotime($year . 'W' . str_pad($i, 2, '0', STR_PAD_LEFT))); // date is monday
			//$date = date('Y-m-d', strtotime('-1 day '. $date)); // back to Sunday

			$formatDate = date('d/m/Y', strtotime($date));
			$return[$date] = 'Week '. str_pad($i, 2, '0', STR_PAD_LEFT) .', '. $year .' ('.$formatDate.')';

		}

		return $return;

	}
	public function listOptionsWeek1($preYear = 1)
	{
		$return = array();

		$year = date('Y');
		$week = date('W');

		for ($i = $week; $i >= 1; $i--) {

			$date = date('Y-m-d', strtotime($year . 'W' . str_pad($i, 2, '0', STR_PAD_LEFT)));
			$date = date('Y-m-d', strtotime('-1 day '. $date));

			$formatDate = date('d/m/Y', strtotime($date));
			$return[$date] = 'Tuần '. str_pad($i, 2, '0', STR_PAD_LEFT) .', '. $year .' ('.$formatDate.')';
		}

		for ($y=0; $y < $preYear; $y++) {
			$year--;
			$week = 52;
			for ($i = $week; $i >= 1; $i--) {

				$date = date('Y-m-d', strtotime($year . 'W' . str_pad($i, 2, '0', STR_PAD_LEFT))); // date is monday
				$date = date('Y-m-d', strtotime('-1 day '. $date)); // back to Sunday

				$formatDate = date('d/m/Y', strtotime($date));
				$return[$date] = 'Tuần '. str_pad($i, 2, '0', STR_PAD_LEFT) .', '. $year .' ('.$formatDate.')';
			}
		}

		return $return;

	}


	public function listOptionsMonth($preYear = 1)
	{
		$return = array();

		$year = date('Y');
		$month = date('m');

		for ($i = $month; $i >= 1 ; $i--) {
			$formatDate = $this->formatDate("Y-m-d", "M-Y", $year . "-" . str_pad($i, 2, '0', STR_PAD_LEFT) . "-01");
			$date = date("Y-m-t", strtotime($year . '-' . str_pad($i, 2, '0', STR_PAD_LEFT) . '-' . '01'));
			$return[$date] = $formatDate;
		}

		for ($y=0; $y < $preYear; $y++) {
			$year--;
			$month = 12;

			for ($i = $month; $i >= 1 ; $i--) {
				$formatDate = $this->formatDate("Y-m-d", "M-Y", $year . "-" . str_pad($i, 2, '0', STR_PAD_LEFT) . "-01");
				$date = date("Y-m-t", strtotime($year . '-' . str_pad($i, 2, '0', STR_PAD_LEFT) . '-' . '01'));
				$return[$date] = $formatDate;
			}
		}

		return $return;
	}

	public function convertNoneUtf8 ($str) {
		$unicode = array(
			'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
			'd'=>'đ',
			'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
			'i'=>'í|ì|ỉ|ĩ|ị',
			'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
			'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
			'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
			'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
			'D'=>'Đ',
			'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
			'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
			'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
			'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
			'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
			'' => ' '
		);

		foreach($unicode as $nonUnicode=>$uni){
			$str = preg_replace("/($uni)/i", $nonUnicode, $str);
		}

		$str = str_replace(
			array('"', "'", '/', '\\', '(', ')', '=', ':v', ':3', '<3', '>', '<', ':', '.', '@', '^', ',', '?','%'),
			array('','','','','','','','','','','','','','','','','', '', ''),
			$str
		);
		return strtoupper($str);
	}


	// add by tuonglv
	private function getDaysEveryMonth($fromDate,$toDate,$addTime)
	{
		$return = array();
		$start = date('Y-m', strtotime($fromDate));
		$end = date('Y-m', strtotime($toDate));
		$i = 1;

		while ($start <= $end || $i < 2) {
			$t = date("Y-m-t", strtotime($start."-01"));
			if($addTime)
				$return[] = $t . " 00:00:00";
			else
				$return[] = $t;

			$start = date('Y-m', strtotime('+1 day ' . $t));
			$i++;
		}
		return $return;
	}


    private function getDaysEveryWeek($fromDate,$toDate,$addTime){
        $return = array();
		//ở form selection, ngày bắt đầu 1 tuần được tính là ngày chủ nhật thay vì thứ 2. Nên ngày cuối tuần sẽ là thứ 7 thay vì chủ nhật
		// nếu sau khi sửa, ngày bắt đầu 1 tuần được tính là thứ 2, thì ngày cuối tuần là chủ nhật, và function bên dưới vẫn chạy ok!

        $fromDate =  $this->get_nearest_date_by_timming($fromDate,17);
		$fromDate = date('Y-m-d', strtotime('+7 day '. $fromDate));
		
        $unixFrom = strtotime($fromDate);
        $unixTo = strtotime(date('Y-m-d', strtotime('+7 day ' . $toDate)));
        $i=1;
        while ($unixFrom <= $unixTo || $i < 2) {
            
            if($addTime)
                $return[] = $fromDate . " 00:00:00";
            else
                $return[] = $fromDate;
            $fromDate = date('Y-m-d', strtotime('+7 day '. $fromDate));
            $unixFrom = strtotime($fromDate);
            $i++;
        }
        return $return;
    }

    public function getDaysEveryDay($fromDate,$toDate,$addTime){
        $return = array();
        $unixFrom=strtotime($fromDate);
        $unixTo = strtotime($toDate);
        $i=1;
        while ($unixFrom <= $unixTo || $i < 2){
            if($addTime)
                $return[] = $fromDate . " 00:00:00";
            else
                $return[] = $fromDate;
            $fromDate = date('Y-m-d', strtotime('+1 day '. $fromDate));
            $unixFrom = strtotime($fromDate);
            $i++;
        }
        return $return;
    }

    /**
     * Modify date: 2016-08-02
     * By: vinhdp
     * Update: 1, 2
     * Add: 3, 4
     */
    public function getDaysFromTiming($fromDate,$toDate,$timming,$addTime){
        switch($timming){
            default:
            case 4:
                $return = $this->getDaysEveryDay($fromDate,$toDate,$addTime);
                break;
            case 5:		// 1: change to every day
                $return = $this->getDaysEveryDay($fromDate,$toDate,$addTime);
                break;
            case 6:		// 2: change to every day
                $return = $this->getDaysEveryDay($fromDate,$toDate,$addTime);
                break;
            case 17:	// 3: weekly
                $return = $this->getDaysEveryWeek($fromDate,$toDate,$addTime);
                break;
            case 31:	// 4: monthly
                $return = $this->getDaysEveryMonth($fromDate,$toDate,$addTime);
                break;
        }
        return $return;
    }
    
    /**
     * Get first and last day from range days
     * @param unknown $arr
     * @return unknown[]
     */
    public function getDaysFromRanges($arr){
    	$days = array();
    	$days[] = $arr[0];
    	$days[] = $arr[count($arr) - 1];
    	
    	return $days;
    }

	public function get_data_string($array,$quote="",$upfirst=false){
		$return = "";
		foreach($array as $value){
            if($upfirst)
			    $return .= $quote . ucfirst($value) . $quote . ", ";
            else
                $return .= $quote . $value . $quote . ", ";
		}
		return substr($return,0,-2);
	}

    public function get_categories($arr_key){
        $return = "";
        foreach($arr_key as $key){
            $return .= "'"  . strtoupper($key) .  "',";
        }
        return $return;
    }

	public function is_admin($account_name){
		$admin_array = array(
			"quangctn",
			"canhtq",
			"lamnt6"
		);

		if(in_array($account_name,$admin_array)){
			return true;
		}
		return false;
	}

	/**
	 * Modify date: 2016-08-02
	 * By: vinhdp
	 * Add: $timming != "5" && $timming != "6"
	 */
    public function sort_data_table($arr, $timming, $isAsc = false){
        $new = array();
        for($i=0;$i<count($arr);$i++){
            $sort_key = $arr[$i]['log_date'];
            if($timming != "4" && $timming != "5" && $timming != "6"){
                $t1 = explode("-", substr($arr[$i]['log_date'],0,7));
                $sort_key = $t1[1] . "-" . $t1[0];
            }
            $new[$sort_key] = $arr[$i];
        }
        if($isAsc == true){
        	ksort($new);
        }
        $return = array();
        foreach($new as $sort_key => $value){
            $return[] = $value;
        }
        return $return;
    }

    /**
     * Modify date: 2016-08-02
     * By: vinhdp
     * Add: 17, 31 kpi id
     * 
     * key user_kpi for all kpi about user
     * key revenue_kpi for all kpi about revenue
     * 
     * 4 as a1
     * 5 as a7
     * 6 as a30
     * 
     * 3 as a3
     * 14 as a14
     * 17 as ac7
     * 31 as ac30
     */
    public function db_field_config($header = false, $kpiName = "Users")
    {
        // ccu
        $percent_or_not = "(%)";

        $config['user_kpi']['4']['acu1'] = 'ACU';
        $config['user_kpi']['4']['pcu1'] = 'PCU';
        
        $config['user_kpi']['4']['aacu1'] = 'Avegare ACU';
        $config['user_kpi']['4']['pacu1'] = 'Peak ACU';
        $config['user_kpi']['4']['apcu1'] = 'Avegare PCU';
        $config['user_kpi']['4']['ppcu1'] = 'Peak PCU';
        
        $config['user_kpi']['31']['aacu1'] = 'Monthly Avegare ACU';
        $config['user_kpi']['31']['pacu1'] = 'Monthly Peak ACU';
        $config['user_kpi']['31']['apcu1'] = 'Monthly Avegare PCU';
        $config['user_kpi']['31']['ppcu1'] = 'Monthly Peak PCU';

        // active user
        $config['user_kpi']['4']['aa1'] = 'Avegare A1';
        $config['user_kpi']['4']['pa1'] = 'Peak A1';
        $config['user_kpi']['4']['a1'] = 'A1';
        $config['user_kpi']['5']['a7'] = 'A7';
        $config['user_kpi']['6']['a30'] = 'A30';
        $config['user_kpi']['7']['am'] = 'Monthly Active ' . $kpiName;
        $config['user_kpi']['8']['a60'] = 'A60';
        $config['user_kpi']['9']['a90'] = 'A90';
        $config['user_kpi']['17']['aw'] = 'Weekly Active ' . $kpiName;
        $config['user_kpi']['31']['am'] = 'Monthly Active ' . $kpiName;
        $config['user_kpi']['3']['a3'] = 'A3';
        $config['user_kpi']['14']['a14'] = 'A14';

        // new register user
        $config['user_kpi']['4']['n1'] = 'N1';
        $config['user_kpi']['5']['n7'] = 'N7';
        $config['user_kpi']['6']['n30'] = 'N30';
        $config['user_kpi']['7']['nm'] = 'Monthly New ' . $kpiName;
        $config['user_kpi']['8']['n60'] = 'N60';
        $config['user_kpi']['9']['n90'] = 'N90';
        $config['user_kpi']['17']['nw'] = 'Weekly New ' . $kpiName;
        $config['user_kpi']['31']['nm'] = 'Monthly New ' . $kpiName;
        $config['user_kpi']['3']['n3'] = 'N3';
        $config['user_kpi']['14']['n14'] = 'N14';

        // retention login count
        $config['user_kpi']['4']['rlc1'] = 'rlc1';
        $config['user_kpi']['5']['rlc7'] = 'rlc7';
        $config['user_kpi']['6']['rlc30'] = 'rlc30';
        $config['user_kpi']['7']['rlcm'] = 'rlcm';
        $config['user_kpi']['8']['rlc60'] = 'rlc60';
        $config['user_kpi']['9']['rlc90'] = 'rlc90';

        // retention login rate
        $config['user_kpi']['4']['rr1'] = 'LR1' . $percent_or_not;
        $config['user_kpi']['3']['rr3'] = 'LR3' . $percent_or_not;
        $config['user_kpi']['14']['rr14'] = 'LR14' . $percent_or_not;
        $config['user_kpi']['5']['rr7'] = 'LR7' . $percent_or_not;
        $config['user_kpi']['6']['rr30'] = 'LR30' . $percent_or_not;
        $config['user_kpi']['7']['rrm'] = 'Monthly LR' . $percent_or_not;
        $config['user_kpi']['8']['rr60'] = 'Login Retention Rate 60days' . $percent_or_not;
        $config['user_kpi']['9']['rr90'] = 'Login Retention Rate 90days' . $percent_or_not;
        $config['user_kpi']['17']['rrw'] = 'Weekly Login Retention Rate' . $percent_or_not;
        $config['user_kpi']['31']['rrm'] = 'Monthly  Login Retention Rate' . $percent_or_not;

        // new user retention count
        $config['user_kpi']['4']['nrc1'] = 'nrc1';
        $config['user_kpi']['5']['nrc7'] = 'nrc7';
        $config['user_kpi']['6']['nrc30'] = 'nrc30';
        $config['user_kpi']['7']['nrcm'] = 'nrcm';
        $config['user_kpi']['8']['nrc60'] = 'nrc60';
        $config['user_kpi']['9']['nrc90'] = 'nrc90';

        // new user retention rate
        $config['user_kpi']['4']['nrr1'] = 'RR1' . $percent_or_not;
        $config['user_kpi']['5']['nrr7'] = 'RR7' . $percent_or_not;
        $config['user_kpi']['6']['nrr30'] = 'RR30' . $percent_or_not;
        $config['user_kpi']['7']['nrrm'] = 'Monthly RR' . $percent_or_not;
        $config['user_kpi']['8']['nrr60'] = 'RR60' . $percent_or_not;
        $config['user_kpi']['9']['nrr90'] = 'RR90' . $percent_or_not;
        $config['user_kpi']['17']['nrrw'] = 'Weekly RR' . $percent_or_not;
        $config['user_kpi']['31']['nrrm'] = 'Monthly RR' . $percent_or_not;
        $config['user_kpi']['3']['nrr3'] = 'RR3' . $percent_or_not;
        $config['user_kpi']['14']['nrr14'] = 'RR14' . $percent_or_not;

        // churn login count
        $config['user_kpi']['4']['clc1'] = 'clc1';
        $config['user_kpi']['5']['clc7'] = 'clc7';
        $config['user_kpi']['6']['clc30'] = 'clc30';
        $config['user_kpi']['7']['clcm'] = 'clcm';
        $config['user_kpi']['8']['clc60'] = 'clc60';
        $config['user_kpi']['9']['clc90'] = 'clc90';

        // playingtime
        $config['user_kpi']['4']['ptime1'] = 'ptime1';
        $config['user_kpi']['3']['ptime3'] = 'ptime3';
        $config['user_kpi']['5']['ptime7'] = 'ptime7';
        $config['user_kpi']['14']['ptime14'] = 'ptime14';
        $config['user_kpi']['6']['ptime30'] = 'ptime30';
        $config['user_kpi']['8']['ptime60'] = 'ptime60';
        $config['user_kpi']['9']['ptime90'] = 'ptime90';
        $config['user_kpi']['17']['ptimew'] = 'ptimew';
        $config['user_kpi']['31']['ptimem'] = 'ptimem';

        // paying user
        $config['revenue_kpi']['4']['pu1'] = 'PU1';
        $config['revenue_kpi']['4']['apu1'] = 'Avegare PU1';
        $config['revenue_kpi']['4']['ppu1'] = 'Peak PU1';

        $config['revenue_kpi']['5']['pu7'] = 'PU7';
        $config['revenue_kpi']['6']['pu30'] = 'PU30';
        $config['revenue_kpi']['7']['pum'] = 'Monthly Paying ' . $kpiName;
        $config['revenue_kpi']['8']['pu60'] = 'PU60';
        $config['revenue_kpi']['9']['pu90'] = 'PU90';
        $config['revenue_kpi']['17']['puw'] = 'Weekly Paying ' . $kpiName;
        $config['revenue_kpi']['31']['pum'] = 'Monthly Paying ' . $kpiName;
        $config['revenue_kpi']['3']['pu3'] = 'PU3';
        $config['revenue_kpi']['14']['pu14'] = 'PU14';

        // paying user revenue
        // luu y kpi_code cua gross va net dat ten bi lon. se gay hiu lam
        $config['revenue_kpi']['4']['gr1'] = 'Revenue in 1days';
        $config['revenue_kpi']['5']['gr7'] = 'Revenue in 7days';
        $config['revenue_kpi']['6']['gr30'] = 'Revenue in 30days';
        $config['revenue_kpi']['7']['grm'] = 'Monthly Revenue';
        $config['revenue_kpi']['8']['gr60'] = 'Revenue in 60days';
        $config['revenue_kpi']['9']['gr90'] = 'Revenue in 90days';
        $config['revenue_kpi']['17']['grw'] = 'Weekly Revenue';
        $config['revenue_kpi']['31']['grm'] = 'Monthly Revenue';
        $config['revenue_kpi']['3']['gr3'] = 'Revenue in 3days';
        $config['revenue_kpi']['14']['gr14'] = 'Revenue in 14days';

        $config['revenue_kpi']['4']['nr1'] = 'Gross revenue in 1days';
        $config['revenue_kpi']['5']['nr7'] = 'Gross revenue in 7days';
        $config['revenue_kpi']['6']['nr30'] = 'Gross revenue in 30days';
        $config['revenue_kpi']['7']['nrm'] = 'Monthly Gross revenue';
        $config['revenue_kpi']['8']['nr60'] = 'Gross revenue in 60days';
        $config['revenue_kpi']['9']['nr90'] = 'Gross revenue in 90days';
        $config['revenue_kpi']['17']['nrw'] = 'Weekly Gross revenue';
        $config['revenue_kpi']['31']['nrm'] = 'Monthly Gross revenue';
        $config['revenue_kpi']['3']['nr3'] = 'Gross revenue in 3days';
        $config['revenue_kpi']['14']['nr14'] = 'Gross revenue in 14days';


        // retention paying count
        $config['revenue_kpi']['4']['rpc1'] = 'rpc1';
        $config['revenue_kpi']['5']['rpc7'] = 'rpc7';
        $config['revenue_kpi']['6']['rpc30'] = 'rpc30';
        $config['revenue_kpi']['7']['rpcm'] = 'rpcm';
        $config['revenue_kpi']['8']['rpc60'] = 'rpc60';
        $config['revenue_kpi']['9']['rpc90'] = 'rpc90';

        // churn paying count
        $config['revenue_kpi']['4']['cpc1'] = 'cpc1';
        $config['revenue_kpi']['5']['cpc7'] = 'cpc7';
        $config['revenue_kpi']['6']['cpc30'] = 'cpc30';
        $config['revenue_kpi']['7']['cpcm'] = 'cpcm';
        $config['revenue_kpi']['8']['cpc60'] = 'cpc60';
        $config['revenue_kpi']['9']['cpc90'] = 'cpc90';

        // new paying user
        $config['revenue_kpi']['4']['npu1'] = 'New PU1';
        $config['revenue_kpi']['5']['npu7'] = 'New PU7';
        $config['revenue_kpi']['6']['npu30'] = 'New PU30';
        $config['revenue_kpi']['7']['npum'] = 'Monthly New PU';
        $config['revenue_kpi']['8']['npu60'] = 'New PU60';
        $config['revenue_kpi']['9']['npu90'] = 'New PU90';
        $config['revenue_kpi']['17']['npuw'] = 'Weekly New Paying ' . $kpiName;
        $config['revenue_kpi']['31']['npum'] = 'Monthly New Paying ' . $kpiName;
        $config['revenue_kpi']['3']['npu3'] = 'New PU3';
        $config['revenue_kpi']['14']['npu14'] = 'New PU14';

        // new paying user revenue
        $config['revenue_kpi']['4']['npu_gr1'] = 'Revenue of New PU1';
        $config['revenue_kpi']['5']['npu_gr7'] = 'Revenue of New PU7';
        $config['revenue_kpi']['6']['npu_gr30'] = 'Revenue of New PU30';
        $config['revenue_kpi']['7']['npu_grm'] = 'Revenue of Monthly PU';
        $config['revenue_kpi']['8']['npu_gr60'] = 'Revenue of New PU60';
        $config['revenue_kpi']['9']['npu_gr90'] = 'Revenue of New PU90';
        $config['revenue_kpi']['17']['npu_grw'] = 'Weekly Revenue of New Paying ' . $kpiName;
        $config['revenue_kpi']['31']['npu_grm'] = 'Monthly Revenue of New Paying ' . $kpiName;
        $config['revenue_kpi']['3']['npu_gr3'] = 'Revenue of New PU3';
        $config['revenue_kpi']['14']['npu_gr14'] = 'Revenue of New PU14';

        $config['revenue_kpi']['4']['npu_nr1'] = 'Gross revenue of New PU1';
        $config['revenue_kpi']['5']['npu_nr7'] = 'Gross revenue of New PU7';
        $config['revenue_kpi']['6']['npu_nr30'] = 'Gross revenue of New PU30';
        $config['revenue_kpi']['7']['npu_nrm'] = 'Gross revenue of Monthly PU';
        $config['revenue_kpi']['8']['npu_nr60'] = 'Gross revenue of New PU60';
        $config['revenue_kpi']['9']['npu_nr90'] = 'Gross revenue of New PU90';
        $config['revenue_kpi']['17']['npu_nrw'] = 'Weekly Gross revenue of New Paying ' . $kpiName;
        $config['revenue_kpi']['31']['npu_nrm'] = 'Monthly Gross revenue of New Paying ' . $kpiName;
        $config['revenue_kpi']['3']['npu_nr3'] = 'Gross revenue of New PU3';
        $config['revenue_kpi']['14']['npu_nr14'] = 'Gross revenue of New PU14';

        // new regiser & paying user
        $config['revenue_kpi']['4']['nnpu1'] = 'NNPU1';
        $config['revenue_kpi']['5']['nnpu7'] = 'NNPU7';
        $config['revenue_kpi']['6']['nnpu30'] = 'NNPU30';
        $config['revenue_kpi']['7']['nnpum'] = 'Monthly NNPU';
        $config['revenue_kpi']['8']['nnpu60'] = 'NNPU60';
        $config['revenue_kpi']['9']['nnpu90'] = 'NNPU90';
        $config['revenue_kpi']['17']['nnpuw'] = 'Weekly NNPU';
        $config['revenue_kpi']['31']['nnpum'] = 'Monthly NNPU';
        $config['revenue_kpi']['3']['nnpu3'] = 'NNPU3';
        $config['revenue_kpi']['14']['nnpu14'] = 'NNPU14';

        // new register & paying user revenue
        $config['revenue_kpi']['4']['nnpu_gr1'] = 'Revenue of NNPU1';
        $config['revenue_kpi']['5']['nnpu_gr7'] = 'Revenue of NNPU7';
        $config['revenue_kpi']['6']['nnpu_gr30'] = 'Revenue of NNPU30';
        $config['revenue_kpi']['7']['nnpu_grm'] = 'Monthly Revenue of NNPU';
        $config['revenue_kpi']['8']['nnpu_gr60'] = 'Revenue of NNPU60';
        $config['revenue_kpi']['9']['nnpu_gr90'] = 'Revenue of NNPU90';
        $config['revenue_kpi']['17']['nnpu_grw'] = 'Weekly Revenue of NNPU';
        $config['revenue_kpi']['31']['nnpu_grm'] = 'Monthly Revenue of NNPU';
        $config['revenue_kpi']['3']['nnpu_gr3'] = 'Revenue of NNPU3';
        $config['revenue_kpi']['14']['nnpu_gr14'] = 'Revenue of NNPU14';

        // new register & paying user revenue
        $config['revenue_kpi']['4']['nnpu_nr1'] = 'Gross revenue of NNPU1';
        $config['revenue_kpi']['5']['nnpu_nr7'] = 'Gross revenue of NNPU7';
        $config['revenue_kpi']['6']['nnpu_nr30'] = 'Gross revenue of NNPU30';
        $config['revenue_kpi']['7']['nnpu_nrm'] = 'Monthly Gross revenue of NNPU';
        $config['revenue_kpi']['8']['nnpu_nr60'] = 'Gross revenue of NNPU60';
        $config['revenue_kpi']['9']['nnpu_nr90'] = 'Gross revenue of NNPU90';
        $config['revenue_kpi']['17']['nnpu_nrw'] = 'Weekly Gross revenue of NNPU';
        $config['revenue_kpi']['31']['nnpu_nrm'] = 'Monthly Gross revenue of NNPU';
        $config['revenue_kpi']['3']['nnpu_nr3'] = 'Gross revenue of NNPU3';
        $config['revenue_kpi']['14']['nnpu_nr14'] = 'Gross revenue of NNPU14';

        // paying retention rate
        $config['user_kpi']['4']['prr1'] = 'PU Retention 1day' . $percent_or_not;
        $config['user_kpi']['5']['prr7'] = 'PU Retention 7days' . $percent_or_not;
        $config['user_kpi']['6']['prr30'] = 'PU Retention 30days' . $percent_or_not;
        $config['user_kpi']['7']['prrm'] = 'PRRM' . $percent_or_not;
        $config['user_kpi']['8']['prr60'] = 'PU Retention 60days' . $percent_or_not;
        $config['user_kpi']['9']['prr90'] = 'PU Retention 90days' . $percent_or_not;
        //$config['user_kpi']['17']['prrw'] = 'PRRW' . $percent_or_not;
        //$config['user_kpi']['31']['prrm'] = 'PRRM' . $percent_or_not;
        $config['user_kpi']['3']['prr3'] = 'PU Retention 3day' . $percent_or_not;
        $config['user_kpi']['14']['prr14'] = 'PU Retention 14days' . $percent_or_not;

        $config['user_kpi']['4']['retention1'] = 'Daily Retention';
        $config['user_kpi']['17']['retentionw'] = 'Weekly Retention';
        $config['user_kpi']['31']['retentionm'] = 'Monthly Retention';


        // churn rate
        $config['user_kpi']['4']['cr1'] = 'Churn Rate 1day' . $percent_or_not;
        $config['user_kpi']['5']['cr7'] = 'Churn Rate 7days' . $percent_or_not;
        $config['user_kpi']['6']['cr30'] = 'Churn Rate 30days' . $percent_or_not;
        $config['user_kpi']['3']['cr3'] = 'Churn Rate 3days' . $percent_or_not;
        $config['user_kpi']['14']['cr14'] = 'Churn Rate 14days' . $percent_or_not;
        $config['user_kpi']['31']['crm'] = 'Churn Rate Monthly' . $percent_or_not;
        $config['user_kpi']['8']['cr60'] = 'Churn Rate 60days' . $percent_or_not;
        $config['user_kpi']['9']['cr90'] = 'Churn Rate 90days' . $percent_or_not;
        $config['user_kpi']['17']['crw'] = 'Churn Rate Weekly' . $percent_or_not;

        // churn paying rate
        $config['user_kpi']['4']['cpr1'] = 'cpr1';
        $config['user_kpi']['5']['cpr7'] = 'cpr7';
        $config['user_kpi']['6']['cpr30'] = 'cpr30';
        $config['user_kpi']['7']['cprm'] = 'cprm';
        $config['user_kpi']['8']['cpr60'] = 'cpr60';
        $config['user_kpi']['9']['cpr90'] = 'cpr90';
        $config['user_kpi']['17']['cprw'] = 'cprw';
        $config['user_kpi']['31']['cprm'] = 'cprm';

        // average playingtime (playingtime / a)
        $config['user_kpi']['4']['avgtime1'] = 'Average Playingtime 1 day';
        $config['user_kpi']['3']['avgtime3'] = 'Average Playingtime 3 days';
        $config['user_kpi']['5']['avgtime7'] = 'Average Playingtime 7 days';
        $config['user_kpi']['14']['avgtime14'] = 'Average Playingtime 14 days';
        $config['user_kpi']['6']['avgtime30'] = 'Average Playingtime 30 days';
        $config['user_kpi']['8']['avgtime60'] = 'Average Playingtime 60 days';
        $config['user_kpi']['9']['avgtime90'] = 'Average Playingtime 90 days';
        $config['user_kpi']['17']['avgtimew'] = 'Average Playingtime Weekly';
        $config['user_kpi']['31']['avgtimem'] = 'Average Playingtime Monthly';

        // ARPU
        $config['revenue_kpi']['4']['arpu1'] = 'ARPU1';
        $config['revenue_kpi']['5']['arpu7'] = 'ARPU7';
        $config['revenue_kpi']['6']['arpu30'] = 'ARPU30';
        $config['revenue_kpi']['7']['arpum'] = 'ARPUM';
        $config['revenue_kpi']['8']['arpu60'] = 'ARPU60';
        $config['revenue_kpi']['9']['arpu90'] = 'ARPU90';
        $config['revenue_kpi']['17']['arpuw'] = 'Weekly ARPU';
        $config['revenue_kpi']['31']['arpum'] = 'Monthly ARPU';
        $config['revenue_kpi']['3']['arpu3'] = 'ARPU3';
        $config['revenue_kpi']['14']['arpu14'] = 'ARPU14';

        // ARPPU
        $config['revenue_kpi']['4']['arppu1'] = 'ARPPU1';
        $config['revenue_kpi']['5']['arppu7'] = 'ARPPU7';
        $config['revenue_kpi']['6']['arppu30'] = 'ARPPU30';
        $config['revenue_kpi']['8']['arppu60'] = 'ARPPU60';
        $config['revenue_kpi']['9']['arppu90'] = 'ARPPU90';
        $config['revenue_kpi']['17']['arppuw'] = 'Weekly ARPPU';
        $config['revenue_kpi']['31']['arppum'] = 'Monthly ARPPU';
        $config['revenue_kpi']['3']['arppu3'] = 'ARPPU3';
        $config['revenue_kpi']['14']['arppu14'] = 'ARPPU14';


        // conversion rate (pu / a)
        $config['revenue_kpi']['4']['cvr1'] = 'Conversion Rate 1day' . $percent_or_not;
        $config['revenue_kpi']['5']['cvr7'] = 'Conversion Rate 7days' . $percent_or_not;
        $config['revenue_kpi']['6']['cvr30'] = 'Conversion Rate 30days' . $percent_or_not;
        $config['revenue_kpi']['8']['cvr60'] = 'Conversion Rate 60days' . $percent_or_not;
        $config['revenue_kpi']['9']['cvr90'] = 'Conversion Rate 90days' . $percent_or_not;
        $config['revenue_kpi']['17']['cvrw'] = 'Conversion Rate Weekly' . $percent_or_not;
        $config['revenue_kpi']['31']['cvrm'] = 'Conversion Rate Monthly' . $percent_or_not;
        $config['revenue_kpi']['3']['cvr3'] = 'Conversion Rate 3day' . $percent_or_not;
        $config['revenue_kpi']['14']['cvr14'] = 'Conversion Rate 14days' . $percent_or_not;


        if ($header == true) {
            // avice user tren
            $config['user_kpi']['4']['a1_trend'] = 'a1_trend';
            $config['user_kpi']['5']['a7_trend'] = 'a7_trend';
            $config['user_kpi']['6']['a30_trend'] = 'a30_trend';
            $config['user_kpi']['7']['am_trend'] = 'am_trend';
            $config['user_kpi']['8']['a60_trend'] = 'a60_trend';
            $config['user_kpi']['9']['a90_trend'] = 'a90_trend';
            $config['user_kpi']['17']['aw_trend'] = 'aw_trend';
            $config['user_kpi']['31']['am_trend'] = 'am_trend';
            $config['user_kpi']['3']['a3_trend'] = 'a3_trend';
            $config['user_kpi']['14']['a14_trend'] = 'a14_trend';

            // new register trend
            $config['user_kpi']['4']['n1_trend'] = 'n1_trend';
            $config['user_kpi']['5']['n7_trend'] = 'n7_trend';
            $config['user_kpi']['6']['n30_trend'] = 'n30_trend';
            $config['user_kpi']['7']['nm_trend'] = 'nm_trend';
            $config['user_kpi']['8']['n60_trend'] = 'n60_trend';
            $config['user_kpi']['9']['n90_trend'] = 'n90_trend';
            $config['user_kpi']['17']['nw_trend'] = 'nw_trend';
            $config['user_kpi']['31']['nm_trend'] = 'nm_trend';
            $config['user_kpi']['3']['n3_trend'] = 'n3_trend';
            $config['user_kpi']['14']['n14_trend'] = 'n14_trend';

            // retention rate
            $config['user_kpi']['17']['rrw_trend'] = 'rrw_trend';
            $config['user_kpi']['31']['rrm_trend'] = 'rrm_trend';

            // new user retention rate
            $config['user_kpi']['17']['nrrw_trend'] = 'nrrw_trend';
            $config['user_kpi']['31']['nrrm_trend'] = 'nrrm_trend';

            /* $config['user_kpi']['4']['cvrr1'] = 'cvrr1';
            $config['user_kpi']['5']['cvrr7'] = 'cvrr7';
            $config['user_kpi']['6']['cvrr30'] = 'cvrr30';
            $config['user_kpi']['7']['cvrrm'] = 'cvrrm'; */

            // gross revenue trend
            $config['user_kpi']['4']['gr1_n_trend'] = 'rv1_n_trend';
            $config['user_kpi']['5']['gr7_n_trend'] = 'rv7_n_trend';
            $config['user_kpi']['6']['gr30_n_trend'] = 'rv30_n_trend';
            $config['user_kpi']['7']['grm_n_trend'] = 'rvm_n_trend';
            $config['user_kpi']['8']['gr60_n_trend'] = 'rv60_n_trend';
            $config['user_kpi']['9']['gr90_n_trend'] = 'rv90_n_trend';
            $config['user_kpi']['17']['grw_n_trend'] = 'rvw_n_trend';
            $config['user_kpi']['31']['grm_n_trend'] = 'rvm_n_trend';
            $config['user_kpi']['3']['gr3_n_trend'] = 'rv3_n_trend';
            $config['user_kpi']['14']['gr14_n_trend'] = 'rv14_n_trend';

            $config['revenue_kpi']['4']['arpu1_trend'] = 'arpu1_trend';
            $config['revenue_kpi']['5']['arpu7_trend'] = 'arpu7_trend';
            $config['revenue_kpi']['6']['arpu30_trend'] = 'arpu30_trend';
            $config['revenue_kpi']['7']['arpum_trend'] = 'arpum_trend';
            $config['revenue_kpi']['8']['arpu60_trend'] = 'arpu60_trend';
            $config['revenue_kpi']['9']['arpu90_trend'] = 'arpu90_trend';
            $config['revenue_kpi']['17']['arpuw_trend'] = 'arpuw_trend';
            $config['revenue_kpi']['31']['arpum_trend'] = 'arpum_trend';
            $config['revenue_kpi']['3']['arpu3_trend'] = 'arpu3_trend';
            $config['revenue_kpi']['14']['arpu14_trend'] = 'arpu14_trend';

            $config['revenue_kpi']['4']['arppu1_trend'] = 'arppu1_trend';
            $config['revenue_kpi']['5']['arppu7_trend'] = 'arppu7_trend';
            $config['revenue_kpi']['6']['arppu30_trend'] = 'arppu30_trend';
            $config['revenue_kpi']['8']['arppu60_trend'] = 'arppu60_trend';
            $config['revenue_kpi']['9']['arppu90_trend'] = 'arppu90_trend';
            $config['revenue_kpi']['17']['arppuw_trend'] = 'arppuw_trend';
            $config['revenue_kpi']['31']['arppum_trend'] = 'arppum_trend';
            $config['revenue_kpi']['3']['arppu3_trend'] = 'arppu3_trend';
            $config['revenue_kpi']['14']['arppu14_trend'] = 'arppu14_trend';

            $config['revenue_kpi']['4']['cvr1_trend'] = 'cvr1_trend';
            $config['revenue_kpi']['5']['cvr7_trend'] = 'cvr7_trend';
            $config['revenue_kpi']['6']['cvr30_trend'] = 'cvr30_trend';
            $config['revenue_kpi']['8']['cvr60_trend'] = 'cvr60_trend';
            $config['revenue_kpi']['9']['cvr90_trend'] = 'cvr90_trend';
            $config['revenue_kpi']['17']['cvrw_trend'] = 'cvrw_trend';
            $config['revenue_kpi']['31']['cvrm_trend'] = 'cvrm_trend';
            $config['revenue_kpi']['3']['cvr3_trend'] = 'cvr3_trend';
            $config['revenue_kpi']['14']['cvr14_trend'] = 'cvr14_trend';

            // paying user trend
            $config['revenue_kpi']['4']['pu1_trend'] = 'pu1_trend';
            $config['revenue_kpi']['5']['pu7_trend'] = 'pu7_trend';
            $config['revenue_kpi']['6']['pu30_trend'] = 'pu30_trend';
            $config['revenue_kpi']['7']['pum_trend'] = 'pum_trend';
            $config['revenue_kpi']['8']['pu60_trend'] = 'pu60_trend';
            $config['revenue_kpi']['9']['pu90_trend'] = 'pu90_trend';
            $config['revenue_kpi']['17']['puw_trend'] = 'puw_trend';
            $config['revenue_kpi']['31']['pum_trend'] = 'pum_trend';
            $config['revenue_kpi']['3']['pu3_trend'] = 'pu3_trend';
            $config['revenue_kpi']['14']['pu14_trend'] = 'pu14_trend';

            // gross revenue trend
            $config['revenue_kpi']['4']['gr1_trend'] = 'rv1_trend';
            $config['revenue_kpi']['5']['gr7_trend'] = 'rv7_trend';
            $config['revenue_kpi']['6']['gr30_trend'] = 'rv30_trend';
            $config['revenue_kpi']['7']['grm_trend'] = 'rvm_trend';
            $config['revenue_kpi']['8']['gr60_trend'] = 'rv60_trend';
            $config['revenue_kpi']['9']['gr90_trend'] = 'rv90_trend';
            $config['revenue_kpi']['17']['grw_trend'] = 'rvw_trend';
            $config['revenue_kpi']['31']['grm_trend'] = 'rvm_trend';
            $config['revenue_kpi']['3']['gr3_trend'] = 'rv3_trend';
            $config['revenue_kpi']['14']['gr14_trend'] = 'rv14_trend';

            // new paying user trend
            $config['revenue_kpi']['4']['npu1_trend'] = 'npu1_trend';
            $config['revenue_kpi']['5']['npu7_trend'] = 'npu7_trend';
            $config['revenue_kpi']['6']['npu30_trend'] = 'npu30_trend';
            $config['revenue_kpi']['7']['npum_trend'] = 'npum_trend';
            $config['revenue_kpi']['8']['npu60_trend'] = 'npu60_trend';
            $config['revenue_kpi']['9']['npu90_trend'] = 'npu90_trend';
            $config['revenue_kpi']['17']['npuw_trend'] = 'npuw_trend';
            $config['revenue_kpi']['31']['npum_trend'] = 'npum_trend';
            $config['revenue_kpi']['3']['npu3_trend'] = 'npu3_trend';
            $config['revenue_kpi']['14']['npu14_trend'] = 'npu14_trend';

            // new paying gross revenue trend
            $config['revenue_kpi']['4']['npu_gr1_trend'] = 'npu_rv1_trend';
            $config['revenue_kpi']['5']['npu_gr7_trend'] = 'npu_rv7_trend';
            $config['revenue_kpi']['6']['npu_gr30_trend'] = 'npu_rv30_trend';
            $config['revenue_kpi']['7']['npu_grm_trend'] = 'npu_rvm_trend';
            $config['revenue_kpi']['8']['npu_gr60_trend'] = 'npu_rv60_trend';
            $config['revenue_kpi']['9']['npu_gr90_trend'] = 'npu_rv90_trend';
            $config['revenue_kpi']['17']['npu_grw_trend'] = 'npu_rvw_trend';
            $config['revenue_kpi']['31']['npu_grm_trend'] = 'npu_rvm_trend';
            $config['revenue_kpi']['3']['npu_gr3_trend'] = 'npu_rv3_trend';
            $config['revenue_kpi']['14']['npu_gr14_trend'] = 'npu_rv14_trend';

            // new user & paying user trend

            $config['revenue_kpi']['17']['nnpuw_trend'] = 'nnpuw_trend';
            $config['revenue_kpi']['31']['nnpum_trend'] = 'nnpum_trend';

            // new user & paying user gross revenue trend
            $config['revenue_kpi']['4']['nnpu_gr1_trend'] = 'nnpu_rv1_trend';
            $config['revenue_kpi']['5']['nnpu_gr7_trend'] = 'nnpu_rv7_trend';
            $config['revenue_kpi']['6']['nnpu_gr30_trend'] = 'nnpu_rv30_trend';
            $config['revenue_kpi']['7']['nnpu_grm_trend'] = 'nnpu_rvm_trend';
            $config['revenue_kpi']['8']['nnpu_gr60_trend'] = 'nnpu_rv60_trend';
            $config['revenue_kpi']['9']['nnpu_gr90_trend'] = 'nnpu_rv90_trend';
            $config['revenue_kpi']['17']['nnpu_grw_trend'] = 'nnpu_rvw_trend';
            $config['revenue_kpi']['31']['nnpu_grm_trend'] = 'nnpu_rvm_trend';
            $config['revenue_kpi']['3']['nnpu_gr3_trend'] = 'nnpu_rv3_trend';
            $config['revenue_kpi']['14']['nnpu_gr14_trend'] = 'nnpu_rv14_trend';
        }
        return $config;
    }

    public function get_kpi_header_name($kpiName = "Users")
    {
        $db_field_config = $this->db_field_config(true, $kpiName);
        $return = array();
        foreach ($db_field_config['user_kpi'] as $timing => $value) {
            foreach($value as $k => $v){
                $return[$k] = $v;
            }
        }
        foreach ($db_field_config['revenue_kpi'] as $timing => $value) {
            foreach($value as $k => $v){
                $return[$k] = $v;
            }
        }
        return $return;
    }

    public function add_timing($ids)
    {
        $t = array_values($this->get_timming_config());
        $return = array();
        foreach ($ids as $id) {
            for ($i = 0; $i < count($t); $i++) {
                $return[$id][] = $id . $t[$i];
            }
        }
        return $return;
    }

    public function add_field_name($ids){
        $db_field_config = $this->get_kpi_header_name();
        $return = array();
        foreach($ids as $id){
            $return[$id] = isset($db_field_config[$id]) ? $db_field_config[$id] : "";
        }
        return $return;
    }

	public function hard_code_check_game_in_new_db($currentGame){
		if (strpos($currentGame, 'myplay') !== false
			|| $currentGame=="contra"
			|| $currentGame == "dttk"
			|| $currentGame == "pmcl"
			|| $currentGame == "sgmb"
			|| $currentGame == "wefight"
			|| $currentGame == "tlbbm"
			|| $currentGame == "zpimgsn"
			|| $currentGame == "lv"
			|| $currentGame == "bc3"
			|| $currentGame == "kv"
			|| $currentGame == "ck"
			|| $currentGame == "zombie3dm"
            || $currentGame == "ts"
            || $currentGame == "kv"
			|| $currentGame == "ica"){
			return true;
		}
		return false;
	}

    public function get_header_name($fields){
        $header_name = $this->get_kpi_header_name();
        $return = array();
        for($i=0;$i<count($fields);$i++){
            $return[$fields[$i]] = $header_name[$fields[$i]];
        }
        return $return;
    }

    public function get_human_date($log_date){
        $return = date("d-M-Y", strtotime($log_date));
        return $return;
    }

    public function get_export_filename($game_code, $feature, $fromDate, $toDate="", $timing = "")
    {
    	$fileName = "";
    	$from = "";
    	$to = "";
    	
    	if($timing == "17"){
    		$from = date("W-Y", strtotime($fromDate));
    		if ($toDate != "") {
    			$to = date("W-Y", strtotime($toDate));
    		}
    	}else if($timing == "31"){

    		$from = date("M-Y", strtotime($fromDate));
    		if ($toDate != "") {
    			$to = date("M-Y", strtotime($toDate));
    		}
    	}else{
    		$from = date("d-M-Y", strtotime($fromDate));
    		if ($toDate != "") {
    			$to = date("d-M-Y", strtotime($toDate));
    		}
    	}
    	
    	$filename = $game_code . "_" . $feature . "_" . $from;
    	if ($toDate != "") {
    		$filename .= "_" . $to;
    	}
    	
        return $filename;
    }
    
    public function add_trend_icon(& $t_2, $timing, $config = null)
    {
        $up_icon = base_url("public/frontend/image/up_icon.jpg");
        $down_icon = base_url("public/frontend/image/down_icon.jpg");
        $equal_icon = base_url("public/frontend/image/equal_icon.jpg");
        $ignore_arr = array("rlc","rpc","cvr","aarpu","arppu","rpc","cpc","clc","rr","cr","prr"
        ,"cpr","nrc", "nrr");
        $t =  array_values($this->get_timming_config());
        $t1 = $ignore_arr;
        for($i=0;$i<count($t1);$i++){
            for($ii = 0;$ii < count($t);$ii++){
                $ignore_arr[] = $ignore_arr[$i] . $t[$ii];
            }
        }
        $ignore_arr[] = "log_date";

        for ($ii = 0; $ii < count($t_2); $ii++) {
            $t_data_1 = $t_2[$ii];
            foreach ($t_data_1 as $t_key => $t_value_1) {
                if (!in_array($t_key,$ignore_arr) && (in_array($t_key,$config) || $config == null)) {
                    $new_column_data="";
                    if (isset($t_2[$ii + 1])) {
                        $t_data_2 = $t_2[$ii + 1];
                        $t_value_2 = $t_data_2[$t_key];

                        $t_value_1 = intval(str_replace(",", "", $t_value_1));
                        $t_value_2 = intval(str_replace(",", "", $t_value_2));

                        if ($t_value_1 != 0 && $t_value_2 != 0) {
                            $percent = (($t_value_1 - $t_value_2) / ($t_value_2)) * 100;
                            if($percent > 0)
                                $new_column_data = '<span class="text-green">' . round($percent, 2) . '% ' . '<i class="fa fa-caret-up"></i></span> ';
                            else if ($percent == 0)
                                $new_column_data = '<i class="fa fa-ellipsis-h text-yellow"></i> ';
                            else
                                $new_column_data = '<span class="text-red">' . round($percent, 2) . '% ' . '<i class="fa fa-caret-down"></i></span>';

                        }
                    }

                    // add to row
                    $index = array_search($t_key,array_keys($t_2[$ii])) + 1;
                    $res = array_slice($t_2[$ii], 0, $index, true) +
                        array($t_key . "_trend" => $new_column_data) +
                        array_slice($t_2[$ii], $index, count($t_2[$ii])-$index, true);
                    $t_2[$ii] = $res;

                }
            }
        }
    }
    public function remove_channel_game_kpi_not_display($data){
        $ignore = $this->get_kpi_not_display();
        $timing_map = $this->get_timming_config();
        foreach($ignore as $kpi_id){
            foreach($timing_map as $timing){
                $kpi_remove = $kpi_id . $timing;
                for($i=0;$i<count($data);$i++){
                    $channel_list = $data[i]['channel'];
                    foreach ($channel_list as $channel) {
                        unset($data[$i][$kpi_remove][$channel]);
                    }

                }
            }
        }
        return $data;
    }

    public function remove_package_game_kpi_not_display($data){
        $ignore = $this->get_kpi_not_display();
        $timing_map = $this->get_timming_config();
        foreach($ignore as $kpi_id){
            foreach($timing_map as $timing){
                $kpi_remove = $kpi_id . $timing;
                for($i=0;$i<count($data);$i++){
                    $package_list = $data[i]['package'];
                    foreach ($package_list as $package) {
                        unset($data[$i][$kpi_remove][$package]);
                    }

                }
            }
        }
        return $data;
    }

    public function remove_game_kpi_not_display($data){
        $ignore = $this->get_kpi_not_display();
        $timing_map = $this->get_timming_config();
        foreach($ignore as $kpi_id){
            foreach($timing_map as $timing){
                $kpi_remove = $kpi_id . $timing;
                for($i=0;$i<count($data);$i++){
                    unset($data[$i][$kpi_remove]);
                }
            }
        }
        return $data;
    }

    public function remove_os_kpi_not_display($data){
        $ignore = $this->get_kpi_not_display();
        $timing_map = $this->get_timming_config();
        $os_list = $this->get_os_list();
        foreach($ignore as $kpi_id){
            foreach($timing_map as $timing){
                $kpi_remove = $kpi_id . $timing;
                foreach($os_list as $os){
                    for($i=0;$i<count($data);$i++){
                        unset($data[$i][$os][$kpi_remove]);
                    }
                }
            }
        }
        return $data;
    }

	public function re_organize_db_data($dbdata){
		$return = array();
		$count = count($dbdata);
		for($i=0;$i < $count;$i++){
			$value = $dbdata[$i];
			foreach($value as $f => $v){
				$return[$f][] = $v;
			}
		}
		return $return;
	}
	
	/**
	 * Convert
	 * log_date			val1		val2		valn
	 * 2016-01-01		1			2			3
	 * TO
	 * log_date		2016-01-01
	 * val1			1
	 * val2			2
	 * 
	 * @param unknown $dbdata
	 * @author vinhdp
	 */
	public function rotate_db_data($dbdata){
		
		$return = array();
		$count = count($dbdata);
		$firstRow = $dbdata[0];

		foreach($firstRow as $f => $v){
			
			// loop through each key
			
			$rowKey = array();
			$sum = 0;
			for($i = 0; $i < $count; $i++) {
				// loop all data
				$rowKey[] = $dbdata[$i][$f];	// add to new row key (auto increase index by 1)
				$sum += $dbdata[$i][$f];
			}
			
			if($sum > 0){
			
				$return[$f] = $rowKey;
			}
		}

		return $return;
	}

    public function db_field_marketing(){
        $config['user_kpi']['4']['mktinstall'] = 'Install';

        $config['user_kpi']['4']['mktnru0'] = 'NRU0';

        $config['user_kpi']['4']['mktnru1'] = 'NRU';

        $config['revenue_kpi']['4']['mktpu1'] = 'PU1';
        $config['revenue_kpi']['3']['mktpu3'] = 'PU3';
        $config['revenue_kpi']['5']['mktpu7'] = 'PU7';
        $config['revenue_kpi']['6']['mktpu30'] = 'PU30';

        $config['revenue_kpi']['4']['mktrev1'] = 'REV1';
        $config['revenue_kpi']['3']['mktrev3'] = 'REV3';
        $config['revenue_kpi']['5']['mktrev7'] = 'REV7';
        $config['revenue_kpi']['6']['mktrev30'] = 'REV30';
        return $config;
    }
    public function get_kpi_must_calc(){
        $extra_list = array("cr", "arppu", "arpu", "cvr", "avgtime");
        return $extra_list;
    }

    public function get_kpi_not_display(){
        $ignore_list = array("cpc", "rlc", "nrc", "clc", "rpc", "rr", "ptime", "retention");
        return $ignore_list;
    }

	public function get_all_kpi(){
        $ignore_list = array();//$this->get_kpi_not_display();
        $extra_list = array();//$this->get_kpi_must_calc();
        $timming_map = $this->get_timming_config();
        $db_field_config = $this->db_field_config(false);
        $return = array();
        foreach ($db_field_config['user_kpi'] as $timing => $value) {
            if ($timing == "7") continue;
            foreach ($value as $k => $v) {
                if (!in_array(substr($k, 0, -strlen($timming_map[$timing])), $ignore_list))
                    $return[$k] = $v;
            }
        }
        foreach ($db_field_config['revenue_kpi'] as $timing => $value) {
            if ($timing == "7") continue;
            foreach ($value as $k => $v) {
                if (!in_array(substr($k, 0, -strlen($timming_map[$timing])), $ignore_list))
                    $return[$k] = $v;
            }
            $extra_list1 = array();
            foreach ($extra_list as $k) {
                $extra_list1[] = $k . $timming_map[$timing];
            }
            $extra_list1 = $this->add_field_name($extra_list1);
            foreach ($extra_list1 as $k => $v) {
                $return[$k] = $v;
            }
        }
        return $return;

    }
	public function create_trend_column(& $data){
		
		$numberOfNewColumns = 0;
		
		foreach($data as $key => $value){
			
			if($key == "log_date"){
				continue;
			}
			$count = count($value);
			
			if($numberOfNewColumns < $count){
				
				$numberOfNewColumns = $count;
			}
			
			$res = $data[$key];
			
			for($i = 1; $i < $count; $i++){
				
				$currentValue = 0;
				$oldValue = 0;
				$trendData = 0;
				$percentFlag = false;
				
				if(strpos ( $data[$key][0], "%" ) == false){
					$currentValue = intval(str_replace(',', '', $data[$key][0]));
					$oldValue = intval(str_replace(',', '', $data[$key][$i]));
				}else{
					$currentValue = floatval(str_replace(',', '', str_replace(' %', '', $data[$key][0])));
					$oldValue = floatval(str_replace(',', '', str_replace(' %', '', $data[$key][$i])));
					$percentFlag = true;
				}

				if($currentValue != 0 && $oldValue != 0){
					
					$percent = 0.00;
					if($percentFlag){
						
						$percent = $currentValue - $oldValue;
					}else{
						$percent = (($currentValue - $oldValue) / ($oldValue)) * 100;
					}
					if($percent > 0){
						$trendData = '<span class="text-green">' . number_format ($percent, 2) . '% </span><i class="fa fa-caret-up text-green"></i>';
					} else if ($percent == 0){
						$trendData = '<i class="fa fa-ellipsis-h text-yellow"></i> ';
					} else {
						$trendData = '<span class="text-red">' . number_format ($percent, 2) . '% </span><i class="fa fa-caret-down text-red"></i>';
					}
				}else{
					$trendData = '<i class="fa fa-ellipsis-h text-yellow"></i> ';
				}
				$data[$key][] = $trendData;
			}
		}
		
		$columnNameArr = array("(Day/ -1Day)", "(Day/ -7Day)", "(Day/ -30Day)");
		//$columnNameArr = array("(Selected Day / Day - 1)", "(Selected Day / Day -7)", "(Selected Day / Day - 30)");
		// process log_date value
		for($i = 0; $i < $numberOfNewColumns - 1; $i++){
			$data['log_date'][] = $columnNameArr[$i];
		}
	}
	
	public function create_trend_column_2(& $data){
	
		$numberOfNewColumns = 0;
	
		foreach($data as $key => $value){
				
			if($key == "log_date"){
				continue;
			}
			$count = count($value);
				
			if($numberOfNewColumns < $count){
	
				$numberOfNewColumns = $count;
			}
				
			$res = $data[$key];
			
			for($i = 1; $i < $count; $i++){
	
				$currentValue = 0;
				$oldValue = 0;
				$trendData = 0;
				$percentFlag = false;
	
				if(strpos ( $data[$key][0], "%" ) == false){
					$currentValue = intval(str_replace(',', '', $data[$key][0]));
					$oldValue = intval(str_replace(',', '', $data[$key][$i]));
				}else{
					$currentValue = floatval(str_replace(',', '', str_replace(' %', '', $data[$key][0])));
					$oldValue = floatval(str_replace(',', '', str_replace(' %', '', $data[$key][$i])));
					$percentFlag = true;
				}
	
				if($currentValue != 0 && $oldValue != 0){
						
					$percent = 0.00;
					if($percentFlag){
	
						$percent = $currentValue - $oldValue;
					}else{
						$percent = (($currentValue - $oldValue) / ($oldValue)) * 100;
					}
					if($percent > 0){
						$trendData = '<span class="text-green">' . number_format ($percent, 2) . '% </span><i class="fa fa-caret-up text-green"></i>';
					} else if ($percent == 0){
						$trendData = '<i class="fa fa-ellipsis-h text-yellow"></i> ';
					} else {
						$trendData = '<span class="text-red">' . number_format ($percent, 2) . '% </span><i class="fa fa-caret-down text-red"></i>';
					}
				}else{
					$trendData = '<i class="fa fa-ellipsis-h text-yellow"></i> ';
				}
				$data[$key][] = $trendData;
			}
		}
	}
	
    public function get_main_chart_title($array_info, $timing){
        $game_name = ($array_info['game_info']["GameName"]);
        $feature = ($array_info['feature']);
        switch ($timing) {
            default:
                $rs = $feature . ' - ' . $game_name;
                break;
            case 4:
                $rs = "Daily " . $feature . ' - ' . $game_name;
                break;
            case 5:
            	$rs = "7 Days " . $feature . ' - ' . $game_name;
            	break;
            case 6:
            	$rs = "30 Days " . $feature . ' - ' . $game_name;
            	break;
            case 17:
                $rs = "Weekly " . $feature . ' - ' . $game_name;
                break;
            case 31:
                $rs = "Monthly " . $feature . ' - ' . $game_name;
                break;
        }
        return $rs;
    }

    public function get_sub_chart_title($array_info, $timing){
        $to = "";
        switch ($timing) {
            default:
            case 4:
            case 5:
            case 6:
                $fr = date("d-M-Y", strtotime($array_info['from']));
                if($array_info['to'] != ""){
                    $to = date("d-M-Y", strtotime($array_info['to']));
                }
            break;
            case 17:
                $fr = "Week " . date("W-Y", strtotime($array_info['from']));
                if($array_info['to'] != ""){
                    $to = "Week " . date("W-Y", strtotime($array_info['to']));
                }
            break;
            case 31:
                $fr = date("M-Y", strtotime($array_info['from']));
                if($array_info['to'] != ""){
                    $to = date("M-Y", strtotime($array_info['to']));
                }
            break;
        }

        if($to!=""){
            $rs1 = "From " . $fr . " to " . $to;
        }else{
            $rs1 = $fr;
        }
        if($array_info['source']){
            $rs1 .= " (source: " . $array_info['source'] . ")";
        }
        return $rs1;
    }

    public function get_nearest_date_by_timming($log_date,$timming)
    {
        $weeks = $this->listOptionsWeek();
        $months = $this->listOptionsMonth();

        $week_keys = array_keys($weeks);
        $month_keys = array_keys($months);

        $rs = $log_date;
        if ($timming == 4) {
            $rs = date('Y-m-d', strtotime($log_date . " - 1 day"));
            //$rs = $log_date;
        } else if ($timming == 17) {
            for ($i = 0; $i < 8; $i++) {
                $t = date('Y-m-d', strtotime($log_date . " - " . $i . " day"));
                if (in_array($t, $week_keys)) {
                    $rs = date('Y-m-d', strtotime($t . " - 1 day"));
                    break;
                }
            }
        } else if ($timming == 31) {
            for ($i = 1; $i < 33; $i++) {
                $t = date('Y-m-d', strtotime($log_date . " - " . $i . " day"));
                if (in_array($t, $month_keys)) {
                    $rs = $t;
                    break;
                }
            }
        }
        return $rs;
    }

	/**
	 * Modify date: 2016-08-02
	 * By: vinhdp
	 * Modify: 5, 6 as daily
	 * Add: 17 as weekly, 31 as monthly
	 * 
	 */
	public function get_xcolumn_by_timming($log_date,$timming,$prefix)
	{
		switch ($timming) {
			default:
			case 4:
			case 5:
			case 6:
				if ($prefix)
					$return = $this->formatDate("Y-m-d", "d-M-y", $log_date);
				else
					$return = $this->formatDate("Y-m-d", "d-M-y", $log_date);
				break;
			case 17:
				if ($prefix)
					$return = "Week " . date('W, Y', strtotime($log_date));
				else
					$return = date('Y-W', strtotime($log_date));
				break;
			case 31:
				if ($prefix)
					$return = date('M-Y', strtotime($log_date));
				else
					$return = date('M-Y', strtotime($log_date));
				break;
		}
		return $return;
	}

    public function parseUserInput($class_name,&$viewData,&$chartData,&$tableData, $isSessionCache = true, $dateLen = 180){

        if ($_SESSION[$class_name] && $isSessionCache == true) {
			$userInput = $_SESSION[$class_name]['post'];
			$viewData['body'] = array_merge($viewData['body'],$userInput);
			if(isset($userInput["daterangepicker"]) && $userInput["daterangepicker"] != ""){
				$tmp = explode("-",$userInput["daterangepicker"]);
				$viewData['body']['day'][1] = trim($tmp[1]);
				$viewData['body']['day'][2] = trim($tmp[0]);
			}else{
				$viewData['body']['day']['2'] = $userInput["datesinglepicker"];
			}
        } else if($_SESSION[$class_name] && $isSessionCache == false){
        	
        	$userInput = $_SESSION[$class_name]['post'];
        	if($userInput!=null){
        		$viewData['body'] = array_merge($viewData['body'],$userInput);
        		if(isset($userInput["daterangepicker"]) && $userInput["daterangepicker"] != ""){
        			$tmp = explode("-",$userInput["daterangepicker"]);
        			$viewData['body']['day'][1] = trim($tmp[1]);
        			$viewData['body']['day'][2] = trim($tmp[0]);
        		}else{
        			$viewData['body']['day']['2'] = $userInput["datesinglepicker"];
        		}
        	}else{
	        	$viewData['body']['day'][1] = date('d/m/Y', strtotime("-1 days"));
	        	$viewData['body']['day'][2] = date('d/m/Y', strtotime("-" . $dateLen . " days"));
	        	//set default week
	        	$t_list_week = array_keys($viewData['body']['optionsWeek']);
	        	$viewData['body']['week'][1] = $t_list_week[0];
	        	$viewData['body']['week'][2] = $t_list_week[12];
	        	//set default month
	        	$t_list_month = array_keys($viewData['body']['optionsMonth']);
	        	$viewData['body']['month'][1] = $t_list_month[0];
	        	$viewData['body']['month'][2] = $t_list_month[12];
        	}
        } else {
            $viewData['body']['options'] = 4;
            //set default day
            $viewData['body']['day'][1] = date('d/m/Y', strtotime("-1 days"));
            $viewData['body']['day'][2] = date('d/m/Y', strtotime("-" . $dateLen . " days"));
            //set default week
            $t_list_week = array_keys($viewData['body']['optionsWeek']);
            $viewData['body']['week'][1] = $t_list_week[0];
            $viewData['body']['week'][2] = $t_list_week[12];
            //set default month
            $t_list_month = array_keys($viewData['body']['optionsMonth']);
            $viewData['body']['month'][1] = $t_list_month[0];
            $viewData['body']['month'][2] = $t_list_month[12];
        }
        
        // vinhdp modify date 2016-10-27 add $isSessionCache
        // end modify
        
		$viewData['body']['day']['default_range_date'] = $viewData['body']['day'][2] . " - " . $viewData['body']['day'][1];
		$viewData['body']['day']['default_single_date'] = $viewData['body']['day'][2];

        $_option = $viewData['body']['options'];
        switch($_option) {
            case '4':
            case '5':
            case '6':
            default:
                $subTitle = 'From ' . $this->formatDate("d/m/Y", "d-M-Y", $viewData['body']['day'][2])  . ' to ' .   $this->formatDate("d/m/Y", "d-M-Y", $viewData['body']['day'][1]);
                $reportType = 'Daily';
                $timing = 'daily';
                list($day, $month, $year) = explode('/', $viewData['body']['day'][1]);
                $toDate = $year . '-' . $month . '-' . $day;
                list($day, $month, $year) = explode('/', $viewData['body']['day'][2]);
                $fromDate = $year . '-' . $month . '-' . $day;
                break;
            /* case 5:
                $subTitle = 'Tuần ' . date('W/Y', strtotime($viewData['body']['week'][2])) . ' - ' . date('W/Y', strtotime($viewData['body']['week'][1])) ;
				// vi ngay dau tuan la ngay chu nhat, nen phai sua lai cho nay`, tang len 1 ngay
				//$subTitle = 'Tuần ' . date('W/Y', strtotime($viewData['body']['week'][2]) + 86400) . ' - ' . date('W/Y', strtotime($viewData['body']['week'][1]) + 86400) ;
                $reportType = 'TUẦN';
                $timing = 'weekly';
                $toDate = $viewData['body']['week'][1];
                $fromDate = $viewData['body']['week'][2];
                break;
            case 6:
                $subTitle = 'Tháng ' . date('m/Y', strtotime($viewData['body']['month']['2'])) . ' - ' . date('m/Y', strtotime($viewData['body']['month']['1'])) ;
                $reportType = 'THÁNG';
                $timing = 'monthly';
                $toDate = $viewData['body']['month'][1];
                $fromDate = $viewData['body']['month'][2];
                break; */
            case 17:
                $subTitle = 'From week ' . date('W-Y', strtotime($viewData['body']['week'][2])) . ' to ' . date('W-Y', strtotime($viewData['body']['week'][1])) ;
                // vi ngay dau tuan la ngay chu nhat, nen phai sua lai cho nay`, tang len 1 ngay
                //$subTitle = 'Tuần ' . date('W/Y', strtotime($viewData['body']['week'][2]) + 86400) . ' - ' . date('W/Y', strtotime($viewData['body']['week'][1]) + 86400) ;
                $reportType = 'Weekly';
                $timing = 'weekly';
                $toDate = $viewData['body']['week'][1];
                $fromDate = $viewData['body']['week'][2];
                break;
            case 31:
                $subTitle = 'From ' . date('M-Y', strtotime($viewData['body']['month']['2'])) . ' to ' . date('M-Y', strtotime($viewData['body']['month']['1'])) ;
               	$reportType = 'Monthly';
                $timing = 'monthly';
                $toDate = $viewData['body']['month'][1];
                $fromDate = $viewData['body']['month'][2];
                break;
        }

        $tableData['reportType'] = $reportType;
        $chartData['container_1']['subTitle'] = $subTitle;
        $chartData['container_1']['timing'] = $timing;
        $viewData['body']['toDate'] = $toDate;
        $viewData['body']['fromDate'] = $fromDate;
    }

    public function parse_date_input($date_string){
        $t1 = explode("-", $date_string);
        $t2 = explode("/", trim($t1[0]));
        $fromDate = $t2[2] . "-" . $t2[1] . "-" . $t2[0];
        $t3 = explode("/", trim($t1[1]));
        $toDate = $t3[2] . "-" . $t3[1] . "-" . $t3[0];
        return array($fromDate,$toDate);
    }
    
    public function getKpiListDate($date){
    	$dateString = $date->format('Y-m-d');
    	
    	$result = array();
    	$result[] = $date->format('Y-m-d');		// log_date
    	$result[] = date('Y-m-d', strtotime('- 1 day', strtotime($dateString)));		// yesterday
    	$result[] = date('Y-m-d', strtotime('- 2 day', strtotime($dateString)));		// 2 days before
    	$result[] = date('Y-m-d', strtotime('- 7 day', strtotime($dateString)));		// 7 days before
    	$result[] = date('Y-m-d', strtotime('- 8 day', strtotime($dateString)));		// 8 days before
    	$result[] = date('Y-m-d', strtotime('- 30 day', strtotime($dateString)));		// 30 days before
    	$result[] = date('Y-m-d', strtotime('- 31 day', strtotime($dateString)));		// 31 days before

    	return $result;
    }

    public function user_date_to_db_date($date){
        $list = explode("/", $date);
        return $list[2] . "-" . $list[1] . "-" . $list[0];
    }
    public function db_date_to_user_date($date){
        $list = explode("-", $date);
        return $list[2] . "/" . $list[1] . "/" . $list[0];
    }

    /**
     * Modify date: 2016-08-02
     * By: vinhdp
     * Add: 17, 31
     * **************************
     * Next Modify
     */
    public function get_timming_config()
    {
        return array("4" => "1", "5" => "7", "6" => "30", "8" => "60", "9" => "90", "17" => "w", "31" => "m",
            "3" => "3", "14" => "14");
    }
    public function get_stringtotime_config(){
        $config = array("4" => "day","5" => "day","6" => "day", "17" => "week", "31" => "month");
        return  $config;
    }

    public function send_mail($mail_config)
    {
        $result = false;
        $this->CI->email->clear(TRUE);
        $from = $mail_config['from'];
        $fromalias = isset($mail_config['fromalias']) ? $mail_config['fromalias'] : "";
        $cc = isset($mail_config['cc']) ? $mail_config['cc'] : null;
        $attach = isset($mail_config['attach']) ? $mail_config['attach'] : null;
        $to = $mail_config['to'];
        $subject = $mail_config['subject'];
        $message = $mail_config['message'];

        if($from != "" && $to != "" && $subject != "" && $message != ""){
            $this->CI->email->set_newline("\r\n");
            $this->CI->email->from($from, $fromalias);
            $this->CI->email->to($to);
            $this->CI->email->subject($subject);
            $this->CI->email->message($message);

            if($cc != null){
                $this->CI->email->cc($cc);
            }

            if($attach != null){
                for($i=0;$i<count($attach);$i++){
                    if(file_exists($attach[$i])){
                        $this->CI->email->attach($attach[$i]);
                    }
                }
            }

            if($this->CI->email->send())
            {
                $result = true;
            }
            else
            {
                show_error($this->CI->email->print_debugger());
            }

        }
        return $result;
    }
    public function get_field_and_timming($field)
    {
        $timming_map = $this->get_timming_config();
        $timming = array_values($timming_map);
        $rs = "";
        for ($i = 0; $i < count($timming); $i++) {
            $_timming = $timming[$i];
            if ($this->ends_with($field, $_timming)) {
                if (strlen($_timming) >= strlen($rs)) {
                    $rs = $_timming;
                }
            }
        }
        if ($rs != "") {
            return array(str_replace($rs, "", $field), $rs);
        }
        return null;
        /*
        $length = strlen($field);
        for($i=1;$i<=$length;$i++){
            $t = substr($field,$length-$i,$i);
            if(in_array($t,$timming)){
                return array(substr($field,0,$length-$i),$t);
            }
        }
        return null;
        */
    }

    public function starts_with($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    public function ends_with($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
        return (substr($haystack, -$length) === $needle);
    }
    
    public function IsNullOrEmptyString($value){
    	return (!isset($value) || trim($value)==='');
    }
    /*
     * if(strpos($number, ".") !== false && $number < 100){
            $number = round($number,2);
        }else{
            if(strpos($number, ",") === false && strpos($number, "%") === false){
                $number = number_format($number);
            }
        }
        return $number;
     */

    public function ud_format_number($number){
        if(is_numeric($number)){
            if(strpos($number, ".") !== false && $number < 100) {
                $number = round($number, 2);
            }else{
                $number = number_format($number);
            }
        }
        return $number;
    }
    
    public function formatDate($from, $to, $dateString){
    	$d = DateTime::createFromFormat ($from , $dateString);
    	$formattedDate = date_format($d, $to);
    	return $formattedDate;
    }
    public function getEndDateOfMonth($dateStr){
    	return date("Y-m-t", strtotime($dateStr));
    }
    public function getRangeDateText($dateStr, $block){
    	$from = date('Y-m-d', strtotime('- '.$block.' day', strtotime($dateStr)));
    	$dateFrom = $this->db_date_to_user_date($from);
    	$fromText =     $this->formatDate("Y-m-d","d M, Y",$from);	
    	$toText =     $this->formatDate("Y-m-d","d M, Y",$dateStr);
    	return $fromText ." - " .$toText;
    }

    public function get_db_date_from_range_date($range_date)
    {
        $t1 = explode("-", $range_date);
        $from_string = $t1[0];
        $to_string = $t1[1];
        $from_arr = explode("/", $from_string);
        $from = trim($from_arr[2]) . "-" . trim($from_arr[1]) . "-" . trim($from_arr[0]);
        $to_arr = explode("/", $to_string);
        $to = trim($to_arr[2]) . "-" . trim($to_arr[1]) . "-" . trim($to_arr[0]);

        return array($from, $to);
    }
    
    public function generateColors($arrKey){
    	// FB, GG, ZM, ZL
    	$fixedColor = array("FB" => "#3B5998", "GG" => "#DD4B39", "ZM" => "#008080", "ZL" => "#1E90FF", "other" => "#696969");
    	$results = array();
    	$colors = array(
    			"#DAA520", "#2F4F4F", "#B0C4DE", "#800000", "#808000", "#CD853F", "#708090"
    	);
    	$i = 0;
    	
    	foreach ($arrKey as $key){
    		
    		if(isset($fixedColor[$key])){
    			$results[$key] = $fixedColor[$key];
    		}else{
    			$results[$key] = $colors[$i];
    		}
    		$i++;
    		
    		if($i >= count($colors)){
    			$i = 0;
    		}
    	}
    	
    	return $results;
    }
    
    public function generateColors2($selectedGroup){

    	$selectedArr = array();
    	foreach($selectedGroup as $kpi => $groupArr){
    		foreach($groupArr as $id => $name){
    			if (!array_key_exists($id, $selectedArr)){
    				$selectedArr[$id] = $id;
    			}
    		}
    	}
    	// FB, GG, ZM, ZL
    	$fixedColor = array("fb" => "#3B5998", "gg" => "#DD4B39", "zm" => "#008080", "zl" => "#1E90FF", "other" => "#696969");
    	$results = array();
    	$colors = array(
    			"#DAA520", "#2F4F4F", "#B0C4DE", "#800000", "#808000", "#CD853F", "#708090",
    			"#5F9EA0", "#008B8B", "#FF8C00", "#2F4F4F", "#DAA520", "#CD5C5C",
    			"#F08080", "#20B2AA", "#778899", "#9370DB", "#3CB371", "#191970", "#FF4500",
    			"#DB7093", "#663399", "#4169E1", "#8B4513", "#4682B4", "#008080", "#40E0D0"
    	);
    	
    	$i = 0;
    	foreach ($selectedArr as $key){
    
    		if(isset($fixedColor[$key])){
    			$results[$key] = $fixedColor[$key];
    		}else{
    			$results[$key] = $colors[$i];
    		}
    		$i++;
    
    		if($i >= count($colors)){
    			$i = 0;
    		}
    	}
    	return $results;
    }

    public function get_os_kpi_id(){
        $kpi_config = array("a1" => "", "a7" => "", "a30" => "", "gr1" => "", "gr7" => "", "gr30" => "",
            "pu1" => "", "pu7" => "", "pu30" => "",
            "npu1" => "", "npu7" => "", "npu30" => "",
            "npu_gr1" => "", "npu_gr7" => "", "npu_gr30" => "",
            "n1"=>"","n7"=>"","n30"=>""
        );

        //$kpi_config = array("a1" => "", "a7" => "", "a30" => "");

        return $kpi_config;
    }
    
    public function reverseData($table)
    {
        $newTable = array();
        $count = count($table);
        for ($i = 0; $i < $count; $i++) {
            $row = $table[$i];
            foreach ($row as $key => $value) {
                if ($key != "log_date" && is_numeric($value)) {
                    if (strpos($value, ".") !== false) {
                        $value = round($value,2);
                        //it's not percent
                        if ($value > 100) {
                            $value = number_format($value);
                        }
                    } else {
                        $value = number_format($value);
                    }
                }
                $newTable[$key][] = $value;
            }
        }
        return $newTable;
    }
    
    public function reverseData2($table, $name){
    	 
    	$newTable = array();
    	$i = 0;
    	foreach($table as $key => $value){
    		
    		$newTable[$name][$i] = $key;
    		
    		foreach($value as $k => $v){
    			
    			if(is_numeric($value)){
    			
    				$newTable[$k][$i] = number_format($v);
    			}else{
    				$newTable[$k][$i] = $v;
    			}
    		}
    		
    		$i = $i + 1;
    	}
    	return $newTable;
    }
    
    function getStartDateIn($date, $timming) {
    	
    	$ts = strtotime($date);
    	switch ($timming) {
    		default:
    		case 4:
    		case 5:
    		case 6:
    			$return="";
    		case 17:
    			$start = strtotime('last Monday', $ts);
    			$return = date('d-M-Y', $start);
    			break;
    		case 31:
    			$return = date('01-M-Y', strtotime($date));
    			break;
    	}
    	return $return;
    }
    public function getDateByTimming($log_date,$timming)
    {
    	switch ($timming) {
    		default:
    		case 4:
    		case 5:
    		case 6:
    				$return = date('d-M-Y', strtotime($log_date));
    				break;
    		case 17:
   					$return = "w" .date('W-Y', strtotime($log_date));
    				break;
    		case 31:
   					$return = date('M-Y', strtotime($log_date));
    				break;
    	}
    	return $return;
    }
    public function getLastDaysOfMonths($logDate,$nMonths,$inPast){
    	$dates =array();
    	$dateStr=$logDate;
    	if($inPast==false){
    		array_push($dates,$dateStr);
    	}
    	for($i=0;$i<$nMonths;$i++){
    		$dateStr = date('Y-m-d', strtotime($logDate . '-' .($i+1) .' month'));
    		$d = date("Y-m-t", strtotime($dateStr));
    		array_push($dates,$d);
    	}
    	return $dates;
    }

    public function get_kpi_in_percent()
    {
        $percent_kpi = array("rr", "nrr", "cr", "cvr", "prr");
        return $percent_kpi;
    }
    public function getLastDayOfMonth($logDate,$inPast){
    	$dateStr=$logDate;
    	if($inPast==false){
    		return date("Y-m-t", strtotime($logDate));    		
    	}else{
    		$dateStr = date('Y-m-d', strtotime($logDate . '-' .($i+1) .' month'));
    		return date("Y-m-t", strtotime($dateStr));
    	}
    }

    public function checkUrlAlive($url){
        $file_headers = @get_headers($url);
        if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
            $exists = false;
        }
        else {
            $exists = true;
        }
        return $exists;
    }

    public function redirect_to($url){
        header("Location: " . $url);
    }
    public function get_current_query_string(){
        $request_uri = $_SERVER['REQUEST_URI'];

        if(strpos($request_uri, "index.php") !== false){
            $t1 = explode("index.php", $request_uri);
            return $t1[1];
        }else{
            return $request_uri;
        }

    }

    public function calculate_report_not_in_database($game_code, $data, $must_calc)
    {
        for ($j = 0; $j < count($data); $j++) {
            $c_row = $data[$j];
            $b_row = $data[$j - 1];
            $percent_or_not = "";
            for ($i = 0; $i < count($must_calc); $i++) {
                $id_arr = $this->get_field_and_timming($must_calc[$i]);
                if ($id_arr != null) {
                    switch ($id_arr[0]) {
                        case "rr":
                            $need = "rr" . $id_arr[1];
                            if (isset($c_row[$need]))
                                $data[$j]['rr' . $id_arr[1]] = round($c_row[$need], 2) . $percent_or_not;
                            else
                                $data[$j]['rr' . $id_arr[1]] = "0" . $percent_or_not;;
                            break;
                        case "nrr":
                            $need = "nrr" . $id_arr[1];
                            if (isset($c_row[$need]))
                                $data[$j]['nrr' . $id_arr[1]] = round($c_row[$need], 2) . $percent_or_not;
                            else
                                $data[$j]['nrr' . $id_arr[1]] = "0" . $percent_or_not;
                            break;
                        case "arppu":
                            $need_1 = "pu" . $id_arr[1];
                            $need_2 = "gr" . $id_arr[1];
                            if (isset($c_row[$need_2]) && isset($c_row[$need_1]) && $c_row[$need_1] != 0 && $c_row[$need_2] != 0) {
                                $data[$j]['arppu' . $id_arr[1]] = round($c_row[$need_2] / $c_row[$need_1], 2);
                            } else {
                                $data[$j]['arppu' . $id_arr[1]] = 0;
                            }
                            break;
                        case "arpu":
                            $need_1 = "a" . $id_arr[1];
                            $need_2 = "gr" . $id_arr[1];
                            if (isset($c_row[$need_2]) && isset($c_row[$need_1]) && $c_row[$need_1] != 0 && $c_row[$need_2] != 0) {
                                $data[$j]['arpu' . $id_arr[1]] = round($c_row[$need_2] / $c_row[$need_1], 2);
                            } else {
                                $data[$j]['arpu' . $id_arr[1]] = 0;
                            }
                            break;
                        case "cvr":
                            $need_1 = "a" . $id_arr[1];
                            $need_2 = "pu" . $id_arr[1];
                            if (isset($c_row[$need_2]) && isset($c_row[$need_1]) && $c_row[$need_1] != 0 && $c_row[$need_2] != 0) {
                                $data[$j]['cvr' . $id_arr[1]] = round(($c_row[$need_2] / $c_row[$need_1]) * 100, 2) . $percent_or_not;
                            } else {
                                $data[$j]['cvr' . $id_arr[1]] = "0" . $percent_or_not;
                            }
                            break;
                        case "cr":
                            $need_1 = "rr" . $id_arr[1];
                            if (isset($c_row[$need_1]) && $c_row[$need_1] != 0) {
                                $data[$j]['cr' . $id_arr[1]] = 100 - $c_row[$need_1];
                            } else {
                                $data[$j]['cr' . $id_arr[1]] = "0" . $percent_or_not;
                            }
                            break;
                        case "avgtime":
                            $need_1 = "a" . $id_arr[1];
                            $need_2 = "ptime" . $id_arr[1];
                            if (isset($c_row[$need_2]) && isset($c_row[$need_1]) && $c_row[$need_1] != 0 && $c_row[$need_2] != 0) {
                            	$data[$j]['avgtime' . $id_arr[1]] = round($c_row[$need_2] / $c_row[$need_1], 2);
                            } else {
                            	$data[$j]['avgtime' . $id_arr[1]] = 0;
                            }
                            break;
                    }
                }
            }
        }
        return $data;
    }

    public function calculate_channel_report_not_in_database($game_code, $data, $must_calc)
    {
        for ($j = 0; $j < count($data); $j++) {
            $channel_list = $data[$j]['channel'];

            foreach ($channel_list as $channel) {

                $c_row = $data[$j];
                $b_row = $data[$j - 1];
                $percent_or_not = "";
                for ($i = 0; $i < count($must_calc); $i++) {
                    $id_arr = $this->get_field_and_timming($must_calc[$i]);
                    if ($id_arr != null) {
                        switch ($id_arr[0]) {
                            case "rr":
                                $need = "rr" . $id_arr[1];
                                if (isset($c_row[$need]))
                                    $data[$j][$channel]['rr' . $id_arr[1]] = round($c_row[$need], 2) . $percent_or_not;

                                else
                                    $data[$j][$channel]['rr' . $id_arr[1]] = "0" . $percent_or_not;;
                                break;
                            case "nrr":
                                $need = "nrr" . $id_arr[1];
                                if (isset($c_row[$need]))
                                    $data[$j][$channel]['nrr' . $id_arr[1]] = round($c_row[$need], 2) . $percent_or_not;

                                else
                                    $data[$j][$channel]['nrr' . $id_arr[1]]= "0" . $percent_or_not;

                                break;
                            case "arppu":
                                $need_1 = "pu" . $id_arr[1];
                                $need_2 = "gr" . $id_arr[1];
                                if (isset($c_row[$need_2]) && isset($c_row[$need_1]) && $c_row[$need_1] != 0 && $c_row[$need_2] != 0) {
                                    $data[$j][$channel]['arppu' . $id_arr[1]] = round($c_row[$need_2] / $c_row[$need_1], 2);

                                } else {
                                    $data[$j][$channel]['arppu' . $id_arr[1]] = 0;

                                }
                                break;
                            case "arpu":
                                $need_1 = "a" . $id_arr[1];
                                $need_2 = "gr" . $id_arr[1];
                                if (isset($c_row[$need_2]) && isset($c_row[$need_1]) && $c_row[$need_1] != 0 && $c_row[$need_2] != 0) {
                                    $data[$j][$channel]['arpu' . $id_arr[1]] = round($c_row[$need_2] / $c_row[$need_1], 2);

                                } else {
                                    $data[$j][$channel]['arpu' . $id_arr[1]][$channel] = 0;


                                }
                                break;
                            case "cvr":
                                $need_1 = "a" . $id_arr[1];
                                $need_2 = "pu" . $id_arr[1];
                                if (isset($c_row[$need_2]) && isset($c_row[$need_1]) && $c_row[$need_1] != 0 && $c_row[$need_2] != 0) {
                                    $data[$j][$channel]['cvr' . $id_arr[1]][$channel] = round(($c_row[$need_2] / $c_row[$need_1]) * 100, 2) . $percent_or_not;

                                } else {
                                    $data[$j][$channel]['cvr' . $id_arr[1]][$channel] = "0" . $percent_or_not;

                                }
                                break;
                            case "cr":
                                $need_1 = "rr" . $id_arr[1];
                                if (isset($c_row[$need_1]) && $c_row[$need_1] != 0) {
                                    $data[$j][$channel]['cr' . $id_arr[1]] = 100 - $c_row[$need_1];


                                } else {
                                    $data[$j][$channel]['cr' . $id_arr[1]] = "0" . $percent_or_not;

                                }
                                break;
                            case "avgtime":
                                $need_1 = "a" . $id_arr[1];
                                $need_2 = "ptime" . $id_arr[1];
                                if (isset($c_row[$need_2]) && isset($c_row[$need_1]) && $c_row[$need_1] != 0 && $c_row[$need_2] != 0) {
                                    $data[$j][$channel]['avgtime' . $id_arr[1]] = round($c_row[$need_2] / $c_row[$need_1], 2);

                                } else {
                                    $data[$j][$channel]['avgtime' . $id_arr[1]] = 0;

                                }
                                break;
                        }
                    }
                }
            }
        }
        return $data;
    }
    public function calculate_package_report_not_in_database($game_code, $data, $must_calc)
    {
        for ($j = 0; $j < count($data); $j++) {
            $package_list = $data[$j]['package'];

            foreach ($package_list as $package) {

                $c_row = $data[$j];
                $b_row = $data[$j - 1];
                $percent_or_not = "";
                for ($i = 0; $i < count($must_calc); $i++) {
                    $id_arr = $this->get_field_and_timming($must_calc[$i]);
                    if ($id_arr != null) {
                        switch ($id_arr[0]) {
                            case "rr":
                                $need = "rr" . $id_arr[1];
                                if (isset($c_row[$need]))
                                    $data[$j][$package]['rr' . $id_arr[1]] = round($c_row[$need], 2) . $percent_or_not;

                                else
                                    $data[$j][$package]['rr' . $id_arr[1]] = "0" . $percent_or_not;;
                                break;
                            case "nrr":
                                $need = "nrr" . $id_arr[1];
                                if (isset($c_row[$need]))
                                    $data[$j][$package]['nrr' . $id_arr[1]] = round($c_row[$need], 2) . $percent_or_not;

                                else
                                    $data[$j][$package]['nrr' . $id_arr[1]]= "0" . $percent_or_not;

                                break;
                            case "arppu":
                                $need_1 = "pu" . $id_arr[1];
                                $need_2 = "gr" . $id_arr[1];
                                if (isset($c_row[$need_2]) && isset($c_row[$need_1]) && $c_row[$need_1] != 0 && $c_row[$need_2] != 0) {
                                    $data[$j][$package]['arppu' . $id_arr[1]] = round($c_row[$need_2] / $c_row[$need_1], 2);

                                } else {
                                    $data[$j][$package]['arppu' . $id_arr[1]] = 0;

                                }
                                break;
                            case "arpu":
                                $need_1 = "a" . $id_arr[1];
                                $need_2 = "gr" . $id_arr[1];
                                if (isset($c_row[$need_2]) && isset($c_row[$need_1]) && $c_row[$need_1] != 0 && $c_row[$need_2] != 0) {
                                    $data[$j][$package]['arpu' . $id_arr[1]] = round($c_row[$need_2] / $c_row[$need_1], 2);

                                } else {
                                    $data[$j][$package]['arpu' . $id_arr[1]][$package] = 0;


                                }
                                break;
                            case "cvr":
                                $need_1 = "a" . $id_arr[1];
                                $need_2 = "pu" . $id_arr[1];
                                if (isset($c_row[$need_2]) && isset($c_row[$need_1]) && $c_row[$need_1] != 0 && $c_row[$need_2] != 0) {
                                    $data[$j][$package]['cvr' . $id_arr[1]][$package] = round(($c_row[$need_2] / $c_row[$need_1]) * 100, 2) . $percent_or_not;

                                } else {
                                    $data[$j][$package]['cvr' . $id_arr[1]][$package] = "0" . $percent_or_not;

                                }
                                break;
                            case "cr":
                                $need_1 = "rr" . $id_arr[1];
                                if (isset($c_row[$need_1]) && $c_row[$need_1] != 0) {
                                    $data[$j][$package]['cr' . $id_arr[1]] = 100 - $c_row[$need_1];


                                } else {
                                    $data[$j][$package]['cr' . $id_arr[1]] = "0" . $percent_or_not;

                                }
                                break;
                            case "avgtime":
                                $need_1 = "a" . $id_arr[1];
                                $need_2 = "ptime" . $id_arr[1];
                                if (isset($c_row[$need_2]) && isset($c_row[$need_1]) && $c_row[$need_1] != 0 && $c_row[$need_2] != 0) {
                                    $data[$j][$package]['avgtime' . $id_arr[1]] = round($c_row[$need_2] / $c_row[$need_1], 2);

                                } else {
                                    $data[$j][$package]['avgtime' . $id_arr[1]] = 0;

                                }
                                break;
                        }
                    }
                }
            }
        }
        return $data;
    }

    public function get_os_list(){
        return array("android", "ios", "other");
    }

    public function calculate_os_json_report_not_in_database($db_data)
    {
        $c_row = $db_data;
        $os_list = $this->get_os_list();
        $need_calc = $this->get_kpi_must_calc();
        $timing_list = $this->get_timming_config();
        $percent_or_not = "";
        foreach ($need_calc as $kpi_code) {
            foreach ($timing_list as $timing) {
                switch ($kpi_code) {
                    case "arppu":
                        $need_1 = "pu" . $timing;
                        $need_2 = "gr" . $timing;
                        if (isset($c_row[$need_2]) && isset($c_row[$need_1])) {
                            $pu_arr = json_decode($c_row[$need_1], true);
                            $gr_arr = json_decode($c_row[$need_2], true);
                            $f_data = array();
                            foreach ($os_list as $os) {
                                if(isset($gr_arr[$os]) && isset($pu_arr[$os]))
                                    $f_data[$os] = round($gr_arr[$os] / $pu_arr[$os], 2);
                            }
                        } else {
                            foreach ($os_list as $os) {
                                $f_data[$os] = 0;
                            }
                        }
                        $c_row['arppu' . $timing] = json_encode($f_data);
                        break;
                    case "arpu":
                        $need_1 = "a" . $timing;
                        $need_2 = "gr" . $timing;
                        if (isset($c_row[$need_2]) && isset($c_row[$need_1])) {
                            $a_arr = json_decode($c_row[$need_1], true);
                            $gr_arr = json_decode($c_row[$need_2], true);
                            $f_data = array();
                            foreach ($os_list as $os) {
                                if(isset($gr_arr[$os]) && isset($a_arr[$os]))
                                    $f_data[$os] = round($gr_arr[$os] / $a_arr[$os], 2);
                            }

                        } else {
                            foreach ($os_list as $os) {
                                $f_data[$os] = 0;
                            }
                        }
                        $c_row['arpu' . $timing] = json_encode($f_data);
                        break;
                    case "cvr":
                        $need_1 = "a" . $timing;
                        $need_2 = "pu" . $timing;
                        if (isset($c_row[$need_2]) && isset($c_row[$need_1])) {
                            $a_arr = json_decode($c_row[$need_1], true);
                            $pu_arr = json_decode($c_row[$need_2], true);
                            $f_data = array();
                            foreach ($os_list as $os) {
                                if(isset($a_arr[$os]) && isset($pu_arr[$os]))
                                    $f_data[$os] = round(($pu_arr[$os] / $a_arr[$os]) * 100, 2) . $percent_or_not;
                            }
                        } else {
                            foreach ($os_list as $os) {
                                $f_data[$os] = 0 . $percent_or_not;
                            }
                        }
                        $c_row['cvr' . $timing] = json_encode($f_data);
                        break;
                    case "cr":
                        $need_1 = "rr" . $timing;
                        if (isset($c_row[$need_1])) {
                            $a_arr = json_decode($c_row[$need_1], true);
                            $f_data = array();
                            foreach ($os_list as $os) {
                                if(isset($a_arr[$os]))
                                    $f_data[$os] = round(100 - $a_arr[$os], 2) . $percent_or_not;
                            }
                        } else {
                            foreach ($os_list as $os) {
                                $f_data[$os] = 0 . $percent_or_not;
                            }
                        }
                        $c_row['cr' . $timing] = json_encode($f_data);
                        break;
                }
            }
        }
        return $c_row;
    }
    public function calculate_os_report_not_in_database($game_code, $data, $must_calc)
    {
        $os_list = $this->get_os_list();
        for ($j = 0; $j < count($data); $j++) {
            foreach ($os_list as $os) {
                $c_row = $data[$j][$os];
                $b_row = $data[$j - 1][$os];
                $percent_or_not = "";
                for ($i = 0; $i < count($must_calc); $i++) {
                    $id_arr = $this->get_field_and_timming($must_calc[$i]);
                    if ($id_arr != null) {
                        switch ($id_arr[0]) {
                            case "rr":
                                $need = "rr" . $id_arr[1];
                                if (isset($c_row[$need]))
                                    $data[$j][$os]['rr' . $id_arr[1]] = round($c_row[$need], 2) . $percent_or_not;
                                else
                                    $data[$j][$os]['rr' . $id_arr[1]] = "0" . $percent_or_not;;
                                break;
                            case "nrr":
                                $need = "nrr" . $id_arr[1];
                                if (isset($c_row[$need]))
                                    $data[$j][$os]['nrr' . $id_arr[1]] = round($c_row[$need], 2) . $percent_or_not;
                                else
                                    $data[$j][$os]['nrr' . $id_arr[1]] = "0" . $percent_or_not;
                                break;
                            case "arppu":
                                $need_1 = "pu" . $id_arr[1];
                                $need_2 = "gr" . $id_arr[1];
                                if (isset($c_row[$need_2]) && isset($c_row[$need_1]) && $c_row[$need_1] != 0 && $c_row[$need_2] != 0) {
                                    $data[$j][$os]['arppu' . $id_arr[1]] = round($c_row[$need_2] / $c_row[$need_1], 2);
                                } else {
                                    $data[$j][$os]['arppu' . $id_arr[1]] = 0;
                                }
                                break;
                            case "arpu":
                                $need_1 = "a" . $id_arr[1];
                                $need_2 = "gr" . $id_arr[1];
                                if (isset($c_row[$need_2]) && isset($c_row[$need_1]) && $c_row[$need_1] != 0 && $c_row[$need_2] != 0) {
                                    $data[$j][$os]['arpu' . $id_arr[1]] = round($c_row[$need_2] / $c_row[$need_1], 2);
                                } else {
                                    $data[$j][$os]['arpu' . $id_arr[1]] = 0;
                                }
                                break;
                            case "cvr":
                                $need_1 = "a" . $id_arr[1];
                                $need_2 = "pu" . $id_arr[1];
                                if (isset($c_row[$need_2]) && isset($c_row[$need_1]) && $c_row[$need_1] != 0 && $c_row[$need_2] != 0) {
                                    $data[$j][$os]['cvr' . $id_arr[1]] = round(($c_row[$need_2] / $c_row[$need_1]) * 100, 2) . $percent_or_not;
                                } else {
                                    $data[$j][$os]['cvr' . $id_arr[1]] = "0" . $percent_or_not;
                                }
                                break;
                            case "cr":
                                $need_1 = "rr" . $id_arr[1];
                                if (isset($c_row[$need_1]) && $c_row[$need_1] != 0) {
                                    $data[$j][$os]['cr' . $id_arr[1]] = 100 - $c_row[$need_1] . $percent_or_not;
                                } else {
                                    $data[$j][$os]['cr' . $id_arr[1]] = "0" . $percent_or_not;
                                }
                                unset($data[$j][$os]['rr' . $id_arr[1]]);
                                break;
                        }
                    }
                }
            }
        }
        return $data;
    }

    public function getKpiSetReport($timing){
        $list=array();
        switch ($timing) {
            default:
            case 4:
                $list = array("a1", "pu1", "gr1");
            case 17:
                $list = array("a1", "pu1", "gr1");
                break;
            case 31:
                $list = array("a1", "pu1", "gr1");
                break;
        }


        return $list;
    }
    public function getDates($start_date, $end_date, $timing){
        $day_arr = $this->getDaysFromTiming($start_date, $end_date, $timing, false);

        $now = date("Y-m-d", time());
        if($timing == "17" || $timing == "31"){
            $yesterday = date("Y-m-d", strtotime($now) - 24*60*60);
            sort($day_arr);
            $max_date = $day_arr[count($day_arr)-1];
            if($max_date > $end_date){
                if($end_date >= $now){
                    $day_arr[count($day_arr)-1] = $yesterday;
                }else{
                    $day_arr[count($day_arr)-1] = $end_date;
                }
            }


        }
        return $day_arr;
    }
    public function getDateFromRange($s, $e)
    {
        for (; ;) {
            $listData2[] = date("Y-m-d", strtotime($s));
            $start = date("Y-m-d", strtotime("+1 day", strtotime($s)));
            $s = $start;
            if (strtotime($start) > strtotime($e)) break;
        }
        return $listData2;
    }

    function isWeekend($date)
    {
        return (date('N', strtotime($date)) > 6);
    }

    function get_months($date1, $date2)
    {

        $time1 = strtotime($date1);
        $time2 = strtotime($date2);

        $my = date('mY', $time2);

        $f = '';

        while ($time1 < $time2) {
            $time1 = strtotime((date('Y-m-d', $time1) . ' +15days'));

            if (date('F', $time1) != $f) {
                $f = date('F', $time1);

                if (date('mY', $time1) != $my && ($time1 < $time2))
                    $months[] = date('Y-m-t', $time1);
            }

        }

        $months[] = date('Y-m-d', $time2);
        return $months;
    }



    private function includeKpi()
    {
        return "";
    }

    private function excludeKpi($kpisDel, $data)
    {
        foreach ($kpisDel as $kpi) {
            $row = array_search($kpi, $data);
            unset($data[$row]);
        }
        return $data;

    }
    function appendSpecialKpi($lstKpi){
        foreach ($lstKpi as $kpi) {
            if (strpos($kpi, '/') == true) {
                $calcKpi = explode('/', $kpi);
                foreach ($calcKpi as $calc) {
                    $lstKpi[] = $calc;
                }
            }
        }
        return $lstKpi;
    }
}
	