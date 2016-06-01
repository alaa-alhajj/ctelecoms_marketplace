<?php

@session_start();
$customer_id = $_SESSION['CUSTOMER_ID'];
$page_id = $_REQUEST['id'];
$get_ticketid = $this->fpdo->from('lz_tickets')
                ->select("lz_tickets.id as ti_id,lz_ticket_messages.created as created,lz_ticket_messages.text as text,lz_ticket_messages.subject as subject,lz_tickets.user_id as user_id")
                ->leftJoin("lz_ticket_messages on lz_tickets.id=lz_ticket_messages.ticket_id")
                ->where("lz_tickets.page_id='$page_id' and lz_tickets.user_id='$customer_id'")->fetch();
$date = date("Y-m-d H:i:s", $get_ticketid['created']);
echo '<div class="panel panel-default">
          <div class="panel-heading ppHead">
         <div class="pull-left">  <h3 class="panel-title"><div >' . SupportTicket . '&nbsp;' . $get_ticketid['ti_id'] . ':&nbsp;<b>' . $get_ticketid['subject'] . '</b></h3></div><div class="pull-right"><div class="pull-right">' . $date . '</div></div>
           </div>
           <div class="panel-body"><br><div class="row">
    ' . $get_ticketid['text'] . "</div><div class='row'></div>
  </div>
</div>";


$get_reply=$this->fpdo->from("lz_ticket_comments")->where("ticket_id",$get_ticketid['id'])->orderBy("created asc")->fetchAll();
$replies="";
$i=0;
  $get_Cu_name=$this->fpdo->from("customers")->where("id",$get_ticketid['user_id'])->fetch();
foreach($get_reply as $replys){
    if($replys['user_id']==0 || $replys['user_id']=='0'){
        $from="Support";
        $to=$get_Cu_name['name'];
    }else{
      
        $from=$get_Cu_name['name'];
        $to="Support";
    }
    $dateR = date("Y-m-d H:i:s", $replys['created']);
    $replies.=' <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">'
            . '<div class="panel panel-default">
        <div class="panel-heading '.$class.' ppHead" role="tab" id="heading'.$i.'">
          <h4 class="panel-title">
            <a  data-toggle="collapse" data-parent="#accordion" href="#collapse'.$i.'" aria-expanded="true" aria-controls="collapse'.$i.'">
            <div class="pull-left">
             '.from.'&nbsp;<b>'.$from.'</b>&nbsp;'.to.'&nbsp;<b>'.$to.'</b>
                 </div>
                 <div class="pull-right">'.$dateR.'</div>
            </a>
          </h4>
        </div>
        <div id="collapse'.$i.'" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading'.$i.'">
          <div class="panel-body ppBody">
         '.$replys['comment'].'   
          </div>
        </div>
      </div>
      </div>';
            $i++;
}
$check_status=$this->fpdo->from("lz_ticket_editors")->where("ticket_id",$get_ticketid['ti_id'])->fetch();
?>
<div class="row">
    <input type="hidden" id='ticket_id' value='<?=$get_ticketid['ti_id']?>'/>
    <div class='col-sm-12 nopadding'>
        <a href='' class="btn btn-danger" data-toggle="modal" data-target="#addReplyModal"><?=AddReplay?> &nbsp; <i class="fa fa-plus-circle"></i></a>
       <? if($check_status['status']!='2'){?>
            <a href='' class="btn btn-danger" data-toggle="modal" data-target="#CloseTicket"><?=Close?> &nbsp; <i class="fa fa-stop"></i></a>
       <?}?>
    </div>
        
</div>
<hr>
<?=$replies?>
<? include '../../widgets/modal/AddReply.php'; ?>
<? include '../../widgets/modal/CloseTicket.php'; ?>