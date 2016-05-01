<?php
class MailFunction{
    function __construct() {
        
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
}