<?php
include('../../common/header.php');
include 'config.php';

   $mailbox->deleteMsg($_REQUEST['msg_id'],$user_id,$db_table_trash);
if($_REQUEST['PnameNum']==='0'){
    echo '<script>window.location = "inbox.php";</script>';
}else if($_REQUEST['PnameNum']==='1'){
      echo '<script>window.location = "send.php";</script>';
}else{
     echo '<script>window.location = "trash.php";</script>';
}

include('../../common/footer.php')
?>
 
 