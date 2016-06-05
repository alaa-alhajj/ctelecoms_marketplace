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
    

