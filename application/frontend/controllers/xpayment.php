<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Xpayment extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
        $this->load->library('user_agent');
        $this->load->helper('url');
        //$this->output->enable_profiler(TRUE); // debug
        $this->load->model('xpayment_model', 'xpayment');
        $this->load->library('util');

    }

    public function xemail($yyyyMMddNow){
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
    	$localip ="0.0.0.0";
    	$url = current_url();
    	$result = false;
    	if($pos!=0 || $localip!=$ip){
    		$this->output->set_output(json_encode(array($result,$ip,$agent)));
    		return;
    	}
    	if($this->util->IsNullOrEmptyString($yyyyMMddNow)){
    		$now = date("Y-m-d", time());
    	}else{
    		$now = $yyyyMMddNow;//date("Y-m-d", time());
    	}
    	    	
    	$yesterday = date("Ymd00", strtotime($now) - 24*60*60);
    	
    	$yub = date("Y-m-d", strtotime($now) - 24*60*60);
    	
    	//var_dump($yesterday);
    	$rzdb = $this->xpayment->getZdb($yesterday);
    	
    	$apps = $this->xpayment->getApps();
    	//var_dump($apps);
    	$zdb = array();
    	$c_apps = count($apps);
    	$c_rzdb = count($rzdb);
    	$vlcm_apps = array(10157,10161,10170,10090);
    	$c_vlcmapps = count($vlcm_apps);
    	$vlcm_xc = -10090;
    	$vlcm_revenue=0;
    	for ($j = 0; $j < $c_rzdb; $j++) {
    		$appid = $rzdb[$j]["app_id"];
    		for ($c = 0; $c < $c_vlcmapps; $c++) {
    			if($vlcm_apps[$c] == $appid){
    				$vlcm_revenue = $vlcm_revenue + $rzdb[$j]["revenue"];
    				break;
    			}	
    		}
    	}
    	//$rzdb[] = array("app_id"=>$vlcm_xc, "game_code"=>"vlcm","revenue"=>$vlcm_revenue);
    	array_push($rzdb,array("app_id"=>strval($vlcm_xc),"revenue"=>strval($vlcm_revenue)));
    	$c_rzdb = count($rzdb);
    	//var_dump($rzdb);
    	$idx =0;
    	for ($i = 0; $i < $c_apps; $i++) {
    		$app1 = $apps[$i]["app_id"];
    		for ($j = 0; $j < $c_rzdb; $j++) {
    			$app2= $rzdb[$j]["app_id"];
    			if($app1==$app2){
    				$zdb[$idx]["app_id"] = $apps[$i]["app_id"];
    				$zdb[$idx]["revenue"] = $rzdb[$j]["revenue"];
    				$zdb[$idx]["game_code"] = $apps[$i]["game_code"];
    				$idx = $idx+1;
    				break;
    			}
    		}
    	}
    	//var_dump($zdb);
    	$udb = $this->xpayment->getUbdb($yub);
    	//var_dump($udb);
    	
    	$c_zdb = count($zdb);
    	//var_dump($c_zdb);
    	$c_udb = count($udb);
    	$data = array();
    	$html ="";
    	$html .= "<!DOCTYPE html>
<html>
<head>
<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
    width: auto;
}
</style>
</head>
<body>";
    	$html .= "<table style=\"border: 1px solid #cccccc;\" align=\"left\"  cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
    	$html .= "<tr bgcolor=\"#70bbd9\"><td colspan=\"7\">Compare Revenue Daily (RV1), Date:" .$yub ."</td></tr>";
    	$html .= "<tr bgcolor=\"#70bbd9\"><td>#</td><td>GameCode</td><td>RV1(kpi.zing.vn)</td><td>SourceLog</td><td>PurchasingPower(PMT)</td><td>D(%)</td><td>D(VND)</td></tr>";
    	for ($k = 0; $k < $c_zdb; $k++) {
    		$z_code =  $zdb[$k]["game_code"];
    		
    		for ($u = 0; $u < $c_udb; $u++) {
    			$u_code = $udb[$u]["game_code"];
    			
    			//var_dump($z_code ."  ". $u_code);
    			if($u_code==$z_code){
    				$data[$k]["game_code"] = $z_code;
    				$data[$k]["u_value"] =$udb[$u]["kpi_value"];
    				$data[$k]["source"] =$udb[$u]["source"];
    				$data[$k]["p_value"] =$zdb[$k]["revenue"];
    				$data[$k]["diff"] =round((1-($data[$k]["u_value"]/$data[$k]["p_value"]))*100,2);
    				$data[$k]["diff_abs"] = abs($data[$k]["diff"]);
    				$data[$k]["diff_value"] =$data[$k]["p_value"]-$data[$k]["u_value"];
    				$color = "#00ff80";
    				if($data[$k]["diff_abs"]<0){
    					$color="#FF0000";
    				}else if($data[$k]["diff_abs"]<3){
    					$color="#00ff80";
    				}else if($data[$k]["diff_abs"]<7){
    					$color="#FFFF00";
    				}else{
    					$color="#FF0000";
    				}
    				$html .= "<tr bgcolor=\"";
    				$html .= $color;
    				$html .= "\">";
    				$html .= "<td>" .($k+1). "</td>";
    				$html .= "<td>";
    				$html .= strtoupper($data[$k]["game_code"]);
    				$html .= "</td><td align=\"right\">";
    				$html .= number_format($data[$k]["u_value"]);
    				$html .= "</td><td align=\"right\">";
    				$html .= $data[$k]["source"];
    				$html .= "</td><td align=\"right\">";
    				$html .= number_format($data[$k]["p_value"]);
    				$html .= "</td><td align=\"right\" bgcolor=\"";
    				$html .= $color;
    				$html .= "\">";
    				$html .= $data[$k]["diff"] ." %";
    				$html .= "</td><td align=\"right\" bgcolor=\"";
    				$html .= $color;
    				$html .= "\">";
    				$html .= number_format($data[$k]["diff_value"]);
    				$html .= "</td>";
    				
    				$html .= "</tr>";
    				
    				break;
    			}
    		}
    	}
    	
    	$html .= "</table>";
    	/*
    	$html .= "<br><br><br><br>";
    	$html .= "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100\">";
    	$html .= "<tr><td> 0 < |D(%)| < 3</td><td bgcolor=\"#00ff80\">normal</td></tr>";
    	$html .= "<tr><td>3 <= |D(%)| < 7</td><td bgcolor=\"#FFFF00\">warning</td></tr>";
    	$html .= "<tr><td>|D(%)| >= 7    </td><td bgcolor=\"#FF0000\">error</td></tr>";
    	$html .= "</table>";
    	
    	*/
    	$html .= "</body></html>";
    	//var_dump($html);
    	
    	$emails_test = array("canhtq@vng.com.vn");
    	$emails = array("canhtq@vng.com.vn","lamnt6@vng.com.vn","quangctn@vng.com.vn","xuyenlt@vng.com.vn","vunv@vng.com.vn");
    	
    	$mail_config = array(
    			"from"=>"kpi-stats@vng.com.vn",
    			"fromalias"=>"KPI cross-check revenue",
    			"to"=>$emails,
    			"subject"=>"[kpi.zing.vn] Cross-check revenue daily (".$yub .")",
    			"message"=> $html
    	);
    	$result = $this->util->send_mail($mail_config);
    	
    	$this->output->set_output(json_encode(array($result,$ip)));
    	return;
    }
    
}

