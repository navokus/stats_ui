<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Promotion_model extends CI_Model {

	private $table = 'C_Promotion';
	
	public function __construct()
	{
		parent::__construct();

	}

	public function listPromotionByMonth($gameCode, $month)
	{

		$this->db->select('*');	
		$this->db->from($this->table);
		$this->db->where('GameCode', $gameCode);
		$this->db->where("(MONTH(FromDate) =  $month OR MONTH(ToDate) =  $month)");
		$query = $this->db->get();	

		$listPromotion = $query->result_array();

		foreach ($listPromotion as $key => $value) {
			$return = $this->getPromoStatisById($gameCode, $value['PromotionID']);
			$listPromotion[$key] = array_merge($listPromotion[$key], $return);
		}

		return $listPromotion;
	}

	public function getPromoStatisById($gameCode, $id)
	{
		$return = array();

		// get statis promotion current
		$this->db->select('*');	
		$this->db->from('RS_Promotion');
		$this->db->where('GameCode', $gameCode);
		$this->db->where('PromotionID', $id);
		$this->db->where('CalculateType', 'current');
		$this->db->order_by('CalculateDate desc, CreatedDate desc');
		$this->db->limit(1);
		$query = $this->db->get();	
		$data['current'] = $query->row_array();

		$return['AccountTotal'] = $data['current']['AccountTotal'];
		$return['RevenueTotal'] = $data['current']['RevenueTotal'];
		$return['PlayingTimeTotal'] = $data['current']['PlayingTimeTotal'];

		return $return;
	}

	// ===================================================

	public function listPromotionByGameCode($gameCode, $limit = 50)
	{
		$this->db->select('*');	
		$this->db->from($this->table);
		$this->db->where('GameCode', $gameCode);
		$this->db->order_by('PromotionID desc, PromotionName asc');
		$this->db->limit($limit);
		$query = $this->db->get();

		return $query->result_array();		
	}

	public function getPromoInfoById($id, $gameCode)
	{
		$this->db->select('*');	
		$this->db->from($this->table);
		$this->db->where('PromotionID', $id);
		$this->db->where('GameCode', $gameCode);
		$query = $this->db->get();	

		return $query->row_array();
	}

	public function addPromotion($data)
	{
		
		$this->db->insert($this->table, $data);

	}

	public function editPromotion($data, $promotionId, $gameCode)
	{
		$this->db->update($this->table, $data, array(
			'PromotionID' => $promotionId,
			'GameCode' => $gameCode
		));
	}

	public function deletePromotion($promotionId, $gameCode) {
		$this->db->delete($this->table, array(
			'PromotionID' => $promotionId,
			'GameCode' => $gameCode
		));
	}

}

/* End of file promotion_model.php */
/* Location: ./application/models/promotion_model.php */