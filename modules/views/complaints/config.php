<?php

$db_table="cms_complaints";
$db_table_evaluation="cms_complaints_evaluation";
$db_table_replies="cms_complaints_replies";
$db_table_status="cms_complaints_status";
$db_cms_emps="cms_users";
$db_users="users_users";
$db_cms_complaints_departments="cms_complaints_departments";

$LPP = 20;
$start = $pn*$LPP;
$limit = "LIMIT $start,$LPP ";

$cols=array('user_id','title','details','start_date','end_date','file','status_id');
$Savecols=array('user_id','title','details','start_date','end_date','file','status_id');
$colsUpdate=array('user_id','title','details','start_date','end_date','file','status_id');

$dcols=array('flag');
$dcolsUpdate=array('isRead');

// replies
$rcols=array('date','body','file');
$rSavecols=array('comp_id','user_id','from','admin_id','date','body','file');
$rcolsUpdate=array('comp_id','user_id','from','admin_id','date','body','file');

$inboxPage="inbox.php";
$sendPage="send.php";
$trashPage="trash.php";

//$dpt_id="3";
?>
