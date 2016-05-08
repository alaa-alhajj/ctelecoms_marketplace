$(document).ready(function() {


    $duration_id = $('#durations').val();
    $group_id = $('#groups').val();
    $dynamic_id = $('#dynamic_price_id').val();

    $.ajax({
        url: _PREF + "GetProductPrice",
        type: 'post',
        data: {duration: $duration_id, group: $group_id, dynamic: $dynamic_id},
        dataType: 'json',
        success: function(data) {

            $('#product_price').html(data[0]);
        },
        error: function(e, msg) {
            console.log(e);
        }
    });


    $('body').on('change', '#groups,#durations', function() {
        $duration_id = $('#durations').val();
        $group_id = $('#groups').val();
        $dynamic_id = $('#dynamic_price_id').val();

        $.ajax({
            url: _PREF + "GetProductPrice",
            type: 'post',
            data: {duration: $duration_id, group: $group_id, dynamic: $dynamic_id},
            dataType: 'json',
            success: function(data) {
                $('#product_price').html(data[0]);


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
        $('.loadImgAdd').append('   <div class="loading"><span>Loading&#8230;</span><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');
        $this = $(this);
         $this.hide();
        $.ajax({
            url: _PREF + "AddToCart",
            type: 'post',
            data: {pro_id: $product_id, duration_id: $duration_id, group_id: $group_id},
            dataType: 'html',
            success: function(data) {
                  $this.parents().find('.loading').remove();
                $('.AddedToCart').hide();
                $('.RemovedFromCart').show();
            }
        });
    });


    $("body").on('click', '.RemovefromCart', function() {
        $product_id = $(this).data('id');
        $this_tr = $(this).data('remove');
       $('.loadImgAdd').append('   <div class="loading"><span>Loading&#8230;</span><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');
      
        $this = $(this);
         $this.hide();
        $.ajax({
            url: _PREF + "RemoveFromCart",
            type: 'post',
            data: {pro_id: $product_id},
            dataType: 'html',
            success: function(data) {
                if ($this_tr !== "") {
                    $this.parents().find('#' + $product_id).fadeOut();
                      $('.RemovedFromCart').hide();
                    $('.AddedToCart').show();
                     $this.parents().find('.loading').remove();
                } else {
                    $('.RemovedFromCart').hide();
                    $('.AddedToCart').show();
                }
            }
        });
    });

    $('body').on('change', '#groups_cart', function() {
        $duration_id = $(this).data('duration');
        $group_id = $(this).val();
        $dynamic_id = $(this).data('dynamic');
        $product_id = $(this).data('product');
        $promo_discount = $(this).data('promo');
           $('#price2_' + $product_id).addClass("colorWhite");
           $('#price2_' + $product_id).append('<div class="loadingPrice"><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');
           
            $('#price_' + $product_id).addClass("colorWhite");
           $('#price_' + $product_id).append('<div class="loadingPrice"><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');
        $.ajax({
            url: _PREF + "GetPriceForShoppingCart",
            type: 'post',
            data: {duration: $duration_id, group: $group_id, dynamic: $dynamic_id, product_id: $product_id},
            dataType: 'json',
            success: function(data) {
                $offer_val = $('#price_' + $product_id).data('offer');
                 $promo_discount =  $('#price_' + $product_id).data('promo');
                $price = data[0] - ($offer_val / 100);
                
                $tot_price_after_discount= $price - ($promo_discount / 100);
                  $('#price2_' + $product_id).find('.loadingPrice').remove();
                  $('#price_' + $product_id).find('.loadingPrice').remove();
                  $('#price2_' + $product_id).removeClass("colorWhite");
                  $('#price_' + $product_id).removeClass("colorWhite");
                $('#price_' + $product_id).html(parseFloat($tot_price_after_discount).toFixed(2));
                $('#price2_' + $product_id).html(data[0]);
                $tot_price = 0;
                $tot_price_before_discount = 0;
                $('.ShoppingCartTable tbody tr').each(function()
                {
                    $this_tr = $(this).attr("id");
                    $tot_price += Number($("#" + $this_tr + " #price_" + $this_tr).html());
                    $tot_price_before_discount += Number($("#" + $this_tr + " #price2_" + $this_tr).html());
                });
                $(".TotalPriceCart").html(parseFloat($tot_price).toFixed(2));parseFloat($tot_price_before_discount).toFixed(2);
                $(".TotalPriceBeforeDiscount").html(parseFloat($tot_price_before_discount).toFixed(2));
            }
        });
    });

    $("body").on('click', '#applay_promocode', function() {
        $code = $('#promoCode-value').val();
        alert($code);
        $this = $(this);
        $.ajax({
            url: _PREF + "ApplayPromoCode",
            type: 'post',
            data: {code: $code},
            dataType: 'json',
            success: function(data) {
              
                if (data[0] === 1 || data[0] === '1') {
                    $products = data[2].split(",");
                  
                    for (var i = 0; i < $products.length; i++) {

                        $old_price = parseFloat($("#price_" + $products[i]).html());
                        $new_price = $old_price - (data[1] / 100);
                        $(".ShoppingCartTable #price_" + $products[i]).html(parseFloat($new_price).toFixed(2));
                    }
                    $tot_price = 0;
                    $('.ShoppingCartTable tbody tr').each(function()
                    {
                        $this_tr = $(this).attr("id");
                        $tot_price += Number($("#" + $this_tr + " #price_" + $this_tr).html());
                    });
                    $(".TotalPriceCart").html(parseFloat($tot_price).toFixed(2));
                    $this.parents().find('.promoCodeTr').fadeOut();
                    $('.thanksPromoMsg').fadeIn();
                } else {
                    openAlert(data[1]);
                }
            }
        });
    });


});