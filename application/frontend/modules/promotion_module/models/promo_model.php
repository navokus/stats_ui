<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Promo_model extends CI_Model
{

    private $tablePromo = 'C_Promotion';
    private $tablePromotionSatistic = 'RS_Promotion';
    private $tablePromotionSatisticDetail = 'RS_Promotion_Detail';
    private $tablePromotionActived = 'RS_Promotion_Actived';
    private $tablePromotionDaily = 'RS_Promotion_Daily';

    public function __construct()
    {
        parent::__construct();

    }

    // ==========================================================

    public function listPromotionByMonth($gameCode, $month, $year)
    {

        $day =cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $fromDate = $year . '-' . $month . '-01 00:00:00';
        $toDate = $year . '-' . $month . '-' . $day . ' 23:59:59';

        $this->db->select('*');
        $this->db->from($this->tablePromo);
        $this->db->where('GameCode', $gameCode);
        $this->db->where("FromDate <=", $toDate);
        $this->db->where("ToDate >=", $fromDate);

        $query = $this->db->get();
        // echo $this->db->last_query();

        $listPromotion = $query->result_array();

        foreach ($listPromotion as $key => $value) {
            $return = $this->getPromoStatisById($gameCode, $value['PromotionID']);
            $listPromotion[$key] = array_merge($listPromotion[$key], $return);
        }

        return $listPromotion;
    }


    public function getPromoInfo($gameCode, $id)
    {

        $this->db->select('*');
        $this->db->from($this->tablePromo);
        $this->db->where('GameCode', $gameCode);
        $this->db->where('PromotionID', $id);
        $query = $this->db->get();

        return $query->row_array();
    }

    public function getPromoStatisById($gameCode, $id)
    {
        $return = array();
        // get statis promotion previous
        $this->db->select('*');
        $this->db->from($this->tablePromotionSatistic);
        $this->db->where('GameCode', $gameCode);
        $this->db->where('PromotionID', $id);
        $this->db->where('CalculateType', 'previous');
        $this->db->order_by('CalculateDate desc, CreatedDate desc');
        $this->db->limit(1);
        $query = $this->db->get();
        $data['previous'] = $query->row_array();


        // get statis promotion current
        $this->db->select('*');
        $this->db->from($this->tablePromotionSatistic);
        $this->db->where('GameCode', $gameCode);
        $this->db->where('PromotionID', $id);
        $this->db->where('CalculateType', 'current');
        $this->db->order_by('CalculateDate desc, CreatedDate desc');
        $this->db->limit(1);
        $query = $this->db->get();
        $data['current'] = $query->row_array();

        // get statis promotion after
        $this->db->select('*');
        $this->db->from($this->tablePromotionSatistic);
        $this->db->where('GameCode', $gameCode);
        $this->db->where('PromotionID', $id);
        $this->db->where('CalculateType', 'after');
        $this->db->order_by('CalculateDate desc, CreatedDate desc');
        $this->db->limit(1);
        $query = $this->db->get();
        $data['after'] = $query->row_array();

        // return data
        $return['AccountTotalPrevious'] = $data['previous']['AccountTotal'];
        $return['RevenueTotalPrevious'] = $data['previous']['RevenueTotal'];
        $return['PlayingTimeTotalPrevious'] = $data['previous']['PlayingTimeTotal'];
        $return['AccountTotalPayingPrevious'] = $data['previous']['AccountTotalPaying'];
        $return['AccountTotalPlayingPrevious'] = $data['previous']['AccountTotalPlaying'];

        $return['AccountTotal'] = $data['current']['AccountTotal'];
        $return['RevenueTotal'] = $data['current']['RevenueTotal'];
        $return['PlayingTimeTotal'] = $data['current']['PlayingTimeTotal'];
        $return['AccountTotalPaying'] = $data['current']['AccountTotalPaying'];
        $return['AccountTotalPlaying'] = $data['current']['AccountTotalPlaying'];

        $return['AccountTotalAfter'] = $data['after']['AccountTotal'];
        $return['RevenueTotalAfter'] = $data['after']['RevenueTotal'];
        $return['PlayingTimeTotalAfter'] = $data['after']['PlayingTimeTotal'];
        $return['AccountTotalPayingAfter'] = $data['after']['AccountTotalPaying'];
        $return['AccountTotalPlayingAfter'] = $data['after']['AccountTotalPlaying'];

        return $return;
    }

    public function getPromoStatisDetailById($gameCode, $id)
    {
        $this->db->select('*');
        $this->db->from($this->tablePromotionSatisticDetail);
        $this->db->where('GameCode', $gameCode);
        $this->db->where('PromotionID', $id);
        $this->db->order_by('CalculateDate desc, CreatedDate desc');
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->row_array();
    }

    // transaction payment promotion
    public function getTransactionPaymentPromotion($gameCode, $promotionId)
    {
        $this->db->select('GameCode, CalculateValue, RevenueTotal, AccountTotalPaying, RevenueTotalNew, RevenueTotalOld');
        $this->db->from('RS_Promotion_Daily');
        $this->db->where('GameCode', $gameCode);
        $this->db->where('PromotionID', $promotionId);
        $this->db->where('CalculateType', 'daily');

        $this->db->order_by('CalculateDate asc');
        $query = $this->db->get();

        return $query->result_array();
    }

    // get user active
    public function getUserActived($gameCode, $promotionId)
    {
        // data current
        $this->db->select('*');
        $this->db->from($this->tablePromotionActived);
        $this->db->where('GameCode', $gameCode);
        $this->db->where('PromotionId', $promotionId);
        $this->db->where("CalculateType = 'current'");
        $this->db->order_by('CalculateDate desc');
        $query = $this->db->get();
        $rs = $query->row_array();

        $return['current'] = $rs;

        // data previous
        $this->db->select('*');
        $this->db->from($this->tablePromotionActived);
        $this->db->where('GameCode', $gameCode);
        $this->db->where('PromotionId', $promotionId);
        $this->db->where("CalculateType = 'previous'");
        $this->db->order_by('CalculateDate desc');
        $query = $this->db->get();
        $rs = $query->row_array();

        $return['previous'] = $rs;

        // data after
        $this->db->select('*');
        $this->db->from($this->tablePromotionActived);
        $this->db->where('GameCode', $gameCode);
        $this->db->where('PromotionId', $promotionId);
        $this->db->where("CalculateType = 'after'");
        $this->db->order_by('CalculateDate desc');
        $query = $this->db->get();
        $rs = $query->row_array();

        $return['after'] = $rs;

        return $return;

    }

    // Promotion statistic monthly
    public function getDataPromoStatisByMonthly($gameCode, $CalculateDateFrom, $CalculateDateTo)
    {

        $aUser = $this->session->userdata('infoUser');

        $this->db->select('rs.PromotionId, rs.CalculateValue, rs.GameCode, rs.AccountTotalNew, rs.AccountTotalOld, rs.RevenueTotalNew, rs.RevenueTotalOld,
			rs.PlayingTimeTotalNew, rs.PlayingTimeTotalOld, rs.RevenueTotal, rs.PlayingtimeTotal, rs.AccountTotalPaying,
			c.PromotionName, c.FromDate, c.ToDate, c.PromotionID');
        $this->db->from($this->tablePromotionDaily . ' rs');
        $this->db->join('C_Promotion c', 'rs.PromotionId = c.PromotionID AND rs.GameCode = c.GameCode');
//        $this->db->where('rs.GameCode', $gameCode);
        $this->db->where('rs.GameCode IN (SELECT GameCode FROM U_Group_Game_Smt WHERE GroupId = '. $aUser['GroupId'].')');
        $this->db->where('rs.CalculateType', 'monthly');
        $this->db->where("STR_TO_DATE('" . date("Y-m", strtotime($CalculateDateFrom)) . "','%Y-%m') <= STR_TO_DATE(CalculateValue,'%Y-%m') ");
        $this->db->where("STR_TO_DATE('" . date("Y-m", strtotime($CalculateDateTo)) . "','%Y-%m') >= STR_TO_DATE(CalculateValue,'%Y-%m') ");

        $this->db->order_by('rs.CalculateValue asc, rs.GameCode asc');
        $query = $this->db->get();
        $rs = $query->result_array();

        $return = array();
        foreach ($rs as $value) {
            $return[$value['CalculateValue']][$value['GameCode']][] = $value;
        }

        return $return;
    }

    // Promotion types monthly
    public function getDataPromoTypesByMonthly($gameCode, $CalculateDateFrom, $CalculateDateTo)
    {
        $aUser = $this->session->userdata('infoUser');

        $this->db->select('rs.PromotionId, rs.CalculateValue, rs.GameCode, rs.AccountTotalNew, rs.AccountTotalOld, rs.RevenueTotalNew, rs.RevenueTotalOld,
			rs.PlayingTimeTotalNew, rs.PlayingTimeTotalOld, rs.RevenueTotal, rs.PlayingtimeTotal, rs.AccountTotalPaying,
			c.PromotionName, c.FromDate, c.ToDate, c.PromotionID, c.PromotionType');
        $this->db->from($this->tablePromotionDaily . ' rs');
        $this->db->join('C_Promotion c', 'rs.PromotionId = c.PromotionID AND rs.GameCode = c.GameCode');
//        $this->db->where('rs.GameCode', $gameCode);
        $this->db->where('rs.GameCode IN (SELECT GameCode FROM U_Group_Game_Smt WHERE GroupId = '. $aUser['GroupId'].')');
        $this->db->where('rs.CalculateType', 'monthly');
        $this->db->where("STR_TO_DATE('" . date("Y-m", strtotime($CalculateDateFrom)) . "','%Y-%m') <= STR_TO_DATE(CalculateValue,'%Y-%m') ");
        $this->db->where("STR_TO_DATE('" . date("Y-m", strtotime($CalculateDateTo)) . "','%Y-%m') >= STR_TO_DATE(CalculateValue,'%Y-%m') ");

        $this->db->order_by('rs.CalculateValue asc, c.PromotionType asc, rs.GameCode asc');
        $query = $this->db->get();
        $rs = $query->result_array();

        $return = array();
        foreach ($rs as $value) {

            if(!$value['PromotionType']) {
                $value['PromotionType'] = 'NO TYPE';
            } else if($value['PromotionType'] === '[Phat code]') {
                $value['PromotionType'] = 'other';
            } else if($value['PromotionType'] === 'Ban code') {
                $value['PromotionType'] = 'convert';
            }

            $return[$value['CalculateValue']][$value['PromotionType']][$value['GameCode']][] = $value;
        }

        return $return;
    }

    

}

/* End of file promotion_model.php */
/* Location: ./application/models/promotion_model.php */