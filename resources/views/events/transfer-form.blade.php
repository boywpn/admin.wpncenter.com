<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">ทำรายการฝาก-ถอน</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="formTransfer" action="">
            <input type="hidden" name="id" value="{{ $username['id'] }}">
            @csrf
            <table class="table table-bordered">
                <tr>
                    <th class="table-primary">
                        Username
                    </th>
                    <td colspan="2">
                        <input type="text" readonly class="form-control-plaintext" id="username" name="username" value="{{ $username['username'] }}">
                    </td>
                </tr>
                <tr>
                    <th class="table-primary">
                        รหัสทำรายการ
                    </th>
                    <td colspan="2">
                        <input type="text" readonly class="form-control-plaintext" id="order_code" name="order_code" value="{{ $get['code'] }}">
                    </td>
                </tr>
                <tr>
                    <th class="table-primary">
                        จำนวนเงิน
                    </th>
                    <td>
                        <input type="text" class="form-control" id="amount" name="amount" value="">
                        <div class="help-block" style="color: red">0 ถอนทั้งหมด หรือระบุจำนวนที่ต้องการถอน เช่น -500</div>
                    </td>
                    <td>
                        <button type="button" onclick="transferCredit()" class="btn btn-primary">ทำรายการ</button>
                    </td>
                </tr>
                <tr>
                    <th class="table-primary">
                        ผลลัพธ์
                    </th>
                    <td colspan="2"><pre id="json">Result!!!</pre></td>
                </tr>
            </table>
        </form>
    </div>
</div>

<script>
    function transferCredit() {
        var frm = $('#formTransfer');
        var order_code = frm.find('input[name=order_code]').val();
        var id = frm.find('input[name=id]').val();
        var username = frm.find('input[name=username]').val();
        var amount = frm.find('input[name=amount]').val();
        var type;

        if(amount == ''){
            alert('กรุณาระบุจำนวนเงินที่ต้องการทำรายการ!');
            return false
        }

        var r = confirm("ต้องการทำรายการนี้หรือไม่?");
        if (r == true) {

            document.getElementById("json").innerHTML = "กำลังทำรายการ...";

            if(amount > 0){
                type = 1
            }else{
                type = 2
            }

            $.ajax({
                type: "POST",
                url: "/events/transfer-credit",
                data: {order_code: order_code, username: username, amount: amount, id: id, type: type},
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    console.log(res)

                    document.getElementById("json").innerHTML = JSON.stringify(res, undefined, 2);
                }
            });

        } else {
            return false;
        }
    }
</script>