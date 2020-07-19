<?php
require_once 'header.php';
?>

<?php
require_once 'bar.php';

if (!isLogin()) {
    echo "<script type='text/javascript'>window.location = 'index.php'</script>";
    exit;
}
$summary = (object) $bank->getSummary();
$eligi = $bank->getEligiblebanks();
?>


<div class="container mt-5">
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 pb-3">
            <div class="pb-3">
                <div class="card backdrop">
                    <div class="card-body">
                        <h5 class="card-title">จาก</h5>
                        <div class="card cx-card text-left p-3">
                            <h5 class="card-title cx-t-1"><?php echo $bank->accdisp ?></h5>
                            <p class="cx-w pt-0"><?php echo $summary->totalAvailableBalance ?> บาท</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pb-3">
                <div class="card backdrop">
                    <div class="card-body">
                        <h5 class="card-title">ฟังก์ชั่น</h5>
                        <div class="text-center">
                            <button class="glow-on-hover backdrop" type="button" id="nobut">กดเงินไม่ใช้บัตร</button>
                        </div>
                        <div class="text-center pt-3">
                            <button class="glow-on-hover backdrop" type="button" id="history"
                                onclick="location.href= 'transfer.php'">โอนเงิน</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-8 col-md-6 col-sm-6 pb-3">
            <div class="card backdrop">
                <div class="card-body ">
                    <h5 class="card-title">บัญชี</h5>
                    <table data-toggle="table" data-url="./src/transaction.php" data-pagination="true"
                        data-search="true">
                        <thead>
                            <tr>
                                <th data-sortable="true" data-field="id">#</th>
                                <th data-field="action">action</th>
                                <th data-field="amount">amount</th>
                                <th data-field="time_at">time_at</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>
<div class="modal fade " id="verify-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content mc-x">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ตรวจสอบการโอนเงิน</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card flex-md-row mb-4 box-shadow h-md-250" id="verify-form">
                    <div class="card-body d-flex flex-column align-items-start">
                        <ul class="mt-56">

                        </ul>

                    </div>
                    <img class="card-img-right flex-auto d-none d-md-block" style="width: 300px; height: 300px;" src=""
                        data-holder-rendered="true">
                </div>
            </div>
            <div class="modal-footer">
                <button class="glow-on-hover backdrop w-100 submitx" type="submit">ยืนยันการโอน</button>

            </div>

        </div>
    </div>
</div>
<?php
require_once 'footer.php';
?>