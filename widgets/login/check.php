<?php
@session_start();

if ($_SESSION['AGI_SW_LOGIN']=="") {

    echo "<script>document.location='"._PREF."admin/views/login/login.php"."';</script>";   
}
