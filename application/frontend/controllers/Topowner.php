<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by IntelliJ IDEA.
 * User: quangctn
 * Date: 04/04/2017
 * Time: 11:32
 */

class Topowner extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('game_model', 'game');
        $this->load->model('topowner_model', 'topowner');
        $this->load->library('form_validation');
        $this->load->library('util');
    }

    public function index()
    {
        $this->view_statistics();
    }

    public function view_statistics()
    {
        /*// process date
        if (isset($_POST['daterangepicker'])) {
            $date = $_POST['daterangepicker'];
            $view_data['body']['day']['default_range_date'] = $date;
        } else {
            $view_data['body']['day']['default_range_date'] = date('d/m/Y', strtotime("-31 days")) . " - " . date('d/m/Y', strtotime("-1 days"));
        }
        $arrListDate = $this->util->parse_date_input($view_data['body']['day']['default_range_date']);*/
        if ($this->input->post('kpidatepicker') != "") {
            $date = $this->input->post('kpidatepicker');
        } else {
            $date = date('d/m/Y');
        }
        $view_data ['body'] ['day'] ['kpidatepicker'] = $date;

        //DAILY (selection = 1)
        $view_data['selection'] = array("rev" => '16001',"a1" =>'10001');
        $this->_template['body']['title'] = "Owner - Daily";
        if(isset($_POST['options'])){
            $tmp = $_POST['options'];
            //WEEKLY
            if ($tmp == 7){
                $view_data['selection'] = array("rev" => '16007',"a1" =>'10007');
                $this->_template['body']['title'] = "Owner - Weekly";
            //MONTHLY
            }elseif ($tmp == 30){
                $view_data['selection'] = array("rev" => '16031',"a1" =>'10031');
                $this->_template['body']['title'] = "Owner - Monthly";
            }elseif ($tmp ==1){
                $this->_template['body']['title'] = "Owner - Daily";
            }
        }
        //var_dump($date);

        $lstGame = $this->topowner->listGamesByLoginUser();
        $arrGameCode = array();
        foreach ($lstGame as $key => $value){
            $arrGameCode[] = $value['GameCode'];
        }
        foreach ($view_data['selection'] as $key => $value){
            $view_data['data'][$key]['data_chart'] = $this->topowner->listOwnerGames($arrGameCode,$value, $date, 'owner');
            $data= $this->topowner->top10Owner($arrGameCode,$value, $date, 'owner',10);
            $view_data['data'][$key]['data_bar_chart'] = $this->renderData($data);
            $view_data['data'][$key]['detail_table']= $this->topowner->listOwnerGames($arrGameCode,$value, $date);

        }
        $this->_template['content'] .= $this->load->view("topowner/view_statistics_topowner", $view_data, TRUE);


        $this->load->view('master_page', $this->_template);
    }
    public function renderData($data){
        $categories = "";
        $total = "";
        foreach($data as $key =>$value){
            $categories .= "'"  . strtoupper($value['owner']) .  "',";
            $total .=  strtoupper($value['total']) .  ",";
        }
        $arr['categories']  = substr($categories,0,strlen($categories)-1);
        $arr['total']  = substr($total,0,strlen($total)-1);
        return $arr;
    }

}