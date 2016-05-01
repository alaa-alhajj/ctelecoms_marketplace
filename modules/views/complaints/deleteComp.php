<?php
include('../../common/header.php');
include 'config.php';


if($_REQUEST['PnameNum']==='0'){
    $complaintsObj->deleteComp($_REQUEST['comp_id'],$db_table,$_REQUEST,$dcols);
    echo '<script>window.location = "inbox.php";</script>';
}else{
    $complaintsObj->deleteComp($_REQUEST['comp_id'],$db_table,$_REQUEST,$dcols);
    echo '<script>window.location = "trash.php";</script>';
}

include('../../common/footer.php');
?>