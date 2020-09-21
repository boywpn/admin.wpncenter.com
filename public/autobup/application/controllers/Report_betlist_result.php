<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_betlist_result extends CI_Controller {

	var $master_table = 'report_betlists_results';
	var $slave_table = 'report_betlists_results_bup_2020_06';
	var $countFiles = '';
	var $process_rowlimit = 500;
	var $curYear;
	var $curMonth;

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
		$this->countFiles = dirname(__DIR__) . '/data/report_betlists_results.txt';

		# set slave table
		$this->slave_table = $this->main->get_count_table($this->countFiles);

		# get current time
		$currentTime = $this->main->get_tablecurdate_last($this->slave_table);
		$this->curYear = date('Y', strtotime($currentTime));
		$this->curMonth = date('m', strtotime($currentTime));
		
		$fullCrTime = date('Y-m-00 00:00:00', strtotime($currentTime));
		$nextCurrTime = date('Y-m', strtotime('+1 month', strtotime($currentTime)));
		$fullnextCrTime = date('Y-m-00 00:00:00', strtotime($nextCurrTime));
		
		# current datemonth
		$todayMonth = date('Y-m');

		# disable programe if staying on current year / month
		if($currentTime == $todayMonth) {
			echo 'Close Process (Current Time) - (' . $currentTime . '/' . $todayMonth . ')';
		} else {
			# 1 Clone db and added field
			$this->db->query('CREATE TABLE IF NOT EXISTS ' . $this->slave_table . ' LIKE ' . $this->master_table . ';');
			$this->db->query('ALTER TABLE ' . $this->slave_table . ' ADD `tmp_id` INT(11) NOT NULL AFTER `id`;');

			# count table
// 			$countSlave = $this->db->select('id')
// 							->from($this->master_table)
// 							->where('YEAR(created_at)', $this->curYear)
// 							->where('MONTH(created_at)', $this->curMonth)
// 							->count_all_results();
// 			$countSlave = $this->db->query('SELECT COUNT(id) as numrows FROM ' . $this->master_table . ' WHERE `created_at` BETWEEN \'' . $fullCrTime . '\' AND \'' . $fullnextCrTime . '\'')->row_array();
// 			$countSlave = $countSlave['numrows'];

			# loop data
			$getData = $this->main->get_blrtmp_data($this->master_table, $fullCrTime, $fullnextCrTime, $this->process_rowlimit);

			# start check
			if(count($getData) == 0)
			{
				# set new slave table
        		$newCurrTime = date('Y-m', strtotime('+1 month', strtotime($currentTime)));
				$this->slave_table = $this->main->regen_newtablebetlist($this->slave_table, str_replace('-','_', $newCurrTime), 4);
				$this->main->set_count_table($this->slave_table, $this->countFiles, $this->slave_table);

				// $this->curYear = date('Y', strtotime($newCurrTime));
				// $this->curMonth = date('m', strtotime($newCurrTime));

				$create_newTable = true;

				# create new table
				if($create_newTable) {
					$this->db->query('CREATE TABLE IF NOT EXISTS ' . $this->slave_table . ' LIKE ' . $this->master_table . ';');
					$this->db->query('ALTER TABLE ' . $this->slave_table . ' ADD `tmp_id` INT(11) NOT NULL AFTER `id`;');
				}
			} else {
				# loop data
				// $getData = $this->main->get_blrtmp_data($this->master_table, $this->curYear, $this->curMonth, $this->process_rowlimit);

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

				echo 'Found match data: ' . number_format(count($getData)) . ' row(s) (Moved: ' . number_format($inserted) . ' row(s) /  Deleted: ' . number_format($deleted) . ' row(s)) / Work on table : ' . $this->slave_table;
			}
			
		}

	}
}
