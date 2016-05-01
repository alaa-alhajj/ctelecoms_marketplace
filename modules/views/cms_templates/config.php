<?php
$db_table="cms_templates";
$LPP = 8;

$cols=array('title',
			'photo',
			'item_html',
			'main_html');
$Savecols=array(
			'title',
			'photo',
			'item_html',
			'main_html'
			);
			
$colsUpdate=array(
			'title',
			'photo',
			'item_html',
			'main_html'
			);

$types=array(
			'title'=>"text",
			'photo'=>"photos",
			'item_html'=>'textarea',
			'main_html'=>"textarea"
			);
$required=array("title"=>"required");

$pageList="listTemplates.php";
$pageInsert="insertTemplate.php";
$pageUpdate="updateTemplate.php";
?>