<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api {

	private $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
	}

	public function getGradeInfo($gameCode)
	{
		
		$url = $this->CI->config->item('api_url') . 'ub/config/grade?game_code=' . $gameCode;
		$key = 'getGradeInfo_' . $gameCode;
		$data = false;

		$this->CI->load->driver('cache');

		try {

			$string = $this->CI->cache->file->get($key);
			if(!$string) {
				$string = file_get_contents($url);
				$this->CI->cache->file->save($key , $string, 60*60*12);
			}

			$data = json_decode($string, true);

		} catch (Exception $e) {
			show_error('lỗi call API');
		}

		if($data) {
			$return = array();
			foreach ($data['data']['grades'] as $key => $aGrade) {
				list($cate, $type, $time) = explode('_', $key);

				foreach ($aGrade as $v) {
					$return[$cate][$time][$v['Id']] = $v;
				}				

			}
			return $return;
		}
	}

	public function getListPromotion($gameCode)
	{
		$url = $this->CI->config->item('api_url') . 'ub/config/promotion?game_code=' . $gameCode;
		$key = 'getListPromotion_' . $gameCode;
		$data = false;

		$this->CI->load->driver('cache');

		try {

			$string = $this->CI->cache->file->get($key);
			if(!$string) {
				$string = file_get_contents($url);
				$this->CI->cache->file->save($key , $string, 60*60);
			}

			$data = json_decode($string, true);

			return $data['data'];
		} catch (Exception $e) {
			show_error('lỗi call API');
		}
	}

}

/* End of file Api.php */
/* Location: ./application/libraries/Api.php */