<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
	<link href="https://fonts.googleapis.com/css?family=Prompt:400,500,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha256-siyOpF/pBWUPgIcQi17TLBkjvNgNQArcmwJB8YvkAgg=" crossorigin="anonymous" />
	<link rel="stylesheet" href="<?php echo base_url('assets/multiselect/sumoselect.min.css'); ?>" />
	<style>
		html,body {
			font-family: 'Prompt', sans-serif;
			font-size: 13px;
			line-height: 1.25;
		}
		.bold {
			font-weight: 600 !important;
		}
		.font-red {
			color: red !important;
		}
		.font-blue {
			color: blue !important;
		}
		.font-green {
			color: green !important;
		}
		.red-point {
			color: red !important;
			font-weight: bold;
		}
		.spr-4 {
			padding-right: 5px;
		}
		.thead-link {
			font-weight: 400;
			color: #fff;
			text-decoration: underline;
		}
		.thead-link:hover {
			color: #fff;
			text-decoration: none;
		}
		.table-content {
			width: 100%;
			display: block;
		}
		.table td,
		.table th {
			padding: 5px 10px !important;
		}
		.table tr:nth-child(even) {
			background-color: #f9f9ed;
		}
		.table tr:nth-child(odd),
		.table tr:hover {
			background-color: #f7f8fa;
		}
		.table thead.thead-clr,
		.table thead.thead-clr > tr,
		.table thead.thead-clr > th {
			background-color: #fed7a0 !important;
		}
		.table tr {
			cursor: pointer !important;
		}
		.table tbody > tr.is-live,
		.table tbody > td.is-live,
		.table tbody > th.is-live {
			background-color: #f5b8b8 !important;
		}
	</style>
    <title>รายงานข้อมูล</title>
  </head>
  <body>
	<div class="container p-1">
		<div class="mt-2">
			<div class="table-responsive">
				<table class="table table-sm table-bordered table-hover" id="table-data" style="margin-bottom: 0;">
					<thead class="thead-dark">
						<th>ลำดับ</th>
						<th>เวลาดำเนินการ</th>
						<th>รายละเอียด</th>
						<th>สถานะบิล</th>
						<th>การเดิมพัน</th>
						<!-- <th>ได้ / เสีย</th> -->
					</thead>
                    <tbody>
                    <?php 
                        $total = 0; 
                        // $total_all = 0; 
                    ?>
                    <?php foreach($getBetlistData as $key => $row): ?>
                        <tr>
                            <td valign="top" width="50"><?php echo ($key + 1); ?></td>
                            <td valign="top">
                                <?php echo $row['bet_time']; ?>
                                <br />Live Score: <span class="font-red"><?php echo $row['liveScore']; ?>
                                <br />Ref No: <span class="font-blue"><?php echo $row['refNo']; ?></span>
                            </td>
                            <td valign="top">
                                Username: <span class="font-blue"><?php echo $row['username']; ?></span>
                                <br />Bet Option: <span class="font-blue"><?php echo $row['betOption']; ?></span>
                                <br />Maket type: <span class="font-blue"><?php echo $row['marketType']; ?></span>
                                <br />State: <?php echo ($row['state'] == 'lose' ? '<span class="font-red">' . $row['state'] . '</span>':'<span class="font-green">' . $row['state'] . '</span>'); ?>
                            </td>
                            <td valign="top"><?php echo $row['status']; ?></td>
                            <td valign="top" align="right">
                                <?php 
                                    $total += $row['stake'];
                                    echo $this->main->calmenow($row['stake'],2);
                                ?>
                            </td>
                            <!-- <td valign="top" align="right">
                                <?php 
                                    $total_all += $row['result_amount'];
                                    echo $this->main->calmenow($row['result_amount'],2);
                                ?>
                            </td> -->
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" align="right">รวม: </td>
                        <td align="right"><?php echo $this->main->calmenow($total,2); ?></td>
                        <!-- <td align="right"><?php echo $this->main->calmenow($total_all,2); ?></td> -->
                    </tr>
                    </tbody>
				</table>
			</div>
		</div>
	</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="//code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="//stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  </body>
</html>