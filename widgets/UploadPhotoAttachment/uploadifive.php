<?php

include_once("../../config.php");
include_once("../../view/common/dbConnection.php");
file_put_contents("req", json_encode($_REQUEST));
file_put_contents("req", json_encode($_FILES));
@session_start();
error_reporting(0);

function randomStringUtil($length = 5) {
    $type = 'num';
    $randstr = '';
    srand((double) microtime() * 1000000);

    $chars = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0',
        'Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'L', 'K', 'J', 'H', 'G', 'F', 'D', 'S', 'A', 'Z', 'X', 'C', 'V', 'B', 'N', 'M',
        'q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'l', 'k', 'j', 'h', 'g', 'f', 'd', 's', 'a', 'z', 'x', 'c', 'v', 'b', 'n', 'm'
    );
    if ($type == "alpha") {
        array_push($chars, '1');
    }

    for ($rand = 0; $rand < $length; $rand++) {
        $random = rand(0, count($chars) - 1);
        $randstr .= $chars[$random];
    }
    return $randstr;
}

if ($_REQUEST['folder'] != '') {
    $uploadDir = '../../uploads/';
} else {
    $uploadDir = '../../attachments/';
}
if (!empty($_FILES)) {

    $f1 = explode('.', $_FILES['Filedata']['name'][0]);

    $fileParts = pathinfo($_FILES['Filedata']['name'][0]);
    $fileName = $f1[0];

    $fileExt = strtolower(end($f1));

    $rund = randomStringUtil(5);
    $ext = end(explode('.', $_FILES['Filedata']['name']));
    $newFile = date('U') . '_' . $rund . '.' . $ext;
    $targetFile = $uploadDir . $newFile;
    // Validate the file type
    $fileTypes = array('jpg', 'jpeg', 'gif', 'png', 'JPG', 'JPEG', 'GIF', 'PNG', 'pdf', 'PDF', 'doc', 'docx', 'DOC', 'DOCX', 'xlsx', 'XLS', 'xls', 'XLSX', 'ppt', 'PPT', 'pptx', 'PPTX'); // Allowed file extensions
    $fileParts = pathinfo($_FILES['Filedata']['name'][0]);
    $tempFile = $_FILES['Filedata']['tmp_name'];    
    // Validate the filetype
    
    if (in_array(strtolower($ext), $fileTypes)) {
        if (move_uploaded_file($tempFile, $targetFile)) {
            if ($_REQUEST['folder'] != "") {
                $query = "INSERT INTO `files` (`folder`,`file`,`name_en`,`ext`) values('','$newFile','$fileName','$fileExt')";


                $q = mysql_query($query);
                $last_id2 = $_SESSION['attach_id'] = mysql_insert_id();
            }
            echo $newFile;
        } else {
            echo 'False';
        }
    } else {

        echo 'False';
    }
}
?>