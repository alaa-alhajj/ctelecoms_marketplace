<?php
session_start();
//error_reporting(0);
include_once ("../../config.php");
include_once '../controller/include.inc.php';
$_SESSION['_PREF']=_PREF;
$pLang='ar';
$_SESSION['pLang']='ar';
$dots = $_SESSION['dots'] = '../../';
$pn = 0;
if($_REQUEST['pn'])	{$pn = $_REQUEST['pn'];}
$start = $pn*$LPP;

$voiControl=new VoilaController();
$utils=new utils();
$widgets=new widgets();

if(!isset($_SESSION['Shopping_Cart'])){
        $_SESSION['Shopping_Cart']=array();
}

if(!isset($_SESSION['compareIDs'])){
        $_SESSION['compareIDs']=array();
}