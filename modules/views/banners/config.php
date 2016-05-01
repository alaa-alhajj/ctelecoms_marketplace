<?php
$db_table="banners_banners";

$LPP = 8;

$cols=array('title','start_date','end_date','brief','details','lang','photos');

$Savecols=array('title','start_date','end_date','brief','details','lang','photos');
$colsUpdate= array('title','start_date','end_date','brief','details','lang','photos');
$types=array('title'=>"text",'start_date'=>'date','end_date'=>'date','brief'=>"SimpleTextEditor",'details'=>"FullTextEditor",'lang'=>'select','photos'=>'photo');
$source=array('lang'=>array("0"=>"languages","1"=>"lang_name","2"=>"lang","type='admin'"));
$required=array("title"=>"required","start_date"=>"required","end_date"=>"required","brief"=>"required","details"=>"required");

$pageList="listBanners.php";
$pageInsert="insertBanner.php";
$pageUpdate="updateBanner.php";
?>
