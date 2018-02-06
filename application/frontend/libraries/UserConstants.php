<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 14/04/2016
 * Time: 09:59
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserConstants
{

    private $CI;

    public static $SELECTION_DAY = "4";
    public static $SELECTION_MONTH = "5";
    public static $SELECTION_YEAR = "6";

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function get_default_day_number(){
        return array(
            "4"=>31,
            "5"=>12,
            "6"=>12,
            "7"=>1
        );
    }


}