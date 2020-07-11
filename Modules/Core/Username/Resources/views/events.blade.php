<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>แจก User เล่นกิจกรรม</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>

<div class="container">
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <th colspan="8" class="text-center" style="background-color: #2e6da4">กิจกรรม บาคาร่า</th>
        </tr>
        <tr>
            <th colspan="2">เลือกเว็บ</th>
            <td colspan="6">
                <select name="optDomain" id="optDomain" class="form-control">
                    <option value="">--เลือกเว็บ--</option>
                    @foreach($domains as $domain)
                        <option value="{{ $domain->id }}" {{ (!empty($get['pn'])) ? (($get['pn']==$domain->id) ? "selected" : "") : "" }}>{{ $domain->name }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <th colspan="8" class="text-center" style="background-color: #009999">Username ทั้งหมด</th>
        </tr>
        <tr>
            <th colspan="2">ค้นหาลูกค้า</th>
            <td colspan="2">
                <input type="text" name="phone" id="phone" class="form-control">
            </td>
            <td colspan="4">
                <button class="btn btn-info" onclick="confirmGive()">ยืนยันการให้สิทธิ์</button>
            </td>
        </tr>
        <tr class="text-center">
            <th class="table-active">Game User</th>
            <th class="table-active">Game Password</th>
            <th class="table-primary">Username</th>
            <th class="table-primary">Password</th>
            <th class="table-active">เบอร์ลูกค้า</th>
            <th class="table-active">ชื่อลูกค้า</th>
            <th class="table-active">สร้างเมื่อ</th>
            <th class="table-active">Option</th>
        </tr>
        @foreach($usernames as $username)
            <tr>
                <td align="center" class="table-info"><b>{{ $username->username }}</b></td>
                <td align="center" class="table-info">@if(!empty($username->member_at))<b>{{ \Illuminate\Support\Facades\Crypt::decryptString($username->password) }}</b>@endif</td>
                <td align="center" class="table-primary"><b>{{ $username->ev_username }}</b></td>
                <td align="center" class="table-primary"><b>@if(!empty($username->member_at)){{ \Illuminate\Support\Facades\Crypt::decryptString($username->ev_password) }}</b>@endif</td>
                <td align="center">{{ $username->phone }}</td>
                <td>{{ $username->name }}</td>
                <td align="center">{{ $username->member_at }}</td>
                <td align="center">
                    @if(!empty($username->member_at))
                        @if($username->ev_status == 1)
                            <button class="btn btn-success btn-sm" onclick="actionGet('/core/username/dis_userev_by_id/{{ $username->id }}/0')">เปิดใช้งาน</button>
                        @else
                            <button class="btn btn-warning btn-sm" onclick="actionGet('/core/username/dis_userev_by_id/{{ $username->id }}/1')">ปิดใช้งาน</button>
                        @endif
                        <button class="btn btn-danger btn-sm" onclick="cancelGive({{ $username->id }})">ยกเลิก</button>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>

</div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script>
        $('select#optDomain').on('change', function(){
            let val = $(this).val();
            window.location = "?pn="+val;
        })

        @if(isset($get['pn']))

        function confirmGive(){

            let phone = $('input#phone').val();
            let pn = "{{ $get['pn'] }}";
            let pn_old = "{{ $partner->old_id }}";
            if(phone == ""){
                alert("กรุณากรอกเบอร์โทรลูกค้า");
                return false;
            }

            var r = confirm("ต้องการแจก Username ให้ลูกค้าเบอร์ "+phone+" นี้หรือไม่?");
            if (r == true) {

                $.ajax({
                    type: "POST",
                    url: "/core/username/events-give",
                    data: {type: "add", phone: phone, pn: pn, pn_old: pn_old},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {

                        if(!res.status){
                            alert(res.msg);
                            return false;
                        }

                        alert(res.msg);
                        location.reload();

                    }
                });

            } else {
                return false;
            }


        }

        function cancelGive(id){
            var r = confirm("ต้องการยกเลิกการให้สิทธิ์ นี้หรือไม่?");
            if (r == true) {

                $.ajax({
                    type: "POST",
                    url: "/core/username/events-give",
                    data: {type: "cancel", id: id},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {

                        if(!res.status){
                            alert(res.msg);
                            return false;
                        }

                        alert(res.msg);
                        location.reload();

                    }
                });

            } else {
                return false;
            }
        }

        function actionGet(url){
            var r = confirm("ต้องการเปลี่ยนสถานะ นี้หรือไม่?");
            if (r == true) {

                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {

                        if(!res.status){
                            alert(res.msg);
                            return false;
                        }

                        location.reload();

                    }
                });

            } else {
                return false;
            }
        }
        @endif
    </script>

</body>
</html>