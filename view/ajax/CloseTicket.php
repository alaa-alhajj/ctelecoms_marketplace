<?php
include('../../view/common/top.php');
include '../livezilla/api/v2/api.php';
@session_start();
$customer_id = $_SESSION['CUSTOMER_ID'];
$ticket_id = $_REQUEST['ticket_id'];
$knowledg = $_REQUEST['knowledg'];
$friend = $_REQUEST['friend'];
$response = $_REQUEST['response'];
$overall = $_REQUEST['overall'];
$comment = $_REQUEST['comment'];
$time = date('U');
$fpdo->insertInto('lz_feedbacks')->values(array('`id`' => $ticket_id, '`created`'=>$time,'`ticket_id`' => $ticket_id, '`user_id`' => $customer_id, '`operator_id`' => 'ec747d8', '`group_id`' => 'support', '`data_id`' => $customer_id))->execute();

$fpdo->insertInto('lz_feedback_criteria')->values(array('`fid`' => $ticket_id, '`cid`' => 'd0', '`value`' => $knowledg))->execute();
$fpdo->insertInto('lz_feedback_criteria')->values(array('`fid`' => $ticket_id, '`cid`' => 'd1', '`value`' => $friend))->execute();
$fpdo->insertInto('lz_feedback_criteria')->values(array('`fid`' => $ticket_id, '`cid`' => 'd2', '`value`' => $response))->execute();
$fpdo->insertInto('lz_feedback_criteria')->values(array('`fid`' => $ticket_id, '`cid`' => 'd3', '`value`' => $overall))->execute();
$fpdo->insertInto('lz_feedback_criteria')->values(array('`fid`' => $ticket_id, '`cid`' => 'd4', '`value`' => 'test'))->execute();
$check_status=$fpdo->from("lz_ticket_editors")->where("ticket_id",$ticket_id)->fetch();
if($check_status !=""){
    $fpdo->update('lz_ticket_editors')->set(array('status' => '2'))->where("ticket_id",$ticket_id)->execute();
}else{
    $fpdo->insertInto('lz_ticket_editors')->values(array('`ticket_id`' => $ticket_id,'`group_id`'=>'support','`status`'=>'2','`time`'=>$time))->execute();
}
echo json_encode(1);
