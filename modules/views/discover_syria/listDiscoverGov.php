<?php include 'config.php';
include '../../common/header.php';
include '../../common/pn.php';


$listTable = $voiControl->ObListTable();

if(isset($_REQUEST) && $_REQUEST['action']=='Delete')
{  
    $save_ob=new saveform($db_discover_gov,$_REQUEST,$dg_cols);
    $utils->redirect($pageList_Govs);
}

if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
    $save_ob = new saveform($db_discover_gov, $_REQUEST, $dg_cols,'id', 'ord');
    $utils->redirect($listTable->getLinkBackGridList());
}

if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $save_ob = new saveform($db_discover_gov, $_REQUEST, $dg_cols);
    $utils->redirect($listTable->getLinkBackGridList());
}

$listTable->_table($db_discover_gov);
$listTable->_columns(array('title'));
$listTable->_Types($dg_types);

$listTable->_class('table table-striped');
$listTable->_edit(true);
$listTable->setOrderBy("id");
$listTable->IsGridList(true);
//$listTable->setExtraLinks(array(array('Payments',$utils->icons->ico['list'],'../customer_payments/listCustomer_payments.php',array('customer_id'=>'customer_id'),'')));
$listTable->_special(false);
$listTable->_active(true);


$listTable->setFilter(array(
    array("title", "text")
));
$listTable->_condition($listTable->getWhereFromFilter($conditions,$conditionsGet));
$listTable->setLimit("$start,$LPP");
$listTable->_PageList($pageList_Govs);
echo $utils->make_tag_html($listTable->GetListTable(),'div','form-itemdetails');


?>

<?
include_once '../../common/footer.php';
