<?
@session_start();
if ($_POST['LoginC'] === 'login' && $_REQUEST['username'] != "" && $_REQUEST['password'] != "") {
    global $utils;
    global $pLang;
    $uname = ($_REQUEST['username']);
    $passwd = md5($_REQUEST['password']);
    $query = $this->fpdo->from('customers')->where(array('email' => $uname, 'password' => $passwd))->fetch();
    if (count($query) >= 0) {
        @session_start();
        $_SESSION['CUSTOMER_Name'] = $query['name'];
        $_SESSION['CUSTOMER_ID'] = $query['id'];

       // echo $pLang;
          $utils->redirect(_PREF.$pLang."/page58/Payment");
    }
}
if($_SESSION['CUSTOMER_ID'] !=""){
     $this->redirect(_PREF.$_SESSION['pLang']."/page58/Payment");

}else{
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
                </div>
            </div>
        </div>
    </div>
</div>
<?
}
?>
