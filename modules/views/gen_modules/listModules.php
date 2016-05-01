<?php
include '../../common/header.php';
include 'config.php';
include '../../common/pn.php';
$listTable = $voiControl->ObListTable();
$utiles = $voiControl->ObUtils();
$is_static = $_REQUEST['is_static'];

$table_name = strtolower(str_replace(' ','_',$_REQUEST['table_name']));
$_REQUEST['table_name']=$table_name;
if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
	
	if(!$is_static){
		$lang_type = $_REQUEST['lang_type'];
		if($lang_type=='Table'){
			$lang_field = " `lang` varchar(255) , ";
		}else{
			$lang_field = "";
		}
		$create_table = $pdo->exec("CREATE TABLE IF NOT EXISTS `".$table_name."` (
				`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				$lang_field
				`page_id` VARCHAR(100) NOT NULL,
				`item_order` INT NOT NULL
				) ;");
		
	}
	
	$save_ob = new saveform($db_table, $_REQUEST, $cols,'id', '');
	$module_id=$save_ob->getInsertId();
	$save_ob = new saveform('cms_module_roles', array('action'=>'Insert','module_id'=>$module_id,'role'=>'list'), array('module_id','role'),'id', '');
	$save_ob = new saveform('cms_module_roles', array('action'=>'Insert','module_id'=>$module_id,'role'=>'insert'), array('module_id','role'),'id', '');
	$save_ob = new saveform('cms_module_roles', array('action'=>'Insert','module_id'=>$module_id,'role'=>'update'), array('module_id','role'),'id', '');
	$save_ob = new saveform('cms_module_roles', array('action'=>'Insert','module_id'=>$module_id,'role'=>'delete'), array('module_id','role'),'id', '');
	$save_ob = new saveform('cms_module_roles', array('action'=>'Insert','module_id'=>$module_id,'role'=>'publish'), array('module_id','role'),'id', '');
	$utils->redirect($listTable->getLinkBackGridList());
}

if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
	
	
    $table_old_name =  $utils->lookupField($db_table,'id' , 'table_name', $_REQUEST['id']);
    $old_lang_type =  $utils->lookupField($db_table,'id' , 'lang_type', $_REQUEST['id']);
	$curr_lang_type = $_REQUEST['lang_type'];
	
	if(!$is_static){
		$update_table = $pdo->exec(" RENAME TABLE `".$table_old_name."` TO `".$table_name."` ;");
	}
	
	$save_ob = new saveform($db_table, $_REQUEST, $cols);
	
	echo $curr_lang_type;
	echo $old_lang_type;
	if($old_lang_type=='Table'&&$curr_lang_type!='Table'){
		$update_table = $pdo->exec(" ALTER TABLE `".$table_name."` DROP `lang`;");
		echo " ALTER TABLE `".$table_name."` DROP `lang`;";
	}elseif($old_lang_type!='Table'&&$curr_lang_type=='Table'){
		$update_table = $pdo->exec("ALTER TABLE `".$table_name."` ADD `lang` VARCHAR(255) NOT NULL; ");
		echo "ALTER TABLE `".$table_name."` ADD `lang` VARCHAR(255) NOT NULL; ";
	}
    $utils->redirect($listTable->getLinkBackGridList());
}

if(isset($_REQUEST) && $_REQUEST['action']=='Delete'){
	$ids = $_REQUEST['DeleteRow'];
	//Delete related table in database
	foreach($ids as $id){
			$table_old_name = $utils->lookupField($db_table,'id' , 'table_name', $id);
			$drop_table = $pdo->exec("DROP TABLE `".$table_old_name."`;");
	}
	$delete_roles = array('action'=>'Delete','DeleteRow'=>$ids);
	//Delete related Roles
	$save_ob=new saveform('cms_module_roles',$delete_roles,$roles_cols,'module_id');
	//Delete related Fields
	$save_ob=new saveform('cms_module_fields',$delete_roles,$roles_cols,'table_id');
	//Delete related Menu items
	$save_ob=new saveform('cms_menu',$delete_roles,$roles_cols,'module_id');
	//Delete related Pages
	$save_ob=new saveform('cms_pages',$delete_roles,$roles_cols,'module_id');
	
	$save_ob=new saveform($db_table,$_REQUEST,$cols,'id');
	$utils->redirect($listTable->getLinkBackGridList());	
}

$listTable->_table($db_table);
$listTable->_columns($cols);
$listTable->_Types($types);
$listTable->setExtendTables($extend);
$listTable->_source($source);
$listTable->_class('');

$conditions = " is_static='0' and publish='1' ";
if($grp_id==1){
	$conditions = " `id`!='0' ";
}
$conditionsGet=array();
$listTable->setFilter(array(array("title", "text")));
$listTable->_condition($listTable->getWhereFromFilter($conditions,$conditionsGet));
$listTable->setExtra($extra);
$listTable->setExtraLinks(array(
				array('Fields',$utils->icons->ico['list'],'../gen_module_fields/listModule_fields.php',array('table_id'=>'id')),
				array('Template',$utils->icons->ico['widget'],'../gen_modules/select-templates.php',array('module_id'=>'id'))
				));
$listTable->_order(true);
$listTable->IsGridList(true);
$listTable->setOrderBy("id asc");
$listTable->_special(false);
$listTable->_active(false);


$listTable->_PageList($pageList);

$ob_roles->getEditRole($grp_id,$listTable,$module_id);
$ob_roles->getDeleteRole($grp_id,$listTable,$module_id);

$listTable->setLimit("$start,$LPP");
$listTable->setBackBtn(false);


echo $utils->make_tag_html($ob_roles->getListRole($grp_id,$listTable,$module_id), 'div', 'form-itemdetails');



include_once '../../common/footer.php';
?>