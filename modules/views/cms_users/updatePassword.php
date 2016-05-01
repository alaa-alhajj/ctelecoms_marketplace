<?php
include 'config.php';
include '../../common/header.php';


if($_REQUEST && $_REQUEST['old_password'] && $_REQUEST['password'] && $_REQUEST['repassword']){
     $oldPass=$_REQUEST['old_password'];
     $newPass=$_REQUEST['password'];
     $repass=$_REQUEST['repassword'];
    
   
    $oldpassword=$utils->lookupField('cms_users','id', 'password', $user_id);
     
    if($oldpassword ==  md5($oldPass)){
        if($newPass==$repass){
            $_REQUEST['action']='Edit';
            $_REQUEST['password']= md5($newPass);
            $_REQUEST['id']=$user_id;
            //save edit
            $save_ob=new saveform($db_table,$_REQUEST,array('password'),'id');
            
            echo "<script> alert('update password successful.');</script>";
          }else{
            echo "<script> alert('Error: please, Enter password again');</script>";
          }
    }else{
        echo "<script> alert('Error: your Old Password is incorrect.');</script>";
    }
}

?>
<div class="col-sm-12">
   <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
       <div class="col-sm-6">Enter  Old Password:</div><div class="col-sm-6"><input name="old_password" type="password" required/></div>
       <div class="col-sm-6">Enter  New Password:</div><div class="col-sm-6"><input name="password" type="password" required/></div>
       <div class="col-sm-6">Please, Enter New Password again:</div><div class="col-sm-6"> <input name="repassword" type="password" required/></div>
       <div class="col-sm-12"><input type="submit" value="submit"/></div>
</form>   
</div>
<?php
include_once '../../common/footer.php';
?>