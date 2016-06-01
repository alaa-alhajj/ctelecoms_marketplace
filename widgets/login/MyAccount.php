<?
@session_start();
$customer_id = $_SESSION['CUSTOMER_ID'];
$get_tickets = $this->fpdo->from('lz_tickets')
                ->select("lz_ticket_messages.subject as subject,lz_ticket_messages.text as text,lz_ticket_editors.status as status,lz_ticket_messages.created as created,lz_tickets.page_id as page_id")
                ->leftJoin("lz_ticket_messages on lz_tickets.id=lz_ticket_messages.ticket_id")
                ->leftJoin("lz_ticket_editors on lz_tickets.id=lz_ticket_editors.ticket_id")
                ->where("lz_tickets.user_id='$customer_id' and lz_ticket_messages.id REGEXP '^-?[0-9]+$'")->limit(3)->fetchAll();
$ticket_table = "<table class='table table-bordered'>"
        . "<thead>"
        . "<th>Title</th><th>status</th><th>Create Date</th>"
        . "</thead><tbody>";
if (count($get_tickets) > 0) {
    foreach ($get_tickets as $tickets) {
        $page_id = $tickets['page_id'];
        $link = _PREF . $_SESSION['pLang'] . "/page$page_id/ticket";
        $onClick = 'document.location = "' . $link . '";';
        $status_id = $tickets['status'];
        $status = "";
        $date = date("Y-m-d H:i:s", $tickets['created']);
        if ($status_id == "" || $status_id == 0 || $status_id == '0') {
            $status = "Open";
        } elseif ($status_id == 1 || $status_id == '1') {
            $status = "In Progress";
        } elseif ($status_id == 2 || $status_id == '2') {
            $status = "Closed";
        } elseif ($status_id == 3 || $status_id == '3') {
            $status = "Deleted";
        }
        $class = "hoverTable";
        $ticket_table.="<tr class='$class' onclick='$onClick'>"
                . "<td>" . $tickets['subject'] . "</td>"
                . "<td>" . $status . "</td>"
                . "<td>" . $date . "</td>"
                . "</tr>";
    }
} else {
    $ticket_table.="<tr><td colspan='3'>No Tickets</td></tr>";
}
$ticket_table.="</tbody></table>";



$orders = $this->fpdo->from("purchase_order")->where(" customer_id='$customer_id'")->limit(3)->fetchAll();
$order_table = "<table class='table table-bordered'>"
        . "<thead>"
        . "<th>Order ID</th><th>Order Date</th><th>Payment Type</th><th>Details</th>"
        . "</thead><tbody>";
if (count($orders) > 0) {
    foreach ($orders as $order) {
        $order_id = $order['id'];
        $page_id = $order['page_id'];
        $order_date = $order['order_date'];
        $payment_type_id = $order['payment_type'];
        $payment_type_info = $this->fpdo->from("payment_types")->where("id='$payment_type_id'")->fetch();
        $payment_type_name = $payment_type_info['name'];
        $link = _PREF . $pLang . "/page$page_id/OrderDetails";
        $order_table.= "
                    <tr>
                        <td>$order_id</td>
                        <td>$order_date</td>
                        <td>$payment_type_name</td>
                        <td><a href='$link' ><i class='fa fa-list'></i></a></td>
                    </tr>
                ";
    }
} else {
    $order_table.="<tr><td colspan='3'>No Orders</td></tr>";
}
$order_table.="</tbody></table>";

$payments_query = $this->fpdo->from('payments')
                ->select("payments.value as value,payment_types.name as payment_type,payments.date as date")
                ->leftJoin("purchase_order on purchase_order.id=payments.po_id")
                ->leftJoin("payment_types on payment_types.id=payments.type")
                ->where("purchase_order.customer_id='$customer'")->limit(3)->fetchAll();
$payment_table = "<table class='table table-bordered'>"
        . "<thead>"
        . "<th>Date</th><th>Payment Type</th><th>Payment value</th>"
        . "</thead><tbody>";
if (count($payments_query) > 0) {
    foreach ($payments_query as $payment_det) {

        $payment_table.= "
                    <tr>
                        <td>" . $payment_det['date'] . "</td>
                        <td>" . $payment_det['payment_type'] . "</td>
                        <td>" . $payment_det['value'] . "</td>
                     
                    </tr>
                ";
    }
} else {
    $payment_table.="<tr><td colspan='3'>No Payments</td></tr>";
}
$payment_table.="</tbody></table>";
?>
<div class="row">
    <div class="col-sm-12">
        <h1>Dashboard</h1>
        <hr>
    </div>
    <div class="col-sm-12">
        <div class="panel panel-ctelecoms panel-scroll-y">
            <div class="panel-heading">Latest Tickets</div>
            <?= $ticket_table ?>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="panel panel-ctelecoms panel-scroll-y">
            <div class="panel-heading">Latest Purchase Orders</div>
            <?= $order_table ?>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="panel panel-ctelecoms panel-scroll-y">
            <div class="panel-heading">Latest Payments</div>
            <?= $payment_table ?>
        </div>
    </div>

</div>



<?


