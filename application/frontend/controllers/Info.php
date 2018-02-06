<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Info extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        //$this->output->enable_profiler(TRUE); // debug
        $this->load->model('game_model', 'game');

    }

    public function index(){
    	$listGames = $this->game->listGames();
    	if(count($listGames) != 0){
    		redirect("dashboard", "index");
    	}
    	
    	$viewData['body']['gameType'] = $this->session->userdata ('game_type');
    	$this->_template ['content'] = $this->load->view ( 'stats/index', $viewData, TRUE );
    	$this->load->view ('master_page', $this->_template );
    }
}


/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */