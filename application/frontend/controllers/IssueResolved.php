<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by IntelliJ IDEA.
 * User: canhtq
 * Date: 12/07/2017
 * Time: 10:41
 */
class IssueResolved extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('game_model', 'game');
        $this->load->model('user_model', 'user');
        $this->load->model('qaissuedgame_model', 'qaissuedgame');
        $this->load->library('form_validation');
        $this->load->library('util');

    }
    public function solved($game,$reportDate)
    {

        $current_user = $this->session->userdata('user');
        $user = $this->user->getUser($current_user);
        if ($user["GroupId"] == 1) {
            if ($this->input->post('message')) {
                $this->form_validation->set_rules('message', 'message', 'required');
                if ($this->form_validation->run() == TRUE) {
                    $games = explode(".", $game);
                    $batch = array();
                    foreach ($games as $gameCode) {
                        if($gameCode!="."){
                            $data = array();
                            $data['game_code'] = $gameCode;
                            $data['qa_user'] = $user["username"];
                            $data['report_date'] = $reportDate;
                            $data['message'] = $this->input->post('message');
                            $data['solved_date'] = date('Y-m-d H:i:s');
                            $batch[] = $data;
                        }
                    }
                    if(count($batch)>0){
                        $this->qaissuedgame->solved_alls($batch);
                        $this->qaissuedgame->clean_games($reportDate,$games);
                    }

                    redirect('IssueResolved');
                }else {
                    $this->form_validation->set_message('message', 'input root cause');
                }
            }
        }

        $this->_template['content'] = $this->load->view('issue/solved', $this->input->post, TRUE);
        $this->load->view('master_page', $this->_template);
    }
    public function index(){
        $this->_template['content'] = $this->load->view('issue/ok', null, TRUE);
        $this->load->view('master_page', $this->_template);
    }

}