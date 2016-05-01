<?php
$db_table="cms_module_fields";
$LPP = 20;
$cols=array('table_id','title','type','required','plus','is_main','is_list');
$types=array('table_id'=>'static','title'=>'text','type'=>'select','required'=>'flag','plus'=>'tags','is_main'=>'checkbox','is_list'=>'flag',is_lang_eff=>'checkbox');
$_source=array('table_id'=>array('cms_modules','id','title',$_REQUEST['table_id']),'plus'=>array('cms_modules','id','title'));
$required=array("title"=>"required",'type'=>'required');

$table_id = $_REQUEST['table_id'];
$lang_type = $utils->lookupField('cms_modules', 'id', 'lang_type', $table_id);
if($lang_type=='Field'){
	array_push($cols,'is_lang_eff');
}


$extra=array('type'=>array(
				'photos',
				'videos',
				'text',
				'email',
				'tel',
				'number',
				'tags',
				'password',
				'select',
				'select+',
				'DynamicSelect',
				'attach',
				'checkbox',
				'map',
				'flag',
				'date',
				'datepicker',
				'timepicker',
				'FullTextEditor',
				'textarea',
				'SimpleTextEditor'
				));
$length=array();
$values=array();
$ClassMainField = "row";
$order_field="ord";
$table_id = $_REQUEST['table_id'];
$order_condition=" table_id='$table_id' ";
$classSubMain = array('col-xs-2', 'pull-left', 'col-xs-9');
$pageList="listModule_fields.php?table_id=".$table_id;
?>