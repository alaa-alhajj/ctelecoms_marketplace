<div class="row row-margin">  
<div class='col-sm-12'>
            <h1>All Products</h1>
            <?
            
            $product_details = "<div class='row row-nomargin nopadding'>";
            $products = $this->fpdo->from("products")->where("active='1'")->fetchAll();

            foreach ($products as $allProducts) {
                    $photos = explode(',', $allProducts['photos']);
                    $product_photo = $this->viewPhoto($photos[2], 'crop', 200, 200, 'img', 1, $_SESSION['dots'], 1, '', 'img-responsive width100');
                       $product_details.= '
                                       <div class="col-sm-6 col-md-3">
                                       <div class="thumbnail">
                                       ' . $product_photo . '
                                        <div class="caption">
                                       <h3>' . $allProducts['title'] . '</h3>
                                           <p style="min-height:41px">' .strip_tags($allProducts['brief']) . '</p>
                                       <p> <a href="#" class="btn btn-default" role="button">MORE</a></p>
                                      </div>
                                           </div>
                                               </div>';
                
            }
            $product_details.="</div>";
            echo $product_details;
            ?>
        </div>
</div>