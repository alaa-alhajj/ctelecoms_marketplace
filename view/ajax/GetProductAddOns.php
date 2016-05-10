<?php
include('../../view/common/top.php');
@session_start();
$product_id = $_REQUEST['product_id'];
$get_addons = $fpdo->from("products")->where("id='$product_id'")->fetch();
$addons_Array = explode(",", $get_addons['add_ons_pro_ids']);
$row_addons = '<table width="100%" class="table table-stripped table-valign-middle table-striped ">'
        . '<tbody>';
foreach ($addons_Array as $addons) {

    if ($addons != "") {
        $get_pro = $fpdo->from('products')->where("id='$addons'")->fetch();
        $photos = explode(',', $get_pro['photos']);
        $addOn_photo = $utils->viewPhoto($photos[0], 'crop', 50, 50, 'img', 1, $_SESSION['dots'], 1, 0, 'img-responsive ');
        $link = _PREF . $_SESSION['pLang'] . "/page" . $addons['page_id'] . "/" . $utils->rewriteFilter($addons['title']);
        $check_session_compare = $_SESSION['compareIDs'];
        if ($check_session_compare[$addons] != "") {
            $class = 'removeFromCompare added';
        } else {
            $class = "addToCompare";
        }


        $get_dynamic_price = $fpdo->from('product_dynamic_price')->where("product_id='$addons'")->fetch();
        $dynamic_price_id = $get_dynamic_price['id'];
        $get_groups = explode(',', rtrim($get_dynamic_price['group_ids'], ','));
        $groups_select = "<select name='' id='' class='ProductGroups ProductGroups_$addons' data-dynamic='" . $dynamic_price_id . "' data-product='" . $addons . "'>";
        foreach ($get_groups as $groups) {
            $get_title_g = $fpdo->from('pro_price_groups')->where("id='$groups'")->fetch();
            $get_unit_name = $fpdo->from('pro_price_units')->where("id", $get_dynamic_price['unit_id'])->fetch();
            $groups_select.="<option value='" . $groups . "'>" . $get_title_g['title'] . ' ' . $get_unit_name['title'] . "</option>";
        }
        $groups_select.="</select>";

        $get_durations = explode(',', rtrim($get_dynamic_price['duration_ids'], ','));
        $durations_select = "<select name='' id='' class='ProductDurations ProductDurations_$addons' data-dynamic='" . $dynamic_price_id . "' data-product='" . $addons . "'>";
        foreach ($get_durations as $duration) {
            $get_title_du = $fpdo->from('pro_price_duration')->where("id='$duration'")->fetch();
            $durations_select.="<option value='" . $duration . "'>" . $get_title_du['title'] . "</option>";
        }
        $durations_select.="</select>";

        $check_session_ShoppinCart = $_SESSION['Shopping_Cart'];

        if ($check_session_ShoppinCart[$addons] != "") {
            
        }else{
            $row_addons.="<tr id='selected_$addons'>"
                    . "<td>" . $addOn_photo . "</td>"
                    . "<td>" . $get_pro['title'] . "</td>"
                    . "<td>" . $durations_select . "</td>"
                    . "<td class='rel-div'>" . $groups_select . "</td>"
                    . "<td>" . '<a href="javascript:;" class="addToCartSmall " data-id="' . $addons . '">
                                 <i class="fa fa-cart-plus" aria-hidden="true"></i>
                                 </a>' . "</td>"
                    . "<td>" . '<a href="javascript:;" class="' . $class . '" data-id="' . $addons . '" data-small="small">
                                 <i class="fa fa-refresh" aria-hidden="true"></i>
                                 </a>' . "</td>"
                    . "<td class='product_price_$addons  rel-div'></td>"
                    . "</tr>";
        }
    }
}
$row_addons.="</tbody></table>";
echo json_encode($row_addons);
?>