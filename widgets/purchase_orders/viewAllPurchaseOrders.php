<?php
    $customer_id=$_SESSION['CUSTOMER_ID'];
   
?>

<div class="purchase-orders-container">
    <div class="col-sm-12 margbtm20">
        <form class="form-inline" role="form" method="post" action="">
            <div class="form-group">
              <label for="from">From:</label>
              <input type="text" class="form-control" name="from" id="from" value="<?=$_REQUEST['from']?>">
            </div>
            <div class="form-group">
              <label for="to">To:</label>
              <input type="text" class="form-control" name="to" id="to" value="<?=$_REQUEST['to']?>">
            </div>
            <button type="submit" class="btn btn-default">Filter</button>
        </form>
    </div>
    
    <table class="table table-bordered table-hover table-striped table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Order Date</th>
                <th>Payment Type</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            global $utils;
            global $pLang;
             $cond="customer_id='$customer_id'";
            if(isset($_REQUEST,$_REQUEST['from']) && $_REQUEST['from']!=''){
                 $from=$_REQUEST['from'];
                 $cond.=" and order_date >= '$from' ";
            }
            if(isset($_REQUEST,$_REQUEST['to']) && $_REQUEST['to']!=''){
                 $to=$_REQUEST['to'];
                 $cond.=" and order_date <= '$to' ";
            }

            $orders = $this->fpdo->from("purchase_order")->where($cond)->fetchAll();
            foreach ($orders as $order) {
                $order_id=$order['id'];
                $page_id=$order['page_id'];
                $order_date=$order['order_date'];
                $payment_type_id=$order['payment_type'];
                $payment_type_info=$this->fpdo->from("payment_types")->where("id='$payment_type_id'")->fetch();
                $payment_type_name=$payment_type_info['name'];
                $link=_PREF.$pLang."/page$page_id/OrderDetails";
                echo "
                    <tr>
                        <td>$order_id</td>
                        <td>$order_date</td>
                        <td>$payment_type_name</td>
                        <td><a href='$link'><i class='fa fa-list'></i> Details</a></td>
                    </tr>
                ";
            }
            ?>

        </tbody>
    </table>

</div>