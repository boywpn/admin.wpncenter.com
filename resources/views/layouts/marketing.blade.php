<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>การตลาด</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>

<div class="container">
    <form action="">
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <th colspan="2" class="text-center" style="background-color: #2e6da4">ค้นหาลูกค้าตามเบอร์โทร</th>
            </tr>
            <tr>
                <th>เลือกเว็บ</th>
                <td>
                    <select name="domain" id="optDomain" class="form-control">
                        <option value="">--เลือกเว็บ--</option>
                        @foreach($domains as $domain)
                            <option value="{{ $domain->id }}" {{ (!empty($get['domain'])) ? (($get['domain']==$domain->id) ? "selected" : "") : "" }}>{{ $domain->domain }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <th colspan="2" class="text-center" style="background-color: #009999">การค้นหา</th>
            </tr>
            <tr>
                <th>User เล่น</th>
                <td>
                    <input type="text" name="user" id="user" value="{{ (!empty($get['user'])) ? $get['user'] : "" }}" class="form-control col-md-6" placeholder="User เล่น" required>
                </td>
            </tr>
            <tr>
                <th></th>
                <td>
                    <button class="btn btn-info" type="submit">ค้นหาลูกค้า</button>
                </td>
            </tr>
        </table>
    </form>
    <table class="table table-bordered table-hover table-striped">
        <tr class="text-center">
            <th class="table-active">Username</th>
            <th class="table-active">Agent</th>
            <th class="table-active">ชื่อลูกค้า</th>
            <th class="table-active">สร้างเมื่อ</th>
            <th class="table-active">Ref. | จำนวน | สร้างเมื่อ</th>
        </tr>
        @foreach($member as $mem)
            @php
                $name = explode(" ", $mem->withdraw_name);

                $agent = $mem->members_agent;
            @endphp
            <tr>
                <td align="center">{{ $get['user'] }}</td>
                <td align="center"></td>
                <td align="center">{{ $name[0] }}</td>
                <td align="center">{{ $mem->created }}</td>
                <td align="center">
                    @foreach($mem->order as $order)
                        {{ $order->id }} | {{ $order->money }} | {{ $order->datetime }} <br>
                    @endforeach
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
            window.location = "?domain="+val;
        })
    </script>

</body>
</html>