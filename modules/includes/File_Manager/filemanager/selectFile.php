<?php

error_reporting(0);
include_once ("../../../../config.php");
require_once '../../../common/dbConnection.php';
include_once ("lang.php");
//include_once ("dialog.php");
$idd = $_POST['id'];
$arr = explode(',', $idd);

function removeElementWithValue($array, $key, $value) {
    foreach ($array as $subKey => $subArray) {
        if ($subArray[$key] == $value) {
            unset($array[$subKey]);
        }
    }
    return $array;
}

$return_arr1[] = array();
foreach ($arr as $a) {
    if ($a != "") {
        $sql = "select * from files where id ='$a'";

        $res = mysql_query($sql);
        $rows = mysql_num_rows($res);
        $file = mysql_result($res, 0, 'file');
        $folder = mysql_result($res, 0, 'folder');
        $id = mysql_result($res, 0, 'id');
        //  array_push($arr1, $folder.$file);
        $row_array['url'] = $folder . $file;
        $row_array['id'] = $id;
        if ($id)
            array_push($return_arr1, $row_array);
        else {
            array_push($return_arr1, array('url' => _PREF . MODULES_FOLDER . "/includes/File_Manager/filemanager/img/ico/removed.jpg", "id" => $a));
        }
    }
}
//$return_arr1 = removeElementWithValue($return_arr1, "url","--");
$str = json_encode($return_arr1);
$str = str_replace('[],', '', $str);

echo $str;
