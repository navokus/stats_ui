<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rptmobileos_model extends MY_Model {
    public function __construct()
    {
        parent::__construct();
        $this->load->library ( 'util' );
    }

    public function insert($data)
    {
        try {
            $this->db->insert("rpt_mobile_os", $data);
            return true;
        } catch (Exception $e) {
            return false;
        }

    }
    public function getMetricMobileOs($metric)
    {
    	
    
    	$this->db->select('sum(value) as value,os,report_date');
    	$this->db->from("rpt_mobile_os");
    	$this->db->where("kpi", $metric);
    	$this->db->where_in('os', array('ios','android','other'));
    	$this->db->group_by(array("os", "report_date"));
    	$query = $this->db->get();
    	$result = $query->result_array();
    
    	return $result;
    }
}
