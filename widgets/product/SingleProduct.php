<?php
$page_id = $_REQUEST['id'];
$get_product_id = $this->fpdo->from('products')->where("page_id='" . $_REQUEST['id'] . "'")->fetch();
$product_id = $get_product_id['id'];
$get_product_details = $this->fpdo->from("products")->where("id='$product_id'")->fetch();
$get_groups = $this->fpdo->from('pro_price_groups')->fetchAll();
$groups_select = "<select name='groups' id='groups' >";
foreach ($get_groups as $groups) {
    $groups_select.="<option val='" . $groups['id'] . "'>" . $groups['title'] . "</option>";
}
$groups_select.="</select>";

$get_durations = $this->fpdo->from('pro_price_duration')->fetchAll();
$durations_select = "<select name='durations' id='durations'>";
foreach ($get_durations as $duration) {
    $durations_select.="<option val='" . $duration['id'] . "'>" . $duration['title'] . "</option>";
}
$durations_select.="</select>";
?>
<div class="row row-nomargin">


    <div class="col-sm-6">
        <?
        $photos = explode(',', $get_product_details['photos']);
        $images = "";
        $i = 0;
        foreach ($photos as $pro_photo) {
            if ($i === 1) {
                $width = 350;
                $height = 350;
                $class = "flright";
            } else {
                $width = 75;
                $height = 75;
                $class = "flLeft";
            }
            $bg = $this->viewPhoto($pro_photo, 'crop', $width, $height, 'img', 1, $_SESSION['dots'], 1, '', $class);
            if ($i <= 4) {
                $images.="<div>" . $bg . "</div>";
            }
            $i++;
        }


        echo $images;
        ?>

    </div>
    <div class="col-sm-6">
        <h1>
<?= $get_product_details['title']; ?>
        </h1>
        <p> <?= $get_product_details['brief']; ?></p>
        <div class='row row-nomargin'>
            <div class="col-sm-6">Duration <?= $durations_select; ?></div>
            <div class="col-sm-6">Number of users <?= $groups_select; ?></div>
        </div>

        <div class='row row-nomargin product-price'>

            <div class="col-sm-6">Price 30$</div>
            <div class="col-sm-6 cart-button"><a href='javascript:;'><i class="fa fa-cart-plus" aria-hidden="true"></i> Add to Cart</a></div>
            <div class="col-sm-12 margTop20 freeTrial-button"><a href=''>Free Trial</a></div>
        </div>

    </div>
    <div class='clear'></div>
    <div class='block'>
        <div id="parentHorizontalTab">
            <ul class="resp-tabs-list hor_1">
               <li>Overview</li>
                            <li>Features</li>
                            <li>Resources</li>
                            <li>FAQ</li>
                            <li>Review</li>
                            <li>Add-ons</li>
            </ul>
            <div class="resp-tabs-container hor_1">
                <div>
                    <p>
                        <!--vertical Tabs-->

                    <div id="ChildVerticalTab_1">
                       
                        <div class="resp-tabs-container ver_1">

                            <div>
                                <p>Lorem ipsum dolor sit amet, lerisque commodo. Nam porta cursus lectusconsectetur adipiscing elit. Vestibulum nibh urna, euismod ut ornare non, volutpat vel tortor. Integer laoreet placerat suscipit. Sed sodales sce. Proin nunc erat, gravida a facilisis quis, ornare id lectus</p>
                            </div>
                            <div>
                                <p>Suspendisse blandit velit Integer laoreet placerat suscipit. Sed sodales scelerisque commodo. Nam porta cursus lectus. Proin nunc erat, gravida a facilisis quis, ornare id lectus. Proin consectetur nibh quis Integer laoreet placerat suscipit. Sed sodales scelerisque commodo. Nam porta cursus lectus. Proin nunc erat, gravida a facilisis quis, ornare id lectus. Proin consectetur nibh quis urna gravid urna gravid eget erat suscipit in malesuada odio venenatis.</p>
                            </div>
                            <div>
                                <p>d ut ornare non, volutpat vel tortor. InLorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum nibh urna, euismod ut ornare non, volutpat vel tortor. Integer laoreet placerat suscipit. Sed sodales scelerisque commodo. Nam porta cursus lectus. Proin nunc erat, gravida a facilisis quis, ornare id lectus. Proin consectetur nibh quis urna gravida mollis.t in malesuada odio venenatis.</p>
                            </div>
                        </div>
                    </div>
                    </p>
                    <p>Tab 1 Container</p>
                </div>
                <div>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum nibh urna, euismod ut ornare non, volutpat vel tortor. Integer laoreet placerat suscipit. Sed sodales scelerisque commodo. Nam porta cursus lectus. Proin nunc erat, gravida a facilisis quis, ornare id lectus. Proin consectetur nibh quis urna gravida mollis.
                    <br>
                    <br>
                    <p>Tab 2 Container</p>
                </div>
                <div>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum nibh urna, euismod ut ornare non, volutpat vel tortor. Integer laoreet placerat suscipit. Sed sodales scelerisque commodo. Nam porta cursus lectus. Proin nunc erat, gravida a facilisis quis, ornare id lectus. Proin consectetur nibh quis urna gravida mollis.
                    <br>
                    <br>
                    <p>Tab 3 Container</p>
                </div>
            </div>
        </div>
    </div>

    <div class='col-sm-12'>
        <h1>Related Products</h1>
        <?
        $product_details = "<div class='row row-nomargin nopadding'>";
        $related_products = explode(',', rtrim($get_product_details['related_pro_ids'], ','));

        foreach ($related_products as $products_related) {
            $product_photo = "";
            $get_pro = $this->fpdo->from('products')->where("id='$products_related'")->fetch();
            $photos = explode(',', $get_product_details['photos']);
            $product_photo = $this->viewPhoto($photos[0], 'crop', 200, 200, 'img', 1, $_SESSION['dots'], 1, '', 'img-responsive width100');
            $product_details.="<div class='col-sm-4 '>" . $product_photo . ""
                    . "<div>" . $get_pro['title'] . "</div>"
                    . "<div>aaaaaa</div>"
                    . "<div class='col-sm-12 nopadding'>"
                    . "<a href='' class='more-button'>MORE</a></div>"
                    . "</div>";
        }
        $product_details.="</div>";
        echo $product_details;
        ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        //Horizontal Tab
        $('#parentHorizontalTab').easyResponsiveTabs({
            type: 'default', //Types: default, vertical, accordion
            width: 'auto', //auto or any width like 600px
            fit: true, // 100% fit in a container
            tabidentify: 'hor_1', // The tab groups identifier
            activate: function(event) { // Callback function if tab is switched
                var $tab = $(this);
                var $info = $('#nested-tabInfo');
                var $name = $('span', $info);
                $name.text($tab.text());
                $info.show();
            }
        });

    });
</script>