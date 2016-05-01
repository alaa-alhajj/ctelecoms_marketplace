<?php

error_reporting(0);
include_once ("../../../../config.php");
require_once '../../../common/dbConnection.php';
include_once ("lang.php");
//include_once ("dialog.php");
$name = $_POST['name'];

    $sql = "select * from files where file ='$name'";

    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
   
    $id = mysql_result($res, 0, 'id');
 

echo $id;
