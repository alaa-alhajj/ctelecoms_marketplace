<?php

include 'config/config.php';
include 'include/utils.php';
include_once ("../../../../config.php");
require_once '../../../common/dbConnection.php';
include_once ("lang.php");
$tags = $_REQUEST['tag'];
$transliteration = $_REQUEST['t'];
$file_path = $_REQUEST['old_path'];
$tags = $_REQUEST['tag'];
$filename =$_REQUEST['name_'.$VIEWLang];

//file_put_contents('newName', json_encode($VIEWLang));
//$m = rename_file($file_path, fix_filename($filename, $transliteration), $transliteration);
$id = addslashes($_POST['id']);
$name_en = addslashes($_POST['name_en']);
$desc_en = addslashes($_POST['desc_en']);

$sql = "select * from files where id ='$id'";

$res = mysql_query($sql);
$rows = mysql_num_rows($res);
$ext= mysql_result($res, 0,'ext');


if ($rows > 0) {
    foreach ($langsArrayView as $value) {
        $values.=",`name_" . $value[0] . "`='" . addslashes($_REQUEST['name_' . $value[0]]) . "'";
    }
    foreach ($langsArrayView as $value) {
        $values1.=",`desc_" . $value[0] . "`='" . addslashes($_REQUEST['desc_' . $value[0]]) . "'";
    }

$fi=$_REQUEST['name_'.$VIEWLang].'.'.$ext;
$actName='name_'.$VIEWLang;
$ff = preg_replace("/\.[^.]+$/", "", $filename);
    $ssq = "UPDATE files set act='1'  $values $values1 ,`name`='$ff'  , `tags`='$tags'"
            . " where id='$id'";



    mysql_query($ssq);
}
echo $ssq;
