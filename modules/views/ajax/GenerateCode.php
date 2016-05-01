<?php

include "../../common/top_ajax.php";
$utils = new utils();
$code = $utils->randomStringUtil(5);
$check_code=$fpdo->from('promo_codes')->where("code='$code'")->fetch();
if($check_code['id'] !=""){
    $go_code=$utils->randomStringUtil(5);
}else{
  $go_code=$code;
}
echo json_encode($go_code);


