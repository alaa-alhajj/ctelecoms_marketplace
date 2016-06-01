<?php  
$livezillaURL = "http://voitest.com/ctelecom_market/livezilla/"; 
$apiURL = $livezillaURL . "api/v2/api.php"; 

// authentication parameters 
$postd["p_user"]='admin'; 
$postd["p_pass"]=md5('admin123');

// function parameter
$postd["p_ticket_create"]=1;
$postd["p_json_pretty"]=1;

class Ticket
{
	public $Group = "support";
	public $CreationType = 0;
	public $Language = "en";
	public $Messages = array();
}

$newTicket = new Ticket();
$postd["p_data"]= json_encode(array("Ticket"=>$newTicket));
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$apiURL);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($postd));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);

if($server_output === false)
	exit(curl_error($ch));

// Please don't miss to add a message to your ticket. Tickets with no messages will not be displayed in the operator client software.

echo $server_output;
curl_close($ch);


?>
