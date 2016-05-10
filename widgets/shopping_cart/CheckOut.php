<?
@session_start();
@session_start();
if ($_POST['LoginC'] === 'login' && $_REQUEST['username'] != "" && $_REQUEST['password'] != "") {
    global $utils;
    global $pLang;
    $uname = ($_REQUEST['username']);
    $passwd = md5($_REQUEST['password']);
    $query = $this->fpdo->from('customers')->where(array('email' => $uname, 'password' => $passwd))->fetch();

    if ($query['id'] != "") {

        $_SESSION['CUSTOMER_Name'] = $query['name'];
        $_SESSION['CUSTOMER_ID'] = $query['id'];

        // echo $pLang;
        $utils->redirect(_PREF . $pLang . "/page58/Payment");
    } else {
        $_SESSION['error_login'] = 'error';
    }
}

if (isset($_REQUEST['SignUp'], $_REQUEST['full_name'], $_REQUEST['email'], $_REQUEST['password'], $_REQUEST['repeat_password']) && $_REQUEST['SignUp'] == 'signup') {

    $_REQUEST['SignUp'] = 'signed';
    $full_name = addslashes($_REQUEST['full_name']);
    $email = addslashes($_REQUEST['email']);
    $company = addslashes($_REQUEST['company']);
    $city = addslashes($_REQUEST['city']);
    $adress = addslashes($_REQUEST['adress']);
    $password = $_REQUEST['password'];
    $repeat_password = $_REQUEST['repeat_password'];
    $successMSG = '';
    $ErrorMSG = '';
    if ($password === $repeat_password) {
        global $utils;
        global $pLang;

        $insert_id = $this->fpdo->insertInto('customers')->values(array('name' => $full_name, 'email' => $email, 'password' => md5($password), 'company' => $company, 'city' => $city, 'adress' => $adress))->execute();
        if ($insert_id != '') {

            $successMSG = "<div class='alert alert-success'>Successful, welcome <strong>$full_name<strong> in our site  </div>";
            @session_start();
            $_SESSION['CUSTOMER_Name'] = $full_name;
            $_SESSION['CUSTOMER_ID'] = $insert_id;
            $utils->redirect(_PREF . $pLang . "/page58/My-Account");
        } else {
            $ErrorMSG = "<div class='alert alert-danger'>Error, this Email is aleady exist.</div>";
        }
    } else {
        $ErrorMSG = "<div class='alert alert-danger'>Error, password don't match repeat password. Enter password again.</div>";
    }
}

if ($_SESSION['CUSTOMER_ID'] != "") {
    $this->redirect(_PREF . $_SESSION['pLang'] . "/page58/Payment");
} else {
    ?>

    <div class="row row-nomargin">
        <div class="col-xs-3">
            <ul id="shopping-cart">
                <li><a href="#">Shopping Cart</a></li>
                <li  class="active-shopping"><a href="#">Checkout</a></li>
                <li><a href="#">Payment</a></li>
                <li><a href="#">Order Details</a></li>

            </ul>
        </div>
        <div class="col-xs-9">
            <div class="row row-nomargin">
                <div class="col-sm-6 nopadding">
                    <h1>New Customer</h1>
                    <div class="login-block sign-up-block">
                        <h1>Sign up</h1>
                        <form class="form-horizontal" role="form" method="post" action="">
                            <?= $successMSG ?>
                            <?= $ErrorMSG ?>
                            <input type="text"  name='full_name' id="full_name" placeholder="Full name" required>
                            <input type="text"  name='company' id="company" placeholder="Company">
                            <input type="text"  name='city' id="city" placeholder="City">
                            <input type="text"  name='adress' id="adress" placeholder="Adress">
                            <input type="email"  name='email' id="email" placeholder="Email" required>
                            <input type="password"  name='password' id="password" placeholder="Password" required>
                            <input type="password"  name='repeat_password' id="repeat_password" placeholder="Re-password" required>
                            <button type="submit"  id="submit" name='SignUp' value="signup">Sign Up</button>
                        </form>
                    </div>
                </div>
                <div class="col-sm-6 nopadding">
                    <h1>Old Customer</h1>
                    <div class="login-block">
                        <h1>Login</h1>
                        <form id="AjaxLogin" action="" method="post">
                            <input type="text" value="" placeholder="Username" id="username" name="username" />
                            <input type="password" value="" placeholder="Password" id="password" name="password" />
                            <button type="submit">Login</button><input type='hidden' name='LoginC' value='login'>
                        </form>
                        <? if ($_SESSION['error_login'] != "") { ?>
                            <div class="alert alert-danger error-login">incorrect email or password</div>
                            <?
                        }
                        unset($_SESSION['error_login']);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?
}
?>
