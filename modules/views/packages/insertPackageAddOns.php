<?php

include '../../common/header.php';
include 'config.php';
$id = $_REQUEST['pro_id'];
if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
    $addOns_ids = "";
    foreach ($_REQUEST['Slectedproduct'] as $product_id) {
        $addOns_ids.=$product_id . ",";
    }
    $query = $fpdo->update($db_table)->set(array('`add_ons_pro_ids`' => $addOns_ids))->where("id='$id'");
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
        $utils->redirect($pageProductRelated . "?pro_id=" . $_REQUEST['pro_id']);
    }
}
echo $path = '<ul id="breadcrumbs-one">
    <li><a href="#">Package Data</a></li>
    <li><a href="#">Package Products</a></li>
    <li><a href="#">Features</a></li>
    <li><a href="">Photos</a></li>
    <li><a href="#">Pricing</a></li>
    <li class="active-menue"><a href="#">Add-ons</a></li>
    <li><a href="#">Related Products</a></li>
    <li><a href="#">Required Fields</a></li>
    <li><a href="#">FAQ</a></li>
    <li><a href="#">SEO</a></li>
</ul>';





$get_From_products = $query = $fpdo->from('products')->where("id !='$id'")
        ->fetchAll();
$select_products = "";
$select_products.='<div style="position:relative"><select class="" name="AllAddOns" id="AllAddOns" style="width: 100%;">';
foreach ($get_From_products as $pro_desc) {
    $pro_id = $pro_desc['id'];
    $select_products.="<option value='$pro_id' > " . $pro_desc['title'] . '</option>';
}
$select_products.="</select></div>";
$AllProducts.="<div class='row search-style'>"
        . "<input type='hidden' name='ReqProduct' id='ReqProduct' value='" . $id . "'>"
        . "<div class='col-sm-10'>" . $select_products . "</div>"
        . '<div class="col-sm-2"><button type="button" id="buttonSearchAddOns" data-place="Search Add-Ons" class="btn btn-submit"><span class="fa fa-search"></span></button></div>'
        . "</div><div class='AddAddOnsTo div-search'>";

$productsA = $query = $fpdo->from('products')->where("id='" . $_REQUEST['pro_id'] . "'")->fetch();

$addOns_array = explode(',', rtrim($productsA['add_ons_pro_ids'], ','));
foreach ($addOns_array as $addOns) {
    if ($addOns != "") {
         $productsName = $query = $fpdo->from('products')->where("id='" . $addOns . "'")->fetch();
        $AllProducts.="<div id='AddonsSelect$addOns' data-id='$addOns'  >" . $productsName['title'] . ""
                . "<a data-id='$addOns' href='javascript:;'  class='remove-AddOns'><i class='fa fa-times'></i></a>"
                . "<input type='hidden' value='" . $addOns . "' name='Slectedproduct[]'></div>";
    }
}
$AllProducts.= "</div>";



$form = new GenerateFormField();
$form->setColumns();
$form->setTypes();
$form->setExtendTables($source);
$form->setRequireds($required);

$form->setCountCell(1);
$form->setSubmit(true);
$form->setSubmitName('Save & Continue');
$form->setSaveCloseBtn(true, 'Save & Close');
$form->setAsForm(true);
$form->setSkipBtn(true, $pageProductRelated . "?pro_id=" . $_REQUEST['pro_id']);
$form->setBackBtn(true);
$form->setAppendToForm($AllProducts);
echo $form->getForm('Insert');
include_once '../../common/footer.php';
