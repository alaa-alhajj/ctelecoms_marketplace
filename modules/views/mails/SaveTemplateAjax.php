<?php
include "../../common/top.php";
include 'config.php';



$fpdo->update($db_table)->set(array('description' => $_REQUEST['description']))->where('id', '0')->execute();

echo json_encode(1);