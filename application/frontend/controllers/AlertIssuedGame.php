<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class AlertIssuedGame extends CI_Controller
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
        $this->load->model('qaissuedgame_model', 'qaissuedgame');
    }

    public function alert(){
        $now = date("Y-m-d", time());
        $yesterday = date("Y-m-d", strtotime($now) - 24*60*60);
        $issues = $this->qaissuedgame->getIssues($yesterday);
        $n = count($issues);
        if($n<=0){
            return;
        }
        $length = strlen($issues["issues"]);
        if($length<=1){
           return;
        }
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
    	$html .= $issues["issues"];

    	$html .= "</body></html>";
    	//var_dump($html);
    	
    	$emails_test = array("canhtq@vng.com.vn");
    	$emails = array("canhtq@vng.com.vn","yentn2@vng.com.vn","quangctn@vng.com.vn","lamnt6@vng.com.vn");
    	
    	$mail_config = array(
    			"from"=>"kpi-stats@vng.com.vn",
    			"fromalias"=>"Game Issued Detection",
    			"to"=>$emails,
    			"subject"=>"[kpi.zing.vn] Game Issued Detection (".$yesterday .")",
    			"message"=> $html
    	);
    	$result = $this->util->send_mail($mail_config);
    	
    	$this->output->set_output(json_encode($issues));
    	return;
    }
    
}

