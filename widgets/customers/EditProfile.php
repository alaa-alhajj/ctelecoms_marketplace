<?php
    global $utils;
    global $pLang;
    
    $customer_id=$utils->checkLogin();
    
    if(isset($_REQUEST,$_REQUEST['id'])){
        //print_r($_REQUEST);
        if(isset($_REQUEST['submit'],$_REQUEST['full_name'],$_REQUEST['email']) && $_REQUEST['submit']=='submit'){
            
            $_REQUEST['submit']='submited';       
            $full_name=  addslashes($_REQUEST['full_name']);
            $email=addslashes($_REQUEST['email']);
            $company=addslashes($_REQUEST['company']);
            $city=addslashes($_REQUEST['city']);
            $adress=addslashes($_REQUEST['adress']);
            $newpassword=$_REQUEST['newpassword'];
            $repeat_newpassword=$_REQUEST['repeat_newpassword'];
            $successMSG='';
            $ErrorMSG='';
            if($newpassword === '' || $repeat_newpassword === ''){
                 $query = $this->fpdo->update("customers")->set(array(
                                                                   'name' => $full_name,
                                                                   'email' => $email,
                                                                   'company' => $company,
                                                                   'city' => $city,
                                                                   'adress' => $adress
                                                                   ))->where('id', $customer_id)->execute();
                  $successMSG="<div class='alert alert-success'> Your information was updated  successfully </div>"; 
            }else{
                
                if($newpassword===$repeat_newpassword){
                    
                    $query = $this->fpdo->update("customers")->set(array(
                                                                   'name' => $full_name,
                                                                   'email' => $email,
                                                                   'company' => $company,
                                                                   'city' => $city,
                                                                   'adress' => $adress,
                                                                   'password' => md5($newpassword)
                                                                   ))->where('id', $customer_id)->execute();
                    if($query!=''){
                        $successMSG="<div class='alert alert-success'> Your information was updated  successfully </div>"; 
                        $_SESSION['CUSTOMER_Name'] = $full_name;
                    }
                }else{
                   $ErrorMSG="<div class='alert alert-danger'>Error, new password don't match repeat new password. Enter new password again.</div>"; 
                }
            
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
          <label class="control-label col-sm-2" for="fullname">Full Name:</label>
          <div class="col-sm-10">
              <input type="text" class="form-control" name='full_name' id="full_name" placeholder="Enter full name" value="<?=$customer_info['name']?>" required>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="company">Company:</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name='company' id="company" placeholder="Enter company" value="<?=$customer_info['company']?>">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="city">City:</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name='city' id="city" placeholder="Enter city" value="<?=$customer_info['city']?>">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="adress">Adress:</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name='adress' id="adress" placeholder="Enter adress" value="<?=$customer_info['adress']?>">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="email">Email:</label>
          <div class="col-sm-10">
              <input type="email" class="form-control" name='email' id="email" placeholder="Enter Email" required value="<?=$customer_info['email']?>">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-offset-2 col-sm-10">If you won't change your old password:</label>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="newpassword">Password:</label>
          <div class="col-sm-10"> 
              <input type="password" class="form-control" name='newpassword' id="newpassword" placeholder="Enter new password if you won't change old one.">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="repeat_newpassword">Repeat Password:</label>
          <div class="col-sm-10"> 
              <input type="password" class="form-control" name='repeat_newpassword' id="repeat_newpassword" placeholder="Enter new password again">
          </div>
        </div>
        <div class="form-group"> 
          <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-default" id="submit" name='submit' value="submit">Submit</button>
          </div>
        </div>
    </form>
</div>

