<?php
include "../../common/top.php";
include '../../common/header.php';
include 'config.php';
if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $save_ob = new saveform($db_table, $_REQUEST, array('title', 'module_id','icon'), 'id');
    $utils->redirect($_SERVER['PHP_SELF']);
}
if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
    $save_ob = new saveform($db_table, $_REQUEST, array('title', 'module_id','icon'), 'id', 'item_order');

    $utils->redirect($_SERVER['PHP_SELF']);
}

if (isset($_REQUEST) && $_REQUEST['action'] == 'Delete') {

    $save_ob = new saveform($db_table, $_REQUEST, $cols,'id');
    $utils->redirect($_SERVER['PHP_SELF']);
}

$listMenu = new nestable();
$listMenu->setTable($db_table);
$listMenu->setColumns(array('id', 'title','icon', 'module_id' ,'p_id'));
$listMenu->setTypes(array('title' => 'text', 'module_id' => 'select+','icon'=>"text"));
$listMenu->setCssClass(array( 'item_link' => 'chosen-select','icon'=>"iconpicker","module_id"=>"form-control"));
$listMenu->setSource(array('module_id'=>array('cms_modules','title',"id")));
$listMenu->setExtraFlag(array("short_cut"));
$listMenu->setOrder('item_order asc');
$listMenu->setField_id('id');
$listMenu->setField_Parent('p_id');
$listMenu->setParentValue(0);
$listMenu->setEdit("editMenu");
$listMenu->setField_order('item_order');
$listMenu->setWhere('');
$listMenu->setEditParameters(array('id'));
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