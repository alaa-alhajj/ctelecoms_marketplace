<?php
  @session_start();
if ($_POST['LoginC'] === 'login' && $_REQUEST['username'] != "" && $_REQUEST['password'] != "") {
    global $utils;
    global $pLang;
    $uname = ($_REQUEST['username']);
    $passwd = md5($_REQUEST['password']);
    $query = $this->fpdo->from('customers')->where(array('email' => $uname, 'password' => $passwd,'active' => 1))->fetch();
    
    if ($query['id'] !="") {
      
        $_SESSION['CUSTOMER_Name'] = $query['name'];
        $_SESSION['CUSTOMER_ID'] = $query['id'];

       // echo $pLang;
          $utils->redirect(_PREF.$_SESSION['pLang']."/page49/My-Account");
    }else{
        $_SESSION['error_login']='error';
    }
}
?>
<div class="logo"></div>
<div class="login-block">
    <h1>Login</h1>
    <form action="" method="post">
        <input type="text" value="" placeholder="Username" id="username" name="username" />
        <input type="password" value="" placeholder="Password" id="password" name="password" />
        <button type="submit">Login</button><input type='hidden' name='LoginC' value='login'>
    </form>
    <?if( $_SESSION['error_login'] !=""){?>
    <div class="alert alert-danger error-login">incorrect email or password</div>
    <?
    }
    unset( $_SESSION['error_login']);
    ?>
</div>
<?


