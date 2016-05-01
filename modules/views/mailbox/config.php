<?php
$db_table="cms_messages";
$db_table_directions="cms_messages_directions";
$db_table_replies="cms_messages_replies";
$db_cms_users="cms_users";
$db_groups="cms_groups";
$db_table_trash="cms_messages_trash";

$LPP = 20;
$start = $pn*$LPP;
$limit = "LIMIT $start,$LPP ";

$cols=array('title','to_group','groups','to_user','users','body','file');
$Savecols=array('title','date','body','file');
$colsUpdate= array('title','date','body','file');

$dcols=array('flag'); //when delete messages just set flag = 1

// direction
$dSavecols=array('msg_id','from_id','to_id','isRead');
$dcolsUpdate=array('id','msg_id','to_id','isRead');
// replies
$rcols=array('date','body','file');
$rSavecols=array('msg_id','date','body','user_id','file');
$rcolsUpdate=array('msg_id','date','body','file');

$rtypes=array('body'=>"FullTextEditor",'file'=>'attach');
$rrequired=array('body'=>'required');

$inboxPage="inbox.php";
$sendPage="send.php";
$trashPage="trash.php";


?>
