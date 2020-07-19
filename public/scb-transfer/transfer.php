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
                        <h5 class="card-title">ไปยัง</h5>
                        <form id="verify" method="post" autocomplete="off">
                            <div class="form-group row">
                                <label class="col-lg-4 col-sm-12 col-form-label">เลขบัญชี</label>
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" autocomplete="false" class="form-control-plaintext brx cx-card"
                                        placeholder="กรอกเลขบัญชีที่จะโอนไป" name="accnum">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-sm-12 col-form-label">จำนวน</label>
                                <div class="col-lg-8 col-sm-12">
                                    <input type="text" autocomplete="false" class="form-control-plaintext brx cx-card"
                                        placeholder="จำนวน เงินที่จะโอน" name="amount">
                                </div>
                            </div>
                            <input type="hidden" name="bankID" value="014" />
                            <div class="text-center">
                                <button class="glow-on-hover backdrop" type="submit">ตรวจสอบข้อมูล</button>
                            </div>
                        </form>

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
                                onclick="location.href= 'transaction.php'">ประวัติการโอน</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-8 col-md-6 col-sm-6 pb-3">
            <div class="card backdrop">
                <div class="card-body ">
                    <h5 class="card-title">บัญชี</h5>
                    <div class="d-inline-flex">
                        <div class="row pl-3 pr-3 pt-2">
                            <?php
                            if (!isset($eligi['data'])) {
                                echo '<h5 class="card-title">เกิดข้อผิดพลาด f5</h5>';
                                return;
                            }
                            foreach ($eligi['data'] as $v) {
                                # code...

                                ?>

                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-4 pb-3 pb-2 pt-2 br-x fadex et <?php if ($v['bankCode'] === "014") {
                                                                                                                        echo "active";
                                                                                                                    } ?>"
                                data-id="<?php echo $v['bankCode'] ?>">
                                <img class=" mr-3" src="<?php echo ps($v['bankLogo']) ?>"
                                    id="b<?php echo $v['bankCode'] ?>" alt="" width="48" height="48">
                                <?php echo $v['bankName'] ?>
                            </div>

                            <?php

                            }
                            ?>

                        </div>

                    </div>
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