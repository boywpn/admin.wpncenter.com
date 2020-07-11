<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Hello, world!</title>

    <style>
        /**
         * Custom translucent site header
         */

        .site-header {
            background-color: rgba(0, 0, 0, .85);
            -webkit-backdrop-filter: saturate(180%) blur(20px);
            backdrop-filter: saturate(180%) blur(20px);
        }
        .site-header a {
            color: #999;
            transition: ease-in-out color .15s;
        }
        .site-header a:hover {
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>
<body>

<nav class="site-header sticky-top py-1">
    <div class="container d-flex flex-column flex-md-row justify-content-between">
        <a class="py-2" href="#">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="d-block mx-auto" role="img" viewBox="0 0 24 24" focusable="false"><title>Product</title><circle cx="12" cy="12" r="10"/><path d="M14.31 8l5.74 9.94M9.69 8h11.48M7.38 12l5.74-9.94M9.69 16L3.95 6.06M14.31 16H2.83m13.79-4l-5.74 9.94"/></svg>
        </a>
        <a class="py-2 d-none d-md-inline-block" href="#">ตรวจสอบรายการฝาก</a>
    </div>
</nav>

<div class="container-fluid">

    <div class="row">
        <div class="col-md-12">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">เวลา</th>
                    <th scope="col">ธนาคาร</th>
                    <th scope="col">จำนวนเงิน</th>
                    <th scope="col">เลขบัญชี</th>
                    <th scope="col">รายละเอียด</th>
                </tr>
                </thead>
                <tbody>
                @foreach($lists as $list)
                    <tr>
                        <th>{{ $list['id'] }}</th>
                        <td>{{ $list['created_at'] }}</td>
                        <td>{{ $list['bank_name'] }}</td>
                        <td>{{ $list['state_deposit'] }}</td>
                        <td>{{ $list['state_account_no'] }}</td>
                        <td>{{ $list['state_detail'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>

</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script src="https://js.pusher.com/4.4/pusher.min.js"></script>
<script>
    // Enable pusher logging - don't include this in production
    // Pusher.logToConsole = true;

    function callPost(url, jsonData){

        $.ajax({
            type: "POST",
            url: url,
            data: jsonData,
            dataType: "json",
            success: function(data){
                console.log(data);
            }
        });

    }

    var pusher_job = new Pusher('ec3771585152f3e7d58f', {
        cluster: 'ap1',
        forceTLS: true
    });

    var ch_job = pusher_job.subscribe('autosystem');
    ch_job.bind('monitor', function(data) {
        //console.log(data)
        //callPost('//trnf.tk/api/banks/pushauto.php?act=order', data)
    });

    var pusher_bank = new Pusher('2f21f967b567a3753051', {
        cluster: 'ap1',
        forceTLS: true
    });
    var ch_bank = pusher_bank.subscribe('autosystem');
    ch_bank.bind('monitor', function(data) {
        console.log(data)
        //callPost('//trnf.tk/api/banks/pushauto.php?act=bank', data)
    });
</script>

</body>
</html>