<?
@session_start();
 global $utils;
 global $pLang;
if ($_POST['LoginC'] === 'login' && $_REQUEST['username'] != "" && $_REQUEST['password'] != "") {
   
    $uname = ($_REQUEST['username']);
    $passwd = md5($_REQUEST['password']);
    $query = $this->fpdo->from('customers')->where(array('email' => $uname, 'password' => $passwd))->fetch();
    $active=$query['active'];
    if ($query['id'] != "" && $active==1) {

        $_SESSION['CUSTOMER_Name'] = $query['name'];
        $_SESSION['CUSTOMER_ID'] = $query['id'];

        // echo $pLang;
        $utils->redirect(_PREF . $pLang . "/page58/Payment");
    }else if ($query['id'] !="" && $active==0) {
        $_SESSION['error_account_activation']='error';
    } else {
        $_SESSION['error_login'] = 'error';
    }
}

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
                        $customer_dtls="<table class='table table-bordered' border='0'>";
                        $customer_dtls.="<tr><td><b> Customer Name</b>:</b></td><td> $full_name</td></tr>
                                         <tr><td><b>  E-mail</b>:</td><td> $email</td></tr>
                                         <tr><td><b>  Company</b>:</td><td> $company</td></tr>
                                         <tr><td><b>  City</b>:</td><td> $city</td></tr>
                                         <tr><td><b>  Adress</b>:</td><td> $adress</td></tr>";
                        $customer_dtls.="</table>";
                        $tags2 = array("{customer_name}" => $full_name, '{register-datetime}' => date("Y-m-d h:i:s"), '{customer-details}' =>$customer_dtls);
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
        
if ($_SESSION['CUSTOMER_ID'] != "") {
    $this->redirect(_PREF . $_SESSION['pLang'] . "/page58/Payment");
} else {
    ?>

    <div class="row row-nomargin">
        <div class="col-xs-3">
            <ul id="shopping-cart">
                <li><a href="#">Shopping Cart</a></li>
                <li  class="active-shopping"><a href="#">Checkout</a></li>
                <li><a href="#">Payment</a></li>
                <li><a href="#">Order Details</a></li>

            </ul>
        </div>
        <div class="col-xs-9">
            <div class="row row-nomargin">
                <div class="col-sm-6 nopadding">
                    <h1>New Customer</h1>
                    <div class="login-block sign-up-block">
                        <h1>Sign up</h1>
                        <form class="form-horizontal" role="form" method="post" action="">
                            <?= $successMSG ?>
                            <?= $ErrorMSG ?>
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
                </div>
                <div class="col-sm-6 nopadding">
                    <h1>Old Customer</h1>
                    <div class="login-block">
                        <h1>Login</h1>
                        <form id="AjaxLogin" action="" method="post">
                            <input type="text" value="" placeholder="Username" id="username" name="username" />
                            <input type="password" value="" placeholder="Password" id="password" name="password" />
                            <button type="submit">Login</button><input type='hidden' name='LoginC' value='login'>
                        </form>
                        <? if ($_SESSION['error_login'] != "") { ?>
                            <div class="alert alert-danger error-login">Incorrect email or password</div>
                            <?
                        }
                        unset($_SESSION['error_login']);
                        ?>
                        <?if( $_SESSION['error_account_activation'] !=""){?>
                        <div class="alert alert-danger error-login">Please activate your account.</div>
                        <?
                        }
                        unset( $_SESSION['error_account_activation']);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?
}
?>
