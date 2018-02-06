<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by IntelliJ IDEA.
 * User: quangctn
 * Date: 14/08/17
 */
class EmailReport2 extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //$this->output->enable_profiler(TRUE);
        $this->load->library('email');
        $this->load->library('util');
        $this->load->model('mail_model', 'mail');
        $this->load->library('form_validation');
        $this->load->library('util');
    }


    public function stats_mail()
    {
        $doneFlg = false;
        //Daily Mail
        /*$currentDate = date('Y-m-d');*/
        /*$currentDate = '2018-01-15';*/
        $currentDate = '2018-02-01';

        /*$chkSend = $this->mail->check_send_mail(date('Y-m-d', strtotime('-1 day' . $currentDate)), 'daily');
        if ($chkSend) {
            $mailFullCfg = $this->mail("1", $currentDate);
            $doneFlg = $this->util->send_mail($mailFullCfg);
            $this->mail->insert_log($mailFullCfg['to'], $mailFullCfg['message'], date('Y-m-d', strtotime('-1 day' . $currentDate)), 'daily', $this->getNameDoneFlag($doneFlg));
        }

        //Check weekly
        $chkSendWeekly = $this->mail->check_send_mail(date('Y-m-d', strtotime('-1 day' . $currentDate)), 'weekly');
        if ($chkSendWeekly) {
            if ($this->checkWeekly($currentDate)) {
                $mailFullCfg = $this->mail("7", $currentDate);
                $doneFlg = $this->util->send_mail($mailFullCfg);
                $this->mail->insert_log($mailFullCfg['to'], $mailFullCfg['message'], date('Y-m-d', strtotime('-1 day' . $currentDate)), 'weekly', $this->getNameDoneFlag($doneFlg));
            }
        }*/
        $chkSendMonthly = $this->mail->check_send_mail(date('Y-m-d', strtotime('-1 day' . $currentDate)), 'monthly');
        if ($chkSendMonthly) {
            if ($this->checkMonthly($currentDate)) {
                $mailFullCfg = $this->mail("31", $currentDate);
                $doneFlg = $this->util->send_mail($mailFullCfg);
                $this->mail->insert_log($mailFullCfg['to'], $mailFullCfg['message'], date('Y-m-d', strtotime('-1 day' . $currentDate)), 'monthly', $this->getNameDoneFlag($doneFlg));
            }
        }
    }

    private function getNameDoneFlag($doneFlg)
    {
        if ($doneFlg) {
            $flag = "_success";
        } else {
            $flag = "fail";
        }
        return $flag;
    }

    /*
     * status = 0 : operator
     * status = 1 : accountant
     * */
    private function getConfigMail($status, $subject, $html, $attach,$timing)
    {
        $mail_config = array();
        $to_date = date('Y-m-d');
        $system_email_address = "kpi.stats@vng.com.vn";
        $groupFA = 1;
        /*$system_email_address = "quangctn@vng.com.vn";*/
        $lstOperation = array("lamnt6", "quangctn","canhtq");
        if ($status == 0) {
            $lstUserName = $lstOperation;
        } else {

            $lstUserName = $this->mail->getLstUserSendMail($groupFA);
            $lstUserName = array_merge($lstUserName,$lstOperation);
            /*if(strcmp($timing,"7")==0 || strcmp($timing,"31")==0){
                $lstUserName = array_merge($lstUserName,array("loanhm"));
            }*/
        }
        $user_name = "";
        for ($i = 0; $i < count($lstUserName); $i++) {
            $user_name .= $lstUserName[$i] . "@vng.com.vn,";
        }
        $user_name = substr($user_name, 0, strlen($user_name) - 1);
        $kpi_report_alias = "KPI Report Tool";
        $mail_config = array(
            "from" => $system_email_address,
            "fromalias" => $kpi_report_alias,
            "to" => $user_name,
            "subject" => $subject,
            "message" => $html
        );
        if ($attach != null) {
            $mail_config['attach'] = array($attach);
        }
        return $mail_config;
    }

    public function appendHtmtCfgMail($mailCfg, $html, $attach)
    {
        $mailCfg['message'] = $html;
        if ($attach != null) {
            $mailCfg['attach'] = array($attach);
        }
        return $mailCfg;
    }

    /*1. Determind people and list game that they can see
      2. Get value of kpi for each game
        Each game have 2 config to get kpi
            + config about amount of kpi show on mail
            + config about amount of kpi in attachment file

    */

    public function getKpiRequire($timing)
    {
        switch ($timing) {
            case "7":
                $kpiRequire = array(
                    '11017' => 'Weekly New User',
                    '10017' => 'Weekly Active User',
                    '28017' => 'Weekly RR(%)',
                    "15017" => "Weekly Paying User",
                    "52017" => "Weekly Gross Revenue ");
                return $kpiRequire;

            case "31":
                $kpiRequire = array(
                    '11031' => 'Monthly New User',
                    '10031' => 'Monthly Active User',
                    '28031' => 'Monthly RR(%)',
                    "15031" => "Monthly Paying User",
                    "52031" => "Monthly Gross Revenue ");
                return $kpiRequire;
            case "1" :
                $kpiRequire = array(
                    "11001" => "N1",
                    "10001" => "A1",
                    "28001" => "RR1",
                    "15001" => "PU1",
                    "52001" => "Daily Gross Revenue ");
                return $kpiRequire;

        }
        return array();
    }

    public function mail($timing, $currentDate)
    {

        $data = array();
        $html = "";
        $fullCfg = "";
        $kpiRequire = $this->getKpiRequire($timing);
        if (strcmp($timing, "1") == 0) {
            $yesterday = date('Y-m-d', strtotime('-1 day ' . $currentDate));
            $twoDayAgo = date('Y-m-d', strtotime('-2 day ' . $currentDate));
            $lstDate = array($yesterday, $twoDayAgo);
            $subject = "KPI Mobile Daily Report - $currentDate";
        } else if (strcmp($timing, "7") == 0) {
            $lastWeek = date('Y-m-d', strtotime('-1 day ' . $currentDate));
            $twoWeekAgo = date('Y-m-d', strtotime('-8 day ' . $currentDate));
            $lstDate = array($lastWeek, $twoWeekAgo);
            $subject = "KPI Mobile Weekly Report - $currentDate";
        } else if (strcmp($timing, "31") == 0) {
            $lastMonth = date('Y-m-d', strtotime('-1 day ' . $currentDate));
            $twoMonthAgo = date('Y-m-d', strtotime('-1day -1 month ' . $currentDate));
            $lstDate = array($lastMonth, $twoMonthAgo);
            $subject = "KPI Mobile Monthly Report - $currentDate";
            /*array(2) { [0]=> string(10) "2018-02-28" [1]=> string(10) "2018-01-31" }*/
        }
        $kpiSum = "52000" + $timing;
        $data['sum'] = $this->mail->getSumKpi($kpiSum, $lstDate);
        $data['attact'] = $this->mail->getKpiFromMobileGame(array_keys($kpiRequire), $lstDate);
        $data['attachNotGE'] = $this->mail->getKpiFromMobileGameNotGE(array_keys($kpiRequire), $lstDate);
        $data['detailMobile'] = $data['attact'];
        $status = $this->checkFullData($data['attact'], date('Y-m-d', strtotime('-1 day' . $currentDate)));
        if ($status) {
            $html = $this->makeHtml($data, $lstDate, $timing);
            /*var_dump($html);exit();*/
            if($data['attachNotGE'] != null){
            $attach = $this->attachMail($data['attact'],$data['attachNotGE'], $data['sum'], $lstDate, $timing);
            }else{
                $attach = $this->attachMail($data['attact'],null, $data['sum'], $lstDate, $timing);
            }
            if (!empty($html)) {
                $fullCfg = $this->getConfigMail(1, $subject, $html, $attach,$timing);
            }
        } else {
            $html = $this->makeMissingHtmltoOperator($data['attact'], date('Y-m-d', strtotime('-1 day' . $currentDate)));
            /*var_dump($html);exit();*/
            $fullCfg = $this->getConfigMail(0, $subject, $html, null,$timing);
        }


        return $fullCfg;

    }

    private function diffGame($data, $reportDate)
    {

        $lstGame = (array_keys($data));
        $lstIssueGame = $this->mail->getIssueGame($reportDate);
        $issueGames = array();
        foreach ($lstIssueGame as $key => $dataIssue) {
            if (in_array($dataIssue['game_code'], $lstGame)) {
                $issueGames['game_code'][] = $dataIssue['game_code'];
                $issueGames['message'][] = $dataIssue['message'];
            }
        }
        return $issueGames;
    }

    /*
    * True:  full data can begin send mail
    * False: still not full, send mail to operator
    */
    private function checkFullData($data, $reportDate)
    {
        $lstDiffGame = $this->diffGame($data, $reportDate);
        if (count($lstDiffGame) == 0 or $lstDiffGame == null) {
            return true;
        }
        return false;
    }

    public function makeMissingHtmltoOperator($data, $reportDate)
    {
        $html = "";
        $lstDiffGame = $this->diffGame($data, $reportDate);
        $pre = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
            <html>
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            
            </head>
            <body>';
        $suf = '</body></html>';
        $html .= $pre;

        $html .= '<table border = "1" cellpadding = "1" cellspacing = "0" >
                        <tr align = "center" style = "background-color:#ddf2f2">
                            <th> Game Code</th>
                            <th> Message </th>
                        </tr>';
        for ($i = 0; $i < count($lstDiffGame); $i++) {
            $html .= "<tr>";
            $gameCode = $lstDiffGame['game_code'][$i];
            $mess = $lstDiffGame['message'][$i];
            $html .= "<td>$gameCode </td>
                          <td>$mess</td> </tr>";
        }
        $html .= "</table>";
        $html .= $suf;
        return $html;
    }

    private function makeHtml($tables, $lstDay, $timing)
    {
        $date_1 = $lstDay[0];
        $date_2 = $lstDay[1];
        if (strcmp($timing, "1") == 0) {
            $date_1 = "Day " . date("d-M-Y", strtotime($date_1));
            $type = "Daily";
        } else if (strcmp($timing, "7") == 0) {
            $date_1 = "Week " . date('W', strtotime($date_1));
            $type = "Weekly";
        } else if (strcmp($timing, "31") == 0) {
            $date_1 = "Month " . date('F / Y', strtotime($date_1));
            $type = "Monthly";
        }


        $pre = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
            <html>
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            
            </head>
            <body>';
        $suf = '</body></html>';
        $html = $pre .
            "KPI Mobile $type Report $date_1 <br><br>";
        $htmlTable = $this->buildHtml($tables, $lstDay, $timing);
        $html .= $htmlTable
            . $suf;
        return $html;


    }

    public function attachMail($data,$dataNotGE, $dataSum, $lstDay, $timing)
    {
        require_once APPPATH . "/third_party/PHPExcel.php";
        $date_1 = $lstDay[0];
        $date_2 = $lstDay[1];
        if (strcmp($timing, "1") == 0) {
            $date_1 = date("d-M-Y", strtotime($date_1)) . " (Yesterday)";
            $date_2 = date("d-M-Y", strtotime($date_2)) . " (Prior to Yesterday)";
            $compare = "DoD";
        } else if (strcmp($timing, "7") == 0) {
            $date_1 = "Week " . date('W', strtotime($date_1) . " (Last week)");
            $date_2 = "Week " . date('W', strtotime($date_2)) . " (Prior to last week -)";
            $compare = "WoW";
        } else if (strcmp($timing, "31") == 0) {
            $date_1 = "Month " . date('F / Y', strtotime($date_1)) . " (Last month)";
            $date_2 = "Month " . date('F / Y', strtotime($date_2)) . " (Prior to last month)";
            $compare = "MoM";
        }

        $newData = $this->createSumData($dataSum, "52000" + $timing, $lstDay);
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $activeSheet = $objPHPExcel->getActiveSheet();
        $activeSheet->setTitle("Mobile Game");
        $default_border = array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '1006A3'));
        $header_style = array(
            'borders' => array('bottom' => $default_border, 'left' => $default_border, 'top' => $default_border, 'right' => $default_border,),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'E1E0F7'),),
            'font' => array('bold' => true,));
        /*Set Header*/
        $nav_compare_stype = array(
            'font' => array('color' => array(
                'rgb' => 'FF0000'
            )));
        $pos_compare_stype = array(
            'font' => array('color' => array(
                'rgb' => '#00BFFF'
            )));
        $rowHeaderSum = 1;
        /*Header Sum*/
        for ($i = 0 ; $i<6 ; $i++){
            $activeSheet->getStyleByColumnAndRow($i, $rowHeaderSum)
                ->applyFromArray($header_style)
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        $col =0;
        $activeSheet->setCellValueByColumnAndRow($col, $rowHeaderSum, "No");$col++;
        $activeSheet->setCellValueByColumnAndRow($col, $rowHeaderSum, "");$col++;
        $activeSheet->setCellValueByColumnAndRow($col, $rowHeaderSum, "Market");$col++;
        $activeSheet->setCellValueByColumnAndRow($col, $rowHeaderSum, $date_1);$col++;
        $activeSheet->setCellValueByColumnAndRow($col, $rowHeaderSum, $date_2);$col++;
        $activeSheet->setCellValueByColumnAndRow($col, $rowHeaderSum, "$compare");$col++;
        $row = $rowHeaderSum + 1;
        /*End Header Sum */
        $totalMarket = count($newData);
        /*Start Data Sum*/
        $stt = 1;
        foreach ($newData as $market => $data2) {

            $market =$this->getFullNameMarket($market);
            $total1 = $data2[$lstDay[0]];
            $total2 = $data2[$lstDay[1]];
            $compare1Day = $this->calcCompare($total1, $total2);
            $total1 = number_format($total1);
            $total2 = number_format($total2);

            $activeSheet->setCellValueByColumnAndRow(0, $row, $stt);$stt++;
            $activeSheet->mergeCellsByColumnAndRow(1, $row, 1, $rowHeaderSum + $totalMarket);
            $activeSheet->setCellValueByColumnAndRow(1, $row, "Gross revenue");
            $activeSheet->setCellValueByColumnAndRow(2, $row, $market);

            $activeSheet->setCellValueByColumnAndRow(3, $row, $total1);
            $activeSheet->getStyleByColumnAndRow(3, $row)
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $activeSheet->setCellValueByColumnAndRow(4, $row, $total2);
            $activeSheet->getStyleByColumnAndRow(4, $row)
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $roundValueSum = number_format($compare1Day, 0);

            if (is_numeric($roundValueSum)) {
                if ($roundValueSum > 0) {
                    $activeSheet->setCellValueByColumnAndRow(5, $row, $compare1Day);
                    $activeSheet->getStyleByColumnAndRow(5, $row)
                        ->applyFromArray($pos_compare_stype)
                        ->getAlignment()
                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                } else {
                    $activeSheet->setCellValueByColumnAndRow(5, $row, $compare1Day);
                    $activeSheet->getStyleByColumnAndRow(5, $row)
                        ->applyFromArray($nav_compare_stype)
                        ->getAlignment()
                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                }
            } else {
                $activeSheet->setCellValueByColumnAndRow(5, $row, "-");
            }
            $activeSheet->setCellValueByColumnAndRow(5, $row, $compare1Day);
            $row++;
        }
        /*End Data Sum*/
        /*Start Detail */
        /*Start Header Detail*/
        $rowHeader = $row + 1;
        for ($i = 0 ; $i<7 ; $i++){
            $activeSheet->getStyleByColumnAndRow($i, $rowHeader)
                ->applyFromArray($header_style)
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        }
        $col =0;
        $activeSheet->setCellValueByColumnAndRow($col, $rowHeader, "No");$col++;
        $activeSheet->setCellValueByColumnAndRow($col, $rowHeader, "Mobile Game");$col++;
        $activeSheet->setCellValueByColumnAndRow($col, $rowHeader, "Market");$col++;
        $activeSheet->setCellValueByColumnAndRow($col, $rowHeader, "KPI ");$col++;
        $activeSheet->setCellValueByColumnAndRow($col, $rowHeader, $date_1);$col++;
        $activeSheet->setCellValueByColumnAndRow($col, $rowHeader, $date_2);$col++;
        $activeSheet->setCellValueByColumnAndRow($col, $rowHeader, $compare);$col++;
        /*End Header Detail*/
        $row = $rowHeader + 1;

        /*Content*/
        $stt = 1;
        foreach ($data as $gameCode => $value) {

            $desc = $value['game_name'];
            $market = $this->getFullNameMarket($value['market']);
            /*$market = $value['market'];*/
            $col=0;
            /*Col = 0*/
            $activeSheet->mergeCellsByColumnAndRow($col, $row, $col, $row + 4);
            $activeSheet->setCellValueByColumnAndRow($col, $row, $stt);$col++;$stt++;
            /*Col = 1*/
            $activeSheet->mergeCellsByColumnAndRow($col, $row, $col, $row + 4);
            $activeSheet->setCellValueByColumnAndRow($col, $row, $desc);$col++;
            /*Col = 2*/
            $activeSheet->mergeCellsByColumnAndRow($col, $row, $col, $row + 4);
            $activeSheet->setCellValueByColumnAndRow($col, $row, $market);$col++;
            $newData = $this->rotateData($value);

            foreach ($newData as $kpi => $kpiValue) {
                $col=3;
                $kpiName = $this->findKpiDesc($kpi, $timing);
                $yesterday = $lstDay[0];
                $twoDayAgo = $lstDay[1];
                $kpi1Day = $kpiValue[$yesterday];
                $kpi2Day = $kpiValue[$twoDayAgo];
                $compare1Day = $this->calcCompare($kpi1Day, $kpi2Day);
                $kpi1Day = number_format($kpiValue[$yesterday]);
                $kpi2Day = number_format($kpiValue[$twoDayAgo]);
                if (strpos($kpi, "280") === 0) {
                    $kpi1Day .= "%";
                    $kpi2Day .= "%";
                }
                /*Col = 3*/
                $activeSheet->setCellValueByColumnAndRow($col, $row, $kpiName);$col++;

                /*Col = 4*/
                $activeSheet->getStyleByColumnAndRow($col, $row)
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                if (strcmp($kpi1Day, "0") == 0) {
                    $activeSheet->setCellValueByColumnAndRow($col, $row, "-");
                } else {
                    $activeSheet->setCellValueByColumnAndRow($col, $row, $kpi1Day);
                }
                $col++;
                /*Col = 5*/
                $activeSheet->getStyleByColumnAndRow($col, $row)
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                if (strcmp($kpi2Day, "0") == 0) {
                    $activeSheet->setCellValueByColumnAndRow($col, $row, "-");
                } else {
                    $activeSheet->setCellValueByColumnAndRow($col, $row, $kpi2Day);
                }
                $col++;

                $roundValue = number_format($compare1Day, 0);
                /*Col = 6*/
                if (is_numeric($roundValue)) {
                    if ($roundValue > 0) {
                        $activeSheet->setCellValueByColumnAndRow($col, $row, $compare1Day);
                        $activeSheet->getStyleByColumnAndRow($col, $row)
                            ->applyFromArray($pos_compare_stype)
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    } else {
                        $activeSheet->setCellValueByColumnAndRow($col, $row, $compare1Day);
                        $activeSheet->getStyleByColumnAndRow($col, $row)
                            ->applyFromArray($nav_compare_stype)
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    }
                } else {
                    $activeSheet->setCellValueByColumnAndRow($col, $row, "-");
                }
                $activeSheet->getStyleByColumnAndRow($col, $row)
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $activeSheet->setCellValueByColumnAndRow($col, $row, $compare1Day);
                $row++;
            }

        }
        /*NOT GE*/
        $row = $row + 3;
        if ($dataNotGE !=null){
            $activeSheet->getStyleByColumnAndRow(0,$row)
                ->applyFromArray($header_style)
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $activeSheet->mergeCellsByColumnAndRow(0, $row, 6, $row);
            $activeSheet->setCellValueByColumnAndRow(0, $row,"Detail game not in GE");
            $row++;
            $stt = 1;
            foreach ($dataNotGE as $gameCode => $value) {

            $desc = $value['game_name'];
            $market = $this->getFullNameMarket($value['market']);
            /*$market = $value['market'];*/
            $col=0;
            /*Col = 0*/
            $activeSheet->mergeCellsByColumnAndRow($col, $row, $col, $row + 4);
            $activeSheet->setCellValueByColumnAndRow($col, $row, $stt);$col++;$stt++;
            /*Col = 1*/
            $activeSheet->mergeCellsByColumnAndRow($col, $row, $col, $row + 4);
            $activeSheet->setCellValueByColumnAndRow($col, $row, $desc);$col++;
            /*Col = 2*/
            $activeSheet->mergeCellsByColumnAndRow($col, $row, $col, $row + 4);
            $activeSheet->setCellValueByColumnAndRow($col, $row, $market);
            $newData = $this->rotateData($value);

            foreach ($newData as $kpi => $kpiValue) {
                $col=3;
                $kpiName = $this->findKpiDesc($kpi, $timing);
                $yesterday = $lstDay[0];
                $twoDayAgo = $lstDay[1];
                $kpi1Day = $kpiValue[$yesterday];
                $kpi2Day = $kpiValue[$twoDayAgo];
                $compare1Day = $this->calcCompare($kpi1Day, $kpi2Day);
                $kpi1Day = number_format($kpiValue[$yesterday]);
                $kpi2Day = number_format($kpiValue[$twoDayAgo]);
                if (strpos($kpi, "280") === 0) {
                    $kpi1Day .= "%";
                    $kpi2Day .= "%";
                }
                /*Col = 3*/
                $activeSheet->setCellValueByColumnAndRow($col, $row, $kpiName);$col++;

                /*Col = 4*/
                $activeSheet->getStyleByColumnAndRow($col, $row)
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                if (strcmp($kpi1Day, "0") == 0) {
                    $activeSheet->setCellValueByColumnAndRow($col, $row, "-");
                } else {
                    $activeSheet->setCellValueByColumnAndRow($col, $row, $kpi1Day);
                }
                $col++;
                /*Col = 5*/
                $activeSheet->getStyleByColumnAndRow($col, $row)
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                if (strcmp($kpi2Day, "0") == 0) {
                    $activeSheet->setCellValueByColumnAndRow($col, $row, "-");
                } else {
                    $activeSheet->setCellValueByColumnAndRow($col, $row, $kpi2Day);
                }
                $col++;

                $roundValue = number_format($compare1Day, 0);
                /*Col = 6*/
                if (is_numeric($roundValue)) {
                    if ($roundValue > 0) {
                        $activeSheet->setCellValueByColumnAndRow($col, $row, $compare1Day);
                        $activeSheet->getStyleByColumnAndRow($col, $row)
                            ->applyFromArray($pos_compare_stype)
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    } else {
                        $activeSheet->setCellValueByColumnAndRow($col, $row, $compare1Day);
                        $activeSheet->getStyleByColumnAndRow($col, $row)
                            ->applyFromArray($nav_compare_stype)
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    }
                } else {
                    $activeSheet->setCellValueByColumnAndRow($col, $row, "-");
                }
                $activeSheet->getStyleByColumnAndRow($col, $row)
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $activeSheet->setCellValueByColumnAndRow($col, $row, $compare1Day);
                $row++;
            }

        }
        }

        /*End Content*/
        /*$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save("abc.xls");*/ // saving the excel file

        define("HOME_DIR", "/home/tuonglv/da_mail");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $outputPathDir = HOME_DIR . "/mobile-market/" . $lstDay[0];

        if (strcmp($timing, "1") == 0) {
            $outputPathDir = $outputPathDir . "/daily";
        } else if (strcmp($timing, "7") == 0) {
            $outputPathDir = $outputPathDir . "/weekly";
        } else if (strcmp($timing, "31") == 0) {
            $outputPathDir = $outputPathDir . "/monthly";
        }
        if (!is_dir($outputPathDir)) {
            mkdir($outputPathDir, 0777, TRUE);
        }
        $fileName = $outputPathDir . "/kpi.stats.vng.com.vn-mobile-market-report." . $lstDay[0] . ".xlsx";
        $objWriter->save($fileName); // saving the excel file*
        return $fileName;
    }

    // scp root@10.40.1.22:/var/www/html/smtq/abc.xls /home/quangctn/Documents/mail_excel/
    private function get_email_source()
    {
        $more_info = "<i>
            Source: <a href='https://kpi.stats.vng.com.vn/index.php/dashboard2?from=mobile-email'>STATS/TEG</a><br>
            </i>
            Regards, </br>Stats
            ";
        return $more_info;
    }
    public function get_note()
    {
        $lstMarket = $this->mail->getMarketGame();
        $note = '<div id="footer"><span style="color:#f21619"><h3><strong>***Note: </strong></h3></span>';

        foreach ($lstMarket as $key => $value) {

            $market = strtoupper($value['market']);
            $lstGameName = $this->mail->getGameNameByMarket($market);
            $htmlGameName = '';
            foreach ($lstGameName as $key2 => $data2) {
                $gameName = $data2['GameName'];
                $htmlGameName .= "$gameName,";
            }
            if ($market == null) {
                $note .= "<p><b>OTHERS</b>: $htmlGameName</p>";
            }else{
            $note .= "<p><b>$market </b>: $htmlGameName</p>";
            }
        }
        $note .= "</div>";
        $note .= "<style>
                #footer {
               font-family: Arial;
               /*font-size: 10px;*/
                }
</style>";
        return $note;
    }

    private function sortGameByRequre($data)
    {
        $newData = array();
        $order = array('vn', 'thai', 'indo', 'sea', 'myanmar');
        $diff = array_diff($order, array_keys($data));
        if (count($diff) != 0) {
            foreach ($diff as $market) {
                array_push($order, $market);
            }
        }
        foreach ($order as $key => $market) {
            if (isset($data[$market])) {
                $newData[$market] = $data[$market];
            }
        }
        if (isset($data[''])) {
            $newData[''] = $data[''];
        }
        return $newData;

    }

    private function buildSumTable($data, $lstDay, $timing)
    {

        $kpi = "52000" + $timing;
        /* Group by kpi_id, report_date (yesterday && 2day ago)
         Order by report_date desc
         data[0] = value of yesterday
         data[1] = value of 2day ago
        */
        $newData = $this->createSumData($data, $kpi, $lstDay);

        /* var_dump($newData);
         exit();*/
        $new_line = "\n";
        $html = "";
        $html .= ' <table border = "1" cellpadding = "1" cellspacing = "0" > ' . $new_line;
        $html .= "<tr style = 'text - align: center; font - weight: bold; background: #ccccff;'>$new_line";
        $html .= $this->get_header_table_daily($lstDay, $timing, true);
        $html .= $this->build_content_table_sum($newData, $kpi, $lstDay);
        $html .= "</table> </br></br></br>";
        return $html;
    }

    private function createSumData($data, $kpi, $lstDate)
    {
        $newData = array();
        foreach ($data as $key => $value) {
            $newData[$value['market']][$value['report_date']] = $value['total'];

        }
        $newData = $this->sortGameByRequre($newData);
        $sum = array();
        foreach ($newData as $key => $value) {
            $sum[$lstDate[0]] += $value[$lstDate[0]];
            $sum[$lstDate[1]] += $value[$lstDate[1]];

        }
        foreach ($lstDate as $date) {
            $newData['All Markets'][$date] = $sum[$date];
        }

        return $newData;
    }
    private function getFullNameMarket($market){
        $market = strtoupper($market);
        /*$fullName = $market;
        $mapping = array('VN'=>'Việt Nam','TH'=>'Thái','ID' => 'Indonesia');
        if(in_array($market,array_keys($mapping))){
            $re = $mapping[$market];
            $fullName = $market."($re)";
        }
        */
        if(strcmp($market,"")==0){
            $market = "OTHERS";
        }
        return $market;
    }
    private function build_content_table_sum($data, $kpi, $lstDay)
    {
        $html = "";
        $yesterday = $lstDay[0];
        $twoDayAgo = $lstDay[1];
        $rowSpan = count($data);
        $i = 0;
        foreach ($data as $market => $data2) {
            $stt = $i+1;
            $market = $this->getFullNameMarket($market);


            $total1 = $data2[$yesterday];
            $total2 = $data2[$twoDayAgo];
            $compare1Day = $this->calcCompare($total1, $total2);

            $total1 = number_format($total1);
            $total2 = number_format($total2);
            $html .= "<tr>";
            $html .= "<td>$stt</td>";
            if ($i == 0) {
                $html .= "<td rowspan = '$rowSpan'>Gross Revenue</td>";
            }

            $html .= "<td>$market</td>";
            $html .= "<td align='right'>$total1</td>";
            $html .= "<td align='right'>$total2</td>";
            $html .= $this->getColorTd($compare1Day);
            $html .= "</tr>";
            $i++;
        }
        return $html;

    }


    private function buildHtml($tables, $lstDay, $timing)
    {
        $html = "<style>
                body,td,tr,table
                {
                font-size: 14px;px;
                }</style>";
        $note = $this->get_note();
        $sign = $this->get_email_source();

        $html .= $this->buildSumTable($tables['sum'], $lstDay, $timing);;

        $html .= $this->buildHtmlTable($tables['detailMobile'], $lstDay, $timing);
        $html .=$note;
        $html .= $sign;
        /*var_dump($html);exit();*/
        return $html;
    }


    private function buildHtmlTable($tables, $lstDay, $timing)
    {
        $kpiSort = '52000' + $timing;
        $top10GameVn = $this->mail->sortMobileGame($kpiSort, $lstDay[0], 'vn', 10);
        $top5GameNotVn = $this->mail->sortMobileGame($kpiSort, $lstDay[0], 'sea', 5);
        $newTable = array();
        foreach ($top10GameVn as $key => $value) {
            $newTable[$value['game_code']] = $tables[$value['game_code']];
        }
        foreach ($top5GameNotVn as $key => $value) {

            $newTable[$value['game_code']] = $tables[$value['game_code']];
        }
        $html = "";
        $new_line = "\n";
        $html .= '<table border="1" pcellpadding="1" cellspacing="0">' . $new_line;
        $html .= "<tr style = 'text-align: center; font-weight: bold; background: #ccccff;'>$new_line";

        $html .= $this->get_header_table_daily($lstDay, $timing);
        $countTr = 0;
        foreach ($newTable as $gameCode => $value) {

            $stt = $countTr +1;
            if($countTr == 0){
                $html .= "<tr align = 'center' style = 'background-color:#ffffaa'>
                    <td colspan='7' align='center'>Top 10 VN Game By Gross Revenue</td>";

                $html .= "
                </tr > ";
            }
            if($countTr == 10){
                $html .= "<tr align = 'center' style = 'background-color:#ffffaa'>
                    <td colspan='7' align='center'>Top 5 Sea By Gross Revenue</td>";

                $html .= "
                </tr > ";
            }
            $i = 0;
            $desc = $value['game_name'];
            $fullNameMarket = $this->getFullNameMarket($value['market']);
            /*$fullNameMarket = $value['market'];*/
            $newData = $this->rotateData($value);
            foreach ($newData as $kpi => $kpiValue) {
                $kpiName = $this->findKpiDesc($kpi, $timing);
                $yesterday = $lstDay[0];
                $twoDayAgo = $lstDay[1];

                $kpi1Day = $kpiValue[$yesterday];
                $kpi2Day = $kpiValue[$twoDayAgo];
                $compare1Day = $this->calcCompare($kpi1Day, $kpi2Day);
                $kpi1Day = number_format($kpi1Day);
                $kpi2Day = number_format($kpi2Day);

                if (strpos($kpi, "28") === 0) {
                    $kpi1Day = round($kpi1Day, 0);
                    $kpi2Day = round($kpi2Day, 0);
                } else {

                }
                $rowSpan = count($newData);

                if ($i == 0) {
                    $html .= "<tr><td rowspan = '$rowSpan'>$stt</td>
                    <td rowspan = '$rowSpan'>$desc</td>
                    <td rowspan = '$rowSpan'>$fullNameMarket</td>
                    <td>$kpiName</td>
                    <td align = 'right' > $kpi1Day</td >
                    <td align = 'right' > $kpi2Day</td > ";
                    $td1 = $this->getColorTd($compare1Day);
                    $html .= $td1;
                    $html .= "
                    </tr > ";
                    $i++;
                } else {
                    $html .= "<tr>
                    <td > $kpiName</td>";
                    if (strpos($kpi, "280") === 0) {
                        $kpi1Day .= "%";
                        $kpi2Day .= "%";
                        $html .= "<td align = 'right' > $kpi1Day</td >
                    <td align = 'right' > $kpi2Day</td >";
                    } else {
                        $html .= " <td align = 'right' > $kpi1Day</td >
                    <td align = 'right' > $kpi2Day</td >";
                    }

                    $td1 = $this->getColorTd($compare1Day);
                    $html .= $td1;
                    $html .= "
                    </tr > ";
                }


            }
            $countTr++;
        }
        $html .= "</table > ";
        return $html;
    }

    private function get_header_table_daily($lstDay, $timing, $sumTable)
    {
        $date_1 = $lstDay[0];
        $date_2 = $lstDay[1];
        if (strcmp($timing, "1") == 0) {
            $date_1 = date("d-M-Y", strtotime($date_1));
            $date_2 = date("d-M-Y", strtotime($date_2));
            $desc1 = "Yesterday";
            $desc2 = "Prior to yesterday";

            $compare = "DoD";
        } else if (strcmp($timing, "7") == 0) {
            $date_1 ="Week ".date('W', strtotime($date_1));
            $date_2 ="Week ".date('W', strtotime($date_2));
            $compare = "WoW";
            $desc1 = "Last week";
            $desc2 = "Prior to last week";
        } else if (strcmp($timing, "31") == 0) {
            $date_1 = "Month ".date('F/Y', strtotime($date_1));
            $date_2 = "Month ".date('F/Y', strtotime($date_2));
            $compare = "MoM";
            $desc1 = "Last month";
            $desc2 = "Prior to last month";
        }

        $header = " <tr align = 'center' style = 'background-color:#ddf2f2' >";
        $header .= "<th>No</th>";
        if ($sumTable) {
            $header .= "<th></th>
                        <th> Market </th>";
        } else {
            $header .= "
                        <th> Game</th>
                        <th> Market</th>
                        <th> KPI</th>";
        }
        $header .= "<th><div>".$date_1."</div><div>(".$desc1.")</div></th>
                    <th><div>".$date_2."</div><div>(".$desc2.")</div></th>
                    <th > $compare</th >
                </tr > ";
        return $header;
    }

    public function findKpiDesc($kpiNeed, $timing)
    {
        $kpi7 = array(
            '11017' => 'Weekly New User',
            '10017' => 'Weekly Active User',
            '28017' => 'Weekly RR(%)',
            "15017" => "Weekly Paying User",
            "52017" => "Weekly Gross Revenue ");

        $kpi31 = array(
            '11031' => 'Monthly New User',
            '10031' => 'Monthly Active User',
            '28031' => 'Monthly RR(%)',
            "15031" => "Monthly Paying User",
            "52031" => "Monthly Gross Revenue ");

        $kpi1 = array(
            "11001" => "N1",
            "10001" => "A1",
            "28001" => "RR1",
            "15001" => "PU1",
            "52001" => "Daily Gross Revenue ");
        $kpiCfg = array("1" => $kpi1, "7" => $kpi7, "31" => $kpi31);
        $kpiDesc = $kpiCfg[$timing][$kpiNeed];
        return $kpiDesc;
    }

    private function rotateData($data)
    {

        $newData = array();
        foreach ($data['report_date'] as $reportDate => $data2) {

            foreach ($data2['kpi_id'] as $kpi => $kpiValue) {
                $newData[$kpi][$reportDate] = $kpiValue;

            }
        }
        return $newData;
    }

    /*Value 1 : kpi data yesterday
      Value 2 :
    */
    private function calcCompare($value1, $value2)
    {
        if (is_numeric($value1) && is_numeric($value2) && $value1 != 0) {
            $result = ($value1 - $value2) / $value2 * 100;
            $result = round($result, 0);
            $result .= '%';
            return $result;
        }
        return "-";
    }


    private function getColorTd($value)
    {
        /* var_dump($value);*/
        $td = "";
        $roundValue = number_format($value, 0);
        if (is_numeric($roundValue)) {
            if ($roundValue > 0) {
                $td .= " <td align = 'right' ><font color = 'blue' > $roundValue" . " % " . " </font ></td > ";
            } else {
                $td .= "<td align = 'right''><font color = 'red'> $roundValue" . " % " . " </font></td> ";
            }
        } else {
            $td .= "<td align='left'> - </td> ";
        }
        return $td;

    }


    private function getTimming($currentDate)
    {
        $timming = "";
        $yesterday = date("Y-m-d", strtotime('-1 day' . $currentDate));
        $dayOfW = date('w', strtotime($yesterday));
        $endMonth = date('Y-m-d', strtotime('last day of this month' . $yesterday));
        // $dayOfW == 0 mean that yesterday is sunday (send weekly email)
        if (strcmp($dayOfW, "0") == 0) {
            $timming = "7";
        } else {
            if (strcmp($endMonth, $yesterday) == 0) {
                //today is end month
                $timming = "31";
            }
        }

        return $timming;
    }

    private function checkWeekly($currentDate)
    {
        $timing = $this->getTimming($currentDate);
        if (strcmp($timing, "7") == 0) {
            return true;
        }
        return false;
    }

    private function checkMonthly($currentDate)
    {

        $timing = $this->getTimming($currentDate);
        if (strcmp($timing, "31") == 0) {
            return true;
        }
        return false;
    }

    public function show_mail($id)
    {
        $a = $this->mail->filter_mail($id);
        var_dump($a);
        exit();
    }
}