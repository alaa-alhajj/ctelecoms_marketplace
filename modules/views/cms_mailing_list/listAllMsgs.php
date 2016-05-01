<?php
include 'config.php';
include '../../common/header.php';
include '../../common/pn.php';
if(isset($_REQUEST) && $_REQUEST['action']=='Delete')
{  
    $mailList->deleteMsg($_REQUEST);
    $utils->redirect($pageListMsgs);
}

if(isset($_REQUEST) && $_REQUEST['resend'])
{  
    $msg_id=$_REQUEST['resend'];
    $mailList->resendMsg($msg_id);
    $utils->redirect($pageListMsgs);
}

$listTable = $voiControl->ObListTable();
$listTable->_table($db_mailing_msg);
$listTable->_columns(array('subject','create_date','grp_ids'));
$listTable->_Types($mtypes);
$listTable->_source($m_source);
$listTable->setExtendTables($m_extend);
$listTable->_class('table table-striped');
$listTable->setOrderBy("id DESC");
$listTable->setExtraLinks(array(array('Archive',$utils->icons->ico['list'],'listArchives.php',array('msg_id'=>'id'),''),array('Resend',"RESEND",'listAllMsgs.php',array('resend'=>'id'),'')));
$listTable->_special(false);
$listTable->_active(false);

$ob_roles->getDeleteRole($grp_id,$listTable,$module_id);
$ob_roles->getEditRole($grp_id,$listTable,$module_id,$pageUpdateMsg);
$listTable->_PageInsert($pageInsertMsg);
$listTable->_PageList($pageListMsgs);
$listTable->setFilter(array(
    array("subject", "text")
));
$listTable->_condition($listTable->getWhereFromFilter($conditions,$conditionsGet));
$listTable->setLimit("$start,$LPP");

echo $utils->make_tag_html($listTable->GetListTable(),'div','form-itemdetails');


include_once '../../common/footer.php';
