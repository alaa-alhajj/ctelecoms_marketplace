<?php
$db_table="cms_pages";
$LPP = 8;
$page_type = $utils->lookupField('cms_pages','id','type',"".$_REQUEST['id']."");
$cols=array('title',
			'html',
			'seo_title',
			'seo_description',
			'seo_keywords',
			'seo_img');
$Savecols=array(
			'title',
			'html',
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
			echo $page_type;
if($page_type!='generated'){array_push($colsUpdate,'html');}
$types=array(
			'title'=>"text",
			'html'=>"FullTextEditor",
			'seo_title'=>"text",
			'seo_description'=>"textarea",
			'seo_keywords'=>"tags",
			'seo_img'=>"photos"
			);
$required=array("title"=>"required");
$dbtable_seo="seo";
$cols_seo=array('title',
			'keywords',
			'details',
			'image');

$Savecols_seo=array(
			'title',
			'keywords',
			'details',
			'image','page_id'
			);
			
$colsUpdate_seo=array(
			'title',
			'keywords',
			'details',
			'image'
			);
$types_seo=array(
			
			'title'=>"text",
			'details'=>"textarea",
			'keywords'=>"tags",
			'image'=>"photos"
			);


$pageList="listCms_pages.php";
$pageInsert="insertCms_page.php";
$pageUpdate="updateCms_page.php";
?>