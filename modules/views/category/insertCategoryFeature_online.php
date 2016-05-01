<?php

include "../../common/top.php";
include '../../common/header.php';
include 'config.php';
if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
    $save_ob = new saveform($db_table, $_REQUEST, $Savecols);

    $utils->redirect($pageList);
}
echo $path = '<ul id="breadcrumbs-one">
  <li><a href="#">Category Info</a></li>
     <li><a href="#">Category Photos</a></li>
    <li  class="active-menue"><a href="#">Category Features</a></li>
     <li><a href="#">SEO</a></li>
    
</ul>';
$get_features = $fpdo->from($db_table_feature)->where("cat_id='" . $_REQUEST['id'] . "'")->feathAll();

$add_feature = '<div class="box box-danger form-horizontal"><div class="box-body">';
$add_feature.='<div class="catFeatures"><div class="col-sm-10 nopadding"><input id="title" name="title" value="" type="text" required="" size="" class=" form-control" placeholder="Enter Feature"></div>';
$add_feature.="<div class='col-sm-2 '><a href='javascript:;' class='addCategoryFeature' data-cat='" . $_REQUEST['id'] . "'><i class='fa fa-plus-circle' aria-hidden='true' style='font-size:29px'></i></a></div>";
$add_feature.="</div>";
$add_feature.='<table id="TableCategoryFeatures" class="table table-striped  table table-bordered table-hover">
<thead>
<tr>
<th>Features</th><th width="30">Edit</th>
<th width="30">Delete</th>
</tr>
</thead>
<tbody id="" class="sortable ui-sortable">';
foreach ($get_features as $feature) {
    $add_feature.="<tr id='f_" . $feature['id'] . "'><td><input id='title' name='title' value='" . $feature['title'] . "' type='text' required='' size='' class=' form-control' readonly='readonly'></td><td><a href='javascript:;' data-id='" . $feature['id'] . "' class='editFeature'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a></td><td><a href='javascript:;' data-id='" . $feature['id'] . "' class='DeleteFeature'><i class='fa fa-times' aria-hidden='true' ></i></a></td></tr>";
}
$add_feature.='</tbody>
</table>';
$pageInsertSEOHref="'".$pageInsertSEO . "?id=" . $_REQUEST['id']."'";
$add_feature.='<div class="hr"><hr></div>';
$add_feature.='<div class="col-sm-12">  '
        . ' <input type="hidden" value="Insert" name="action" id="action">'
        . '<button type="button" class="btn btn-back " onclick="history.back();">Back</button>&nbsp;'
        . '<input type="button"  class="btn btn-new" value="Save & Close" onClick="location.href=' . "$pageListHref" . '">'
        . '<input type="button"  class="btn btn-submit" value="Save & Continue" onClick="location.href=' . "$pageInsertSEOHref" . '">  </div>';

$add_feature.="</div></div>";

echo $add_feature;
include_once '../../common/footer.php';
