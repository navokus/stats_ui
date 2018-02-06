<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class KpiAggregationApi extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
        $this->load->library('user_agent');
        $this->load->helper('url');
        //$this->output->enable_profiler(TRUE); // debug
        $this->load->model('kpiaggregation_model', 'aggregation');
        $this->load->model('device_model', 'device');
        $this->load->library('util');

    }

    public function test(){
    	$this->output->set_content_type('application/json');
    	$fields=array("a1","n1","aw","nw","am","nm");
    	//$dates = array("2016-09-01","2016-09-02");
    	$dates = $this->util->getDaysFromTiming("2016-08-01", "2016-09-03", "daily", false);
        $data= $this->device->get_totalOs("dttk",$fields,$dates);
        $this->output->set_output(json_encode($data));
    }
    
    public function dataSum($indate){
    	
    	$this->output->set_content_type('application/json');
    	
    	
    	$agent="";
    	$me=false;
    	if ($this->agent->is_browser())
    	{
    		$agent = $this->agent->browser().' '.$this->agent->version();
    	}
    	elseif ($this->agent->is_robot())
    	{
    		$agent = $this->agent->robot();
    	}
    	elseif ($this->agent->is_mobile())
    	{
    		$agent = $this->agent->mobile();
    	}
    	else
    	{
    		$agent = $this->agent;
    		$me=true;
    	}
    	$ip = $this->input->ip_address();
    	$curl ="curl";
    	$pos = strpos($agent, $curl);
    	$localip ="::1";
    	$url = current_url();
    	$result = false;
    	if($pos!=0 || $localip!=$ip){
    		$this->output->set_output(json_encode(array($result,$ip,$agent)));
    		return;
    	}
    	 
    	$rs = array();
    	
    	if($indate==null){
    		$indate =date ( 'Y-m-d', strtotime( $indate . '-1 day' ) );
    		//date ( 'Y-m-d', strtotime( $indate . '-1 day' ) );
    	}
    	
    	$yDate1 = $indate;
    	$yDate2 = date ( 'Y-m-d', strtotime( $indate . '-1 day' ) );
    	$kpis = array(16031,16001);
    	$yDates = array($yDate1,$yDate2);
    	foreach ($kpis as $key => $value ){
    		
    		$itResult = array();
    		foreach ($yDates as $keyDate => $valueDate ){
    			
    			$ok = $this->aggregation->aggregateByPlatform($value,$valueDate,"platform");
    			$itResult[$valueDate][] =$ok;
    			$ok = $this->aggregation->aggregateRanking($value,$valueDate);
    			$itResult[$valueDate][] =$ok;
    			$ok = $this->aggregation->aggregateRankingMobile($value,$valueDate);
    			$itResult[$valueDate][] =$ok;
    			$ok = $this->aggregation->aggregateRankingPC($value,$valueDate);
    			$itResult[$valueDate][] =$ok;
    		}
    		
    		$rs[$value][] = $itResult;
    	}
    	
    	
    	$this->output->set_output(json_encode($rs));
    	return;
    }
    
    
    public function collect(){
    	$yesterday = date ( 'Y-m-d', strtotime( date('Y-m-d') . '-1 day' ) );
    	$date = array(
    			"2016-01-31",
    			"2016-02-29",
    			"2016-03-31",
    			"2016-04-30",
    			"2016-05-31",
    			"2016-06-30",
    			"2016-07-31",
    			"2016-08-31",
    			$yesterday
    	);
    	foreach ($date as $key => $value)
    	{
    		$dbArray = $this->aggregation->aggregateRankingMobile(16031,$value);
    		$dbArray = $this->aggregation->aggregateRankingPC(16031,$value);
    	}
    
    	$encode_data = json_encode($dbArray);
    	var_dump($encode_data);
    	die;
    }
}

