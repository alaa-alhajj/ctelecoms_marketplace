<?php
session_start();
ini_set('short_open_tag', '1');
include_once ("../../../config.php");
include_once '../../controller/include.inc.php';
$pn = 0;
if ($_REQUEST['pn']) {
    $pn = $_REQUEST['pn'];
}
$start = $pn * $LPP;

$voiControl = new VoilaController();
$utils = new utils();
$ob_roles = $voiControl->obRoles();
$ob_roles->checkUserLogin();
$mailbox = $voiControl->ObMailBox();
$mailList=$voiControl->ObMailList();
$complaintsObj = $voiControl->ObComplaints();
$temp_maker = $voiControl->obTemplateMaker();
$ob_cms_lang = $voiControl->obcms_lang();

$user_id = $_SESSION['cms-user-id'];
$grp_id = $_SESSION['cms-grp-id'];

if ($_REQUEST['cmsMID'] != "") {
    $module_id = $_SESSION['cmsMID'] = $_REQUEST['cmsMID'];
} else {
    $module_id = $_SESSION['cmsMID'];
}
if ($_REQUEST['table_id'] != "") {
    $table_id = $_SESSION['table_id'] = $_REQUEST['table_id'];
} else {
    $table_id = $_SESSION['table_id'];
}

$_REQUEST['table_id']=$table_id;


if($_REQUEST['cmsMlang'] != "") {
    $_SESSION['cmsMlang'] = $_REQUEST['cmsMlang'];
}elseif(!isset($_SESSION['cmsMlang'])){
    $_SESSION['cmsMlang']=$ob_cms_lang->getDefaultLang();
}

$cms_active_langs = $_SESSION['cms_active_langs'] = $ob_cms_lang->getAllLangs();

$cmsMlang = $_SESSION['cmsMlang'];
