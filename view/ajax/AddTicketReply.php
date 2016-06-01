<?php
include('../../view/common/top.php');
include '../livezilla/api/v2/api.php';
include '../../view/controller/CORE/SendMail.php';
@session_start();
$customer_id = $_SESSION['CUSTOMER_ID'];
$ticket_id=$_REQUEST['ticket'];
$reply=$_REQUEST['reply'];
$get_cu_name = $fpdo->from('customers')->where('id', $customer_id)->fetch();
$livezillaURL = "http://voitest.com/ctelecom_market/livezilla/";
$apiURL = $livezillaURL . "api/v2/api.php";
$time = date('U');
// authentication parameters 
$postd["p_user"] = 'admin';
$postd["p_pass"] = md5('admin123');

// function parameter
$postd["p_ticketmessage_create"] = 1;
$postd["p_json_pretty"] = 1;

class TicketMessage {
    public $TicketId = 1;
       public function setTicketId($TicketId) {
        $this->TicketId = $TicketId;
    }

    public $Comments = array(array("john_doe","comment #1"));
      public function setComments($Comments) {
        $this->Comments = $Comments;
    }
}

$newMessage = new TicketMessage();
$newMessage->setTicketId($ticket_id);
$newMessage->setComments(array(array("$customer_id","$reply")));
$postd["p_data"] = json_encode(array("TicketMessage" => $newMessage));
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $apiURL);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postd));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);

if ($server_output === false)
    exit(curl_error($ch));
$fpdo->update('lz_tickets')->set(array('wait_begin' => $time))->where('id', $ticket_id)->execute();
$fpdo->update('lz_ticket_comments')->set(array('message_id' => $ticket_id,'user_id'=>$customer_id))->where("ticket_id='$ticket_id' and operator_id='$customer_id'")->execute();


curl_close($ch);
$title_mail = "New Reply for Ticket".$ticket_id;
$message = $reply;
$email_support=$fpdo->from('lz_groups')->where('id','support')->fetch();
$get_email_pass=$fpdo->from('mails')->where('id',1)->fetch();
$mailer = new SendMail(mail_host, $get_email_pass['username'], $get_email_pass['password'], mail_port, mail_auth);
$attach = array($_SERVER['DOCUMENT_ROOT'] . _PREF . "view/ajax/price_query.txt");
$mailer->sendMail("info@voitest.com", $email_support['email'], $title_mail, $message,$attach, 1);

echo json_encode(1);

