<?php

include "../../common/top.php";
include '../../common/header.php';
include 'config.php';
if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
    $save_ob = new saveform($db_table, $_REQUEST, $Savecols);

    $utils->redirect($congrats);
}
echo $path = '<ul id="breadcrumbs-one">
    <li><a href="#">Duration</a></li>
    <li><a href="#">Types</a></li>
    <li><a href="#">Measurement Units</a></li>
    <li class="active-menue"><a href="#" >Groups</a></li>
</ul>';

$get_durations = $fpdo->from($db_pro_Groups)->feathAll();
$row_id = explode('_', $db_pro_Groups);
$add_feature = '<div class="box box-danger form-horizontal"><div class="box-body">';
$add_feature.='<div class="catFeatures"><div class="col-sm-10 nopadding"><input id="title" name="title" value="" type="text" required="" size="" class=" form-control" placeholder="New Group"></div>';
$add_feature.="<div class='col-sm-2 '><a href='javascript:;' class='addPricingSetting' data-table='" . $db_pro_Groups . "'><i class='fa fa-plus-circle' aria-hidden='true' style='font-size:29px'></i></a></div>";
$add_feature.="</div>";
$add_feature.='<table id="TablePricingDuration" class="table table-striped  table table-bordered table-hover">
<thead>
<tr>
<th>Pricing Groups</th><th width="30">Edit</th>
<th width="30">Delete</th>
</tr>
</thead>
<tbody id="" class="sortable ui-sortable">';
foreach ($get_durations as $duration) {
    $add_feature.="<tr id='" . $row_id[2] . '_' . $duration['id'] . "'><td><input id='title' name='title' value='" . $duration['title'] . "' type='text' required='' size='' class=' form-control' readonly='readonly'></td><td><a href='javascript:;' data-id='" . $duration['id'] . "' data-table='" . $db_pro_Groups . "' class='editPricingSetting'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a></td><td><a href='javascript:;' data-id='" . $duration['id'] . "' data-table='" . $db_pro_Groups . "' class='DeletePricingSetting'><i class='fa fa-times' aria-hidden='true' ></i></a></td></tr>";
}
$add_feature.='</tbody>
</table>';

$add_feature.='<div class="hr"><hr></div>';
$add_feature.='<div class="col-sm-12 nopadding">  '
        . ' <input type="hidden" value="Insert" name="action" id="action"><button type="button" class="btn btn-back " onclick="history.back();">Back</button>&nbsp;'
       . '<button type="button" class="btn btn-back " onclick="location.href='."$congrats".'">Skip</button>&nbsp;'
        . '<input type="button"  class="btn btn-submit" value="Save & Continue" onClick="location.href=' . "$congrats" . '">  </div>';

$add_feature.="</div></div>";

echo $add_feature;
include_once '../../common/footer.php';
