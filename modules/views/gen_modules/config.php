<?php
$db_table="cms_modules";
$LPP = 20;
if($grp_id==1){
	$cols=array('title','table_name','publish','is_static','static_path','lang_type','is_gridlist');
	$types=array('title'=>'text','table_name'=>'text','publish'=>'checkbox','is_static'=>'checkbox','static_path'=>'text','lang_type'=>'select','is_gridlist'=>'chechbox');
}else{
	$cols=array('title','table_name','lang_type','is_gridlist');
	$types=array('title'=>'text','table_name'=>'text','lang_type'=>'select','is_gridlist'=>'chechbox');
}

$extra = array("lang_type"=>array('Table','Field'));
$pageList="listModules.php";
$length=array();
$values=array();
$ClassMainField = "row";
$classSubMain = array('col-xs-2', 'pull-left', 'col-xs-9');
?>