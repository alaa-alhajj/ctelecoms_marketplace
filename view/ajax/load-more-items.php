<?php
include('../../view/common/top.php');
$widget_id = $_REQUEST['widget_id'];
echo $widgets->printWidget($widget_id);

?>