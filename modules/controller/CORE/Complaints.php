<?php

class Complaints extends utils{

    protected $fpdo;

    function __construct() {
        global $fpdo;
        $this->fpdo = & $fpdo;
    }
    
    public function getMComplaints_ids($comp_type,$db_table,$db_cms_complaints_departments,$user_id) { //$fpdo using when sent type only
            
            //get department for user_id;
            $cond=' user_id='.$user_id.'';
            $arr=$this->fpdo->from($db_cms_complaints_departments)->where($cond)->fetchAll();
            $dpt_id=0;
            if(count($arr)>0){
                $dpt_id=$arr[0]['id'];
            }else{
                return ;
            }
            
         if($comp_type==="inbox"){             
            $comp_ids=array();
            $unReadComp_ids=array();
            $unReadComp=0;
            $cond=' department_id='.$dpt_id.' and flag = 0 ';
            $result=$this->fpdo->from($db_table)->where($cond)->fetchAll();
            $nums=count($result);
            if($nums<=0){
                return;
            }
            foreach ($result as $row) {
                $comp_id= $row['id']; 
                $is_read= $row['isread'];
                if(!in_array($comp_id, $comp_ids)){
                    $comp_ids[]=$comp_id;
                }

                if($is_read == 0){
                    $unReadComp_ids[]=$comp_id;
                    $unReadComp++;
                }
            }
            $_SESSION['comp_ids']=$comp_ids;
            $_SESSION['unReadComp']=$unReadComp; 

            $ret=array($comp_ids,$unReadComp);
           
            
         }
         else{   //trash type
             
            $TrashComp_ids=array();
            
            $cond=' department_id='.$dpt_id.' and flag = 1 ';
            $result=$this->fpdo->from($db_table)->where($cond)->fetchAll();
            $nums=count($result);
            if($nums<=0){
                return;
            }
            foreach ($result as $row) {
                $comp_id= $row['id']; 
                if(!in_array($comp_id, $TrashComp_ids)){
                          $TrashComp_ids[]=$comp_id;
                }  
            }
            $_SESSION['TrashComp_ids']=$TrashComp_ids;//useing in read mail
            
            $ret=array($TrashComp_ids,0);
            
         }
          return $ret;
    }
    public function showComplaints($type,$db_table,$db_table_replies,$conditions) {
                
            $comps='';
            if($type==='inbox'){
                $comps='';
                foreach ($this->fpdo->from($db_table)->where($conditions)->fetchAll() as $row){
                    $bold_str='';
                    if($row['isread']==0){ //if complaint unRead Complaint
                        $bold_str='style="font-weight:bold;"';
                    }else{
                        $bold_str='';
                    }
                    //get replies counts
                    $count=$this->getRepliesCount($db_table_replies,$row['id']);
                    $count_srt='';
                    if($count>0){
                        $count=$count+1; //add main complaint;
                        $count_srt=" ($count)";
                    }
                    $comps.='<script type="text/javascript">
                                var link=true;
                             </script>
                            <tr id="'.$row['id'].'" onmouseover="this.style.cursor=\'pointer\'" onclick="if (link) window.location =\'read-complaint.php?comp_id='.$row['id'].'&page=inbox.php\'" '.$bold_str.'>
                                <td><input type="checkbox" name="rows[]" value="'.$row['id'].'" onmouseover="link=false;" onmouseout="link=true;"></td>
                                <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                                <td class="mailbox-name">'.$row['full_name'].''.$count_srt.'</td>';
                    $comps.='<td class="mailbox-subject">'.$row['title'].'</td>';

                    if($row['file']==""){
                         $comps.='<td class="mailbox-attachment"></td>';
                    }else{
                        $comps.='<td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>';
                    }

                    $comps.='<td class="mailbox-date">'.  $this->diff_dates(date("Y-m-d h:i:s"), $row['start_date']).'</td>
                        </tr>        
                        '; 
                }
            }
            else{ //trash type
               
                foreach ($this->fpdo->from($db_table)->where($conditions)->fetchAll() as $row){

                   
                    $comps.='<script type="text/javascript">
                                var link=true;
                             </script>
                            <tr id="'.$row['id'].'" onmouseover="this.style.cursor=\'pointer\'" onclick="if (link) window.location =\'read-complaint.php?comp_id='.$row['id'].'&page=trash.php\'">
                                <td><input type="checkbox" name="rows[]" value="'.$row['id'].'" onmouseover="link=false;" onmouseout="link=true;"></td>
                                <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                                <td class="mailbox-name">'.$row['full_name'].'</td>';

                    $comps.='<td class="mailbox-subject">'.$row['title'].'</td>';
                   

                    if($row['file']==""){
                         $comps.='<td class="mailbox-attachment"></td>';
                    }else{
                        $comps.='<td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>';
                    }
                    $comps.='<td class="mailbox-date">'.  $this->diff_dates(date("Y-m-d h:i:s"), $row['start_date']).'</td>
                        </tr>        
                        '; 
                }
            }
            
                return $comps;  
    }
    public function getComplaintInfo($db_table,$db_users,$compId,$prevPage) {
                $comp=array();
                
                $cond=' id = '.$compId.'';
                $result=$this->fpdo->from($db_table)->where($cond)->fetchAll();
                $nums=count($result);
                if($nums<=0){
                    return;
                }
                foreach ($result as $row) {
                    $comp_id=$row['id']; 
                    $full_name=$row['full_name']; 
                    $email=$row['email']; 
                    $department_id=$row['department_id'];                    
                    $title=$row['title']; 
                    $details=$row['details'];
                    $start_date=$row['start_date'];
                    $end_date=$row['end_date']; 
                    $file=$row['file']; 
                    $isRead=$row['isread']; 
                    $status_id 	= $row['status_id'];
                    
                    $name=$full_name."( $email )" ; 
                    
                    if($isRead=='0'){ //if is un read complaint
                        if($prevPage==='inbox.php'){
                        //set this comp is read
                        $vars=array('action'=>'Edit','isRead'=>1,'id'=>$comp_id);
                        $updateArr=array('isRead');
                        $save_ob = new saveform($db_table, $vars, $updateArr,'id', '');
                        
                        //send email to Complaint sender
//                            $to = $email;
//                            $subject = "Recive Complaint";
//                            $txt = "Hello Dir $name, Your Complaint was delivered";
//                            $headers = "From: voila@gmail.com" . "\r\n" ; 
//
//                            mail($to,$subject,$txt,$headers); //send email to senders 
                        }
                    }
                    
                }
                    
                $comp=array('comp_id'=>$comp_id,'full_name'=>$full_name,'email'=>$email,'dpt_id'=>$department_id,'title'=>$title,'name'=>$name,
                            'start_date'=>$start_date,'end_date'=>$end_date,'details'=>$details,'file'=>$file,'status_id'=>$status_id);
              
            return $comp;        
    }
    public function showCompAttachment($file) {
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
    
    public function addReply($values,$db_table,$db_table_replies,$user_id){
        
            $values['admin_id']=$user_id;
            $date=date("Y-m-d h:i:s");
            $values['date']=$date;
            $values['file']='';
            $body=$values['body'];
            $body=  stripcslashes($body);

            $comp_id=$values['comp_id'];
            
            $values['from']='admin';
            $values['action']='Insert';
            
            $save_ob=new saveform($db_table_replies,$values,array('comp_id','from','admin_id','date','body','file'));
           
            //change complaint status
            $comp_id=$values['comp_id'];
            $val2=array('action'=>'Edit','id'=>$comp_id,'status_id'=>3);
            $updateArr=array('status_id');
            $save_ob=new saveform($db_table,$val2,array('status_id'),'id');
            
            $email=$values['email'];
            $enc_email=  md5($email);
            $full_name=$values['full_name'];
            
            $to = $email;
            $subject = "show Reply";
            $txt = "<br/>
                    Hello Dir $full_name,
                    Admin Reply:
                    $body
                    <br />
                    if you want Reply press <a href='http://localhost/cms-6/widgets/complaints/addReply.php?action=SendReply&comp_id=".$comp_id."&enc_email=".$enc_email."'>here</a>.<br />
                    if you want Close Complaint press <a href='http://localhost/cms-6/widgets/complaints/createEvaluation.php?id=".$comp_id."'>here</a>
                   ";
            $headers = "From: voila@gmail.com" . "\r\n" ; 

           // mail($to,$subject,$txt,$headers); //send email to senders 
            //echo $txt;
            
    }

    public function showCompReplies($db_table,$db_table_replies,$db_cms_emps,$comp_id) {
                
                $reply='<div class="replies col-sm-12">';      
                $status=$this->fpdo->from($db_table_replies)->where('comp_id='.$comp_id.' order by date ')->fetchAll();
                if($status){
                    foreach ($this->fpdo->from($db_table_replies)->where('comp_id='.$comp_id.' order by date ')->fetchAll() as $row){
                        $reply.='<div class="reply col-sm-12">';
                        //get reply information
                         if($row['from']=='admin'){
                             $temp_record2=$this->fpdo->from($db_cms_emps)->where('id='.$row['admin_id'])->limit('0,1')->fetch();
                             $name=$temp_record2['full_name']." (".$temp_record2['email'].")";
                         }else{
                             $temp_record2=$this->fpdo->from($db_table)->where('id='.$comp_id)->limit('0,1')->fetch();
                             $name=$temp_record2['full_name']." (".$temp_record2['email'].")";
                         }
                         $reply.='<div class="reply-header col-sm-12">';
                         $reply.='<div class="from-cls col-sm-6"><b>'.$name.'</b></div>';
                         $reply.='<div class="date-cls col-sm-6">'.$row['date'].'</div>';
                         $reply.='</div>';
                         $reply.='<div class="body-cls col-sm-12">'.$row['body'].'</div>';
                         $reply.='</div>';
                    }
                }
                    $reply.="</div>";
                return $reply;
    }
    
    function getRepliesCount($db_table_replies,$comp_id){
         $status=$this->fpdo->from($db_table_replies)->where('comp_id='.$comp_id.' order by date ')->fetchAll();
         return count($status);
    }
    public function deleteComp($comp_id,$db_table,$values,$dcols) {
        
        $values['action']='Edit';
        $cond=' id = '.$comp_id.' limit 0,1';
        $result=$this->fpdo->from($db_table)->where($cond)->fetchAll();
        $nums=count($result);
        if($nums<=0){
            return;
        }
        $row=$result[0];
        $flag= $row['flag']; 
        $id= $row['id']; 
        $values['flag']=1; 
       if($flag > 0){
            $values['flag']=2;
       }
        $values['id']=$id;
       $save_ob=new saveform($db_table,$values,$dcols,'id');
        
    }
    public function createReport($db_table,$db_table_evaluation,$cond){
                    $counter=0;  // count of complaints
                    $complaint_days=array();
                    $report='';
                    $report.='<div class="col-sm-12 complaints-div">';
                    foreach ($this->fpdo->from($db_table)->where($cond.' order by end_date desc')->fetchAll() as $row){
                         $counter++;
                        
                         $start = $row['start_date'];
                         $end =  $row['end_date'];
                         $end_date="";
                         if(strtotime($end)){
                               $diff = abs(strtotime($end) - strtotime($start));
                               $complaint_days[]=floor(($diff)/ (60*60*24));
                               $end_date=$this->get_date_format($row['end_date']);
                         }else{
                                $end_date="Complaint doesn't stopped.";
                         }
                        //for print items
                        $report.='<a href="read-complaint.php?comp_id='.$row['id'].'" style="decoration:none; color:black;"><div class="complaint col-sm-12">';
                        //get msg sender
                        $comp_creater=$row['full_name'];
                        
                        $report.='<div class="msg-header col-sm-12">';
                        $report.='<div class="from-cls col-sm-3"> Sender: '.$comp_creater.'</div>';
                        $report.='<div class="title-cls col-sm-5"> Title: '.$row['title'].'</div>';
                        $report.='<div class="date-cls col-sm-4" style="text-align:left; padding-left:40px;">Start: '.$this->get_date_format($row['start_date']).'<br/>End: '.$end_date.'</div>';
                        $report.='</div>';
                        
                        //get Evaluation values
                        $cond2=" comp_id =".$row['id'];
                        $res=$this->fpdo->from($db_table_evaluation)->where($cond2)->fetchAll();
                        if(count($res)>0){
                            $evaluation_values=$res[0];
                           
                            $report.='<div class="col-sm-12 evaluatio-values">
                                      <div class="col-sm-12"><div class="col-sm-3" >Satisfaction Value:</div><div class="col-sm-9" >'.$evaluation_values['satisfaction_value'].'</div></div>
                                      <div class="col-sm-12"><div class="col-sm-3" >Response Speed:</div><div class="col-sm-9" >'.$evaluation_values['response_speed'].'</div></div>
                                      <div class="col-sm-12"><div class="col-sm-3" >Employee Cooperation:</div><div class="col-sm-9" >'.$evaluation_values['emp_cooperation'].'</div></div>
                                     ';
                            if($evaluation_values['details']!=''){
                                $report.='<div class="col-sm-12">More Details:</div>
                                        <div class="col-sm-12">'.$evaluation_values['details'].'</div>';
                            }
                             $report.='</div>';
                             
                        }else{
                            $report.='<div class=" col-sm-12 evaluatio-values"> Complaint isn\'t closed </div>';
                        }
                       
                        $report.='</div></a>';
                    }
                    $report.="</div>";
                    
                    //get number complaints an averages
                    $sum=0;
                    foreach ($complaint_days as $value) {
                        $sum=$sum+$value;
                    }
                    
                    if(count($complaint_days)>0){
                      $average=floor($sum / count($complaint_days));
                    }else{
                      $average=0;  
                    }
                    $report2= '<div class="col-sm-12 evaluiation-div">
                             <div class="col-sm-6">Complaints Numbers: '.$counter.'</div>
                             <div class="col-sm-6">Average of time: '.$average.' Days</div>    
                          </div>';
                    $report2.= '<hr/>';
                    
                    return $report2.$report;
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
    public function getNextCompId($comp_ids,$comp_id){
        $nextComp=0;
        if(count($comp_ids)>0){
            $key=array_search ($comp_id, $comp_ids);
            $next=$key+1;
            if (array_key_exists($next,$comp_ids)){
                $next." <br />".$comp_ids[$next];
                $nextComp=$comp_ids[$next];
            }else{
                $nextComp=$comp_ids[$key];
            }   
        }else{
            $nextComp=$comp_id;
        }
        return $nextComp;
    }
    public function getPrevCompId($comp_ids,$comp_id){
        $prevComp=0;
        if(count($comp_ids)>0){
            $key=array_search ($comp_id, $comp_ids);
            $prev=$key-1;
            if (array_key_exists($prev,$comp_ids)){
                  $prev." <br />".$comp_ids[$prev];
                  $prevComp=$comp_ids[$prev];
              }else{
                  $prevComp=$comp_ids[$key];
              }
        }else{
            $prevComp=$comp_id;  
           }
        return $prevComp;
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
        if($nextPage>$tp){
           $nextPage=$tp; 
        }

        $prevPage=$pn-1;
        if($prevPage<0){
           $prevPage=0; 
        }

       $end=$start+$LPP; 
        if($end>$total){
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
    public function Create_pagination($comp_ids,$LPP,$pn,$start,$limit) {
        $total=sizeof($comp_ids);
        $vars=$this->get_pagination($total,$LPP,$pn,$start,$limit); //get pagination variable
        
        $pg_format=$vars['start']."-".$vars['end']."/".$vars['total'];
        $prevPage=$_SERVER['PHP_SELF']."?pn=".$vars['prevPage']."&title=".$values['title']."";
        $nextPage=$_SERVER['PHP_SELF']."?pn=".$vars['nextPage']."&title=".$values['title']."";
        
        $ret=array('pg_format'=>$pg_format,'prevPage'=>$prevPage,'nextPage'=>$nextPage);
        return $ret;
     } 
}
