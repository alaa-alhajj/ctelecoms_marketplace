<div class="row row-margin">  
<div class='col-sm-12'>
            <h1>All Products</h1>
            <?
            
            $product_details = "<div class='row row-nomargin nopadding'>";
            $products = $this->fpdo->from("products")->where("active='1'")->fetchAll();

            foreach ($products as $allProducts) {
                    $photos = explode(',', $allProducts['photos']);
                    $product_photo = $this->viewPhoto($photos[0], 'crop', 200, 200, 'img', 1, $_SESSION['dots'], 1, '', 'img-responsive width100');
                    $product_details.="<div class='col-sm-4 '>" . $product_photo . ""
                            . "<div>" . $allProducts['title'] . "</div>"
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