<?php 
include('../../common/header.php');
include 'config.php';
?>
        <?php         
            $page=$_REQUEST['page'];
           
            if(isset($_REQUEST) && isset($_REQUEST['msg_id'])){
               
                $prevPage=basename($_REQUEST['page']); //parent page 
                $mail=$mailbox->getMsgInfo($db_table,$db_table_directions,$db_cms_users,$_REQUEST['msg_id'],$user_id,$prevPage);   
                $msg_id=$mail['msg_id'];
                $title=$mail['title'];
                $date=$mail['date'];
                $body=$mail['body'];
                $file=$mail['file'];
                $from=$mail['name']; 
                $from_id=$mail['from_id'];//is array of from_ids 
             
            }
           //insert reply for message
            if($_REQUEST && $_REQUEST['action']=='Reply'){
                
                $mailbox->addReply($user_id,$from_id,$_REQUEST,$db_table_replies,$db_table_directions,$rSavecols);
                
                $utils->redirect('read-mail.php?msg_id='.$_REQUEST['msg_id'].'&page='.$page.'');
            }
        
        ?>
          <div class="row">
            <?php include('mail-nav.php');?>
            <div class="col-md-9">
              <div class="box box-danger">
                  <?php
                  
                        $msg_ids=array(); 
                        $Pname=basename($_SERVER['HTTP_REFERER']);
                        $pages=array($inboxPage,$sendPage,$trashPage);
                        
                        if(!in_array($Pname, $pages)){
                            $Pname=$_REQUEST['page'];
                        }
                        
                        if($Pname ==='inbox.php'){
                            $ids= $mailbox->getMessages_ids('inbox', $db_table_directions, $db_table_trash, $user_id);
                            $msg_ids=  explode(',', $ids);
                            $PnameNum=0;
                        }else if($Pname ==='send.php'){
                            $ids= $mailbox->getMessages_ids('sent', $db_table_directions, $db_table_trash, $user_id);
                            $msg_ids=  explode(',', $ids);
                            $PnameNum=1;
                        }else{ //trash.php
                            $ids= $mailbox->getTrashMsg($db_table_trash,$user_id);
                            $msg_ids=  explode(',', $ids);
                            $PnameNum=2;
                           
                        }
                        

                        $nextMsg=$mailbox->getNextMsgId($msg_ids,$msg_id);
                        $prevMsg=$mailbox->getPrevMsgId($msg_ids,$msg_id);
                        
                        
                      ?>
                <div class="box-header with-border">
                  <h3 class="box-title">Read Mail</h3>
                  <div class="box-tools pull-right">
                    <a href="<?php echo "read-mail.php?msg_id=".$prevMsg."&page=".$Pname;?>" class="btn btn-box-tool" data-toggle="tooltip" title="Previous"><i class="fa fa-chevron-left"></i></a>
                    <a href="<?php echo "read-mail.php?msg_id=".$nextMsg."&page=".$Pname;?>" class="btn btn-box-tool" data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></a>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <div class="mailbox-read-info">
                    <h3><?=$title?></h3>
                        <?php 
                           if($Pname=='inbox.php'){
                               echo '<h5>From: '.$from.' <span class="mailbox-read-time pull-right">';
                           }else{
                               echo '<h5>To: '.$from.' <span class="mailbox-read-time pull-right">';
                           }
                        ?>
                        
                        <?php
                            echo $mailbox->get_date_format($date);
                        ?>
                        </span></h5>
                  </div><!-- /.mailbox-read-info -->
                  
                  <div class="mailbox-controls with-border text-center">
                    <div class="btn-group">
                      <a href="deleteMsg.php?msg_id=<?=$msg_id?>&PnameNum=<?=$PnameNum?>" onclick="return confirm('Are you sure you want to delete this Message?');"><button class="btn btn-default btn-sm" data-toggle="tooltip" title="Delete"><i class="fa fa-trash-o"></i></button></a>
                      <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Reply" onclick="addReply();"><i class="fa fa-reply"></i></button>
                      <a href="compose.php?msg_id=<?=$msg_id?>&act=forward"><button class="btn btn-default btn-sm" data-toggle="tooltip" title="Forward"><i class="fa fa-share"></i></button></a>
                    </div><!-- /.btn-group -->
                    <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Print"><i class="fa fa-print"></i></button>
                  </div><!-- /.mailbox-controls -->
                  <div class="mailbox-read-message">
                    <?=$body?>
                  </div><!-- /.mailbox-read-message -->
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <ul class="mailbox-attachments clearfix">
                      <?php 
                        $attas=$mailbox->showMsgAttachment($file);
                        echo $attas;
                      ?>
                  </ul>
                </div><!-- /.box-footer -->
                
                    <style>
                           .reply-header{
                                min-height:40px;
                                padding:10px 0px 10px 0px;
                                background-color: #dddddd;
                                border-bottom:1px solid #cccccc;
                           }
                           .title-cls{
                             text-align:center;
                            }
                           .date-cls{
                             text-align:right;
                           }
                           .body-cls{
                             background-color:white;
                             padding:15px;
                             border:1px 
                           }
                           .replies{
                               margin-left:0px;
                               margin-right:0px;
                            }
                           .reply{
                               margin-top:5px;
                            }
                    </style>
                    <?php
                        //print replies
                        $replies=$mailbox->showMsgReplies($db_table_replies,$db_cms_users,$msg_id);
                        echo $replies;
                    ?>
                    <script>
                            function addReply(){
                                $(".form-cls").slideDown(500);
                             }

                    </script>
                     <?php     
                        $reply_div=''; 
                        $reply_div.='<form action="'.$_SERVER['PHP_SELF'].'" method="post">
                             <div class="form-cls col-sm-12" style="display:none; margin:15px 0px 15px 0px;">
                                  <textarea name="body" class="reply-body FullTextEditor"></textarea>
                                  <input name="msg_id" type="hidden" value="'.$msg_id.'"/>
                                  <input name="page" type="hidden" value="'.$page.'"/>
                                  <input name="action" type="hidden" value="Reply"/>    
                                  <input class="btn btn-default" name="submit" type="submit" value="Send" />   
                              </div>
                              </form>
                             ';
                        echo $reply_div;
                    ?>
                    
                <div class="box-footer">
                  <div class="pull-right">
                    <button class="btn btn-default" onclick="addReply();"><i class="fa fa-reply"></i> Reply</button>
                    <a href="compose.php?msg_id=<?=$msg_id?>&act=forward&page=<?=$page?>"><button class="btn btn-default"><i class="fa fa-share"></i> Forward</button></a>
                  </div>
                    <a href="deleteMsg.php?msg_id=<?=$msg_id?>&PnameNum=<?=$PnameNum?>" onclick="return confirm('Are you sure you want to delete this Message?');"><button class="btn btn-default" ><i class="fa fa-trash-o"></i> Delete</button></a>
                  <!--<button class="btn btn-default" onclick="delete_message(<?=$msg_id?>,<?=$PnameNum?>)" ><i class="fa fa-trash-o"></i> Delete</button>-->
                  <button class="btn btn-default"><i class="fa fa-print"></i> Print</button>
                </div><!-- /.box-footer -->
              </div><!-- /. box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
	<?php include('../../common/footer.php');?>