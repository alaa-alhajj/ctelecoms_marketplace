<?php
$db_table="discover_syria";
$db_discover_gov="cities";
$db_dicover_category="dicover_category";
$db_dicover_sub_category="dicover_sub_category";
$db_table_maps='maps';

$LPP = 8;

$cols=array('title','details','gov_id','cat_id','sub_cat_id','photos','map');
$Savecols=array('title','details','gov_id','cat_id','sub_cat_id','photos');
$colsUpdate= array('title','details','gov_id','cat_id','sub_cat_id','photos','map');

$types=array('title'=>"text",'details'=>"FullTextEditor",'gov_id'=>'select','cat_id'=>'select','sub_cat_id'=>'select','photos'=>'photos','map'=>'map');
$source=array('gov_id'=>array("0"=>"cities","1"=>"name_ar","2"=>"id"),'cat_id'=>array("0"=>"dicover_category","1"=>"title","2"=>"id"),'sub_cat_id'=>array("0"=>"dicover_sub_category","1"=>"title","2"=>"id","cat_id=1"));
$required=array("title"=>"required","details"=>"required","gov_id"=>"required","cat_id"=>"required","sub_cat_id"=>"required");

//discover_gov
$dg_cols=array('name_ar');
$dg_Savecols=array('name_ar');
$dg_colsUpdate= array('name_ar');
$dg_types=array('name_ar'=>"text");
$dg_required=array("name_ar"=>"required");

//dicover_category
$dc_cols=array('title');
$dc_Savecols=array('title');
$dc_colsUpdate= array('title');
$dc_types=array('title'=>"text");
$dc_required=array("title"=>"required");

//dicover_sub_category
$dsc_cols=array('cat_id','title');
$dsc_Savecols=array('cat_id','title');
$dsc_colsUpdate= array('cat_id','title');
$dsc_types=array('cat_id'=>"select",'title'=>"text");
$dsc_source=array('cat_id'=>array("0"=>"dicover_category","1"=>"title","2"=>"id"));

$dsc_required=array("title"=>"required");


$pageList="listDiscoverSyria.php";
$pageInsert="insertItem.php";
$pageUpdate="updateItem.php";

$pageList_Govs="listDiscoverGov.php";
$pageList_Cats="listCategories.php";
$pageListSub_Cats="listSubCategories.php";

//Maps Varialble
$map_save_cols=array('lat','lng');
?>
