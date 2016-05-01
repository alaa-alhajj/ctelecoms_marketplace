<?php

if ($_REQUEST['lpp']) {
    $LPP=$_REQUEST['lpp'];
}
$pn = 0;
if ($_REQUEST['pn'])
    $pn = $_REQUEST['pn'];
$start = $pn * $LPP;
?>