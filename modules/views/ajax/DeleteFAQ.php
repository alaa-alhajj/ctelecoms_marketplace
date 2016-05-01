<?php

include '../../common/top.php';
$id = $_REQUEST['id'];
$query = $fpdo->deleteFrom('product_faq')->where("id='$id'")->execute();
echo json_encode(1);
