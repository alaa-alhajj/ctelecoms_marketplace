$(document).ready(function() { 
  $( "#from" ).datepicker({dateFormat: 'yy-mm-dd'});
  $( "#to" ).datepicker({dateFormat: 'yy-mm-dd'});
});

$("#PaymentForm .radio1 input").click(function (){
    $("#PaymentForm .payment_type").hide();
    $("#PaymentForm .bank-type").slideDown();
});

$("#PaymentForm .radio2 input").click(function (){
    $("#PaymentForm .payment_type").hide();
    $("#PaymentForm .E-payment-type").slideDown();
});
    
$(".TryFreeRequest").click(function(){
    var customer_id=$(this).data('customer_id');
    var product_id=$(this).data('product_id');
    $.ajax({
        url:_PREF+"AddProductfreeRequest",
        type:'post',
        data:{
            customer_id:customer_id,
            product_id:product_id}
        ,success:function(data){    
                alert(data);
        }
    });
});

