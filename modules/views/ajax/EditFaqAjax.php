<?php

include "../../common/top_ajax.php";
$id=$_REQUEST['id'];
$qestion=$_REQUEST['question'];
$answer=$_REQUEST['answer'];
   $query = $fpdo->update('product_faq')->set(array('question' => $qestion,'answer'=>$answer))->where("id='$id'")->execute();
echo json_encode(1);
