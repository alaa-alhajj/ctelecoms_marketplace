<?php

include "../../common/top_ajax.php";
$utils = new utils();
echo $utils->createComboBoxFiltered('cms_modules', 'id', 'title', $_REQUEST['val'], $_REQUEST['field'],"","",$_REQUEST['class']);


