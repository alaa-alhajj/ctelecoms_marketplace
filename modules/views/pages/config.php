<?php
$db_table="pages_pages";
$LPP = 8;

$cols=array('title','brief','details','lang');
$Savecols=array('title','brief','details','lang');
$colsUpdate= array('title','brief','details','lang');

$types=array('title'=>"text",'brief'=>'SimpleTextEditor','details'=>"FullTextEditor",'lang'=>'select');
$source=array('lang'=>array("0"=>"languages","1"=>"lang_name","2"=>"lang","type='admin'")); 
$required=array("title"=>"required","brief"=>"required","details"=>"required",'lang'=>'required');

$pageList="listPages.php";
$pageInsert="insertPage.php";
$pageUpdate="updatePage.php";
?>