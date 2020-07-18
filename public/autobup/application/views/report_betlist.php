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
			line-height: 1.1;
		}
		.bold {
			font-weight: 600 !important;
		}
		.font-red {
			color: red !important;
			font-style: italic;
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
	<div class="container p-3">
		<div class="row">
			<div class="col-12 col-lg-4">
				<h2 class="m-2">รายงานข้อมูล - <?php echo date('d/m/Y', strtotime($data_date)); ?></h2>
			</div>
			<div class="col-12 col-lg-8">
				<form method="post" class="form-inline mt-2 justify-content-end align-items-center" action="">
					<?php if($leauge_data): ?>
					<select class="form-control multi-select" name="leauge_id[]" multiple="multiple">
						<?php foreach($leauge_data as $key => $row): ?>
							<option value="<?php echo $row['leauge_id']; ?>" <?php echo (in_array($row['leauge_id'], $_POST['leauge_id']) ? 'selected':''); ?>><?php echo $row['leauge_name']; ?></option>
						<?php endforeach; ?>
					</select>
					<?php endif; ?>
					<input type="text" class="form-control mb-2 mr-sm-2 date-picker" name="select_date" value="<?php echo $data_date; ?>" />
					<button type="submit" class="btn btn-primary mb-2">ค้นหา</button>
				</form>
			</div>
		</div>
		<div class="mt-4">
			<div class="table-responsive">
				<table class="table table-sm table-bordered table-hover" id="table-data" style="margin-bottom: 0;">
					<thead class="thead-dark">
						<th>
							<div class="text-center">
								<a href="javascript:;" onclick="return fetchData();" class="thead-link">โหลดใหม่</a> <span id="countSec">10</span> วินาที
							</div>
						</th>
					</thead>
				</table>
				<div class="table-content"></div>
			</div>
		</div>
	</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="//code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="//stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url('assets/multiselect/jquery.sumoselect.min.js'); ?>"></script>
	<!-- datepicker -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha256-bqVeqGdJ7h/lYPq6xrPv/YGzMEb6dNxlfiTUHSgRCp8=" crossorigin="anonymous"></script>

	<script>
	jQuery(function($) {
		$('.date-picker').datepicker({
			format: "yyyy-mm-dd"
		});
		$('.multi-select').SumoSelect();

		fetchData();
	});

	var interVal;
	var countInterv;
	function fetchData() {
		clearInterval(interVal);
		clearInterval(countInterv);
		var setCount = 11;

		// first run
		jQuery.ajax({
			url: '<?php echo base_url('betlist/ajax_table'); ?>',
			type: 'POST',
			dataType: 'html',
			data: <?php echo json_encode($_POST); ?>,
			beforeSend: function() {
				$('.table-content').html('<div class="mt-3 mb-3 ml-1 mr-1">Fetching data...</div>');
			},
			success: function(data) {
				$('.table-content').html(data);
			}
		})

		countInterv = setInterval(function() {
			if(setCount > 1) {
				setCount = setCount - 1;
				$('#countSec').text(setCount);
			}
		}, 1000);

		interVal = setInterval(function() {
			jQuery.ajax({
				url: './betlist/ajax_table',
				type: 'POST',
				dataType: 'html',
				data: <?php echo json_encode($_POST); ?>,
				success: function(data) {
					$('.table-content').html(data);
				},
				complete: function() {
					setCount = 11;
				}
			})
		}, 10000);
	}

	function openPopup(url) {
		popupWindow = window.open(url,'popUpWindow','height=600,width=800,left=0,top=0,resizable=false,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
	}

	</script>
  </body>
</html>