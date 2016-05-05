<?php
include "../../common/top.php";
@session_start();
unset($_SESSION['AGI_SW_LOGIN']);
$utils->redirect('../login/login.php');