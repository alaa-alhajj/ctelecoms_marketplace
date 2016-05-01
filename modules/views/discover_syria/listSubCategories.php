<?php include 'config.php';
include '../../common/header.php';
include '../../common/pn.php';


$listTable = $voiControl->ObListTable();

if(isset($_REQUEST) && $_REQUEST['action']=='Delete')
{  
    $save_ob=new saveform($db_dicover_sub_category,$_REQUEST,$dsc_cols);
    $utils->redirect($pageListSub_Cats);
}

if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
    $save_ob = new saveform($db_dicover_sub_category, $_REQUEST, $dsc_cols,'id', 'ord');
    $utils->redirect($listTable->getLinkBackGridList());
}

if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $save_ob = new saveform($db_dicover_sub_category, $_REQUEST, $dsc_cols);
    $utils->redirect($listTable->getLinkBackGridList());
}

$listTable->_table($db_dicover_sub_category);
$listTable->_columns(array('cat_id','title'));
$listTable->_Types($dsc_types);
$listTable->_source(array("cat_id" => array($db_dicover_category, "id", "title")));
$listTable->_class('table table-striped');
$listTable->_edit(true);
$listTable->setOrderBy("id");
$listTable->IsGridList(true);
//$listTable->setExtraLinks(array(array('Payments',$utils->icons->ico['list'],'../customer_payments/listCustomer_payments.php',array('customer_id'=>'customer_id'),'')));
$listTable->_special(false);
$listTable->_active(true);

$listTable->setExtendTables($dsc_source);
$listTable->setFilter(array(
    array("title", "text")
));
$listTable->_condition($listTable->getWhereFromFilter($conditions,$conditionsGet));
$listTable->setLimit("$start,$LPP");
echo $utils->make_tag_html($listTable->FilterTable(), 'div', 'Filter');
echo $utils->make_tag_html($utils->make_module_btns("Add,Delete,Reload"),'div','container-button');
echo $utils->make_tag_html($listTable->GetListTable(),'div','form-itemdetails');
echo $utils->make_tag_html($listTable->create_pagination($listTable->getCount(),$pn,$LPP,$pageListSub_Cats."?pn=^"),'div','col-sm-12');

?>

<?
include_once '../../common/footer.php';
