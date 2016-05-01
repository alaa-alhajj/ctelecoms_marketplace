<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MailBox extends utils{

    protected $fpdo;

    function __construct() {
        global $fpdo;
        $this->fpdo = & $fpdo;
    }
   
    
    
    public function getMessages_ids($msg_type,$db_table_directions,$db_table_trash,$user_id) { //$fpdo using when sent type only
        $msg_ids=array();
        
        if($msg_type!="trash"){
            $where_col='';
            if($msg_type==="inbox"){
                $where_col="to_id";
             }else{
                $where_col="from_id";
             }
            //get unread msg ids 
            
            $cond=' '.$where_col.'='.$user_id.'   group by msg_id';
            $result=$this->fpdo->from($db_table_directions)->where($cond)->fetchAll();
            if(count($result)<=0){
                return '';
            }
            foreach ($result as $row) {
                    $msg_id=$row['msg_id'];
                    $trashStatus=$this->getTrashStatus($db_table_trash,$msg_id,$user_id);
                     if($trashStatus==0){
                         $msg_ids[]=$msg_id;
                     } 
            }
        }else{ //trash messages
            $cond='user_id='.$user_id.'';
            $result=$this->fpdo->from($db_table_trash)->where($cond)->fetchAll();
                if(count($result)<=0){
                    return '';
                }
                foreach ($result as $row) {
                        $msg_id=$row['msg_id'];
                        $msg_ids[]=$msg_id;
                }
            }
            
           $ret=  implode(',', $msg_ids); 
          return $ret;
    
    }
    
    public function checkTotalUnReadMsg($db_table_directions,$db_table_trash,$user_id){
        
        $cond=' to_id='.$user_id.' and msg_type <> "reply" and isRead="no"';
        $counter1=0;
        
        $result=$this->fpdo->from($db_table_directions)->where($cond)->fetchAll();
        foreach ($result as $row) {
            $msg_id=$row['msg_id'];
            $status=  $this->getTrashStatus($db_table_trash, $msg_id, $user_id); //check if msg isn't deleted
            if($status==0){
                $counter1++;
            }
        }
        $msgCount=$counter1;
        
        $cond2=' to_id='.$user_id.' and msg_type = "reply" and isRead="no" group by msg_id';
        $result2=$this->fpdo->from($db_table_directions)->where($cond2)->fetchAll();
        $rplyCount=count($result2);
        
        $total=$msgCount+$rplyCount;
        
        return $total;
    }
    public function checkReplyies($db_table_directions,$msg_id){  
        $cond=' msg_id='.$msg_id.' and msg_type = "reply"';
        $result=$this->fpdo->from($db_table_directions)->where($cond)->fetchAll();
        $num=count($result);
        return $num;
    }
    public function checkReadMsg($db_table_directions,$msg_id,$user_id){
            $Status='yes';
            $cond=' msg_id='.$msg_id.' and to_id ="'.$user_id.'" and isRead="no" ';
            $result=$this->fpdo->from($db_table_directions)->where($cond)->fetchAll();
			$row = $result[0];
            if($row['isread']=='no'){
                $Status='no';

            }  
            return $Status;      
    }
    public function getTrashStatus($db_table_trash,$msg_id,$user_id){
            $trashStatus=false;
            $cond=' msg_id='.$msg_id.' and user_id ='.$user_id;
            $result=$this->fpdo->from($db_table_trash)->where($cond)->fetchAll();
            if(count($result)>0){
                $trashStatus=true;
            } else{
                $trashStatus=false;
            }
            return $trashStatus;
    }
    public function getTrashMsg($db_table_trash,$user_id){
            
            $ids=array();            
            $cond=' user_id ='.$user_id.' group by msg_id';
            $result=$this->fpdo->from($db_table_trash)->where($cond)->fetchAll();
            if(count($result)>0){
                foreach ($result as $row) {
                     $ids[]=$row['msg_id'];      
                }
            }
            $ret=  implode(',', $ids);
            return $ret;
    }
    
    public function getMsgList($type,$db_table,$db_table_directions,$db_table_trash,$db_cms_users,$user_id,$filter,$limit) {
                
             $msg_ids=  $this->getMessages_ids($type, $db_table_directions, $db_table_trash, $user_id);
                    
          
             
            $where_col='';
            $user_index='';
            if($type==="inbox"){
                $where_col="to_id";
                $user_index="from_id";
                $prevPage='inbox.php';
             }elseif($type=='sent'){
                $where_col="from_id";
                $user_index="to_id";
                $prevPage='sent.php';
             }elseif($type=='trash'){
                  $prevPage='trash.php';      
                  $msg_ids = $this->getTrashMsg($db_table_trash,$user_id);
             }
             
             
            if($msg_ids!=''){
                $conditions = " $filter id in (".$msg_ids.") order by id desc $limit ";
            }else{
                 $conditions = " $filter 0 order by id desc $limit ";
            }
            
            $msgs='';    
                $infoResult=$this->fpdo->from($db_table)->where($conditions)->fetchAll();
                if($infoResult){
                    foreach ($infoResult as $row){
                        $msg_id=$row['id'];
                        $userNames='';
                        $usersIds=array();
                        if($type!='trash'){
                            $record=$this->fpdo->from($db_table_directions)->where('msg_id='.$msg_id.' and '.$where_col.'='.$user_id.'')->fetchAll();
                            foreach ($record as $TempRec) {
                                if($type==="inbox"){
                                        $user=$TempRec[$where_col];
                                }else{
                                       $user=$TempRec[$user_index];
                                }
                                
                                $usersIds[]=$user;
                            }
                           
                        }else{
                           $user=$user_id;
                           $usersIds[]=$user;
                        }
                        
                        $str_ids=  implode(',',$usersIds);
                         $user_info=$this->fpdo->from($db_cms_users)->where('id in ('.$str_ids.')')->fetchAll();
                         
                         $users_name=array();
                         foreach ($user_info as $info) {
                            $users_name[]=$info['full_name'];  
                         }
                         $Names=  implode(',',$users_name);
                         
                        // GET Replies numbers
                        $repliesCount=  $this->checkReplyies($db_table_directions, $msg_id);
                        $repliesStyle='';
                      
                        if($repliesCount>0){
                            $repliesStyle=" ($repliesCount) ";
                        }
                        

                        $isreadStyle="";
                        if($type=='inbox' && $this->checkReadMsg($db_table_directions,$msg_id,$user_id)=='no'){
							
                            $isreadStyle='style="font-weight:bold;"';
                        }
                        
                        $msgs.='<script type="text/javascript">
                                var link=true;
                                </script>
                                <tr id="'.$msg_id.'" onmouseover="this.style.cursor=\'pointer\'" onclick="if (link) window.location =\'read-mail.php?msg_id='.$row['id'].'&page='.$prevPage.'\'" '.$isreadStyle.'>
                                <td><input type="checkbox" name="rows[]" value="'.$msg_id.'" onmouseover="link=false;" onmouseout="link=true;"></td>
                                <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                                <td class="mailbox-name">'.$Names.' '.$repliesStyle.' </td>'; 
                        
                        
                        
                        $msgs.='<td class="mailbox-subject">'.$row['title'].'</td>';

                        if($row['file']==""){
                             $msgs.='<td class="mailbox-attachment"></td>';
                        }else{
                            $msgs.='<td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>';
                        }

                        $msgs.='<td class="mailbox-date">'.  $this->diff_dates(date("Y-m-d h:i:s"), $row['date']).'</td>
                            </tr>       
                            '; 
                    }
                }

                        
                return $msgs;  
    } 
    public function showMsgAttachment($file) {
                $attachs = explode(",", $file);
                $attas='';
               
                foreach ($attachs as $a) {
                    $ext = explode(".", $a);
                    $iconTag="";
                        
                    switch (end($ext)) { 
                        case 'pdf': 
                            $iconTag ='<span class="mailbox-attachment-icon"><i class="fa fa-file-pdf-o"></i></span>';
                            break;
                        case 'doc':
                        case 'docx':    
                            $iconTag ='<span class="mailbox-attachment-icon"><i class="fa fa-file-word-o"></i></span>';
                            break;
                        case 'png':
                        case 'gpj': 
                            $iconTag ='<span class="mailbox-attachment-icon has-img"><img src="'.$a.'" alt="Attachment"></span>';
                            break;   
                        default:
                            $iconTag ='<span class="mailbox-attachment-icon"><i class="fa"></i></span>';
                            break;
                    }
                    if($a==""){
                        break;
                    }
                    $attas.='<li>';
                    $attas.=$iconTag;
                    $attas.='<div class="mailbox-attachment-info">
                              <a href="'.$a.'" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i>'.$a.'</a>
                              <span class="mailbox-attachment-size">
                                1,245 KB
                                <a href="'.$a.'" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                              </span>
                            </div>
                          </li>';
                } //end foreach
            return $attas;            
    }    
    public function addReply($user_id,$from_IDs,$values,$db_table_replies,$db_table_directions,$rSavecols) {
        
        $values['user_id']=$user_id;
        $date=date("Y-m-d h:i:s");
        $values['date']=$date;
        $values['file']='';
        $body=$values['body'];
        $body=  stripcslashes($body);
        $msg_id=$values['msg_id'];
        
        $values['action']='Insert'; 
        //print_r($values);
        $save_ob=new saveform($db_table_replies,$values,$rSavecols);

        foreach ($from_IDs as $from_id) {
            $values['from_id']=$user_id;   
            $values['to_id']=$from_id;
            $values['msg_type']='reply';
            $values['isRead']='no';
            $save_ob=new saveform($db_table_directions,$values,array('msg_id','from_id','to_id','msg_type','isRead'));
        }
       
        
    }
    
    public function showMsgReplies($db_table_replies,$db_cms_users,$msg_id) {
                       
                $reply='<div class="replies col-sm-12">'; 
                foreach ($this->fpdo->from($db_table_replies)->where('msg_id='.$msg_id.' order by date asc')->fetchAll() as $row){
                   $reply.='<div class="reply col-sm-12">';
                   //get reply information
                    $temp_record2=$this->fpdo->from($db_cms_users)->where('id='.$row['user_id'])->limit('0,1')->fetch();
                    $reply.='<div class="reply-header col-sm-12">';
                    $reply.='<div class="from-cls col-sm-6">'.$temp_record2['full_name'].'</div>';
                    $reply.='<div class="date-cls col-sm-6">'.$this->get_date_format($row['date']).'</div>';
                    $reply.='</div>';
                    $reply.='<div class="body-cls">'.$row['body'].'</div>';
                    $reply.='</div>';
                }
                $reply.="</div>";
                
                return $reply;
    }
    public function getMsgInfo($db_table,$db_table_directions,$db_cms_users,$msgId,$user_id,$prevPage) {
        $mail=array();
        
        $cond='  id = '.$msgId.'';
        $result=$this->fpdo->from($db_table)->where($cond)->fetchAll();
        $nums=count($result);
        if($nums<=0){
            return $mail;
        }
        foreach ($result as $row) {
                    $msg_id= $row['id'];
                    $title=$row['title']; 
                    $date=$row['date']; 
                    $body=$row['body']; 
                    $file=$row['file']; 
                    
                    $cond2="";
                    if($prevPage=='inbox.php'){
                        $cond2=" msg_id = $msg_id and to_id = $user_id";
                    }else{  //send page or trash page
                        $cond2=" msg_id = $msg_id and from_id = $user_id";       
                    }
                    
                    $res=$this->fpdo->from($db_table_directions)->where($cond2)->fetchAll();
                    $n2=count($res);
                    if($n2<=0){
                        return $mail;
                    }else{
                        $userNames=array();
                        
                        foreach ($res as $user_name) {
                            $row=$user_name;
                            if($prevPage=='inbox.php'){
                                 $U_id=$row['from_id'];
                            }else{
                                 $U_id=$row['to_id'];
                            }
                            
                            $cond3='id = '.$U_id.'';
                            $res2=$this->fpdo->from($db_cms_users)->where($cond3)->fetchAll();
                            $n3=count($res2);
                            if($n3<=0){
                                return $mail;
                            }
                            $user_row=$res2[0];
                            $full_name= $user_row['full_name'];
                            $id= $user_row['id'];
                            
                            if(!array_key_exists($id,$userNames)){
                                $userNames[$id]=$full_name;
                            }
                            
                        } 
                    }

                    //$email= $user_row['email'];
                    $name=  implode(",", $userNames);
                    
                    if($prevPage=='inbox.php'){
                        //set this msg is read
                        $cond4=' msg_id ='.$msg_id.' and to_id='.$user_id.'';
                        $res3=$this->fpdo->from($db_table_directions)->where($cond4)->fetchAll();
                        $n4=count($res3);
                        if( $n4 > 0){
                            $fromIDs=array();
                            foreach ($res3 as $dir_info) {
                                //$dir_info=$dir;
                                $id=$dir_info['id'];
                                
                                if(!in_array($dir_info['from_id'],$fromIDs)){
                                    $fromIDs[]=$dir_info['from_id'];
                                }
                                
                                $vars=array('action'=>'Edit','isRead'=>'yes','id'=>$id);
                                $updateArr=array('isRead');

                                $save_ob = new saveform($db_table_directions, $vars, $updateArr,'id', '');
                            }
                            
                        }
                    }
                    $mail=array('msg_id'=>$msg_id,'from_id'=>$fromIDs,'title'=>$title,'name'=>$name,'date'=>$date,'body'=>$body,'file'=>$file);  
        }
        return $mail;        
    }
    public function deleteMsg($msg_id,$user_id,$db_table_trash) {
            $add=array();           
            $add['msg_id']=$msg_id;
            $add['user_id']=$user_id;            
            $add['action']='Insert';

            $save_ob=new saveform($db_table_trash,$add,array('msg_id','user_id')); 

                      

    }
    public function createMsg($db_table,$db_table_directions,$db_cms_users,$values,$Savecols,$dSavecols,$from_id,$sendTo) {
        
                $values['date']=date("Y-m-d h:i:s");

                $save_ob = new saveform($db_table, $values, $Savecols);
                $msg_id=$save_ob->getInsertId();

                foreach ($sendTo as $toId) {
                     //save infromation in direction table
                    $values['from_id']=$from_id;
                    $values['msg_id']=$msg_id;
                    $values['to_id']=$toId;
                    $values['isRead']='no';
                    //print_r($values) ." <br />";
                    
                    $save_ob = new saveform($db_table_directions, $values, $dSavecols);
                }
                        
    }
    public function createForwardMsg($db_table,$db_table_directions,$db_table_replies,$db_cms_users,$msg_id,$user_id){
                $cond='id = '.$msg_id.'';
                $temp=$this->fpdo->from($db_table)->where($cond)->fetchAll();
                $result=$temp[0];
                $forward_text='';
                if(count($result)>0){
                    $msg_id=$result['id']; 
                    $title=$result['title']; 
                    $date=$result['date'];
                    $body=$result['body']; 
                    $file=$result['file']; 



                    $prev_Page=$values['page'];
                    $cond2='';
                    if($prev_Page==true){
                        $cond2 ='  msg_id = '.$msg_id.' and to_id ='.$user_id;
                    }else{
                        $cond2 ='  msg_id = '.$msg_id.' and from_id ='.$user_id;
                    }


                    $temp2=$this->fpdo->from($db_table_directions)->where($cond2)->fetchAll();
                    $result2=$temp2[0];
                    $from_id=$result2['from_id'];


                    $cond3='id = '.$from_id.'';
                    $temp3=$this->fpdo->from($db_cms_users)->where($cond3)->fetchAll();
                    $result3=$temp3[0];
                    $email=$result3['email'];
                    $full_name=$result3['full_name'];


                    $forward_text=" Message: $title <br />";
                    $forward_text.="From: $full_name <br/>";
                    $forward_text.="Date: $date <br/>";
                    $forward_text.="Body:<br/> $body <br/>";
                    $forward_text.="Attachments: <br/> $file <br/>";
                    $forward_text.=$this->showMsgReplies($db_table_replies,$db_cms_users,$msg_id);         
         }
         return $forward_text;
    }
    public function get_pagination($total,$LPP,$pn,$start,$limit) {

         //total = records numbers
         //tp number of pages 
         //pn is num this page
        
        if($total > 0){
             $tp=  ceil($total / $LPP)-1;
        }else{
              $tp=0;
        }

        $nextPage=$pn+1;
        if($nextPage > $tp){
           $nextPage=$tp; 
        }

        $prevPage=$pn-1;
        if($prevPage < 0){
           $prevPage=0; 
        }

       $end=$start+$LPP; 
        if($end > $total){
           $end=$total; 
        }

        $pagination_vars=array();
        $pagination_vars['total']=$total;
        $pagination_vars['LPP']=$LPP;
        $pagination_vars['pn']=$pn;
        $pagination_vars['start']=$start;
        $pagination_vars['end']=$end;
        $pagination_vars['limit']=$limit;
        $pagination_vars['nextPage']=$nextPage;
        $pagination_vars['prevPage']=$prevPage;
        return $pagination_vars;
    } 
    public function Create_pagination($msg_ids,$LPP,$pn,$start,$limit) {
        
        $total=sizeof($msg_ids);
        $vars=$this->get_pagination($total,$LPP,$pn,$start,$limit); //get pagination variable
        
        $pg_format=$vars['start']."-".$vars['end']."/".$vars['total'];
        $prevPage=$_SERVER['PHP_SELF']."?pn=".$vars['prevPage']."&title=".$values['title']."";
        $nextPage=$_SERVER['PHP_SELF']."?pn=".$vars['nextPage']."&title=".$values['title']."";
        
        $ret=array('pg_format'=>$pg_format,'prevPage'=>$prevPage,'nextPage'=>$nextPage);
        return $ret;
     }        
    public function diff_dates($date2,$date1){


            $diff = abs(strtotime($date2) - strtotime($date1));

            //$years = floor($diff / (365*60*60*24));
            //$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
            //$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

            $days = floor(($diff)/ (60*60*24));
            $hours = floor(($diff - $days * 60*60*24)/ (60*60));
            $minutes = floor(($diff  - $days * 60*60*24 - $hours *60*60)/ (60));

             //printf("%d days, %d hours, %d minutes\n", $days, $hours, $minutes);
             $res=array();
             if($days!=0){
                 $res['days']=$days;
             }
             if($hours!=0){
                $res['hours']=$hours;
             }
             if($minutes!=0){
                 $res['minutes']=$minutes;
             }

             $result="";
             foreach ($res as $key => $value) {
                 if($key=='minutes'){
                       $result.=$value." ".$key." ";
                 }else{
                       $result.=$value." ".$key.", ";
                 }

             }
             if($result!=""){
                 $result.=" ago";
             }

     return $result;
    }
    public function get_date_format($date){
         $d = strtotime($date);
         $new_date = date('d M. Y h:i a', $d); 
         return $new_date;
    }
    public function getNextMsgId($msg_ids,$msg_id){
        $nextMsg=0;
        $key=array_search ($msg_id, $msg_ids);
        $next=$key+1;
        if (array_key_exists($next,$msg_ids)){
            $next." <br />".$msg_ids[$next];
            $nextMsg=$msg_ids[$next];
        }else{
            $nextMsg=$msg_ids[$key];
        }   
        return $nextMsg;
    }
    public function getPrevMsgId($msg_ids,$msg_id){
          $prevMsg=0;
          $key=array_search ($msg_id, $msg_ids);
          $prev=$key-1;
          if (array_key_exists($prev,$msg_ids)){
                $prev." <br />".$msg_ids[$prev];
                $prevMsg=$msg_ids[$prev];
            }else{
                $prevMsg=$msg_ids[$key];
            }
        return $prevMsg;
    }
    
}
