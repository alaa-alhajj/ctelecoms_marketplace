<?php
  @session_start();
if ($_POST['LoginC'] === 'login' && $_REQUEST['username'] != "" && $_REQUEST['password'] != "") {
    global $utils;
    global $pLang;
    $uname = ($_REQUEST['username']);
    $passwd = md5($_REQUEST['password']);
    $query = $this->fpdo->from('customers')->where(array('email' => $uname, 'password' => $passwd))->fetch();
    $active=$query['active'];
    if ($query['id'] !="" && $active==1) {
        $_SESSION['CUSTOMER_Name'] = $query['name'];
        $_SESSION['CUSTOMER_ID'] = $query['id'];

       // echo $pLang;
          $utils->redirect(_PREF.$_SESSION['pLang']."/page49/My-Account");
    }else if ($query['id'] !="" && $active==0) {
        $_SESSION['error_account_activation']='error';
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
    <div class="alert alert-danger error-login">Incorrect email or password</div>
    <?
    }
    unset( $_SESSION['error_login']);
    ?>
    <?if( $_SESSION['error_account_activation'] !=""){?>
    <div class="alert alert-danger error-login">Please activate your account.</div>
    <?
    }
    unset( $_SESSION['error_account_activation']);
    ?>
</div>
<?


