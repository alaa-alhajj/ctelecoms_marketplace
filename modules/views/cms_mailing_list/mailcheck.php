<?php
//$uid=41;
//$archive_id=75;
include 'config.php';
include '../../common/header.php';

$uid= $_REQUEST['uid'];
$archive_id= $_REQUEST['archive_id'];

$status=$mailList->saveUIDafterOppenedEmail($uid,$archive_id);
/*
if($status){
    echo "update true";
}else{
    echo "update failed";
}
 */



