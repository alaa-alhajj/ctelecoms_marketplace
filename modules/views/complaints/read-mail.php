<?include('../../common/header.php')?>
<?include 'config.php';?>
        <?php         
            if(isset($_REQUEST) && isset($_REQUEST['comp_id'])){
                $comp=$complaintsObj->getComplaintInfo($db_table,$db_users,$_REQUEST['comp_id'],$_REQUEST['page']);
                $comp_id=$comp['comp_id'];
                $title=$comp['title'];
                $start_date=$comp['start_date'];
                $details=$comp['details'];
                $file=$comp['file'];
                $from=$comp['name']; 
            }
           //insert reply for message
            if($_REQUEST && $_REQUEST['action']=='Reply'){
    
            $_REQUEST['admin_id']=$user_id;
            $date=date("Y-m-d h:i:s");
            $_REQUEST['date']=$date;
            $_REQUEST['file']='';
            $body=$_REQUEST['reply-body'];
            $body=  stripcslashes($body);

            $comp_id=$_REQUEST['comp_id'];
            $_REQUEST['from']='admin';
            $_REQUEST['action']='Insert';
            $save_ob=new saveform($db_table_replies,$_REQUEST,$rSavecols);
            $utils->redirect('read-mail.php?comp_id='.$comp_id);
            }
        
        ?>
          <div class="row">
            <?include('mail-nav.php')?>
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
                    <a href="<?php echo "read-mail.php?comp_id=".$prevComp."&page=".$Pname;?>" class="btn btn-box-tool" data-toggle="tooltip" title="Previous"><i class="fa fa-chevron-left"></i></a>
                    <a href="<?php echo "read-mail.php?comp_id=".$nextComp."&page=".$Pname;?>" class="btn btn-box-tool" data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></a>
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
                  <script>
                      function delete_message(comp_id,PnameNum){
                           $.ajax({
                                    url: "deleteComp.php",
                                    type: "post",
                                    data: {
                                            "comp_id":comp_id,
                                            "page":PnameNum
                                          },
                                    success: function(output) 
                                    {     
                                         //alert(output);
                                         if(PnameNum == 0){
                                              window.location = 'inbox.php';
                                         }else{
                                             window.location = 'trash.php';
                                         } 
                                    }
                                });
                      }
                  </script>
                  <div class="mailbox-controls with-border text-center">
                    <div class="btn-group">
                      <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Delete" onclick="delete_message(<?=$comp_id?>,<?=$PnameNum?>);"><i class="fa fa-trash-o"></i></button>
                      <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Reply" onclick="addReply();"><i class="fa fa-reply"></i></button>
                      <a href="report.php?comp_id=<?=$comp_id?>"><button class="btn btn-default btn-sm" data-toggle="tooltip" title="Report"><i class="fa">Report</i></button></a>
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
                    $reply=$complaintsObj->showCompReplies($fpdo,$db_table_replies,$db_cms_emps,$db_users,$comp_id);
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
                                  <input name="user_id" type="hidden" value="'.$user_id.'"/>    
                                  <input name="action" type="hidden" value="Reply"/>    
                                  <input class="btn btn-default" name="submit" type="submit" value="Save" />   
                              </div>
                              </form>
                             ';
                        echo $reply_div;
                    ?>
                    
                <div class="box-footer">
                  <div class="pull-right">
                    <button class="btn btn-default" onclick="addReply();"><i class="fa fa-reply"></i> Reply</button>
                    <button class="btn btn-default"><i class="fa fa-share"></i> Forward</button>
                  </div>
                  <button class="btn btn-default" onclick="delete_message(<?=$comp_id?>,<?=$PnameNum?>);" ><i class="fa fa-trash-o"></i> Delete</button>
                  <button class="btn btn-default"><i class="fa fa-print"></i> Print</button>
                </div><!-- /.box-footer -->
              </div><!-- /. box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
	<?include('../../common/footer.php')?>