<?php
    if(isset($_REQUEST,$_REQUEST['id'])){
        //print_r($_REQUEST);
        $id=$_REQUEST['id'];
        $title=$_REQUEST['title'];
        $link=_PREF.$_REQUEST['pLang'].'/page'.$id.'/'.$title;
        
        if(isset($_REQUEST['SignUp'],$_REQUEST['full_name'],$_REQUEST['email'],$_REQUEST['password'],$_REQUEST['repeat_password']) && $_REQUEST['SignUp']=='signup'){
            
            $_REQUEST['SignUp']='signed';       
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


                <div class="login-block sign-up-block">
                        <h1>Sign up</h1>
                        <form class="form-horizontal" role="form" method="post" action="">
                            <?=$successMSG?>
                            <?=$ErrorMSG?>
                            <input type="text"  name='full_name' id="full_name" placeholder="Full name" required>
                            <input type="text"  name='company' id="company" placeholder="Company">
                            <input type="text"  name='city' id="city" placeholder="City">
                            <input type="text"  name='adress' id="adress" placeholder="Adress">
                            <input type="email"  name='email' id="email" placeholder="Email" required>
                            <input type="password"  name='password' id="password" placeholder="Password" required>
                            <input type="password"  name='repeat_password' id="repeat_password" placeholder="Re-password" required>
                            <button type="submit"  id="submit" name='SignUp' value="signup">Sign Up</button>
                        </form>
                </div>
