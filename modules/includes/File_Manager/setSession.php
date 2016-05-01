<?php

@session_start();
if ($_REQUEST['json'] != "") {
    $_SESSION['json_selected'] = $_REQUEST['json'];
}