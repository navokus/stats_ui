<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game extends MY_Controller {

	private $totalGrade = 8;

	public function __construct()
	{
		parent::__construct();
		// $this->output->enable_profiler(TRUE);
		$this->load->model('game_model', 'game');
		$this->load->model('grade_model', 'grade');
		$this->load->model('classification_model', 'classification');
		$this->load->model('Gradeconfig_model', 'gradeconfig');

		$this->load->library('form_validation');
		$this->load->library('util');

	}

	public function index()
	{
		$this->listGames();
	}

	public function listGames()
	{

		$return['list'] = $this->game->listGames(TRUE);
		$this->_template['content'] = $this->load->view('game/listGames', $return, TRUE);
		$this->load->view('master_page', $this->_template);
	}

    public function addGame()
    {

        if ($this->input->post('GameName')) {


            $this->form_validation->set_rules('PercentPaying', 'PercentPaying', 'required');
            $this->form_validation->set_rules('PercentPlaying', 'PercentPlaying', 'required');

            if ($this->form_validation->run() == TRUE)
            {
                //lamnt6
                $_POST['AlphaTestDate'] = $this->util->user_date_to_db_date($_POST['AlphaTestDate']);
                $_POST['OpenDate'] = $this->util->user_date_to_db_date($_POST['OpenDate']);

                $this->game->addGame($_POST);
                redirect('Game');
            }
        }
        if ($_POST ['AlphaTestDate']== "") {
            $_POST ['AlphaTestDate'] = date('d/m/Y', strtotime('-1 days'));
        }
        if ($_POST ['OpenDate']== "") {
            $_POST ['OpenDate'] = date('d/m/Y', strtotime('-1 days'));
        }
        $return['gameType'] = array(array("IdGameType"=>"1", "GameTypeName"=>"Abc"));//null;//$this->gameType->listGameType();
        $return['gameType2'] = array(array('Id'=>'1','Type'=>'Web Game'),array('Id'=>'2','Type'=>'Mobile Game'));//$this->gameType->listGameType2();
        $return['sendMail'] = array(
            "0" => "OFF",
            "1" => "ON");
        $return['region'] = array(
            "global" => "global",
            "local" => "local");
        $return['Status'] = array(
            "0" => "Closed ",
            "1" => "Launching",
            "2" => "Integrating ");
        $this->_template['content'] = $this->load->view('game/addGame', $return, TRUE);
        $this->load->view('master_page', $this->_template);
    }

    public function editGame($gameCode)
    {

        $aGame = $this->game->getGame($gameCode);


        if ($_POST) {

            // update game
            if ($_POST['up_game']) {
                $data = array(
                    'GameName' => $_POST['GameName'],
                    'IdGameType' => $_POST['IdGameType'],
                    'GameType2' => $_POST['GameType2'],
                    'SendMail' => $_POST['SendMail'],
                    'region' => $_POST['region'],
                    'Status' => $_POST['Status'],
                    'market' => $_POST['market'],
                    'owner' => $_POST['owner'],

                    'ContactProduct' => $_POST['ContactProduct'],
                    'ContactTechOm' => $_POST['ContactTechOm'],

                    'AlphaTestDate' => $this->util->user_date_to_db_date($_POST['AlphaTestDate']),
                    'OpenDate' => $this->util->user_date_to_db_date($_POST['OpenDate']),
                );
                $this->game->editGame($data, $gameCode);
            }


            // update paying
            if ($_POST['up_paying']) {

                $percent = $aGame['PercentPaying'];
                $type = 1;

                // put pay grade
                $key = 1;
                foreach ($_POST['Pay']["daily"]["name"] as $value) {

                    if ($value) {
                        $data = array(
                            'GradeName' => $value,
                            'IdGrade' => $this->util->convertNoneUtf8($value),
                            'Value' => $key,
                            'Percent' => $percent,
                            'GradePoint' => $key * $percent,
                            'Order' => $key
                        );

                        $this->grade->putGrade($data, $gameCode, $type);
                        $key++;
                    }
                }


            }

            // update playing
            if ($_POST['up_playing']) {

                $percent = $aGame['PercentPlaying'];
                $type = 2;

                // put play grade
                $key = 1;
                foreach ($_POST['Play']["daily"]["name"] as $value) {

                    if ($value) {
                        $data = array(
                            'GradeName' => $value,
                            'IdGrade' => $this->util->convertNoneUtf8($value),
                            'Value' => $key,
                            'Percent' => $percent,
                            'GradePoint' => $key * $percent,
                            'Order' => $key
                        );

                        $this->grade->putGrade($data, $gameCode, $type);
                        $key++;
                    }
                }

            }

            // update behavior
            if ($_POST['up_behavior']) {

                // put behavior Classification
                foreach ($_POST['Behavior']["name"] as $key => $value) {

                    if ($value) {
                        $data = array(
                            'ClassificationName' => $value,
                            'IdClassification' => $this->util->convertNoneUtf8($value),
                            'FromValue' => $_POST['Behavior']["from"][$key],
                            'ToValue' => $_POST['Behavior']["to"][$key],
                            'Order' => ($key + 1)
                        );

                        $this->classification->putClassification($data, $gameCode);
                        $key++;
                    }
                }

            }

            redirect('Game');
        } else {

            $_POST = $aGame;
            $_POST['AlphaTestDate']= $this->util->db_date_to_user_date($_POST['AlphaTestDate']);
            $_POST['OpenDate'] = $this->util->db_date_to_user_date($_POST['OpenDate']);
            // loop all period time


        }
        //redirect("Game");

        $return['totalGrade'] = $this->totalGrade;
        $return['gameType'] = array(array("IdGameType"=>"1", "GameTypeName"=>"Abc"));//null;//$this->gameType->listGameType();
        $return['gameType2'] = array(array('Id'=>'1','Type'=>'Web Game'),array('Id'=>'2','Type'=>'Mobile Game'));//$this->gameType->listGameType2();
        $this->_template['content'] = $this->load->view('game/editGame', $return, TRUE);
        $this->load->view('master_page', $this->_template);

    }
	public function delGame($gameCode)
	{
		$this->game->delGame( $gameCode);
		$this->listGames();
	}

	public function exportGameJson($gameCode , $view = 0)
	{
		$result = array();
		$aGame = $this->game->getGame($gameCode);
		// loop all period time
		if ($view == 1) {
			echo json_encode(array('grades' => $result), JSON_NUMERIC_CHECK);
		} 
		return json_encode(array('grades' => $result), JSON_NUMERIC_CHECK);
	}

	public function postConfigGame($gameCode)
	{
		$aGame = $this->game->getGame($gameCode);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->config->item('api_url') . 'ub/config/setgrade');
		curl_setopt($ch, CURLOPT_POST, 1);
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, 
			http_build_query(
				array(
					'game_code' => $gameCode,
					'game_type' => $aGame['GameTypeName'],
					'json_data' => $this->exportGameJson($gameCode),
				)
			)
		);
		// receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec ($ch);
		curl_close ($ch);
		//var_dump($server_output);
	}

    public function update_report_menu()
    {
        $game_code = $this->session->userdata('current_game');
        if (isset($_POST['updatereport'])) {
            unset($_POST['updatereport']);
            $this->game->update_report_menu($game_code, $_POST);
        }
        if (isset($_POST['addgroup'])) {
            unset($_POST['addgroup']);
            $this->game->add_report_group($_POST);
        }
        if (isset($_POST['addreport'])) {
            unset($_POST['addreport']);
            $this->game->add_report($_POST);
        }
        $get_all_report = $this->game->get_full_report();
        $all_menu_report = $this->re_organize_leftmenu_data($get_all_report);

        $group_report = $this->get_group_report($this->game->get_menu_report());

        $game_menu_report = $this->game_model->get_report_list_from_gamecode($game_code);
        $game_menu_report = $this->re_organize_leftmenu_data($game_menu_report);

        if (count($_POST)) {
            $t['left_menu'] = $game_menu_report;
            $this->_template['menu'] = $this->load->view('left_menu', $t, TRUE);
        }
        $gameList = $this->game->listGames();
        $viewData ['body'] ['aGames'] = $gameList;

        $return['selection'] = $this->load->view('body_parts/selection/game_selection', $viewData, TRUE);
        $return['all_report'] = $all_menu_report;
        $return['game_report'] = $game_menu_report;
        $return['group_report'] = $group_report;

        $this->_template['content'] = $this->load->view('game/update_report_menu', $return, TRUE);
        $this->load->view('master_page', $this->_template);
    }

    private function get_group_report($get_all_report){
        $return = array();
        for($i=0;$i<count($get_all_report);$i++){
            $return[$get_all_report[$i]['group_id']] = $get_all_report[$i]['group_name'];
        }
        return $return;
    }

    private function re_organize_leftmenu_data($left_raw){
        $return = array();
        for($i=0;$i<count($left_raw);$i++){
            $l = $left_raw[$i];

            $return[$l['group_id']]['group_name'] = $l['group_name'];
            $return[$l['group_id']]['class_1'] = $l['gr_class_1'];
            $return[$l['group_id']]['class_2'] = $l['gr_class_2'];

            $return[$l['group_id']]['report_detail'][$l['report_id']]['report_name'] = $l['report_name'];
            $return[$l['group_id']]['report_detail'][$l['report_id']]['report_url'] = $l['url'];
            $return[$l['group_id']]['report_detail'][$l['report_id']]['report_class_1'] = $l['rp_class_1'];
            $return[$l['group_id']]['report_detail'][$l['report_id']]['report_class_2'] = $l['rp_class_2'];
        }
        return $return;
    }

}

/* End of file Game.php */
/* Location: ./application/controllers/Game.php */