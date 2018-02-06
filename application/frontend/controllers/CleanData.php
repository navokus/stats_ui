<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by IntelliJ IDEA.
 * User: quangctn
 * Date: 03/05/2017
 * Time: 14:49
 */
class CleanData extends MY_Controller {


    public function __construct()
    {
        parent::__construct();

        $this->load->model('game_model', 'game');
        $this->load->model('cleandata_model', 'cleandata');
        $this->load->library('form_validation');
        $this->load->library('util');

    }

    public function index()
    {
        $this->listGames();
    }
    public function listGames()
    {
        $viewData['body']['aGames'] = $this->game->listGames();
        $gameCode = $this->session->userdata('current_game');
        $viewData['body']['gameCode'] = $gameCode;
        $this->form_validation->set_rules('valueSelect', 'valueSelect', 'required');
        if ($this->input->post('kpidatepicker') != "") {
            $date = $this->input->post('kpidatepicker');
            if ($this->input->post('action') == 'clean')
            {
                $lstTable =explode( ',' ,$_POST['valueSelect'] );
                foreach ( $lstTable as $value ){
                    $_POST['table_name'] = $value;
                  $this->cleandata->clean($_POST);
                }

            }else
                if ($this->input->post('action') == 'restore') {
                    $lstTable =explode( ',' ,$_POST['valueSelect'] );
                    foreach ( $lstTable as $value ){
                        $_POST['table_name'] = $value;
                        $this->cleandata->restore($_POST);
                    }
                }else{
            }
        } else {
            $date = '01/01/2015';
        }
        $viewData['list_table'] = array(
            "game_kpi" => "Game Kpi",
           /* "os_kpi" => "Os Kpi",*/
            "server_kpi_json" => "Server Kpi",
            "channel_kpi" => "Channel Kpi",
            "package_kpi" => "Package Kpi ");
        $viewData ['body'] ['day'] ['kpidatepicker'] = $date;

        $this->_template['content'] = $this->load->view('clean_data/cleandata', $viewData, TRUE);
        $this->load->view('master_page', $this->_template);
    }


}