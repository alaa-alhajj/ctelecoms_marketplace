<?php
    if(isset($_REQUEST,$_REQUEST['id'])){
        //print_r($_REQUEST);
        $id=$_REQUEST['id'];
        $title=$_REQUEST['title'];
        $link=_PREF.$_REQUEST['pLang'].'/page'.$id.'/'.$title;
        
        if(isset($_REQUEST['submit'],$_REQUEST['full_name'],$_REQUEST['email'],$_REQUEST['password'],$_REQUEST['repeat_password']) && $_REQUEST['submit']=='submit'){
            
            $_REQUEST['submit']='submited';       
            $full_name=  addslashes($_REQUEST['full_name']);
            $email=addslashes($_REQUEST['email']);
            $company=addslashes($_REQUEST['company']);
            $city=addslashes($_REQUEST['city']);
            $adress=addslashes($_REQUEST['adress']);
            $password=$_REQUEST['password'];
            $repeat_password=$_REQUEST['repeat_password'];
            $successMSG='';
            $ErrorMSG='';
            if($password===$repeat_password){
                global $utils;
                global $pLang;
                
                $insert_id = $this->fpdo->insertInto('customers')->values(array('name'=>$full_name,'email'=>$email,'password'=>md5($password),'company'=>$company,'city'=>$city,'adress'=>$adress))->execute(); 
                if($insert_id!=''){
                    
                    $successMSG="<div class='alert alert-success'>Successful, welcome <strong>$full_name<strong> in our site  </div>"; 
                    @session_start();
                    $_SESSION['CUSTOMER_Name'] = $full_name;
                    $_SESSION['CUSTOMER_ID'] = $insert_id;
                    $utils->redirect(_PREF.$pLang."/page49/My-Account");
                    
                }else{
                    $ErrorMSG="<div class='alert alert-danger'>Error, this Email is aleady exist.</div>"; 
                }
                
                
            }else{
               $ErrorMSG="<div class='alert alert-danger'>Error, password don't match repeat password. Enter password again.</div>"; 
            }
            
        }
    }
    
    
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
              <input type="text" class="form-control" name='full_name' id="full_name" placeholder="Enter full name" required>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="company">Company:</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name='company' id="company" placeholder="Enter company">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="city">City:</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name='city' id="city" placeholder="Enter city">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="adress">Adress:</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name='adress' id="adress" placeholder="Enter adress">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="email">Email:</label>
          <div class="col-sm-10">
              <input type="email" class="form-control" name='email' id="email" placeholder="Enter Email" required>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="password">Password:</label>
          <div class="col-sm-10"> 
              <input type="password" class="form-control" name='password' id="password" placeholder="Enter password" required>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="repeat_password">Repeat Password:</label>
          <div class="col-sm-10"> 
              <input type="password" class="form-control" name='repeat_password' id="repeat_password" placeholder="Enter password again" required>
          </div>
        </div>
        <div class="form-group"> 
          <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-default" id="submit" name='submit' value="submit">Submit</button>
          </div>
        </div>
    </form>
</div>

