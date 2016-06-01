<?php
include 'config.php';
include '../../common/header.php';
include '../../common/pn.php';

if ($_REQUEST['yearly_chartPO'] != "") {
    $req_year = $_REQUEST['yearly_chartPO'];
} else {
    $req_year = date("Y");
}
$query1 = $fpdo->from('purchase_order')->select("Year(order_date) as year")
               ->GroupBy("YEAR(order_date)")->OrderBy('YEAR(order_date) desc')
               ->fetchALL();
$year_select='<select class="form-control" name="yearly_chartPO" id="yearly_chartPO" >';
foreach ($query1 as $q) {
    if ($_REQUEST['yearly_product'] == $q['year']) {
        $selected = "selected='selected'";
    } else {
        $selected = "";
    }

    $year_select.="<option $selected value='" . $q['year'] . "'>" . $q['year'] . "</option>";
}
$year_select.="</select>";

$select_year='<div class="box-header with-border">
<form><div class="form-inline">
<div class="form-group">
<label>Select Year: </label>'.$year_select.'</div></div>
</form> </div>';
$customer_report_bycity=''
        . '<input type="hidden" id="year_chart_po" value="'.$req_year.'">'
    
 . $select_year
  . ' <div class="col-sm-12" id="po_date"></div>'
        . ' <div class="col-sm-12" id="po_customer"></div>'
         . ' <div class="col-sm-12" id="po_region"></div>'
      ;

$form = new GenerateFormField();
$form->setColumns();
$form->setTypes();
$form->setExtendTables($source);
$form->setRequireds($required);

$form->setCountCell(1);
$form->setSubmit(false);

$form->setAsForm(false);

$form->setBackBtn(false);
$form->setAppendToForm($customer_report_bycity);
echo $form->getForm('Insert');

?>
  
<?
include_once '../../common/footer.php';
