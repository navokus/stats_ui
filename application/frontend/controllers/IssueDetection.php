<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by IntelliJ IDEA.
 * User: canhtq
 * Date: 03/07/2017
 * Time: 09:39
 */
class IssueDetection extends CI_Controller
{
    private $thresholdRevenueLevel1=10;
    private $thresholdRevenueLevel2=5;
    private $thresholdDoDLevel1=80;
    private $thresholdDoDLevel2=20;
    private $missLogFlag=false;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
        $this->load->library('util');
        $this->load->model('game_model', 'game');
        $this->load->model('user_model', 'user');
        $this->load->model('gamekpi_model', 'gamekpi');
        $this->load->model('qaissuedgame_model', 'qaissuedgame');
        $this->load->model('qcgamekpi_model', 'qcgamekpi');
        $this->load->library('user_agent');
        $this->load->library('kpiconfig');
        $this->load->helper('url');
    }

    private function isMissing($data){
        $rs["result"]=false;
        $rs["message"]="";
        foreach ($data as $kpi_id=>$kpi_value) {
            $nval = floatval($kpi_value);
            if($nval==0){
                $rs["result"]=true;
                $rs["message"]=$kpi_id. " Missing ";
                $rs["kpi_id"]=$kpi_id;
                $rs["type"]="missing";
                return $rs;
            }
        }
        return $rs;
    }
    private function passCrossCheck($data,$xdata,$kpi_cross_check){
        $rs["result"]=true;
        $rs["message"]="";
        foreach ($data as $kpi_id=>$kpi_value) {
            if($kpi_id==$kpi_cross_check){
                $xvalue = $xdata[$kpi_id];
                $nval = floatval($kpi_value);
                $xval = floatval($xvalue);
                if($xval>0) {
                    $strData = "kpitool(".number_format($nval). ")";
                    $strX = "vs.(".number_format($xval). ")";
                    $desc = "Detail: ".$strData . " " . $strX;
                    $dp = (($nval - $xval) / $xval) * 100;
                    if (abs($dp) > 10) {
                        $rs["result"]=false;
                        $rs["message"]=$kpi_cross_check ." % Diff cross-check: ".number_format($dp,2) .". " .$desc;
                        $rs["kpi_id"]=$kpi_id;
                        $rs["type"]="xcheck";
                        return $rs;
                    }
                }
            }

        }
        return $rs;
    }



    private function passDoD($data,$kpi_dod){
        $rs["result"]=true;
        $rs["message"]="";
        $rs["type"]="";
        $now = date("Y-m-d", time());
        $yesterday = date("Y-m-d", strtotime($now) - 24 * 60 * 60);
        $yesterday_1 = date("Y-m-d", strtotime($now) - 2*24 * 60 * 60);
        $dataYesterday_1 = $data[$yesterday_1];
        $dataYesterday=$data[$yesterday];
        foreach ($dataYesterday_1 as $kpi_id=>$kpi_value) {
            if($kpi_id==$kpi_dod){
                $nval1 = floatval($kpi_value);
                $nval2 = floatval($dataYesterday[$kpi_id]);
                $dp = (($nval2 - $nval1) / $nval1) * 100;
                $strYes = $yesterday."(".number_format($nval2). ")";
                $strYes_1 = $yesterday_1."(".number_format($nval1). ")";
                $desc = "Detail: ".$strYes_1 . " " . $strYes;
                if($kpi_dod=="16001" || $kpi_dod =="52001"){

                    $v_thresh =50*1000000;//50 mil
                    if(abs($nval2-$nval1)>$v_thresh){
                        $rs["result"]=false;
                        $rs["message"]=$kpi_dod. " Value Incr: ".number_format($nval2-$nval1) .". ".$desc ;
                        $rs["kpi_id"]=$kpi_id;
                        $rs["type"]="dod";
                        return $rs;
                    }
                    $p_thresh=200;
                    if(abs($dp)>$p_thresh){
                        $rs["result"]=false;
                        $rs["message"]=$kpi_dod. " % Incr: ".number_format($dp) ."%. " . $desc ;
                        $rs["kpi_id"]=$kpi_id;
                        $rs["type"]="dod";
                        return $rs;
                    }
                }else{
                    $p_thresh=200;
                    if(abs($dp)>$p_thresh){
                        $rs["result"]=false;
                        $rs["message"]=$kpi_dod. " % Incr: ".number_format($dp) ."%. " . $desc ;
                        $rs["kpi_id"]=$kpi_id;
                        $rs["type"]="dod";
                        return $rs;
                    }
                }
            }
        }
        return $rs;
    }
    public function getGamesByRangeDates($fromDate, $toDate, $games)
    {
        $rs = array();
        foreach ($games as $gameCode) {

            $dataGame = $this->gamekpi->getByRangeDates($fromDate,$toDate,$gameCode,$this->kpiconfig->get_qa_kpi_daily($gameCode));
            $rs[$gameCode]=$dataGame;
        }
        return $rs;
    }
    public function detect(){
        $now = date("Y-m-d", time());
        $yesterday = date("Y-m-d", strtotime($now) - 24 * 60 * 60);
        $yesterday_1 = date("Y-m-d", strtotime($now) - 2*24 * 60 * 60);
        $games = $this->game->getQaAlls();
        $slovedGames = $this->qaissuedgame->getSolvedGames($yesterday);
        $oldIssuedGames= $this->qaissuedgame->getIssuedGames($yesterday);
        $solveds = array();

        foreach ($slovedGames as $game) {
            $solveds[]= $game["game_code"];

        }
        $notSolveds = array();
        foreach ($oldIssuedGames as $game) {
            $notSolveds[]= $game["game_code"];
        }

        $gameArray =array();
        $gameIndex = array();
        foreach ($games as $game) {
            if (!in_array($game["GameCode"], $solveds)) {
                $gameArray[]= $game["GameCode"];
            }
            $gameIndex[$game["GameCode"]]["game_code"]=$game["GameCode"];
            $gameIndex[$game["GameCode"]]["game_type"]=$game["GameType2"];
            $gameIndex[$game["GameCode"]]["send_mail"]=$game["SendMail"];
            $gameIndex[$game["GameCode"]]["status"]=$game["Status"];
        }
        //var_dump($gameArray);
        $srcData = $this->getGamesByRangeDates($yesterday_1,$yesterday,$gameArray);
        $xData = $this->qcgamekpi->getGamesData($yesterday);
        $rs = array();
        $rs["issue"]=array();
        $rs["kpi_miss"]=array();
        $rs["x_revenue"]=array();
        $rs["dod_revenue"]=array();
        $rs["src"]=$srcData;
        $rs["x"]=$xData;
        //$rs["revenue_warning"]=array();
        foreach ($srcData as $game=>$value) {
            $check = $this->isMissing($value[$yesterday]);
            if($check["result"]){
                $rs["kpi_miss"][]=$game;
                if(!isset($rs["issue"][$game])){
                    $rs["issue"][$game]=$check;
                }
            }
        }
        foreach ($srcData as $game=>$value) {
            if(!isset($rs["issue"][$game])){
                $check=$this->passCrossCheck($value[$yesterday], $xData[$game],"16001");
                if(!$check["result"]){
                    $rs["x_revenue"][]=$game;
                    if(!isset($rs["issue"][$game])){
                        $rs["issue"][$game]=$check;
                    }
                }
            }
        }
        foreach ($srcData as $game=>$value) {
            if(!isset($rs["issue"][$game])){
                $check=$this->passDoD($value,"16001");
                if(!$check["result"]){
                    $rs["dod_revenue"][]=$game;
                    if(!isset($rs["issue"][$game])){
                        $rs["issue"][$game]=$check;
                    }
                }
            }
        }
        $gross_kpi_id="52001";
        foreach ($srcData as $game=>$value) {
            if(!isset($rs["issue"][$game])){
                $check=$this->passCrossCheck($value[$yesterday], $xData[$game],$gross_kpi_id);
                if(!$check["result"]){
                    $rs["x_revenue"][]=$game;
                    if(!isset($rs["issue"][$game])){
                        $rs["issue"][$game]=$check;
                    }
                }
            }
        }
        foreach ($srcData as $game=>$value) {
            if(!isset($rs["issue"][$game])){
                $check=$this->passDoD($value,$gross_kpi_id);
                if(!$check["result"]){
                    $rs["dod_revenue"][]=$game;
                    if(!isset($rs["issue"][$game])){
                        $rs["issue"][$game]=$check;
                    }
                }
            }
        }
        $issued_games =array();
        $last =date("Y-m-d H:i:s", time());
        foreach ($rs["issue"] as $key=>$issue) {
                $issued["game_code"] = $key;
                $issued["report_date"] = $yesterday;
                $issued["message"] = $issue["message"];
                $issued["update_time"] = $last;
                if($issue["type"]!="dod"){
                    $issued_games[] = $issued;
                }
        }


        if(count($issued_games)>0){
            $this->qaissuedgame->addIssues($issued_games);
        }
        $this->qaissuedgame->clean($yesterday,$last);

        $issueTable = $this->issuesTable($rs["issue"]);
        $misslogTable ="";
        $issues = $this->qaissuedgame->getIssues($yesterday);
        $data= json_decode($issues["issues"],true);
        $this->missLogFlag=false;
        if(count($data["games"])>0){
            //co issues
            $nowHH = date("H", time());
            if($nowHH>"08"){
                $misslogTable= $this->logMissingTable($data["games"],$data["hdfs"]);
            }
        }
        if($this->missLogFlag){
            $emails_test = array("canhtq@vng.com.vn");
            $emails = array("canhtq@vng.com.vn","duclt@vng.com.vn","vunv@vng.com.vn","quangctn@vng.com.vn","lamnt6@vng.com.vn");
            $mail_config = array(
                "from" => "kpi-detection@vng.com.vn",
                "fromalias" => "GameKPI Alert",
                "to" => $emails,
                "subject" => "MissLog " . $yesterday . ". Check time:" . date("Y-m-d H:i:s", time()),
                "message" => $this->makeHtml(array($misslogTable))
            );
            $result = $this->util->send_mail($mail_config);
        }
        if(count($rs["issue"])>0) {
            $emails_test = array("canhtq@vng.com.vn");
            $emails = array("canhtq@vng.com.vn","quangctn@vng.com.vn","lamnt6@vng.com.vn");
            $mail_config = array(
                "from" => "kpi-detection@vng.com.vn",
                "fromalias" => "GameKPI Alert",
                "to" => $emails,
                "subject" => "Issues " . $yesterday . ". Check time:" . date("Y-m-d H:i:s", time()),
                "message" => $this->makeHtml(array($issueTable, $misslogTable))
            );
            $result = $this->util->send_mail($mail_config);
        }
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    private function makeHtml($tables){

        $now = date("Y-m-d", time());
        $yesterday = date("Y-m-d", strtotime($now) - 24 * 60 * 60);
        $html = "";
        $html .= "<!DOCTYPE html>
        <html>
        <head>
        <style>
      /* -------------------------------------
          GLOBAL RESETS
      ------------------------------------- */
      img {
        border: none;
        -ms-interpolation-mode: bicubic;
        max-width: 100%; }

      body {
        background-color: #f6f6f6;
        font-family: sans-serif;
        -webkit-font-smoothing: antialiased;
        font-size: 14px;
        line-height: 1.4;
        margin: 0;
        padding: 0; 
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%; }

      table {
        border-collapse: separate;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%; }
        table td {
          font-family: sans-serif;
          font-size: 14px;
          vertical-align: top; }

      /* -------------------------------------
          BODY & CONTAINER
      ------------------------------------- */

      .body {
        background-color: #f6f6f6;
        width: 100%; }

      /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
      .container {
        display: block;
        Margin: 0 auto !important;
        /* makes it centered */
        max-width: 580px;
        padding: 10px;
        width: 580px; }

      /* This should also be a block element, so that it will fill 100% of the .container */
      .content {
        box-sizing: border-box;
        display: block;
        Margin: 0 auto;
        max-width: 580px;
        padding: 10px; }

      /* -------------------------------------
          HEADER, FOOTER, MAIN
      ------------------------------------- */
      .main {
        background: #fff;
        border-radius: 3px;
        width: 100%; }

      .wrapper {
        box-sizing: border-box;
        padding: 20px; }

      .footer {
        clear: both;
        padding-top: 10px;
        text-align: center;
        width: 100%; }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
          color: #999999;
          font-size: 12px;
          text-align: center; }

      /* -------------------------------------
          TYPOGRAPHY
      ------------------------------------- */
      h1,
      h2,
      h3,
      h4 {
        color: #000000;
        font-family: sans-serif;
        font-weight: 400;
        line-height: 1.4;
        margin: 0;
        Margin-bottom: 30px; }

      h1 {
        font-size: 35px;
        font-weight: 300;
        text-align: center;
        text-transform: capitalize; }

      p,
      ul,
      ol {
        font-family: sans-serif;
        font-size: 14px;
        font-weight: normal;
        margin: 0;
        Margin-bottom: 15px; }
        p li,
        ul li,
        ol li {
          list-style-position: inside;
          margin-left: 5px; }

      a {
        color: #3498db;
        text-decoration: underline; }


      /* -------------------------------------
          OTHER STYLES THAT MIGHT BE USEFUL
      ------------------------------------- */
      .last {
        margin-bottom: 0; }

      .first {
        margin-top: 0; }

      .align-center {
        text-align: center; }

      .align-right {
        text-align: right; }

      .align-left {
        text-align: left; }

      .clear {
        clear: both; }

      .mt0 {
        margin-top: 0; }

      .mb0 {
        margin-bottom: 0; }

      .preheader {
        color: transparent;
        display: none;
        height: 0;
        max-height: 0;
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        mso-hide: all;
        visibility: hidden;
        width: 0; }

      .powered-by a {
        text-decoration: none; }

      hr {
        border: 0;
        border-bottom: 1px solid #f6f6f6;
        Margin: 20px 0; }

      /* -------------------------------------
          RESPONSIVE AND MOBILE FRIENDLY STYLES
      ------------------------------------- */
      @media only screen and (max-width: 620px) {
        table[class=body] h1 {
          font-size: 28px !important;
          margin-bottom: 10px !important; }
        table[class=body] p,
        table[class=body] ul,
        table[class=body] ol,
        table[class=body] td,
        table[class=body] span,
        table[class=body] a {
          font-size: 16px !important; }
        table[class=body] .wrapper,
        table[class=body] .article {
          padding: 10px !important; }
        table[class=body] .content {
          padding: 0 !important; }
        table[class=body] .container {
          padding: 0 !important;
          width: 100% !important; }
        table[class=body] .main {
          border-left-width: 0 !important;
          border-radius: 0 !important;
          border-right-width: 0 !important; }
        table[class=body] .btn table {
          width: 100% !important; }
        table[class=body] .btn a {
          width: 100% !important; }
        table[class=body] .img-responsive {
          height: auto !important;
          max-width: 100% !important;
          width: auto !important; }}

      /* -------------------------------------
          PRESERVE THESE STYLES IN THE HEAD
      ------------------------------------- */
      @media all {
        .ExternalClass {
          width: 100%; }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
          line-height: 100%; }
        .apple-link a {
          color: inherit !important;
          font-family: inherit !important;
          font-size: inherit !important;
          font-weight: inherit !important;
          line-height: inherit !important;
          text-decoration: none !important; } 
        .btn-primary table td:hover {
          background-color: #34495e !important; }
        .btn-primary a:hover {
          background-color: #34495e !important;
          border-color: #34495e !important; } }

    </style>
        </head>
        <body>";
        $html .= "<p>Hi!,";
        $html .= "</br>";
        $html .= "</br>";
        $html .= "Pls check your systems. ";
        $html .= "</br>";
        $html .= '</p>';
        $html .= "<p>Data Date:" .$yesterday;
        $html .= '</p>';
        $html .= "</br>";
        $html .= '</p>';
        foreach ($tables as $table){
            $html .= $table;
            $html .= "<p></br></p>";
        }

        $html .= "</body></html>";
        return $html;
    }
    private function getTd($color,$value){
        $html = "<td bgcolor=\"";
        $html .= $color;
        $html .= "\">";
        $html .= $value;
        $html .= "</td>";
        return $html;
    }

    private function issuesTable($issues){

        $now = date("Y-m-d", time());
        $yesterday = date("Y-m-d", strtotime($now) - 24 * 60 * 60);
        $html = "";
        $html .= "<p><br></p>";
        $html .= "<table style=\"border: 1px solid #cccccc;\" align=\"left\"  cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
        $html .= "<tr bgcolor=\"#70bbd9\"><td colspan=\"3\">Overview</td></tr>";
        $html .= "<tr bgcolor=\"#70bbd9\"><td>GameCode</td><td>Issue</td><td>Resolve</td></tr>";
        $alls ="";
        foreach ($issues as $key=>$issue) {
            $color = "#669999";
            $html .= "<tr>";
            $html .= $this->getTd($color,$key);
            $html .= $this->getTd($color,$issue["message"]);

            //$strSolve ="";
            //if($issue["type"]!="missing"){
                $strSolve ="<a href='https://kpi.stats.vng.com.vn/index.php/IssueResolved/solved/".$key."/".$yesterday."'>Solve</a>";
                $alls .= $key.".";
            //}
            $html .= $this->getTd($color,$strSolve);
            $html .= "</tr>";
        }
        $alls .= "#";
        $alls = str_replace(".#","",$alls);
        $html .= "<tr>";
        $html .= $this->getTd($color,".");
        $html .= $this->getTd($color,".");
        $html .= $this->getTd($color,"<a href='https://kpi.stats.vng.com.vn/index.php/IssueResolved/solved/".$alls."/".$yesterday."'>==>Solve Alls</a>");
        $html .= "</tr>";
        $html .= "</table>";
        return $html;
    }

    public function logMissingTable($games,$hdfs)
    {
        $html = "";
        $html .= "<p><br></p>";
        $waitingLog=false;
        $html .= "<table style=\"border: 1px solid #cccccc;\" align=\"left\"  cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
        $htmlGames = "ISSUED GAMES: ";
        foreach ($games as $game) {
            $htmlGames .= $game .",";
        }
        $html .= "<tr bgcolor=\"#70bbd9\"><td colspan=\"5\">Missing LogFiles</td></tr>";
        $html .= "<tr bgcolor=\"#70bbd9\"><td>GameCode</td><td>Path</td><td>Size</td><td>Is Exists</td><td>Status</td></tr>";
        foreach ($hdfs as $game => $files) {
            $color = "#669999";
            foreach ($files as $file) {
                if(!$file["exists"]) {
                    $this->missLogFlag=true;
                    $html .= "<tr>";
                    $html .= $this->getTd($color,$game);
                    $path =str_replace("u003d", "=", $file["filePath"]);
                    $html .= $this->getTd($color,$path);
                    $html .= $this->getTd($color,$file["fileSize"]);
                    $html .= $this->getTd($color,$file["exists"] == true ? "true" : "false");
                    $html .= $this->getTd($color,$file["exists"] == true ? "running report..." : "waiting log");
                    $html .= "</tr>";
                    if(!$waitingLog){
                        $waitingLog=true;
                    }
                }
            }
        }
        $html .= "</table>";
        if(!$waitingLog){
            $html = "";
        }
        return $html;
    }

}