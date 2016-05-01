<?php 
include('../../common/header.php');
include 'config.php';
?>
        
       <?php
            if(isset($_REQUEST) && $_REQUEST['action']=='Delete')
             {
                 $_REQUEST['action']='Edit';
                 $msg_ids=$_REQUEST['msg_id']=$_REQUEST['rows'];
                 
                 foreach ($msg_ids as $msg_id) {

                      $mailbox->deleteMsg($msg_id,$user_id,$db_table_trash); 
                 }
                 
                  //print_r($_REQUEST);
                  $utils->redirect($sendPage);
             }
             
            $ids= $mailbox->getMessages_ids('sent', $db_table_directions, $db_table_trash, $user_id);
            $Sendmsg_ids=array();
            if($ids!=''){
                $Sendmsg_ids=  explode(',', $ids);
            }
       ?> 
        
           <div class="row">
            <?php include('mail-nav.php');?>
            <div class="col-md-9">    
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Sent</h3>
                  <div class="box-tools pull-right">
                    <div class="has-feedback">
                        <script type="text/javascript">
                          $(document).ready(function() {
                            $('.submit_on_enter').keydown(function(event) {
                                if (event.keyCode == 13) {
                                    this.form.submit();
                                    return false;
                                 }
                            });
                          });
                        </script>  
                      <form action="<?=$_SERVER['PHP_SELF']?>">  
                          <input type="text" name="title" value="<?=$_REQUEST['title']?>" class="form-control input-sm submit_on_enter" placeholder="Search Mail">
                      </form>
                      <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                    <?php
                      
                    ?>
                    <script>
                         function checkAll(ele) {
                                var checkboxes = document.getElementsByTagName('input');
                                if (ele.value==='0') { 
                                    for (var i = 0; i < checkboxes.length; i++) {
                                        if (checkboxes[i].type == 'checkbox') {
                                            checkboxes[i].checked = true;
                                        }
                                    }
                                    ele.value='1';
                                } else {
                                    for (var i = 0; i < checkboxes.length; i++) {
                                        console.log(i)
                                        if (checkboxes[i].type == 'checkbox') {
                                            checkboxes[i].checked = false;
                                        }
                                    }
                                    ele.value='0';
                                }
                                 
                            }

                    </script>
                  <div class="mailbox-controls">
                    <!-- Check all button -->
                    <button class="btn btn-default btn-sm checkbox-toggle checked" value="0" onclick="checkAll(this)"><i class="fa fa-square-o"></i></button>
                    <div class="btn-group">
                        <a id="AskDelete" href="javascript:void(0);"><button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button></a>
                      <!--<button class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                          <button class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>-->
                    </div><!-- /.btn-group -->
                    <button class="btn btn-default btn-sm" onClick="window.location='<?=$sendPage?>'"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right">
                      <?php
                        $pg=$mailbox->Create_pagination($Sendmsg_ids,$LPP,$pn,$start,$limit);
                        echo $pg['pg_format'];
                      ?>  
                      <div class="btn-group">
                          <a href="<?=$pg['prevPage']?>"><button class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button></a>
                          <a href="<?=$pg['nextPage']?>"><button class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button></a>
                      </div><!-- /.btn-group -->
                    </div><!-- /.pull-right -->
                  </div>
                  <div class="table-responsive mailbox-messages">
                   <form name="TableForm">  
                    <table class="table table-hover table-striped">
                      <tbody>
                            <?php      
                                $filter=""; 
                                if(isset($_REQUEST['title']) && $_REQUEST['title']!=""){
                                     $filter="title like '%".$_REQUEST['title']."%' and "; 
                                 }
                                 
                                $msgs=$mailbox->getMsgList('sent',$db_table,$db_table_directions,$db_table_trash,$db_cms_users,$user_id,$filter,$limit); 
                                echo $msgs;
                            ?> 
                                      
                        
                      </tbody>
                    </table><!-- /.table -->
                    </form>
                  </div><!-- /.mail-box-messages -->
                </div><!-- /.box-body -->
                <div class="box-footer no-padding">
                <div class="mailbox-controls">
                   
                  <div class="pull-right" style="float: right; margin-bottom: 5px;">
                     <?php
                        $pg=$mailbox->Create_pagination($Sendmsg_ids,$LPP,$pn,$start,$limit);
                         echo $pg['pg_format'];
                      ?>  
                      <div class="btn-group">
                          <a href="<?=$pg['prevPage']?>"><button class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button></a>
                          <a href="<?=$pg['nextPage']?>"><button class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button></a>
                      </div><!-- /.btn-group -->
                   </div><!-- /.pull-right -->
                 </div>
                </div>
              </div><!-- /. box -->
            </div><!-- /.col -->
           
          </div><!-- /.row -->
	<?php include('../../common/footer.php');?>