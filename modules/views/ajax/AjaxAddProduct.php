<?php

include '../../common/top.php';
$id = $_REQUEST['id'];

$query = $fpdo->from('products')->where("id ='$id'")->fetch();

if ($query) {

    echo $html = "<div id='AddonsSelect$id' data-id='$id'  >" . $query['title'] . ""
  
    . "<a data-id='$id' href='javascript:;'  class='remove-AddOns'><i class='fa fa-times'></i></a>"
    . "<input type='hidden' value='" . $id . "' name='Slectedproduct[]'></div>";
}