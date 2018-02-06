<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 12/04/2016
 * Time: 10:38
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Geo_model extends CI_Model {

    private $table = 'login_geolocation';

    public function __construct()
    {
        parent::__construct();

    }
//select game_code, login_date, city_name,country_code,sum(ltotal) from login_geolocation where game_code='jx1' and login_date >= '2016-01-01' and login_date < '2016-01-03' group by city_name;

    public function getDataDrawChart($gameCode, $timming, $calculateDateFrom,$calculateDateTo)
    {
        $ubstats = $this->load->database('ubstats', TRUE);
        $aTiming = array('4' => 'daily', '5' => 'weekly', '6' => 'monthly', '7' => 'yearly');
        $ubstats->select("date_format(login_date,'%Y-%m-%d') as log_date_ymd,game_code,city_name,country_code,sum(ltotal) as ltotal",false);
        $ubstats->from($this->table);
        $ubstats->where('game_code', $gameCode);
        $ubstats->where('login_date >= ', $calculateDateFrom);
        $ubstats->where('login_date <= ', $calculateDateTo);
        $ubstats->group_by('city_name');
        $ubstats->order_by('ltotal', 'asc');
        $query = $ubstats->get();
        $result = $query->result_array();

        //$sql = $this->db->last_query();
        //var_dump($sql);
        //exit();
        //file_put_contents("/tmp/tuonglv/sql.txt",$sql . "\n\n",FILE_APPEND);
        //var_dump($result);exit();

        return $result;
    }



}


