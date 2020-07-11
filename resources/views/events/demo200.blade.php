@extends('events.app')

@section('content')

    {!! $text_warning !!}
    {!! $text_danger !!}

    @if(empty($members))

        <div class="alert alert-danger">ไม่มีข้อมูลสมาชิก!!!</div>

    @else

        <table class="table table-bordered">
            <tr>
                <th class="text-right">ชื่อลูกค้า</th>
                <td>{{ $members['withdraw_name'] }}</td>
                <th class="text-right">เบอร์ลูกค้า</th>
                <td>{{ $members['m_username'] }}</td>
            </tr>
            <tr>
                <th class="text-right">เอเจนท์</th>
                <td>{{ ($members['agent'] != 1) ? $members['members_agent']['name'] : "เว็บหลัก" }}</td>
                <th class="text-right">สมัครวันที่</th>
                <td>{{ $members['created'] }}</td>
            </tr>
            <tr>
                <th class="text-right">รายการสมัคร</th>
                <td colspan="3">
                    @foreach($members['order'] as $order)
                        {{ $order['id'] }} | {{ number_format($order['money'], 2) }} | {{ $order['datetime'] }} <br>
                    @endforeach
                </td>
            </tr>
        </table>

        @if($show_table)
            <table class="table table-bordered table-hover table-striped">
                <tr class="text-center">
                    <th class="table-active">Web Game</th>
                    <th class="table-active">Username</th>
                    <th class="table-active">สร้างเมื่อ</th>
                    <th class="table-active">สถานะ</th>
                    <th class="table-active">ตัวเลือก</th>
                </tr>
                @foreach($game_web as $web)
                    @php
                        $textKey = $members['id']."-".$web->new_id;
                        $code = Crypt::encryptString($textKey);
                    @endphp
                    <tr>
                        <td align="center">{{ $web->name }}</td>
                        <td align="center">{{ (isset($member_user[$web->id])) ? $member_user[$web->id]['username'] : "" }}</td>
                        <td align="center">{{ (isset($member_user[$web->id])) ? $member_user[$web->id]['created'] : "" }}</td>
                        <td align="center">
                            @if(isset($member_user[$web->id]['new']))
                                @if($member_user[$web->id]['new']['have_promo'] == 1)
                                    รับโปรเมื่อ {{ $member_user[$web->id]['new']['promo_at'] }} รหัส: {{ $member_user[$web->id]['new']['promo_code'] }}
                                @endif
                            @endif
                        </td>
                        <td align="center">
                            @if(isset($member_user[$web->id]['username']))
                                <button type="button" class="btn btn-warning btn-sm">ดูประวัติ</button>
                                <button type="button" onclick="formTransfer('{{ $member_user[$web->id]['new_id']  }}', '{{ $param['event'] }}')" class="btn btn-danger btn-sm">ฝาก-ถอน</button>
                            @else
                                <button type="button" onclick="genUsername('{{ $members['A_I'] }}', '{{ $web->new_id }}')" class="btn btn-info btn-sm">เปิด User</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif

    @endif

@endsection

@section('js')

    <script>
        function genUsername(id, game_id){
            var r = confirm("ต้องการสร้าง Username ของเว็บนี้หรือไม่?");
            if (r == true) {

                $.ajax({
                    type: "GET",
                    url: "/events/gen-username",
                    data: {id: id, game_id: game_id},
                    dataType: "json",
                    // headers: {
                    //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    // },
                    success: function (res) {
                        console.log(res)

                        if(!res.status){
                            alert(res.message);
                            return false;
                        }

                        alert(res.message);
                        location.reload();
                    }
                });

            } else {
                return false;
            }
        }

        function formTransfer(username_id, code){
            $.ajax({
                type: "GET",
                url: "/events/transfer-form",
                data: {username_id: username_id, code: code},
                dataType: "html",
                // headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // },
                success: function (res) {
                    console.log(res)

                    var $modal = $('#modalForm');

                    $modal.find('.modal-dialog').html(res);

                    $modal.modal('show');

                }
            });
        }
    </script>

@endsection