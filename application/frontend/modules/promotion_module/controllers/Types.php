<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Types extends CI_Controller {

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
		$data['list_promotion'] = $this->promotion->getDataPromoTypesByMonthly($gameCode, $from, $to);

		unset($_SESSION['promoTypes'][$data['key']]);

		$_SESSION['promoTypes'][$data['key']]['content'] = $this->load->view('types', $data, TRUE);
		$_SESSION['promoTypes'][$data['key']]['header'] = '_' . 'Tháng' . '_' . date('Y/m', strtotime($from)) . '_' . date('Y/m',strtotime($to));

	}

	public function delCache($key)
	{

		if (isset($_SESSION['promoTypes'][$key])) {
			unset($_SESSION['promoTypes'][$key]);
		}

		redirect(site_url('Promotion/types/'));
	}

}

/* End of file Index.php */
/* Location: ./application/controllers/Index.php */