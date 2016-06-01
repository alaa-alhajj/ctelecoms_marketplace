<?

include "../../common/top_ajax.php";
$get_cats=$fpdo->from("purchase_order_products")->select("count(*) as cnt,product_category.title as cat_name")
               ->leftJoin("products on products.id=purchase_order_products.product_id")
               ->leftJoin("product_category on product_category.id=products.cat_id")
              ->groupBy("products.cat_id")->fetchAll();
$cats="";
foreach($get_cats as $cat){
   $cats.= $cat['cat_name'].",";
}
echo json_encode($cats);