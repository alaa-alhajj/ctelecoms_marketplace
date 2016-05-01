<?php
$db_table="cms_widgets";
$LPP = 8;

$cols=array('title',
			'html');
$Savecols=array(
			'title',
			'html'
			);
			
$colsUpdate=array(
			'title',
			'html'
			);

$types=array(
			'title'=>"text",
			'html'=>"FullTextEditor"
			);
$required=array("title"=>"required");

$pageList="listWidgets.php";
$pageInsert="insertWidget.php";
$pageUpdate="updateWidget.php";
?>