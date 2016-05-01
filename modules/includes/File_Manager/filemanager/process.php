<?php

error_reporting(0);
include_once ("../../../../config.php");
require_once '../../../common/dbConnection.php';
include_once ("lang.php");
//include_once ("dialog.php");
$idd = $_POST['id'];


$fildName = $_POST['fildName'];
$sql = "select * from files where id ='$idd'";

$res = mysql_query($sql);
$rows = mysql_num_rows($res);
$an = mysql_result($res, 0, 'type');
$name_ar = mysql_result($res, 0, 'name_ar');

$name_en = mysql_result($res, 0, 'name_en');

$desc_ar = mysql_result($res, 0, 'desc_ar');

$desc_en = mysql_result($res, 0, 'desc_en');
$tags = mysql_result($res, 0, 'tags');
$out = '<div class="modal-header">
                
                  <h3>Edit</h3>
                </div><input type="hidden" name="id" value="' . $idd . '">';
$out.='<input class="input-block-level" autocomplete="off"  type="hidden" name="name" id="C">';
foreach ($langsArrayView as $value) {
    if ($value[0] == $ActiveLang)
        $m = 'id="A"';
    $out.='Name_' . $value[0] . ': <br/><input value="' . ${'name_' . $value[0]} . '" name="name_' . $value[0] . '" class="input-xlarge ' . $filedName . '" "' . $m . '"/><br/>';
}
foreach ($langsArrayView as $value) {
    $out.=' Desc_' . $value[0] . ': <br/><textarea rows="4" cols="41" name="desc_' . $value[0] . '" style="height: 91px; width: 277px;" class="input-xlarge ' . $filedName . '" id="' . $value[0] . 'editDescVal' . $filedName . '">' . ${'desc_' . $value[0]} . '</textarea><br/>';
}

$out.=' </div>';
$out.='  <div id="wrapper">
        <p>Tags :
            <input name="tag" id="tagsVal' . $_POST['fildName'] . '"  class="ttagsVal' . $_POST['fildName'] . '" value="' . $tags . '" />
        </p>
    </div>
               <link rel="stylesheet" href="css/tags.css" type="text/css"/>
<script type="text/javascript" src="js/jquery.tagsinput.min.js"></script>
    <script>
    $(function() {
   var myInput = $("#A");
   myInput.change(function() {
       $("#C").val(myInput.val());
      document.getElementsByName("name")[0].value =myInput.val() ;
   });
});
        $(".ttagsVal' . $_POST['fildName'] . '").tagsInput({
   
        });
    </script> 
';

echo $out;
