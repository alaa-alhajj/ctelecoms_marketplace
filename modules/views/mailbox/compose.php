<?php
include('../../common/header.php');
include 'config.php';
?>
          <div class="row">
            <?php include('mail-nav.php');?>
            <div class="col-md-9">
              <div class="box box-danger">
                <?php
                   
                    $from_id=0;
                    if (isset($_REQUEST) && $_REQUEST['action'] == 'Insert') {
                      
                        $sendTo=array();
                        $to_type=$_REQUEST['to-type'];
                        
                            if($to_type=="groups"){
                                 $groups=$_REQUEST['groups'];
                                  //print_r($groups);
                                    if(count($groups)>0){
                                            foreach ($groups as $grp_id) {
                                                $cond='grp_id= '.$grp_id.'';
                                                $temp=$fpdo->from($db_cms_users)->where($cond)->fetchAll();
                                                foreach ($temp as $row) {
                                                    $sendTo[]=$row['id'];
                                                }
                                           }
                                     }
                            }else{ //users
                                $users=$_REQUEST['users'];
                                $sendTo=$users;
                            }
                        
                        if(($key = array_search($user_id, $sendTo)) !== false) {
                            unset($sendTo[$key]);
                        }
                      
                        
                        if((count($sendTo)<=0) and $from_id != 0){ //if create forward messages
                            $sendTo[]=$_REQUEST['from_id'];
                        }
                       
                       
                        if(count($sendTo)>0){
                            $mailbox->createMsg($db_table,$db_table_directions,$db_cms_users,$_REQUEST,$Savecols,$dSavecols,$user_id,$sendTo); //sendto its user id array
                        }else{
                            echo "<script>alert('There aren\'t user in groups.');</script>";
                            $utils->redirect($_SERVER['PHP_SELF']);
                        }
                         $utils->redirect($sendPage);
                    }
                   
                    if (isset($_REQUEST) && $_REQUEST['act'] == 'forward') {
                        if(isset($_REQUEST['msg_id'])){
                             $forward_text=$mailbox->createForwardMsg($db_table,$db_table_directions,$db_table_replies,$db_cms_users,$_REQUEST['msg_id'],$user_id);
                            
                        }
                    }
                    
                 ?>
                <form action="<?=$_SERVER['PHP_SELF']?>" method="post">  
                <div class="box-header with-border">
                  <h3 class="box-title">Compose New Message</h3>
                </div><!-- /.box-header -->
                <script>
                   $( document ).ready(function(){ 
                      
                    });
                  
                   function showDiv(){
                        if($('#groups').is(':checked')) {
                           $(".users-div").fadeOut();
                           $(".groups-div").fadeIn();
                        }else{
                            $(".groups-div").fadeOut();
                            $(".users-div").fadeIn();
                        }
                   }
                    
                </script>
                <div class="col-sm-12">
                    Send To:&nbsp; &nbsp;<input type="radio" name="to-type" value="groups" id="groups" checked onclick="showDiv();"/>&nbsp;Groups &nbsp; &nbsp; <input type="radio" name="to-type" value="users" id="users" onclick="showDiv();"/>&nbsp; Users
                </div> 
                <div class="col-sm-12 groups-div">
                     Groups Names:&nbsp; &nbsp;
                     <?php
                             $cond=' id <> 1 '; //exciption super admin
                             $temp=$fpdo->from($db_groups)->where($cond)->fetchAll();
                             if(count($temp)>0){
                                 foreach ($temp as $row) {
                                     echo '<input type="checkbox" name="groups[]" value="'.$row['id'].'"/>&nbsp;'.$row['title'].'&nbsp; &nbsp;';
                                 }
                             }
                     ?>
                </div> 
                <div class="col-sm-12 users-div" style="display: none;">
                     Users Names:&nbsp; &nbsp;
                     <?php
                             $cond=' id <> '.$user_id.' and grp_id <> 1';
                             $temp=$fpdo->from($db_cms_users)->where($cond)->fetchAll();
                             if(count($temp)>0){
                                 foreach ($temp as $row) {
                                     echo '<input type="checkbox" name="users[]" value="'.$row['id'].'"/>&nbsp;'.$row['full_name'].'&nbsp; &nbsp;';
                                 }
                             }
                     ?>
                    
                </div> 
                <div class="box-body">
                  <div class="form-group">
                      <!--  <input class="form-control" type="email" name="to" placeholder="To:" required>-->
                  </div>
                  <div class="form-group">
                    <input class="form-control" type="text" name="title" placeholder="Subject:">
                  </div>
                  <div class="form-group">
                    <textarea id="compose-textarea" class="form-control FullTextEditor"  style="height: 300px" name="body">
                    <?=$forward_text?>
                    </textarea>
                  </div>
                  <div class="form-group">
                    <div class="btn btn-default btn-file">
                      <i class="fa fa-paperclip"></i> Attachment
                      <input type="file" name="file">
                    </div>
                    <p class="help-block">Max. 32MB</p>
                    <input type="hidden" name="action" value="Insert">
                    <input type="hidden" name="from_id" value="<?=$from_id?>"/>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="pull-right">
                    <button type="submit" name="submit" class="btn btn-danger"><i class="fa fa-envelope-o"></i> Send</button>
                  </div>
                 <button class="btn btn-default" onclick="document.location='inbox.php';return false;"><i class="fa fa-times"></i> Discard</button>
                </div><!-- /.box-footer -->
                </form>
                  
              </div><!-- /. box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
	<?php include('../../common/footer.php');?>