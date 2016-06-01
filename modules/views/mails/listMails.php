<?php
include 'config.php';
include '../../common/header.php';
include '../../common/pn.php';
?>
<div class="row">
    <div class="col-sm-12">
        <a href="javascript:;" class="emailTemplate btn btn-submit" style='float: right;margin-bottom: 5px;'>Message Template</a>
    </div>
</div>


<?
define('start_date', 'start date');

if(isset($_REQUEST) && $_REQUEST['action']=='Delete')
{  
    $save_ob=new saveform($db_table,$_REQUEST,$cols);
    $utils->redirect($pageList);
}

$listTable = $voiControl->ObListTable();
$listTable->_table($db_table);
$listTable->_columns(array('name','email_type'));

$listTable->_source($source);
$listTable->_class('table table-striped');
$listTable->_edit($pageUpdate, array("id"));
$listTable->_Add(false);
$listTable->setOrderBy("id");
//$listTable->setExtraLinks(array(array('Payments',$utils->icons->ico['list'],'../customer_payments/listCustomer_payments.php',array('customer_id'=>'customer_id'),'')));
$listTable->_special(false);
$listTable->_active(false);

$listTable->setFilter(array(
    array("name", "text")
));
$conditions="id !='0'";
$listTable->_condition($listTable->getWhereFromFilter($conditions,$conditionsGet));
$listTable->setLimit("$start,$LPP");
$listTable->_PageList($pageList);
$listTable->_PageInsert($pageInsert);
echo $utils->make_tag_html($listTable->GetListTable(),'div','form-itemdetails');


?>

<?
include_once '../../common/footer.php';
