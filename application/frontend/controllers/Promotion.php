<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Promotion extends MY_Controller {

    public function __construct() {
        parent::__construct();
//        $this->output->enable_profiler(TRUE);
        $this->load->model('game_model', 'game');
        $this->load->model('promotion_model', 'promotion');
        $this->load->library('util');
    }

    public function index() {
        $return['content'] = '';
        $return['aGames'] = $this->game->listGames();

        if ($_POST) {
            $_SESSION['promo'] = $_POST;

        }

        $month = $this->input->post('month');

        if (!$month) {
            $year = date('Y');
            $month = date('m');
        } else {
            list($year, $month) = explode('-', date('Y-m', strtotime($month)));
        }
        $gameCode = $this->session->userdata('default_game');

        if ($_POST || !$_SESSION['promotionIndex']) {
            $this->updateListPromotions($gameCode);
            $this->load->module('promotion_module/Index/index', array($gameCode, $month, $year));
        }

        if ($_GET['view'] == 1 && count(explode('_', $this->uri->segment(3))) == 2) {
            list($gameCode, $promotionId) = explode('_', $this->uri->segment(3));

            // update target
            if ($this->input->post('name')) {

                $name = $this->input->post('name');
                $target = $this->input->post('target');
                $result = $this->input->post('result');
                $description = $this->input->post('description');


                $aTarget = array();
                foreach ($name as $key => $value) {

                    if (empty($name[$key]) === FALSE && empty($target[$key]) === FALSE) {

                        $aTarget[] = array(
                            'name' => $name[$key],
                            'target' => $target[$key],
                            'result' => $result[$key],
                            'description' => $description[$key],
                        );
                    }
                }

                // update db target
                $this->promotion->editPromotion(array('Target' => json_encode($aTarget)), $promotionId, $gameCode);
            }

            $this->load->module('promotion_module/Index/promotionDetail', array($gameCode, $promotionId));
        }

        if (isset($_SESSION['promotionIndex'])) {

            $aPromotion = array_reverse($_SESSION['promotionIndex']);

            $i = 1;
            foreach ($aPromotion as $key => $value) {
                if ($this->uri->segment(3) == $key || (!$this->uri->segment(3) && $i == 1)) {
                    $return['content'] = $value['content'];
                }
                $i++;
            }
        }

        // call module promotion index


        $return['post'] = $_SESSION['promo'];
        $return['optionsMonth'] = $this->util->listOptionsMonth();

        $this->_template['content'] = $this->load->view('promotion/index', $return, TRUE);
        $this->load->view('master_page', $this->_template);

    }

    public function deleteTarget($gameCode, $promotionId, $offset) {

        $aPromo = $this->promotion->getPromoInfoById($promotionId, $gameCode);

        $target = json_decode($aPromo['Target'], TRUE);

        $aTarget = array();
        foreach ($target as $key => $value) {

            if($key != $offset){

                $aTarget[] = $value;

            }
        }

        // update db target
        $this->promotion->editPromotion(array('Target' => json_encode($aTarget)), $promotionId, $gameCode);

        redirect('Promotion/index/'. $gameCode .'_'. $promotionId .'?view=1');

    }

    public function updateListPromotions($gameCode = null) {
        // delete promotion
        $aPromotionDelete['bc'] = array(10, 42, 72, 136, 2826);
        $aPromotionDelete['msg'] = array(9, 67, 2778, 2832);
        $aPromotionDelete['zg'] = array(4, 6, 8, 2497);
        $aPromotionDelete['wjx'] = array(2759);
        $aPromotionDelete['ylzt'] = array(2676);
        $aPromotionDelete['fs'] = array(2765);
        $aPromotionDelete['ttl3d'] = array(2811, 2813);
        $aPromotionDelete['jx1'] = array(24513);

        // update promotion type

        $sContent = '
9K,68,New Register
9K,2648,Other
9K,2735,Up Level
9K,2739,New Register
9K,2681,New Register
9K,2721,New Register
9K,2717,New Register
9K,2730,Other
9K,2736,Convert
9K,2762,Other
9K,2775,Other
9K,2776,Convert
9K,2780,Up Level
CT,2792,New Register
CT,2796,Up Level
CT,2809,Convert
CT,15,Convert
CT,49,New Register
CT,2812,Up Level
BC,2729,Other
BC,2737,Convert
BC,2696,Convert
BC,2718,New Register
BC,2725,Other
BC,2723,Other
BC,2728,Other
BC,2757,New Register
BC,2743,Up Level
BC,2683,New Register
BC,2768,New Register
BC,2675,Other
BC,2678,Other
BC,2781,New Register
BC,2826,New Register
BOOM,2753,Convert
MU,2830,New Register
MU,2834,New Register
CSM,2740,Other
CSM,2770,Other
CTC,116,Convert
DBTH,2793,Convert
DBTH,73,Convert
FS,2690,Convert
FS,2765,Convert
FV,2831,Convert
FV,38,Convert
JX1,2685,Convert
JX1,23,Return User
JX1,2451,Other
JX1,74,Other
JX1CTC,2814,Up Level
JX1CTC,19,Up Level
JX1CTC,2777,New Register
JX1CTC,43,New Register
JX1F,2727,Convert
JX1F,2726,Convert
JX1F,2769,Other
JX1F,1825,Convert
JX2,2673,Convert
JX2,1598,New Register
JX2,2722,Other
JX2,34,New Register
JX3,2557,New Register
JX3,2760,Convert
JX3,2682,Convert
KS,2827,Other
TTL3D,2695,Other
TTL3D,2686,Convert
TTL3D,1612,Convert
TTL3D,2734,Convert
TTL3D,1599,Up Level
TTL3D,2786,Convert
TTL3D,2811,Other
TTL3D,2813,Convert
TTL3D,2835,Other
TTL3D,2720,Up Level
TTL3D,2731,Other
TTL3D,2732,Other
TTL3D,2741,Other
TTL3D,2742,Other
TTL3D,2756,Other
TTL3D,2771,Other
TTL3D,2772,Other
TTL3D,2779,Convert
TTL3D,2787,Other
TTL3D,2815,Other
TTL3D,2816,Other
TTL3D,2817,Other
TTL3D,2818,Other
TTL3D,2819,Other
TTL3D,2820,Other
TTL3D,2821,Other
TTL3D,2822,Other
TTL3D,2823,Other
TTL3D,2824,Other
TTL3D,52,Convert
TTL3D,23,Other
TTL3D,1605,Other
TTL3D,64,New Register
WJX2,2758,Up Level
WJX2,2748,New Register
WJX2,2750,Other
WJX2,2807,Up Level
WJX2,2808,Convert
WJX2,91,Other
WJX2,20,Up Level
WJX2,45,Up Level
WJX2,46,Up Level
WJX2,29,Convert
WJX2,50,Other
KUPAO,2783,Up Level
LAIO,2774,Other
MSG,5,Other
MSG,2752,Convert
MSG,2679,Convert
MSG,2687,Other
MSG,2733,Other
MSG,2778,Convert
MSG,2795,Convert
MSG,2832,Convert
MSG,48,Convert
MSG,2716,New Register
MSG,2738,Other
MSG,2789,Other
NKVN,2684,Convert
NKVN,2724,Convert
NKVN,1565,New Register
NKVN,2764,Up Level
NLVN,70,Convert
VLCM2,2719,New Register
VLPLUS,2833,Up Level
WH2,2744,Other
WH2,2745,Other
WH2,2746,Other
WH2,2747,Other
WH2,2755,Other
WH2,2763,Convert
WH2,2773,Other
WH2,2761,New Register
WH2,2784,Convert
WH2,2785,Convert
WH2,1613,Convert
WH2,1614,Convert
WH2,70,Convert
WH2,1613,Convert
WH2,1614,Convert
WH2,71,New Register
WJX,35,Convert
WJX,2759,Up Level
WJX,2788,New Register
WJX,90,New Register
WJX,44,New Register
WTVN,2666,Convert
WTVN,1612,Other
WTVN,2791,Convert
WTVN,65,Convert
YLZT,2624,Other
YLZT,2751,Other
YLZT,2676,Up Level
YLZT,2790,Convert
YLZT,2825,Up Level
YLZT,2689,Other
ZG,2677,Convert
ZG,2671,New Register
ZG,2766,Other
ZG,2674,New Register
ZG,2567,Other
ZG,2497,Up Level
ZG,2692,Other
ZG,2693,Other
ZG,2694,Other
ZG,2782,Other
ZG,2680,Other
ZG,2688,Other
ZG,2691,Other
ZG,2697,Other
ZG,2698,Other
ZG,2699,Other
ZG,2700,Other
ZG,2701,Other
ZG,2702,Other
ZG,2703,Other
ZG,2704,Other
ZG,2705,Other
ZG,2706,Other
ZG,2707,Other
ZG,2708,Other
ZG,2709,Other
ZG,2710,Other
ZG,2711,Other
ZG,2712,Other
ZG,2713,Other
ZG,2714,Other
ZG,2715,Other
ZG,2797,Other
ZG,2798,Other
ZG,2799,Other
ZG,2800,Other
ZG,2801,Other
ZG,2802,Other
ZG,2803,Other
ZG,2804,Other
ZG,2805,Other
ZG,2806,Other
ZG,457,Other
ZS,2767,Convert
ZS,2828,Other
9K,159,New Register
BC,159,New Register
BNB,159,New Register
CT,159,New Register
MSG,159,New Register
MU,159,New Register
WTVN,159,New Register
ZG,159,New Register
ZS,159,New Register
ZS,159,New Register
JX1CTC,50,Up Level
WTVN,73,New Register
BC2,75,New Register
DBTH,78,Convert
JX3,1609,Convert
BNB,2753,Convert
JX1F,42,Other
WH2,1611,Convert
WH2,1610,New Register
WH2,1608,Other
WJX2,41,New Register
WTVN,2446,Other
ZG,1926,Other
ZS,62,Other
9K,71,Up Level
JX1,32,Up Level
JX1F,39,New Register
JX1F,38,Convert
JX2,1606,Other
TTL3D,1607,Convert
WH2,1608,New Register
9K,72,New Register
FS,1604,Convert
JX1,37,Convert
JX3,1602,Convert
JX3,1585,Up Level
MSG,61,Convert
NKVN,1569,Convert
WJX2,40,New Register
ZG,1931,Other
JX2,1601,Convert
JX1,50,Up Level
JX2,50,Up Level
WJX,50,Up Level
MU,46,New Register
9K,66,New Register
BC,66,New Register
CT,66,New Register
BNB,66,New Register
MSG,66,New Register
WTVN,66,New Register
ZG,66,New Register
ZS,66,New Register
9K,93,New Register
BC,93,New Register
CT,93,New Register
BNB,93,New Register
MSG,93,New Register
WTVN,93,New Register
ZG,93,New Register
ZS,93,New Register
BC2,93,New Register
9K,73,New Register
BC,73,New Register
CT,73,New Register
BNB,73,New Register
MSG,73,New Register
WTVN,73,New Register
ZG,73,New Register
ZS,73,New Register
BC2,73,New Register
BC2,136,Convert
LTVN,373,New Register
MU,66,New Register
';

        $aRows = explode("\n", $sContent);

        foreach ($aRows as $row) {
            if ($row) {
                $row = explode(',', $row);
                $row[0] = strtolower($row[0]);
                $aPromotionUpdateType[$row[0]]['id'][] = $row[1];
                $aPromotionUpdateType[$row[0]]['type'][] = str_replace(' ', '-', strtolower($row[2]));
            }
        }

        $aGames = $this->game->listGames();

        foreach ($aGames as $value) :

            $gameCode = $value['GameCode'];

            // sync data from api to database
            $this->load->driver('cache');
            $keyFile = 'cacheGetListPromotion_' . $gameCode;

            $string = $this->cache->file->get($keyFile);

            // $string = "";

            if (!$string) {
                $this->load->library('api');
                $aPromotions = $this->api->getListPromotion($gameCode);

                if ($aPromotions) {
                    foreach ($aPromotions as $value) {

                        $check = $this->promotion->getPromoInfoById($value['promotionId'], $gameCode);

                        if (!in_array($value['promotionId'], $aPromotionDelete[$gameCode])) {

                            $param = array(
                                'PromotionID' => $value['promotionId'],
                                'PromotionName' => $value['promotionName'],
                                'PromotionType' => $value['event'],
                                'GameCode' => $value['gameCode'],
                                'FromDate' => $value['startDate'],
                                'ToDate' => $value['endDate'],
                                'CreatedBy' => 'system',
                                'CreatedDate' => date('Y-m-d H:i:s'),
                            );

                            if (in_array($value['promotionId'], $aPromotionUpdateType[$gameCode]['id'])) {

                                foreach ($aPromotionUpdateType[$gameCode]['id'] as $key => $promoId) {

                                    if ($promoId == $value['promotionId']) {
                                        $param['PromotionType'] = $aPromotionUpdateType[$gameCode]['type'][$key];
                                        break;
                                    }
                                }
                            }


                            if ($check) {
                                unset($param['CreatedBy']);
                                unset($param['CreatedDate']);
                                $this->promotion->editPromotion($param, $value['promotionId'], $gameCode);
                            } else {
                                $this->promotion->addPromotion($param);
                            }
                        }
                    }
                }

                // remove promotion
                foreach ($aPromotionDelete[$gameCode] as $promotionId) {
                    $this->promotion->deletePromotion($promotionId, $gameCode);
                }


                $this->cache->file->save($keyFile, '1', 60 * 60 * 3);
            }
        endforeach;
    }

    // promotion statistic
    public function statistic() {
        $return['content'] = '';
        $return['aGames'] = $this->game->listGames();

        // check game code permission
        $check = false;
        foreach ($return['aGames'] as $value) {
            if ($value['GameCode'] == $this->session->userdata('default_game')) {
                $check = true;
                break;
            }
        }

        if ($check == false) {
            show_error('You do not permission!!!');
        }

        if ($_POST) {
            $_SESSION['promo'] = $_POST;
        }

        $month = $this->input->post('month');

        if (!$month) {
            $month[1] = date('Y-m-d');
            $month[2] = date('Y-m-d');
        }
        $gameCode = $this->session->userdata('default_game');

        if ($_POST || !$_SESSION['promoStatis']) {
            $this->updateListPromotions($gameCode);
            $this->load->module('promotion_module/Statistic/index', array($gameCode, $month[1], $month[2]));
        }


        if (isset($_SESSION['promoStatis'])) {

            $aPromotion = array_reverse($_SESSION['promoStatis']);

            $i = 1;
            foreach ($aPromotion as $key => $value) {
                if ($this->uri->segment(3) == $key || (!$this->uri->segment(3) && $i == 1)) {
                    $return['content'] = $value['content'];
                }
                $i++;
            }
        }

        $return['post'] = $_SESSION['promo'];
        $return['optionsMonth'] = $this->util->listOptionsMonth();

        $this->_template['content'] = $this->load->view('promotion/statistic', $return, TRUE);
        $this->load->view('master_page', $this->_template);

    }

    // promotion types
    public function types() {
        $return['content'] = '';
        $return['aGames'] = $this->game->listGames();

        if ($_POST) {
            $_SESSION['promo'] = $_POST;
        }

        $month = $this->input->post('month');

        if (!$month) {
            $month[1] = date('Y-m-d');
            $month[2] = date('Y-m-d');
        }
        $gameCode = $this->session->userdata('default_game');

        if ($_POST || !$_SESSION['promoTypes']) {
            $this->updateListPromotions();
            $this->load->module('promotion_module/Types/index', array($gameCode, $month[1], $month[2]));
        }


        if (isset($_SESSION['promoTypes'])) {

            $aPromotion = array_reverse($_SESSION['promoTypes']);

            $i = 1;
            foreach ($aPromotion as $key => $value) {
                if ($this->uri->segment(3) == $key || (!$this->uri->segment(3) && $i == 1)) {
                    $return['content'] = $value['content'];
                }
                $i++;
            }
        }

        $return['post'] = $_SESSION['promo'];
        $return['optionsMonth'] = $this->util->listOptionsMonth();

        $this->_template['content'] = $this->load->view('promotion/types', $return, TRUE);
        $this->load->view('master_page', $this->_template);
    }
}

/* End of file Promotion.php */
/* Location: ./application/controllers/Promotion.php */