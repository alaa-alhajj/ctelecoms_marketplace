<?php
$db_table="news_news";
$db_table_types="news_types";
$LPP = 8;

$cols=array('title','news_type','start_date','end_date','brief','details','photos');

$Savecols=array('title','news_type','start_date','end_date','brief','details','photos');
$colsUpdate= array('title','news_type','start_date','end_date','brief','details','photos');

$types=array('title'=>"text",'news_type'=>"select",'start_date'=>'date','end_date'=>'date','brief'=>"SimpleTextEditor",'details'=>"FullTextEditor",'photos'=>'photos');
$source=array('news_type'=>array("0"=>"news_types","1"=>"name_en","2"=>"id"),'lang'=>array("0"=>"languages","1"=>"lang_name","2"=>"lang","type='admin'"));
$required=array("title"=>"required","news_type"=>"required","start_date"=>"required","end_date"=>"required","brief"=>"required","details"=>"required");

// types
$tcols=array('name_en','name_ar');
$tSavecols=array('name_en','name_ar');
$tcolsUpdate= array('name_en','name_ar');
$ttypes=array('name_en'=>"text",'name_ar'=>'text');
$trequired=array("name_en"=>"required",'name_ar'=>'required');

$pageList="listNews.php";
$pageInsert="insertNews.php";
$pageUpdate="updateNews.php";
$news_type="listNewsTypes.php";
?>
