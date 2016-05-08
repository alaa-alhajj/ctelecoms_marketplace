<?php
@session_start();
unset($_SESSION['CUSTOMER_Name']);
unset($_SESSION['CUSTOMER_ID']);
unset($_SESSION['Shopping_Cart']);
unset($_SESSION['PROMO_CODE']);
unset($_SESSION['compareIDs']);
echo _PREF.$_SESSION['pLang']."/page48/Login";

$this->redirect(_PREF.$_SESSION['pLang']."/page48/Login");