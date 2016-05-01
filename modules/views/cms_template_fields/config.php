<?php
$db_table="cms_template_fields";
$db_settings_table="cms_tem_field_settings";
$LPP = 8;

$cols=array('title',
			'template_id');
$Savecols=array(
			'title',
			'template_id'
			);
			
$colsUpdate=array(
			'title',
			'template_id'
			);

$types=array(
			'title'=>'text'
			);
$required=array("title"=>"required");




$settings_cols=array(
			'field_id',
			'width',
			'height',
			'limit',
			'resize_type');
$settings_Savecols=array(
			'field_id',
			'width',
			'height',
			'limit',
			'resize_type'
			);
			
$settings_colsUpdate=array(
			'field_id',
			'width',
			'height',
			'limit',
			'resize_type'
			);

$settings_types=array(
			'width'=>'text',
			'height'=>'text',
			'limit'=>'text',
			'resize_type'=>'select'
			);

?>