<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class KpiDefine extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        //$this->output->enable_profiler(TRUE); // debug

    }

    public function index(){
    	
    	$viewData ['body'] ['title']="KPI Define";
    	$this->_template ['content'] = $this->load->view ( 'kpi_define/index', $viewData, TRUE );
    	$this->_template['body']['aGames'] = $this->game->listGames();
    	$this->_template['body']['title'] = "Kpi Define";
    	$this->load->view ('master_page', $this->_template );
    }

    



}


/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */