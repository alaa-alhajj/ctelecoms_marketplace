var bool = true;
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
    function animateMydiv($this_icon) {

        if (bool === true) {
            $this_icon.show();

            $this_icon.animate({'left': 115 + 'px'}, 1000, function() {
                $this_icon.hide();
                $this_icon.animate({'left': '-20px'}, 10, function() {
                });

                animateMydiv($this_icon, true);

            });
        }
    }

    function ChangePrices() {

        $('.ProductDurations').each(function() {
            $dynamic_id = $(this).data('dynamic');
            $product_id = $(this).data('product');
            $duration_id = $('.ProductDurations_' + $product_id).val();
          
            $group_id = $('.ProductGroups_' + $product_id).val();
            if (typeof $group_id === 'undefined') {
                $group = $('.groups_cart_' + $product_id).val();
            } else {
                $group = $group_id;
            }
            $type = $('.groups_cart_' + $product_id).data('type');

            // $('.product_price_' + $product_id).addClass("colorWhite");
            // $('.product_price_' + $product_id).append('<div class="loadingPrice"><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');
            $.ajax({
                url: _PREF + "GetProductPrice",
                type: 'post',
                data: {duration: $duration_id, group: $group, dynamic: $dynamic_id, product: $product_id, type: $type},
                dataType: 'json',
                success: function(data) {

                    $('.product_price_' + data[1]).removeClass("colorWhite");
                    $('.product_price_' + data[1]).html("$" + parseFloat(data[0]).toFixed(2));

                    if (data[4] === 'unit') {
                        $('.groups_cart.groups_cart_' + data[1]).attr({"min": 0, "max": data[2]});

                    }




                }
            });
        });


    }
    function sumPrices() {
        $tot_price = 0;
        $tot_price_before_discount = 0;
        $discount = 0;
        $('.ShoppingCartTable tbody tr').each(function()
        {
            $this_tr = $(this).attr("id");
            $tot_price += Number($("#" + $this_tr + " #price_" + $this_tr).html());
            $tot_price_before_discount += Number($("#" + $this_tr + " #price2_" + $this_tr).html());
            $discount += Number($("#" + $this_tr + " #price_" + $this_tr).data('offer'));
            $discount += Number($("#" + $this_tr + " #price_" + $this_tr).data('promo'));
        });
        $(".TotalPriceCart").html(parseFloat($tot_price).toFixed(2));
        parseFloat($tot_price_before_discount).toFixed(2);
        $(".TotalPriceBeforeDiscount").html(parseFloat($tot_price_before_discount).toFixed(2));
        $(".DiscountOrderCart").html(parseFloat($discount).toFixed(2));
    }

    ChangePrices();
    $('body').on('change', '.ProductGroups,.ProductDurations', function() {
        $dynamic_id = $(this).data('dynamic');
        $product_id = $(this).data('product');
        $duration_id = $('.ProductDurations_' + $product_id).val();
        $group_id = $('.ProductGroups_' + $product_id).val();
        $type = $('.groups_cart_' + $product_id).data('type');
        // $('.product_price_' + $product_id).addClass("colorWhite");
        // $('.product_price_' + $product_id).append('<div class="loadingPrice"><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');
        $.ajax({
            url: _PREF + "GetProductPrice",
            type: 'post',
            data: {duration: $duration_id, group: $group_id, dynamic: $dynamic_id, product: $product_id, type: $type},
            dataType: 'json',
            success: function(data) {

                $('.product_price_' + data[1]).removeClass("colorWhite");
                $('.product_price_' + data[1]).html("$"+parseFloat(data[0]).toFixed(2) );


            }
        });
    });
    /* Get Tabs Details Ajax*/
    $("body").on('click', '.GetProductDetails', function() {
        $(".load-img-details").fadeIn();

        $product_id = $(this).data('id');
        $get_data = $(this).data('details');
        $this = $(this);

        $this.removeClass('GetProductDetails');
        $.ajax({
            url: _PREF + "GetProductDetails",
            data: {product: $product_id, get_data: $get_data},
            success: function(data) {
                $.when($(".load-img-details").fadeOut(200)).done(function() {
                    $('#' + $get_data).html(data);
                    $this.removeClass('GetProductDetails');
                    displayRating();
                    LoadMoreReviews();
                });



            }
        });
    });

    path_hash = window.location.hash;
    arr = path_hash.split('#Details');
    $ReqId = arr[arr.length - 1];
    $product_id = $('#product_id').val();
    if (path_hash === "") {
        $ReqId = '1';
    }

    $get_data = $('.Details_' + $ReqId).attr('id');
    $(".load-img-details").fadeIn();

    $.ajax({
        url: _PREF + "GetProductDetails",
        data: {product: $product_id, get_data: $get_data},
        success: function(data) {
            $.when($(".load-img-details").fadeOut(200)).done(function() {

                $('#' + $get_data).html(data);
                $('.product_details li[data-request="Details_' + $ReqId + '"]').removeClass('GetProductDetails');
                displayRating();
                LoadMoreReviews();
            });



        }
    });

    /*Rating*/
      $('.rating').rating({'showCaption': false, showClear: false,
            'stars': '5', 'min': '0', 'max': '5', 'value': '2', 'step': '1', 'size': 'xs', 'starCaptions': {0: '0', 1: '1', 2: '2', 3: '3', 4: '4', 5: '5'}});
    function displayRating() {
        $('.rb-rating').rating({'showCaption': false, showClear: false,
            'stars': '5', 'min': '0', 'max': '5', 'value': '2', 'step': '1', 'size': 'xs', 'starCaptions': {0: '0', 1: '1', 2: '2', 3: '3', 4: '4', 5: '5'}});
    }
    $("body").on('click', '.rating-stars', function() {
        $this_val = $('.rb-rating').val();
        $product_id = $('#product_id').val();
        $customer_id = $('#customer_login').val();

        $.ajax({
            url: _PREF + "AddRating",
            type: 'post',
            data: {product: $product_id, rate: $this_val, customer: $customer_id},
            dataType: 'html',
            success: function(data) {
                $('.rb-rating').rating('refresh', {
                    disabled: !$('.rb-rating').attr('disabled')
                });



            }
        });

    });


    /*Review*/
    $("body").on('click', '.write-review', function() {
        $product_id = $('#product_id').val();
        $customer_id = $('#customer_login').val();
        $('#WriteReview #customer_id').val($customer_id);
        $('#WriteReview #product_sel').val($product_id);
        $('#WriteReview').modal('show');
    });
    $('#addReviewBtn').click(function() {

        $customer_id = $('#WriteReview #customer_id').val();
        $product_id = $('#WriteReview #product_sel').val();
        $review = $('#WriteReview #review_text').val();
        $.ajax({url: _PREF + "AddReview",
            dataType: 'html',
            type: 'post',
            data: {customer: $customer_id, product: $product_id, review: $review},
            success: function(data) {
                $('#WriteReview').modal('hide');
                openAlert(data);
            }
        });
    });
    /* Load more reviews*/
    function LoadMoreReviews() {
        size_li = $(".comments_review .comment_user").size();
        x = 1;
        $('.comments_review .comment_user:lt(' + x + ')').fadeIn();
        $('#loadMore').click(function() {
            x = (x + 5 <= size_li) ? x + 5 : size_li;
            $('.comments_review .comment_user:lt(' + x + ')').fadeIn();
        });
    }



    function openAlert(message, title)
    {
        $('#Alert_Body').html('');
        $('#title_alert').html('');
        $('#title_alert').html(title);
        $('#Alert_Body').html(message);
        $('#AlertModal').modal('show');
    }

    $('#Details').easyResponsiveTabs({
        type: 'default',
        width: 'auto',
        fit: true,
        tabidentify: 'hor_1',
        activate: function(event) {
            var $tab = $(this);
            var $info = $('#nested-tabInfo');
            var $name = $('span', $info);
            $name.text($tab.text());
            $info.show();
        }
    });
    var openshopping = false;

    $('.show-shoppingCart').click(function(e) {

        if (openshopping === false) {
            $('.search-box').animate({width: '0px'}, 300);
            setTimeout(function() {
                $('.search-box').css('display', 'none');
            }, 250);
            /*$('.search-box .textbox').val('');*/
            opense = false;
            $('.sign_in').hide();
            $('.shopping-dropdown').toggle();
            openshopping = true;
            opensign = false;

        } else {
            $('.shopping-dropdown').toggle();
            openshopping = false;
        }
        e.stopPropagation(e);
    });
    var opense = false;
    $('body').on('click', '.searchID', function(e) {

        if (opense === false) {
            $('.sign_in').hide();
            $('.shopping-dropdown').hide();
            $('.search-box').css('display', 'block').animate({width: '600px'}, 300);
            opense = true;
            openshopping = false;
            opensign = false;
        }
        else {

            $('.search-box').animate({width: '0px'}, 300);
            setTimeout(function() {
                $('.search-box').css('display', 'none');
            }, 250);
            /*$('.search-box .textbox').val('');*/
            opense = false;
        }
        e.stopPropagation();

    });
    var opensign = false;
    $('html').click(function(e) {
        $array_classes = ['sign_in', 'login', 'form-signin', 'text_input', 'button_login', ' row row-nomargin shopping-dropdown ', 'show-shoppingCart', 'relative', 'search-form', 'textbox', 'bbtn_2','row in-cart','col-xs-2 nopadding','col-sm-12 show-Cart-btn','col-xs-7 '];
        if (jQuery.inArray(e.target.className, $array_classes) > -1) {
        } else {
           
            if (opensign === true) {
                $('.sign_in').hide();
                opensign = false;
            }
            else if (openshopping === true) {

                $('.shopping-dropdown').hide();
                openshopping = false;
            }
            else if (opense === true) {
                $('.search-box').animate({width: '0px'}, 300);
                setTimeout(function() {
                    $('.search-box').css('display', 'none');
                }, 250);
                /*  $('.search-box .textbox').val('');*/
                opense = false;
            }
        }
    });
    $('.login').click(function(e) {
        if (opensign === false) {
            $('.shopping-dropdown').hide();
            openshopping = false;
            $('.search-box').animate({width: '0px'}, 300);
            setTimeout(function() {
                $('.search-box').css('display', 'none');
            }, 250);
            /*$('.search-box .textbox').val('');*/
            opense = false;
            $('.sign_in').toggle();
            opensign = true;
        } else {
            $('.sign_in').toggle();
            opensign = false;
        }
        e.stopPropagation(e);
    });

    function changeCartCount($count) {
        $('.CatValue').html($count);
        $('.CatValue').addClass('animated shake');
        setTimeout(function() {
            $('.CatValue').removeClass('animated');
            $('.CatValue').removeClass('shake');
        }, 500);
        $.ajax({url: _PREF + "CheckCart",
            dataType: 'json',
            type: 'post',
            data: {count: $count},
            success: function(data) {
                $(".shopping-dropdown").html(data);
            }
        });
    }
    /*Add To Cart*/
    $("body").on('click', '.addToCart', function() {

        $product_id = $(this).data('id');
        $duration_id = $('.ProductDurations_' + $product_id).val();
        $group_id = $('.ProductGroups_' + $product_id).val();
        if (typeof $group_id === 'undefined') {
            $group = $('.groups_cart_' + $product_id).val();
        } else {
            $group = $group_id;
        }
        $add_Row = "add";
        $this = $(this);
        $this.addClass("pointerEvents");
        $('.AddedToCart').hide();
        $this.parents().find(".load-img[data-id='" + $product_id + "']").fadeIn();
        // $('.loadImgAdd').append('   <div class="loadingAdd"><span>Adding To Cart&#8230;</span><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');
        $this = $(this);
        $this.addClass("pointerEvents");
        $.ajax({
            url: _PREF + "AddToCart",
            type: 'post',
            data: {pro_id: $product_id, duration_id: $duration_id, group_id: $group},
            dataType: 'html',
            success: function(data) {

                $.when($this.parents().find(".load-img[data-id='" + $product_id + "']").fadeOut(200))
                        .done(function() {

                            $('.RemovedFromCart').show();
                        });
                changeCartCount(data);
                $this.parents().find('.loadingAdd').remove();


                $this.removeClass("pointerEvents");
            }
        });
    });


    $("body").on('click', '.RemovefromCart', function() {
        $product_id = $(this).data('id');
        $this_tr = $(this).data('remove');
        $single = $(this).data('single');

        $this = $(this);
        $this.find('.icon').html('');
        $this.addClass("pointerEvents");
        $this.find(".load-img[data-id='" + $product_id + "']").fadeIn();
        if ($this_tr !== "") {
            $this.parents().find('#' + $product_id).addClass('Removing');
            $this.parents().find('#' + $product_id).children('td:nth(3)').append('<span class="span-removing">Deleting&#8230; <i class="fa fa-spinner fa-spin spinner-style" ></i></span>');
        }
        $find_same_pro = $this.parents().find(".RemovefromCart[data-id='" + $product_id + "']");

        $.ajax({
            url: _PREF + "RemoveFromCart",
            type: 'post',
            data: {pro_id: $product_id},
            dataType: 'html',
            success: function(data) {

                if ($this_tr !== "") {
                    $this.parents().find('#' + $product_id).fadeOut();
                    $this.parents().find('#' + $product_id).remove();
                    $this.parents().find('.loadingAdd').remove();
                } else {
                    $('.RemovedFromCart').hide();
                    $('.AddedToCart').show();
                }
                if (typeof $single === 'undefined') {
                    $this.addClass('addToCartSmall');
                    $this.removeClass('RemovefromCart');
                    $.when($this.find(".load-img[data-id='" + $product_id + "']").fadeOut(200))
                            .done(function() {
                                $this.find('.icon').html('<i class="demo-icon iconc-cart-plus icon-style shopCartIcon animated fadeInLeft"></i>');
                            });

                    $find_same_pro.addClass('addToCartSmall');
                    $find_same_pro.removeClass('RemovefromCart');
                }
                $('.RemovedFromCart').hide();
                $('.AddedToCart').show();
                $this.removeClass("pointerEvents");
                sumPrices();
                changeCartCount(data);
            }
        });
    });

    $("body").on('click', '.RemovefromCartSingle', function() {
        $product_id = $(this).data('id');
        $this_tr = $(this).data('remove');
        $this = $(this);
        $this.find('.icon').html('');

        $('.RemovedFromCart').hide();
        $this.addClass("pointerEvents");
        $this.parents().find(".load-img[data-id='" + $product_id + "']").fadeIn();
        if ($this_tr !== "") {
            $this.parents().find('#' + $product_id).addClass('Removing');
            $this.parents().find('#' + $product_id).children('td:nth(3)').append('<span class="span-removing">Deleting&#8230; <i class="fa fa-spinner fa-spin spinner-style" ></i></span>');
        }
        $find_same_pro = $this.parents().find(".RemovefromCart[data-id='" + $product_id + "']");

        $.ajax({
            url: _PREF + "RemoveFromCart",
            type: 'post',
            data: {pro_id: $product_id},
            dataType: 'html',
            success: function(data) {
                $.when($this.parents().find(".load-img[data-id='" + $product_id + "']").fadeOut(200))
                        .done(function() {

                            $('.AddedToCart').show();
                        });
                if ($this_tr !== "") {
                    $this.parents().find('#' + $product_id).fadeOut();
                    $this.parents().find('#' + $product_id).remove();
                    $this.parents().find('.loadingAdd').remove();
                } else {
                    $('.RemovedFromCart').hide();
                    $('.AddedToCart').show();
                }



                $this.removeClass("pointerEvents");
                sumPrices();
                changeCartCount(data);
            }
        });
    });

    /* Add to cart from modal*/
    $("body").on('click', '.addToCartSmall', function() {
        $product_id = $(this).data('id');
        $duration_id = $('.ProductDurations_' + $product_id).val();
        $group_id = $('.ProductGroups_' + $product_id).val();
        if (typeof $group_id === 'undefined') {
            $group = $('.groups_cart_' + $product_id).val();
        } else {
            $group = $group_id;
        }

        $add_Row = "add";
        $this = $(this);
        $this.addClass("pointerEvents");
        $this_icon = $(this).find(".shopCartIcon");
        $this.find('.icon').empty();
        $this.find(".load-img[data-id='" + $product_id + "']").fadeIn();
        bool = true;

        $.ajax({
            url: _PREF + "AddToCart",
            type: 'post',
            data: {pro_id: $product_id, duration_id: $duration_id, group_id: $group, add: $add_Row},
            dataType: 'json',
            success: function(data) {

                $('.ShoppingCartTable').append(data[0]);
                $('#selected_' + $product_id).fadeOut();
                sumPrices();
                $this.removeClass("pointerEvents");
                $.when($this.find(".load-img[data-id='" + $product_id + "']").fadeOut(200))
                        .done(function() {
                            $this.find('.icon').html('<i class="demo-icon iconc-cart icon-style shopCartIcon animated fadeInLeft"></i>');
                        });

                $this.addClass('RemovefromCart');
                $this.removeClass('addToCartSmall');
                changeCartCount(data[1]);
                bool = false;
            }
        });
    });


    /*Add To Compare*/
    $("body").on('click', '.addToCompare', function() {
        $product_id = $(this).data('id');
        $small_btn = $(this).data('small');
        $this = $(this);


        if ($small_btn !== "") {
            $this.addClass("pointerEvents");
            $this.addClass('added');
            $this.find('.icon').empty();
            $this.find(".load-img[data-id='" + $product_id + "']").fadeIn();
        } else {
            $('.loadImgAdd').append('<div class="loadingAdd"><span>Adding To Compare&#8230;</span><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');
            $this.addClass("pointerEvents");
        }


        $.ajax({
            url: _PREF + "AddToCompare",
            type: 'post',
            data: {compare_id: $product_id},
            dataType: 'json',
            success: function(data) {
                if (data === 1 || data === '1') {
                    if ($small_btn !== "") {
                        $this.addClass("removeFromCompare");
                        $this.removeClass("addToCompare");
                        $this.removeClass("pointerEvents");
                        $.when($this.find(".load-img[data-id='" + $product_id + "']").fadeOut(200))
                                .done(function() {
                                    $this.find('.icon').html('<i class="demo-icon iconc-compare icon-style animated fadeInLeft"></i>');
                                });
                    } else {
                        $this.parents().find('.loadingAdd').remove();
                        $('.AddedToCompare').hide();
                        $('.RemovedToCompare').show();
                        $this.removeClass("pointerEvents");
                    }
                } else {
                    $this.removeClass('added');
                    openAlert(data);
                }


            }
        });
    });

    $("body").on('click', '.removeFromCompare', function() {
        $product_id = $(this).data('id');
        $small_btn = $(this).data('small');
        $this = $(this);
        if ($small_btn !== "") {
            $this.removeClass('added');
            $this.find('.icon').empty();
            $this.find(".load-img[data-id='" + $product_id + "']").fadeIn();
        } else {
            $('.loadImgAdd').append('<div class="loadingAdd"><span>Removing From Compare&#8230;</span><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');
        }
        $this.find(".icon-style").addClass("fa-spin");
        $this.addClass("pointerEvents");
        $.ajax({
            url: _PREF + "RemoveFromCompare",
            type: 'post',
            data: {compare_id: $product_id},
            dataType: 'html',
            success: function(data) {
                if ($small_btn !== "") {
                    $this.addClass("addToCompare");
                    $this.removeClass("removeFromCompare");

                } else {
                    $this.parents().find('.loadingAdd').remove();
                    $('.RemovedToCompare').hide();
                    $('.AddedToCompare').show();
                    $this.removeClass("pointerEvents");
                }
                $this.removeClass("pointerEvents");
                $this.find(".icon-style").removeClass('fa-spin');
                $.when($this.find(".load-img[data-id='" + $product_id + "']").fadeOut(200))
                        .done(function() {
                            $this.find('.icon').html('<i class="demo-icon iconc-compare-plus icon-style animated fadeInLeft"></i>');
                        });

            }
        });
    });


    $('body').on('change', '.groups_cart', function() {
        $group_id = $(this).val();
        $dynamic_id = $(this).data('dynamic');
        $product_id = $(this).data('product');
        $promo_discount = $(this).data('promo');
        $duration = $(this).data('duration');
   $('.product_price_' + $product_id).html('');
   $this=$(this);
      $this.parents().find(".load-price-img[data-id='" + $product_id + "']").fadeIn();
if ($duration === "") {
            $duration_id = $('.ProductDurations_' + $product_id).val();
        } else {
            $duration_id = $duration;
        }
        $type = $(this).data('type');

        $('#price2_' + $product_id).addClass("colorWhite");
        $('#price2_' + $product_id).append('<div class="loadingPrice"><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');

        $('#price_' + $product_id).addClass("colorWhite");
        $('#price_' + $product_id).append('<div class="loadingPrice"><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');
        $.ajax({
            url: _PREF + "GetPriceForShoppingCart",
            type: 'post',
            data: {duration: $duration_id, group: $group_id, dynamic: $dynamic_id, product_id: $product_id, type: $type},
            dataType: 'json',
            success: function(data) {

                $offer_val = $('#price_' + $product_id).data('offer');
                $promo_discount = $('#price_' + $product_id).data('promo');
                $price = data[0] - (data[0] * ($offer_val / 100));

                $tot_price_after_discount = $price - ($price * ($promo_discount / 100));
                $('#price2_' + $product_id).find('.loadingPrice').remove();
                $('#price_' + $product_id).find('.loadingPrice').remove();
                $('#price2_' + $product_id).removeClass("colorWhite");
                $('#price_' + $product_id).removeClass("colorWhite");
                $('#price_' + $product_id).html(parseFloat($tot_price_after_discount).toFixed(2));
                $('#price2_' + $product_id).html(parseFloat(data[0]).toFixed(2));
                $.when($this.parents().find(".load-price-img[data-id='" + $product_id + "']").fadeOut(200))
                        .done(function() {

                        $('.product_price_' + $product_id).html("$"+parseFloat(data[0]).toFixed(2));
                        });
                sumPrices();
            }
        });
    });

    $('body').on('keyup', '.groups_cart', function() {
        $group_id = $(this).val();
        $dynamic_id = $(this).data('dynamic');
        $product_id = $(this).data('product');
        $promo_discount = $(this).data('promo');
        $duration = $(this).data('duration');
          $('.product_price_' + $product_id).html('');
             $this=$(this);
      $this.parents().find(".load-price-img[data-id='" + $product_id + "']").fadeIn();

        if ($duration === "") {
            $duration_id = $('.ProductDurations_' + $product_id).val();
        } else {
            $duration_id = $duration;
        }
        $type = $(this).data('type');

        $('#price2_' + $product_id).addClass("colorWhite");
        $('#price2_' + $product_id).append('<div class="loadingPrice"><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');

        $('#price_' + $product_id).addClass("colorWhite");
        $('#price_' + $product_id).append('<div class="loadingPrice"><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');
        $.ajax({
            url: _PREF + "GetPriceForShoppingCart",
            type: 'post',
            data: {duration: $duration_id, group: $group_id, dynamic: $dynamic_id, product_id: $product_id, type: $type},
            dataType: 'json',
            success: function(data) {

                $offer_val = $('#price_' + $product_id).data('offer');
                $promo_discount = $('#price_' + $product_id).data('promo');
                $price = data[0] - (data[0] * ($offer_val / 100));

                $tot_price_after_discount = $price - ($price * ($promo_discount / 100));
                $('#price2_' + $product_id).find('.loadingPrice').remove();
                $('#price_' + $product_id).find('.loadingPrice').remove();
                $('#price2_' + $product_id).removeClass("colorWhite");
                $('#price_' + $product_id).removeClass("colorWhite");
                $('#price_' + $product_id).html(parseFloat($tot_price_after_discount).toFixed(2));
                $('#price2_' + $product_id).html(parseFloat(data[0]).toFixed(2));
                    $.when($this.parents().find(".load-price-img[data-id='" + $product_id + "']").fadeOut(200))
                        .done(function() {

                        $('.product_price_' + $product_id).html("$"+parseFloat(data[0]).toFixed(2));
                        });
              
                sumPrices();
            }
        });
    });

    $("body").on('click', '#applay_promocode', function() {
        $code = $('#promoCode-value').val();
        $('.promoCodeTr ').append('   <div class="loadingPromo"><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');

        $this = $(this);
        $this.addClass("pointerEvents");
        $.ajax({
            url: _PREF + "ApplayPromoCode",
            type: 'post',
            data: {code: $code},
            dataType: 'json',
            success: function(data) {

                if (data[0] === 1 || data[0] === '1') {
                    $products = data[2].split(",");
                    $tot_dis = 0;
                    for (var i = 0; i < $products.length; i++) {

                        $old_price = parseFloat($("#price_" + $products[i]).html());

                        $new_price = $old_price - ($old_price * (data[1] / 100));
                        $tot_dis += Number(data[1]);
                        $(".ShoppingCartTable #price_" + $products[i]).data('promo', data[1]);

                        $(".ShoppingCartTable #price_" + $products[i]).html(parseFloat($new_price).toFixed(2));
                    }
                    sumPrices();
                    $this.parents().find('.promoCodeTr').fadeOut();
                    $('.thanksPromoMsg').fadeIn();
                    $('.promoCodeTr ').find('.loadingPromo').remove();
                } else {
                    $('.ErrorPromoMsg').fadeIn();
                    $('.promoCodeTr ').find('.loadingPromo').remove();
                }
                $this.removeClass("pointerEvents");
            }
        });
    });

    /*select addons in shopping cart*/
    $("body").on('click', '.SelectAddons', function() {
        $product_id = $(this).data('id');
        $('#SelectAddoOns #productID').val($product_id);
        $.ajax({
            url: _PREF + "GetProductAddOns",
            type: "post",
            data: {product_id: $product_id},
            dataType: "json",
            success: function(data) {
                $('#SelectAddoOns .productAddons').html(data);
                ChangePrices();
                $('#SelectAddoOns').modal('show');

            }
        });

    });
    jQuery(function($) {

        //#main-slider
        $(function() {
            $('#main-slider.carousel').carousel({
                interval: 8000
            });
        });


        //Initiat WOW JS
        new WOW().init();

        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('.scrollup').fadeIn();
            } else {
                $('.scrollup').fadeOut();
            }
        });
        $('.scrollup').click(function() {
            $("html, body").animate({scrollTop: 0}, 1000);
            return false;
        });

    });
    /* Ajax login*/
    $("[name='ajaxLogin']").submit(function(event) {
        event.preventDefault();
        $username = $(this).find('#username').val();
        $password = $(this).find('#password').val();

        $.ajax({
            url: _PREF + "AjaxLogin",
            type: "post",
            data: {username: $username, password: $password},
            dataType: "json",
            success: function(data) {
                window.location = data;

            }
        });
    });

    var carousel = $("#owl-demo");
    carousel.owlCarousel({
        items: 6,
        navigation: true,
        navigationText: ["<img src='" + _PREF + "view/includes/css/images/prev.png'>", "<img src='" + _PREF + "view/includes/css/images/next.png'>"]
    });
    /*Tickets*/
    $("body").on('click', '#addTicketBtn', function() {
        $customer_id = $('#addTicketModal #customer_id').val();
        $subject = $('#addTicketModal #subject').val();
        $text = $('#addTicketModal #text').val();

        $.ajax({
            url: _PREF + "AddTicket",
            type: "get",
            data: {customer: $customer_id, subject: $subject, text: $text},
            dataType: "json",
            success: function(data) {
                if (data === 1 || data === '1') {
                    $('#addTicketModal').modal('hide');
                    location.reload();
                }

            }
        });
    });
  
    $("body").on('click', '#addReplyBtn', function() {
        $ticket_id = $('#ticket_id').val();
        $reply = $('#addReplyModal #reply').val();

        $.ajax({
            url: _PREF + "AddTicketReply",
            type: "post",
            data: {ticket: $ticket_id,reply:$reply},
            dataType: "json",
            success: function(data) {
                if (data === 1 || data === '1') {
                    $('#addReplyModal').modal('hide');
                    location.reload();
                }

            }
        });
    });
    
    $('#closeTicketBtn').click(function() {

        var ticket_id = $('#ticket_id').val();

        var knowledg = $('#knowledg').val();
        var friend = $('#friend').val();
        var response = $('#response').val();
         var overall = $('#overall').val();
           var comment = $('#comment').val();

        $.ajax({url: _PREF + "CloseTicketA",
            dataType: 'json',
            type: 'post',
            data: {ticket_id: ticket_id, knowledg: knowledg, friend: friend, response: response,overall:overall,comment:comment},
            success: function(data) {
                $('#CloseTicket').modal('hide');

                if (data === 1) {
                   location.reload();
                }
            }
        });


    });
var counter = 0;
    var attachments = [];
    var btn_name = $('.omb_btn').html();
    $('#attach_upload').uploadifive({
        'auto': true,
        'formData': {'photo': 'something'},
        'queueID': 'queue1',
        'fileSizeLimit': 10000,
        'uploadScript': _PREF + "widgets/UploadPhotoAttachment/uploadifive.php",
        'removeCompleted': false,
        'multi': true,
        'onCancel': function(file) {
            $('.omb_btn').removeClass('disabled');
            $('.omb_btn').html(btn_name);
        },
        'onSelect': function(queue) {
            $('.omb_btn').addClass('disabled');
            $('.omb_btn').html('<i class="fa fa-spinner fa-spin "></i> Waiting');
        },
        'onUploadComplete': function(file, data) {

            if (data !== 'False')
            {
                $('#uploadifive-attach_upload-file-' + counter).attr('data-photo', data);
                attachments.push(data);
                $('#attach').val(attachments);
                $('.omb_btn').removeClass('disabled');
                $('.omb_btn').html(btn_name);
            }
            counter++;
        }, 'onError': function(event, queueID, fileObj, errorObj) {

            $("#attach_upload" + queueID).fadeOut(250, function() {
                $("#attach_upload" + queueID).remove();
            });
            $('.omb_btn').removeClass('disabled');
            $('.omb_btn').html(btn_name);
        }
    });

});