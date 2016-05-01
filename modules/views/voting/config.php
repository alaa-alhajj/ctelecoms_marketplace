<?php
$db_table="voting_voting";
$LPP = 20;
$cols=array('question','create_date','publish','lang');
$types=array('question'=>'text','create_date'=>"date",'publish'=>'checkbox','lang'=>'select');
$source=array('lang'=>array('cms_lang','id','title'));
$extend=array('lang'=>array('cms_lang','id','title'));
$length=array();
$values=array();
$ClassMainField = "row";
$classSubMain = array('col-xs-2', 'pull-left', 'col-xs-9');
?>