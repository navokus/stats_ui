<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 01/11/2016
 * Time: 09:14
 */
class Test extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('test_model', 'test');
    }

    public function test($kpi,$start,$end)
    {
        $kpi2 = $kpi;
        switch ($kpi) {
            case "ACU":
                $kpi2 = '30001';
                break;
            case "PCU":
                $kpi2 = '31001';
                break;
            default:
                $kpi2 = '10001';
        }
        $view_data['kpi'] = $kpi2;
        $view_data['title'] = $kpi;
        $view_data['data'] = $this->test->getDataServer($start, $end, $kpi2);
        $this->_template['content'] .= $this->load->view("topowner/test_ui", $view_data, true);
        $this->load->view('master_page', $this->_template);
        }


}