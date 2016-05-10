<div class="row row-margin">  
<div class='col-sm-12'>
            <h1>All Products</h1>
            <?
            
            $product_details = "<div class='row row-nomargin nopadding'>";
            $products = $this->fpdo->from("products")->where("active='1'")->fetchAll();

            foreach ($products as $allProducts) {
                    $photos = explode(',', $allProducts['photos']);
                    $product_photo = $this->viewPhoto($photos[2], 'crop', 200, 200, 'img', 1, $_SESSION['dots'], 1, '', 'img-responsive width100');
                     $link=_PREF.$_SESSION['pLang']."/page".$allProducts['page_id'] ."/".  $this->rewriteFilter($allProducts['title']);  
                   $check_session_compare = $_SESSION['compareIDs'];
            if ($check_session_compare[$allProducts['id']] != "") {
                $class = 'removeFromCompare added';
            } else {
                $class = "addToCompare";
            }
                     $product_details.= '
                                       <div class="col-sm-6 col-md-3">
                                       <div class="thumbnail">
                                       ' . $product_photo . '
                                        <div class="caption">
                                       <h3>' . $allProducts['title'] . '</h3>
                                           <p style="min-height:41px">' .strip_tags($allProducts['brief']) . '</p>
                                             <div class="row row-margin">
                                        <div class="col-xs-6 nopadding">                                       
                                       <a href="' . $link . '" class="btn btn-default" role="button">MORE</a> 
                                          </div>
                                          <div class="col-xs-6 nopadding">
                                 <a href="javascript:;" class="addToCartSmall small-addToCart">
                                 <i class="fa fa-cart-plus" aria-hidden="true"></i>
                                 </a>     
                                 <a href="javascript:;" class="' . $class . ' small-addToCart" data-id="' . $allProducts['id'] . '" data-small="small">
                                 <i class="fa fa-refresh" aria-hidden="true"></i>
                                 </a>
                                  </div>
                                          </div>
                                    
</div>
                                           </div>
                                               </div>';
                
            }
            $product_details.="</div>";
            echo $product_details;
            ?>
        </div>
</div>