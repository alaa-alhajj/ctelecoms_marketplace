<?php
    global $utils;
    global $pLang;
    
    $customer_id=$utils->checkLogin();
    
    if(isset($_REQUEST)){
        //print_r($_REQUEST);
        if(isset($_REQUEST['submit'],$_REQUEST['oldpassword'],$_REQUEST['newpassword'],$_REQUEST['repeat_newpassword'])){

            echo $oldPassword=addslashes($_REQUEST['oldpassword']);
            echo $newpassword=$_REQUEST['newpassword'];
            echo $repeat_newpassword=$_REQUEST['repeat_newpassword'];
            $successMSG='';
            $ErrorMSG='';
            //check if old password is correct
            $customer_info = $this->fpdo->from('customers')->where('id', $customer_id)->fetch();
           
            $customer_password=$customer_info['password'];
            if($customer_password !==  md5($newpassword)){ //check if new password is same old one.
                if($customer_password ===  md5($oldPassword)){ // old password is correct
                    if($newpassword===$repeat_newpassword && $newpassword!=''){
                         print_r($_REQUEST);
                        $query = $this->fpdo->update("customers")->set(array('password' => md5($newpassword) ))->where('id', $customer_id)->execute();
                        if($query!=''){
                            $successMSG="<div class='alert alert-success'> Your Password was changed successfully </div>"; 
                        }
                    }else{
                       $ErrorMSG="<div class='alert alert-danger'>Error, new password don't match  renew password. Enter new password again.</div>"; 
                    }

                }else{ // old password is incurrect
                    $ErrorMSG="<div class='alert alert-danger'>Failed, Old password is incorrect. Please, enter your old password correctly   .</div>"; 
                }
                
            }else{
                $ErrorMSG="<div class='alert alert-danger'>Failed,Your new password is same Old one.</div>"; 
            }
      }
    }
    
?>

<?php
   $customer_info = $this->fpdo->from("customers")->where("id='$customer_id'")->fetch();
?>
<div class="col-sm-12">
    <form class="form-horizontal" role="form" method="post" action="">
        <div class="form-group">
          <label class=" col-sm-2"></label>
          <div class="col-sm-10">
            <?=$successMSG?>
            <?=$ErrorMSG?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-offset-2 col-sm-10">If you wont change your old password fill these fields:</label>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="oldpassword">Old Password:</label>
          <div class="col-sm-10"> 
              <input type="password" class="form-control" name='oldpassword' id="oldpassword" placeholder="Enter old password.">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="newpassword">New Password:</label>
          <div class="col-sm-10"> 
              <input type="password" class="form-control" name='newpassword' id="newpassword" placeholder="Enter new password.">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="repeat_newpassword">Repeat New Password:</label>
          <div class="col-sm-10"> 
              <input type="password" class="form-control" name='repeat_newpassword' id="repeat_newpassword" placeholder="Enter new password again.">
          </div>
        </div>
        <div class="form-group"> 
          <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-default" id="submit" name='submit' value="submit">Submit</button>
          </div>
        </div>
    </form>
</div>

