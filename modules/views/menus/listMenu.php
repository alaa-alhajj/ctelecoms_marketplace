<?php
include "../../common/top.php";
include '../../common/header.php';
include 'config.php';
if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $save_ob = new saveform($db_table, $_REQUEST, array('item_label', 'item_link'), 'menu_id');
    $utils->redirect($_SERVER['PHP_SELF']);
}
if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
	$_REQUEST['lang']=$cmsMlang;
    $save_ob = new saveform($db_table, $_REQUEST, array('item_label', 'item_link','lang'), 'menu_id', 'item_order');

    $utils->redirect($_SERVER['PHP_SELF']);
}

if (isset($_REQUEST) && $_REQUEST['action'] == 'Delete') {
	
    $save_ob = new saveform($db_table, $_REQUEST, $cols,'menu_id');
    $utils->redirect($_SERVER['PHP_SELF']);
}

echo $utils->lang_switcher();
$listMenu = new nestable();
$listMenu->setTable($db_table);
$listMenu->setColumns(array('menu_id', 'item_label', 'item_link', 'p_id','lang'));
$listMenu->setTypes(array('item_label' => 'text', 'item_link' => 'select'));
$listMenu->setCssClass(array( 'item_link' => 'chosen-select'));
$listMenu->setSource(array('item_link'=>array('cms_pages','title',"concat('".$cmsMlang."/page',id,'/',replace(title,' ','-'))"," lang='$cmsMlang' ")));
$listMenu->setOrder('item_order asc');
$listMenu->setField_id('menu_id');
$listMenu->setField_Parent('p_id');
$listMenu->setParentValue(0);
$listMenu->setEdit("editMenu");
$listMenu->setField_order('item_order');

$listMenu->setWhere(array('lang'=>$cmsMlang));
$listMenu->setEditParameters(array('menu_id'));
echo "<div class='box box-danger'>";
echo "<div class='box-header with-border'>";
echo $listMenu->getModuleButtons();
echo"</div>";
echo "<div class='box-body'>";

$ret.= '<link href="../../includes/plugins/nestable/nestable.css" rel="stylesheet" type="text/css"/>';
$ret.="<form name='TableForm'>";

$ret.="<div class='dd nest-list' id='nestable3'>";
$ret.= $listMenu->geInsertForm();
$ret.= $listMenu->GetList();
$ret.= $listMenu->getActivity();
$ret.= "</div>";
$ret.="</form>";
echo $ret;
echo "</div>";
echo "</div>";

include_once '../../common/footer.php';
?>
<script>


</script>