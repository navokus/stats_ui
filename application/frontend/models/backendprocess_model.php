<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 09/08/2016
 * Time: 14:10
 */

class Backendprocess_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function sum_revenue_by_os($report_date, $kpi_id)
    {
        $this->db->select("kpi_value, game_code");
        $this->db->from('os_kpi');
        $this->db->where("report_date", $report_date);
        $this->db->where("kpi_id", $kpi_id);

        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    public function insert_sum_revnue_by_os($data)
    {
        $this->db->insert("sum_revenue_by_os", $data);
    }

    public function delete_sum_revnue_by_os($data)
    {
        $this->db->delete("sum_revenue_by_os", $data);
    }

    public function collectMonthly()
    {

        $this->db->select("kpi_value, game_code");
        $this->db->from('game_kpi');
        $this->db->where("kpi_id", 30031);
        $this->db->where("report_date", '2017-01-05');


        $query = $this->db->get();
        $result = $query->result_array();
        foreach ($result as $value) {

            $this->db->where('game_code', $value['game_code']);
            $q = $this->db->get('tmp_gst_monthly');
            if ($q->num_rows() <= 0) {
                $this->db->set('game_code', $value['game_code']);

                $this->db->insert('tmp_gst_monthly');
            } else {

            }

            $data = array('acu' => $value['kpi_value']);
            $this->db->where('game_code', $value['game_code']);
            $this->db->update('tmp_gst_monthly', $data);
        }
        return $result;
    }

    public function collectMonthlyReport($date)
    {

        $this->clRev($date);
        $this->clAA1($date);
        $this->clA30($date);
        $this->clPU($date);
        $this->clNewUser($date);
        $this->clACU($date);
        $this->clPCU($date);
        $this->clNPU($date);
        $this->clPA1($date);
        $this->clUser($date);
    }
	private function insertOrUpdate($result, $kpi,$monthly){
		foreach ($result as $value) {
		
			$this->db->where('game_code', $value['game_code']);
			$this->db->where('source', $value['source']);
			$this->db->where('monthly', $monthly);
			$q = $this->db->get('tmp_gst_monthly');
			if ($q->num_rows() <= 0) {
				$this->db->set('game_code', $value['game_code']);
				$this->db->set('source', $value['source']);
				$this->db->set('monthly', $monthly);
				$this->db->insert('tmp_gst_monthly');
			}
			//$this->db->reset_query();
			$data = array($kpi => $value['kpi_value']);
			$this->db->where('game_code', $value['game_code']);
			$this->db->where('source', $value['source']);
			$this->db->where('monthly', $monthly);
			$this->db->update('tmp_gst_monthly', $data);
		}
	}
	
    private function clRev($date)
    {

        $start = date('Y-m-01', strtotime($date));

        $monthly = date('Y-m', strtotime($date));
        $this->db->select("kpi_value, game_code,source");
        $this->db->from('game_kpi');
        $this->db->where("kpi_id", 16031);
        //$this->db->where("source", 'ingame');
        //$this->db->where("game_code", 'stct');
        $this->db->where("report_date", $date);
        $query = $this->db->get();
        $result = $query->result_array();
        $this->insertOrUpdate($result,'revenue',$monthly);
        /*
        foreach ($result as $value) {

            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $q = $this->db->get('tmp_gst_monthly');
            if ($q->num_rows() <= 0) {
                $this->db->set('game_code', $value['game_code']);
                $this->db->set('monthly', $monthly);
                $this->db->insert('tmp_gst_monthly');
            }
            //$this->db->reset_query();
            $data = array('revenue' => $value['kpi_value']);
            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $this->db->update('tmp_gst_monthly', $data);
        }*/
        return $result;
    }

    private function clUser($date)
    {

        $start = date('Y-m-01', strtotime($date));

        $monthly = date('Y-m', strtotime($date));
        $this->db->select("kpi_value, game_code,source");
        $this->db->from('game_kpi');
        $this->db->where("kpi_id", 10031);
        //$this->db->where("game_code", 'stct');
        //$this->db->where("source", 'ingame');
        $this->db->where("report_date", $date);
        $query = $this->db->get();
        $result = $query->result_array();
        $this->insertOrUpdate($result,'users',$monthly);
        /*
        foreach ($result as $value) {

            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $q = $this->db->get('tmp_gst_monthly');
            if ($q->num_rows() <= 0) {
                $this->db->set('game_code', $value['game_code']);
                $this->db->set('monthly', $monthly);
                $this->db->insert('tmp_gst_monthly');
            }
            //$this->db->reset_query();
            $data = array('users' => $value['kpi_value']);
            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $this->db->update('tmp_gst_monthly', $data);
        }*/
        return $result;
    }

    private function clArppu($date)
    {

        $start = date('Y-m-01', strtotime($date));

        $monthly = date('Y-m', strtotime($date));
        $this->db->select("kpi_value, game_code,source");
        $this->db->from('game_kpi');
        $this->db->where("kpi_id", 16031);
        //$this->db->where("game_code", 'stct');
        //$this->db->where("source", 'ingame');
        $this->db->where("report_date", $date);
        $query = $this->db->get();
        $result = $query->result_array();
        $this->insertOrUpdate($result);
        /*
        foreach ($result as $value) {

            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $q = $this->db->get('tmp_gst_monthly');
            if ($q->num_rows() <= 0) {
                $this->db->set('game_code', $value['game_code']);
                $this->db->set('monthly', $monthly);
                $this->db->insert('tmp_gst_monthly');
            }
            //$this->db->reset_query();
            $data = array('revenue' => $value['kpi_value']);
            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $this->db->update('tmp_gst_monthly', $data);
        }*/
        
        return $result;
    }

    private function clPU($date)
    {

        $start = date('Y-m-01', strtotime($date));

        $monthly = date('Y-m', strtotime($date));
        $this->db->select("kpi_value, game_code,source");
        $this->db->from('game_kpi');
        $this->db->where("kpi_id", 15031);
        //$this->db->where("source", 'ingame');
        //$this->db->where("game_code", 'stct');
        $this->db->where("report_date", $date);
        $query = $this->db->get();
        $result = $query->result_array();
        $this->insertOrUpdate($result,'pu',$monthly);
        /*
        foreach ($result as $value) {

            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $q = $this->db->get('tmp_gst_monthly');
            if ($q->num_rows() <= 0) {
                $this->db->set('game_code', $value['game_code']);
                $this->db->set('monthly', $monthly);
                $this->db->insert('tmp_gst_monthly');
            }
            //$this->db->reset_query();
            $data = array('pu' => $value['kpi_value']);
            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $this->db->update('tmp_gst_monthly', $data);
        }*/
        return $result;
    }

    private function clNPU($date)
    {

        $start = date('Y-m-01', strtotime($date));

        $monthly = date('Y-m', strtotime($date));
        $this->db->select("kpi_value as kpi_value, game_code,source");
        $this->db->from('game_kpi');
        $this->db->where("kpi_id", 19031);
        //$this->db->where("source", 'ingame');
        //$this->db->where("source", 'ingame');
        $this->db->where("report_date", $date);
        $this->db->group_by(array("game_code", "source"));
        
        $query = $this->db->get();
        $result = $query->result_array();
        $this->insertOrUpdate($result,'npu',$monthly);
        /*
        foreach ($result as $value) {

            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $q = $this->db->get('tmp_gst_monthly');
            if ($q->num_rows() <= 0) {
                $this->db->set('game_code', $value['game_code']);
                $this->db->set('monthly', $monthly);
                $this->db->insert('tmp_gst_monthly');
            }
            //$this->db->reset_query();
            $data = array('npu' => $value['kpi_value']);
            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $this->db->update('tmp_gst_monthly', $data);
        }*/
        return $result;
    }

    private function clAA1($date)
    {

        $start = date('Y-m-01', strtotime($date));

        $monthly = date('Y-m', strtotime($date));
        $this->db->select("avg(kpi_value) as kpi_value, game_code,source");
        $this->db->from('game_kpi');
        $this->db->where("kpi_id", 10001);
        //$this->db->where("source", 'ingame');
        //$this->db->where("source", 'ingame');
        $this->db->where("report_date >=", $start);
        $this->db->where("report_date <=", $date);
        $this->db->group_by("game_code");
        $query = $this->db->get();
        $result = $query->result_array();
        $this->insertOrUpdate($result,'aa1',$monthly);
        /*
        foreach ($result as $value) {

            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $q = $this->db->get('tmp_gst_monthly');
            if ($q->num_rows() <= 0) {
                $this->db->set('game_code', $value['game_code']);
                $this->db->set('monthly', $monthly);
                $this->db->insert('tmp_gst_monthly');
            }
            //$this->db->reset_query();
            $data = array('aa1' => $value['kpi_value']);
            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $this->db->update('tmp_gst_monthly', $data);
        }*/
        return $result;
    }

    private function clPA1($date)
    {

        $start = date('Y-m-01', strtotime($date));

        $monthly = date('Y-m', strtotime($date));
        $this->db->select("max(kpi_value) as kpi_value, game_code,source");
        $this->db->from('game_kpi');
        $this->db->where("kpi_id", 10001);
        //$this->db->where("source", 'ingame');
        
        $this->db->where("report_date", $date);
        $this->db->group_by("game_code");
        $query = $this->db->get();
        $result = $query->result_array();
        $this->insertOrUpdate($result,'pa1',$monthly);
        /*
        foreach ($result as $value) {

            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $q = $this->db->get('tmp_gst_monthly');
            if ($q->num_rows() <= 0) {
                $this->db->set('game_code', $value['game_code']);
                $this->db->set('monthly', $monthly);
                $this->db->insert('tmp_gst_monthly');
            }
            //$this->db->reset_query();
            $data = array('pa1' => $value['kpi_value']);
            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $this->db->update('tmp_gst_monthly', $data);
        }*/
        return $result;
    }

    private function clA30($date)
    {

        $start = date('Y-m-01', strtotime($date));

        $monthly = date('Y-m', strtotime($date));
        $this->db->select("kpi_value, game_code,source");
        $this->db->from('game_kpi');
        $this->db->where("kpi_id", 10030);
        //$this->db->where("source", 'ingame');
        //$this->db->where("game_code", 'stct');
        $this->db->where("report_date", $date);
        $this->db->group_by("game_code");
        $query = $this->db->get();
        $result = $query->result_array();
        $this->insertOrUpdate($result,'a30',$monthly);
        /*
        foreach ($result as $value) {

            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $q = $this->db->get('tmp_gst_monthly');
            if ($q->num_rows() <= 0) {
                $this->db->set('game_code', $value['game_code']);
                $this->db->set('monthly', $monthly);
                $this->db->insert('tmp_gst_monthly');
            }
            //$this->db->reset_query();
            $data = array('a30' => $value['kpi_value']);
            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $this->db->update('tmp_gst_monthly', $data);
        }*/
        return $result;
    }

    private function clNewUser($date)
    {

        $start = date('Y-m-01', strtotime($date));

        $monthly = date('Y-m', strtotime($date));
        $this->db->select("kpi_value, game_code,source");
        $this->db->from('game_kpi');
        $this->db->where("kpi_id", 11031);
        //$this->db->where("source", 'ingame');
        //$this->db->where("game_code", 'stct');
        $this->db->where("report_date", $date);
        $this->db->group_by(array("game_code", "source"));
        $query = $this->db->get();
        $result = $query->result_array();
        $this->insertOrUpdate($result,'new_users',$monthly);
        /*
        foreach ($result as $value) {

            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $q = $this->db->get('tmp_gst_monthly');
            if ($q->num_rows() <= 0) {
                $this->db->set('game_code', $value['game_code']);
                $this->db->set('monthly', $monthly);
                $this->db->insert('tmp_gst_monthly');
            }
            //$this->db->reset_query();
            $data = array('new_users' => $value['kpi_value']);
            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $this->db->update('tmp_gst_monthly', $data);
        }
        */
        return $result;
    }

    private function clACU($date)
    {

        $start = date('Y-m-01', strtotime($date));

        $monthly = date('Y-m', strtotime($date));
        $this->db->select("avg(kpi_value) as kpi_value, game_code,source");
        $this->db->from('game_kpi');
        $this->db->where("kpi_id", 30001);
        //$this->db->where("source", 'ingame');
        //$this->db->where("game_code", 'stct');
        $this->db->where("report_date >=", $start);
        $this->db->where("report_date <=", $date);
        $this->db->group_by(array("game_code", "source"));
        $query = $this->db->get();
        $result = $query->result_array();
        $this->insertOrUpdate($result,'acu',$monthly);
        /*
        foreach ($result as $value) {

            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $q = $this->db->get('tmp_gst_monthly');
            if ($q->num_rows() <= 0) {
                $this->db->set('game_code', $value['game_code']);
                $this->db->set('monthly', $monthly);
                $this->db->insert('tmp_gst_monthly');
            }
            //$this->db->reset_query();
            $data = array('acu' => $value['kpi_value']);
            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $this->db->update('tmp_gst_monthly', $data);
        }
        */
        return $result;
    }

    private function clPCU($date)
    {

        $start = date('Y-m-01', strtotime($date));

        $monthly = date('Y-m', strtotime($date));
        $this->db->select("max(kpi_value) as kpi_value, game_code,source");
        $this->db->from('game_kpi');
        $this->db->where("kpi_id", 31001);
        //$this->db->where("source", 'ingame');
        //$this->db->where("game_code", 'stct');
        $this->db->where("report_date >=", $start);
        $this->db->where("report_date <=", $date);
        $this->db->group_by(array("game_code", "source"));
        $query = $this->db->get();
        $result = $query->result_array();
        $this->insertOrUpdate($result,'pcu',$monthly);
        /*
        foreach ($result as $value) {

            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $q = $this->db->get('tmp_gst_monthly');
            if ($q->num_rows() <= 0) {
                $this->db->set('game_code', $value['game_code']);
                $this->db->set('monthly', $monthly);
                $this->db->insert('tmp_gst_monthly');
            }
            //$this->db->reset_query();
            $data = array('pcu' => $value['kpi_value']);
            $this->db->where('game_code', $value['game_code']);
            $this->db->where('monthly', $monthly);
            $this->db->update('tmp_gst_monthly', $data);
        }*/
        return $result;
    }

    public function get_data_avg_peak($day_arr, $kpi_id_arr)
    {
        $kpi_ids_config = $this->getKpiIDs(null, $kpi_id_arr);
        $sql = "g.GameCode,g.region,g.GameName,gk.report_date, gk.kpi_id,kd.kpi_name,gk.source,gk.report_date, kd.group_id, gk.kpi_value, mrs.kpi_type";
        $this->db->select($sql, false);
        $this->db->from("game_kpi gk");
        $this->db->join("games g", 'gk.game_code = g.GameCode', 'left');
        $this->db->join('kpi_desc kd', 'gk.kpi_id = kd.kpi_id', 'left');
        $this->db->join('mt_report_source mrs', 'mrs.game_code = gk.game_code and mrs.group_id = kd.group_id and mrs.data_source = gk.source', 'left');
        $this->db->where("mrs.kpi_type", "game_kpi");
        $this->db->where("gk.game_code is not null");
        //$this->db->where("g.GameType2", 1);
        //$this->db->where("g.Status", 1);
        $this->db->where_in("gk.report_date", $day_arr);
        $this->db->where_in('gk.kpi_id', array_keys($kpi_ids_config));

        $query = $this->db->get();
        $result = $query->result_array();

        $return = array();
        for ($i = 0; $i < count($result); $i++) {
            $_log_date = $result[$i]['report_date'];
            $_kpi_id = $result[$i]['kpi_name'];
            $_kpi_value = $result[$i]['kpi_value'];
            $_game_code = $result[$i]['GameCode'];
            $_source = $result[$i]['source'];
            //$_region = $result[$i]['region'];
            //$_game_name = $result[$i]['GameName'];
            $return[$_game_code][$_kpi_id][$_log_date] = $_kpi_value;
            $return[$_game_code][$_kpi_id]['source'] = $_source;
            //$return[$_game_code]['game_name'] = $_game_name;
            //$return[$_game_code]['region'] = $_region;
        }
        return $return;
    }

    public function insert_avg_peak($data)
    {
        try {

            $this->db->delete("game_kpi", array(
                'report_date' => $data['report_date'],
                'game_code' => $data['game_code'],
                'source' => $data['source'],
                'kpi_id' => $data['kpi_id']
            ));

            $data['calc_date'] = date('Y-m-d H:i:s');
            $this->db->insert("game_kpi", $data);
            return $data;
        } catch (Exception $e) {
            return false;
        }

    }

}