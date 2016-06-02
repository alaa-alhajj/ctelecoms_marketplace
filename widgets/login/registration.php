<?php
     global $utils;
     global $pLang;
    if(isset($_REQUEST,$_REQUEST['cust'],$_REQUEST['code'])){
        $code=$_REQUEST['code'];
        $cid=$_REQUEST['cust'];
        $cust_info=$this->fpdo->from('customers')->where('id='.$cid)->fetch();
        $activation_code=$cust_info['activation_code'];
        $full_name=$cust_info['name'];
        if($code==$activation_code){
            //make customer account active
            $this->fpdo->update('customers')->set(array('active' =>1))->where("id=$cid")->execute();
            $successMSG="<div class='alert alert-success'>Your Account was activated Successfully, welcome <strong>$full_name<strong> in our site  </div>"; 
            @session_start();
            $_SESSION['CUSTOMER_Name'] = $full_name;
            $_SESSION['CUSTOMER_ID'] =$cid ;
            $utils->redirect(_PREF.$pLang."/page49/My-Account");
        }else{
            $ErrorMSG="<div class='alert alert-danger'>Sorry Your Account isn't activated, check your email and try again.</div>"; 
        }
    }    


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
                //check if email already exist
                $cust_info=$this->fpdo->from('customers')->where(array('email' => $email))->fetch();
                if(count($cust_info) <= 1){ // email not exist
                    $activation_code=  rand(10000, 99999);
                    $insert_id = $this->fpdo->insertInto('customers')->values(array('name'=>$full_name,'email'=>$email,'password'=>md5($password),'company'=>$company,'city'=>$city,'adress'=>$adress,'activation_code'=>$activation_code))->execute(); 
                    $active_link="http://"._SITE._PREF.$_SESSION['pLang']."/page69/cust$insert_id/code$activation_code/activation";
                    
                    if($insert_id!=''){
                        //send activation email
                        $tags = array("{full-name}" => $full_name, '{activation-link}' => $active_link);
                        $utils->sendMailC("info@voitest.com", $email, "Activation Email", "", 2, $tags);
                        
                        //send new customer notification to admin
                        //get recipient_email
                        $mail_dts=$this->fpdo->from('mails')->where('id=4')->fetch();
                        $recipient_email=$mail_dts['recipient_email'];
                        $customer_dtls.="</table>";
                        $utils->sendMailC("info@voitest.com", $recipient_email, "new Customer register notification", "", 4, $tags2);
                        
                        $successMSG="<div class='alert alert-success'>Successful, welcome <strong>$full_name</strong> in our site. Check your Email for Activation email.  </div>"; 
                    }else{
                        $ErrorMSG="<div class='alert alert-danger'>Error,Please, try again later.</div>";
                    }
                }else{ // email already exist
                     $ErrorMSG="<div class='alert alert-danger'>Error, this Email <$email> is aleady exist.</div>"; 
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
