<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Betlist extends MY_Controller {

	function __construct() {
		parent::__construct();
	}

	public function sync_betlist($limit = 1000) {
		$get_data = $this->main->get_reportbetlist($limit);
		$this->main->insert_betlist($get_data);
		echo 'Done';
	}

	public function update_ishalf() {
		$updated = $this->main->update_ishalf();
		echo 'Updated ' . $updated . ' row(s)';
	}

	public function sync_report_agent($mandate = '') {
		$date = $mandate ? date('Y-m-d', strtotime($mandate)) : date('Y-m-d');
		$report_data = $this->main->get_reportdata_agent($date);
		if($report_data) {
			$this->main->insert_tmpreport_agent($report_data);
			echo '(Agent) Done -> '. $date;
		} else {
			echo 'No data update';
		}
	}

	public function sync_report_member($mandate = '') {
		$date = $mandate ? date('Y-m-d', strtotime($mandate)) : date('Y-m-d');
		$report_data = $this->main->get_reportdata_member($date);
		if($report_data) {
			$this->main->insert_tmpreport_member($report_data);
			echo '(Member) Done -> '. $date;
		} else {
			echo 'No data update';
		}
	}

	public function sync_report_agent_fb($mandate = '') {
		$date = $mandate ? date('Y-m-d', strtotime($mandate)) : date('Y-m-d', strtotime('-1 day'));
		$report_data = $this->main->get_reportdata_agent($date);
		if($report_data) {
			$this->main->insert_tmpreport_agent($report_data);
			echo '(Agent) Done -> '. $date;
		} else {
			echo 'No data update';
		}
	}

	public function sync_report_member_fb($mandate = '') {
		$date = $mandate ? date('Y-m-d', strtotime($mandate)) : date('Y-m-d', strtotime('-1 day'));
		$report_data = $this->main->get_reportdata_member($date);
		if($report_data) {
			$this->main->insert_tmpreport_member($report_data);
			echo '(Member) Done -> '. $date;
		} else {
			echo 'No data update';
		}
	}

	public function sync_report_agent_bup($mandate = '') {
		$date = $mandate ? date('Y-m-d', strtotime($mandate)) : date('Y-m-d');
		$get_onlymonth = date('Y_m', strtotime($date));
		$tables_is = 'report_betlists_bup_' . $get_onlymonth;
		$report_data = $this->main->get_reportdata_agent_($date, $tables_is);
		if($report_data) {
			$this->main->insert_tmpreport_agent($report_data);
			echo '(Agent) Done -> '. $date;
		} else {
			echo 'No data update';
		}
	}

	public function sync_report_member_bup($mandate = '') {
		$date = $mandate ? date('Y-m-d', strtotime($mandate)) : date('Y-m-d');
		$get_onlymonth = date('Y_m', strtotime($date));
		$tables_is = 'report_betlists_bup_' . $get_onlymonth;
		$report_data = $this->main->get_reportdata_member_($date, $tables_is);
		if($report_data) {
			$this->main->insert_tmpreport_member($report_data);
			echo '(Member) Done -> '. $date;
		} else {
			echo 'No data update';
		}
	}

	public function index()
	{
		$postData = $_POST;
		$postData['select_date'] = ($_POST['select_date'] ? $_POST['select_date'] : $this->main->get_currentdate());
		$data['data_date'] = $postData['select_date'];
		$data['leauge_data'] = $this->main->get_leauge('', $postData['select_date']);
		$data['report_data'] = $this->main->get_report_data(1000, $postData);
		$this->load->view('report_betlist', $data);
	}

	public function ajax_table() {
		$postData = $_POST;
		$postData['select_date'] = ($_POST['select_date'] ? $_POST['select_date'] : $this->main->get_currentdate());
		$data['data_date'] = $postData['select_date'];
		$data['report_data'] = $this->main->get_report_data(1000, $postData);
		$this->load->view('report_betlist_ajax', $data);
	}

	public function betdata_info() {
		$match_id = $this->input->get('match_id');
		$leauge_id = $this->input->get('leauge_id');
		$game_id = $this->input->get('game_id');
		$isLive = $this->input->get('isLive');
		$isHalf = $this->input->get('isHalf');
		$betOption = $this->input->get('betOption');
		$winlostDate = $this->input->get('winlostDate');
		$liveScore = $this->input->get('liveScore');

		$getBetlistData = $this->main->get_betlistinfo($game_id, $leauge_id, $match_id, $isLive, $isHalf, $betOption, $winlostDate, $liveScore);
		// print_r($getBetlistData);
		// exit;
		$data['getBetlistData'] = $getBetlistData;
		$this->load->view('report_betlist_info', $data);
	}
}
