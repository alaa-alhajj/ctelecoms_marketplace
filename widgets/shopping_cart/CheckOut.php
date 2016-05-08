<?
@session_start();
if($_SESSION['CUSTOMER_ID'] ===""){
    

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
       
    </div>
</div>
<?
}else{
 //   $this->redirect(_PREF.$_SESSION['pLang']."/page58/Payment");
}
?>
