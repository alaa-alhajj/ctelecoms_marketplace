<?php
include('../../common/header.php');
?>
<section class="col-lg-12 connectedSortable">

    <!-- Map box -->
    <div class="box box-solid bg-danger">
        <div class="box-header">
            <!-- tools box -->
            <div class="pull-right box-tools">
                <button class="btn btn-danger btn-sm daterange pull-right" data-toggle="tooltip" title="Date range"><i class="fa fa-calendar"></i></button>
                <button class="btn btn-danger btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>
            </div><!-- /. tools -->
            <i class="fa fa-map-marker"></i>
            <h3 class="box-title">
                Visitors
            </h3>
        </div>
        <div class="box-body">
            <div id="world-map" style="height: 250px; width: 100%;"></div>
        </div><!-- /.box-body-->
        <div class="box-footer no-border">
            <div class="row">
                <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                    <div id="sparkline-1"></div>
                    <div class="knob-label">Visitors</div>
                </div><!-- ./col -->
                <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                    <div id="sparkline-2"></div>
                    <div class="knob-label">Online</div>
                </div><!-- ./col -->
                <div class="col-xs-4 text-center">
                    <div id="sparkline-3"></div>
                    <div class="knob-label">Exists</div>
                </div><!-- ./col -->
            </div><!-- /.row -->
        </div>

    </div>
    <!-- /.box -->
    <?
    // latest Customers
    $customers = $fpdo->from("customers")->orderBy("id desc")->limit(5)->fetchAll();
    $latest_customers = "
            <div class='panel dashboard-box panel-scroll-y'>
    <div class='panel-heading'>Latest Customers
    </div>";
    $latest_customers .= "<table id='TableForm' class='table table-striped'>
<thead>
<tr><thead><th>Name</th><th width='30'>Edit</th><th width='30'>View</th></thead></tr><tbody>";
    foreach ($customers as $customer) {
        $link_edit = "../cms_modules/updateModules.php?id=" . $customer['id'] . "&cmsMID=100";
        $link_page = _PREF . $cmsMlang . '/page' . $customer['page_id'] . '/' . $utils->rewriteFilter($customer['title']);
        $latest_customers.="<tr>";
        $latest_customers.="<td>" . $customer['name'] . "</td>";
        $latest_customers.='<td><a href="' . $link_edit . '" title="" data-toggle="tooltip" data-original-title="edit"><i class="fa fa-pencil-square-o"></i></a></td>';
        $latest_customers.='<td><a href="' . $link_page . '" target="_blank" title="" data-toggle="tooltip" data-original-title="view"><i class="fa fa-search"></i></a></td>';
        $latest_customers.="</tr>";
    }
    $latest_customers.="</tbody></table></div>";


    //latest Products
    $Products = $fpdo->from("products")->orderBy("id desc")->limit(5)->fetchAll();
    $latest_products = "
            <div class='panel dashboard-box panel-scroll-y'>
    <div class='panel-heading'>Latest Products
    </div>";
    $latest_products .= "<table id='TableForm' class='table table-striped'>
<thead>
<tr><thead><th>Name</th><th width='30'>Edit</th><th width='30'>View</th></thead></tr><tbody>";
    foreach ($Products as $product) {
        if ($product['is_package'] === '0' || $product['is_package'] === 0) {
            $link_edit = "../products/updateProduct.php?id=" . $product['id'] . "&cmsMID=96";
        } else {
            $link_edit = "../packages/updatePackage.php?id=" . $product['id'] . "&cmsMID=97";
        }
        $link_page = _PREF . $cmsMlang . '/page' . $product['page_id'] . '/' . $utils->rewriteFilter($product['title']);
        $latest_products.="<tr>";
        $latest_products.="<td>" . $product['title'] . "</td>";
        $latest_products.='<td><a href="' . $link_edit . '" title="" data-toggle="tooltip" data-original-title="edit"><i class="fa fa-pencil-square-o"></i></a></td>';
        $latest_products.='<td><a href="' . $link_page . '" target="_blank" title="" data-toggle="tooltip" data-original-title="view"><i class="fa fa-search"></i></a></td>';
        $latest_products.="</tr>";
    }
    $latest_products.="</tbody></table></div>";

    //Latesr Purchase Orders

    $Purchase_orders = $fpdo->from("purchase_order")
                    ->select("customers.name as cu_name,purchase_order.order_date as date")
                    ->leftJoin("customers on customers.id=purchase_order.customer_id")
                    ->orderBy("id desc")->limit(5)->fetchAll();
    $latest_orders = "
            <div class='panel dashboard-box panel-scroll-y'>
    <div class='panel-heading'>Latest Purchase Orders
    </div>";
    $latest_orders .= "<table id='TableForm' class='table table-striped'>
<thead>
<tr><thead><th>Customer Name</th><th>Date</th><th width='30'>Details</th><th width='30'>View</th></thead></tr><tbody>";
    foreach ($Purchase_orders as $pur_order) {
        $link_edit = "../purchase_orders/DetailsOfPurchaseOrder.php?id=" . $pur_order['id'] . "&cmsMID=105";
        $link_page = _PREF . $cmsMlang . '/page' . $pur_order['page_id'] . '/' . $utils->rewriteFilter($pur_order['cu_name']);
        $latest_orders.="<tr>";
        $latest_orders.="<td>" . $pur_order['cu_name'] . "</td>";
        $latest_orders.="<td>" . $pur_order['date'] . "</td>";
        $latest_orders.='<td><a href="' . $link_edit . '" title="" data-toggle="tooltip" data-original-title="Details"><i class="fa fa-list-ol"></i></a></td>';
        $latest_orders.='<td><a href="' . $link_page . '" target="_blank" title="" data-toggle="tooltip" data-original-title="view"><i class="fa fa-search"></i></a></td>';
        $latest_orders.="</tr>";
    }
    $latest_orders.="</tbody></table></div>";


    //Top visited products
    $top_products = $fpdo->from("cms_pages")
                    ->select("products.title as pro_name,products.id as pro_id,products.is_package as is_package,products.page_id as page_id")
                    ->leftJoin("products on products.page_id=cms_pages.id")
                    ->where("cms_pages.is_main='0' and(cms_pages.module_id='96' or cms_pages.module_id='97')")
                    ->orderBy("cms_pages.views desc")->limit(5)->fetchAll();
    $top_visited = "
            <div class='panel dashboard-box panel-scroll-y'>
    <div class='panel-heading'>Top Visited Products
    </div>";
    $top_visited .= "<table id='TableForm' class='table table-striped'>
<thead>
<tr><thead><th>Name</th><th width='30'>Edit</th><th width='30'>View</th></thead></tr><tbody>";
    foreach ($top_products as $top_pro) {
        if ($top_pro['is_package'] === '0' || $top_pro['is_package'] === 0) {
            $link_edit = "../products/updateProduct.php?id=" . $top_pro['pro_id'] . "&cmsMID=96";
        } else {
            $link_edit = "../packages/updatePackage.php?id=" . $top_pro['pro_id'] . "&cmsMID=97";
        }
        $link_page = _PREF . $cmsMlang . '/page' . $top_pro['page_id'] . '/' . $utils->rewriteFilter($top_pro['pro_name']);
        $top_visited.="<tr>";
        $top_visited.="<td>" . $top_pro['pro_name'] . "</td>";
        $top_visited.='<td><a href="' . $link_edit . '" title="" data-toggle="tooltip" data-original-title="edit"><i class="fa fa-pencil-square-o"></i></a></td>';
        $top_visited.='<td><a href="' . $link_page . '" target="_blank" title="" data-toggle="tooltip" data-original-title="view"><i class="fa fa-search"></i></a></td>';
        $top_visited.="</tr>";
    }
    $top_visited.="</tbody></table></div>";
    
     //Top saled products
    $saled_products = $fpdo->from("purchase_order_products")
                    ->select("products.title as pro_name,products.id as pro_id,products.page_id as page_id,products.is_package as is_package")
                    ->leftJoin("products on products.id=purchase_order_products.product_id")
                  
                    ->orderBy("purchase_order_products.id desc")->groupBy("purchase_order_products.product_id")->limit(5)->fetchAll();
    $top_saled = "
            <div class='panel dashboard-box panel-scroll-y'>
    <div class='panel-heading'>Top Saled Products
    </div>";
    $top_saled .= "<table id='TableForm' class='table table-striped'>
<thead>
<tr><thead><th>Name</th><th width='30'>Edit</th><th width='30'>View</th></thead></tr><tbody>";
    foreach ($saled_products as $saled_pro) {
        if ($top_pro['is_package'] === '0' || $top_pro['is_package'] === 0) {
            $link_edit = "../products/updateProduct.php?id=" . $saled_pro['pro_id'] . "&cmsMID=96";
        } else {
            $link_edit = "../packages/updatePackage.php?id=" . $saled_pro['pro_id'] . "&cmsMID=97";
        }
        $link_page = _PREF . $cmsMlang . '/page' . $saled_pro['page_id'] . '/' . $utils->rewriteFilter($saled_pro['pro_name']);
        $top_saled.="<tr>";
        $top_saled.="<td>" . $saled_pro['pro_name'] . "</td>";
        $top_saled.='<td><a href="' . $link_edit . '" title="" data-toggle="tooltip" data-original-title="edit"><i class="fa fa-pencil-square-o"></i></a></td>';
        $top_saled.='<td><a href="' . $link_page . '" target="_blank" title="" data-toggle="tooltip" data-original-title="view"><i class="fa fa-search"></i></a></td>';
        $top_saled.="</tr>";
    }
    $top_saled.="</tbody></table></div>";
    ?>
    <!-- solid sales graph -->


</section><!-- right col -->
<div class="row row-nomargin">
    <div class="col-sm-4  ">
        <div class="">
            <?= $latest_customers ?>
        </div>
    </div>
    <div class="col-sm-4  ">
        <div class="">
            <?= $latest_products ?>
        </div>
    </div>

    <div class="col-sm-4  ">
        <div class="">
            <?= $latest_orders ?>
        </div>
    </div>


</div>

<div class="row row-nomargin">
    <div class="col-sm-4  ">
        <div class="">
            <?= $top_visited ?>
        </div>
    </div>
    
     <div class="col-sm-4  ">
        <div class="">
            <?= $top_saled ?>
        </div>
    </div>



</div>
<?php echo $utils->get_shortcuts(); ?>
<?php
include('../../common/footer.php');
?>       
