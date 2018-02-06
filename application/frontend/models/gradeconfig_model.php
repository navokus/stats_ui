<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gradeconfig_model extends CI_Model {

	private $table = 'CF_GradeConfiguration';

	public function __construct()
	{
		parent::__construct();
		
	}

	public function listGradeConfig( $gameCode, $type, $idPeriodTime)
	{
				
		$this->db->select('*');
		$this->db->from($this->table);	
		$this->db->where('GameCode', $gameCode);
		$this->db->where('GradeType', $type);
		$this->db->where('IdPeriodTime', $idPeriodTime);
			
		$this->db->order_by('IdGradeConfiguration', 'asc');

		$query = $this->db->get();
		$result = $query->result_array();

		return $result;
	}

	public function getGradeConfig($idGrade, $gameCode, $type, $idPeriodTime)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where('GameCode', $gameCode);
		$this->db->where('GradeType', $type);
		$this->db->where('IdGrade', $idGrade);
		$this->db->where('IdPeriodTime', $idPeriodTime);
		$query = $this->db->get();
		return $query->row_array();
	}


	public function addGradeConfig($data)
	{
		try {
			$data['CreatedDate'] = date('Y-m-d H:i:s');
			$this->db->insert($this->table, $data);
			return $data;
		} catch (Exception $e) {
			return false;
		}
	}

	public function editGradeConfig($data, $gameCode, $type, $idPeriodTime)
	{
		try {
			$where = array(
				'GameCode' => $gameCode, 
				'GradeType' => $type,
				'IdPeriodTime' => $idPeriodTime,
				'IdGrade' => $data['IdGrade']
			);
			$this->db->update($this->table, $data, $where);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public function putGradeConfig($data, $gameCode, $type)
	{
		try {
			$gameCode = strtolower($gameCode);
			$data['CreatedBy'] = $this->session->userdata('user');

			if ($this->getGradeConfig($data['IdGrade'], $gameCode, $type, $data['IdPeriodTime'])) {
				// update
				$this->editGradeConfig($data, $gameCode, $type, $data['IdPeriodTime']);
			} else {
				// add
				$data['GameCode'] = $gameCode;
				$data['GradeType'] = $type;
				$this->addGradeConfig($data);
			}
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public function delGradeConfig($gameCode, $type)
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