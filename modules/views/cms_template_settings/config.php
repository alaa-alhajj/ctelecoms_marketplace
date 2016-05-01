<?php
$db_table="cms_template_settings";
$LPP = 8;

$cols=array(
			'id',
			'template_id',
			'replace_stm',
			'default_value');
$Savecols=array(
			'default_value'
			);
			
$colsUpdate=array(
			'default_value'
			);

$types=array(
			'default_value'=>"text"
			);
$required=array("replace_stm"=>"required");

?>