<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by IntelliJ IDEA.
 * User: quangctn
 * Date: 04/25/2017
 */

class GameMaster extends MY_Controller {


	public function __construct()
	{
		parent::__construct();

		$this->load->model('game_model', 'game');
        $this->load->model('gamemaster_model', 'gamemaster');
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
        $viewData['list'] = $this->gamemaster->listGames($gameCode);
        $this->_template['content'] = $this->load->view('game_master/listGames', $viewData, TRUE);
		$this->load->view('master_page', $this->_template);
	}
    public function renderData(){

    }
	public function addGame()
	{
        if ($this->input->post('game_code')) {


            $this->form_validation->set_rules('game_code', 'game_code', 'required');
            $this->form_validation->set_rules('group_id', 'group_id', 'required');
            $this->form_validation->set_rules('data_source', 'data_source', 'required');
            $this->form_validation->set_rules('kpi_type', 'kpi_type', 'required');

            if ($this->form_validation->run() == TRUE)
            {
                $this->gamemaster->addGame($_POST);
                redirect('GameMaster');
            }
        }
        $view_data = $this->config();
        $view_data['lstGameCode']= $this->game->listGames();
        $this->_template['content'] = $this->load->view('game_master/addGame', $view_data, TRUE);
        $this->load->view('master_page', $this->_template);
	}
    public function config(){
        $view_data['kpiType'] = array(
            "Channel" => "channel_kpi",
            "Game_Behavior" => "game_behavior",
            "Game" => "game_kpi",
            "Hourly" => "hourly_kpi",
            "Os" => "os_kpi",
            "Package" => "package_kpi",
            "Realtime Game" => "realtime_game_kpi",
            "Server Kpi" => "server_kpi",
            "Game Retention" => "game_retention",
            "Server Kpi_Hourly" => "server_kpi_hourly",
            "Marketing" => "marketing_kpi",
            "Group" => "group_kpi",
            "Game Kpi Hourly" => "game_kpi_hourly",
            "Country Kpi" => "country_kpi");
        $view_data['dataSource'] = array("ingame", "sdk", "payment", "voss");
        $view_data['groupId'] = array(
            "User" => "1",
            "Revenue" => "2",
            "Ccu" => "3");
        return $view_data;
    }
	public function editGame($kpiType,$groupId,$dataSource)
	{
	    $gameCode =  $this->session->userdata('current_game');
        $data['kpi_type']= $kpiType;
        $data['game_code']= $gameCode;
        $data['group_id']= $groupId;
        $data['data_source']= $dataSource;
        $infoMtGame = $this->gamemaster->getGame($data);
        if(count($infoMtGame)==0){
            redirect('GameMaster');
        }else {
            $view_data = $this->config();
            if ($_POST) {

                // update game
                if ($_POST['up_game']) {
                    $data_update = array(
                        'data_source' => $_POST['data_source']
                    );
                    $this->gamemaster->editGame($data_update, $data);
                }
                redirect('GameMaster');
            } else {

                $_POST = $infoMtGame;
            }

            $this->_template['content'] = $this->load->view('game_master/editGame', $view_data, TRUE);
            $this->load->view('master_page', $this->_template);
        }
	}

	public function delGame($kpiType,$groupId,$dataSource)
	{
        $gameCode =  $this->session->userdata('current_game');
        $data['kpi_type']= $kpiType;
        $data['game_code']= $gameCode;
        $data['group_id']= $groupId;
        $data['data_source']= $dataSource;
		$this->gamemaster->delGame( $data);
        redirect('GameMaster');
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