<?php

include "../../common/top.php";
include '../../common/header.php';
include 'config.php';
$id = $_REQUEST['pro_id'];
  $values1 = $fpdo->from($db_pro_FAQ)->where('product_id', $_REQUEST['pro_id'])->fetch();
    if($values1['id']!=""){
        $pageFAQ=$pageProductFAQ;
    }else{
        $pageFAQ=$pageProductFAQ;
    }
if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
    $req_ids = "";
    foreach ($_REQUEST['title_req'] as $req_id) {

        $req_ids.=$req_id . ",";
    }
    $query = $fpdo->update($db_table)->set(array('customer_req_fields' => $req_ids))->where("id='$id'");
    $exec = $query->execute();

    if ($exec == true || ( $exec >= 0 && is_int($exec))) {
        $success = true;
    } else {
        $success = false;
    }
    $message = "";
    @session_start();

    if ($success) {
        $message = $utils->getConstant("Success");
        $type = "success";
        $_SESSION['saveFormStatus'] = "success";
    } else {
        $message = $utils->getConstant("Faild");
        $type = "error";
        $_SESSION['saveFormStatus'] = "faild";
    }

    echo '<script>waitingDialog.hide();
            swal({
            title: "",
            text: "' . $message . '",
            type: "' . $type . '",
            showConfirmButton: false
            , showConfirmButton: false, timer: 2000
        });
        </script>';
    if ($_REQUEST['saveClose'] != "") {
        $utils->redirect($pageList);
    } else {
      

              $utils->redirect($pageFAQ . "?pro_id=" . $_REQUEST['pro_id']);
    
        
        
      
    }
}
echo $path = '<ul id="breadcrumbs-one">
    <li><a href="#">Package Data</a></li>
    <li><a href="#">Package Products</a></li>
    <li><a href="#">Features</a></li>
    <li><a href="">Photos</a></li>
    <li><a href="#">Pricing</a></li>
    <li><a href="#">Add-ons</a></li>
    <li><a href="#">Related Products</a></li>
    <li  class="active-menue"><a href="#">Required Fields</a></li>
    <li><a href="#">FAQ</a></li>
    <li><a href="#">SEO</a></li>
</ul>';

$values1 = $fpdo->from($db_table)->where('id', $_REQUEST['pro_id'])->fetch();





$get_features=$fpdo->from('customer_fields')->feathAll();

$add_feature='<div class="box box-danger form-horizontal"><div class="box-body">';
$add_feature.='<div class="customerField"><div class="col-sm-10 nopadding"><input id="title" name="title" value="" type="text" required="" size="" class=" form-control" placeholder="Enter Field"></div>';
$add_feature.="<div class='col-sm-2 '><a href='javascript:;' class='addCustomerField' data-ajax='../../views/ajax/AddFieldAjax.php'><i class='fa fa-plus-circle' aria-hidden='true' style='font-size:29px'></i></a></div>";
$add_feature.="</div>";
$add_feature.='<table id="TableCustomerFields" class="table table-striped  table table-bordered table-hover">
<thead>
<tr>
<th>Fields</th>
</tr>
</thead>
<tbody id="" class="sortable ui-sortable">';
foreach($get_features as $feature){
     $values_check = explode(',', $values1['customer_req_fields']);
      if (in_array($feature['id'], $values_check) == true) {
                $checked = "checked = 'checked'";
            }else{
               $checked=""; 
            }
    $add_feature.="<tr id='f_" . $feature['id'] . "' data-id='".$feature['id']."' class='Fieldtr'><td>"
            . "<div  class='checkbox'> <label>"
            . "<input type='checkbox' name='".$feature['title']."' $checked value='".$feature['id']."'>
        
            </label>
        <input id='title' name='title' value='" . $feature['title'] . "' type='text' required='' size='' class=' form-control inputField' readonly='readonly'></div></td>"
         . "</tr>";
}
$add_feature.='</tbody>
</table>';

$add_feature.='<div class="hr"><hr></div>';
$add_feature.='<div class="col-sm-12"> '
        . '  <input type="hidden" value="Insert" name="action" id="action">'
          . '<button type="button" class="btn btn-back " onclick="history.back();">Back</button>&nbsp;'
        . '<a href="'.$pageFAQ . "?pro_id=" . $_REQUEST['pro_id'].'" class="btn btn-back ">Skip</a>&nbsp;'
        . '<a href="javascript:;" class="btn btn-new SaveProductField"  data-redirect="'.$pageList .'" data-id="'.$_REQUEST['pro_id'].'">Save & Close</a>'
        . '<a href="javascript:;" class="btn btn-submit SaveProductField"  data-redirect="'.$pageFAQ . "?pro_id=" . $_REQUEST['pro_id'].'" data-id="'.$_REQUEST['pro_id'].'">Save & Continue </a> </div>';

$add_feature.="</div></div>";

echo $add_feature;

$form = new GenerateFormField();
$form->setColumns();
$form->setTypes();
$form->setExtendTables($sourc_Req_fields);

$form->setRequireds();
$form->setCountCell(1);
$form->setSubmit(true);
$form->setSkipBtn(true, $pageFAQ . "?pro_id=" . $_REQUEST['pro_id']);

$form->setSubmitName('Save & Continue');
$form->setSaveCloseBtn(true, 'Save & Close');
$form->setAsForm(true);
$form->setClassMain("col-sm-12");
$form->setSubMain(array('col-sm-12 float-pricing', 'red', 'col-sm-12'));
$form->setAppendToForm($add_feature);
//echo $form->getForm('Insert');

include_once '../../common/footer.php';
