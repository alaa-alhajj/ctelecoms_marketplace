<?php
include('../../view/common/top.php');
include '../livezilla/api/v2/api.php';
//include '../../view/controller/CORE/SendMail.php';
$customer = $_REQUEST['customer'];
$title = $_REQUEST['subject'];
$brief = $_REQUEST['text'];
$time = date('U');
$get_cu_name = $fpdo->from('customers')->where('id', $customer)->fetch();
$insert_id = $fpdo->insertInto('lz_tickets')->values(array('`user_id`' => $customer, 'target_group_id' => 'support', '`hash`' => '9CAD0ED5881A', '`channel_type`' => '0', '`iso_language`' => 'EN-GB', '`deleted`' => '0', '`last_update`' => $time, '`wait_begin`' => $time, '`priority`' => '2'))->execute();


$livezillaURL = "http://voitest.com/ctelecom_market/livezilla/";
$apiURL = $livezillaURL . "api/v2/api.php";

// authentication parameters 
$postd["p_user"] = 'admin';
$postd["p_pass"] = md5('admin123');

// function parameter
$postd["p_ticketmessage_create"] = 1;
$postd["p_json_pretty"] = 1;
$postd["p_sendemailresponder"] = 1;
$postd["p_sendemailreply"] = 1;
$postd["p_quotemessageid"] = $insert_id;

class TicketMessage {

    public $Fullname = "";
    public $TicketId = 0;
    public $Phone = "";
    public $Type = 0;
    public $Text = "aaa";
    public $Subject = "";
    public $Email = "";
    public $IP = "";
    public $Company = "";

    public function setFullname($Fullname) {
        $this->Fullname = $Fullname;
    }

    public function setTicketId($TicketId) {
        $this->TicketId = $TicketId;
    }

    public function setEmail($Email) {
        $this->Email = $Email;
    }

    public function setText($Text) {
        $this->Text = $Text;
    }

    public function setSubject($Subject) {
        $this->Subject = $Subject;
    }

    public function setCompany($Company) {
        $this->Company = $Company;
    }

    //public $Customs = array(array("CustomerNumber","11112726"));
    //public $Comments = array(array("john_doe","comment #1"),array("john_doe","comment #2"));
}

$newMessage = new TicketMessage();

$newMessage->setFullname($get_cu_name['name']);
$newMessage->setTicketId($insert_id);
$newMessage->setEmail($get_cu_name['email']);
$newMessage->setSubject($title);
$newMessage->setText($brief);
$newMessage->setCompany($get_cu_name['company']);
$postd["p_data"] = json_encode(array("TicketMessage" => $newMessage));
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiURL);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postd));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);

if ($server_output === false)
    exit(curl_error($ch));

//echo $server_output;
curl_close($ch);
$fpdo->update('lz_tickets')->set(array('wait_begin' => $time))->where('id', $insert_id)->execute();

//page
$static_widget_id = 35;
$page_id = $fpdo->insertInto('cms_pages')->values(array('html' => "##wid_start## ##wid_id_start##$static_widget_id##wid_id_end## ##wid_end##", 'type' => "generated", 'lang' => $pLang, 'hidden' => '1'))->execute();
//add generate page_id to purchase order 
$query = $fpdo->update("lz_tickets")->set(array('page_id' => $page_id))->where('id', $insert_id)->execute();
$title_mail = "New Ticket has been opened";
$message = "Ticket Details:<br>Ticket id:" . $insert_id . "<br> Ticket Subject:" . $title . "<br> Ticket Message:" . $brief;

$get_email_pass=$fpdo->from('mails')->where('id',1)->fetch();
//$mailer = new SendMail(mail_host, $get_email_pass['username'], $get_email_pass['password'], mail_port, mail_auth);
$attach = array($_SERVER['DOCUMENT_ROOT'] . _PREF . "view/ajax/price_query.txt");
$email_support=$fpdo->from('lz_groups')->where('id','support')->fetch();
//$mailer->sendMail("info@voitest.com", $email_support['email'], $title_mail, $message, $attach, 1);

global $utils;
$tags2 = array("{full-name}" => $get_cu_name['name'], '{ticket-id}' => '11', '{body}' => $brief);
$utils->sendMailC("info@voitest.com", $email_support['email'], $subject, "", 1, $tags2,$attach);


echo json_encode(1);
