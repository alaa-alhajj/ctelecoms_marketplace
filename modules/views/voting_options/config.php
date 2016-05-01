<?php
$db_table="voting_options";
$LPP = 20;
$cols=array('vote_id','option','counts');
$types=array('vote_id'=>'static','option'=>'text','counts'=>'static');
$_source=array('vote_id'=>array('voting_voting','id','question',$_REQUEST['vote_id']));
$extra=array();
$length=array();
$values=array();
$ClassMainField = "row";
$classSubMain = array('col-xs-2', 'pull-left', 'col-xs-9');


?>