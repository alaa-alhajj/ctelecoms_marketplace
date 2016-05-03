<?php  include '../../../view/common/top_ajax2.php';


$compare_id=$_REQUEST['compare_id'];

//add compare id  to compareIDs session
if($compare_id!=''){
    $compareIDs=$_SESSION['compareIDs'];
    $compareIDs[]=$compare_id;
    $_SESSION['compareIDs']=$compareIDs;
}
print_r($compareIDs);