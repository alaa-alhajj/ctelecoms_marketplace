<?php 
include('../../view/common/top.php');


$compare_id=$_REQUEST['compare_id'];

//add compare id  to compareIDs session
if($compare_id!=''){
    $compareIDs=$_SESSION['compareIDs'];
    $compareIDs[$compare_id]=$compare_id;
    $_SESSION['compareIDs']=$compareIDs;
}
print_r($compareIDs);