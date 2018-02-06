<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sync_model extends MY_Model {
    public function __construct()
    {
        parent::__construct();
        $this->load->library ( 'util' );
    }

    public function syncToGs($result,$tableName)
    {
        $sharedb = $this->load->database('share_gs_db', TRUE);
        $sharedb->insert_batch($tableName,$result);
        $sharedb->flush_cache();
    }
    public function cleanGs($dates,$tableName)
    {
        $sharedb = $this->load->database('share_gs_db', TRUE);
        $sharedb -> where_in('report_date', $dates);
        $sharedb -> delete($tableName);
    }
    public function getChannelKPI($dates,$games)
    {
    	$this->db->select('report_date,game_code,source,group_id,kpi_id,kpi_value,calc_date');
    	$this->db->from("group_kpi_json");
   		$this->db->where_in("report_date", $dates);
        $this->db->where_in("game_code", $games);
    	$query = $this->db->get();
    	$result = $query->result_array();
    	return $result;
    }


}

/* End of file game_model.php */
/* Location: ./application/models/game_model.php */