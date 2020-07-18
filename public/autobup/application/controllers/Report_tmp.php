<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_tmp extends CI_Controller {

	var $master_table = 'report_betlists_tmp';
	var $slave_table = 'report_betlists_tmp_bup_1';
	var $countFiles = '';
	var $process_rowlimit = 40000;
	var $process_perslave_table = 1000000;

	function __construct() {
		parent::__construct();
		# init set for long query
		date_default_timezone_set('Asia/Bangkok');
		ini_set("memory_limit", -1);
		set_time_limit(-1);
		ini_set('max_execution_time', 1000);
	}

	public function index()
	{
		# setup var
		$this->countFiles = dirname(__DIR__) . '/data/report_betlists_tmp.txt';
		$current_time = date('Y-m-d H:i:s');
		$setting_time = date('Y-m-d H:i:s', strtotime('-1 hour'));

		# set slave table
		$this->slave_table = $this->main->get_count_table($this->countFiles);

		# 1 Clone db and added field
		$this->db->query('CREATE TABLE IF NOT EXISTS ' . $this->slave_table . ' LIKE ' . $this->master_table . ';');
		$this->db->query('ALTER TABLE ' . $this->slave_table . ' ADD `tmp_id` INT(11) NOT NULL AFTER `id`;');

		# count table
		$countSlave = $this->db->count_all($this->slave_table);

		# start check
		if($countSlave > $this->process_perslave_table)
		{
			# set new slave table
			$this->slave_table = $this->main->regen_newtable($this->slave_table);
			$this->main->set_count_table($this->slave_table, $this->countFiles, $this->slave_table);
	
			$create_newTable = true;

			# create new table
			if($create_newTable) {
				$this->db->query('CREATE TABLE IF NOT EXISTS ' . $this->slave_table . ' LIKE ' . $this->master_table . ';');
				$this->db->query('ALTER TABLE ' . $this->slave_table . ' ADD `tmp_id` INT(11) NOT NULL AFTER `id`;');
			}
		}

		# loop data
		$getData = $this->main->get_tmp_data($this->master_table, $setting_time, $this->process_rowlimit);

		# start looping
		$inserted = 0;
		$deleted = 0;

		foreach($getData as $key => $row) {
			# setting up array for insert into slave table
			$arraySet = $row;
			$arraySet['tmp_id'] = $row['id'];

			# unset primary id
			unset($arraySet['id']);

			# set insert str
			$this->db->insert($this->slave_table, $arraySet);
			$inserted++;

			# delete query
			$this->db->delete($this->master_table, ['id' => $row['id']]);
			$deleted++;
		}

		echo 'Found match data: ' . number_format(count($getData)) . ' row(s) (Moved: ' . number_format($inserted) . ' row(s) /  Deleted: ' . number_format($deleted) . ' row(s))';

	}
}
