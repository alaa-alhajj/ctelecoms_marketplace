<div class="col-md-3">
  <a href="compose.php" class="btn btn-danger btn-block margin-bottom">Compose</a>
  <div class="box box-solid">
	<div class="box-header with-border">
	  <h3 class="box-title">Folders</h3>
	  <div class="box-tools">
		<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	  </div>
	</div>
	<div class="box-body no-padding">
	  <ul class="nav nav-pills nav-stacked">
                <?php
                  $Pname=basename($_SERVER['PHP_SELF']);
                  $pages=array($inboxPage,$sendPage,$trashPage);
                  if(!in_array($Pname, $pages)){
                       $Pname=basename($_SERVER['HTTP_REFERER']);
                  }
                  if($Pname ==$inboxPage){
                       echo '<li class="active"><a href="inbox.php"><i class="fa fa-inbox"></i> Inbox <span class="label label-primary pull-right">'.$_SESSION["unReadComp"].'</span></a></li>';
                  }else{
                      echo '<li><a href="inbox.php"><i class="fa fa-inbox"></i> Inbox <span class="label label-primary pull-right">'.$_SESSION["unReadComp"].'</span></a></li>'; 
                  }
                  
                  if($Pname ==$sendPage){
                       echo '<li class="active"><a href="send.php"><i class="fa fa-envelope-o"></i> Sent</a></li>';
                  }else{
                       echo '<li><a href="send.php"><i class="fa fa-envelope-o"></i> Sent</a></li>';
                  }
                  
                  if($Pname ==$trashPage){
                       echo '<li class="active"><a href="trash.php"><i class="fa fa-trash-o"></i> Trash</a></li>';
                  }else{
                       echo '<li><a href="trash.php"><i class="fa fa-trash-o"></i> Trash</a></li>';
                  }
                ?>
	  </ul>
	</div><!-- /.box-body -->
  </div><!-- /. box -->
  
</div><!-- /.col -->