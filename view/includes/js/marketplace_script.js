$(document).ready(function() {

    function ChangePrices() {

        $('.ProductDurations').each(function() {
            $dynamic_id = $(this).data('dynamic');
            $product_id = $(this).data('product');
            $duration_id = $('.ProductDurations_' + $product_id).val();
            $group_id = $('.ProductGroups_' + $product_id).val();
            $('.product_price_' + $product_id).addClass("colorWhite");
            $('.product_price_' + $product_id).append('<div class="loadingPrice"><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');
            $.ajax({
                url: _PREF + "GetProductPrice",
                type: 'post',
                data: {duration: $duration_id, group: $group_id, dynamic: $dynamic_id, product: $product_id},
                dataType: 'json',
                success: function(data) {
                    $('.product_price_' + data[1]).removeClass("colorWhite");
                    $('.product_price_' + data[1]).html(parseFloat(data[0]).toFixed(2)+"$");



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
         $('.product_price_' + $product_id).addClass("colorWhite");
            $('.product_price_' + $product_id).append('<div class="loadingPrice"><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');
        $.ajax({
            url: _PREF + "GetProductPrice",
            type: 'post',
            data: {duration: $duration_id, group: $group_id, dynamic: $dynamic_id, product: $product_id},
            dataType: 'json',
            success: function(data) {

                 $('.product_price_' + data[1]).removeClass("colorWhite");
                    $('.product_price_' + data[1]).html(parseFloat(data[0]).toFixed(2)+"$");


            }
        });
    });
    /* Get Tabs Details Ajax*/
    $("body").on('click', '.GetProductDetails', function() {
        $("#Details").append('   <div class="loading"><span>Loading&#8230;</span><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');

        $product_id = $(this).data('id');
        $get_data = $(this).data('details');
        $this = $(this);

        $this.removeClass('GetProductDetails');
        $.ajax({
            url: _PREF + "GetProductDetails",
            data: {product: $product_id, get_data: $get_data},
            success: function(data) {

                $('#' + $get_data).find('p').append(data);
                $this.removeClass('GetProductDetails');
                $("#Details").find('.loading').remove();
                displayRating();
                LoadMoreReviews();
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

    $.ajax({
        url: _PREF + "GetProductDetails",
        data: {product: $product_id, get_data: $get_data},
        success: function(data) {

            $('#' + $get_data).find('p').append(data);
            $('.product_details li[data-request="Details_' + $ReqId + '"]').removeClass('GetProductDetails');

            displayRating();
            LoadMoreReviews();

        }
    });

    /*Rating*/
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

    /*Add To Cart*/
    $("body").on('click', '.addToCart', function() {
        $product_id = $('#product_id').val();
        $duration_id = $('#durations').val();
        $group_id = $('#groups').val();
        $('.loadImgAdd').append('   <div class="loadingAdd"><span>Adding To Cart&#8230;</span><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');
        $this = $(this);
        $this.addClass("pointerEvents");
        $.ajax({
            url: _PREF + "AddToCart",
            type: 'post',
            data: {pro_id: $product_id, duration_id: $duration_id, group_id: $group_id},
            dataType: 'html',
            success: function(data) {
                $this.parents().find('.loadingAdd').remove();
                $('.AddedToCart').hide();
                $('.RemovedFromCart').show();
                $this.removeClass("pointerEvents");
            }
        });
    });


    $("body").on('click', '.RemovefromCart', function() {
        $product_id = $(this).data('id');
        $this_tr = $(this).data('remove');
        $this = $(this);
        $this.addClass("pointerEvents");
        $('.loadImgAdd').append('<div class="loadingAdd"><span>Removing From Cart&#8230;</span><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');
        if ($this_tr !== "") {
            $this.parents().find('#' + $product_id).addClass('Removing');
            $this.parents().find('#' + $product_id).children('td:nth(3)').append('<span class="span-removing">Deleting&#8230; <i class="fa fa-spinner fa-spin spinner-style" ></i></span>');

        }


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
$('.RemovedFromCart').hide();
                    $('.AddedToCart').show();
                $this.removeClass("pointerEvents");
                sumPrices();
            }
        });
    });

    /* Add to cart from modal*/
    $("body").on('click', '.addToCartSmall', function() {
        $product_id = $(this).data('id');
        $duration_id = $('.ProductDurations_' + $product_id).val();
        $group_id = $('.ProductGroups_' + $product_id).val();
        $add_Row = "add";
        $this = $(this);
        $this.addClass("pointerEvents");
        $this.addClass('added');
        $.ajax({
            url: _PREF + "AddToCart",
            type: 'post',
            data: {pro_id: $product_id, duration_id: $duration_id, group_id: $group_id, add: $add_Row},
            dataType: 'json',
            success: function(data) {
                $this.addClass('added');
                $('.ShoppingCartTable').append(data);
                $('#selected_' + $product_id).fadeOut();
                sumPrices();
                $this.removeClass("pointerEvents");
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
        } else {
            $('.loadImgAdd').append('   <div class="loadingAdd"><span>Adding To Compare&#8230;</span><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');
            $this.addClass("pointerEvents");
        }


        $.ajax({
            url: _PREF + "AddToCompare",
            type: 'post',
            data: {compare_id: $product_id},
            dataType: 'html',
            success: function(data) {
                if ($small_btn !== "") {
                    $this.addClass("removeFromCompare");
                    $this.removeClass("addToCompare");
                    $this.removeClass("pointerEvents");
                } else {
                    $this.parents().find('.loadingAdd').remove();
                    $('.AddedToCompare').hide();
                    $('.RemovedToCompare').show();
                    $this.removeClass("pointerEvents");
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
        } else {
            $('.loadImgAdd').append('   <div class="loadingAdd"><span>Removing From Compare&#8230;</span><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');
        }

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
            }
        });
    });


    $('body').on('change', '#groups_cart', function() {
        $duration_id = $(this).data('duration');
        $group_id = $(this).val();
        $dynamic_id = $(this).data('dynamic');
        $product_id = $(this).data('product');
        $promo_discount = $(this).data('promo');
        $type = $(this).data('type');
        if ($type === 'group')
        {
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
                    sumPrices();
                }
            });
        }
    });

    $('body').on('keyup', '#groups_cart', function() {
        $duration_id = $(this).data('duration');
        $group_id = $(this).val();
        $dynamic_id = $(this).data('dynamic');
        $product_id = $(this).data('product');
        $promo_discount = $(this).data('promo');
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


});