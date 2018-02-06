<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistic extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
//		$this->output->enable_profiler(TRUE);
		$this->load->model('promo_model', 'promotion');
	}

	public function index($gameCode, $to, $from)
	{
		$data['key'] = $gameCode . '_' . $from . '_' . $to ;
		$data['gameCode'] = $gameCode;
		$data['list_promotion'] = $this->promotion->getDataPromoStatisByMonthly($gameCode, $from, $to);

		unset($_SESSION['promoStatis'][$data['key']]);

		$_SESSION['promoStatis'][$data['key']]['content'] = $this->load->view('statistic', $data, TRUE);
		$_SESSION['promoStatis'][$data['key']]['header'] = '_' . 'Tháng' . '_' . date('Y-m', strtotime($from)) . '_' . date('Y-m',strtotime($to));

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

		unset($_SESSION['promoStatis'][$data['key']]);

		$_SESSION['promoStatis'][$data['key']]['content'] = $this->load->view('promotion_detail', $data, TRUE);
		$_SESSION['promoStatis'][$data['key']]['header'] = $gameCode . '_' . $data['aPromo']['PromotionName'];

	}

	public function delCache($key)
	{

		if (isset($_SESSION['promoStatis'][$key])) {
			unset($_SESSION['promoStatis'][$key]);
		}

		redirect(site_url('Promotion/statistic/'));
	}

}

/* End of file Index.php */
/* Location: ./application/controllers/Index.php */