<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends MY_Model {

    function query_statement($dbfield,$table) {
        $count = 0;
        $fields = '';
    
        foreach($dbfield as $col => $val) {
            if ($count++ != 0) $fields .= ', ';
            $col = addslashes($col);
            $val = addslashes($val);
            $fields .= "`$col` = '$val'";
        }
        $query = "INSERT INTO `$table` SET $fields;";
        return $query;
    
    }
    
    function get_onlytable($table_name = '') {
        $extract_stb = explode('_', $table_name);
        $extract_stb = array_slice($extract_stb, 0, -1);
        return implode('_', $extract_stb);
    }
    
    function regen_newtable($table_name = '', $last_key = 4) {
        $extract_stb = explode('_', $table_name);
        $newtable_num = (int) ($extract_stb[$last_key] + 1);
        $extract_stb = array_slice($extract_stb, 0, -1);
        array_push($extract_stb, $newtable_num);
        $new_slavetb = implode('_', $extract_stb);
        return $new_slavetb;
    }

    function regen_newtablebetlist($table_name = '', $newCurrTime = '') {
        $extract_stb = explode('_', $table_name);
        $extract_stb = array_slice($extract_stb, 0, 3);
        $new_slavetb = implode('_', $extract_stb);
        return $new_slavetb . '_' . $newCurrTime;
    }

    function get_tablecurdate($table_name = '') {
        $extract_stb = explode('_', $table_name);
        return date('Y-m', strtotime($extract_stb[3] . '-' . $extract_stb[4]));
    }
    
    function get_count_table($countFile = '') {
        $fp = fopen($countFile, 'r') or die("Unable to open file!");
        $content = fread($fp, filesize($countFile));
        fclose($fp);
        return $content;
    }
    
    function set_count_table($num = '', $countFile = '', $slave_table = '') {
        $fp = fopen($countFile, 'w+') or die ('Failed to created count file');
        fwrite($fp, $num ? $num : $slave_table);
        fclose($fp);
        return true;
    }

    function get_tmp_data($table = '', $settime = '', $limit = '') {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where('`betlist_id` IS NOT NULL');
        $this->db->where('`status` IS NOT NULL');
        $this->db->where('`updated_at` < \'' . $settime . '\'');
        if($limit) {
            $this->db->limit($limit);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_bltmp_data($table = '', $year = '', $month = '', $limit = '') {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where('YEAR(work_time)', $year);
        $this->db->where('MONTH(work_time)', $month);
        if($limit) {
            $this->db->limit($limit);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_reportbetlist($limit = 1000, $game_id = 24, $game_type_id = 55, $bet_type = 'Football') {
        // $query = $this->db->query("SELECT rb.id, rbs.game_result 
        //     FROM
        //         report_betlists AS rb
        //         LEFT JOIN report_betlists_results AS rbs ON ( rb.id = rbs.betlist_id ) 
        //     WHERE
        //         ( rb.board_game_id = '" . $game_id . "' 
        //         AND rb.game_type_id = '" . $game_type_id . "' 
        //         AND rb.bet_type = '" . $bet_type . "' 
        //         AND rbs.game_result IS NOT NULL )
        //         AND rb.is_fixtures IS NULL
        //     ORDER BY
        //         rb.bet_time ASC 
        //         LIMIT 0,". $limit . "");
        $query = $this->db->query("SELECT rb.id, rbs.game_result 
            FROM
                report_betlists AS rb
                LEFT JOIN report_betlists_results AS rbs ON ( rb.id = rbs.betlist_id ) 
            WHERE
                ( rb.board_game_id = " . $game_id . " 
                AND rb.game_type_id = " . $game_type_id . " 
                AND rb.bet_type = " . $bet_type . " 
                AND rbs.game_result != NULL )
                AND rb.is_fixtures = NULL 
            ORDER BY
                rb.bet_time ASC 
                LIMIT 0,". $limit . "");
        return $query->result_array();
    }

    function insert_betlist($data = [], $game_id = 24) {
        if($data) {
            foreach($data as $key => $row) {
                # decrypt json
                $gr_data = json_decode($row['game_result'], true);
                $this->insert_function($gr_data, $row['id']);
            }
            return true;
        } else {
            return false;
        }
    }

    function insert_function($data = [], $betlist_id = '') {
        if($betlist_id) {
            if($this->check_result($betlist_id) == 0) {
                if($data) {
                    # set var
                    $json_data = $data;

                    # get leauge & match
                    $leauge_id = $this->get_leaugeid($json_data['subBet'][0]['league']);
                    $match_id = $this->get_matchid($leauge_id, $json_data['subBet'][0]['match']);
    
                    # get half
                    $findH = strstr($json_data['subBet'][0]['marketType'], 'First Half');
                    if($findH) {
                        $isHalf = 1;
                    } else {
                        $isHalf = 0;
                    }
    
                    # setting array to insert
                    $arraySet = [
                        'betlist_id' => $betlist_id,
                        'game_id' => $game_id,
                        'betOption' => $json_data['subBet'][0]['betOption'],
                        'marketType' => $json_data['subBet'][0]['marketType'],
                        'league' => $leauge_id,
                        'match' => $match_id,
                        'winlostDate' => $json_data['subBet'][0]['winlostDate'],
                        'liveScore' => $json_data['subBet'][0]['liveScore'],
                        'isHalfWonLose' => $json_data['isHalfWonLose'],
                        'isLive' => $json_data['isLive'],
                        'isHalf' => $isHalf,
                        'refNo' => $json_data['refNo'],
                        'sportsType' => $json_data['sportsType'],
                        'orderTime' => $json_data['orderTime'],
                        'modifyDate' => $json_data['modifyDate'],
                        'stake' => $json_data['stake'],
                        'status' => $json_data['subBet'][0]['status'],
                        'created_at' => $this->now(),
                        'updated_at' => $this->now()
                    ];
    
                    $this->db->insert('rp_gameresult', $arraySet);
                }
            }

            $this->update_fixtures($betlist_id);
            return true;
        } else {
            return false;
        }
    }

    function update_ishalf() {
        $sql = 'SELECT
                    id,
                    marketType,
                    betOption
                FROM
                    rp_gameresult
                ORDER BY id ASC';
        $data = $this->db->query($sql)->result_array();

        # init val
        $update = 0;
        foreach($data as $key => $row) {

            # get half
            $findH = strstr($row['marketType'], 'First Half');
            if($findH) {
                $isHalf = 1;
            } else {
                $isHalf = 0;
            }

            $this->db->where('id', $row['id']);
            $this->db->update('rp_gameresult', ['isHalf' => $isHalf]);
            $update++;
        }
        return $update;
    }

    function get_currentdate() {
        if(date('H:i:s') < '10:59:59') {
            return date('Y-m-d', strtotime('-1 days'));
        } else {
            return date('Y-m-d');
        }
    }

    function update_fixtures($id = '') {
        $this->db->where('id', $id);
        $this->db->update('report_betlists', ['is_fixtures' => 1, 'fixture_at' => $this->now()]);
    }

    function check_result($id = '') {
        return $this->db->where('betlist_id', $id)->from("rp_gameresult")->count_all_results();
    }

    function get_leaugeid($leauge_name = '') {
        if($leauge_name) {
            $this->db->select('id');
            $this->db->from('rp_leauge');
            $this->db->where('leauge_name', trim($leauge_name));
            $query = $this->db->get();
            $row = $query->row_array();
            if($row) {
                return $row['id'];
            } else {
                $settingArray = [
                    'leauge_name' => trim($leauge_name),
                    'created_at' => $this->now(),
                    'updated_at' => $this->now()
                ];
                $this->db->insert('rp_leauge', $settingArray);
                return $this->db->insert_id();
            }
        } else {
            return false;
        }
    }

    function get_matchid($leauge_id = '', $match_name = '') {
        if($leauge_id && $match_name) {
            $this->db->select('id');
            $this->db->from('rp_match');
            $this->db->where('leauge_id', $leauge_id);
            $this->db->where('match_name', trim($match_name));
            $query = $this->db->get();
            $row = $query->row_array();
            if($row) {
                return $row['id'];
            } else {
                # explode for getting the team name
                $matchStr = str_replace(['  vs ', '  vs  '], [' vs ', ' vs '], trim($match_name));
                $exData = explode(' vs ', $matchStr);

                $settingArray = [
                    'leauge_id' => $leauge_id,
                    'match_name' => trim($match_name),
                    'team_home' => $exData[0],
                    'team_aways' => $exData[1],
                    'created_at' => $this->now(),
                    'updated_at' => $this->now()
                ];
                $this->db->insert('rp_match', $settingArray);
                return $this->db->insert_id();
            }
        } else {
            return false;
        }
    }

    function get_leauge($leauge_id = '', $date = '') {
        # get Leauge
        if($leauge_id && is_array($leauge_id)) {
            $leauge_sql = 'SELECT
                id AS leauge_id,
                leauge_name 
            FROM
                rp_leauge 
            WHERE
                ( SELECT COUNT(*) AS num FROM rp_gameresult WHERE league = rp_leauge.id AND DATE(winlostDate) = \'' . $date . '\' ) <> 0 
                AND id IN (' . implode(',', $leauge_id) . ')';
        } else {
            $leauge_sql = 'SELECT
                id AS leauge_id,
                leauge_name 
            FROM
                rp_leauge 
            WHERE
                ( SELECT COUNT(*) AS num FROM rp_gameresult WHERE league = rp_leauge.id AND DATE(winlostDate) = \'' . $date . '\' ) <> 0';
        }
        return $this->db->query($leauge_sql)->result_array();
    }

    function get_match_data($leauge_id = '', $data = [], $limit = 1000) {
        $sql = "SELECT
                rp.id,
                rp.isLive,
                rp.isHalf,
                rp.isHalfWonLose,
                rp.liveScore,
                rp.game_id as rep_game_id,
                rp.league as rep_league_id,
                rp.match as rep_match_id,
                rpm.team_home,
                rpm.team_aways,
                DATE_FORMAT(rp.winlostDate, '%Y-%m-%d') as winlostDate,
                rpm.match_name,
                rpm.id as match_id,
                ( SELECT SUM( stake ) FROM rp_gameresult WHERE isLive = 0 AND isHalf = 0 AND betOption = rpm.team_home AND `league` = rp.league AND `match` = rp.`match` AND winlostDate = rp.winlostDate AND liveScore = rp.liveScore ) AS home_summary_bef,
                ( SELECT SUM( stake ) FROM rp_gameresult WHERE isLive = 0 AND isHalf = 0 AND betOption = rpm.team_aways AND `league` = rp.league AND `match` = rp.`match` AND winlostDate = rp.winlostDate AND liveScore = rp.liveScore ) AS aways_summary_bef,
                ( SELECT SUM( stake ) FROM rp_gameresult WHERE isLive = 0 AND isHalf = 0 AND betOption = 'Over' AND `league` = rp.league AND `match` = rp.`match` AND winlostDate = rp.winlostDate AND liveScore = rp.liveScore ) AS over_summary_bef,
                ( SELECT SUM( stake ) FROM rp_gameresult WHERE isLive = 0 AND isHalf = 0 AND betOption = 'Under' AND `league` = rp.league AND `match` = rp.`match` AND winlostDate = rp.winlostDate AND liveScore = rp.liveScore ) AS under_summary_bef, 
                ( SELECT SUM( stake ) FROM rp_gameresult WHERE isLive = 0 AND isHalf = 1 AND betOption = rpm.team_home AND `league` = rp.league AND `match` = rp.`match` AND winlostDate = rp.winlostDate AND liveScore = rp.liveScore  ) AS home_summary_bef_half,
                ( SELECT SUM( stake ) FROM rp_gameresult WHERE isLive = 0 AND isHalf = 1 AND betOption = rpm.team_aways AND `league` = rp.league AND `match` = rp.`match` AND winlostDate = rp.winlostDate AND liveScore = rp.liveScore ) AS aways_summary_bef_half,
                ( SELECT SUM( stake ) FROM rp_gameresult WHERE isLive = 0 AND isHalf = 1 AND betOption = 'Over' AND `league` = rp.league AND `match` = rp.`match` AND winlostDate = rp.winlostDate AND liveScore = rp.liveScore ) AS over_summary_bef_half,
                ( SELECT SUM( stake ) FROM rp_gameresult WHERE isLive = 0 AND isHalf = 1 AND betOption = 'Under' AND `league` = rp.league AND `match` = rp.`match` AND winlostDate = rp.winlostDate AND liveScore = rp.liveScore ) AS under_summary_bef_half, 
                ( SELECT SUM( stake ) FROM rp_gameresult WHERE isLive = 1 AND isHalf = 0 AND betOption = rpm.team_home AND `league` = rp.league AND `match` = rp.`match` AND `status` = 'running' AND liveScore = rp.liveScore AND winlostDate = rp.winlostDate ) AS home_summary_live,
                ( SELECT SUM( stake ) FROM rp_gameresult WHERE isLive = 1 AND isHalf = 0 AND betOption = rpm.team_aways AND `league` = rp.league AND `match` = rp.`match` AND `status` = 'running' AND liveScore = rp.liveScore AND winlostDate = rp.winlostDate ) AS aways_summary_live,
                ( SELECT SUM( stake ) FROM rp_gameresult WHERE isLive = 1 AND isHalf = 0 AND betOption = 'Over' AND `league` = rp.league AND `match` = rp.`match` AND `status` = 'running' AND liveScore = rp.liveScore AND winlostDate = rp.winlostDate ) AS over_summary_live,
                ( SELECT SUM( stake ) FROM rp_gameresult WHERE isLive = 1 AND isHalf = 0 AND betOption = 'Under' AND `league` = rp.league AND `match` = rp.`match` AND `status` = 'running' AND liveScore = rp.liveScore AND winlostDate = rp.winlostDate ) AS under_summary_live, 
                ( SELECT SUM( stake ) FROM rp_gameresult WHERE isLive = 1 AND isHalf = 1 AND betOption = rpm.team_home AND `league` = rp.league AND `match` = rp.`match` AND `status` = 'running' AND liveScore = rp.liveScore AND winlostDate = rp.winlostDate ) AS home_summary_live_half,
                ( SELECT SUM( stake ) FROM rp_gameresult WHERE isLive = 1 AND isHalf = 1 AND betOption = rpm.team_aways AND `league` = rp.league AND `match` = rp.`match` AND `status` = 'running' AND liveScore = rp.liveScore AND winlostDate = rp.winlostDate ) AS aways_summary_live_half,
                ( SELECT SUM( stake ) FROM rp_gameresult WHERE isLive = 1 AND isHalf = 1 AND betOption = 'Over' AND `league` = rp.league AND `match` = rp.`match` AND `status` = 'running' AND liveScore = rp.liveScore AND winlostDate = rp.winlostDate ) AS over_summary_live_half,
                ( SELECT SUM( stake ) FROM rp_gameresult WHERE isLive = 1 AND isHalf = 1 AND betOption = 'Under' AND `league` = rp.league AND `match` = rp.`match` AND `status` = 'running' AND liveScore = rp.liveScore AND winlostDate = rp.winlostDate ) AS under_summary_live_half
            FROM
                rp_gameresult AS rp
                LEFT JOIN rp_match AS rpm ON ( rpm.leauge_id = rp.league AND rpm.id = rp.`match` ) 
            WHERE
                rp.league = '" . $leauge_id . "' 
                AND DATE(rp.winlostDate) = '" . $data['select_date'] . "' 
            GROUP BY
                rp.`match`,
                rp.liveScore
            ORDER BY
                rp.isLive DESC,
                rpm.match_name ASC,
                rpm.id DESC,
                rp.id DESC";
        return $this->db->query($sql)->result_array();
    }

    function get_report_data($limit = 1000, $data = []) {
        # get Leauge
        $leauge_data = $this->get_leauge($data['leauge_id'], $data['select_date']);

        # loop the data for prepare data into array set.
        foreach($leauge_data as $key => $row) {
            $match_data = $this->get_match_data($row['leauge_id'], $data, $limit);
            $leauge_data[$key]['leauge_data'] = $match_data;
        }
        
        return $leauge_data;
    }

    function get_reportdata_agent($date = '') {
        $query = $this->db->query("SELECT
        rb.id,
        rb.agent_id,
        rb.username_id,
        ca.name AS contact,
        ca.ref AS username,
        rb.board_game_id AS game_id,
        rb.game_type_id AS type_id,
        pn.name AS pn_name,
        pn.id AS pn_id,
        DATE_FORMAT(rb.work_time, '%Y-%m-%d') AS work_date,
        rb.bet_amount,
        COUNT(rb.id) AS stake,
        Sum(rb.turnover) AS turnover,
        Sum(rb.rolling) AS valid_amount,
        Sum(rb.result_amount) AS member_winloss,
        Sum(rb.commission_amount) AS member_comm,
        Sum(rb.result_amount + rb.commission_amount) AS member_total,
        Sum(rb.agent_winloss) AS agent_winloss,
        Sum(rb.agent_amount) AS agent_amount,
        Sum(rb.agent_commission) AS agent_comm,
        Sum(rb.agent_winloss + rb.agent_commission) AS agent_total,
        Sum(rb.super_winloss) AS super_winloss,
        Sum(rb.super_amount) AS super_amount,
        Sum(rb.super_commission) AS super_comm,
        Sum(rb.super_winloss + rb.super_commission) AS super_total,
        Sum(rb.company_winloss) AS company_winloss,
        Sum(rb.company_amount) AS company_amount,
        Sum(rb.company_commission) AS company_comm,
        Sum(rb.company_winloss + rb.company_commission) AS company_total
        FROM
        report_betlists AS rb
        LEFT JOIN core_agents AS ca ON rb.agent_id = ca.id
        LEFT JOIN core_partners AS pn ON ca.partner_id = pn.id
        WHERE
        rb.work_time BETWEEN '" . $date . " 00:00:00' AND '" . $date . " 23:59:59'
        GROUP BY
        rb.agent_id,
        rb.game_type_id,
        DATE(rb.work_time)");
        return $query->result_array();
    }

    function get_reportdata_member($date = '') {
        $query = $this->db->query("SELECT
        rb.id,
        rb.agent_id,
        rb.username_id,
        rb.board_game_id AS game_id,
        rb.game_type_id AS type_id,
        mb.name AS contact,
        cu.username,
        DATE_FORMAT(rb.work_time, '%Y-%m-%d') AS work_date,
        rb.bet_amount,
        COUNT(rb.id) AS stake,
        Sum(rb.turnover) AS turnover,
        Sum(rb.rolling) AS valid_amount,
        Sum(rb.result_amount) AS member_winloss,
        Sum(rb.commission_amount) AS member_comm,
        Sum(rb.result_amount + rb.commission_amount) AS member_total,
        Sum(rb.agent_winloss) AS agent_winloss,
        Sum(rb.agent_amount) AS agent_amount,
        Sum(rb.agent_commission) AS agent_comm,
        Sum(rb.agent_winloss + rb.agent_commission) AS agent_total,
        Sum(rb.super_winloss) AS super_winloss,
        Sum(rb.super_amount) AS super_amount,
        Sum(rb.super_commission) AS super_comm,
        Sum(rb.super_winloss + rb.super_commission) AS super_total,
        Sum(rb.company_winloss) AS company_winloss,
        Sum(rb.company_amount) AS company_amount,
        Sum(rb.company_commission) AS company_comm,
        Sum(rb.company_winloss + rb.company_commission) AS company_total
        FROM
        report_betlists AS rb
        LEFT JOIN core_username AS cu ON rb.username_id = cu.id
        LEFT JOIN member_members AS mb ON cu.member_id = mb.id
        WHERE
        rb.work_time BETWEEN '" . $date . " 00:00:00' AND '" . $date . " 23:59:59'
        GROUP BY
        rb.username_id,
        rb.game_type_id,
        DATE(rb.work_time)");
        return $query->result_array();
    }

    function get_reportdata_agent_($date = '', $tables_is = '') {
        $query = $this->db->query("SELECT
        rb.id,
        rb.agent_id,
        rb.username_id,
        ca.name AS contact,
        ca.ref AS username,
        rb.board_game_id AS game_id,
        rb.game_type_id AS type_id,
        pn.name AS pn_name,
        pn.id AS pn_id,
        DATE_FORMAT(rb.work_time, '%Y-%m-%d') AS work_date,
        rb.bet_amount,
        COUNT(rb.id) AS stake,
        Sum(rb.turnover) AS turnover,
        Sum(rb.rolling) AS valid_amount,
        Sum(rb.result_amount) AS member_winloss,
        Sum(rb.commission_amount) AS member_comm,
        Sum(rb.result_amount + rb.commission_amount) AS member_total,
        Sum(rb.agent_winloss) AS agent_winloss,
        Sum(rb.agent_amount) AS agent_amount,
        Sum(rb.agent_commission) AS agent_comm,
        Sum(rb.agent_winloss + rb.agent_commission) AS agent_total,
        Sum(rb.super_winloss) AS super_winloss,
        Sum(rb.super_amount) AS super_amount,
        Sum(rb.super_commission) AS super_comm,
        Sum(rb.super_winloss + rb.super_commission) AS super_total,
        Sum(rb.company_winloss) AS company_winloss,
        Sum(rb.company_amount) AS company_amount,
        Sum(rb.company_commission) AS company_comm,
        Sum(rb.company_winloss + rb.company_commission) AS company_total
        FROM
        " . $tables_is . " AS rb
        LEFT JOIN core_agents AS ca ON rb.agent_id = ca.id
        LEFT JOIN core_partners AS pn ON ca.partner_id = pn.id
        WHERE
            rb.work_time BETWEEN '" . $date . " 00:00:00' AND '" . $date . " 23:59:59'
        GROUP BY
        rb.agent_id,
        rb.game_type_id,
        DATE(rb.work_time)");
        return $query->result_array();
    }

    function get_reportdata_member_($date = '', $tables_is = '') {
        $query = $this->db->query("SELECT
        rb.id,
        rb.agent_id,
        rb.username_id,
        rb.board_game_id AS game_id,
        rb.game_type_id AS type_id,
        mb.name AS contact,
        cu.username,
        DATE_FORMAT(rb.work_time, '%Y-%m-%d') AS work_date,
        rb.bet_amount,
        COUNT(rb.id) AS stake,
        Sum(rb.turnover) AS turnover,
        Sum(rb.rolling) AS valid_amount,
        Sum(rb.result_amount) AS member_winloss,
        Sum(rb.commission_amount) AS member_comm,
        Sum(rb.result_amount + rb.commission_amount) AS member_total,
        Sum(rb.agent_winloss) AS agent_winloss,
        Sum(rb.agent_amount) AS agent_amount,
        Sum(rb.agent_commission) AS agent_comm,
        Sum(rb.agent_winloss + rb.agent_commission) AS agent_total,
        Sum(rb.super_winloss) AS super_winloss,
        Sum(rb.super_amount) AS super_amount,
        Sum(rb.super_commission) AS super_comm,
        Sum(rb.super_winloss + rb.super_commission) AS super_total,
        Sum(rb.company_winloss) AS company_winloss,
        Sum(rb.company_amount) AS company_amount,
        Sum(rb.company_commission) AS company_comm,
        Sum(rb.company_winloss + rb.company_commission) AS company_total
        FROM
        " . $tables_is . " AS rb
        LEFT JOIN core_username AS cu ON rb.username_id = cu.id
        LEFT JOIN member_members AS mb ON cu.member_id = mb.id
        WHERE
            rb.work_time BETWEEN '" . $date . " 00:00:00' AND '" . $date . " 23:59:59'
        GROUP BY
        rb.username_id,
        rb.game_type_id,
        DATE(rb.work_time)");
        return $query->result_array();
    }

    public function insert_tmpreport_agent($report_data = []) {
        if($report_data) {
            foreach($report_data as $key => $data) {
                if($this->check_tmpresult_agent($data['agent_id'], $data['game_id'], $data['type_id'], $data['work_date']) == 0) {
                    $this->db->insert('report_betlists_temp_agent', [
                        'agent_id' => $data['agent_id'],
                        'username_id' => $data['username_id'],
                        'contact' => $data['contact'],
                        'username' => $data['username'],
                        'game_id' => $data['game_id'],
                        'type_id' => $data['type_id'],
                        'pn_name' => $data['pn_name'],
                        'pn_id' => $data['pn_id'],
                        'work_date' => $data['work_date'],
                        'bet_amount' => $data['bet_amount'],
                        'stake' => $data['stake'],
                        'turnover' => $data['turnover'],
                        'valid_amount' => $data['valid_amount'],
                        'member_winloss' => $data['member_winloss'],
                        'member_comm' => $data['member_comm'],
                        'member_total' => $data['member_total'],
                        'agent_winloss' => $data['agent_winloss'],
                        'agent_amount' => $data['agent_amount'],
                        'agent_comm' => $data['agent_comm'],
                        'agent_total' => $data['agent_total'],
                        'super_winloss' => $data['super_winloss'],
                        'super_amount' => $data['super_amount'],
                        'super_comm' => $data['super_comm'],
                        'super_total' => $data['super_total'],
                        'company_winloss' => $data['company_winloss'],
                        'company_amount' => $data['company_amount'],
                        'company_comm' => $data['company_comm'],
                        'company_total' => $data['company_total']
                    ]);
                } else {
                    # update replace
                    $this->db->where('agent_id', $data['agent_id']);
                    $this->db->where('game_id', $data['game_id']);
                    $this->db->where('type_id', $data['type_id']);
                    $this->db->where('work_date', $data['work_date']);
                    $this->db->update('report_betlists_temp_agent', [
                        'agent_id' => $data['agent_id'],
                        'username_id' => $data['username_id'],
                        'contact' => $data['contact'],
                        'username' => $data['username'],
                        'game_id' => $data['game_id'],
                        'type_id' => $data['type_id'],
                        'pn_name' => $data['pn_name'],
                        'pn_id' => $data['pn_id'],
                        'work_date' => $data['work_date'],
                        'bet_amount' => $data['bet_amount'],
                        'stake' => $data['stake'],
                        'turnover' => $data['turnover'],
                        'valid_amount' => $data['valid_amount'],
                        'member_winloss' => $data['member_winloss'],
                        'member_comm' => $data['member_comm'],
                        'member_total' => $data['member_total'],
                        'agent_winloss' => $data['agent_winloss'],
                        'agent_amount' => $data['agent_amount'],
                        'agent_comm' => $data['agent_comm'],
                        'agent_total' => $data['agent_total'],
                        'super_winloss' => $data['super_winloss'],
                        'super_amount' => $data['super_amount'],
                        'super_comm' => $data['super_comm'],
                        'super_total' => $data['super_total'],
                        'company_winloss' => $data['company_winloss'],
                        'company_amount' => $data['company_amount'],
                        'company_comm' => $data['company_comm'],
                        'company_total' => $data['company_total']
                    ]);
                }
            }
        }
    }

    public function insert_tmpreport_member($report_data = []) {
        if($report_data) {
            foreach($report_data as $key => $data) {
                if($this->check_tmpresult_member($data['agent_id'], $data['game_id'], $data['type_id'], $data['work_date'], $data['username_id']) == 0) {
                    # insert new
                    $this->db->insert('report_betlists_temp_member', [
                        'agent_id' => $data['agent_id'],
                        'username_id' => $data['username_id'],
                        'contact' => $data['contact'],
                        'username' => $data['username'],
                        'game_id' => $data['game_id'],
                        'type_id' => $data['type_id'],
                        'pn_name' => $data['pn_name'],
                        'pn_id' => $data['pn_id'],
                        'work_date' => $data['work_date'],
                        'bet_amount' => $data['bet_amount'],
                        'stake' => $data['stake'],
                        'turnover' => $data['turnover'],
                        'valid_amount' => $data['valid_amount'],
                        'member_winloss' => $data['member_winloss'],
                        'member_comm' => $data['member_comm'],
                        'member_total' => $data['member_total'],
                        'agent_winloss' => $data['agent_winloss'],
                        'agent_amount' => $data['agent_amount'],
                        'agent_comm' => $data['agent_comm'],
                        'agent_total' => $data['agent_total'],
                        'super_winloss' => $data['super_winloss'],
                        'super_amount' => $data['super_amount'],
                        'super_comm' => $data['super_comm'],
                        'super_total' => $data['super_total'],
                        'company_winloss' => $data['company_winloss'],
                        'company_amount' => $data['company_amount'],
                        'company_comm' => $data['company_comm'],
                        'company_total' => $data['company_total']
                    ]);
                } else {
                    # update replace
                    $this->db->where('agent_id', $data['agent_id']);
                    $this->db->where('game_id', $data['game_id']);
                    $this->db->where('type_id', $data['type_id']);
                    $this->db->where('work_date', $data['work_date']);
                    $this->db->where('username_id', $data['username_id']);
                    $this->db->update('report_betlists_temp_member', [
                        'agent_id' => $data['agent_id'],
                        'username_id' => $data['username_id'],
                        'contact' => $data['contact'],
                        'username' => $data['username'],
                        'game_id' => $data['game_id'],
                        'type_id' => $data['type_id'],
                        'pn_name' => $data['pn_name'],
                        'pn_id' => $data['pn_id'],
                        'work_date' => $data['work_date'],
                        'bet_amount' => $data['bet_amount'],
                        'stake' => $data['stake'],
                        'turnover' => $data['turnover'],
                        'valid_amount' => $data['valid_amount'],
                        'member_winloss' => $data['member_winloss'],
                        'member_comm' => $data['member_comm'],
                        'member_total' => $data['member_total'],
                        'agent_winloss' => $data['agent_winloss'],
                        'agent_amount' => $data['agent_amount'],
                        'agent_comm' => $data['agent_comm'],
                        'agent_total' => $data['agent_total'],
                        'super_winloss' => $data['super_winloss'],
                        'super_amount' => $data['super_amount'],
                        'super_comm' => $data['super_comm'],
                        'super_total' => $data['super_total'],
                        'company_winloss' => $data['company_winloss'],
                        'company_amount' => $data['company_amount'],
                        'company_comm' => $data['company_comm'],
                        'company_total' => $data['company_total']
                    ]);
                }
            }
        }
    }

    function check_tmpresult_agent($agent_id = '', $game_id = '', $type_id = '', $work_date = '') {
        return $this->db->where('agent_id', $agent_id)
        ->where('game_id', $game_id)
        ->where('type_id', $type_id)
        ->where('work_date', trim($work_date))
        ->from("report_betlists_temp_agent")->count_all_results();
    }

    function check_tmpresult_member($agent_id = '', $game_id = '', $type_id = '', $work_date = '', $username_id = '') {
        return $this->db->where('agent_id', $agent_id)
        ->where('game_id', $game_id)
        ->where('type_id', $type_id)
        ->where('work_date', trim($work_date))
        ->where('username_id', $username_id )
        ->from("report_betlists_temp_member")->count_all_results();
    }

    function calmenow($number) {
        if($number < 0) {
            return '<span class="red-point">' . number_format($number) . '</span>';
        } else {
            return '<span class="bold">' . number_format($number) . '</span>';
        }
    }

    function get_betlistinfo($game_id = 24, $leauge_id = '', $match_id = '', $isLive = 0, $isHalf = 0, $betOption = '', $winlostDate = '', $liveScore) {
        $sql = "SELECT
                rb.*,
                rp.betlist_id,
                rp.refNo,
                rp.status,
                rp.liveScore,
                rp.stake,
                rp.betOption,
                rp.marketType
            FROM
                rp_gameresult AS rp
                LEFT JOIN report_betlists AS rb ON ( rp.betlist_id = rb.id ) 
            WHERE
                rp.league = '" . $leauge_id . "'  
                AND rp.`match` = '" . $match_id . "'
            ";

            $sql .= " AND rp.isLive = '" . $isLive . "'  AND rp.isHalf = '" . $isHalf . "' ";

            if($isLive == 1) {
                $sql .= " AND rp.status = 'running' ";
            }

        if($betOption) {
            $sql .= " AND rp.betOption = '" . $betOption . "' ";
        }

        if($winLostDate) {
            $sql .= " AND DATE_FORMAT(rp.winlostDate, '%Y-%m-%d') = '" . $winlostDate . "' ";
        }

        if($liveScore) {
            $sql .= " AND rp.liveScore = '" . $liveScore . "' ";
        }

        $sql .= "ORDER BY rb.bet_time DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}