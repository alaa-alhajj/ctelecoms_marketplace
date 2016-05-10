<?php  include('../../view/common/top.php');
@session_start();
$compare_id=$_REQUEST['compare_id'];

//remove compare id  to compareIDs session
if($compare_id!=''){
    $compareIDs=$_SESSION['compareIDs'];
    if(($key = array_search($compare_id, $compareIDs)) !== false) {
        unset($compareIDs[$key]);
    }
    $_SESSION['compareIDs']=$compareIDs;
}
print_r($compareIDs);