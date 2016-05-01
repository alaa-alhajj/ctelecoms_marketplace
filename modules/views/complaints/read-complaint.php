<?php 
include('../../common/header.php');
include 'config.php';
?>
        <?php         
            if(isset($_REQUEST) && isset($_REQUEST['comp_id'])){
                $comp=$complaintsObj->getComplaintInfo($db_table,$db_users,$_REQUEST['comp_id'],$_REQUEST['page']);
                $comp_id=$comp['comp_id'];
                $title=$comp['title'];
                $start_date=$comp['start_date'];
                $details=$comp['details'];
                $file=$comp['file'];
                $from=$comp['name'];
                $email=$comp['email'];
                $full_name=$comp['full_name'];
            }
           //insert reply for message
            if($_REQUEST && $_REQUEST['action']=='Reply'){
                
              $complaintsObj->addReply($_REQUEST,$db_table,$db_table_replies,$user_id);
            
            //$utils->redirect('read-complaint.php?comp_id='.$comp_id);
            }
        
        ?>
          <div class="row">
            <?php include('complaints-nav.php');?>
            <div class="col-md-9">
              <div class="box box-primary">
                  <?php
                        $comp_ids=array();                        
                        $Pname=basename($_SERVER['HTTP_REFERER']);
                        $pages=array($inboxPage,$sendPage,$trashPage);
                        if(!in_array($Pname, $pages)){
                            $Pname=$_REQUEST['page'];
                        }

                        if($Pname =='inbox.php'){
                            $comp_ids= $_SESSION['comp_ids']; 
                            $PnameNum=0;
                        }else{ //trash.php
                            $comp_ids= $_SESSION['TrashComp_ids'];
                            $PnameNum=2;
                        }
                        
                        $nextComp=$complaintsObj->getNextCompId($comp_ids,$comp_id);
                        $prevComp=$complaintsObj->getPrevCompId($comp_ids,$comp_id);
                        
                        
                      ?>
                <div class="box-header with-border">
                  <h3 class="box-title">Read Complaint</h3>
                  <div class="box-tools pull-right">
                    <a href="<?php echo "read-complaint.php?comp_id=".$prevComp."&page=".$Pname;?>" class="btn btn-box-tool" data-toggle="tooltip" title="Previous"><i class="fa fa-chevron-left"></i></a>
                    <a href="<?php echo "read-complaint.php?comp_id=".$nextComp."&page=".$Pname;?>" class="btn btn-box-tool" data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></a>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <div class="mailbox-read-info">
                    <h3><?=$title?></h3>
                    <h5>From: <?=$from?> <span class="mailbox-read-time pull-right">
                        <?php
                            echo $complaintsObj->get_date_format($start_date);
                        ?>
                        </span></h5>
                  </div><!-- /.mailbox-read-info -->
                  <div class="mailbox-controls with-border text-center">
                    <div class="btn-group">
                      <a href="deleteComp.php?comp_id=<?=$comp_id?>&PnameNum=<?=$PnameNum?>" onclick="return confirm('Are you sure you want to delete this Complaint?');"><button class="btn btn-default btn-sm" data-toggle="tooltip" title="Delete"><i class="fa fa-trash-o"></i></button></a>
                      <button class="btn btn-default btn-sm scroll-down" data-toggle="tooltip" title="Reply" onclick="addReply();"><i class="fa fa-reply"></i></button>
                    </div><!-- /.btn-group -->
                    <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Print"><i class="fa fa-print"></i></button>
                  </div><!-- /.mailbox-controls -->
                  <div class="mailbox-read-message">
                    <?=$details?>
                  </div><!-- /.mailbox-read-message -->
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <ul class="mailbox-attachments clearfix">
                      <?php 
                        $attas=$complaintsObj->showCompAttachment($file);
                        echo $attas;
                      ?>
                  </ul>
                </div><!-- /.box-footer -->
                
                    <style>
                           .reply-header{
                                min-height:50px;
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
                    $reply=$complaintsObj->showCompReplies($db_table,$db_table_replies,$db_cms_emps,$comp_id);
                    echo $reply;
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
                                  <input name="comp_id" type="hidden" value="'.$comp_id.'"/>
                                  <input name="email" type="hidden" value="'.$email.'"/>
                                  <input name="full_name" type="hidden" value="'.$full_name.'"/>    
                                  <input name="action" type="hidden" value="Reply"/>    
                                  <input class="btn btn-default" name="submit" type="submit" value="Send" />   
                              </div>
                              </form>
                             ';
                        echo $reply_div;
                    ?>
                <a name="replay-section"></a>  
                <div class="box-footer">
                  <div class="pull-right">
                    <button class="btn btn-default" onclick="addReply();"><i class="fa fa-reply"></i> Reply</button>
                  </div>
                    <a href="deleteComp.php?comp_id=<?=$comp_id?>&PnameNum=<?=$PnameNum?>" onclick="return confirm('Are you sure you want to delete this Complaint?');"><button class="btn btn-default"  ><i class="fa fa-trash-o"></i> Delete</button></a>
                  <button class="btn btn-default"><i class="fa fa-print"></i> Print</button>
                </div><!-- /.box-footer -->
              </div><!-- /. box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
	<?php include('../../common/footer.php');?>