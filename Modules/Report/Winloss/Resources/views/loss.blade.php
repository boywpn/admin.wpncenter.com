<style>
    table#winloss-table{
        font-size: 12px;
        font-family: Tahoma, Helvetica, sans-serif;
        width: 100%;
        border-collapse: collapse;
    }

    table#winloss-table th, table#winloss-table td{
        padding: 5px;
    }

    table#winloss-table td a{
        color: blue;
    }
    table#winloss-table td a:hover{
        text-decoration: underline;
    }

    table#winloss-table thead th{
        background-color: #0A5C9F;
        color: #ffffff;
        text-align: center;
    }

    table#winloss-table tfoot th{
        background-color: #9fcbea;
        color: #000;
    }

    table#winloss-table td.text-red, table#winloss-table th.text-red{
        color: #B50000 !important;
    }

    .taking-block{
        width: 100%;
        background-color: #F5E3E3;
        display: inline-block;
        font-weight: bold;
        color: #000;
    }

    .game-type-check label {
        min-width: 120px;
    }

    .text-blue{
        color: blue;
    }
    .text-black-bold{
        color: #000;
        font-weight: bold;
    }
    .text-red{
        color: #B50000;
    }
</style>

    <table border="1" id="winloss-table" class="table-bordered table-hover table-striped" cellspacing="1" cellpadding="3">
        <thead>
        <tr>
            <th rowspan="2">บัญชี</th>
            <th rowspan="2">ติดต่อ</th>
            <th rowspan="2">วันที่</th>
            <th rowspan="2">เงินหมุนเวียน</th>
            <th rowspan="2">เงินถูกต้อง</th>

            <th rowspan="2">จำนวนเดิมพัน</th>


            <th colspan="4">สมาชิก</th>
        </tr>
        <tr>
            <th>ชนะ/แพ้</th>
            <th>คอมมิชชั่น</th>
            <th>ยอดรวม</th>
            <th>คืนเสีย 5%</th>
        </tr>
        </thead>
        <tbody>

        @php
            $total_loss = 0;
            $total_back_credit = 0;
            $count_back = 0;
            $total_winloss = 0;
        @endphp

        @foreach($lists as $id => $list)

            @php
                $total_member_winloss = 0;
                $chk_all = 1;
                $credit = 0;
            @endphp

            @foreach($date AS $d)

                @php
                    $winloss = (isset($list['date'][$d][0]->member_winloss)) ? $list['date'][$d][0]->member_winloss : "";

                    if(!empty($winloss)){
                        $total_member_winloss += $winloss;
                        $total_winloss += $winloss;
                    }else{
                        $chk_all = 0;
                    }
                @endphp

            <tr>
                <td>{{ $list['username'] }}</td>
                <td>{{ $list['name'] }}</td>
                <td>{{ $d }}</td>
                <td>{{ (isset($list['date'][$d][0]->turnover)) ? $list['date'][$d][0]->turnover : "" }}</td>
                <td>{{ (isset($list['date'][$d][0]->valid_amount)) ? $list['date'][$d][0]->valid_amount : "" }}</td>
                <td>{{ (isset($list['date'][$d][0]->stack_count)) ? $list['date'][$d][0]->stack_count : "" }}</td>
                <td>{{ $winloss }}</td>
                <td>{{ (isset($list['date'][$d][0]->member_comm)) ? $list['date'][$d][0]->member_comm : "" }}</td>
                <td>{{ (isset($list['date'][$d][0]->member_total)) ? $list['date'][$d][0]->member_total : "" }}</td>
                <td></td>
            </tr>
            @endforeach

            @php
                if($total_member_winloss > 0){
                    $chk_all = 0;
                }else{
                    $credit = ($total_member_winloss * 0.05) * -1;

                    if($chk_all){
                        $total_loss += $total_member_winloss;
                        $total_back_credit += $credit;
                        $count_back += 1;
                    }
                }
            @endphp
            <tr style="background-color: {{ (!$chk_all) ? "red" : "green" }}; font-size: 18px; color: white">
                <td colspan="6"></td>
                <td>{{ $total_member_winloss . " CHK: " . $chk_all }}</td>
                <td colspan="2"></td>
                <td>{{ $credit }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #6d4c41; font-size: 18px; color: white">
                <td colspan="6"></td>
                <td>รวมได้/เสีย {{ $total_winloss }}</td>
                <td colspan="2"></td>
                <td></td>
            </tr>
            <tr style="background-color: #6d4c41; font-size: 18px; color: white">
                <td colspan="6"></td>
                <td>รวมเสียคิดคืน {{ $total_loss }} จำนวน {{ $count_back }} คน</td>
                <td colspan="2"></td>
                <td>5% รวมคืน {{ $total_back_credit }}</td>
            </tr>
        </tfoot>
    </table>