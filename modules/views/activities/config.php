<?php
$db_table="activities_activities";
$db_table_types="activities_types";
$LPP = 8;

$cols=array('title','activities_type','start_date','end_date','brief','details','photos');
$Savecols=array('title','activities_type','start_date','end_date','brief','details','photos');
$colsUpdate= array('title','activities_type','start_date','end_date','brief','details','photos');
$types=array('title'=>"text",'activities_type'=>"select",'start_date'=>'date','end_date'=>'date','brief'=>"SimpleTextEditor",'details'=>"FullTextEditor",'photos'=>'photos');
$source=array('activities_type'=>array("0"=>"activities_types","1"=>"name_en","2"=>"id"),
              'lang'=>array("0"=>"languages","1"=>"lang_name","2"=>"lang","type='admin'"));
$required=array("title"=>"required","activities_type"=>"required","brief"=>"required","details"=>"required");

// types
$tcols=array('name_en','name_ar');
$tSavecols=array('name_en','name_ar');
$tcolsUpdate= array('name_en','name_ar');
$ttypes=array('name_en'=>"text",'name_ar'=>'text');
$trequired=array("name_en"=>"required",'name_ar'=>'required');


$pageList="listActivities.php";
$pageInsert="insertActivity.php";
$pageUpdate="updateActivity.php";
$activities_type="listActivitiesTypes.php";
?>
