<?php

/**
 * Created by IntelliJ IDEA.
 * User: canhtq
 * Date: 09/06/2017
 * Time: 15:22
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class GameKpi_model extends MY_Model
{
    private $_table_name = "game_kpi";
    public function __construct()
    {
        parent::__construct();
        $this->load->library ( 'util' );
    }

    public function getByRangeDates($fromDate, $toDate, $game_code, $kpi_ids)
    {
        $yesterday = date("Y-m-d", time() - 24 * 60 * 60);
        if ($yesterday < $toDate)
            $toDate = $yesterday;
        $day_arr = $this->util->getDaysFromTiming($fromDate, $toDate, 4, false);
        return $this->getByArrayDates($day_arr,$game_code,$kpi_ids);
    }

    public function getByDate($date, $game_code, $kpi_ids)
    {
        $day_arr = $this->util->getDaysFromTiming($date, $date, 4, false);
        return $this->getByArrayDates($day_arr,$game_code,$kpi_ids);
    }
    public function getByArrayDates($days, $game_code, $kpis)
    {
        $data = $this->getMetrics($kpis, "game_kpi", $game_code, $days, "game_kpi");
        $rs = array();
        $emptyData = array();
        foreach ($kpis as $kpi_id) {
            $emptyData[$kpi_id]="0";
        }

        for ($i = count($days) - 1; $i >= 0; $i--) {
            $tmp = $data[$days[$i]];
            if(!isset($tmp)){
                $tmp=$emptyData;
            }
            $rs[$days[$i]]= $tmp;
        }

        return $rs;
    }

    public function getGamesByArrayDates($days, $games, $kpis)
    {
        $rs = array();
        foreach ($games as $gameCode) {
            $dataGame = $this->getByArrayDates($days,$gameCode,$kpis);
            $rs[$gameCode]=$dataGame;
        }
        return $rs;
    }
    public function getGamesByRangeDates($fromDate, $toDate, $games, $kpis)
    {
        $rs = array();
        foreach ($games as $gameCode) {
            $dataGame = $this->getByRangeDates($fromDate,$toDate,$gameCode,$kpis);
            $rs[$gameCode]=$dataGame;
        }
        return $rs;
    }
    public function getGamesByDate($date, $games, $kpis)
    {
        $rs = array();
        foreach ($games as $gameCode) {
            $dataGame = $this->getByDate($date,$gameCode,$kpis);
            $rs[$gameCode]=$dataGame;
        }
        return $rs;
    }


}