<?php 
include('../../common/header.php');
include 'config.php';
?>
         
          <div class="row">
            <?php include('complaints-nav.php');?>
            <div class="col-md-9">
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Report</h3>
                  
                </div><!-- /.box-header -->
                <div class="box-footer">
                </div><!-- /.box-footer -->
                
<?php
 echo '<style>
        .filter-cls{
            margin-bottom:15px;
         }
       
       .complaint{
          margin-top:5px;
          background-color:white;
          width:100%;
          border:1px solid  #cccccc;
          margin-bottom:15px;
       }
       .complaint:hover{
          opacity:0.6;
        }
       .msg-header{
            min-height:50px;
            padding:10px;
            border-bottom:1px solid #cccccc;
       }
       .title-cls{
         text-align:center;
        }
       .date-cls{
         text-align:right;
       }
        .flag-cls{
         background-color:white;
         padding-left:15px;
         border-bottom:1px solid #cccccc;
        }       

       .body-cls{
         background-color:white;
         padding:15px;
         border-bottom:1px solid #cccccc;
       }
       .btns-cls{
         margin-top:-20px;
         text-align:right;
       }
       .form-cls textarea{
         padding:15px;
        }
       .send-reply-cls{
         float:right;
       }
       .replies{
           float:left;
           width:100%;
        }
       .reply{
           background-color:white;
           margin-top:5px;
        }
        .complaints-div{
           margin-top:20px;
           padding:15px;
         }
        .evaluiation-div{
           margin-top:40px;
           width:100%;
           text-align:center;
        } 
        .evaluatio-values{
           padding:5px;          
        }

      </style>';

?>
               
             <div class="col-sm-12 filter-cls">
                    <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
                        Complaint status: 
                        <select name="status_id" id="status_id" class="btn btn-default" style="padding-left:0px; padding-right: 0px;" value="<?=$_REQUEST['status_id']?>" >
                            <option value="0" >all</option>
                            <?php
                                foreach ($fpdo->from($db_table_status)->where('')->fetchAll() as $row){
                                    if($row['id'] == $_REQUEST['status_id']){
                                          $isSelected = ' selected="selected"'; // if the option submited in form is as same as this row we add the selected tag
                                     } else {  
                                          $isSelected = ''; // else we remove any tag
                                     }
                                 echo '<option value="'.$row['id'].'"'.$isSelected.'>'.$row['name'].'</option>';
                                }
                            ?>
                        </select>

                        Department: 
                        <select name="department_id" class="btn btn-default" style="padding-left:0px; padding-right: 0px;" value="<?php echo $_REQUEST['department_id'];?>"  >
                            <option value="0" >all</option>
                            <?php
                                foreach ($fpdo->from($db_cms_complaints_departments)->where('')->fetchAll() as $row){
                                    
                                     if($row['id'] == $_REQUEST['department_id']){
                                          $isSelected = ' selected="selected"'; // if the option submited in form is as same as this row we add the selected tag
                                     } else {  
                                          $isSelected = ''; // else we remove any tag
                                     }    
                                 echo '<option value="'.$row['id'].'"'.$isSelected.'>'.$row['name'].'</option>';
                                }
                            ?>
                        </select>

                         After Date: 
                         <input type="date" name="date" class="" value="<?=$_REQUEST['date']?>" style="height:30px;" />
                         <input type="submit" class="btn btn-default" value="filter"/>
                    </form>
            </div>
            <?php

                    $conditions=array();

                    if ($_REQUEST && $_REQUEST['status_id']){
                        $conditions[]="status_id=".$_REQUEST['status_id'];
                    }else{
                        $conditions[]='1';
                    }

                    if ($_REQUEST && $_REQUEST['department_id']){
                         $conditions[]="department_id=".$_REQUEST['department_id'];
                    }else{
                         $conditions[]='1';
                    }

                    if ($_REQUEST && $_REQUEST['date']){
                        $conditions[]="start_date >= ".$_REQUEST['date'];
                    }else{
                         $conditions[]='1';
                    }
                    //print_r($conditions);

                    $cond=implode($conditions, " and ");
                    //echo $cond;
                    
                      $report=$complaintsObj->createReport($db_table,$db_table_evaluation,$cond);
                      echo $report;
                    
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
                        <button class="btn btn-default"><i class="fa fa-print"></i> Print</button>
                  </div>
                </div><!-- /.box-footer -->
              </div><!-- /. box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
	<?php include('../../common/footer.php');?>