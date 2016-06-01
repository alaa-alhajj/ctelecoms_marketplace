<?php
include('../../view/common/top.php');
@session_start();
$uname = ($_REQUEST['username']);
$passwd = md5($_REQUEST['password']);


$query = $fpdo->from('customers')->where(array('email' => $uname, 'password' => $passwd,'active' => 1))->fetch();

if ($query['id'] != "") {

    $_SESSION['CUSTOMER_Name'] = $query['name'];
    $_SESSION['CUSTOMER_ID'] = $query['id'];
   echo json_encode(_PREF . $_SESSION['pLang'] . "/page49/My-Account");

} else {
    $_SESSION['error_login'] = 'error';
    echo json_encode(_PREF . $_SESSION['pLang'] . "/page48/Login");
     
}
?>