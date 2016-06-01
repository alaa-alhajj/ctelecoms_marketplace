<?php

include('../../view/common/top.php');


$compare_id = $_REQUEST['compare_id'];

//add compare id  to compareIDs session
if ($compare_id != '') {
    $compareIDs = $_SESSION['compareIDs'];
    $compareIDs[$compare_id] = $compare_id;
    $_SESSION['compareIDs'] = $compareIDs;
}

function checkCompareAbility($fpdo, $products_ids) {

    $cat_ids = array();
    foreach ($products_ids as $id) {
        $product_info = $fpdo->from("products")->where("id='$id'")->fetch();
        $cat_id = $product_info['cat_id'];
        $cat_ids[] = $cat_id;
    }
    //print_r($cat_ids);
    $compareStatus = FALSE;
    if ((count(array_unique($cat_ids)) === 1) && (count($cat_ids) > 0)) {
        $compareStatus = True;
        return array('compareStatus' => $compareStatus, 'cat_id' => $cat_ids[0]);
    }

    return array('compareStatus' => $compareStatus, 'cat_id' => '');
}

$res = checkCompareAbility($fpdo, $_SESSION['compareIDs']);
 $compare_status=$res['compareStatus'];
 if($compare_status){
     echo json_encode(1);
 }else{
     if($compare_id!=''){
    $compareIDs=$_SESSION['compareIDs'];
    if(($key = array_search($compare_id, $compareIDs)) !== false) {
        unset($compareIDs[$key]);
    }
    $_SESSION['compareIDs']=$compareIDs;
}
     echo json_encode("Sorry, You can't compare these products.");
 }
