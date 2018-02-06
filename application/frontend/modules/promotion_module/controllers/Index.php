<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('promo_model', 'promotion');
	}

	public function index($gameCode, $month, $year)
	{
		$data['key'] = $gameCode . '_' . $year . '_' . $month ;
		$data['gameCode'] = $gameCode;
		$data['list_promotion'] = $this->promotion->listPromotionByMonth($gameCode, $month, $year);
		unset($_SESSION['promotionIndex'][$data['key']]);

		$_SESSION['promotionIndex'][$data['key']]['content'] = $this->load->view('index', $data, TRUE);
		$_SESSION['promotionIndex'][$data['key']]['header'] = $gameCode . '_' . 'Tháng' . '_' . $year . '_' . $month ;

	}

	public function promotionDetail($gameCode, $promotionId)
	{
		$data['key'] = $gameCode . '_' . $promotionId;
		$data['aPromo'] = $this->promotion->getPromoInfo($gameCode, $promotionId);
		$data['aStatis'] = $this->promotion->getPromoStatisById($gameCode, $promotionId);
		$data['aStatisDetail'] = $this->promotion->getPromoStatisDetailById($gameCode, $promotionId);
		$data['payPromotionDaily'] = $this->promotion->getTransactionPaymentPromotion($gameCode, $promotionId);
		$data['userActived'] = $this->promotion->getUserActived($gameCode, $promotionId);
		$data['color'] = 'success';

		unset($_SESSION['promotionIndex'][$data['key']]);

		$_SESSION['promotionIndex'][$data['key']]['content'] = $this->load->view('promotion_detail', $data, TRUE);
		$_SESSION['promotionIndex'][$data['key']]['header'] = $gameCode . '_' . $data['aPromo']['PromotionName'];


	}

	public function delCache($key)
	{

		if (isset($_SESSION['promotionIndex'][$key])) {
			unset($_SESSION['promotionIndex'][$key]);
		}

		redirect(site_url('Promotion/index/'));
	}

}

/* End of file Index.php */
/* Location: ./application/controllers/Index.php */