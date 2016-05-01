<?php
include('../../../view/common/top_ajax.php');
$widget_id = $_REQUEST['widget_id'];
echo $widgets->printWidget($widget_id);

?>