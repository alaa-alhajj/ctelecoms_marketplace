$(document).ready(function() {
    $duration_id = $('#durations').val();
    $group_id = $('#groups').val();
    $dynamic_id = $('#dynamic_price_id').val();

    $.ajax({
        url: _PREF + "GetProductPrice",
        type: 'post',
        data: {duration: $duration_id, group: $group_id, dynamic: $dynamic_id},
        dataType: 'html',
        success: function(data) {
            $('#product_price').html(data);


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
            dataType: 'html',
            success: function(data) {
                $('#product_price').html(data);


            }
        });
    });


    /*
     $('#features').children().hide();
     $('#resources').children().hide();
     $('#review').children().hide();
     $('#faq').children().hide();
     $('#addons').children().hide();
     
     $("body").on('click', '.GetProductDetails', function() {
     $product_id = $(this).data('id');
     $get_data = $(this).data('details');
     $this=$(this);
     $('#'+$get_data).children().show();
     $this.removeClass('GetProductDetails');
     $.ajax({
     url: _PREF + "GetProductDetails",
     
     data: {product: $product_id, get_data: $get_data},
     
     success: function(data) {
     
     $('#'+$get_data).find('p').append(data);
     $this.removeClass('GetProductDetails');
     
     
     }
     });
     
     });
     */
    /*Rating*/

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
                $('.count_' + $this_val).html(data);


            }
        });

    });
    $('.rb-rating').rating({'showCaption': false, showClear: false,
        'stars': '5', 'min': '0', 'max': '5', 'value': '2', 'step': '1', 'size': 'xs', 'starCaptions': {0: '0', 1: '1', 2: '2', 3: '3', 4: '4', 5: '5'}});
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
});