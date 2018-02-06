<?php


class TotalReport extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
//		 $this->output->enable_profiler(TRUE);
        $this->load->model('game_model', 'game');
        $this->load->library('form_validation');
        $this->load->library('util');
        $this->load->model('rptmobileos_model', 'mobileos');
    }
    
    public function mobileOs($date)
    {
    	$mos = $this->mobileos->getMetricMobileOs("grm");
    	$byDates = array();
    	$byOss = array();
    	foreach ($mos as $l1Os) {
    		$byDates[$l1Os["report_date"]][] = $l1Os;
    		$byOss[$l1Os["os"]][] = $l1Os;
    	}
    	$this->output->set_content_type('application/json');
    	$this->output->set_output(json_encode($byOss));
    	return;
    }
    
    public function ambyos($type)
    {
    	if ($this->input->post('options')) {
    		$_SESSION[$this->class_name]['post'] = $_POST;
    	}
    	$mos = $this->mobileos->getMetricMobileOs($type);
    	$byDates = array();
    	$byOss = array();
    	foreach ($mos as $l1Os) {
    		$byDates[$l1Os["report_date"]][] = $l1Os;
    		$byOss[$l1Os["os"]][] = $l1Os;
    	}
    	$viewData ['body']["byDates"] = $byDates;
    	$viewData ['body']["byOss"] = $byOss;
    	
    	switch ($type){
    		case "am":
    			$viewData ['body']["title"] = "Monthly Active Users";
    			break;
    			case "grm":
    				$viewData ['body']["title"] = "Monthly Revenue";
    				break;
    	}
    	$viewData ['body'] ['selection'] = $this->load->view ('body_parts/selection/only_date', $viewData, TRUE );
    	//load master view
    	$this->_template['content'] .= $this->load->view("total/ambyos", $viewData, TRUE);
    	$this->load->view('master_page', $this->_template);
    }
}