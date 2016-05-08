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
        $this = $(this);
        $.ajax({
            url: _PREF + "AddToCart",
            type: 'post',
            data: {pro_id: $product_id, duration_id: $duration_id, group_id: $group_id},
            dataType: 'html',
            success: function(data) {
                $('.AddedToCart').hide();
                $('.RemovedFromCart').show();
            }
        });
    });


    $("body").on('click', '.RemovefromCart', function() {
        $product_id = $(this).data('id');
        $this_tr = $(this).data('remove');
        alert($product_id);
        $this = $(this);
        $.ajax({
            url: _PREF + "RemoveFromCart",
            type: 'post',
            data: {pro_id: $product_id},
            dataType: 'html',
            success: function(data) {
                if ($this_tr !== "") {
                    $this.parents().find('#' + $product_id).fadeOut();
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
        $.ajax({
            url: _PREF + "GetProductPrice",
            type: 'post',
            data: {duration: $duration_id, group: $group_id, dynamic: $dynamic_id},
            dataType: 'json',
            success: function(data) {

                $('#price_'+$product_id).html(data[0]);


            }
        });
    });


});