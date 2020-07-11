<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ระบบทำกิจกรรม</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <style>
        .container{
            width: 1400px;
            max-width: 1400px;
        }
    </style>

    @yield('css')
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
                    <select name="domain" id="optDomain" class="form-control col-md-6">
                        <option value="">--เลือกเว็บ--</option>
                        @foreach($domains as $domain)
                            <option value="{{ $domain->id }}" {{ (!empty($param['domain'])) ? (($param['domain']==$domain->id) ? "selected" : "") : "" }}>{{ $domain->domain }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <th colspan="2" class="text-center" style="background-color: #009999">การค้นหา</th>
            </tr>
            <tr>
                <th>กิจกรรม</th>
                <td>
                    <select name="event" class="form-control col-md-6" required>
                        <option value="">--เลือกกิจกรรม--</option>
                        <option value="demo200" {{ (!empty($param['event'])) ? (($param['event']=='demo200') ? "selected" : "") : "" }}>ทดลองเล่น 200</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>เบอร์โทร</th>
                <td>
                    <input type="text" name="phone" id="phone" value="{{ (!empty($param['phone'])) ? $param['phone'] : "" }}" class="form-control col-md-6" placeholder="เบอร์โทร">
                </td>
            </tr>
            <tr>
                <th>Username</th>
                <td>
                    <input type="text" name="user" id="user" value="{{ (!empty($param['user'])) ? $param['user'] : "" }}" class="form-control col-md-6" placeholder="User เล่น">
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



    @yield('content')

    <!-- Modal -->
    <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalForm" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">

        </div>
    </div>

</div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script>
        $('select#optDomain').on('change', function(){
            let val = $(this).val();
            window.location = "?domain="+val;
        })
    </script>

    @yield('js')

</body>
</html>