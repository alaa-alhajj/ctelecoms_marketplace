<?php

$LPP = 20;
$cols = array('title', 'table_name', 'publish', 'is_static', 'static_path');
$types = array('title' => 'text', 'table_name' => 'text', 'publish' => 'checkbox', 'is_static' => 'checkbox', 'static_path' => 'text');



$cms_module = $fpdo->from('cms_modules')->where(" id='$module_id' ")->fetchAll();
foreach($cms_module as $row){
	$db_table = $row['table_name'];
	$module_lang_type = $row['lang_type'];
	$is_gridlist = $row['is_gridlist'];
	$has_order = $row['has_order'];
}


$types = array();
$required = array();
$listCols = array();
$sourceCols = array();
$cols = array();
$plus = array();
$map_field = "";
$order_field = "";
$query = $fpdo->from('cms_module_fields')->where(" table_id='$module_id' ")->orderBy('ord asc')->fetchAll();
foreach ($query as $row) {
    $field_title = '' . $row['title'];
    $is_list = $row['is_list'];
    $is_lang_eff = $row['is_lang_eff'];
	if($is_lang_eff){
		foreach($cms_active_langs as $lang){
			array_push($cols, $field_title.'_'.$lang);
			if ($is_list) {
				array_push($listCols, $field_title.'_'.$lang);
			}
			if ($row['required']) {
				$col_required = array($field_title.'_'.$lang => 'required');
				$required[$field_title.'_'.$lang] = 'required';
			}
			
			$types[$field_title.'_'.$lang] = $row['type'];
			
			
		}
	}else{
		
		array_push($cols, $field_title);
		if ($is_list) {
			array_push($listCols, $field_title);
		}
		if ($row['required']) {
			$col_required = array($field_title => 'required');
			$required[$field_title] = 'required';
		}
		$types[$field_title] = $row['type'];
		
		if($row['type']=='DynamicSelect'){
			
			$source_table = $utils->lookupField('cms_modules', 'id', 'table_name', $row['plus']);
			$main_field = $fpdo->from('cms_module_fields')->where(" table_id='".$row['plus']."' AND is_main='1' ")->fetch();
			$main_field_lang_eff = $main_field['is_lang_eff'];
			$main_field_title = $main_field['title'];
			if($main_field_lang_eff=='1'){
				$main_field_title = $main_field_title.'_'.$cmsMlang;
			}
			$sourceCols[$field_title] = array($source_table,'id',$main_field_title);
		}
	}
	
   

    
    if ($row['type'] == 'map') {
        $map_field = $field_title;
    }
    $plus[$field_title] = explode(',', $row['plus']);

    
}

if($module_lang_type=='Table'){
	array_push($cols, 'lang');
}

if($is_gridlist){
	
}else{
	$pageList = "listModules.php";
	$pageInsert = "insertModules.php";
	$pageUpdate = "updateModules.php";
}


$length = array();
$values = array();
$ClassMainField = "row";
$classSubMain = array('col-xs-2', 'pull-left', 'col-xs-9');
?>