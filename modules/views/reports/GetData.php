<?

include "../../common/top_ajax.php";

if ($_REQUEST['type'] == 'Customers_Region') {

    $query2 = $fpdo->from("customers")->select("count(*) as cnt")->groupBy("city")->fetchAll();
    $json_array = array();
    foreach ($query2 as $q) {
        $ans = $q['city'];
        $cnt = $q['cnt'];
        $array = array();
        $array['name'] = $ans;
        $array['data'] = intval($cnt);
        array_push($json_array, $array);
    }
    echo json_encode($json_array);
} elseif ($_REQUEST['type'] == 'Customers_Date') {
    $year = $_REQUEST['year'];
    $query2 = $fpdo->from("customers")->select("count(*) as cnt,MONTHNAME(`register_date`) as date")
                    ->where("YEAR(register_date)='$year'")
                    ->groupBy("DATE(`register_date`)")->fetchAll();
    $json_array = array();
    foreach ($query2 as $q) {
        $ans = $q['date'];
        $cnt = $q['cnt'];
        $array = array();
        $array['name'] = $ans;
        $array['data'] = intval($cnt);
        array_push($json_array, $array);
    }
    echo json_encode($json_array);
} elseif ($_REQUEST['type'] == 'PO_customer') {
    $query2 = $fpdo->from("purchase_order")->select("count(*) as cnt,customers.name as name")
                    ->leftJoin("customers on customers.id=purchase_order.customer_id")
                    ->groupBy("purchase_order.customer_id")->fetchAll();
    $json_array = array();
    foreach ($query2 as $q) {
        $ans = $q['name'];
        $cnt = $q['cnt'];
        $array = array();
        $array['name'] = $ans;
        $array['data'] = intval($cnt);
        array_push($json_array, $array);
    }
    echo json_encode($json_array);
} elseif ($_REQUEST['type'] == 'PO_region') {
    $query2 = $fpdo->from("purchase_order")->select("count(*) as cnt,customers.city as city")
                    ->leftJoin("customers on customers.id=purchase_order.customer_id")
                    ->groupBy("customers.city")->fetchAll();
    $json_array = array();
    foreach ($query2 as $q) {
        $ans = $q['city'];
        $cnt = $q['cnt'];
        $array = array();
        $array['name'] = $ans;
        $array['data'] = intval($cnt);
        array_push($json_array, $array);
    }
    echo json_encode($json_array);
} elseif ($_REQUEST['type'] == 'PO_date') {
    $year = $_REQUEST['year'];
    $query2 = $fpdo->from("purchase_order")->select("count(*) as cnt,MONTHNAME(order_date) as month")
                    ->where("YEAR(`order_date`)='$year'")
                    ->groupBy("MONTH(`order_date`)")->fetchAll();
    $json_array = array();
    foreach ($query2 as $q) {
        $ans = $q['month'];
        $cnt = $q['cnt'];
        $array = array();
        $array['name'] = $ans;
        $array['data'] = intval($cnt);
        array_push($json_array, $array);
    }
    echo json_encode($json_array);
} elseif ($_REQUEST['type'] == 'pro_cat') {

    $query2 = $fpdo->from("product_category")->select("count(*) as cnt,product_category.title as cat_name")
                    ->Join("products on products.cat_id=product_category.id")
                    ->Join("purchase_order_products on purchase_order_products.product_id=products.id")
                    ->groupBy("product_category.id")->fetchAll();
    $json_array = array();
    $cat_Array = array();
    foreach ($query2 as $q) {
        $ans = $q['cat_name'];
        $cnt = $q['cnt'];
        $array = array();
        $array['name'] = $ans;
        $array['data'] = intval($cnt);
        array_push($json_array, $array);
        array_push($cat_Array, $ans);
    }

    echo json_encode(array($json_array, $cat_Array));
} elseif ($_REQUEST['type'] == 'pro_date') {
    $products = $fpdo->from('products')->fetchAll();
    //$return = "[";
    $counter = 0;
    $json_array = array();
    foreach ($products as $pro) {
        $id = $pro['id'];
        $title = $pro['title'];
        // $return.="{name:'$title'";

        $productAr = array();
        // $productAr['name'] = $title;
        $query2 = $fpdo->from("purchase_order")->select("count(*) as cnt,MONTH(purchase_order.order_date) as month")
                        ->Join("purchase_order_products on purchase_order_products.product_id=purchase_order.id")
                        ->Join("products on products.id=purchase_order_products.product_id")
                        ->where("products.id", $pro['id'])
                        ->groupBy("products.id,MONTH(purchase_order.order_date)")->fetchAll();
        $monthArray = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        //  $return.=",data:[";
        if (count($query2) > 0) {

            foreach ($query2 as $q) {
                $ans = $q['month'];
                $cnt = $q['cnt'];
                $monthArray[$ans - 1] = (int)$cnt;
                $array = array();
                $array['name'] = $title;
                $array['data'] = $monthArray;
               // echo json_encode($array);
                array_push($json_array, $array);
            }
            // $productAr['data'] = $monthArray;
        }else{
            $ans = $q['month'];
                $cnt = 0;
                $monthArray[$ans - 1] = (int)$cnt;
                $array = array();
                $array['name'] = $title;
                $array['data'] = $monthArray;
               // echo json_encode($array);
                array_push($json_array, $array);
        }
        //  $return.=implode(",", $monthArray);
        // $return.="]}";
        if ($counter < count($products) - 1) {
            // $return.=",";
        }
        $counter++;
    }
    // $return.="]";
    //  $return;
    echo json_encode($json_array);

    /*  $months = array('3');
      $json_array = array();
      foreach ($months as $month) {
      $products = $fpdo->from('products')->orderBy("id desc")->fetchAll();

      foreach ($products as $pro) {
      $id = $pro['id'];
      $title = $pro['title'];
      $query2 = $fpdo->from("purchase_order")->select("count(*) as cnt,MONTH(purchase_order.order_date) as month")
      ->Join("purchase_order_products on purchase_order_products.product_id=purchase_order.id")
      ->Join("products on products.id=purchase_order_products.product_id")
      ->where("products.id='1' and MONTH(purchase_order.order_date)='$month'")
      ->fetch();
      file_put_contents('chart.txt', $query2);
      if (count($query2) > 0) {
      $ans = $q['month'];
      $cnt = $q['cnt'];
      $array = array();
      $array['name'] = $title;
      $array['data'] = intval($cnt);
      echo json_encode($array);
      }
      }
      } */
}