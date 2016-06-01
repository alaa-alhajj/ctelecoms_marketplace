<?php
@session_start();
$query = $this->fpdo->from('menus')->where("  p_id=0 and active='1' and lang='" . $_SESSION['pLang'] . "'")->orderBy("item_order ASC")->fetchAll();
$menu_numrows = count($query);

$i = 0;
if ($menu_numrows) {
    $file;
    if ($uri && $uri != 'index.php') {
        //$cur_id = getMenuId($file,$pLang);
        //$parent = getTopParent('menus',$pLang, $cur_id);
        if (!$parent) {

            //$parent_id = getMenuId('menus',$parent_url,$pLang); 
            //$parent = getTopParent('menus',$pLang, $parent_id);
        }
    }
    foreach ($query as $row) {

        $p_id = $row["menu_id"];
        $p_item_name = $row["item_label"];
        $p_item_link = $row["item_link"];

        $item_class = '';

        if ($i == ($menu_numrows - 1)) {
            $item_class2 = 'last-item';
        } else {
            $item_class2 = '';
        }
        if ($i == 0) {
            $item_class3 = 'first-item';
        } else {
            $item_class3 = '';
        }
        if ($p_item_link == "") {
            $p_link = '#';
        } else {
            if ($p_link) {
                $p_link = str_replace('enen/', 'en/', _PREF . 'en' . $p_item_link);
            } else {
                $p_link = _PREF . $p_item_link;
            }
            if ($p_id == $parent) {
                $item_class = "active";
            } else {
                $item_class = "";
            }
        }
        $class1 = '';
        $query2 = $this->fpdo->from('menus')->where("p_id='$p_id' and active='1'")->orderBy("item_order ASC")->fetchAll();
        $menu_numrows2 = count($query2);
        if ($menu_numrows2 > 0) {
            $j = 0;
            $res_menu .='<li class=" main-menu-item dropdown ' . $item_class . ' ' . $item_class2 . ' ' . $item_class3 . '">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">' . $p_item_name . '<span class="caret"></span></a>  <ul class="dropdown-menu">';
            foreach ($query2 as $row2) {
                $sub_id = $row2["menu_id"];
                $sub_item_name = $row2["item_label"];
                $sub_item_link = $row2["item_link"];
                if ($college_id != '') {
                    $arr_link = explode('/', $sub_item_link);
                    $new_link = "";
                    $count = 0;
                    foreach ($arr_link as $li) {
                        if ($count == 0) {
                            $new_link.="College" . $college_id . "/";
                        } else {
                            $new_link.=$li . "/";
                        }
                        $count++;
                    }
                    $new_link = substr($new_link, 0, -1);
                    $sub_item_link = _PREF . 'en' . "/" . $new_link;
                } else {
                    $sub_item_link = _PREF . $sub_item_link;
                }
                $res_menu .= "<li><a href='$sub_item_link'>$sub_item_name</a></li>";
                $j++;
            }
            $res_menu .='</ul></li>';
        } else {

            $res_menu .= "<li class='$item_class $item_class2 $item_class3 '><a href='$p_link'>$p_item_name</a></li>";
        }
        $i++;
    }
}
$shop_list = "";
$shopping_cart = $_SESSION['Shopping_Cart'];

foreach ($shopping_cart as $key => $product) {
    if($product['pro_id'] !=""){
    $product_id = $product['pro_id'];

    $duration_id = $product['duration_id'];
    $duration_name = $this->fpdo->from("pro_price_duration")->where("id='$duration_id'")->fetch();
    $group_id = $product['group_id'];
    $get_pro_name = $this->fpdo->from("products")->where("id='$product_id'")->fetch();
    $photos = explode(',', $get_pro_name['photos']);
    $product_photo = $this->viewPhoto($photos[0], 'crop', 50, 50, 'img', 1, $_SESSION['dots'], 1, '');
    $shop_list.='  <div class="col-sm-12">
                                        <div class="row in-cart">
                                            <div class="col-xs-3 nopadding">' . $product_photo . '</div>
                                            <div class="col-xs-7 "><span>' . $get_pro_name['title'] . '</span></div>
                                            <div class="col-xs-2 nopadding">
                                                <a href="javascript:;" class="RemovefromCart" data-id="'.$product_id.'"><i class="fa fa-times" aria-hidden="true"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    ';
   
    }
}
 $shop_list.='<div class="col-sm-12 show-Cart-btn">
                    <a href="' . _PREF . $_SESSION['pLang'] . "/page50/Shopping-Cart" . '" class="btn-cart">Show Cart</a>
                </div>';

if (count($_SESSION['Shopping_Cart']) > 0) {
$cart=$shop_list;
    $class_shoppingCart = "show-shoppingCart";
} else {
    $cart = "<div class='col-sm-12'><span class='empty-cart'>You Cart is empty<span></div>";
}
?>
<div class="login-mobile">
    <ul class="nav navbar-nav navbar-right login-ul" id="login-ul">
        <? if ($_SESSION['CUSTOMER_ID'] != "") { ?>
            <li> <a href="<?= _PREF . $_SESSION['pLang'] . "/page56/Logout" ?>">Logout <i class="fa fa-lock icon-style-header" aria-hidden="true"></i></a></li>
        <? } else { ?>
            <li class="login_tab"> 
                <a href="javascript:;" class="login">
                    <span>Login</span>
                    <i class="fa fa-lock icon-style-header" aria-hidden="true"></i>
                </a>
                <div class="sign_in ">
                    <form name='ajaxLogin' method="post">
                        <input type="text"  class="text_input" placeholder="Email" id="username" name="username">
                        <input type="password"  class="text_input" placeholder="Password" id="password" name="password">

                        <button type="submit" class="button_login btn" >Sign in</button>
                        <span class='ora'>Or</span>
                        <a href="<?= _PREF . $_SESSION['pLang'] . "/page69/Register" ?>" class="or-signup">Sign up</a>
                    </form>
                </div>
            </li>
        <? } ?>
        <li class="relative">
            <a  href="javascript:;" class="relative show-shoppingCart">Cart
                <i class="fa fa-shopping-cart icon-style-header "></i>
                <span class="CatValue"><?
                    echo count($_SESSION['Shopping_Cart']);
                    ?></span>
            </a>
            <div class=" row row-nomargin shopping-dropdown ">

                <?= $cart ?>

            </div>
        </li>
    </ul> 
</div>

<div class="row">
    <div class="col-sm-3 logo">
        <div>
            <a class="" href="<?= _PREF ?>"> <img src="<?= _ViewIMG ?>logo.png" alt="" class="img-responsive"/></a>
        </div>
    </div>
    <div class="col-sm-9">
        <nav class="navbar costume-nav">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed toggle-btn" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar "></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                    <ul class="nav navbar-nav navbar-right login-ul" id="login-ul">
                        <? if ($_SESSION['CUSTOMER_ID'] != "") { ?>
                            <li> <a href="<?= _PREF . $_SESSION['pLang'] . "/page56/Logout" ?>">Logout <i class="fa fa-lock icon-style-header" aria-hidden="true"></i></a></li>
                        <? } else { ?>
                            <li class="login_tab"> 
                                <a href="javascript:;" class="login">
                                    <span>Login</span>
                                    <i class="fa fa-lock icon-style-header" aria-hidden="true"></i>
                                </a>
                                <div class="sign_in ">
                                    <form name='ajaxLogin' method="post" class='form-signin'>
                                        <input type="text"  class="text_input" placeholder="Email" id="username" name="username">
                                        <input type="password"  class="text_input" placeholder="Password" id="password" name="password">

                                        <button type="submit" class="button_login btn" >Sign in</button>
                                        <span class='ora'>Or</span>
                                        <a href="<?= _PREF . $_SESSION['pLang'] . "/page69/Register" ?>" class="or-signup">Sign up</a>
                                    </form>
                                </div>
                            </li>
                        <? } ?>
                        <li class="relative">

                            <a href="javascript:;" class="relative show-shoppingCart ">Cart
                                <i class="fa fa-shopping-cart icon-style-header "></i>
                                <span class="CatValue"><?
                                    echo count($_SESSION['Shopping_Cart']);
                                    ?></span>
                            </a>
                            <div class=" row row-nomargin shopping-dropdown ">
                                <?= $cart ?>
                                
                            </div>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right nav-ul">
                        <?= $res_menu ?>
                        <li class="search_block relative">
                            <a href="javascript:;" class="btn-search searchID " >
                                <span class="fa fa-search" aria-hidden="true"></span>
                            </a>
                            <div class="search-box">
                                <form action="#" method="post" class='search-form'>
                                    <input name="word" class="textbox" placeholder="Search ..." type="text">
                                    <button type="submit" class="btn btn_search bbtn_2">
                                        <span class="fa fa-search" aria-hidden="true"></span>
                                    </button>
                                </form>
                            </div>

                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
    </div>