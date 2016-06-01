<?php
@session_start();
$customer_id = $_SESSION['CUSTOMER_ID'];
$get_tickets = $this->fpdo->from('lz_tickets')
                ->select("lz_ticket_messages.subject as subject,lz_ticket_messages.text as text,lz_ticket_editors.status as status,lz_ticket_messages.created as created,lz_tickets.page_id as page_id")
                ->leftJoin("lz_ticket_messages on lz_tickets.id=lz_ticket_messages.ticket_id")
                ->leftJoin("lz_ticket_editors on lz_tickets.id=lz_ticket_editors.ticket_id")
                ->where("lz_tickets.user_id='$customer_id' and lz_ticket_messages.id REGEXP '^-?[0-9]+$'")->fetchAll();
$allTickets = "<table class='table table-bordered'>"
        . "<thead>"
        . "<th>Title</th><th>status</th><th>Create Date</th>"
        . "</thead><tbody>";
if(count($get_tickets)>0){
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
    $allTickets.="<tr class='$class' onclick='$onClick'>"
            . "<td>" . $tickets['subject'] . "</td>"
            . "<td>" . $status . "</td>"
            . "<td>" . $date . "</td>"
            . "</tr>";
}
}else{
   $allTickets.="<tr><td colspan='3'>No Tickets</td></tr>"; 
}
$allTickets.="</tbody></table>";
//echo $allTickets;
?>
<div class="row">

    <div class="col-sm-12">
        <h1>All Tickets</h1>
        <a href='' class="btn btn-danger " data-toggle="modal" data-target="#addTicketModal">Add new ticket &nbsp; <i class="fa fa-plus-circle"></i></a>
    </div>
</div>
<hr>
<div class="row">

    <div class="col-sm-12">
        <?= $allTickets ?>
    </div>
</div>

<? include '../../widgets/modal/AddTicket.php'; ?>