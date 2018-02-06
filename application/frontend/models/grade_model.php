<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grade_model extends CI_Model {

	private $table = 'C_Grade';

	public function __construct()
	{
		parent::__construct();
		
	}

	public function listGrade($gameCode, $type = null)
	{
				
		$this->db->select('*');
		$this->db->from($this->table);	
		$this->db->where('GameCode', $gameCode);
		
		if ($type) {
			$this->db->where('GradeType', $type);
		}

		$this->db->order_by('Value', 'asc');

		$query = $this->db->get();
		$result = $query->result_array();

		return $result;
	}

	public function getGrade($idGrade, $gameCode, $type)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where('GameCode', $gameCode);
		$this->db->where('GradeType', $type);
		$this->db->where('IdGrade', $idGrade);
		$query = $this->db->get();
		return $query->row_array();
	}


	public function addGrade($data)
	{
		try {
			$data['CreatedDate'] = date('Y-m-d H:i:s');
			$this->db->insert($this->table, $data);
			return $data;
		} catch (Exception $e) {
			return false;
		}
	}

	public function editGrade($data, $gameCode, $type)
	{
		try {
			$where = array(
				'GameCode' => $gameCode, 
				'GradeType' => $type,
				'IdGrade' => $data['IdGrade']
			);
			$this->db->update($this->table, $data, $where);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public function putGrade($data, $gameCode, $type)
	{
		try {
			$gameCode = strtolower($gameCode);
			$data['CreatedBy'] = $this->session->userdata('user');

			if ($this->getGrade($data['IdGrade'], $gameCode, $type)) {
				// update
				$this->editGrade($data, $gameCode, $type);
			} else {
				// add
				$data['GameCode'] = $gameCode;
				$data['GradeType'] = $type;
				$this->addGrade($data);
			}
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public function delGrade($gameCode, $type)
	{
		try {
			$where = array(
				'GameCode' => $gameCode, 
				'GradeType' => $type
			);
			$this->db->delete($this->table, $where);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}

/* End of file game_model.php */
/* Location: ./application/models/game_model.php */