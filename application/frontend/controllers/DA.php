<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 07/07/2016
 * Time: 10:28
 */

class DA extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('game_model', 'game');
        $this->load->model('userkpi_model', 'userkpi');
        $this->load->library('form_validation');
        $this->load->library('util');
    }

    public function index(){

    }
}