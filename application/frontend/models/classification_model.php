<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classification_model extends CI_Model {

	private $table = 'C_Classification';

	public function __construct()
	{
		parent::__construct();
		
	}

	public function listClassification($gameCode)
	{
				
		$this->db->select('*');
		$this->db->from($this->table);	
		$this->db->where('GameCode', $gameCode);
				
		$this->db->order_by('Order', 'asc');

		$query = $this->db->get();
		$result = $query->result_array();

		return $result;
	}

	public function getClassification($idClassification, $gameCode)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where('GameCode', $gameCode);
		$this->db->where('IdClassification', $idClassification);
		$query = $this->db->get();
		return $query->row_array();
	}


	public function addClassification($data)
	{
		try {
			$data['CreatedDate'] = date('Y-m-d H:i:s');
			$this->db->insert($this->table, $data);
			return $data;
		} catch (Exception $e) {
			return false;
		}
	}

	public function editClassification($data, $gameCode)
	{
		try {
			$where = array(
				'GameCode' => $gameCode, 
				'IdClassification' => $data['IdClassification']
			);
			$this->db->update($this->table, $data, $where);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public function putClassification($data, $gameCode)
	{
		try {
			$gameCode = strtolower($gameCode);
			$data['CreatedBy'] = $this->session->userdata('user');

			if ($this->getClassification($data['IdClassification'], $gameCode)) {
				// update
				$this->editClassification($data, $gameCode);
			} else {
				// add
				$data['GameCode'] = $gameCode;
				$this->addClassification($data);
			}
			
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public function delClassification($gameCode)
	{
		try {
			$where = array(
				'GameCode' => $gameCode, 
			);
			$this->db->delete($this->table, $where);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}

/* End of file classification_model.php */
/* Location: ./application/models/classification_model.php */