<?php


class MailList extends utils{
    protected $fpdo;

    function __construct() {
        global $fpdo;
        $this->fpdo = & $fpdo;
    }
    
    public function createMSG($args) {
        
        $args['create_date']=date("Y-m-d G:i:s");
        $args['grp_ids']=  implode(',', $args['grp_ids']);
        $mSavecols=array('subject','body','create_date','grp_ids');
        $save_ob = new saveform('mailing_msg', $args, $mSavecols);  //save msg
        $args['msg_id']=$save_ob->getInsertId();
        
        //print_r($args);
        //save Msg in archive table
        $archive_id=$this->saveMsgInArchives($args);
        $args['archive_id']=$archive_id;
        
        //send and check delivered msgs
        $this->sendMsgToAllMailingList($args);

    }
    
    public function resendMsg($msg_id) {
        $msg_info=$this->getMsgInfo($msg_id);
        
        $args=array();
        $args['msg_id']=$msg_info['id'];
        $args['grp_ids']=$msg_info['grp_ids'];
        $args['action']='Insert';
        
        //save Msg in archive table
        $archive_id=$this->saveMsgInArchives($args);
        $args['archive_id']=$archive_id;
        
        //send and check delivered msgs
        $this->sendMsgToAllMailingList($args);
    }
    
    public function saveMsgInArchives($args) {
        
        //msg_id  in $args array
        $args['send_date']=date("Y-m-d G:i:s A");
        $ma_Savecols=array('msg_id','send_date','grp_ids');
        $save_ob2 = new saveform('mailing_archive', $args, $ma_Savecols); //save to archive
        
        return $save_ob2->getInsertId();
    }
    
    public function sendMsgToAllMailingList($args) {
        
            $MLids=$this->getMailingListByGroupsIds($args['grp_ids']); //get mailing list ids
            $D_MLids=array(); //delivered msg ids
            foreach ($MLids as $id) {
                $mlist_info=$this->getUserInfo($id);
                    $status=$this->sendMsg($args, $mlist_info);
                    if($status){
                       $D_MLids[]= $id;
                    }
            }
            $args['delivered_ids']= implode(',', $D_MLids);
            $md_Savecols=array('archive_id','delivered_ids');
            $save_ob3 = new saveform('mailing_delivary_rep', $args, $md_Savecols); //save in delivared_ids
            
    }
    
    public function sendMsg($args,$mlist_info) {
                        
                        $userId=$mlist_info['id'];
                        $userName=$mlist_info['full_name'];
                        $userEmail=$mlist_info['email'];
                        
                        $Admin_mail="info@voitest.com";
                        
                        $msg_id=$args['msg_id'];
                        $MsgSubject = stripslashes($args['subject']);
                        $MsgBody=stripslashes($args['body']);

                        $to  = $userEmail ; // note the comma
			/* message */
			$message  = "Dear $userName <br>";
			$message .= $MsgBody;
			
			/* To send HTML mail, you can set the Content-type header. */
			$headers  = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

			/* additional headers */
			$headers .= "To:".$userEmail."\r\n";
			$headers .= "From: ".$Admin_mail." \r\n";
                        
                        //$str=$userEmail.' | '.$Admin_mail;
                        //echo "<script>alert('".$str."');</script>"; 
                        //echo $message."<br/>";
			/* and now mail it */
			$s = mail($to, $MsgSubject, $message, $headers);      
                        if($s){
                            return TRUE;	
                        }else{
                            return FALSE;
                        }
    }
    
    public function getUserInfo($id) {
            $cond=" id='$id'";
            $result=$this->fpdo->from('mailing_list')->where($cond)->fetchAll();
            if(count($result)>0){
                return $result[0] ;
            }
    }
    public function getGroupInfo($id){
            $cond=" id='$id'";
            $result=$this->fpdo->from('mailing_groups')->where($cond)->fetchAll();
            if(count($result)>0){
                return $result[0] ;
            }
    }
    public function getMsgInfo($id) {
            $cond=" id='$id'";
            $result=$this->fpdo->from('mailing_msg')->where($cond)->fetchAll();
            if(count($result)>0){
                return $result[0] ;
            }
    }
    public function getMailingListByGroupsIds($grp_ids) {
        $ids=  explode(',', $grp_ids);
        $MLids=array();
        foreach ($ids as $id) {
            $cond=" grp_id='$id' and active='1'";
            $result=$this->fpdo->from('mailing_list')->where($cond)->fetchAll();
            if(count($result)>0){
                foreach ($result as $row) {
                    $MLids[]=$row['id'];      
                }
            }
        }
        return $MLids;
    }
    
    public function deleteMsg($avgs) {
        print_r($avgs);
        
        $MSG_ids=$avgs['DeleteRow'];
        foreach ($MSG_ids as $id) {
            $avgs['DeleteRow']=array($id);
            $save_ob=new saveform('mailing_msg',$avgs,array('subject','body'));
            
            $cond=" msg_id='$id'";
            $result=$this->fpdo->from('mailing_archive')->where($cond)->fetchAll();
            if(count($result)>0){
                foreach ($result as $row) {
                    $archive_id=$row['id'];  
                    $avgs['DeleteRow']=array($archive_id);
                    $save_ob=new saveform('mailing_archive',$avgs,array('msg_id')); 
                    
                    $cond=" archive_id='$archive_id'";
                    $result=$this->fpdo->from('mailing_delivary_rep')->where($cond)->fetchAll();
                    if(count($result)>0){
                         foreach ($result as $row2) {
                            $rep=$row2['id'];  
                            $avgs['DeleteRow']=array($rep);
                            print_r($avgs);
                            $save_ob=new saveform('mailing_delivary_rep',$avgs,array('archive_id')); 
                         
                         }    
                    }
                    
                }
            }
            
            
        }
        
    }
    
    public function getReportsInfo($archive_id){
            $delivered_num=0;
            $recipient_num=0;
            $opened_num=0;
            $delivered_ids='';
            $recipient_ids='';
            $opened_ids='';
            $cond=" archive_id='$archive_id'";
            $result=$this->fpdo->from('mailing_delivary_rep')->where($cond)->fetchAll();
            if(count($result)>0){
                    $row=$result[0];
                    $delivered_ids=$row['delivered_ids'];
                    if($delivered_ids!=''){
                        $D_arr=  explode(',', $delivered_ids);
                        $delivered_num=  count($D_arr);
                    }
                    $recipient_ids=$row['recipient_ids'];
                    if($recipient_ids!=''){
                        $R_arr=  explode(',', $recipient_ids);
                        $recipient_num=  count($R_arr);
                    }
                    $opened_ids=$row['opened_ids'];
                    if($opened_ids!=''){
                        $O_arr=  explode(',', $opened_ids);
                        $opened_num=  count($O_arr);
                    }
                
            }
            
            return array('delivered_num'=>$delivered_num,'recipient_num'=>$recipient_num,'opened_num'=>$opened_num,
                         'delivered_ids'=>$delivered_ids,'recipient_ids'=>$recipient_ids,'opened_ids'=>$opened_ids);
    } 
    
    public function getListOfUsers($Uids) {
        $report='';
        if($Uids!=''){
            $users=  explode(',', $Uids);
            $report='<table class="table table-bordered table-hover table-striped" >'
                    . '<tr><th>User Name</th><th> E-Mail</th><th>Registration Date</th><th> Group Name </th></tr>';

            $UsInfo=array();
            foreach ($users as $id) {
                $arr=$this->getUserInfo($id);
                $UsInfo[]=$arr;
                $group_info=$this->getGroupInfo($arr['grp_id']);
                $report.="<tr><td>".$arr['full_name']."</td><td>".$arr['email']."</td><td>".$arr['date']."</td><td>".$group_info['title_en']."</td></tr>";  
            }

            $report.='</table>';
        }else{
            $report='There aren\'t any Users.';
        }
        //return $UsInfo; //return information as array
        return $report;  //return information as report
    }
    
}
