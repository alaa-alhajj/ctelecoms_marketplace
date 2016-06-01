<?php
include_once ("../config.php");
include_once '../view/controller/include.inc.php';
$_SESSION['_PREF']=_PREF;
$pLang='en';
$_SESSION['pLang']='en';
$dots = $_SESSION['dots'] = '../../';
$pn = 0;
if($_REQUEST['pn'])	{$pn = $_REQUEST['pn'];}
$start = $pn*$LPP;

$voiControl=new VoilaController();
$utils=new utils();
$widgets=new widgets();
$ticket_id=$_REQUEST['tid'];
$get_page_id=$fpdo->from('lz_tickets')->where("id='$ticket_id'")->fetch();

   $utils->redirect(_PREF.$_SESSION['pLang']."/page".$get_page_id['page_id']."/ticket");
?>