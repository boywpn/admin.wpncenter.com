<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_betlist_tmp extends CI_Controller {

	var $master_table = 'report_betlists_tmp';
	var $slave_table = 'report_betlists_tmp_bup_';
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
        # Start API Response time
        $this->benchmark->mark('cron_start');
	}

	public function index()
	{
		# set time
		$current_time = date('Y-m-d H:i:s');
		$setting_time = date('Y-m-d H:i:s', strtotime('-1 hour'));

		# parameter
		$game_id = $this->input->get('game_id');

		# loop data
		$getData = $this->main->get_tmp_data($this->master_table, $setting_time, $game_id, $this->process_rowlimit);
		// print_r($this->db->last_query());

		if(count($getData) > 0) {
			# start looping
			$inserted = 0;
			$deleted = 0;

			foreach($getData as $key => $row) {
				# setting up array for insert into slave table
				$arraySet = $row;
				$arraySet['tmp_id'] = $row['id'];

				# get update date
				$date_table = date('Y_m', strtotime($row['updated_at']));

				# build name slave_table
				$btable = $this->slave_table . $date_table;

				# unset primary id
				unset($arraySet['id']);

				if (!$this->db->table_exists($btable))
				{
					echo 'No table found';
					$this->db->query('CREATE TABLE IF NOT EXISTS ' . $btable . ' LIKE ' . $this->master_table . ';');
					$this->db->query('ALTER TABLE ' . $btable . ' ADD `tmp_id` INT(11) NOT NULL AFTER `id`;');
					echo ' / Table ' . $btable . ' created.';
				}

				# set insert str
				$this->db->insert($btable, $arraySet);
				$inserted++;

				# delete query
				$this->db->delete($this->master_table, ['id' => $row['id']]);
				$deleted++;
			}

			echo 'Found match data: ' . number_format(count($getData)) . ' row(s) / Moved: ' . number_format($inserted) . ' row(s) / Deleted: ' . number_format($deleted) . ' row(s)';
			echo '<hr />';
			echo 'Query time : ' . $this->benchmark->elapsed_time('cron_start', 'cron_end') . ' sec(s)';
		} else {
			echo 'No ' . $this->master_table . ' process.';
		}
	}
}
