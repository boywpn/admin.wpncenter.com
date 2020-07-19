<?php
require_once 'header.php';
if (isset($_GET['pin'])) {
    if (trim($_GET['pin']) == $config['pin']) {
        $_SESSION['login'] = true;
    }
}
if (isLogin()) {
    echo "<script type='text/javascript'>window.location = 'transfer.php'</script>";
    exit;
}
?>
<style>
.bd-placeholder-img {
    font-size: 1.125rem;
    text-anchor: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

@media (min-width: 768px) {
    .bd-placeholder-img-lg {
        font-size: 3.5rem;
    }
}

html,
body {
    height: 100%;
}

body {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-align: center;
    align-items: center;
    padding-top: 40px;
    padding-bottom: 40px;
    background-color: #f5f5f5;
}

.form-signin {
    width: 100%;
    max-width: 330px;
    padding: 15px;
    margin: auto;
}

.form-signin .checkbox {
    font-weight: 400;
}

.form-signin .form-control {
    position: relative;
    box-sizing: border-box;
    height: auto;
    padding: 10px;
    font-size: 16px;
}

.form-signin .form-control:focus {
    z-index: 2;
}

.form-signin input[type="email"] {
    margin-bottom: -1px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
}

.form-signin input[type="password"] {
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}
</style>

<body class="">
    <form class="form-signin" method="POST" action="./src/login.php">
        <h1 class="h3 mb-3 font-weight-normal text-center">เข้าสู่ระบบ</h1>

        <div class="form-group">
            <label for="exampleInputPassword1">รหัสผ่าน</label>
            <input type="password" class="form-control" id="exampleInputPassword1" name="password"
                placeholder="Password" required>
        </div>

        <button class="btn btn-lg btn-primary btn-block" type="submit">เข้าสู่ระบบ</button>

    </form>
</body>
<?php
require_once 'footer.php';
?>