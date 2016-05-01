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
$get_features = $fpdo->from($db_table_feature)->where("cat_id='" . $_REQUEST['id'] . "'")->orderBy("item_order ASC")->feathAll();

$add_feature = '<div class="box box-danger form-horizontal"><div class="box-body">';
$add_feature.='<div class="catFeatures"><div class="col-sm-10 nopadding"><input id="title" name="title" value="" type="text" required="" size="" class=" form-control" placeholder="Enter Feature"></div>';
$add_feature.="<div class='col-sm-2 '><a href='javascript:;' class='addCategoryFeature' data-cat='" . $_REQUEST['id'] . "'><i class='fa fa-plus-circle' aria-hidden='true' style='font-size:29px'></i></a></div>";
$add_feature.="</div>";
$add_feature.='<table id="TableCategoryFeatures" class="table table-striped  table table-bordered table-hover">
<thead>
<tr>
<th>Features</th>
<th>type</th><th>plus</th><th>is main</th>
<th width="30">Edit</th><th width="30">Delete</th>
</tr>
</thead>
<tbody id="sortable_a" class="sortable ui-sortable table-fileds-costum">';
$script = ""
        . " var order_table='$db_table_feature';\n"
        . " var order_filed='item_order';\n"
        . " var order_id='id';\n"
        . " ordIds=new Array();\n";
$m = 1;
foreach ($get_features as $feature) {
    $get_fe_val = $fpdo->from($db_table_feature)->where("id", $feature['id'])->fetch();
    $field_ob = new field();
    $field_ob->SetIdField('type' . $feature['id']);
    $field_ob->SetNameField('type');
    $field_ob->SetCssClass('nonedisplay');
    $field_ob->SetTypeField('select');
    $field_ob->SetTable();
    // $field_ob->SetRequiredField($this->requireds[$col]);
    $field_ob->SetTname('type');
    $field_ob->SetTvalue('id');
    $field_ob->SetValueField($get_fe_val['type']);
    $field_ob->setWhere();
    $field_ob->SetExtra($extra);
    
      if ($feature['type'] == 'DynamicSelect') {
        $fileds = $fpdo->from('cms_module_fields')->where("table_id='" . $feature['plus'] . "' and is_main='1'")->fetch();
        $module = $fpdo->from('cms_modules')->where('id', $feature['plus'])->fetch();
        $table = $module['title'];

        $plus = $table;
        $id_table=$feature['plus'];
    }else{
        $plus=str_replace('×', '', $feature['plus']);
    }
    $add_feature.="<tr id='f_" . $feature['id'] . "'>
	<td><input id='title' name='title' value='" . $feature['title'] . "' type='text' required='' size='' class=' form-control' readonly='readonly'></td>
	<td data-id='" . $feature['id'] . "'>" . $field_ob->getField() . "<span id='sp" . $feature['id'] . "'>" . $feature['type'] . "</span></td>
     <td id='plus_" . $feature['id'] . "' data-plus='".$id_table."'><span >" . $plus . '</span><input id="plus" name="" value="' . str_replace('×', '', $feature['plus']) . '" type="tags" size="" class="TagsInput tags_'.$feature['id'] .'" placeholder="" style="display:none">'
          
            . '</td>';

    $add_feature.="<td>" . $utils->switcher($db_table_feature, $feature['id'], 'is_main', $feature['is_main'], "SwitcherV") . "</td>\n";
    $add_feature.="<td><a href='javascript:;' data-id='" . $feature['id'] . "' class='editFeature'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a></td>
	<td><a href='javascript:;' data-id='" . $feature['id'] . "' class='DeleteFeature'><i class='fa fa-times' aria-hidden='true' ></i></a></td></tr>";
    $script.="ordIds[$m]='" . $feature['id'] . "';";
    $m++;
}
$add_feature.='</tbody>
</table>';
$pageInsertSEOHref = "'" . $pageInsertSEO . "?id=" . $_REQUEST['id'] . "'";
$add_feature.='<div class="hr"><hr></div>';
$add_feature.='<div class="col-sm-12">  '
        . ' <input type="hidden" value="Insert" name="action" id="action">'
        . '<button type="button" class="btn btn-back " onclick="history.back();">Back</button>&nbsp;'
        . '<input type="button"  class="btn btn-new" value="Save & Close" onClick="location.href=' . "$pageListHref" . '">'
        . '<input type="button"  class="btn btn-submit" value="Save & Continue" onClick="location.href=' . "$pageInsertSEOHref" . '">  </div>';

$add_feature.="</div></div>";

$add_feature.="<script>$script</script>\n";
echo $add_feature;
include_once '../../common/footer.php';
