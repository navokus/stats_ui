<?php

/**
 * Created by IntelliJ IDEA.
 * User: lamnt6
 * Date: 10/10/2017
 * Time: 14:47
 */
class SdkController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->source="sdk";
    }

    public function index()
    {

    }

    public function sdk($groupId)
    {
        $this->source="sdk";
        var_dump($groupId);
        redirect(site_url($groupId));
    }
}