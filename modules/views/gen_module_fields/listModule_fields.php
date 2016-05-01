<?php
include '../../common/header.php';
include 'config.php';
include '../../common/pn.php';
$listTable = $voiControl->ObListTable();

$table_name =  $utils->lookupField('cms_modules','id' , 'table_name', $table_id);
$type = $_REQUEST['type'];
$field_titel = $_REQUEST['title'];
$_REQUEST['title'] = strtolower(str_replace(' ','_',$field_titel));
if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
	if($type=='photos'||
		$type=='files'||
		$type=='filed'||
		$type=='videos'||
		$type=='text'||
		$type=='email'||
		$type=='tel'||
		$type=='number'||
		$type=='tags'||
		$type=='password'||
		$type=='select'||
		$type=='select+'||
		$type=='DynamicSelect'||
		$type=='attach'||
		$type=='checkbox'||
		$type=='map'||
		$type=='flag'){
			$alter_query2 = " VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ; ";			
	}elseif($type=='date'||
			$type=='datepicker'||
			$type=='timepicker'){
			$alter_query2 = " DATETIME NOT NULL ; ";
	}elseif($type=='FullTextEditor'||
			$type=='textarea'||
			$type=='SimpleTextEditor'){
			$alter_query2 = " TEXT NOT NULL ;";
	}
	$is_lang_eff = $_REQUEST['is_lang_eff'];
	if($is_lang_eff){
		$lang_result = $fpdo->from('cms_langs')->where(" active='1' ")->fetchAll();
		foreach($lang_result as $lang){
			
			$alter_query = "ALTER TABLE `".$table_name."` ADD `".$_REQUEST['title'].'_'.$lang['lang']."` ".$alter_query2;
			$add_field = $pdo->exec($alter_query);
		}
	}else{
		$alter_query = "ALTER TABLE `".$table_name."` ADD `".$_REQUEST['title']."` ".$alter_query2;
		$add_field = $pdo->exec($alter_query);
	}
	
	if($_REQUEST['is_main']){
		$fpdo->update($db_table)->set(array('is_main'=>'0'))->where("table_id='".$table_id."'")->execute();
	}
    $save_ob = new saveform($db_table, $_REQUEST, $cols,'id', 'ord');
	
    $utils->redirect($listTable->getLinkBackGridList());
}
if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
	$is_lang_eff = $_REQUEST['is_lang_eff'];
	$it_was_lang_eff =  $utils->lookupField($db_table,'id' , 'is_lang_eff' ,$table_id);

	if($type=='photos'||
		$type=='files'||
		$type=='filed'||
		$type=='videos'||
		$type=='text'||
		$type=='email'||
		$type=='tel'||
		$type=='number'||
		$type=='tags'||
		$type=='password'||
		$type=='select'||
		$type=='select+'||
		$type=='DynamicSelect'||
		$type=='attach'||
		$type=='checkbox'||
		$type=='map'||
		$type=='flag'){
		$alter_query2 = "VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;";
	}elseif($type=='FullTextEditor'||
			$type=='textarea'||
			$type=='SimpleTextEditor'){
		$alter_query2 = "TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;";
	}elseif($type=='date'||
			$type=='datepicker'||
			$type=='timepicker'){
		$alter_query2 = "DATETIME NOT NULL;";	
	}
	
	$table_field_name =  $utils->lookupField('cms_module_fields','id' , 'title',$_REQUEST['id']);
	$old_is_lang_eff =  $utils->lookupField('cms_module_fields','id' , 'is_lang_eff', $_REQUEST['id']);
	$curr_is_lang_eff = $_REQUEST['is_lang_eff'];
	
	if(!$old_is_lang_eff&&$curr_is_lang_eff){
		$delete_field = $pdo->exec("ALTER TABLE `".$table_name."` DROP `".$table_field_name."`");
		$lang_result = $fpdo->from('cms_langs')->where(" active='1' ")->fetchAll();
		foreach($lang_result as $lang){
			$alter_query = "ALTER TABLE `".$table_name."` ADD `".$_REQUEST['title'].'_'.$lang['lang']."` ".$alter_query2;			
			$add_field = $pdo->exec($alter_query);
		}
	}elseif($old_is_lang_eff&&!$curr_is_lang_eff){
		$alter_query = "ALTER TABLE `".$table_name."` ADD `".$_REQUEST['title']."` ".$alter_query2;	
		$add_field = $pdo->exec($alter_query);			
		$lang_result = $fpdo->from('cms_langs')->where(" active='1' ")->fetchAll();
		foreach($lang_result as $lang){
			$alter_query = "ALTER TABLE `".$table_name."` DROP `".$_REQUEST['title'].'_'.$lang['lang']."`";			
			$drop_field = $pdo->exec($alter_query);
		}
	}else{
		$alter_query = "ALTER TABLE `".$table_name."` CHANGE `".$table_field_name."` `".$_REQUEST['title']."` ".$alter_query2;
		$drop_field = $pdo->exec($alter_query);
	}
	
		
		
		
	
	if($_REQUEST['is_main']){
		$fpdo->update($db_table)->set(array('is_main'=>'0'))->where("table_id='".$table_id."'")->execute();
	}
	$save_ob = new saveform($db_table, $_REQUEST, $cols);
	$utils->redirect($listTable->getLinkBackGridList());
}

if(isset($_REQUEST) && $_REQUEST['action']=='Delete'){
	$ids = $_REQUEST['DeleteRow'];
	foreach($ids as $id){
		$table_field_name = $utils->lookupField($db_table,'id' , 'title', $id);
		$old_is_lang_eff =  $utils->lookupField('cms_module_fields','id' , 'is_lang_eff', $id);
		
		if($old_is_lang_eff){
			$lang_result = $fpdo->from('cms_langs')->where(" active='1' ")->fetchAll();
			foreach($lang_result as $lang){
				$alter_query = "ALTER TABLE `".$table_name."` DROP `".$table_field_name.'_'.$lang['lang']."`";			
				$drop_field = $pdo->exec($alter_query);
			}
		}else{
			
			$delete_field = $pdo->exec("ALTER TABLE `".$table_name."` DROP `".$table_field_name."`");	

		}
	}
	
    $save_ob=new saveform($db_table,$_REQUEST,$cols,'id');
	$utils->redirect($pageList);
}

$listTable->_table($db_table);
$listTable->_columns($cols);
$listTable->_Types($types);
$listTable->_source($_source);
$listTable->setExtra($extra);

$conditions = " `id`!='0' ";
$conditionsGet=array('table_id'=>'table_id');
$listTable->_condition($listTable->getWhereFromFilter($conditions,$conditionsGet));

$listTable->_class('table table-striped table-fileds');
$listTable->setParentAttr(array('table_id'));
$listTable->_order(true,$order_field,$order_condition);
$listTable->IsGridList(true);
$listTable->setOrderBy("ord asc");
$listTable->_special(false);
$listTable->_active(false);
$listTable->setFilter(array( array("lable", "text")));
$listTable->setLimit("$start,$LPP");
$listTable->_PageList($pageList);
$listTable->_PageInsert($pageInsert);
$listTable->setRequireds($required);
$ob_roles->getEditRole($grp_id,$listTable,$module_id);
$ob_roles->getDeleteRole($grp_id,$listTable,$module_id);
$listTable->setBackBtn('../gen_modules/listModules.php?cmsMID=1');

echo $utils->make_tag_html($ob_roles->getListRole($grp_id,$listTable,$module_id), 'div', 'form-itemdetails');



include_once '../../common/footer.php';
?>