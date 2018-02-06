<?php

class MY_Controller extends CI_Controller
{

    public $source_menu;
    public $_template;
    private $_user;
    public $_gameInfo;
    function __construct()
    {
        parent::__construct();
        $this->load->library('UserConstants');
        $this->load->library('email');
        $this->load->model("common_model", "common_model");
        $this->load->model("game_model", "game_model");
        $this->load->model('log_model', 'logView');
        $this->load->model('user_model', 'user');
        $this->load->model('game_model', 'game');
        
        
        $this->check_login();
        $this->check_permission();
        $this->set_game_type();
        $this->set_game_list();

        $game_code = $this->get_user_input_game_code();
        $this->set_game_info($game_code);
        $this->set_everything_about_gamecode($game_code);
    }

    private function set_game_info($game_code){
        $this->_gameInfo = $this->game_model->get_game_info($game_code);
    }

    private function set_game_list()
    {
        $listGames = $this->game->listGames();
        if (count($listGames) == 0 && $this->router->fetch_class() != "Info") {
            redirect('info', 'index');
        }
        $this->_template['list_games'] = $listGames;
    }

    private function check_login()
    {
    	$query_string = $this->util->get_current_query_string();
    	
        if (!$this->session->userdata('user')) {
            
            redirect('Login?return=' . $query_string, 'refresh');
        }
    }
    private function check_statslogin()
    {
    	if (!$this->session->userdata('user')) {
    		$query_string = $this->util->get_current_query_string();
    		redirect($this->config->config["stats_sso_domain"] ."ssoLogin?appId=" . $this->config->config["stats_app_id"]);
    	}
    }
    private function check_permission()
    {
        $this->_user = $this->user->checkUserLogin($this->session->userdata('user'));
        if (!$this->_user) {
            show_error('Do not permission');
        }
        $this->session->set_userdata(array('infoUser' => $this->_user));
    }

    private function set_game_type()
    {
        if (isset($_GET['gameType2'])) {
            if ($_GET['gameType2'] == 0) {
                $this->session->set_userdata('gameType2', '');
            } else {
                $this->session->set_userdata('gameType2', $_GET['gameType2']);
            }
        }

        /** vinhdp - 2016-06-26: game type selection (ALL, DESKTOP, MOBILE) */
        $gameType = $this->input->post('game_type');
        if ($gameType == NULL && $this->session->userdata('game_type') == false) {
            $gameType = "all";
        } else if ($gameType == NULL && $this->session->userdata('game_type')) {
            $gameType = $this->session->userdata('game_type');
        }

        $this->session->set_userdata('game_type', $gameType);
        /** end */

    }


    public function get_user_input_game_code()
    {
        if ($this->input->post('default_game')) {
            $game_code = $this->input->post('default_game');
        } else if($this->session->userdata("default_game")) {
            $game_code = $this->session->userdata("default_game");
        }else{
            $game_code = $this->_template['list_games'][0]['GameCode'];
        }
        return $game_code;
    }

    public function set_everything_about_gamecode($game_code)
    {
        $this->session->set_userdata('default_game', $game_code);
        $url = base_url(uri_string());
        $this->logView->addTrackingView($this->session->userdata('user'), $game_code, $url);
        $this->session->set_userdata('current_game', $game_code);

        $is_mobile_game = $this->common_model->is_mobile_game($game_code);
        $this->session->set_userdata('is_mobile_game', $is_mobile_game);

        $left_menu = $this->game_model->get_report_list_from_gamecode($game_code);
        if (!$this->check_permission_page($left_menu)) {
            show_error('You have not permission to access this page');
        }
        $left_menu = $this->re_organize_leftmenu_data($left_menu);
        $return['left_menu'] = $left_menu;
        $return['user'] = $this->_user;
        $this->_template['top_menu'] = $this->load->view('top_menu', $return, TRUE);

        if(strcmp($this->source_menu,"sdk")==0){
            $this->_template['menu'] = $this->load->view('sdk/left_menu', $return, TRUE);

        }else{
            $this->_template['menu'] = $this->load->view('left_menu', $return, TRUE);

        }

    }

    private function re_organize_leftmenu_data($left_raw)
    {
        $return = array();
        for ($i = 0; $i < count($left_raw); $i++) {
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

    private function check_permission_page($left_menu)
    {
        return true;
        $url = $_SERVER['REQUEST_URI'];
        if (strpos($url, 'index.php') !== false) {
            $t1 = explode("index.php", $url);
            $t2 = $t1[1];
            $t3 = substr($t2, 1, strlen($t2) - 1);
            $t4 = explode("?", $t3);
            $url = $t4[0];
            for ($i = 0; $i < count($left_menu); $i++) {
                if ($left_menu[$i]['url'] == $url) {
                    return true;
                }
            }
        }
        return false;
    }
}
