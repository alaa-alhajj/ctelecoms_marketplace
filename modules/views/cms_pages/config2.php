<?php
$db_table="cms_pages";
$LPP = 8;
$page_type = $utils->lookupField('cms_pages','id','type',"".$_REQUEST['id']."");
$cols=array('title',
			'seo_title',
			'seo_description',
			'seo_keywords',
			'seo_img');
$Savecols=array(
			'title',
			'seo_title',
			'seo_description',
			'seo_keywords',
			'seo_img',
			'lang'
			);
			
$colsUpdate=array(
			'title',
			'seo_title',
			'seo_description',
			'seo_keywords',
			'seo_img'
			);
			
if($page_type!='generated'){array_push($colsUpdate,'html');}
$types=array(
			'title'=>"text",
			'seo_title'=>"text",
			'seo_description'=>"textarea",
			'seo_keywords'=>"tags",
			'seo_img'=>"photos"
			);
$required=array("title"=>"required");

$pageList="listCms_pages_generated.php";
$pageUpdate="updateCms_page_generated.php";


?>