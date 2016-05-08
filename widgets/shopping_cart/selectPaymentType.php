<div class="row row-nomargin">
    <div class="col-xs-3">
        <ul id="shopping-cart">
            <li><a href="#">Shopping Cart</a></li>
            <li><a href="#">Checkout</a></li>
            <li class="active-shopping"><a href="#">Payment</a></li>
            <li><a href="#">Order Details</a></li>

        </ul>
    </div>
    <div class="col-xs-9">
        <?
        $shopping_cart = $_SESSION['Shopping_Cart'];
         print_r($shopping_cart);
        global $utils;
        ?>

    </div>
</div>
