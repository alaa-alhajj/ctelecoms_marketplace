<?phpinclude 'config.php';
include '../../common/header.php';
include '../../common/pn.php';


$listTable = $voiControl->ObListTable();

if(isset($_REQUEST) && $_REQUEST['action']=='Delete')
{  
    $save_ob=new saveform($db_table_types,$_REQUEST,$tcols);
    $utils->redirect($activities_type);
}

if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
    $save_ob = new saveform($db_table_types, $_REQUEST, $tcols,'id', 'ord');
    $utils->redirect($listTable->getLinkBackGridList());
}

if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $save_ob = new saveform($db_table_types, $_REQUEST, $tcols);
    $utils->redirect($listTable->getLinkBackGridList());
}

$listTable->_table($db_table_types);
$listTable->_columns(array('id','name_en','name_ar'));
$listTable->_Types($ttypes);

$listTable->_class('table table-striped');
$listTable->_edit(true);
$listTable->setOrderBy("id");
$listTable->IsGridList(true);
//$listTable->setExtraLinks(array(array('Payments',$utils->icons->ico['list'],'../customer_payments/listCustomer_payments.php',array('customer_id'=>'customer_id'),'')));
$listTable->_special(false);
$listTable->_active(true);


$listTable->setFilter(array(
    array("name_en", "text")
));
$listTable->_condition($listTable->getWhereFromFilter($conditions,$conditionsGet));
$listTable->setLimit("$start,$LPP");
echo $utils->make_tag_html($listTable->FilterTable(), 'div', 'Filter');
echo $utils->make_tag_html($utils->make_module_btns("Add,Delete,Reload"),'div','container-button');
echo $utils->make_tag_html($listTable->GetListTable(),'div','form-itemdetails');
echo $utils->make_tag_html($listTable->create_pagination($listTable->getCount(),$pn,$LPP,$pageList."?pn=^"),'div','col-sm-12');

?>

<?
include_once '../../common/footer.php';
