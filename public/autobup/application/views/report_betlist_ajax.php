
<table class="table table-sm table-bordered table-hover" id="table-data">
<?php if(count($report_data)>0):?>
<thead class="thead-dark">
<tr>
    <th scope="col" rowspan="2" width="40">ลำดับ</th>
    <th scope="col" rowspan="2" width="120">เวลา</th>
    <th scope="col" rowspan="2">ทีม</th>
    <th scope="col" colspan="6" class="text-center">Full time</th>
    <th scope="col" colspan="6" class="text-center">1st Half</th>
</tr>
<tr>
    <th scope="col" class="text-right">เจ้าบ้าน</th>
    <th scope="col" class="text-right">ทีมเยือน</th>
    <th scope="col" class="text-right">รวม</th>
    <th scope="col" class="text-right">สูง</th>
    <th scope="col" class="text-right">ต่ำ</th>
    <th scope="col" class="text-right">รวม</th>
    <th scope="col" class="text-right">เจ้าบ้าน</th>
    <th scope="col" class="text-right">ทีมเยือน</th>
    <th scope="col" class="text-right">รวม</th>
    <th scope="col" class="text-right">สูง</th>
    <th scope="col" class="text-right">ต่ำ</th>
    <th scope="col" class="text-right">รวม</th>
</tr>
</thead>
<tbody>
<?php 
    # set before total
    $tt_home_summary = 0;
    $tt_aways_summary = 0;
    $tt_over_summary = 0;
    $tt_under_summary = 0;
?>
<?php foreach($report_data as $key => $row): ?>

<?php if(count($row['leauge_data']) > 0): ?>
        <thead class="thead-clr">
            <th colspan="16"><?php echo $row['leauge_name']; ?></th>
        </thead>
    <?php foreach($row['leauge_data'] as $lkey => $ldata): ?>

    <?php
//      $betlist = $this->db->query("SELECT * FROM report_betlists WHERE id = '".$ldata['betlist_id']."'")->row_array();
//      if($betlist['state'] != 'running'){
//        continue;
//      }
    ?>

    <?php if($ldata['isLive'] == 1): ?>
        <tr class="is-live">
            <td scope="row" valign="top"><?php echo ($lkey + 1); ?></td>
            <td valign="top">
               <span class="font-red spr-4">Live!</span> <?php echo $ldata['liveScore']; ?>
            </td>
            <td valign="top"><?php echo $ldata['match_name']; ?> (ID:<?php echo $ldata['match_id']; ?>) (State: <?php echo $ldata['rb_state']?>)</td>
            <td class="text-right" onclick="return openPopup('<?php echo base_url('/betlist/betdata_info?match_id=' . $ldata['rep_match_id'] . '&leauge_id=' . $ldata['rep_league_id'] . '&game_id=' . $ldata['rep_game_id'] . '&isLive=1&isHalf=0&betOption=' . $ldata['team_home'] . '&winlostDate=' . $ldata['winlostDate'] . '&liveScore=' . $ldata['liveScore']); ?>');"><?php echo number_format($ldata['home_summary_live'], 0); ?></td>
            <td class="text-right" onclick="return openPopup('<?php echo base_url('/betlist/betdata_info?match_id=' . $ldata['rep_match_id'] . '&leauge_id=' . $ldata['rep_league_id'] . '&game_id=' . $ldata['rep_game_id'] . '&isLive=1&isHalf=0&betOption=' . $ldata['team_aways'] . '&winlostDate=' . $ldata['winlostDate'] . '&liveScore=' . $ldata['liveScore']); ?>');"><?php echo number_format($ldata['aways_summary_live'], 0); ?></td>
            <td class="text-right"><?php echo $this->main->calmenow($ldata['home_summary_live'] - $ldata['aways_summary_live']); ?></td>
            <td class="text-right" onclick="return openPopup('<?php echo base_url('/betlist/betdata_info?match_id=' . $ldata['rep_match_id'] . '&leauge_id=' . $ldata['rep_league_id'] . '&game_id=' . $ldata['rep_game_id'] . '&isLive=1&isHalf=0&betOption=Over&winlostDate=' . $ldata['winlostDate'] . '&liveScore=' . $ldata['liveScore']); ?>');"><?php echo number_format($ldata['over_summary_live'], 0); ?></td>
            <td class="text-right" onclick="return openPopup('<?php echo base_url('/betlist/betdata_info?match_id=' . $ldata['rep_match_id'] . '&leauge_id=' . $ldata['rep_league_id'] . '&game_id=' . $ldata['rep_game_id'] . '&isLive=1&isHalf=0&betOption=Under&winlostDate=' . $ldata['winlostDate'] . '&liveScore=' . $ldata['liveScore']); ?>');"><?php echo number_format($ldata['under_summary_live'], 0); ?></td>
            <td class="text-right"><?php echo $this->main->calmenow($ldata['over_summary_live'] - $ldata['under_summary_live']); ?></td>
            <td class="text-right" onclick="return openPopup('<?php echo base_url('/betlist/betdata_info?match_id=' . $ldata['rep_match_id'] . '&leauge_id=' . $ldata['rep_league_id'] . '&game_id=' . $ldata['rep_game_id'] . '&isLive=1&isHalf=1&betOption=' . $ldata['team_home'] . '&winlostDate=' . $ldata['winlostDate'] . '&liveScore=' . $ldata['liveScore']); ?>');"><?php echo number_format($ldata['home_summary_live_half'], 0); ?></td>
            <td class="text-right" onclick="return openPopup('<?php echo base_url('/betlist/betdata_info?match_id=' . $ldata['rep_match_id'] . '&leauge_id=' . $ldata['rep_league_id'] . '&game_id=' . $ldata['rep_game_id'] . '&isLive=1&isHalf=1&betOption=' . $ldata['team_aways'] . '&winlostDate=' . $ldata['winlostDate'] . '&liveScore=' . $ldata['liveScore']); ?>');"><?php echo number_format($ldata['aways_summary_live_half'], 0); ?></td>
            <td class="text-right"><?php echo $this->main->calmenow($ldata['home_summary_live_half'] - $ldata['aways_summary_live_half']); ?></td>
            <td class="text-right" onclick="return openPopup('<?php echo base_url('/betlist/betdata_info?match_id=' . $ldata['rep_match_id'] . '&leauge_id=' . $ldata['rep_league_id'] . '&game_id=' . $ldata['rep_game_id'] . '&isLive=1&isHalf=1&betOption=Over&winlostDate=' . $ldata['winlostDate'] . '&liveScore=' . $ldata['liveScore']); ?>');"><?php echo number_format($ldata['over_summary_live_half'], 0); ?></td>
            <td class="text-right" onclick="return openPopup('<?php echo base_url('/betlist/betdata_info?match_id=' . $ldata['rep_match_id'] . '&leauge_id=' . $ldata['rep_league_id'] . '&game_id=' . $ldata['rep_game_id'] . '&isLive=1&isHalf=1&betOption=Under&winlostDate=' . $ldata['winlostDate'] . '&liveScore=' . $ldata['liveScore']); ?>');"><?php echo number_format($ldata['under_summary_live_half'], 0); ?></td>
            <td class="text-right"><?php echo $this->main->calmenow($ldata['over_summary_live_half'] - $ldata['under_summary_live_half']); ?></td>
        </tr>
    <?php else: ?>
        <tr>
            <td scope="row" valign="top"><?php echo ($lkey + 1); ?></td>
            <td valign="top">
                <?php echo date('Y-m-d', strtotime($ldata['winlostDate'])); ?>
            </td>
            <td valign="top"><?php echo $ldata['match_name']; ?> (ID:<?php echo $ldata['match_id']; ?>)</td>
            <td class="text-right" onclick="return openPopup('<?php echo base_url('/betlist/betdata_info?match_id=' . $ldata['rep_match_id'] . '&leauge_id=' . $ldata['rep_league_id'] . '&game_id=' . $ldata['rep_game_id'] . '&isLive=0&isHalf=0&betOption=' . $ldata['team_home'] . '&winlostDate=' . $ldata['winlostDate']); ?>');"><?php echo number_format($ldata['home_summary_bef'], 0); ?></td>
            <td class="text-right" onclick="return openPopup('<?php echo base_url('/betlist/betdata_info?match_id=' . $ldata['rep_match_id'] . '&leauge_id=' . $ldata['rep_league_id'] . '&game_id=' . $ldata['rep_game_id'] . '&isLive=0&isHalf=0&betOption=' . $ldata['team_aways'] . '&winlostDate=' . $ldata['winlostDate']); ?>');"><?php echo number_format($ldata['aways_summary_bef'], 0); ?></td>
            <td class="text-right"><?php echo $this->main->calmenow($ldata['home_summary_bef'] - $ldata['aways_summary_bef']); ?></td>
            <td class="text-right" onclick="return openPopup('<?php echo base_url('/betlist/betdata_info?match_id=' . $ldata['rep_match_id'] . '&leauge_id=' . $ldata['rep_league_id'] . '&game_id=' . $ldata['rep_game_id'] . '&isLive=0&isHalf=0&betOption=Over&winlostDate=' . $ldata['winlostDate']); ?>');"><?php echo number_format($ldata['over_summary_bef'], 0); ?></td>
            <td class="text-right" onclick="return openPopup('<?php echo base_url('/betlist/betdata_info?match_id=' . $ldata['rep_match_id'] . '&leauge_id=' . $ldata['rep_league_id'] . '&game_id=' . $ldata['rep_game_id'] . '&isLive=0&isHalf=0&betOption=Under&winlostDate=' . $ldata['winlostDate']); ?>');"><?php echo number_format($ldata['under_summary_bef'], 0); ?></td>
            <td class="text-right"><?php echo $this->main->calmenow($ldata['over_summary_bef'] - $ldata['under_summary_bef']); ?></td>
            <td class="text-right" onclick="return openPopup('<?php echo base_url('/betlist/betdata_info?match_id=' . $ldata['rep_match_id'] . '&leauge_id=' . $ldata['rep_league_id'] . '&game_id=' . $ldata['rep_game_id'] . '&isLive=0&isHalf=1&betOption=' . $ldata['team_home'] . '&winlostDate=' . $ldata['winlostDate']); ?>');"><?php echo number_format($ldata['home_summary_bef_half'], 0); ?></td>
            <td class="text-right" onclick="return openPopup('<?php echo base_url('/betlist/betdata_info?match_id=' . $ldata['rep_match_id'] . '&leauge_id=' . $ldata['rep_league_id'] . '&game_id=' . $ldata['rep_game_id'] . '&isLive=0&isHalf=1&betOption=' . $ldata['team_aways'] . '&winlostDate=' . $ldata['winlostDate']); ?>');"><?php echo number_format($ldata['aways_summary_bef_half'], 0); ?></td>
            <td class="text-right"><?php echo $this->main->calmenow($ldata['home_summary_bef_half'] - $ldata['aways_summary_bef_half']); ?></td>
            <td class="text-right" onclick="return openPopup('<?php echo base_url('/betlist/betdata_info?match_id=' . $ldata['rep_match_id'] . '&leauge_id=' . $ldata['rep_league_id'] . '&game_id=' . $ldata['rep_game_id'] . '&isLive=0&isHalf=1&betOption=Over&winlostDate=' . $ldata['winlostDate']); ?>');"><?php echo number_format($ldata['over_summary_bef_half'], 0); ?></td>
            <td class="text-right" onclick="return openPopup('<?php echo base_url('/betlist/betdata_info?match_id=' . $ldata['rep_match_id'] . '&leauge_id=' . $ldata['rep_league_id'] . '&game_id=' . $ldata['rep_game_id'] . '&isLive=0&isHalf=1&betOption=Under&winlostDate=' . $ldata['winlostDate']); ?>');"><?php echo number_format($ldata['under_summary_bef_half'], 0); ?></td>
            <td class="text-right"><?php echo $this->main->calmenow($ldata['over_summary_bef_half'] - $ldata['under_summary_bef_half']); ?></td>
        </tr>
    <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
<?php endforeach; ?>
</tbody>
<?php else: ?>
    <div class="mt-3 mb-3 ml-1 mr-1">ไม่พบข้อมูลวันที่ <?php echo date('d/m/Y', strtotime($data_date)); ?></div>
<?php endif; ?>
</table>