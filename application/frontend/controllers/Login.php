<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Login extends CI_Controller {
	private $longSessionCookie ="_lsid";
	public function __construct() {
		$this->CI = & get_instance ();
		parent::__construct ();
		$this->load->model ( 'log_model' );
		$this->load->library ( 'curl' );
		$this->load->helper ( 'cookie' );
	}
	public function index() {
		$url_return = isset ( $_GET ['return'] ) ? $_GET ['return'] : "";
		if ($this->config->config ["use_stats_sso"]) {
			$sid = $this->input->cookie($this->longSessionCookie, false);
			if($sid!=NULL){
				return $this->checklogin($sid);
			}else{
				redirect ( $this->config->config ["stats_sso_domain"] . "ssoLogin?appId=" . $this->config->config ["stats_app_id"] );
			}
		} else {
			
			if ($this->session->userdata ( 'user' )) {
				$this->redirect_to ( $url_return );
			}
			
			if ($_POST) {
				$domain = $this->input->post ( 'domain' );
				$pass = $this->input->post ( 'pass' );
				
				if (! $domain) {
					$data ['message'] = "Vui lòng nhập account domain";
				}
				
				if (! $pass) {
					$data ['message'] = "Vui lòng nhập mật khẩu";
				}
				
				if ($domain && $pass) {
					
					$domain = str_replace ( '@vng.com.vn', '', strtolower ( $domain ) );
					
					// cal api ldap check login
					$check = $this->checkLDap ( $domain, $pass );
					if ($check === false)
						$check = $this->checkLDap ( $domain, $pass );
					
					if ($check == true) {
						$this->load->model ( 'user_model', 'user' );
						$aUser = $this->user->checkUserLogin ( $domain );
						if ($aUser) {
							// log login
							@$this->log_model->add ( $domain, 'login' );
							
							$this->session->set_userdata ( array (
									'user' => $domain 
							) );
							// redirect('dashboard');
							$this->redirect_to ( $url_return );
						}
						$data ['message'] = "Bạn không đủ quyền truy cập";
					} else {
						$data ['message'] = "Thông tin đăng nhập không đúng";
					}
				}
			}
			
			$this->load->view ( 'login/login', $data );
		}
	}
	public function logout() {
		delete_cookie ( $this->longSessionCookie );
		$this->session->unset_userdata ( 'user' );
		$this->session->sess_destroy ();
		// $this->session->unset_userdata();
		session_destroy ();
		
		//
		if ($this->config->config ["use_stats_sso"]) {
			redirect ( $this->config->config ["stats_sso_domain"] . "ssoLogin?appId=" . $this->config->config ["stats_app_id"] );
		} else {
			redirect ( 'Login' );
		}
	}
	private function checkLDap($domain, $pass) {
		$check = false;
		$con = ldap_connect ( $this->config->item ( 'LDAPS_URL' ), $this->config->item ( 'LDAPS_PORT' ) ) or die ( 'Could not connect LDAP server' );
		if ($con) {
			ldap_set_option ( $con, LDAP_OPT_PROTOCOL_VERSION, 3 );
			ldap_set_option ( $con, LDAP_OPT_REFERRALS, 0 );
			$rs = ldap_bind ( $con, $domain . '@vng.com.vn', $pass );
			if ($rs)
				$check = true;
			ldap_close ( $con );
		}
		return $check;
	}
	private function redirect_to($url) {
		if (strpos ( $url, "Login" ) !== false || $url == "") {
			redirect ( "dashboard2" );
		} else {
			redirect ( $url );
		}
	}
	private function getSession($sid) {
		$this->load->library ( 'rest', array (
				'server' => $this->config->config ["stats_sso_session_server"] 
		) );
		$this->rest->http_header ( 'X-App-Id', $this->config->config ["stats_app_id"] );
		$this->rest->http_header ( 'X-Api-Key', $this->config->config ["stats_app_key"] );
		$result = $this->rest->post ( 'api/checkSession', json_encode ( array (
				'id' => $sid 
		) ), 'application/json' );
		return $result;
	}
	private function setLongSession($sid){
		$sso_cookie = array (
				'name' => $this->longSessionCookie,
				'value' => $sid,
				'expire' => time () + (30 * 24 * 60 * 60),
				'path' => '/'
		);
		$this->input->set_cookie ( $sso_cookie );
	}
	
	public function checklogin($sid) {
		
		$result = $this->getSession ( $sid );
		if ($result->status == 200) {
			$this->load->model ( 'user_model', 'user' );
			$user = $this->user->checkUserLogin ( $result->data->uid );
			if ($user) {
				$this->log_model->add ( $domain, 'login' );
				$this->session->set_userdata ( array (
						'sid' => $sid 
				) );
				$this->session->set_userdata ( array (
						'user' => $result->data->uid 
				) );
				$this->setLongSession($sid);
				redirect ( 'dashboard2' );
			} else {
				log_message ( 'info', 'user id:' . $result->data->uid . ' does not exist' );
				redirect ( $this->config->config ["stats_sso_domain"] . "ssoLogin?appId=" . $this->config->config ["stats_app_id"] );
			}
		} else {
			log_message ( 'info', 'stats sso result:' . $result->status . $result->messsage );
			redirect ( $this->config->config ["stats_sso_domain"] . "ssoLogin?appId=" . $this->config->config ["stats_app_id"] );
		}
	}
	public function test() {
		$this->load->helper ( 'cookie' );
		$ci = $this->input->cookie ( 'ci_session', false );
		$sso_cookie = array (
				'name' => '_sso_id',
				'value' => '1222222',
				'expire' => time () + (30 * 24 * 60 * 60),
				'path' => '/' 
		);
		$this->input->set_cookie ( $sso_cookie );
		$this->output->set_output ( json_encode ( $ci ) );
		$this->output->set_content_type ( 'application/json' );
		
		return;
	}
	public function test2() {
		$ci = $this->input->cookie ( '_sso_id', false );
		$this->output->set_output ( json_encode ( $ci ) );
		$this->output->set_content_type ( 'application/json' );
		
		return;
	}
}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */
