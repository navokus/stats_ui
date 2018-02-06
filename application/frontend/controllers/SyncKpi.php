<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by IntelliJ IDEA.
 * User: canhtq
 * Date: 09/08/2016
 * Time: 14:08
 */


class SyncKpi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
        $this->load->library('util');
        $this->load->model('sync_model', 'sync');
        $this->load->library('user_agent');
        $this->load->helper('url');
    }


    public function sync7Gs(){
        $now = date("Y-m-d", time());
        $yesterday = date("Y-m-d", strtotime($now) - 24*60*60);
        $day7 = date("Y-m-d", strtotime($now) - 7*24*60*60);
        $dates = $this->util->getDaysFromTiming($day7, $yesterday, "daily", false);
        $kpiData = $this->sync->getChannelKPI($dates,array("ygh5"));
        $this->sync->cleanGs($dates,"share_group_kpi_json");
        $this->sync->syncToGs($kpiData,"share_group_kpi_json");
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode("ok"));
    }

    public function sync1Gs(){
        $now = date("Y-m-d", time());
        $yesterday = date("Y-m-d", strtotime($now) - 24*60*60);
        $day1 = date("Y-m-d", strtotime($now) - 2*24*60*60);
        $dates = $this->util->getDaysFromTiming($day1, $yesterday, "daily", false);
        $kpiData = $this->sync->getChannelKPI($dates,array("ygh5"));
        $this->sync->cleanGs($dates,"share_group_kpi_json");
        $this->sync->syncToGs($kpiData,"share_group_kpi_json");
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode("ok"));
    }
}