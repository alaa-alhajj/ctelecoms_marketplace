<?php

include 'config.php';

include '../../common/header.php';
include '../../common/pn.php';

$listTable = $voiControl->ObListTable();


if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
    $save_ob = new saveform($db_table, $_REQUEST, $cols, 'id', '');
    $utils->redirect($listTable->getLinkBackGridList());
}
if (isset($_REQUEST) && $_REQUEST['action'] == 'Edit') {
    $save_ob = new saveform($db_table, $_REQUEST, $cols);
    $utils->redirect($listTable->getLinkBackGridList());
}

if (isset($_REQUEST) && $_REQUEST['action'] == 'Delete') {
    $save_ob = new saveform($db_table, $_REQUEST, $cols, 'id');
    $utils->redirect($listTable->getLinkBackGridList());
}

$listTable->_table($db_table);
$listTable->_columns($cols);
$listTable->_Types($types);
$listTable->setExtendTables($extend);
$listTable->_source($source);
$listTable->_class('table table-striped');
$listTable->_edit(true);
$conditions = " `id`!='0' ";
$conditionsGet = array();
$listTable->setFilter(array(
    array("title", "text")
));
$listTable->_condition($listTable->getWhereFromFilter($conditions, $conditionsGet));
$listTable->setExtraLinks(array(array('Options', $utils->icons->ico['list'], '../voting_options/listVotingOptions.php', array('vote_id' => 'id'))));
$listTable->_order(true);
$listTable->IsGridList(true);
$listTable->setOrderBy("id asc");
$listTable->_special(false);
$listTable->_active(false);
$listTable->setLimit("$start,$LPP");

echo $utils->make_tag_html($listTable->FilterTable(), 'div', 'Filter');
echo $utils->make_tag_html($utils->make_module_btns('Add,Delete'), 'div', 'container-button');
echo $utils->make_tag_html($listTable->GetListTable(), 'div', 'form-itemdetails');
echo $utils->make_tag_html($listTable->create_pagination($listTable->getCount(), $pn, $LPP, $pageList . "?pn=^" . $listTable->getWhereFromFilter($conditions, $conditionsGet, true)), 'div', 'col-sm-12');


include_once '../../common/footer.php';
