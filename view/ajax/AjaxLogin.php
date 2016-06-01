<?php
include('../../view/common/top.php');
@session_start();
$uname = ($_REQUEST['username']);
$passwd = md5($_REQUEST['password']);


$query = $fpdo->from('customers')->where(array('email' => $uname, 'password' => $passwd))->fetch();
$active=$query['active'];
if ($query['id'] != "" && $active==1) {
    $_SESSION['CUSTOMER_Name'] = $query['name'];
    $_SESSION['CUSTOMER_ID'] = $query['id'];
   echo json_encode(_PREF . $_SESSION['pLang'] . "/page49/My-Account");
}else if ($query['id'] !="" && $active==0) {
        $_SESSION['error_account_activation']='error';
        echo json_encode(_PREF . $_SESSION['pLang'] . "/page48/Login");
} else {
    $_SESSION['error_login'] = 'error';
    echo json_encode(_PREF . $_SESSION['pLang'] . "/page48/Login");
     
}
?>